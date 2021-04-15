<?php
    if (isset($_GET['action']) && $_GET['action'] == "disconnect") {
        disconnect();
    } elseif (isset($_POST['theme']) && $_POST['theme'] == 'dark') {
        $_SESSION['theme'] = 'dark-theme';
        header("Refresh:0");
    } elseif (isset($_POST['theme']) && $_POST['theme'] == 'light') {
        $_SESSION['theme'] = 'light-theme';
        header("Refresh:0");
    }
?>
<div class="three columns">
    <img id="logo-twitter" src="misc/logo-twitter.png" alt="logo-twitter">
    <br>
    <br>
    <?php
    if (isset($_SESSION['fullname']) || !empty($_SESSION['fullname'])) {
        ?>
        <a class="menu-accueil" href="home.php">
            <strong class="<?php echo $_SESSION['theme']; ?>__menu">Accueil</strong>
        </a>
        <br>
        <br>
        <a class="menu-accueil" href="send.php">
            <strong class="<?php echo $_SESSION['theme']; ?>__menu">Messagerie</strong>
        </a>
        <br>
        <br>
        <?php
    }
    ?>
    <a class="menu-accueil" href="explore.php">
        <strong class="<?php echo $_SESSION['theme']; ?>__menu">Explorer</strong>
    </a>
    <br>
    <br>
    <a class="menu-accueil" href="search.php">
        <strong class="<?php echo $_SESSION['theme']; ?>__menu">Rechercher</strong>
    </a>
    <br>
    <br>
    <?php
    if (isset($_SESSION['fullname']) || !empty($_SESSION['fullname'])) {
        ?>
    <a class="menu-accueil" href="profile.php">
        <strong class="<?php echo $_SESSION['theme']; ?>__menu">Profil</strong>
    </a>
    <?php
    } else {
        ?>
        <a class="menu-accueil" href="index.php">
            <strong class="<?php echo $_SESSION['theme']; ?>__menu">Connexion</strong>
        </a>
        <br>
        <br>
        <a class="menu-accueil" href="inscription.php">
            <strong class="<?php echo $_SESSION['theme']; ?>__menu">Inscription</strong>
        </a>
        <?php
    }
    ?>
    <br>
    <br>
    <form method="POST">
    <input type="submit" name="theme" value="dark" />
    </form>
    <form method="POST">
        <input type="submit" name="theme" value="light" />
    </form>
    <br>
    <br>


    <?php
    if (isset($_SESSION['fullname']) || !empty($_SESSION['fullname'])) {
        ?>
        <div class="menu_infos">
            <?php
                if($_SESSION['picture'] != null) {
                    ?>
                    <img src="<?php echo $_SESSION['picture'] ?>" alt="image d'avatar" id="menu_image">
                    <?php
                } else {
                    ?>
                    <img src="./misc/nopic.jpg" alt="image d'avatar vide" id="menu_image">
                    <?php
                }
            ?>
            <div style="float: left; margin-right: 15px;">
                <b><?php echo $_SESSION['fullname'] ?></b><br/>
                <?php echo $_SESSION['username'] ?><br/>
            </div>
            <a href="profile.php?action=disconnect"><img src="./misc/disconnect.png" alt="Bouton de déconnexion" class="profile__disconnect"></a><br/>
        </div>
        <?php
    }
    ?>
    
</div>