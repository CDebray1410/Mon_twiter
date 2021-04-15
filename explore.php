<?php
session_start();

require_once('model/user_manager.php');
try {
    if (isset($_POST['tweet']) && $_POST['tweet'] != "") {
        sendTweet($_SESSION['user_id'], strip_tags($_POST['tweet']));
        header("Location: explore.php");
    } elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "follow") {
        follow($_SESSION['user_id'], $_POST['user_follow']);
    } elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "unfollow") {
        unfollow($_SESSION['user_id'], $_POST['user_follow']);
    }
} catch (Exception $e) {
    $error_to_print = $e->getMessage();
}

$tweets = showTweets();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
    <br>
    <main class="container">
        <div class="row">
            <?php
            include_once("menu.php");
            ?>
            <div class="nine columns home-center">
                <a class="menu-accueil" href="explore.php">
                    <strong class="<?php echo $_SESSION['theme']; ?>__menu">Explorer</strong>
                </a>
                <br>
                <br>
                <div class="row">
                <?php
                if (isset($_SESSION['fullname']) || !empty($_SESSION['fullname'])) {
                ?>
                    <form method="POST">
                        <div id="form-center">   
                            <?php                    
                                if($_SESSION['picture'] != null) {
                                    ?>
                                    <img src="<?php echo $_SESSION['picture'] ?>" alt="photo de profil" class="image-profile-home">
                                    <?php
                                } else {
                                    ?>
                                    <img src="./misc/nopic.jpg" alt="image d'avatar vide" class="image-profile-home">
                                    <?php
                                }
                            ?>                      
                            <input type="text" id="tweet" name="tweet" placeholder="Quoi de neuf ?" maxlength="140" required>
                        </div>
                        <br>
                        <div>
                            <input type="submit" id="tweeter" value="Tweeter">
                        </div>
                    </form>
                    <?php
                        if (!empty($error_to_print)) {
                            echo "<ul class='error'>" . $error_to_print . "</ul>";
                        }
                    ?>
                <?php
                }
                ?>
                </div>
                    <?php
                    while ($donnees = $tweets->fetch()) {
                        ?>
                        <div class="row tweet_show">
                        <?php
                        if($donnees['picture'] != null) {
                            ?>
                            <img src="<?php echo $donnees['picture'] ?>" alt="photo de profil" class="image-profile-home tweet_show__image">
                            <?php
                        } else {
                            ?>
                            <img src="./misc/nopic.jpg" alt="image d'avatar vide" class="image-profile-home tweet_show__image">
                            <?php
                        }
                        ?>
                            <div>
                                <span><b><?php
                                    if(isset($_SESSION['fullname'])){
                                        if ($donnees['fullname'] != $_SESSION['fullname']) {
                                            echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['tweet_date']));
                                            echo buttonFollow($_SESSION['user_id'], $donnees['user_id']);
                                        } else {
                                            echo $donnees['fullname'] . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['tweet_date']));
                                        }
                                    } else {
                                        echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['tweet_date']));
                                    }
                                ?></b></span>
                                <p><?php echo turnToLink($donnees['content']) ?></p>
                            </div>
                        </div>
                        <hr>
                        <a href="comments.php?id_tweet=<?php echo $donnees['tweet_id'] ?>">Commentaies</a>
                    <?php
                    }
                    ?>
            </div>
        </div>
    </main>
    <footer>
    </footer>

</body>

</html>