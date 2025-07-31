<!-- inclusion des variables et fonctions -->
<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaitMaison - Page d'accueil</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
    <style>
        .recipe-banner {
            width: 100%;
            height: 200px;
            overflow: hidden;
            border-radius: 0.5rem;
            margin-top: 1rem;
            
        }

        .recipe-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <!-- inclusion de l'entête du site -->
        <?php require_once(__DIR__ . '/header.php'); ?>
            <?php foreach (getRecipes($recipes) as $recipe) : ?>
            <div class="card rounded-4">
                <article class="card-body">
                    <h3><a href="recipes_read.php?id=<?php echo($recipe['recipe_id']); ?>" style="text-decoration: none;"><?php echo htmlspecialchars($recipe['title']); ?></a></h3>
                    <div>
                        <?php
                            // On retire les balises HTML pour la prévisualisation
                            $preview = strip_tags($recipe['recipe']);
                            if (mb_strlen($preview) > 150) {
                                $preview = mb_substr($preview, 0, 150) . '...';
                            }
                            echo htmlspecialchars($preview);
                        ?>
                    </div>
                    <?php if (!empty($recipe['imagePath'])) : ?>
                        <div class="recipe-banner">
                            <img src="<?php echo htmlspecialchars($recipe['imagePath']); ?>" alt="Image de la recette">
                        </div>
                    <?php endif; ?>
                    <i><?php echo htmlspecialchars(displayAuthor($recipe['author'], $users)); ?></i><br>
                    <?php
                        $raw = $recipe['tags'];
                        $json = str_replace("'", '"', $raw);
                        $tags = json_decode($json, true);

                        if (is_array($tags)) {
                            foreach ($tags as $tag) {
                                echo '<span class="badge text-bg-primary" style="margin-right: 5px;">' . htmlspecialchars($tag) . '</span>';
                            }
                        }
                    ?>
                    <?php if (isset($_SESSION['LOGGED_USER']) && $recipe['author'] === $_SESSION['LOGGED_USER']['email']) : ?>
                        <ul class="list-group list-group-horizontal" style="padding-top: 10px">
                            <li class="list-group-item"><a class="link-warning" href="recipes_update.php?id=<?php echo($recipe['recipe_id']); ?>">Editer l'article</a></li>
                            <li class="list-group-item"><a class="link-danger" href="recipes_delete.php?id=<?php echo($recipe['recipe_id']); ?>">Supprimer l'article</a></li>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>
                <br>
            <?php endforeach ?>     
        </div>
        <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
