<?php
session_start();

require_once('model/user_manager.php');
try {
    if (isset($_GET['id_tweet']) && preg_match('/[0-9]+/', $_GET['id_tweet']) && isset($_POST['commentaire'])) {
        sendTweetComment($_GET['id_tweet'], $_SESSION['user_id'], $_POST['commentaire']);
    } elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "follow") {
        follow($_SESSION['user_id'], $_POST['user_follow']);
    } elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "unfollow") {
        unfollow($_SESSION['user_id'], $_POST['user_follow']);
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
        <div class="row word_breaker">
            <?php
            include_once("menu.php");
            ?>
            <div class="nine columns home-center">
                <a class="menu-accueil" href="home.php">
                    <strong>Accueil</strong>
                </a>
                <br>
                <br>
                <div class="row word_breaker">
                    <?php
                    if (isset($_GET['id_tweet']) && preg_match('/[0-9]+/', $_GET['id_tweet'])) {

                        $current_tweet = getTweet($_GET['id_tweet']);

                        $tweet = $current_tweet->fetch();
                    ?>

                        <div class="row tweet_show word_breaker" style="padding-bottom: 50px;">
                            <?php
                            if ($tweet['picture'] != null) {
                            ?>
                                <img src="<?php echo $tweet['picture'] ?>" alt="photo de profil" class="image-profile-home tweet_show__image">
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
                                        if ($tweet['fullname'] != $_SESSION['fullname']) {
                                            echo "<a href='profile_tweetos.php?user_id=$tweet[user_id]'>" . $tweet['fullname'] . "</a>" . " " . $tweet['username'] . ", le " . date('d-m-Y', strtotime($tweet['tweet_date']));
                                            echo buttonFollow($_SESSION['user_id'], $tweet['user_id']);
                                        } else {
                                            echo $tweet['fullname'] . " " . $tweet['username'] . ", le " . date('d-m-Y', strtotime($tweet['tweet_date']));
                                        }
                                    } else {
                                        echo "<a href='profile_tweetos.php?user_id=$tweet[user_id]'>" . $tweet['fullname'] . "</a>" . " " . $tweet['username'] . ", le " . date('d-m-Y', strtotime($tweet['tweet_date']));
                                    }
                                ?></b></span>
                                <?php
                                ?>
                                <p><?php echo turnToLink($tweet['content']) ?></p>
                            </div>
                        </div>
                        <?php



                        $tweet_comment = getTweetComments($_GET['id_tweet']);

                        while ($donnees = $tweet_comment->fetch()) {
                        ?>
                            <div class="row tweet_show word_breaker">
                                <?php
                                if ($donnees['picture'] != null) {
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
                                                echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['comment_date']));
                                                echo buttonFollow($_SESSION['user_id'], $donnees['user_id']);
                                            } else {
                                                echo $donnees['fullname'] . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['comment_date']));
                                            }
                                        } else {
                                            echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['comment_date']));
                                        }
                                    ?></b></span>
                                    <p><?php echo turnToLink($donnees['content']) ?></p>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        header('Location : index.php');
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['fullname']) || !empty($_SESSION['fullname'])) {
                        ?>
                        <hr>
                        <form method="POST">
                            <input type="text" placeholder="Ajouter un commentaire" name="commentaire" id="commentaire">
                            <input type="submit" value="Envoyer">
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


            </div>
        </div>
        </div>
    </main>
    <footer>
    </footer>

</body>

</html>