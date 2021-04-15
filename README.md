## Index
1. Infos générales
2. Installation
3. Prérequis
4. Utilisation
5. Fonctionnalités

## 1) Infos générales
    Nom du projet : Tweet Académie
    Statut du projet : Fini.
        Version : 1.0 
    Auteurs: Aurélien Jussaume, Christopher Debray, Xavier Vauconsant, Joan Charlet
    Objectif (résumé du sujet) :
        Le but de ce projet est de créer un réseau social pour les étudiants de votre promotion, qui aura les mêmesfonctionnalités quetwitter. 
        Il devra être le plus ressemblant possible en terme de fonctionnalités par rap-port au site “Twitter”.

## 2 Installation
    Importer la base de données common-database .

## 3) Prérequis
    PHP, MySQL, Apache2

## 4) Utilisation
    Pour utiliser le site il faut modifier le fichier model/connection_db.php, 
    il faudra changer le nom de l'utilisateur et le mot de passe pour la connexion à la base de données

## 5) Fonctionnalités  

###### Inscription de compte contenant :
* Photo de profil (optionnel)
* Nom et prénom
* Email ou téléphone
* Date de naissance 
* Mot de passe 
* Vérification du mot de passe 

###### Connexion au compte :
* Email ou téléphone
* Mot de passe

###### Possibilités de (connecté ou pas) :
- Changer le thème
- Utiliser la page de recherche 
* Consulter les profils des utilisateurs qui :
    * Affiche des informations sur l'utilisateur (Nom prénom, username, photo de profil, etc).
    * Possède de liens vers la page contenant les followers et les gens que l'utilisateur follow.

* Consulter les tweets (page Acceuil)
* Consulter les commentaires des tweets

* Système de thème (dark mode / light mode)

###### Page de Recherche
* Rechercher des tweet en fonction du / des hastags qui lui sont associé
* Rechercher des utilisateur par username ressemblant à celui qui est entré dans le champ de texte

###### Possibilités (si connecté) :
- D'accéder à sa page de compte personnel qui permet de modifier :
    - La photo
    - La bannière
    - Le username
    - Le email
    - Le téléphone
    - Le mot de passe 
    - Le biographie

    - Poster des tweets (page Acceuil)
    - Poster des commentaires dans les tweets
    - Utilisation / mention d'hashtags (#tag) dans les tweets comme dans les commentaires
        - Les mentions sont sous la forme d'un lien vers la page de recherche correspondant au #tag écrit

    - Utilisation / mention d'utilisateurs (@username) dans les tweets comme dans les commentaires
        - Les mentions sont sous la forme d'un lien vers la page de recherche correspondant au @username écrit

    - Follow ou Unfollow un utilisateur.

    - Système de messagerie privé
        - Envoi ou réception de message
        - Affichage des messages envoyés ou reçus 
