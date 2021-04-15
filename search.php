<?php
session_start();

require_once('model/user_manager.php');
$serchingTweets = null;
$serchingUsers = null;
if (isset($_GET['hashtag']) && $_GET['hashtag'] != "") {
    $serchingTweets = "ok";
    $searchedTweets = searchHashtag($_GET['hashtag']);
} 

if (isset($_POST['user']) && $_POST['user'] != "") {
    $serchingUsers = "ok";
    $searchedUsers = searchUsers($_POST['user']);
} 

if (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "follow") {
    follow($_SESSION['user_id'], $_POST['user_follow']);
} elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "unfollow") {
    unfollow($_SESSION['user_id'], $_POST['user_follow']);
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
        <div class="row">
            <?php
            include_once("menu.php");
            ?>
            <div class="nine columns home-center">
                <br>
                <br>
                <div class="row">
                    <form method="GET">
                        <div id="form-center">
                            <label for="hashtag">Nom de l'hashtag recherché</label>
                            <input type="text" id="hashtag" name="hashtag" placeholder="Entrer le nom de l'hashtag" maxlength="140" required>
                        </div>
                        <br>
                        <div>
                            <input type="submit" id="tweeter" value="Rechercher">
                        </div>
                    </form>
                </div>
                <div class="row">
                    <form method="POST">
                        <div id="form-center">
                            <label for="user">Nom de l'utilisateur recherché</label>
                            <input type="text" id="user" name="user" placeholder="Entrer le pseudo recherché" maxlength="140" required>
                        </div>
                        <br>
                        <div>
                            <input type="submit" id="tweeter" value="Rechercher">
                        </div>
                    </form>
                </div>
                <?php
                
                if ($serchingUsers == "ok" && empty($searchedUsers) ) {
                    ?>
                        <p class="error">Aucune correspondance .</p>
                    <?php
                } else {

                    if (!empty($searchedUsers) && $searchedUsers != null) {
                        while ($donnees = $searchedUsers->fetch()) {
                        ?>
                            <div class="row word_breaker" style="margin-bottom: 30px;">
                                <div class="profile">
                                <?php                  
                                    if($donnees['picture'] != null) {
                                        ?>
                                        <img src="<?php echo $donnees['picture'] ?>" alt="image d'avatar" class="image-profile-home tweet_show__image">
                                        <?php
                                    } else {
                                        ?>
                                        <img src="./misc/nopic.jpg" alt="image d'avatar vide" class="image-profile-home tweet_show__image">
                                        <?php
                                    }
                                    if(isset($_SESSION['fullname'])){
                                        if ($donnees['fullname'] != $_SESSION['fullname']) {
                                            echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a> " . $donnees['username'] . "Inscrit depuis " . date('d-m-Y', strtotime($donnees['register_date']));
                                            echo buttonFollow($_SESSION['user_id'], $donnees['user_id']) . "<br/>";
                                        } else {
                                            echo $donnees['fullname'] . " " . $donnees['username'];
                                        }
                                    } else {
                                        echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a> " . $donnees['username'] . "Inscrit depuis " . date('d-m-Y', strtotime($donnees['register_date'])) . "<br/>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    } elseif (!empty($searchedTweets) && $searchedTweets != null) {
                        while ($donnees = $searchedTweets->fetch()) {
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
                                                echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'];
                                                echo buttonFollow($_SESSION['user_id'], $donnees['user_id']) . "<br/>";
                                            } else {
                                                echo $donnees['fullname'] . " " . $donnees['username'];
                                            }
                                        } else {
                                            echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'];
                                        }
                                    ?></b></span>
                                    <p><?php echo turnToLink($donnees['content']) ?></p>
                                </div>
                            </div>
                            <hr>
                            <a href="comments.php?id_tweet=<?php echo $donnees['tweet_id'] ?>">Commentaies</a>
                        <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
    </main>
    <footer>
    </footer>

</body>

</html>