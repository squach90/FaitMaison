<?php
session_start();

require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */
$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo('La recette n\'existe pas');
    return;
}

// On récupère la recette
$retrieveRecipeStatement = $mysqlClient->prepare('SELECT r.* FROM recipes r WHERE r.recipe_id = :id ');
$retrieveRecipeStatement->execute([
    'id' => (int)$getData['id'],
]);
$recipe = $retrieveRecipeStatement->fetch();

if (!$recipe) {
    echo('La recette n\'existe pas');
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaitMaison - <?php echo($recipe['title']); ?></title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <style>
        .recipe-banner {
            width: 100%;
            height: 300px;
            overflow: hidden;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .recipe-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .parent {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(1, 1fr);
            gap: 8px;
        }

        .parent img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
        }

        /* Modal styles */
        #imageModal {
            display: none; /* hidden by default */
            position: fixed;
            z-index: 1000;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
        }

        #imageModal img {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80vh;
            border-radius: 0.5rem;
        }

        #imageModal .close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: white;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        #imageModal .close:hover {
            color: #bbb;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <?php if (!empty($recipe['imagePath'])) : ?>
            <div class="recipe-banner">
                <img src="<?php echo htmlspecialchars($recipe['imagePath']); ?>" alt="Image de la recette">
            </div>
        <?php endif; ?>

        <h1><?php echo($recipe['title']); ?></h1>
        <div class="row">
            <article class="col">
                <?php echo($recipe['recipe']); ?>
            </article>
        </div>
        <div class="parent">
            <?php
            foreach (['galleryImagePath1', 'galleryImagePath2', 'galleryImagePath3'] as $galleryColumn) {
                if (!empty($recipe[$galleryColumn])) {
                    echo '<img src="' . htmlspecialchars($recipe[$galleryColumn]) . '" alt="Image de la recette" class="zoomable">';
                }
            }
            ?>
        </div>

        <!-- Modal pour zoom image -->
        <div id="imageModal">
            <span class="close">&times;</span>
            <img id="modalImg" src="" alt="Image agrandie">
        </div>

        <p><i>Écris par <?php echo($recipe['author']); ?></i></p>
    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>

    <script>
        // Récupérer les éléments
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImg');
        const closeBtn = document.querySelector('#imageModal .close');
        const zoomableImages = document.querySelectorAll('.zoomable');

        zoomableImages.forEach(img => {
            img.addEventListener('click', () => {
                modal.style.display = 'block';
                modalImg.src = img.src;
                modalImg.alt = img.alt;
            });
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Fermer la modal en cliquant en dehors de l'image
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>
