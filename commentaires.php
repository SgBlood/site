<!DOCTYPE html>
<?php
session_start(); 
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>EPSI - Forum/commentaires</title>
        <link href="style.css" rel="stylesheet" /> 
    </head>
    <body>
        <h1>BONNE LECTURE !</h1>
        <h4><a href="forum.php?theme=">Retour à la liste des articles</a></h4>
 
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

if (isset($_GET['billet'])) {
    $id_billet=htmlspecialchars($_GET['billet']); // sécurité
    $req = $bdd->prepare('SELECT id FROM Articles WHERE id = ?');
    $req->execute(array($id_billet));
    $count = $req->rowCount();
    if ($count == 1) { // un id unique par billet, pas plus pas moins d'où le ' == 1 '
        // Récupération du billet
        $req = $bdd->prepare('SELECT *, DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%i\') AS date_post FROM Articles WHERE id = ?'); // * = tout
        $req->execute(array($id_billet));
        $donnees = $req->fetch();
        ?>
        <div class="news">
            <h3>
                <?php echo htmlspecialchars($donnees['titre']); ?>
                <a class="auteur_billet" href="auteur.php?auteur=<?php echo $donnees['auteur']; ?>">De <?php echo $donnees['auteur']; ?></a>
            </h3>
            <p style="background-color: #DDDDDDDD">
                <strong> Theme : </strong><?php echo $donnees['theme'];?>
                <em class="date_billet">le <?php echo $donnees['date_post']; ?></em><br /><br />
                <strong> Résumé : </strong><br /><?php echo nl2br(htmlspecialchars(stripslashes($donnees['resume'])));?><br /><br />
                <strong> Contenu : </strong><br /><?php echo nl2br(htmlspecialchars(stripslashes($donnees['contenu']))); ?>
            </p>
        </div>
        <br /><br /><hr />
        <form action="commentaires.php?billet= <?php echo $id_billet; ?>" method="post" id="commentaire">
            <p>
            <label for="commentaire">Votre commentaire :</label>
            <br />
            <textarea class="form" name="commentaire" rows="2" cols="53" placeholder="J'adore ton article !"></textarea><br/>
            <input type="submit" value="Envoyer" id="publier" name="publier"/> 
            </p>
        </form> 

        <?php
        $req->closeCursor(); // on libère le curseur pour la prochaine requête
        if (isset($_POST['commentaire']) AND isset($_POST['publier'])) { // si on a envoyé un commentaire
            $pseudo= $_SESSION['pseudo'];
            $commentaire=addslashes($_POST['commentaire']);
            if (strlen($commentaire) < 300) { // si le commentaire à moins de 300 caractères
                // Insertion du message à l'aide d'une requête préparée
                $req = $bdd->prepare('INSERT INTO commentaires (id_billet, auteur, commentaire, date_commentaire) VALUES(:id_billet, :auteur, :commentaire, NOW())');
                $req->execute(array('id_billet'=> $id_billet, 'auteur'=> $pseudo, 'commentaire'=> $commentaire));  //on insére le message dans la bdd
            } else {
                echo '<h3> Commentaire trop long !</h3>';
            }
        }
        ?> <h2>Commentaires :</h2> <?php
        //système de pagination
        if (isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0) { 
            $pageCourante = intval($_GET['page']); // pour transformer $_GET['page'] en int (nb entier)
        } else {
            $pageCourante = 1;                
        }
        $commentairesParPage = 5;
        $depart = ($pageCourante-1)*$commentairesParPage;
        $req = $bdd->prepare('SELECT id FROM commentaires WHERE id_billet = ?');
        $req->execute(array($id_billet));                    
        $commentairesTotaux = $req->rowCount();
        $pagesTotales = ceil($commentairesTotaux/$commentairesParPage);

        // Récupération des commentaires
        $req = $bdd->prepare('SELECT auteur, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%imin%ss\') AS date_commentaireFR FROM commentaires WHERE id_billet = ? ORDER BY date_commentaire DESC LIMIT ' .$depart. ','.$commentairesParPage);
        $req->execute(array($id_billet));
        $count = $req->rowCount();
        if ($count == 0) { // si il n'y a aucun commentaire pour ce billet :
            echo '<h3> Aucun commentaire disponible </h3>';
        } else {
            while ($donnees = $req->fetch()) {
                echo '<p><strong>'.htmlspecialchars($donnees['auteur']).'</strong> : '. nl2br(htmlspecialchars(stripslashes($donnees['commentaire']))). '<br /><i class="date_tchat">le ' . $donnees['date_commentaireFR'] .'</i><hr /></p>';
            } // Fin de la boucle des commentaires
            $req->closeCursor();
            echo '<div class="pagination">';
                for ($i=1;$i<=$pagesTotales;$i++) { // affichage de liens pour naviguer entre les pages 
                    if ($i == $pageCourante) {
                        echo '<a id="PageCourante" href="#"> ' .$i. ' </a>';
                    } else {
                       echo '<a href="commentaires.php?billet='.$id_billet.'&page=' .$i. '"> ' .$i. ' </a>'; 
                    } 
                }
            echo '</div>';
        }
    } else {
    echo "<h3>Article ou billet introuvable</h3>";
    }
} else {
    echo "<h3>Arrete de jouer avec l'URL, tu perds ton temps petit -_- </h3>"; // pour le fun
}
?>
    </body>
</html>