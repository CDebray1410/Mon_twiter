<?php
    session_start();

    require_once('model/user_manager.php');

    if (isset($_GET['action']) && $_GET['action'] == "disconnect") {
        disconnect();
    }
    elseif (!empty($_GET['user_id'])) {
        $showProfile = showProfile($_GET['user_id']);
    } 
    
    if (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "follow") {
        follow($_SESSION['user_id'], $_POST['user_follow']);
    } 
    elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "unfollow") {
        unfollow($_SESSION['user_id'], $_POST['user_follow']);
    }
    $tweets = showHomeTweets($_GET['user_id']);

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
                    if($showProfile['banner'] != null) {
                        ?>
                        <div class="banner" style="background-image: url('<?php echo $showProfile['banner']; ?>');">
                        <?php
                    } else {
                        ?>
                        <div class="banner">
                        <?php
                    }                    
                            if($showProfile['picture'] != null) {
                                ?>
                                <img src="<?php echo $showProfile['picture'] ?>" alt="image d'avatar" class="banner__img">
                                <?php
                            } else {
                                ?>
                                <img src="./misc/nopic.jpg" alt="image d'avatar vide" class="banner__img">
                                <?php
                            }
                        ?>
                    </div>
                    <!-- : "<a href='follow.php?follower_id={$showProfile['user_id']}'> Followers {$showProfile['followers']}</a></b>" -->
                    <b><?php echo $showProfile['fullname'] ?></b><br>
                    <?php echo $showProfile['username'] ?>
                    <?php echo $follow = ($showProfile['followers'] == 0 || $showProfile['followers'] == null) ? "<b>Followers 0 </b>" : "<a href='follow.php?follower_id={$showProfile['user_id']}'> Followers {$showProfile['followers']}</a></b>"  ?>
                    <?php echo $follow = ($showProfile['following'] == 0 || $showProfile['following'] == null) ? "<b>Following 0 </b>" : "<a href='follow.php?following_id={$showProfile['user_id']}'> Following {$showProfile['following']}</a></b>"  ?>
                    <br>
                    Née le <?php echo $showProfile['birthdate'] ?>. Inscrit depuis <?php echo date("d-m-Y", strtotime($showProfile['register_date'])) ?>

                    <?php
                    if(isset($_SESSION['fullname']) && $showProfile['fullname'] != $_SESSION['fullname']) {
                        echo buttonFollow($_SESSION['user_id'], $showProfile['user_id']);
                    }
                    if ($showProfile['biography'] != null) {
                        ?>
                        <div style="margin-top: 20px;">
                            <?php echo "<i>" . $showProfile['biography'] . "</i>" ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div style="margin-top: 45px;">
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
        </fieldset>
    </main>
</body>
</html>