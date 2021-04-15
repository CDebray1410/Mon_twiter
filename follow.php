<?php
    session_start();

    require_once('model/user_manager.php');

    if (isset($_GET['action']) && $_GET['action'] == "disconnect") {
        disconnect();
    }
    
    if (!empty($_GET['follower_id'])) {
        $follow = showFollower($_GET['follower_id']);
    } elseif (!empty($_GET['following_id'])) {
        $follow = showFollowing($_GET['following_id']);
    }
    
    if (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "follow") {
        follow($_SESSION['user_id'], $_POST['user_follow']);
    } 
    elseif (isset($_POST['user_follow']) && isset($_POST['following']) && $_POST['following'] == "unfollow") {
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
        <?php
        while ($showFollow = $follow->fetch()) {
            ?>
            <div class="row tweet_show">
            <?php
            if($showFollow['picture'] != null) {
                ?>
                <img src="<?php echo $showFollow['picture'] ?>" alt="photo de profil" class="image-profile-home tweet_show__image">
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
                            if ($showFollow['fullname'] != $_SESSION['fullname']) {
                                echo "<a href='profile_tweetos.php?user_id=$showFollow[user_id]'>" . $showFollow['fullname'] . "</a>" . " " . $showFollow['username'];
                                echo buttonFollow($_SESSION['user_id'], $showFollow['user_id']);
                            } else {
                                echo $showFollow['fullname'] . " " . $showFollow['username'];
                            }
                        } else {
                            echo "<a href='profile_tweetos.php?user_id=$showFollow[user_id]'>" . $showFollow['fullname'] . "</a>" . " " . $showFollow['username'];
                        }
                    ?></b></span>
                    <?php
                    if ($showFollow['biography'] != null) {
                        ?>
                        <div>
                            <?php echo "<i>" . $showFollow['biography'] . "</i>" ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
        </fieldset>
    </main>
</body>
</html>