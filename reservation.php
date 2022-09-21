<!DOCTYPE html>
<?php
session_start(); 
?>
<html>
   <head>
        <meta charset="utf-8" />
        <title>EPSI - Reserver un Food-Truck</title> 
        <link href="style.css" rel="stylesheet" type="text/css"/>  <!-- lier avec le fichier css "style.css"-->
    </head>
    <body>
<?php
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

if(isset($_SESSION['pseudo'])) { ?>

        <h1>Réservez dans le Food-Truck du jour !</h1>
        <h4>Veuillez remplir ce formulaire de réservation - Bon appetit :)</h4>
        <div>  <!-- formulaire pour poster l'article -->
            <form action="post.php" method="post" id="poste">
                <p>
                    <label for="nom_prenom">Nom/prénom :</label>
                    <input class="form" type="text" name="nom_prenom" placeholder="Entrez votre nom complet" size="45" required/>
                </p>
                <p>
                    <label for="food_truck">Food-Truck</label>
                    <select class="form" name="food_truck" required>
                            <option value="Uncle Steve">Uncle Steve Fast food (hamburger, paninis, sandwiches)</option>
                            <option value="Tradi'Pizza">Tradi'Pizza (pizza artisanales)</option>
                            <option value="Seltz et Limone">Seltz et Limone (Spécialités Italiennes, Piadina)</option>
                            <option value="Go Fast Fast food">Go Fast Fast food (hamburger, frites maison)</option>
                            <option value="Autre" selected >Autre</option>
                    </select><br />
                </p>
                <p>
                    <label for="post_resume">Commande :</label>
                    <textarea class="form" name="post_resume" id="post_resume" rows="5" cols="80" required >Dis nous ce que tu veux commander</textarea>
                </p>
                <p>
                    <label for="post_contenu">Info supplémentaire :</label>
                    <textarea class="form" name="post_contenu" id="post_contenu" rows="15" cols="80" placeholder="Informations supplémantaire"></textarea><br />
                </p>
                    <input type="submit" value="COMMANDER" name="post_publier" id="publier" />
            </form>
        </div>
<?php
    if(isset($_POST['post_titre']) && isset($_POST['post_theme']) && isset($_POST['post_contenu']) && isset($_POST['post_publier'])) { // Si le formulaire à bien été envoyé (avec tous les champs remplis)
        // sécurité :
        $post_titre = addslashes($_POST['post_titre']); 
        $post_theme =  htmlspecialchars($_POST['post_theme']); // normalement on ne peut pas modifier les thèmes qui sont déja définis mais on ne sait jamais 
        $post_resume = addslashes($_POST['post_resume']); 
        $post_contenu = addslashes($_POST['post_contenu']);
        if (strlen($post_titre) < 100) {
            if (strlen($post_resume) < 255) {
                $req = $bdd->prepare('INSERT INTO Articles (auteur, titre, theme ,resume ,contenu, date_post) VALUES(:auteur, :titre, :theme, :resume, :contenu, NOW())');
                $req->execute(array('auteur'=> $_SESSION['pseudo'], 'titre' => $post_titre, 'theme'=> $post_theme, 'resume' => $post_resume ,'contenu'=> $post_contenu )); 
                echo '<footer>Votre article à bien été publié</footer>';
            } else {
                echo '<footer> Résumé trop long !</footer>';
            }
        } else {
            echo '<footer> Titre trop long !</footer>';
            header('post.php');
        }
    }
} else {
    echo '<footer> Vous n\'êtes pas connecté !</footer>';
}
?> 
</html>