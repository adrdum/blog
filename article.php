<?php
define('BLOG_EXEC', true);

require_once('include/connexion.inc.php');
require_once('include/article-fonction.inc.php');

if ($utilisateur['type'] != 'admin')
{
    notif_ajouter('Vous n\'êtes pas connecté(e).', 'alert-error');
    header('Location: .');
    exit();
}

// récupération des valeurs passées avec GET et POST début
    $article_titre = var_post('titre');
    $article_texte = var_post('texte');
    $article_tag = array_unique(array_map('trim', explode(',', var_post('tag'))));
    sort($article_tag); // tableau de tags trié
    $article_publie = (bool)var_post('publie');
    $article_image = var_files('image');
    $article_image_suppr = (bool)var_post('suppr');
    $submit = var_post('submit');
    $id = (int)var_get('id');
// récupération des valeurs passées avec GET et POST fin

$article_image_actuelle = false;
if (!$submit) $article_publie = true; // case cochée par défaut

if (!$id)
    $titre = 'Ajouter un article';
else
    $titre = 'Modifier un article';

if ($submit) // si formulaire envoyé
{
    $erreur = false;

    if (!$article_titre || !$article_texte)
    {
        notif_ajouter('Veuillez renseigner les champs « Titre » et « Texte ».', 'alert-error');
        $erreur = true;
    }

    if (!$article_image['tmp_name']) // test image envoyée
        $article_image = false;

    if ($article_image && !$erreur)
    {
        $retour = image_test($article_image); // vérifier image
        if ($retour)
        {
            notif_ajouter($retour, 'alert-error');
            $erreur = true;
            $article_image = false;
        }
    }

    if (!$erreur)
    {
        // insérer / mettre à jour (table article)
        $titre_m = mysql_real_escape_string($article_titre);
        $texte_m = mysql_real_escape_string($article_texte);

        if (!$id)
            $query = 'INSERT INTO article (titre, texte, `date`, publie) '.
                     'VALUES (\''.$titre_m.'\', \''.$texte_m.'\', \''.time().'\', \''.$article_publie.'\')';
        else
            $query = 'UPDATE article SET '.
                     'titre=\''.$titre_m.'\', '.
                     'texte=\''.$texte_m.'\', '.
                     'date_modif=\''.time().'\', '.
                     'publie=\''.$article_publie.'\' '.
                     'WHERE id='.$id;
        if (!mysql_query($query))
        {
            if (!$id)
                notif_ajouter('Il y a eu une erreur lors de l\'ajout.', 'alert-error');
            else
                notif_ajouter('Il y a eu une erreur lors de la modification.', 'alert-error');
            $erreur = true;
        }
    }

    if (!$erreur)
    {
        // insérer / mettre à jour (tables tag et r_tag_article)
        if ($id) article_tag_supprimer($id); // pour les mises à jour (table r_article_tag)

        $article_id = ($id)?$id:mysql_insert_id();

        if (count($article_tag) != 0 && $article_tag[0] != '') // s'il y a des tags
            foreach ($article_tag as $tag)
            {
                if (!$tag_id = tag_existe($tag))
                    $tag_id = tag_ajouter($tag); // ajouter les tags non présent (table tag)
                article_tag_ajouter($article_id, $tag_id); // ajouter les tags à l'article (table r_article_tag)
            }

        if ($id) tag_effacer_non_utilises(); // pour les mises à jour (table tag)
    }

    if ($id)
        $article_image_actuelle = (file_exists('./data/article_image/'.$id.'.jpg'))?'vignette.jpg.php?t=1&id='.$id:'';

    if ($article_image_suppr && !$erreur)
    {
        unlink('./data/article_image/'.$id.'.jpg'); // supprimer image
    }

    if ($article_image && !$erreur)
    {
        // sauvegarder image envoyée
        if (!move_uploaded_file($article_image['tmp_name'], dirname(__FILE__).'/data/article_image/'.$article_id.'.jpg'))
        {
            notif_ajouter('Il y a eu une erreur lors de la sauvegarde de l\'image sur le serveur.', 'alert-error');
            $erreur = true;
        }
    }

    if (!$erreur)
    {
        if (!$id)
            notif_ajouter('Votre article à bien été ajouté.', 'alert-success');
        else
            notif_ajouter('Votre article à bien été modifié.', 'alert-success');

        header('Location: .');
        exit();
    }
}
elseif ($id) // si édition d'un article
{
    $query = 'SELECT id, titre, texte, date, publie FROM article WHERE id='.$id;
    $result = mysql_query($query);
    if ($result)
    {
        $row = mysql_fetch_assoc($result);
        if ($row)
        {
            $article_titre = $row['titre'];
            $article_texte = $row['texte'];
            $article_tag = lister_tag($id);
            $article_publie = $row['publie'];
            mysql_free_result($result);

            $article_image_actuelle = (file_exists('./data/article_image/'.$id.'.jpg'))?'vignette.jpg.php?t=1&id='.$id:'';
        }
        else
        {
            notif_ajouter('L\'article n\'existe plus.', 'alert-error');
            header('Location: .');
            exit();
        }
    }
}
// sinon affichage formulaire création d'article vide

require('include/smarty.inc.php');

$smarty->assign('recherche', '');
$smarty->assign('nonpublie', '');
$smarty->assign('titre', $titre);
$smarty->assign('form_action', (!$id)?'article.php':'article.php?id='.$id);
$smarty->assign('form_bouton_value', (!$id)?'Ajouter':'Modifier');
$smarty->assign('article_titre', $article_titre);
$smarty->assign('article_texte', $article_texte);
$smarty->assign('article_tag', implode(', ', $article_tag));
$smarty->assign('article_image_actuelle', $article_image_actuelle);
$smarty->assign('article_publie', $article_publie);

$smarty->display('article.tpl');
