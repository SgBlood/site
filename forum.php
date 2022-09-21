<!DOCTYPE html>
<?php
session_start(); 
?>
<html>
   <head>
        <meta charset="utf-8" />
        <title>EPSI - Forum</title> 
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
        <h1>BIENVENUE SUR LE FORUM EPSI !</h1>
        <?php 
        try // connexion à la bdd (base de donnée)
        {
            $bdd = new PDO('mysql:host=localhost;dbname=Epsi;charset=utf8', 'root', '');
        }

        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }

        if (isset($_GET['theme'])) {
            // Pour le système de pagination 
            if (isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0) { 
                $pageCourante = intval($_GET['page']); // pour transformer $_GET['page'] en int (nb entier)
            } else {
                $pageCourante = 1;                
            }
            $billetsParPage = 5;
            $depart = ($pageCourante-1)*$billetsParPage;

            if ($_GET['theme']) { // Si on à cliqué sur un thème précis (dans le menu)
                $theme=htmlspecialchars($_GET['theme']); // sécurité
                $req = $bdd->prepare('SELECT theme FROM Articles WHERE theme = ?');
                $req->execute(array($theme));
                $count = $req->rowCount();

                if ($count > 0) { 
                    //système de pagination
                    $req = $bdd->prepare('SELECT id FROM Articles WHERE theme = ?');
                    $req->execute(array($theme));                    
                    $billetsTotaux = $req->rowCount();
                    $pagesTotales = ceil($billetsTotaux/$billetsParPage);

                    echo '<h4> Découvrez nos derniers articles/billets sur le theme ' . $theme .' :)</h4>';
                    $req = $bdd->prepare('SELECT id,auteur,titre,theme,resume,DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%imin%ss\') AS date_postFR FROM Articles WHERE theme = ? ORDER BY date_post DESC LIMIT '. $depart .',' . $billetsParPage);
                    $req->execute(array($theme));
                    while ($donnees = $req->fetch()) {  
                        $com = $bdd->prepare('SELECT id FROM commentaires WHERE id_billet = ?'); // Pour creer une variable qui contient le nombre de commentaire ($count) 
                        $com->execute(array($donnees['id']));
                        $count = $com->rowCount();?>
                        <div class="news">
                            <h3>
                                <a href="commentaires.php?billet= <?php echo $donnees['id']; ?>"><?php echo htmlspecialchars(stripslashes($donnees['titre'])); ?></a>
                                <em class="auteur_billet"> De <?php echo htmlspecialchars($donnees['auteur']); ?></em>
                            </h3> 
                            <p>
                                <?php // On affiche le résumé du billet nl2br = pour que les retours à la ligne s'affichent <br /> en html
                                echo nl2br(htmlspecialchars(stripslashes($donnees['resume']))) . '<br />'; ?>
                                <br />
                                <em><a href="commentaires.php?billet= <?php echo $donnees['id']; ?>">Commentaires [<?php echo $count; ?>]</a></em> <!-- lien vers la page qui permet de poster des commentaires (et aussi de les voir), on affiche aussi le nombre de commentaire -->
                                <em class="date_billet">le <?php echo $donnees['date_postFR']; ?></em> 
                            </p>
                        </div>
                        <?php
                    }
                    $req -> closeCursor(); // pour liberer le curseur 
                    echo '<div class="pagination">';
                        for ($i=1;$i<=$pagesTotales;$i++) { // affichage de liens pour naviguer entre les pages 
                            if ($i == $pageCourante) {
                                echo '<a id="PageCourante" href="#"> ' .$i. ' </a>';
                            } else {
                               echo '<a href="forum.php?theme='.$theme.'&page=' .$i. '"> ' .$i. ' </a>'; 
                            } 
                        }
                    echo '</div>';
                } else { // si aucun article n'a ce thème (ou si l'utilisateur un mis un faux thème dans l'url)
                    echo"<h3>Aucun article ne correspond à ce theme</h3>";
                }

            } else { // Sinon on affiche tous les thèmes (= quand on clique sur 'blog' dans le menu)  ?>

                <!-- barre de recherche -->
                <div class="recherche">
                    <form action="forum.php" method="GET">
                        <label for="search"><strong>Rechercher un article : </strong></label>
                        <input type="search" name="search" placeholder="Recherche..." size="50" required/>
                    </form>
                    <p><strong>  Rechercher un auteur : </strong><a href="auteur.php?auteur=">Clique ici ! </a></p>
                </div>
                <?php
                // Système de pagination
                $req = $bdd->query('SELECT id FROM Articles');
                $billetsTotaux = $req->rowCount();
                $pagesTotales = ceil($billetsTotaux/$billetsParPage); // ceil() permet d'arrondir à l'entier suppérieur

                $req = $bdd->query('SELECT id,auteur,titre,theme,resume,DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%i\') AS date_postFR FROM Articles ORDER BY date_post DESC LIMIT ' .$depart. ',' . $billetsParPage);
                while ($donnees = $req->fetch()) {  
                    $com = $bdd->prepare('SELECT id FROM commentaires WHERE id_billet = ?'); // Pour creer une variable qui contient le nombre de commentaire ($count) 
                    $com->execute(array($donnees['id']));
                    $count = $com->rowCount(); ?>
                    <div class="news">
                        <h3>
                            <a href="commentaires.php?billet= <?php echo $donnees['id']; ?>"><?php echo htmlspecialchars(stripslashes($donnees['titre'])); ?></a>
                            <em class="auteur_billet"> De <?php echo htmlspecialchars(stripslashes($donnees['auteur'])); ?></em>
                        </h3> 
                        <p>
                            <?php 
                            echo '<strong> Theme : </strong>' . $donnees['theme'] . '<br /><br />';
                            echo nl2br(htmlspecialchars(stripslashes($donnees['resume']))) . '<br />'; ?>
                            <br />
                            <em><a href="commentaires.php?billet= <?php echo $donnees['id']; ?>">Commentaires [<?php echo $count; ?>]</a></em> <!-- lien vers la page qui permet de poster des commentaires (et aussi de les voir), on affiche aussi le nombre de commentaire -->
                            <em class="date_billet">le <?php echo $donnees['date_postFR']; ?></em> 
                        </p>
                    </div>
                <?php
                }
                $req -> closeCursor(); // pour liberer le curseur

                echo '<div class="pagination">';
                    for ($i=1;$i<=$pagesTotales;$i++) { // affichage de liens pour naviguer entre les pages 
                        if ($i == $pageCourante) {
                            echo '<a id="PageCourante" href="#"> ' .$i. ' </a>';
                        } else {
                            echo '<a href="forum.php?theme=&page=' .$i. '"> ' .$i. ' </a>'; 
                        } 
                    }
                echo '</div>';
            }
        } else { // on a fait une recherche
            if(isset($_GET['search']) AND !empty($_GET['search'])) {
                // Système de pagination
                if (isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0) { 
                    $pageCourante = intval($_GET['page']); // pour transformer $_GET['page'] en int (nb entier)
                } else {
                    $pageCourante = 1;                
                }
                $billetsParPage = 5;
                $depart = ($pageCourante-1)*$billetsParPage;
                $search = htmlspecialchars($_GET['search']);
                $req = $bdd->query('SELECT id FROM Articles WHERE titre LIKE "%'.$search.'%" '); // LIKE va permettre de rechercher un titre approximatif (les % signifie qu'il peut y avoir des caractères avant ou après ce qu'on à recherché: $search)
                $count = $req->rowCount();
                $req = $bdd->query('SELECT id,auteur,titre,theme,resume,DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%i\') AS date_postFR FROM Articles WHERE titre LIKE "%'.$search.'%" ORDER BY date_post DESC LIMIT ' .$depart. ',' . $billetsParPage); 
                if($count == 0) { // si aucun résultat, on regarde le titre + le résumé
                    $req = $bdd->query('SELECT id FROM Articles WHERE CONCAT(titre,resume) LIKE "%'.$search.'%" ');
                    $count = $req->rowCount();
                    $req = $bdd->query('SELECT id,auteur,titre,theme,resume,DATE_FORMAT(date_post, \'%d/%m/%Y à %Hh%i\') AS date_postFR FROM Articles WHERE CONCAT(titre,resume) LIKE "%'.$search.'%" ORDER BY date_post DESC LIMIT ' .$depart. ',' . $billetsParPage);
                }
                if($count != 0) {
                    // Système de pagination
                    $billetsTotaux = $count;
                    $pagesTotales = ceil($billetsTotaux/$billetsParPage);

                    echo '<h2> Récultats correspondants à : <i>' . $search . '</i></h2>';
                    while ($donnees = $req->fetch()) {  
                        $com = $bdd->prepare('SELECT id FROM commentaires WHERE id_billet = ?');
                        $com->execute(array($donnees['id']));
                        $count = $com->rowCount(); ?>
                        <div class="news">
                            <h3>
                                <a href="commentaires.php?billet= <?php echo $donnees['id']; ?>"><?php echo htmlspecialchars(stripslashes($donnees['titre'])); ?></a>
                                <em class="auteur_billet"> De <?php echo htmlspecialchars(stripslashes($donnees['auteur'])); ?></em>
                            </h3> 
                            <p>
                                <?php 
                                echo '<strong> Theme : </strong>' . $donnees['theme'] . '<br /><br />';
                                echo nl2br(htmlspecialchars(stripslashes($donnees['resume']))) . '<br />'; ?>
                                <br />
                                <em><a href="commentaires.php?billet= <?php echo $donnees['id']; ?>">Commentaires [<?php echo $count; ?>]</a></em> <!-- lien vers la page qui permet de poster des commentaires (et aussi de les voir), on affiche aussi le nombre de commentaires -->
                                <em class="date_billet">le <?php echo $donnees['date_postFR']; ?></em> 
                            </p>
                        </div>
                    <?php 
                    }
                    $req->closeCursor();   
                    echo '<div class="pagination">';
                        for ($i=1;$i<=$pagesTotales;$i++) { // affichage de liens pour naviguer entre les pages 
                            if ($i == $pageCourante) {
                                echo '<a id="PageCourante" href="#"> ' .$i. ' </a>';
                            } else {
                                echo '<a href="forum.php?search=' .$search.'&page=' .$i. '"> ' .$i. ' </a>'; 
                            } 
                        }
                    echo '</div>';                     
                } else {
                    echo '<h3> Aucun article correspondant à: <i>'  .$search. '</i></h3>';
                } 
            } else {
                echo '<h4> Aucun article correspondant </h4>';
            }
        }
        ?>
    </body>
</html>