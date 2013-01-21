<?php
defined('BLOG_EXEC') or die('Restricted access');

if (!defined('ENT_HTML5')) define('ENT_HTML5', 0); // PHP < 5.4

function var_post($var, $defaut=false)
{
    return (isset($_POST[$var]))?$_POST[$var]:$defaut;
}

function var_get($var, $defaut=false)
{
    return (isset($_GET[$var]))?$_GET[$var]:$defaut;
}

function var_cookie($var, $defaut=false)
{
    return (isset($_COOKIE[$var]))?$_COOKIE[$var]:$defaut;
}

function var_session($var, $defaut=false)
{
    return (isset($_SESSION[$var]))?$_SESSION[$var]:$defaut;
}

function var_files($var, $defaut=false)
{
    return (isset($_FILES[$var]))?$_FILES[$var]:$defaut;
}

function notif_ajouter($msg, $type)
{
    // type : alert-error, alert-success, alert-info
    $_SESSION['notif_msg'] = $msg;
    $_SESSION['notif_type'] = $type;
}

function notif_afficher($msg='', $type='')
{
    if ($msg == '')
    {
        $msg = var_session('notif_msg');
        $type = var_session('notif_type');
    }
    $type=($type)?$type:'hide';
    $retour = '<div id="notif" class="alert '.$type.'">'.
              '<a class="cacher_notif" href="#null">×</a>'. // croix
              '<span>'.htmlspecialchars($msg, ENT_QUOTES|ENT_HTML5).'</span></div>'; // message
    $_SESSION['notif_msg'] = ''; // effacer
    $_SESSION['notif_type'] = '';
    return $retour;
}

function url($fichier, $parametres=false, $ancre='', $html_special_chars=true)
{
    $url = $fichier;
    if ($parametres)
    {
        ksort($parametres);
        $separateur = '?';
        foreach ($parametres as $k => $v)
        {
            if ($v)
            {
                $url .= $separateur.$k.'='.urlencode($v);
                $separateur = '&';
            }
        }
    }
    if ($ancre)
        $url .= '#'.urlencode($ancre);

    return ($html_special_chars)?htmlspecialchars($url, ENT_QUOTES|ENT_HTML5, 'UTF-8'):$url;
}

function pagination_nb_pages($nb_article, $nb_articles_par_page)
{
    return (int)((max($nb_article, 1)-1)/$nb_articles_par_page+1);
}

function pagination_num_1er_element($num_page, $nb_elements_par_page)
{
    return ($num_page-1)*$nb_elements_par_page; // numéro du 1er élément de la page
}

function pagination($num_page, $nb_page, $fichier='', $parametres=false, $taille=5 /* minimun 2 */)
{
    // valeur de retour : code HTML de la pagination pour Bootstrap
    $html = '<div class="pagination pagination-centered"><ul>';
    if (!$nb_page) $nb_page=1;

    // bouton précédent début
    if ($num_page > 2)
        $html .= '<li><a href="'.url($fichier, array_merge($parametres, array('page' => $num_page-1))).'">&lt;</a></li>';
    else if ($num_page == 2)
        $html .= '<li><a href="'.url($fichier, $parametres).'">&lt;</a></li>';
    else
        $html .= '<li class="disabled"><a href="#null">&lt;</a></li>';
    // bouton précédent fin

    // calcul $page_debut $page_fin début
    $page_debut = max(1, min($nb_page, $num_page+$taille)-2*$taille);
    $page_fin = min($nb_page, $page_debut+2*$taille);
    if ($page_debut > 1) $page_debut++;
    if ($page_fin < $nb_page) $page_fin--;
    // calcul $page_debut $page_fin fin

    if ($page_debut > 1)
        $html .= '<li class="disabled"><a href="#null">...</a></li>';

    for ($i=$page_debut; $i<=$page_fin; $i++)
    {
        $select=($num_page == $i)?'class="active" ':'';

        if ($i == 1)
            $html .= '<li><a '.$select.'href="'.url($fichier, $parametres).'">1</a></li>';
        else
            $html .= '<li><a '.$select.'href="'.url($fichier, array_merge($parametres, array('page' => $i))).'">'.$i.'</a></li>';
    }

    if ($page_fin < $nb_page)
        $html .= '<li class="disabled"><a href="#null">...</a></li>';

    // bouton suivant début
    if ($num_page < $nb_page)
        $html .= '<li><a href="'.url($fichier, array_merge($parametres, array('page' => $num_page+1))).'">&gt;</a></li>';
    else
        $html .= '<li class="disabled"><a href="#null">&gt;</a></li>';
    // bouton suivant début

    $html .= '</ul></div>';

    return $html;
}

function lister_tag($article_id)
{
    // valeur de retour : tableau de tag
    $retour = array();
    $query = 'SELECT nom FROM tag '.
             'INNER JOIN r_article_tag ON tag_id=tag.id '.
             'WHERE article_id='.$article_id.' '.
             'ORDER BY nom';
    $result = mysql_query($query);
    if (!$result) return false;
    while ($row = mysql_fetch_assoc($result))
        $retour[] = $row['nom'];
    mysql_free_result($result);
    return $retour;
}

function tag_effacer_non_utilises()
{
    $query = 'DELETE FROM tag WHERE id NOT IN (SELECT DISTINCT tag_id FROM r_article_tag)';
    return mysql_query($query);
}
