<?php
session_start();

require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

$postData = $_POST;

if (
    !isset($postData['id']) || !is_numeric($postData['id']) ||
    empty($postData['title']) || trim(strip_tags($postData['title'])) === '' ||
    empty($postData['recipe']) || trim($postData['recipe']) === ''
) {
    echo "Il manque des informations pour permettre l'édition du formulaire.";
    return;
}

$allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'heic'];
$maxFileSize = 8000000; // 8 Mo
$uploadDir = __DIR__ . '/images/';

if (!is_dir($uploadDir)) {
    echo "Le dossier images est manquant.";
    return;
}

// Fonction pour vérifier si un chemin d'image existe déjà
function getExistingImagePath($mysqlClient, $filename): ?string {
    $stmt = $mysqlClient->prepare('SELECT imagePath FROM recipes WHERE imagePath = :imagePath');
    $stmt->execute(['imagePath' => './images/' . $filename]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['imagePath'] : null;
}

// Fonction pour gérer l'upload d'une image
function handleImageUpload($mysqlClient, $inputName, $uploadDir, $allowedExtensions, $maxFileSize): ?string {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
        if ($_FILES[$inputName]['size'] > $maxFileSize) {
            echo "Le fichier {$inputName} est trop volumineux.<br>";
            return null;
        }

        $fileInfo = pathinfo($_FILES[$inputName]['name']);
        $extension = strtolower($fileInfo['extension']);

        if (!in_array($extension, $allowedExtensions)) {
            echo "Extension non autorisée pour {$inputName}.<br>";
            return null;
        }

        $filename = uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (getExistingImagePath($mysqlClient, $filename) || file_exists($destination)) {
            return './images/' . $filename;
        }

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $destination)) {
            return './images/' . $filename;
        } else {
            echo "Erreur lors de l'upload de {$inputName}.<br>";
        }
    }

    return null;
}

// Nettoyage des métadonnées d'image
function removeImageMetadata(string $imagePath): void {
    $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            $img = imagecreatefromjpeg($imagePath);
            if ($img) {
                imagejpeg($img, $imagePath, 90);
                imagedestroy($img);
            }
            break;
        case 'png':
            $img = imagecreatefrompng($imagePath);
            if ($img) {
                imagepng($img, $imagePath);
                imagedestroy($img);
            }
            break;
        case 'gif':
            $img = imagecreatefromgif($imagePath);
            if ($img) {
                imagegif($img, $imagePath);
                imagedestroy($img);
            }
            break;
    }
}

// Corrige l'orientation d'une image JPEG via EXIF
function fixImageOrientation(string $filename): void {
    if (!function_exists('exif_read_data')) return;

    $exif = @exif_read_data($filename);
    if (!$exif || !isset($exif['Orientation'])) return;

    $orientation = $exif['Orientation'];
    if ($orientation === 1) return;

    $img = imagecreatefromjpeg($filename);
    if (!$img) return;

    switch ($orientation) {
        case 2: $img = imageflip($img, IMG_FLIP_HORIZONTAL); break;
        case 3: $img = imagerotate($img, 180, 0); break;
        case 4: $img = imageflip($img, IMG_FLIP_VERTICAL); break;
        case 5: $img = imageflip($img, IMG_FLIP_VERTICAL); $img = imagerotate($img, -90, 0); break;
        case 6: $img = imagerotate($img, -90, 0); break;
        case 7: $img = imageflip($img, IMG_FLIP_HORIZONTAL); $img = imagerotate($img, -90, 0); break;
        case 8: $img = imagerotate($img, 90, 0); break;
    }

    imagejpeg($img, $filename, 90);
    imagedestroy($img);
}

// Récupération des anciens chemins d'images
$getOldImages = $mysqlClient->prepare('SELECT imagePath, galleryImagePath1, galleryImagePath2, galleryImagePath3 FROM recipes WHERE recipe_id = :id');
$getOldImages->execute(['id' => $postData['id']]);
$oldImages = $getOldImages->fetch(PDO::FETCH_ASSOC);

