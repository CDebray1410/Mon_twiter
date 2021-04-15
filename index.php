<?php
    require_once('model/user_manager.php');
    session_start();

    try{

        if (isset($_POST['email']) && isset($_POST['password-connexion'])) {
            userConnexion($_POST['email'], null, $_POST['password-connexion'] );
        } elseif (isset($_POST['telephone']) && isset($_POST['password-connexion'])) {
            userConnexion(null, $_POST['telephone'], $_POST['password-connexion'] );
        } elseif (isset($_SESSION['fullname'])) {
            header('Location: home.php');
        }
    } catch (Exception $e) {
        $error_to_print = $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="skeleton.css">
    <title>Tweet Académie</title>
</head>

<?php 
if(!empty($_SESSION['theme'])) {
    ?>
    <body class="<?php echo $_SESSION['theme']; ?>">
    <?php
} else {
    ?>
    <body>
    <?php
}
?>
    <header>
    </header>
    <main class="container">
        <fieldset id="margin-fieldset-connexion" class="five columns">
            <img id="logo-twitter" src="misc/logo-twitter.png" alt="#">
            <form method="POST">
            <h4><strong>Se connecter à Tweet Académie</strong></h4>
                <div class="row">
                    <div id="input">
                        <label for="identifiant">Entrez votre email ou <button id="telephone">téléphone</button></label>
                        <input class="u-full-width" type="email" placeholder="Email" name="email" id="email" required>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="password-connexion">Mot de passe</label>
                        <input class="u-full-width" type="password" name="password-connexion" id="password-connexion" required>
                    </div>
                </div>
                <input class="button-primary" type="submit" value="Valider">
                <br>
                <a href="inscription.php">S'incrire sur Tweet Académie</a>
                <a href="explore.php" class="float_right">Accueil</a>
            </form>
            <?php
            if (!empty($error_to_print)) {
                echo "<ul class='error'>" . $error_to_print . "</ul>";
            }
            ?>
        </fieldset>
    </main>
    <script src="main.js"></script>
</body>

</html>