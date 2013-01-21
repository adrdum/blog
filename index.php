<?php
define('BLOG_EXEC', true);

require_once('include/connexion.inc.php');

// récupération des valeurs passées avec GET et POST début
    $nonpublie = ($utilisateur['type'] == 'visiteur')?0:(int)var_get('nonpublie');
    $num_page = (int)var_get('page');
    $recherche = var_get('recherche');
    $tag = var_get('tag');
// récupération des valeurs passées avec GET et POST fin

$parametres = array( // paramètres pour les URL de la page
    'recherche' => $recherche,
    'nonpublie' => $nonpublie,
    'tag' => $tag
);

$query_recherche = ($recherche)?'AND texte LIKE \'%'.addcslashes(mysql_real_escape_string($recherche), '%_').'%\' ':' ';
$query_tag = ($tag)?'AND id IN ('.
                    'SELECT article_id FROM r_article_tag '.
                    'INNER JOIN tag ON tag_id=tag.id '.
                    'WHERE nom=\''.mysql_real_escape_string($tag).'\')':'';

// nombre d'articles début
    $query = 'SELECT COUNT(*) AS nb '.
             'FROM article '.
             'WHERE publie='.(1-$nonpublie).' '.$query_recherche.' '.$query_tag;
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    $nb_article = $row['nb']; // nombre d'articles
    mysql_free_result($result);
// nombre d'articles fin

$nb_page = pagination_nb_pages($nb_article, $config['app']);

// redirection si nécessaire début
    if ($num_page < 0 || $num_page == 1)
    {
        header('Location: '.url('.', $parametres, '', false));
        exit();
    }

    $num_page = (!$num_page)?1:$num_page;

    if ($num_page > $nb_page)
    {
        header('Location: '.url('.', array_merge($parametres, array('page' => $nb_page)), '', false));
        exit();
    }
// redirection si nécessaire fin

$debut = pagination_num_1er_element($num_page, $config['app']);

// choix du titre début
    if (!$nonpublie && !$recherche && !$tag)
    {
        $titre = 'Tous les articles';
    }
    else
    {
        $titre = 'Articles';
        if ($nonpublie) $titre .= ' non publiés';
        if ($tag) $titre .= ' avec le tag « '.htmlspecialchars($tag, ENT_QUOTES|ENT_HTML5).' »';
        if ($tag && $recherche) $titre .= ' et';
        if ($recherche) $titre .= ' contenant « '.htmlspecialchars($recherche, ENT_QUOTES|ENT_HTML5).' »';
    }
// choix du titre fin

$pagination_html = '';
$articles = array();
$texte_pas_articles = '';

if ($nb_article)
{
    $pagination_html = pagination($num_page, $nb_page, '.', $parametres);

    // liste article début
        $query = 'SELECT id, titre, texte, `date`, date_modif, publie '.
                 'FROM article '.
                 'WHERE publie='.(1-$nonpublie).' '.$query_recherche.' '.$query_tag.' '.
                 'ORDER BY `date` DESC '.
                 'LIMIT '.$debut.','.$config['app'];
        $result = mysql_query($query);
        for ($i=0; $row = mysql_fetch_assoc($result); $i++)
        {
            $articles[$i] = $row; // articles stockés dans $articles[]
            // ajout identifiant image s'il existe
            $articles[$i]['image'] = file_exists('./data/article_image/'.$row['id'].'.jpg')?$row['id']:false;
            $articles[$i]['tag'] = lister_tag($row['id']); // ajout liste des tags
        }
        mysql_free_result($result);
    // liste article fin
}
else
{
    $texte_pas_articles = 'Pas d\'articles';
}

require('include/smarty.inc.php');

$smarty->assign('recherche', $recherche);
$smarty->assign('nonpublie', $nonpublie);
$smarty->assign('titre', $titre);
$smarty->assign('pagination_html', $pagination_html);
$smarty->assign('articles', $articles);
$smarty->assign('texte_pas_articles', $texte_pas_articles);

$smarty->display('index.tpl');