// Supprimer les anciennes images si elles ont été remplacées
function deleteOldImage(string $oldPath, ?string $newPath): void {
    if ($newPath && $oldPath && $newPath !== $oldPath) {
        $fullOldPath = __DIR__ . '/' . ltrim($oldPath, './');
        if (file_exists($fullOldPath)) {
            unlink($fullOldPath);
        }
    }
}

// Upload des images
$screenshotPath      = handleImageUpload($mysqlClient, 'screenshot', $uploadDir, $allowedExtensions, $maxFileSize);
$galleryImage1Path   = handleImageUpload($mysqlClient, 'galleryImage1', $uploadDir, $allowedExtensions, $maxFileSize);
$galleryImage2Path   = handleImageUpload($mysqlClient, 'galleryImage2', $uploadDir, $allowedExtensions, $maxFileSize);
$galleryImage3Path   = handleImageUpload($mysqlClient, 'galleryImage3', $uploadDir, $allowedExtensions, $maxFileSize);


deleteOldImage($oldImages['imagePath'],         $screenshotPath);
deleteOldImage($oldImages['galleryImagePath1'], $galleryImage1Path);
deleteOldImage($oldImages['galleryImagePath2'], $galleryImage2Path);
deleteOldImage($oldImages['galleryImagePath3'], $galleryImage3Path);


foreach ([$screenshotPath, $galleryImage1Path, $galleryImage2Path, $galleryImage3Path] as $path) {
    if ($path) {
        $fullPath = __DIR__ . '/' . ltrim($path, './');
        fixImageOrientation($fullPath);
        removeImageMetadata($fullPath);
    }
}

// Nettoyage des données
$id     = (int)$postData['id'];
$title  = trim(strip_tags($postData['title']));
$recipe = trim($postData['recipe']);
$tags = array_map('trim', explode(',', $postData['tags']));

// Ajouter # devant chaque tag s'il n'y est pas déjà
$tags = array_map(function($tag) {
    return strpos($tag, '#') === 0 ? $tag : '#' . $tag;
}, $tags);

// Mise à jour dans la base de données
$updateRecipe = $mysqlClient->prepare('
    UPDATE recipes
    SET title = :title,
        recipe = :recipe,
        imagePath = :imagePath,
        galleryImagePath1 = :galleryImagePath1,
        galleryImagePath2 = :galleryImagePath2,
        galleryImagePath3 = :galleryImagePath3,
        tags = :tags
    WHERE recipe_id = :id
');

$updateRecipe->execute([
    'id'                => $id,
    'title'             => $title,
    'recipe'            => $recipe,
    'imagePath'         => $screenshotPath ?? '',
    'galleryImagePath1' => $galleryImage1Path ?? '',
    'galleryImagePath2' => $galleryImage2Path ?? '',
    'galleryImagePath3' => $galleryImage3Path ?? '',
    'tags'              => json_encode($tags, JSON_UNESCAPED_UNICODE),
]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FaitMaison - Recette modifiée</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1 class="mt-4">Recette modifiée avec succès !</h1>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($title) ?></h5>
                <p class="card-text"><strong>Email :</strong> <?= htmlspecialchars($_SESSION['LOGGED_USER']['email']) ?></p>
                <p class="card-text"><strong>Recette :</strong><br><?= nl2br(htmlspecialchars($recipe)) ?></p>

                <?php if ($screenshotPath): ?>
                    <p><strong>Image principale :</strong><br>
                        <img src="<?= htmlspecialchars($screenshotPath) ?>" class="img-fluid" alt="Image principale">
                    </p>
                <?php endif; ?>

                <p><strong>Galerie d'images :</strong><br>
                <?php foreach ([$galleryImage1Path, $galleryImage2Path, $galleryImage3Path] as $galleryImage): ?>
                    <?php if ($galleryImage): ?>
                        <img src="<?= htmlspecialchars($galleryImage) ?>" class="img-thumbnail me-2 mb-2" style="max-width: 150px;" alt="Image galerie">
                    <?php endif; ?>
                <?php endforeach; ?>
                </p>
            </div>
            <div class="card-footer text-end">
                <a href="./recipes_read.php?id=<?= $id ?>" class="btn btn-primary"><?= htmlspecialchars($title) ?></a>
            </div>
        </div>
    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
