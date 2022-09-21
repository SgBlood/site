<!DOCTYPE html>
<html>
    <head>  <!-- en tête-->
        <meta charset="utf-8" />   <!-- le type de caractère utilisé (encodage=UTF-8) -->
        <title>EPSI - Page Membre</title>  <!-- titre de la page -->
        <link href="style.css" rel="stylesheet" type="text/css"/>  <!-- lier avec le fichier css "style.css"-->
    </head>
    <body> 
        <header>
            <nav> <!-- le menu -->
                <ul id="menu">
                    <li class="li" ><a id="a_blog" href="forum.php?theme=">Forum</a>
                        <ul class="sous_menu"> <!-- je vais utliser la méthode $_GET donc j'ai besoin d'avoir certaines informations dans l'URL (le thème) en fonction de où on clique dans le sous-menu -->
                            <li><a href="forum.php?theme=Campus">Campus</a></li>  
                            <li><a href="forum.php?theme=Actu">Actualités</a></li>
                            <li><a href="forum.php?theme=Devoirs">Devoirs</a></li>
                            <li><a href="forum.php?theme=Numérique">Numérique</a></li>
                            <li><a href="forum.php?theme=Sport">Sport</a></li>
                            <li><a href="forum.php?theme=Question">Questions</a></li>
                            <li><a href="forum.php?theme=Autre">Autre</a></li>
                        </ul>
                    </li>
                    <li class="li"><a href="post.php">Poster un article</a></li>
                    <li class="li"><a href="tchat.php">Salon de discussion</a></li>
                    <li class="li"><a href="page_membre.php">Ta page membre</a></li>
                </ul>
            </nav>
        </header>
        <div class="page_membre">
            <?php
            session_start();
            try
            {
                $bdd = new PDO('mysql:host=localhost;dbname=Epsi;charset=utf8', 'root', '');  
            }
             catch(Exception $e)
            {
                die('Erreur : '.$e->getMessage());  
            }

            if(isset($_SESSION['pseudo'])) { 
                $email = $_SESSION['email'];
                $pseudo = $_SESSION['pseudo'];
                $nb=0; ?>

                <h1>TA PAGE MEMBRE </h1>
                <br/> 

                <h2><U> Vos billets :</U></h2>
                <?php // Pour afficher les billets que la personne à publiés
                $req = $bdd->prepare("SELECT id,auteur,titre FROM Articles WHERE auteur = ?");
                $req->execute(array($pseudo));
                $count = $req->rowCount();
                if ($count>0) { // si la personne à déjà poster plus de 0 articles
                    while ($donnees = $req->fetch()) {
                        $nb+=1;
                        echo '<a href="commentaires.php?billet=' . $donnees['id'] . '"><strong>' . $nb . '.  ' . htmlspecialchars(stripslashes($donnees['titre'])) . '</a></strong>'; ?>
                        <a class="supprimer" href="suppression.php?id=<?php echo $donnees['id']; ?>" onclick="return(confirm('Etes vous sur de vouloir supprimer cet article : <?php echo stripslashes($donnees['titre']); ?>'));">[Supprimer]</a><br /> <!-- Utilisation du javascript pour confirmer la demande de suppression -->
                    <?php      
                    }
                    $req->closeCursor();
                } else {
                    echo '<h3> Vous n\'avez publié aucun article </h3>';
                } 
                $nb=0 //on réinitialise la variable nb ?> 

                <h2><U> Vos derniers commentaires :</U></h2>
                <?php 
                $req = $bdd->prepare('SELECT auteur,commentaire,id_billet, DATE_FORMAT(date_commentaire, \'%d/%m/%Y à %Hh%i\') AS date_commentaireFR FROM commentaires WHERE auteur = ? ORDER BY date_commentaire DESC LIMIT 0,5');
                $req->execute(array($pseudo));
                $count = $req->rowCount();
                if ($count>0) { // si la personne à déjà poster au moins un commentaire
                    while ($donnees = $req->fetch()) {
                        echo '<a class="derniersCom" href="commentaires.php?billet=' . $donnees['id_billet'] . '">' . nl2br(htmlspecialchars(stripslashes($donnees['commentaire']))) . '</a><br />';
                        echo '<i class="date_tchat"> le ' . $donnees['date_commentaireFR'] . '</i><br />';
                    }
                    $req->closeCursor();
                } else {
                    echo '<h3> Vous n\'avez posté aucun commentaire </h3>';
                } ?>
                <br />
                <a href="inscription.php" style="color: red;"><strong>Se déconnecter</strong></a></p> <?php
            } else {
                echo '<footer> Vous n\'êtes pas connecté !</footer>';
            }
        ?>
        </div>
    </body>
</html>