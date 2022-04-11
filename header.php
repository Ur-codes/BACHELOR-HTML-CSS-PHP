<!DOCTYPE HTML>
<HTML lang="fr">
<head>
  <title>Velibre</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/styles.css">

  <!-- Lealeftjs -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
</head>

<!-- Connexion BDD -->
<?php 
  $servername = "localhost";
  $username = "root";
  $password = "";
  $db = new PDO("mysql:host=$servername;dbname=velibre", $username, $password);
  session_start();
?>

<!-- Navbar -->
<nav class="navbar navbar-light bg-light velibre-navbar">
    <?php
    if (!empty($_SESSION)) {?>
      <?php
      $requete = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo="'.$_SESSION['pseudo'].'" AND admin="oui"');
      $requete->execute();
      if ($row = $requete->fetch(PDO::FETCH_ASSOC)) {
        echo '<span>Bonjour, <b>admin</b> '.$_SESSION['pseudo'].'</span>';
        echo "<a href='carte.php'>Accueil</a>";
        echo "<a href='gererstationsvelos.php'>Gérer stations/vélos</a>";
        echo "<a href='deconnexion.php'>Se déconnecter</a>";
      }
      else {
        echo '<span>Bonjour, <b>utilisateur</b> '.$_SESSION['pseudo'].'</span>';
        echo "<a href='carte.php'>Accueil</a>";
        echo "<a href='profil.php'>Profil</a>";
        echo "<a href='deconnexion.php'>Se déconnecter</a>";
      }
    }
    else {
      
        echo "<a href='connexion.php' style='float:right;'>Connexion</a>";
        echo "<a href='inscription.php' style='float:right;'>Inscription</a>";
    }
    ?>
</nav>