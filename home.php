<?php
session_start();

require_once('model/user_manager.php');
try {
    if (isset($_POST['tweet']) && $_POST['tweet'] != "") {
        sendTweet($_SESSION['user_id'], strip_tags($_POST['tweet']));
        header("Location: home.php");
    } elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "follow") {
        follow($_SESSION['user_id'], $_POST['user_follow']);
    } elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "unfollow") {
        unfollow($_SESSION['user_id'], $_POST['user_follow']);
    } elseif (!isset($_SESSION['fullname']) || empty($_SESSION['fullname'])) {
        header('Location: index.php');
    }
} catch (Exception $e) {
    $error_to_print = $e->getMessage();
}

$showProfile = getFollowers($_SESSION['user_id']);
$tweets = showHomeTweets($_SESSION['user_id']);

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

                <a class="menu-accueil" href="home.php">
                    <strong class="<?php echo $_SESSION['theme']; ?>__menu">Accueil</strong>
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
                        if($donnees['pictureFollowing'] != null) {
                            ?>
                            <img src="<?php echo $donnees['pictureFollowing'] ?>" alt="photo de profil" class="image-profile-home tweet_show__image">
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
                                        if ($donnees['fullnameFollowing'] != $_SESSION['fullname']) {
                                            echo "<a href='profile_tweetos.php?user_id=$donnees[idFollowing]'>" . $donnees['fullnameFollowing'] . "</a>" . " " . $donnees['usernameFollowing'] . ", le " . date('d-m-Y', strtotime($donnees['tweetDateFollowing']));
                                            echo buttonFollow($_SESSION['user_id'], $donnees['idFollowing']);
                                        } else {
                                            echo $donnees['fullnameFollowing'] . " " . $donnees['usernameFollowing'] . ", le " . date('d-m-Y', strtotime($donnees['tweetDateFollowing']));
                                        }
                                    } else {
                                        echo "<a href='profile_tweetos.php?user_id=$donnees[idFollowing]'>" . $donnees['fullnameFollowing'] . "</a>" . " " . $donnees['usernameFollowing'];
                                    }
                                ?></b></span>
                                <p><?php echo turnToLink($donnees['tweetContentFollowing']) ?></p>
                            </div>
                        </div>
                        <hr>
                        <a href="comments.php?id_tweet=<?php echo $donnees['tweetIdFollowing'] ?>">Commentaies</a>
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