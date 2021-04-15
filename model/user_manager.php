<?php

function userInscription($fullname, $email = null, $tel = null, $birth_date, $password, $password_check, $image = "") {
    require_once('connection_db.php');

    $fullname_strip = strip_tags($fullname);
    $email_strip = strip_tags($email);
    $tel_strip = strip_tags($tel);
    $pass_chars = strip_tags($password);
    $pass_verif_chars = strip_tags($password_check);
    $image_send = $image;
    $final_check = [];
    $error_array = [];

    $username = "@" . str_replace(' ', '', $fullname_strip);

    if($email != null && $tel == null) {
        $get_mail = $db->prepare('SELECT UPPER(email) FROM users WHERE UPPER(email) = UPPER(:e_mail)');
        $get_mail->execute(
            array(
            'e_mail' => $email_strip
            )
        );
        $check_mail_taken = ($donnees = $get_mail->fetch());
        $get_mail->closeCursor();
    
        if ($check_mail_taken) {
            array_push($final_check, 1);
            $mail_taken = false;
            array_push($error_array ,'<li>L\'email "' . $email_strip . '" est déjà utilisé !</li>');
        } else {
            array_push($final_check, "E");
            $mail_taken = true;
        }
    } elseif ($email == null && $tel != null) {
        if (!preg_match('/0[0-9]{9}/', $tel_strip)) {
            array_push($final_check, 1);
            array_push($error_array ,'<li>Le numéro doit correspondre au format demandé !</li>');
        } else {
            array_push($final_check, "T");
        }
    }

    $current_date = date("Y-m-d");
    $check_diff = date_diff(date_create($birth_date), date_create($current_date));
    $person_age = $check_diff->format('%y');

    if ($person_age <= 12) {
        array_push($final_check, 1);
        array_push($error_array ,'<li>Vous devez avoir 13 ans ou + !</li>');
    } else {
        array_push($final_check, 0);
    }

    if($pass_chars != $pass_verif_chars) {
        array_push($final_check, 1);
        array_push($error_array ,'<li>Le mot de passe et sa vérification doivent être identique !</li>');
    } else {
        array_push($final_check, 0);
    }

    if($image_send['name'] != "") {
        $image_send = $_FILES['profile_photo'];
        move_uploaded_file($image_send['tmp_name'], "images_profil/" . $image_send['name']);
        $image = "./images_profil/" . $image_send['name'];
    } else {
        $image = "";
    }

    $salt = 'vive le projet tweet_academy';
    $salty_pass = $pass_chars.$salt;
    $pass_hache = hash('ripemd160', $salty_pass);

    if (implode('', $final_check) == 'E00') {

        $member = $db->prepare('INSERT INTO users(fullname, birthdate, phone, email, password, picture, banner, biography, username) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $created_account = $member->execute(
            array(
                $fullname_strip,
                $birth_date,
                "",
                $email_strip,
                $pass_hache,
                $image,
                "",
                "",
                $username
            )
        );

        header('Location: index.php');
    } elseif(implode('', $final_check) == 'T00') {

        $member = $db->prepare('INSERT INTO users(fullname, birthdate, phone, email, password, picture, banner, biography, username) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $created_account = $member->execute(
            array(
                $fullname_strip,
                $birth_date,
                $tel_strip,
                "",
                $pass_hache,
                $image,
                "",
                "",
                $username
            )
        );

        header('Location: index.php');
    } else {
        $error_print = implode("", $error_array);
        throw new Exception($error_print);
    }
}

