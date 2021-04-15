<?php
    session_start();

    require_once('model/user_manager.php');

    try {
        $showProfile = getFollowers($_SESSION['user_id']);

        if (isset($_FILES['new_photo'])) {
            changeAccount("photo", $_SESSION['user_id'], $_FILES['new_photo']);
        } elseif (isset($_FILES['new_banner'])) {
            changeAccount("banner", $_SESSION['user_id'], $_FILES['new_banner']);
        } elseif (isset($_POST['new_name'])) {
            changeAccount("username", $_SESSION['user_id'], $_POST['new_name']);
        } elseif (isset($_POST['new_mail'])) {
            changeAccount("email", $_SESSION['user_id'], $_POST['new_mail']);
        } elseif (isset($_POST['new_phone_number'])) {
            changeAccount("phone", $_SESSION['user_id'], $_POST['new_phone_number']);
        } elseif (isset($_POST['new_password'])) {
            changeAccount("pass", $_SESSION['user_id'], $_POST['new_password']);
        } elseif (isset($_POST['new_bio'])) {
            changeAccount("bio", $_SESSION['user_id'], $_POST['new_bio']);
        } elseif (isset($_GET['action']) && $_GET['action'] == "disconnect") {
            disconnect();
        } elseif (!isset($_SESSION['fullname']) || empty($_SESSION['fullname'])) {
            header('Location: index.php');
        }
    } catch (Exception $e) {
        $error_to_print = $e->getMessage();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="skeleton.css">
    <title>Tweet academy</title>
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
    <main class="container">
        <?php
            include_once("menu.php");
        ?>
        <fieldset id="margin-fieldset-profile" class="seven columns">
            <div class="row word_breaker" style="margin-bottom: 30px;">
                <div class="profile">
                    <?php
                    if($_SESSION['banner'] != null) {
                        ?>
                        <div class="banner" style="background-image: url('<?php echo $_SESSION['banner']; ?>');">
                        <?php
                    } else {
                        ?>
                        <div class="banner">
                        <?php
                    }                    
                            if($_SESSION['picture'] != null) {
                                ?>
                                <img src="<?php echo $_SESSION['picture'] ?>" alt="image d'avatar" class="banner__img">
                                <?php
                            } else {
                                ?>
                                <img src="./misc/nopic.jpg" alt="image d'avatar vide" class="banner__img">
                                <?php
                            }
                        ?>
                    </div>
                    <b><?php echo $_SESSION['fullname'] ?></b><br/>
                    <?php echo $_SESSION['username'] ?>
                    <?php echo $follow = ($showProfile['followers'] == 0 || $showProfile['followers'] == null) ? "<b>Followers 0 </b>" : "<a href='follow.php?follower_id={$_SESSION['user_id']}'> Followers {$showProfile['followers']}</a></b>"  ?>
                    <?php echo $follow = ($showProfile['following'] == 0 || $showProfile['following'] == null) ? "<b>Following 0 </b>" : "<a href='follow.php?following_id={$_SESSION['user_id']}'> Following {$showProfile['following']}</a></b>"  ?>
                    <br>
                    Née le <?php echo $_SESSION['birthdate'] ?>. Inscrit depuis le <?php echo date("d-m-Y", strtotime($_SESSION['inscription_date'])) ?>
                    <?php
                    if ($_SESSION['biography'] != null) {
                        ?>
                        <div style="margin-top: 20px;">
                            <?php echo "<i>" . $_SESSION['biography'] . "</i>" ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <img id="logo-twitter" src="misc/logo-twitter.png" alt="#">

            <?php
                if (!empty($error_to_print)) {
                    echo "<ul class='error'>" . $error_to_print . "</ul>";
                }
            ?>
            <form method="POST" enctype="multipart/form-data">
                <h4><strong>Modifier profil</strong></h4>
                <div class="row word_breaker">
                    <div>
                        <label for="new_photo">Changer la photo de profil</label>
                        <input class="input-modification" class="u-full-width" type="file" name="new_photo" id="new_photo" required>
                        <input class="button-primary submit-modification" type="submit" value="Modifier" name="change_photo">
                    </div>
                    <?php
                    if (isset($_GET['changed']) && $_GET['changed'] == 'photo') {

                        echo "<span>Photo de profil changée !</span>";
                    }
                    ?>
                </div>
            </form>
            <form method="POST" enctype="multipart/form-data">
                <div class="row word_breaker">
                    <div>
                        <label for="new_banner">Changer la bannière</label>
                        <input class="input-modification" class="u-full-width" type="file" name="new_banner" id="new_banner" required>
                        <input class="button-primary submit-modification" type="submit" value="Modifier" name="change_banner">
                    </div>
                    <?php
                    if (isset($_GET['changed']) && $_GET['changed'] == 'banner') {

                        echo "<span>Bannière changée !</span>";
                    }
                    ?>
                </div>
            </form>
            <form method="POST">
                <div class="row word_breaker">
                    <div>
                        <label for="new-name">Changer de nom d'utlisateur</label>
                        <input class="input-modification" class="u-full-width" type="text" placeholder="Nom" name="new_name" id="new_name" required>
                        <input class="button-primary submit-modification" type="submit" value="Modifier" name="change_name">
                    </div>
                    <?php
                    if (isset($_GET['changed']) && $_GET['changed'] == 'username') {

                        echo "<span>Pseudo changé !</span>";
                    }
                    ?>
                </div>
            </form>
            <form method="POST">
                <div class="row word_breaker">
                    <div>
                        <label for="new_mail">Changer l'email</label>
                        <input class="input-modification" class="u-full-width" type="email" placeholder="exemple@gmail.com" name="new_mail" id="new_mail" required>
                        <input class="button-primary submit-modification" type="submit" value="Modifier" name="change_mail">
                    </div>
                    <?php
                    if (isset($_GET['changed']) && $_GET['changed'] == 'email') {

                        echo "<span>Email changé !</span>";
                    }
                    ?>
                </div>
            </form>
            <form method="POST">
                <div class="row word_breaker">
                    <div>
                        <label for="new_phone_number">Changer de téléphone</label>
                        <input class="input-modification" class="u-full-width" type="tel" placeholder="numéro de téléphone" name="new_phone_number" id="new_phone_number" pattern="[0-9]{10}" minlength="10" maxlength="10" required>
                        <input class="button-primary submit-modification" type="submit" value="Modifier" name="change_number">
                    </div>
                    <?php
                    if (isset($_GET['changed']) && $_GET['changed'] == 'phone') {

                        echo "<span>Téléphone changé !</span>";
                    }
                    ?>
                </div>
            </form>
            <form method="POST">
                <div class="row word_breaker">
                    <div>
                        <label for="new_password">Changez de mot de passe </label>
                        <input class="input-modification" class="u-full-width" type="password" minlength="8" name="new_password" id="new_password" required>
                        <input class="button-primary submit-modification" type="submit" value="Modifier" name="change_password">
                    </div>
                    <?php
                    if (isset($_GET['changed']) && $_GET['changed'] == 'pass') {

                        echo "<span>Mot de passe changé !</span>";
                    }
                    ?>
                </div>
            </form>
            <form method="POST">
                <div class="row word_breaker">
                    <div>
                        <label for="new_bio">Changez la biographie </label>
                        <input class="input-modification" class="u-full-width" type="text" minlength="8" name="new_bio" id="new_bio" required>
                        <input class="button-primary submit-modification" type="submit" value="Modifier" name="change_bio">
                    </div>
                    <?php
                    if (isset($_GET['changed']) && $_GET['changed'] == 'bio') {

                        echo "<span>Biographie changée !</span>";
                    }
                    ?>
                </div>
            </form>
        </fieldset>
    </main>
</body>
</html>