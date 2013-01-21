<?php
define('BLOG_EXEC', true);

require_once('include/connexion.inc.php');
require_once('include/article-fonction.inc.php');

if ($utilisateur['type'] == 'visiteur')
{
    notif_ajouter('Vous n\'êtes pas connecté(e).', 'alert-error');
    header('Location: .');
    exit();
}

$id = (int)var_get('id'); // identifiant de l'article à supprimer

$erreur = false;

$query = 'DELETE FROM article WHERE id='.$id; // supprimer article
if (!mysql_query($query)) $erreur = true;

if (!$erreur)
{
    if (!article_tag_supprimer($id)) $erreur = true; // (table r_article_tag)
}

if (!$erreur)
{
    if (!tag_effacer_non_utilises()) $erreur = true; // (table tag) supprimer les tags qui appartiennent uniquement à l'article supprimé
}

$fichier = './data/article_image/'.$id.'.jpg';
if (file_exists($fichier) && !$erreur)
{
    if (!unlink($fichier)) $erreur = true; // supprimer image
}

if (!$erreur)
    notif_ajouter('Votre article à bien été supprimé.', 'alert-success');
else
    notif_ajouter('Il y a eu une erreur lors de la suppression.', 'alert-error');

header('Location: .');
exit();
