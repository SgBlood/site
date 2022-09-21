<!DOCTYPE html>
<?php
session_start(); 
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>EPSI - BLOG/auteur</title>
        <link href="style.css" rel="stylesheet" /> 
    </head>
    <body>
        <h1>DÉCOUVREZ NOS MEMBRES !</h1>
        <h4><a href="forum.php?theme=">Retour à la liste des billets</a></h4>
        <!-- barre de recherche -->
        <form action="auteur.php" method="GET">
            <label for="auteur"><strong>Rechercher un membre : </strong></label>
            <input type="search" name="auteur" placeholder="Recherche..." size="50" required/>
        </form>
        <br />
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

if (isset($_GET['auteur'])) {
    $nb=0;
    $auteur=htmlspecialchars($_GET['auteur']); // sécurité
    $req = $bdd->prepare('SELECT pseudo,DATE_FORMAT(date_inscription, \'%d/%m/%Y\') AS date_inscription FROM Visiteurs WHERE pseudo = ?');
    $req->execute(array($auteur));
    $count = $req->rowCount();

    if ($count == 1) { // Si le nom dans le $_GET correspond bien à un visiteur (quelqu'un qui à un compte) 
        $données = $req->fetch();
        $date_inscription= $données['date_inscription'];
        $req->closeCursor(); ?>
        <div class="page_membre">
            <h4> Cette personne a créée son compte le <?php echo $date_inscription; ?>  :) </h4>
            <h2 class="infoimportante"><U> <?php echo $auteur; ?> </U></h2>
            <?php // Pour afficher les billets que la personne à publiés
            $req = $bdd->prepare("SELECT id,auteur,titre FROM Articles WHERE auteur = ?");
            $req->execute(array($auteur));
            $count = $req->rowCount();

            echo '<h2><U>(' . $count .') Billets :</U></h2>';
            if ($count>0) { // si la personne à déjà poster plus de 0 articles
                while ($donnees = $req->fetch()) {
                    $nb+=1;
                    echo '<a class="recherche" href="commentaires.php?billet=' . $donnees['id'] . '"><strong>' . $nb . '.  ' . htmlspecialchars(stripslashes($donnees['titre'])) . '</a></strong><br />';    
                }
                $req->closeCursor();
            } else {
                echo '<h3> Cette personne à publié aucun article </h3>';
            } 
            $nb=0; //on réinitialise la variable nb
            $req = $bdd->prepare('SELECT auteur,commentaire,id_billet, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%i\') AS date_commentaireFR FROM commentaires WHERE auteur = ? ORDER BY date_commentaire DESC LIMIT 0,5');
            $req->execute(array($auteur));
            $count = $req->rowCount();

            echo '<h2><U> Derniers commentaires :</U></h2>';
            if ($count>0) { // si la personne à déjà poster au moins un commentaire
                while ($donnees = $req->fetch()) {
                    echo '<a class="derniersCom" href="commentaires.php?billet=' . $donnees['id_billet'] . '">' . nl2br(htmlspecialchars(stripslashes($donnees['commentaire']))) . '</a><br />';
                    echo '<i class="date_tchat"> le ' . $donnees['date_commentaireFR'] . '</i><br />';
                }
                $req->closeCursor();
            } else {
                echo '<h3> Cette personne n\'a posté aucun commentaire </h3>';
            }
        echo '</div>';
    } else { // on va faire une recherche approximative
        $req = $bdd->query('SELECT pseudo FROM Visiteurs WHERE pseudo LIKE "%'.$auteur.'%" '); 
        $count = $req->rowCount();
        if ($count == 0) {
            echo "<h3>Auteur introuvable</h3>";
        } else {
            echo '<h4>Vous recherchez peut être : </h4>';
            while ($donnees = $req->fetch()) {
                echo '<p><a class="recherche" href="auteur.php?auteur='. $donnees['pseudo'] . '"><strong> - '. $donnees['pseudo'] .'</strong></a></p>';    
            }
            $req->closeCursor();  
        }
    }           
} else {
    echo "<h3>Arrete de jouer avec l'URL, tu perds ton temps petit -_- </h3>";  // juste pour le fun
}
?>
    </body>
</html>