<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Connexion</title>
        <link href="style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <h1>CONNEXION</h1>
        <h4>Veuillez vous connecter:</h4>
        <div>  <!-- formulaire d'inscription ou de connexion -->
            <form action="connexion.php" method="post">
                <p>
                <label for="pseudo">Pseudo :</label><br />
                <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre pseudo" size="35" required/><br /><br />
                <label for="mdp">Mot de passe :</label><br />
                <input type="password" name="mdp" id="mdp" placeholder="Entrez votre mot de passe" size="35" required/><br /><br />
                <input type="submit" value="Se connecter" />
                </p>
            </form>
        </div>
<?php

// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=Epsi;charset=utf8', 'root', '');
}
 catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}
// fin de la connexion à la bdd


if(isset($_POST['pseudo']) && isset($_POST['mdp'])) {
    // sécurité
    $pseudo = htmlspecialchars($_POST['pseudo']);

    //  Récupération de l'utilisateur et de son mdp
    $req = $bdd->prepare('SELECT id, mdp, email FROM Visiteurs WHERE pseudo = :pseudo');
    $req->execute(array('pseudo' => $pseudo));
    $resultat = $req->fetch();


    if (!$resultat) { // si aucun pseudo dans la bdd correspond au pseudo rentré :
        echo 'Mauvais identifiant ou mot de passe !'; // En vrai c'est l'identifiant qui est mauvais 
    } else {
        // Comparaison du mdp envoyé via le formulaire avec la base
        $isPasswordCorrect = password_verify($_POST['mdp'], $resultat['mdp']);   // c'est un booléen
        if ($isPasswordCorrect) { // si $isPasswordCorrect == True (donc si c'est le bon mdp) :
            session_start();
            $_SESSION['id'] = $resultat['id'];   // on stocke l'id et le pseudo (par exemple) du client dans des varriables SESSION
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['email'] = $resultat['email'];
            echo 'Vous êtes connecté '. $_SESSION['pseudo'] . ' !';
            header('Location: page_membre.php'); // on renvoit à la page membre
        } else {
            echo 'Mauvais identifiant ou mot de passe !'; // En vrai c'est le mdp qui est mauvais 
        }
    }
}

?>
        <div>  <!-- liens vers les pages inscription et récupération de mot de passe -->
            <h2>Retour à la page d'inscription </h2>
            <p><a href="inscription.php">S'inscrire</a></p>
        </div>
        <div>
            <h2>Mot de passe oublié ? </h2>
            <p><a href="">Réinitialiser mon mdp</a></p> <!-- pas de lien, je le ferais plus tard -->
        </div>
        
    </body>    
</html>
