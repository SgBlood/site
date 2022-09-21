<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Inscription</title>
        <link href="style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <h1>INSCRIPTION</h1>
        <h4>Veuillez remplir votre formulaire d'inscription :</h4>
        <div>  <!-- formulaire d'inscription ou de connexion -->
            <form action="inscription.php" method="post">
                <p>
                <label for="pseudo">Pseudo :</label><br />
                <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre pseudo" size="35" required /><br /><br />
                <label for="mdp">Mot de passe :</label><br />
                <input type="password" name="mdp" id="mdp" placeholder="Entrez votre mot de passe" size="35" required /><br /><br />
                <label for="mdp2">Retapez votre mot de passe :</label><br />
                <input type="password" name="mdp2" id="mdp2" placeholder="Confirmez votre mot de passe" size="35" required /><br /><br />
                <label for="email">Adresse email :</label><br />
                <input type="email" name="email" id="email" placeholder="Entrez votre email" size="35" required /><br /><br />
                <input type="submit" value="S'inscrire" />
                </p>
            </form>
        </div>
<?php
session_start();
// Connexion à la base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=Epsi;charset=utf8', 'root', '');  //bdd = Base De Données
}
 catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());  // Pour afficher les erreurs
}
// fin de la connexion à la bdd

if(isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['mdp2']) && isset($_POST['email']))  // Si tous les champs sont remplis
{
    // sécurité
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mdp_hache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    $mdp2 = password_hash($_POST['mdp2'], PASSWORD_DEFAULT);
    $email = htmlspecialchars($_POST['email']);

    if (strlen($pseudo) < 50) { 
        if ($_POST['mdp'] == $_POST['mdp2'] ) { // si les deux mdp sont pareils
            $req = $bdd->query("SELECT pseudo FROM Visiteurs WHERE pseudo = '$pseudo'"); // on selectionne les pseudos égaux aux pseudos(si il y en a) dans la colone pseudo de la bdd 
            $count = $req->rowCount(); // $count = le nombre de fois où il y a le meme pseudo dans la bdd
            if ($count == 0) { // si il y a pas ce pseudo dans la bdd 
                $req = $bdd->query("SELECT email FROM Visiteurs WHERE email = '$email'"); // on selectionne les mails égaux aux mails(si il y en a) dans la colone email de la bdd 
                $count = $req->rowCount(); // $count = le nombre de fois où il y a le meme email dans la bdd
                if ($count == 0) { // si il y a pas cet email dans la bdd 
                    $req = $bdd->prepare('INSERT INTO Visiteurs (pseudo, mdp, email, date_inscription) VALUES(:pseudo, :mdp, :email, NOW())');
                    $req->execute(array('pseudo'=> $pseudo, 'mdp' => $mdp_hache, 'email'=> $email));  //une fois que tout est validé, on insére le nouveau membre dans la bdd

                    $req = $bdd->prepare('INSERT INTO recuperation(email) VALUES (?) ');
                    $req->execute(array($email));
                    }
                    header('Location: connexion.php'); // on renvoit à la page indiquée
            } else {
                echo '<h3>Ce pseudo est déjà utilisé</h3>';
            }
        } else {
            echo '<h3>Mots de passe non identiques</h3>';
        }
    } else {
        echo '<h3>Pseudo trop long !</h3> ';
    }
}
?>
        <div>
            <h2> Deja connecté ? </h2>
            <p><a href="connexion.php">Se connecter</a></p>  
        </div>
    </body>
</html>