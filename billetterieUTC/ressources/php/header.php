<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" title="CSS Accueil" href="ressources/css/general.css">
    <link rel="stylesheet" type="text/css" title="CSS Accueil" href="ressources/css/accueil.css">
    <link rel="stylesheet" type="text/css" title="CSS Accueil" href="ressources/css/billetterie.css">
    <link href='https://fonts.googleapis.com/css?family=Product+Sans' rel='stylesheet' type='text/css'>
    <link rel="icon" href="ressources/img/mascotte_cote.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link href="http://fr.allfont.net/allfont.css?fonts=matura-mt-script-capitals" rel="stylesheet" type="text/css" />

    <link href="http://fr.allfont.net/allfont.css?fonts=stencil" rel="stylesheet" type="text/css" />
    <link href="http://fr.allfont.net/allfont.css?fonts=high-tower-text" rel="stylesheet" type="text/css" />


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Pour la toute première fois l'association étudiante Light Up City organise 'Compiègne en Lumiere', l'évènement ayant pour but de rassembler Compiègnois et Étudiants le temps d'une soirée avec un parcours illuminant et animant la ville de Compiègne !">

    <title>Festupic</title>

</head>

<body>
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    </script>





    <div id="bandeau_connexion" class="align-items-center">
        <ul class="nav nav-tabs" role="tablist">
          <?php
            if (isset($_SESSION['id'])) {
              ?>
                <li><span>
                     <a href=""><?php echo $_SESSION['firstname'], ' ', $_SESSION['lastname']; ?></a>
                </span></li>
              <?php
                $query = $db->request(
                  "SELECT * FROM tickets WHERE idUser = ?",
                  array($_SESSION['id'])
                );

                if ($query->rowCount() != 0) {
                  ?>
                    <li><span>
                         <a href="./account.php">Mes billets</a>
                    </span></li>
                  <?php
                }
              ?>
            <?php
              $query = $db->request(
                "SELECT * FROM tickets WHERE idUser = ? AND status = 1",
                array($_SESSION['id'])
              );

            ?>
                <li><span>
                     <a href="./disconnect.php">Se déconnecter</a>
                </span></li>
              <?php
            }
            else {
              ?>
                <li><span>
                   <a href="./register.php">S'inscrire</a>
                </span></li>
                <li><span>
                  <a href="./connect.php">Se connecter</a>
                </span></li>
              <?php
            }
          ?>
        </ul>
    </div>



<?php
  ini_set('display_errors', 1);  ini_set('display_startup_errors', 1);  error_reporting(E_ALL);
?>
