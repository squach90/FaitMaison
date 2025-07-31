<?php
session_start();

require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */
$getData = $_GET;

if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo('Il faut un identifiant de recette pour la modifier.');
    return;
}

$retrieveRecipeStatement = $mysqlClient->prepare('SELECT * FROM recipes WHERE recipe_id = :id');
$retrieveRecipeStatement->execute([
    'id' => (int)$getData['id'],
]);
$recipe = $retrieveRecipeStatement->fetch(PDO::FETCH_ASSOC);

// si la recette n'est pas trouvée, renvoyer un message d'erreur
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaitMaison - Edition de recette</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
</head>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const zoomRange = document.getElementById('zoomRange');
    const image = document.getElementById('previewImage');

    zoomRange.addEventListener('input', () => {
        const scale = zoomRange.value;
        image.style.transform = `scale(${scale})`;
    });
});
</script>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1>Mettre à jour <?php echo($recipe['title']); ?></h1>
        <form action="recipes_post_update.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3 visually-hidden">
                <label for="id" class="form-label">Identifiant de la recette</label>
                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo($getData['id']); ?>">
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Titre de la recette</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="title-help" value="<?php echo($recipe['title']); ?>">
                <div id="title-help" class="form-text">Choisissez un titre percutant !</div>
            </div>
            <div class="mb-3">
                <label for="recipe" class="form-label">Description de la recette</label>
                <textarea class="form-control" placeholder="Seulement du contenu vous appartenant ou libre de droits." id="recipe" name="recipe"><?php echo $recipe['recipe']; ?></textarea>
                <br>
                <label for="title" class="form-label">Tags (a séparer par ", ")</label>
                <input type="text" class="form-control" id="tags" name="tags">
            </div>
            <div class="mb-3">
                <label for="screenshot" class="form-label">Image de Preview (Max: 8Mb)</label>
                <input type="file" class="form-control" id="screenshot" name="screenshot" />


                <?php if (!empty($recipe['imagePath'])) : ?>
                    <small class="form-text text-muted">
                        Image actuelle : <code><?php echo htmlspecialchars($recipe['imagePath']); ?></code>
                    </small>
                <?php else : ?>
                    <small class="form-text text-muted">
                        Aucun fichier sélectionné. Dossier cible : <code>/images</code>
                    </small>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <?php if (!empty($recipe['imagePath'])) : ?>
                    <div class="recipe-banner">
                        <img id="previewImage" src="<?php echo htmlspecialchars($recipe['imagePath']); ?>" alt="Image actuelle">
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-3" id="galleryDiv">
                <label for="galleryImage" class="form-label">Image pour la Gallery (Max: 8Mb)</label>
                <input type="file" class="form-control" id="galleryImage1" name="galleryImage1" />
                <input type="file" class="form-control" id="galleryImage2" name="galleryImage2" />
                <input type="file" class="form-control" id="galleryImage3" name="galleryImage3" />
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
        <br />
    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>

<style>
.recipe-banner {
    width: 100%;
    height: 300px;
    overflow: hidden;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    position: relative;
}

.recipe-banner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.2s ease; /* pour un effet fluide */
}

#galleryDiv {
    display: flex;
    flex-wrap: wrap;
    row-gap: 10px;
}

</style>


</html>
