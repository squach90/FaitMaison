<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaitMaison - Connexion</title>
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1>Connexion</h1>

        <?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
            <form action="submit_login.php" method="POST">
                <?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['LOGIN_ERROR_MESSAGE'];
                        unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="you@exemple.com">
                    <div id="email-help" class="form-text">L'email utilisé lors de la création de compte.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        <?php else : ?>
            <div class="alert alert-success" role="alert">
                Bonjour <?php echo $_SESSION['LOGGED_USER']['email']; ?> et bienvenue sur le site !
            </div>
        <?php endif; ?>
    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>


