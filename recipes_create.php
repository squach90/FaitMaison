<?php
session_start();

require_once(__DIR__ . '/isConnect.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FaitMaison - Ajout de recette</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
        >
    </head>
    <body class="d-flex flex-column min-vh-100">
        <div class="container">

            <?php require_once(__DIR__ . '/header.php'); ?>

            <h1>Ajouter une recette</h1>
            <form action="recipes_post_create.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Titre de la recette</label>
                    <input type="text" class="form-control" id="title" name="title" aria-describedby="title-help">
                    <div id="title-help" class="form-text">Choisissez un titre percutant !</div>
                </div>
                <div class="mb-3">
                    <label for="recipe" class="form-label">Description de la recette</label>
                    <textarea class="form-control" placeholder="Décriver votre recette" id="recipe" name="recipe"></textarea>
                </div>
                <div class="mb-3">
                    <label for="screenshot" class="form-label">Image de Preview (Max: 8Mb)</label>
                    <input type="file" class="form-control" id="screenshot" name="screenshot" />
                </div>
                <div class="mb-3" id="galleryDiv">
                    <label for="galleryImage" class="form-label">Image pour la Gallery (Max: 8Mb)</label>
                    <input type="file" class="form-control" id="galleryImage1" name="galleryImage1" />
                    <input type="file" class="form-control" id="galleryImage2" name="galleryImage2" />
                    <input type="file" class="form-control" id="galleryImage3" name="galleryImage3" />
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>

        <?php require_once(__DIR__ . '/footer.php'); ?>
    </body>

    <style>
        #galleryDiv {
            display: flex;
            flex-wrap: wrap;
            row-gap: 10px;
        }
    </style>
</html>