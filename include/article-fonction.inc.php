<?php
defined('BLOG_EXEC') or die('Restricted access');

function tag_existe($tag)
{
    $query = 'SELECT id AS nb FROM tag WHERE nom=\''.mysql_real_escape_string($tag).'\'';
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if (!$row) return 0;
    $id = $row['nb'];
    mysql_free_result($result);
    return $id;
}

function tag_ajouter($tag)
{
    $query = 'INSERT INTO tag SET nom=\''.mysql_real_escape_string($tag).'\'';
    $result = mysql_query($query);
    if (!$result) return 0;
    return mysql_insert_id();
}

function article_tag_ajouter($article_id, $tag_id)
{
    $query = 'INSERT INTO r_article_tag SET article_id='.$article_id.', tag_id='.$tag_id;
    return mysql_query($query);
}

function article_tag_supprimer($article_id)
{
    $query = 'DELETE FROM r_article_tag WHERE article_id='.$article_id;
    return mysql_query($query);
}

function image_test($image)
{
    global $config;
    if ($image['error'] != 0)
        return 'Il y a eu une erreur lors du téléchargement du fichier.';

    if ($image['type'] != 'image/jpeg')
        return 'Le fichier n\'est pas une image JPEG.';

    if ($image['size'] > $config['image_taille_max'])
        return 'La taille de l\'image est trop grande';
}
