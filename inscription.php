<?php
    require_once('model/user_manager.php');
    session_start();

    try {
        if (isset($_POST['fullname']) && isset($_POST['email']) && isset($_POST['birth-date']) && isset($_POST['password1']) && isset($_POST['password2'])) {
            userInscription($_POST['fullname'], $_POST['email'], null, $_POST['birth-date'], $_POST['password1'], $_POST['password2'], $_FILES['profile_photo']);
        } elseif (isset($_POST['fullname']) && isset($_POST['telephone']) && isset($_POST['birth-date']) && isset($_POST['password1']) && isset($_POST['password2'])) {
            userInscription($_POST['fullname'], null, $_POST['telephone'], $_POST['birth-date'], $_POST['password1'], $_POST['password2'], $_FILES['profile_photo']);
        } elseif (isset($_SESSION['fullname'])) {
            header('Location: profile.php');
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
        <fieldset id="margin-fieldset-inscription" class="five columns">
            <img id="logo-twitter" src="misc/logo-twitter.png" alt="#">
            <form method="POST" enctype="multipart/form-data">
                <h4><strong>Créez votre compte</strong></h4>
                <div>
                    <label class="header">Photo de profil</label>
                    <input id="image" type="file" name="profile_photo" placeholder="Photo" accept="image/png, image/jpeg">
                </div>
                <div class="row">
                    <div>
                        <label for="fullname">Votre nom et prénom</label>
                        <input class="u-full-width" type="text" placeholder="Nom et prénom" name="fullname" id="fullname" required>
                    </div>
                </div>
                <div class="row">
                    <div id="input">
                        <label for="email">Votre adresse mail ou <button id="telephone">téléphone</button></label>
                        <input class="u-full-width" type="email" placeholder="exemple@gmail.com" id="email" name="email" required>
                    </div>
                    <div>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="birth-date">Votre date de naissance</label>
                        <input class="u-full-width" type="date" name="birth-date" id="birth-date" required>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="password1">Mot de passe</label>
                        <input class="u-full-width" type="password" name="password1" id="password1" required>
                    </div>
                    <div>
                        <label for="password2">Comfirmez votre mot de passe</label>
                        <input class="u-full-width" type="password" name="password2" id="password2" required>
                    </div>
                </div>
                <input class="button-primary" type="submit" value="Valider">
                <?php
                if (!empty($error_to_print)) {
                    echo "<ul class='error'>" . $error_to_print . "</ul>";
                }
                ?>
                <br>
                <a href="index.php">Accéder à la page de connexion</a>
                <a href="explore.php" class="float_right">Accueil</a>
            </form>
        </fieldset>
    </main>
    <footer>
    </footer>
    <script src="main.js"></script>
</body>

</html>