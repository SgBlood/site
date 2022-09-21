<?php
// connexion à la bdd
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=Epsi;charset=utf8', 'root', '');  
}
 catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());  
}
// fin de la connexion à la bdd

if (isset($_GET['id'])) {
	if (is_numeric($_GET['id'])) { // pour savoir si l'id dans l'url est un nombre entier 
		$id = $_GET['id'];
		$supp = $bdd->prepare('DELETE FROM Articles WHERE id= ?'); // supression de l'article si toutes les conditions sont remplies
		$supp->execute(array($id));
		$supp = $bdd->prepare('DELETE FROM commentaires WHERE id_billet= ?'); // supression des commentaires de cet article 
		$supp->execute(array($id));
		
	}
}
header('Location: page_membre.php'); // retourne à la page membre

?>