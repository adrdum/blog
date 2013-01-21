<?php
defined('BLOG_EXEC') or die('Restricted access');

$config['production'] = false; // true => production / false => développement

$config['blog_nom'] = 'Blog';
$config['blog_slogan'] = 'Pour m\'initier à PHP';
$config['blog_auteur'] = '';
$config['blog_description'] = 'Petit blog pour m\'initier à PHP';

$config['mysql_serv'] = '';
$config['mysql_util'] = '';
$config['mysql_mdp']  = '';
$config['mysql_bdd']  = '';

$config['timezone'] = 'Europe/Paris'; // fuseau horaire
$config['app'] = 3; // articles par page
$config['temps_connexion'] = 15*60; // temps des sessions en secondes
$config['notif_timeout'] = 5000; // temps d'affichage des notifications en millisecondes
$config['recherche_placeholder'] = 'informatique, linux';
$config['image_taille_max'] = 1024*1024; // en octets

$config['vignette_max_largeur'] = 612; // en pixels
$config['vignette_max_hauteur'] = 500; // en pixels
$config['petite_vignette_max_largeur'] = 200; // en pixels
$config['petite_vignette_max_hauteur'] = 200; // en pixels
$config['vignette_qualite'] = 75; // de 0 à 100

if (!$config['production']) // configuration développement
{
    $config['mysql_serv'] = 'localhost';
    $config['mysql_util'] = '';
    $config['mysql_mdp']  = '';
    $config['mysql_bdd']  = '';
}
