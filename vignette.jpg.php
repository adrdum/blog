<?php
define('BLOG_EXEC', true);

require_once('./include/configuration.inc.php');
require_once('./include/fonction.inc.php');

switch ((int)var_get('t'))
{
    case 0: // vignette moyenne
        $vignette_max_largeur = $config['vignette_max_largeur'];
        $vignette_max_hauteur = $config['vignette_max_hauteur'];
        break;
    case 1: // petite vignette
        $vignette_max_largeur = $config['petite_vignette_max_largeur'];
        $vignette_max_hauteur = $config['petite_vignette_max_hauteur'];
        break;
    default:
        exit();
}

$chemin = './data/article_image/'.(int)var_get('id').'.jpg'; // chemin de l'image d'origine

if (!is_readable($chemin)) exit();

if (!$taille = getimagesize($chemin)) exit();

if ($taille[0] <= $vignette_max_largeur &&
    $taille[1] <= $vignette_max_hauteur)
{
    // pas de redimensionnement
    header('Content-type: image/jpeg');
    readfile($chemin);
    exit();
}

// calcul des nouvelles dimensions début
    if (!$taille[1]) exit();
    $ratio_1 = $taille[0]/$taille[1];
    $ratio_2 = $vignette_max_largeur/$vignette_max_hauteur;

    if ($ratio_1 > $ratio_2)
    {
        $largeur = $vignette_max_largeur;
        $hauteur = (int)($vignette_max_largeur/$ratio_1);
    }
    else
    {
        $largeur = (int)($vignette_max_hauteur*$ratio_1);;
        $hauteur = $vignette_max_hauteur;
    }
// calcul des nouvelles dimensions fin

if (!$image_src = imagecreatefromjpeg($chemin)) exit();

if (!$image_dest = imagecreatetruecolor($largeur, $hauteur)) exit();

if (!imagecopyresampled($image_dest, $image_src, 0, 0, 0, 0, $largeur, $hauteur, $taille[0], $taille[1])) exit();

header('Content-type: image/jpeg');
imagejpeg($image_dest, NULL, $config['vignette_qualite']);
