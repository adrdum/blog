<?php
define('BLOG_EXEC', true);

require_once('include/connexion.inc.php');

// SID = identifiant de session

if (var_get('logout')) // si l'utilisateur demande une déconnexion
{
    // supression du SID et du cookie
    if (mysql_query('UPDATE utilisateur SET sid=\'\' WHERE email=\''.mysql_real_escape_string($utilisateur['email']).'\''))
    {
        setcookie('sid', '', 1);
        notif_ajouter('Vous êtes bien déconnecté(e).', 'alert-success');
    }
    else
        notif_ajouter('Il y a eu une erreur lors de la déconnexion.', 'alert-error');
    header('Location: .');
    exit();
}

// récupération des valeurs passées avec GET et POST début
    $email = var_post('email');
    $mdp = var_post('mdp');
// récupération des valeurs passées avec GET et POST fin

if (var_post('submit')) // si formulaire envoyé
{
    if ($email=='' || $mdp=='')
        echo notif_afficher('Veuillez renseigner tous les champs.', 'alert alert-error');
    else
    {
        $query = 'SELECT id, email, mdp, type FROM utilisateur '.
                 'WHERE email=\''.mysql_real_escape_string($email).'\' '.
                 'AND mdp=\''.md5($email.':'.$mdp).'\'';
        $result = mysql_query($query);
        if ($result)
        {
            if (mysql_num_rows($result)) // si utilisateur et mot de passe corrects
            {
                $sid = md5($email.':'.$mdp.':'.time().':'.microtime()); // génération SID
                $sid_fin = time()+$config['temps_connexion']; // calcul limite validité
                $query_maj_sid = 'UPDATE utilisateur '.
                                 'SET sid=\''.$sid.'\', sid_fin='.$sid_fin.' '.
                                 'WHERE email=\''.mysql_real_escape_string($email).'\'';
                if (mysql_query($query_maj_sid)) // enregistrement dans la base de données
                {
                    setcookie('sid', $sid, $sid_fin); // envoi du SID par cookie
                    notif_ajouter('Vous êtes connecté(e).', 'alert-success');
                    header('Location: .');
                    exit();
                }
            }
            mysql_free_result($result);
        }
        notif_ajouter('Il y a eu une erreur lors de la connexion. Saisissez à nouveau votre adresse email et votre mot de passe.', 'alert-error');
    }
}

require('include/smarty.inc.php');

$smarty->assign('recherche', '');
$smarty->assign('nonpublie', '');
$smarty->assign('titre', 'Connexion');
$smarty->assign('email', $email);

$smarty->display('connexion.tpl');
