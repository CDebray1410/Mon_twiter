<?php
session_start();

require_once('model/user_manager.php');

include('model/connection_db.php');

if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    if(isset($_POST['send_message']) && isset($_POST['receiver_name'])) {
        sendMessage($_SESSION['user_id'] ,$_POST['receiver_name'], $_POST['content']);
    } 
    $msg = getMessage($_SESSION['user_id']);
} else {
    header('Location: index.php');
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
                <a class="menu-accueil" href="send.php">
                    <strong class="<?php echo $_SESSION['theme']; ?>__menu">Messages</strong>
                </a>
                <hr>
                <div class="row">
                <?php
                if (isset($_SESSION['fullname']) || !empty($_SESSION['fullname'])) {
                ?>
                    <form method="POST">
                    <div>
                    <input type="text" name="receiver_name" placeholder="@pseudo" required/>
                    </div>
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
                            <input type="text" id="tweet" name="content" placeholder="Envoyer un message" maxlength="140" required>
                        </div>
                        <br>
                        <div>
                            <input type="submit" id="tweeter" name="send_message" value="envoyer">
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
                while ($donnees = $msg->fetch()) {
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
                                        echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['message_date']));
                                        echo buttonFollow($_SESSION['user_id'], $donnees['user_id']);
                                    } else {
                                        echo $donnees['fullname'] . " " . $donnees['username'] . ", le " . date('d-m-Y', strtotime($donnees['message_date']));
                                        echo getReceiver($donnees['receiver_id']);
                                    }
                                } else {
                                    echo "<a href='profile_tweetos.php?user_id=$donnees[user_id]'>" . $donnees['fullname'] . "</a>" . " " . $donnees['username'];
                                }
                            ?></b></span>
                            <p><?php echo turnToLink($donnees['content']) ?></p>
                        </div>
                    </div>
                    <hr>
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



