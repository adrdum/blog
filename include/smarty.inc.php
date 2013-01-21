<?php
defined('BLOG_EXEC') or die('Restricted access');

require_once('libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->assign('notification', notif_afficher());
$smarty->assign('utilisateur', $utilisateur);
$smarty->assign('blog_nom', $config['blog_nom']);
$smarty->assign('blog_slogan', $config['blog_slogan']);
$smarty->assign('blog_auteur', $config['blog_auteur']);
$smarty->assign('blog_description', $config['blog_description']);
$smarty->assign('recherche_placeholder', $config['recherche_placeholder']);
