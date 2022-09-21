<!DOCTYPE html>
<?php
session_start(); 
?>
<html>
    <head>
        <meta charset="utf-8" />
        <title>EPSI - Salon de discussion</title> 
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
<?php
    try
    {
        $bdd = new PDO('mysql:host=localhost;dbname=Epsi;charset=utf8', 'root', '');
    }
    catch(Exception $e)  // Pour afficher les erreurs 
    {
        die('Erreur :'.$e->getMessage());
    }


    if(isset($_SESSION['pseudo'])) { ?>

        <h1> BIENVENUE DANS NOTRE SALON DE DISCUSSION</h1>
        <h4><?php echo 'Bonjour ' . $_SESSION['pseudo'] . ' !<br />'; ?>
        Veuillez entrer votre message dans la case ci-dessous pour l'envoyer dans le tchat :</h4>
        <form action="tchat.php" method="post">
            <p>
            <input type="text" name="message" id="message" size="35" />
            <input type="submit" value="Envoyer" />
            </p>
        </form>

        <?php
        if(isset($_POST['message'])) { // Si tous les champs sont remplis
            // sécurité
            $pseudo = htmlspecialchars($_SESSION['pseudo']);
            $message = addslashes($_POST['message']); // addslashes() est une fonction de protection un peu comme htmlspecialchars

            $req = $bdd->prepare('INSERT INTO Tchat (pseudo, message, date_tchat) VALUES(:pseudo, :message, NOW())');
            $req->execute(array('pseudo'=> $pseudo, 'message'=> $message));  //on insére le message dans la bdd
            header('Location: tchat.php'); // on renvoit à la page actuelle
        }

        $reponse = $bdd->query('SELECT pseudo, message, DATE_FORMAT(date_tchat, \'%d/%m/%Y à %Hh%imin%ss\') AS date_tchatFR FROM Tchat ORDER BY ID DESC LIMIT 0,20');

        while ($donnees=$reponse->fetch()) {
            echo '<p><strong>'.htmlspecialchars($donnees['pseudo']).'</strong> : '. htmlspecialchars(stripslashes($donnees['message'])). '<br /><i class="date_tchat">le ' . $donnees['date_tchatFR'] .'</i><hr /></p>'; // <hr /> pour tracer une ligne et stripslashes() pour enlever les anti-slash mis par addslashes() pour la sécurité dans le message
        }

        $reponse->closeCursor();

    } else {
        echo '<footer> Vous n\'êtes pas connecté !</footer>';
    } ?> 
    </body>
</html>