function userConnexion($email = null, $tel = null, $pass) {
    require_once('connection_db.php');
    $email_strip = strip_tags($email);
    $pass_strip = strip_tags($pass);
    $tel_strip = strip_tags($tel);

    if($email != null && $tel == null) {
        $get_email_password = $db->prepare('SELECT * FROM users WHERE email = :e_mail');
        $get_email_password->execute(
            array(
            'e_mail' => $email_strip
            )
        );

        $result= $get_email_password->fetch();

        $get_email_password->closeCursor();

        if (!$result) {
            throw new Exception('<li>Mauvais email ou mot de passe !</li>');
        } else {


            $salt = 'vive le projet tweet_academy';
            $salty_pass = $pass_strip.$salt;
            $pass_hache = hash('ripemd160', $salty_pass);

            if ($pass_hache == $result['password']) {
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['fullname'] = $result['fullname'];
                $_SESSION['birthdate'] = $result['birthdate'];
                $_SESSION['phone'] = $result['phone'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['picture'] = $result['picture'];
                $_SESSION['banner'] = $result['banner'];
                $_SESSION['biography'] = $result['biography'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['inscription_date'] = $result['register_date'];
                $_SESSION['theme'] = "light-theme";

                header('Location: home.php');
            } else {
                throw new Exception('<li>Mauvais email ou mot de passe !</li>');
            }
        }
    } elseif ($email == null && $tel != null) {
        $get_tel_password = $db->prepare('SELECT * FROM users WHERE phone = :phone');
        $get_tel_password->execute(
            array(
            'phone' => $tel_strip
            )
        );

        $result= $get_tel_password->fetch();

        $get_tel_password->closeCursor();

        if (!$result) {
            throw new Exception('<li>Mauvais téléphone ou mot de passe !</li>');
        } else {


            $salt = 'vive le projet tweet_academy';
            $salty_pass = $pass_strip.$salt;
            $pass_hache = hash('ripemd160', $salty_pass);

            if ($pass_hache == $result['password']) {
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['fullname'] = $result['fullname'];
                $_SESSION['birthdate'] = $result['birthdate'];
                $_SESSION['phone'] = $result['phone'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['picture'] = $result['picture'];
                $_SESSION['banner'] = $result['banner'];
                $_SESSION['biography'] = $result['biography'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['inscription_date'] = $result['register_date'];

                header('Location: home.php');
            } else {
                throw new Exception('<li>Mauvais téléphone ou mot de passe !</li>');
            }
        }
    }
}

function disconnect() {
    $_SESSION = array();
    session_destroy();

    header('Location: index.php');
}

function changeAccount($selection, $user_id, $new_value) {
    require('connection_db.php');
    switch($selection) {
        case 'photo' :
            if($new_value['name'] != "") {
                move_uploaded_file($new_value['tmp_name'], "images_profil/" . $new_value['name']);
                $image = "./images_profil/" . $new_value['name'];

                $change_picture = $db->prepare('UPDATE users SET picture = :picture WHERE user_id = :user_id');
                $change_picture->execute(
                    array(
                    'picture' => $image,
                    'user_id' => $user_id
                    )
                );
                $_SESSION['picture'] = $image;
                header('Location: profile.php?changed=photo');
            } else {
                header('Location: profile.php');
            }
        break;
        case 'banner' :
            if($new_value['name'] != "") {
                move_uploaded_file($new_value['tmp_name'], "images_profil/" . $new_value['name']);
                $image = "./images_profil/" . $new_value['name'];

                $change_header = $db->prepare('UPDATE users SET banner = :banner WHERE user_id = :user_id');
                $change_header->execute(
                    array(
                    'banner' => $image,
                    'user_id' => $user_id
                    )
                );
                $_SESSION['banner'] = $image;
                header('Location: profile.php?changed=banner');
            } else {
                header('Location: profile.php');
            }
        break;
        case 'username' :
            $fullname_strip = strip_tags($new_value);
            $username;
            if (preg_match("/^@/", $fullname_strip)) {
                $username = str_replace(' ', '', $fullname_strip);
            } else {
                $username = "@" . str_replace(' ', '', $fullname_strip);
            }

            $getUsernames = $db->prepare('SELECT username FROM users WHERE username = ?');
            $getUsernames->execute(array($username));
            $checkUsername = $getUsernames->fetch();

            if ($checkUsername) {
                throw new Exception('<li>Ce nom d\'utilisateur est déjà pris !</li>');
            } else {
                $change_pass = $db->prepare('UPDATE users SET username = :username WHERE user_id = :user_id');
                $change_pass->execute(
                    array(
                    'username' => $username,
                    'user_id' => $user_id
                    )
                );
                $_SESSION['username'] = $username;
                header('Location: profile.php?changed=username');
            }
        break;
        case 'pass' :
            $pass_chars = strip_tags($new_value);

            $salt = 'vive le projet tweet_academy';
            $salty_pass = $pass_chars.$salt;
            $pass_hache = hash('ripemd160', $salty_pass);

            $change_pass = $db->prepare('UPDATE users SET password = :password WHERE user_id = :user_id');
            $change_pass->execute(
                array(
                'password' => $pass_hache,
                'user_id' => $user_id
                )
            );
            header('Location: profile.php?changed=pass');
        break;
        case 'email' :
            $email_strip = strip_tags($new_value);
            $get_mail = $db->prepare('SELECT UPPER(email) FROM users WHERE UPPER(email) = UPPER(:e_mail)');
            $get_mail->execute(
                array(
                'e_mail' => $email_strip
                )
            );
            $check_mail_taken = ($donnees = $get_mail->fetch());
            $get_mail->closeCursor();

            if ($check_mail_taken) {
                throw new Exception('<li>Cet email est déjà utilisé !</li>');
            } else {
                $change_mail = $db->prepare('UPDATE users SET email = :e_mail WHERE user_id = :user_id');
                $change_mail->execute(
                    array(
                    'e_mail' => $email_strip,
                    'user_id' => $user_id
                    )
                );
                $_SESSION['e_mail'] = $email_strip;
                header('Location: profile.php?changed=email');
            }
        break;
        case 'phone' :
            $change_tel = $db->prepare('UPDATE users SET phone = :phone WHERE user_id = :user_id');
            $change_tel->execute(
                array(
                'phone' => $new_value,
                'user_id' => $user_id
                )
            );
            header('Location: profile.php?changed=phone');
        break;
        case 'bio' :
            $new_value_strip = strip_tags($new_value);

            $change_bio = $db->prepare('UPDATE users SET biography = :biography WHERE user_id = :user_id');
            $change_bio->execute(
                array(
                'biography' => $new_value_strip,
                'user_id' => $user_id
                )
            );
            $_SESSION['biography'] = $new_value_strip;
            header('Location: profile.php?changed=bio');
        break;
    }
}

function sendTweet($user_id = null, $content = null) {
    require('connection_db.php');

    if (strlen($content) >= 140) {
        throw new Exception('<li>Le tweet ne doit pas contenir plus de 140 caractères !</li>');
    } elseif($user_id != null && $content != null && $content != "") {
        $content_strip = strip_tags($content);
        $message = $content_strip;


        $create_tweet= $db->prepare('INSERT INTO tweets(user_id, content) VALUES(:user_id, :content)');
        $create_tweet->execute(array(
            'user_id' => $user_id,
            'content' => $message
        ));

        $getTweetId = $db->prepare('SELECT * FROM tweets WHERE user_id = :user_id AND content = :content ORDER BY user_id DESC LIMIT 1');
        $getTweetId->execute(
            array(
                'user_id' => $user_id,
                'content' => $message
            )
        );
        $getCurrentTweet = ($donnees = $getTweetId->fetch());

        $pattern = "/#[a-zA-Z0-9_-]+/";

        preg_match_all($pattern, $message, $hashtag_array);
        foreach($hashtag_array[0] as $value) {
            createHashtag($value, $getCurrentTweet['tweet_id']);
        }
    }
}

function showTweets() {
    require('connection_db.php');
    $getTweets = $db->query('SELECT t.tweet_id, t.user_id tweet_user_id, t.content content, t.tweet_date, u.user_id user_id, u.fullname, u.picture, u.username FROM tweets t INNER JOIN users u ON u.user_id = t.user_id ORDER BY t.tweet_date DESC');
    return $getTweets;
}
function showHomeTweets($user_id) {
    require('connection_db.php');
    $getHomeTweets = $db->prepare('SELECT * FROM (SELECT users.fullname AS fullnameFollowing, users.username AS usernameFollowing, tweets.tweet_id AS tweetIdFollowing, tweets.content AS tweetContentFollowing, tweets.tweet_date AS tweetDateFollowing, users.picture AS pictureFollowing, users.user_id AS idFollowing FROM tweets JOIN follows ON tweets.user_id = follows.user_id JOIN users ON follows.user_id = users.user_id WHERE follows.follower_id = :user_id UNION SELECT users.fullname, users.username, tweets.tweet_id, tweets.content, tweets.tweet_date, users.picture, users.user_id FROM users JOIN tweets ON users.user_id = tweets.user_id WHERE users.user_id = :user_id) AS test ORDER BY tweetDateFollowing DESC ');
    $getHomeTweets->execute(
        array(
            'user_id' => $user_id
        )
    );
    return $getHomeTweets;
}

function getTweet($tweet_id) {
    require('connection_db.php');
    $tweet_id_strip = strip_tags($tweet_id);

    $getTweet = $db->prepare('SELECT t.tweet_id, t.user_id tweet_user_id, t.content content, t.tweet_date, u.user_id user_id, u.fullname, u.picture, u.username FROM tweets t INNER JOIN users u ON u.user_id = t.user_id WHERE t.tweet_id = ?');
    $getTweet->execute(array($tweet_id_strip));
    return $getTweet;
}

function getTweetComments($tweet_id) {
    require('connection_db.php');
    $tweet_id_strip = strip_tags($tweet_id);

    if(!empty($tweet_id)) {
        $getTweetComments = $db->prepare('SELECT c.comment_id, c.tweet_id, c.user_id, c.content, c.comment_date, u.user_id user_id, u.fullname, u.picture, u.username FROM comments c INNER JOIN users u ON u.user_id = c.user_id WHERE c.tweet_id = ? ORDER BY c.comment_date DESC');
        $getTweetComments->execute(array($tweet_id_strip));
        return $getTweetComments;
    }
}

function sendTweetComment($tweet_id, $user_id, $content) {
    require('connection_db.php');
    if (strlen($content) >= 140) {
        throw new Exception('<li>Le commentaire ne doit pas contenir plus de 140 caractères !</li>');
    } elseif(!empty($tweet_id) && !empty($user_id) && !empty($content) && $content != "") {
        $content_strip = strip_tags($content);
        $tweet_id_strip = strip_tags($tweet_id);
        $message = $content_strip;

        $pattern = "/#[a-zA-Z0-9_-]+/";

        preg_match_all($pattern, $message, $hashtag_array);;
        foreach($hashtag_array[0] as $value) {
            createHashtag($value, $tweet_id);
        }

        $createComment= $db->prepare('INSERT INTO comments(tweet_id, user_id, content) VALUE(:tweet_id, :user_id, :content)');
        $createComment->execute(array(
            'tweet_id' => $tweet_id_strip,
            'user_id' => $user_id,
            'content' => $message
        ));
    }
    $createComment->closeCursor();

    header("Refresh:0");
}

function createHashtag($hashtag, $id_tweet) { // gère 1 hashtag à la fois, donc on peut vérif si il correspond à un déjà crée direct dedans
    require('connection_db.php');
    $hashtag_strip = strip_tags($hashtag);

    $getHashtag = $db->prepare('SELECT hashtag FROM hashtags WHERE hashtag = :hashtag');
    $getHashtag->execute(
        array(
        'hashtag' => $hashtag_strip
        )
    );
    $checkHashtagTaken = ($donnees = $getHashtag->fetch());

    if (!$checkHashtagTaken) {
        $createHashtag= $db->prepare('INSERT INTO hashtags(hashtag) VALUE(:hashtag)');
        $createHashtag->execute(array(
            'hashtag' => $hashtag_strip
        ));

    }
    // TEST GET CURRENT HASHTAG POUR LE LINK, ON récup l'id qui viens d'être crée , puis on le link au tweet
    $getHashtagNew = $db->prepare('SELECT * FROM hashtags WHERE hashtag = :hashtag');
    $getHashtagNew->execute(
        array(
        'hashtag' => $hashtag_strip
        )
    );

    $checkHashtagLink = ($donnees = $getHashtagNew->fetch());

    createHashtagLink ($id_tweet, $checkHashtagLink['hashtag_id']);

}

function createHashtagLink ($tweet_id, $hashtag_id) {
    require('connection_db.php');

    $getHashtagLink = $db->prepare('SELECT * FROM tweets_hashtags WHERE tweet_id = :tweet_id AND hashtag_id = :hashtag_id');
    $getHashtagLink->execute(
        array(
        'tweet_id' => $tweet_id,
        'hashtag_id' => $hashtag_id
        )
    );
    $checkHashtagTaken = ($donnees = $getHashtagLink->fetch());

    if (!$checkHashtagTaken) {
        $createHashtagLink = $db->prepare('INSERT INTO tweets_hashtags(tweet_id, hashtag_id) VALUE(:tweet_id , :hashtag_id)');
        $createHashtagLink->execute(array(
            'tweet_id' => $tweet_id,
            'hashtag_id' => $hashtag_id,
        ));
    }
}

function searchHashtag($hashtag) {
    require('connection_db.php');
    if (preg_match("/^#/", $hashtag)) {
        $hashtag_strip = strip_tags($hashtag);
    } else {
        $hashtag_strip = "#" . strip_tags($hashtag);
    }

    $getHashtagId = $db->prepare('SELECT * FROM hashtags WHERE hashtag = ?'); // Récupère l'id_hashtag grâce au hashtag
    $getHashtagId->execute(array($hashtag_strip));
    if($fetchHashtagId = ($donnees = $getHashtagId->fetch())) {
        $getTweetsByHashtag = $db->prepare('SELECT * FROM hashtags JOIN tweets_hashtags ON hashtags.hashtag_id = tweets_hashtags.hashtag_id JOIN tweets ON tweets_hashtags.tweet_id = tweets.tweet_id JOIN users ON tweets.user_id = users.user_id WHERE tweets_hashtags.hashtag_id = ?');
        $getTweetsByHashtag->execute(array($fetchHashtagId['hashtag_id']));
        return $getTweetsByHashtag;
    }
}

function searchUsers($user) {
    require('connection_db.php');
    if (preg_match("/^@/", $user)) {
        $user_strip = strip_tags(preg_replace("/@/", "", $user));
    } else {
        $user_strip = strip_tags($user);
    }

    $getUsers = $db->prepare('SELECT * FROM users WHERE username LIKE ?');
    $getUsers->execute(array('%' . $user_strip . '%')); // Vérifie si le @username contient le truc cherché
    return $getUsers;
}

function showProfile($user_id) {
    require('connection_db.php');

    if (isset($user_id)) {

        $profile = $db->prepare('SELECT * , (SELECT COUNT(*) FROM follows WHERE follower_id = :user_id GROUP BY follower_id) as "following", (SELECT COUNT(*) FROM follows WHERE user_id = :user_id GROUP BY user_id) as "followers" FROM users WHERE user_id = :user_id ');
        $profile->execute(
            array(
                "user_id" => $user_id
            )
        );
        $donnees = $profile->fetch();

        return $donnees;
    }
}
function getFollowers($user_id) {
    require('connection_db.php');

    if (isset($user_id)) {
        $profile = $db->prepare('SELECT (SELECT COUNT(*) FROM follows WHERE follower_id = :user_id GROUP BY follower_id) as "following", (SELECT COUNT(*) FROM follows WHERE user_id = :user_id GROUP BY user_id) as "followers" FROM users WHERE user_id = :user_id ');
        $profile->execute(
            array(
                "user_id" => $user_id
                )
            );
            $donnees = $profile->fetch();
            
            return $donnees;
    }
}

function turnToLink($content) {
    require('connection_db.php');

    $patternPseudo = "/@[^ \t]+/"; 
    $message = strip_tags($content);

    preg_match_all($patternPseudo, $message, $users_array); // Récupère tous les élément qui sont des pseudo (@pseudo)
    foreach($users_array[0] as $value) {
        $getTargetId = $db->prepare('SELECT * FROM users WHERE username = :username'); 
        $getTargetId->execute(
            array(
                'username' => $value  // Récupère le pseudo qui correspond à la value sur chaque loop
            )
        );
        $getTargetId = ($donnees = $getTargetId->fetch());

        if(isset($_SESSION['username'])) {
            if($getTargetId && $getTargetId['username'] != $_SESSION['username']) { // check si l'occurence correspond à l'un des profils
                $message = preg_replace("/$value/", '<a href="profile_tweetos.php?user_id=' . $getTargetId['user_id'] . '">' . $getTargetId['username']. '</a>', $message); // Remplace la match @pseudo par un lien vers le profile de la personne.
            } elseif ($getTargetId && $getTargetId['username'] == $_SESSION['username']) {
                $message = preg_replace("/$value/", '<a href="profile.php">' . $getTargetId['username']. '</a>', $message); // Remplace la match @pseudo par un lien vers le profile de la personne.
            }
        } else {
            if ($getTargetId) {
                $message = preg_replace("/$value/", '<a href="profile_tweetos.php?user_id=' . $getTargetId['user_id'] . '">' . $getTargetId['username']. '</a>', $message); 
            }
        }
    }

    $patternHashtag = "/#[a-zA-Z0-9_-]+/";

    preg_match_all($patternHashtag, $message, $users_array); // Récupère tous les élément qui sont des pseudo (@pseudo)
    foreach($users_array[0] as $value) {
        $hashtag = str_replace("#" , "%23" ,$value);
        $message = preg_replace("/$value/", '<a href="search.php?hashtag=' . strip_tags($hashtag) . '">' . strip_tags($value) . '</a>', $message); // Remplace la match @pseudo par un lien vers le profile de la personne.
    }

    return $message;
}

function follow($follower_id, $user_id) {
    require('connection_db.php');

    $following = $db->query("INSERT INTO follows (follower_id, user_id) SELECT $follower_id, $user_id WHERE NOT EXISTS (SELECT follower_id, user_id FROM follows WHERE follower_id = $follower_id AND user_id = $user_id)");
    header("Refresh:0");
}
function unfollow($follower_id, $user_id) {
    require('connection_db.php');

    $following = $db->query("DELETE FROM follows WHERE follower_id = $follower_id AND user_id = $user_id");
    header("Refresh:0");
}

function buttonFollow($follower_id, $user_id) {
    require('connection_db.php');
    $getFollow = $db->query("SELECT * FROM follows WHERE follower_id = $follower_id AND user_id = $user_id"); 
    $getFollow = ($donnees = $getFollow->fetch());
    $button;

    if(!$getFollow) {
        ob_start();
        ?>
            <form method='POST' id='form-follow'>
            <input type='submit' name='following' value='follow' id='button-follow' />
            <input type='hidden' name='user_follow' value='<?php echo $user_id ?>' />
            </form>
        <?php
        $button = ob_get_clean();
        return $button;
    } else {
        ob_start();
        ?>
            <form method='POST' id='form-follow'>
            <input type='submit' name='following' value='unfollow' id='button-follow' />
            <input type='hidden' name='user_follow' value='<?php echo $user_id ?>'/>
            </form>
        <?php
        $button = ob_get_clean();
        return $button;
    }
}

function showFollower($user_id) {
    require('connection_db.php');

    if (isset($user_id)) {
        $profile = $db->prepare('SELECT u.user_id, u.fullname, u.picture ,u.biography, u.username FROM users u JOIN follows f ON f.follower_id = u.user_id WHERE f.user_id = :user_id');
        $profile->execute(
            array(
                "user_id" => $user_id
            )
        );

        return $profile;
    }
}

function showFollowing($user_id) {
    require('connection_db.php');

    if (isset($user_id)) {
        $profile = $db->prepare('SELECT u.user_id, u.fullname, u.picture ,u.biography, u.username FROM users u JOIN follows f ON f.user_id = u.user_id WHERE f.follower_id = :user_id');
        $profile->execute(
            array(
                "user_id" => $user_id
            )
        );

        return $profile;
    }
}


function sendMessage($sender_id ,$receiver_name, $content) {
    require('connection_db.php');

    if (!empty($receiver_name) && !empty($content)) {
        $receiver = strip_tags($receiver_name);
        $content = strip_tags($content);
        $receiver_id = $db->prepare('SELECT user_id FROM users WHERE username = ?');
        $receiver_id->execute(array($receiver));
        $receiver_exist = $receiver_id->fetch();
        if($receiver_exist) {
            $mess = $db->prepare('INSERT INTO messages(user_id, receiver_id, content) VALUES(?, ?, ?)');
            $mess->execute(array($sender_id, $receiver_exist['user_id'], $content));
            $err = "Votre message à bien été envoyé !";
        } else {
            $err = "Cet utilisateur n'existe pas !";
        } 
    } else {
        $err = "Veuillez compléter tous les champs";
    }   
}

function getMessage($user_id) {
    require('connection_db.php');

    $msg = $db->prepare('SELECT m.message_id, m.user_id m_user_id, m.receiver_id, m.content, m.message_date, u.user_id user_id, u.fullname, u.picture, u.username FROM messages m JOIN users u ON u.user_id = m.user_id WHERE m.receiver_id = :user_id OR m.user_id = :user_id ORDER BY m.message_date DESC');
    $msg->execute(
        array(
            'user_id' => $user_id
        )
    );
    return $msg;
}
function getReceiver($receiver_id) {
    require('connection_db.php');

    $receiver = $db->prepare('SELECT u.user_id user_id, username FROM users u JOIN messages m ON u.user_id = m.receiver_id WHERE m.receiver_id = :receiver_id');
    $receiver->execute(
        array(
            'receiver_id' => $receiver_id
        )
    );
    $receiverGet = $receiver->fetch();
    $receiverMessage = "<a href='profile_tweetos.php?user_id=$receiverGet[user_id]' style='padding-left: 20px;'>À : " . $receiverGet['username'] . "</a>";
    return $receiverMessage;
}
?>