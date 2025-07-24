<?php
session_start();

require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */
$postData = $_POST;

if (
    !isset($postData['id'])
    || !is_numeric($postData['id'])
    || empty($postData['title'])
    || empty($postData['recipe'])
    || trim(strip_tags($postData['title'])) === ''
    || trim($postData['recipe']) === ''
) {
    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return;
}

$allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'heic'];
$maxFileSize = 8000000; // 8 Mo
$uploadDir = __DIR__ . '/images/';

if (!is_dir($uploadDir)) {
    echo "Le dossier images est manquant.";
    return;
}

/**
 * Fonction pour gérer l'upload d'une image, retourne le chemin relatif ou null si erreur.
 */
function handleImageUpload(string $inputName, string $uploadDir, array $allowedExtensions, int $maxFileSize): ?string {
    if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
        if ($_FILES[$inputName]['size'] > $maxFileSize) {
            echo "Le fichier {$inputName} est trop volumineux.<br>";
            return null;
        }
        $fileInfo = pathinfo($_FILES[$inputName]['name']);
        $extension = strtolower($fileInfo['extension']);
        if (!in_array($extension, $allowedExtensions)) {
            echo "Extension {$extension} non autorisée pour {$inputName}.<br>";
            return null;
        }
        // Génération d'un nom unique pour éviter les collisions
        $filename = uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;
        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $destination)) {
            // Retourne le chemin relatif pour insertion en base et affichage
            return './images/' . $filename;
        } else {
            echo "Erreur lors de l'upload de {$inputName}.<br>";
            return null;
        }
    }
    // Pas d'image uploadée, on retourne null
    return null;
}

// Traitement des uploads
$screenshotPath = handleImageUpload('screenshot', $uploadDir, $allowedExtensions, $maxFileSize);
$galleryImage1Path = handleImageUpload('galleryImage1', $uploadDir, $allowedExtensions, $maxFileSize);
$galleryImage2Path = handleImageUpload('galleryImage2', $uploadDir, $allowedExtensions, $maxFileSize);
$galleryImage3Path = handleImageUpload('galleryImage3', $uploadDir, $allowedExtensions, $maxFileSize);

// Nettoyage des données
$id = (int)$postData['id'];
$title = trim(strip_tags($postData['title']));
$recipe = trim($postData['recipe']);

// Préparation et exécution de la mise à jour SQL
$updateRecipe = $mysqlClient->prepare('
    UPDATE recipes
    SET title = :title, recipe = :recipe, imagePath = :imagePath, galleryImagePath1 = :galleryImagePath1, galleryImagePath2 = :galleryImagePath2, galleryImagePath3 = :galleryImagePath3
    WHERE recipe_id = :id
');

$updateRecipe->execute([
    'id' => $id,
    'title' => $title,
    'recipe' => $recipe,
    'imagePath' => $screenshotPath ?? '',
    'galleryImagePath1' => $galleryImage1Path ?? '',
    'galleryImagePath2' => $galleryImage2Path ?? '',
    'galleryImagePath3' => $galleryImage3Path ?? '',
]);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaitMaison - Création de recette</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1>Recette modifiée avec succès !</h1>

        <div class="card">

            <div class="card-body">
                <h5 class="card-title"><?php echo(htmlspecialchars($title)); ?></h5>
                <p class="card-text"><b>Email</b> : <?php echo htmlspecialchars($_SESSION['LOGGED_USER']['email']); ?></p>
                <p class="card-text"><b>Recette</b> : <?php echo nl2br(htmlspecialchars($recipe)); ?></p>

                <?php if ($screenshotPath): ?>
                    <p><b>Image principale :</b><br>
                    <img src="<?php echo htmlspecialchars($screenshotPath); ?>" alt="Image principale" class="img-fluid"></p>
                <?php endif; ?>

                <p><b>Galerie d'images :</b><br>
                <?php
                foreach ([$galleryImage1Path, $galleryImage2Path, $galleryImage3Path] as $galleryImage) {
                    if ($galleryImage) {
                        echo '<img src="' . htmlspecialchars($galleryImage) . '" alt="Image galerie" class="img-thumbnail me-2 mb-2" style="max-width:150px;">';
                    }
                }
                ?>
                </p>
            </div>

            <a href="./recipes_read.php?id=<?php echo $id ?>"><?php echo $title ?></a>
        </div>
    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
