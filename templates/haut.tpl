<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>{$titre|escape:'html'} - {$blog_nom|escape:'html'}</title>
    <meta name="description" content="{$blog_description|escape:'html'}">
    <meta name="author" content="{$blog_auteur|escape:'html'}">
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">
    <script type="text/javascript" src="assets/js/jquery-1.8.3.min.js"></script>
  </head>
  <body>
    <div class="container">
      <div class="content">
        <div class="page-header well">
          <h1>{$blog_nom|escape:'html'} <small>{$blog_slogan|escape:'html'}</small></h1>
        </div>
        <div class="row">
          <div class="span8">
            {$notification}
            <!-- contenu -->
