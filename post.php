<!DOCTYPE html>
<?php
session_start(); 
?>
<html>
   <head>
        <meta charset="utf-8" />
        <title>EPSI - Poster un article</title> 
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

        <h1>POSTER UN ARTICLE</h1>
        <h4>Veuillez remplir ce formulaire pour poster votre article :)</h4>
        <div>  <!-- formulaire pour poster l'article -->
            <form action="post.php" method="post" id="poste">
                <p>
                    <label for="post_titre">Titre :</label>
                    <input class="form" type="text" name="post_titre" placeholder="Entrez votre titre" size="45" required/>
                </p>
                <p>
                    <label for="post_theme">Theme :</label>
                    <select class="form" name="post_theme" >
                            <option value="Campus">Campus</option>
                            <option value="Actu">Actualité</option>
                            <option value="Devoirs">Devoirs</option>
                            <option value="Numerique">Numérique</option>
                            <option value="Sport">Sport</option>
                            <option value="Question">Questions</option>
                            <option value="Autre" selected >Autre</option>
                    </select><br />
                </p>
                <p>
                    <label for="post_resume">Résumé :</label>
                    <textarea class="form" name="post_resume" id="post_resume" rows="5" cols="80" required >Cet article n'a pas de résumé ...</textarea>
                </p>
                <p>
                    <label for="post_contenu">Contenu :</label>
                    <textarea class="form" name="post_contenu" id="post_contenu" rows="15" cols="80" required placeholder="Ecrivez ici pour modifier le contenu de votre article" ></textarea><br />
                    ( L'auteur et la date sont remplis automatiquements )
                </p>
                    <input type="submit" value="PUBLIER" name="post_publier" id="publier" />
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