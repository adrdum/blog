<?php
defined('BLOG_EXEC') or die('Restricted access');

session_start();
require_once('include/configuration.inc.php');
require_once('include/fonction.inc.php');

header('Content-Type: text/html; charset=UTF-8'); // utile pour certain hébergeur
date_default_timezone_set($config['timezone']);

// connexion base de donnée début
mysql_connect($config['mysql_serv'], $config['mysql_util'], $config['mysql_mdp']);
mysql_select_db($config['mysql_bdd']);
mysql_query('SET NAMES \'utf8\'');
// connexion base de donnée fin

// système d'authentification début
$utilisateur['id'] = 0;            // valeur par défaut
$utilisateur['email'] = '';        // valeur par défaut
$utilisateur['type'] = 'visiteur'; // valeur par défaut
$utilisateur_sid = var_cookie('sid');

// voir le fichier connexion.php pour la création du SID
if ($utilisateur_sid) // si l'utilisateur a envoyé un cookie SID
{
    // récupérer informations sur l'utilisateur
    $query = 'SELECT id, email, mdp, type FROM utilisateur '.
             'WHERE sid=\''.mysql_real_escape_string($utilisateur_sid).'\''.
             'AND sid_fin > '.time();
    $result = mysql_query($query);
    if (mysql_num_rows($result)) // si le sid est dans la table utilisateur et est encore valable
    {
        $utilisateur = mysql_fetch_assoc($result);    // récupération des données de l'utilisateur
        $sid_fin = time()+$config['temps_connexion']; // augmenter le temps de validité du cookie
        setcookie('sid', $utilisateur_sid, $sid_fin);
        $query_maj_temps = 'UPDATE utilisateur SET sid_fin='.$sid_fin.' '.
                           'WHERE sid = \''.$utilisateur_sid.'\'';
        mysql_query($query_maj_temps);
    }
    else
        setcookie('sid', '', 1); // SID non valide + effacer cookie
    mysql_free_result($result);
}
// système d'authentification fin
