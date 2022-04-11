<?php 
include ("header.php");
?>

<body class="container" style="text-align:center;">
    <h2>Espace de management des stations</h2>
    <?php
        if (!empty($_POST['ajouterVelo'])) {
            $requete = $db->prepare('INSERT INTO velos VALUES (null,"non louer")');
            $requete->execute();
            $requete2 = $db->prepare('SELECT * FROM velos ORDER BY id DESC LIMIT 1');
            $requete2->execute();
            $row = $requete2->fetch();
            $requete3 = $db->prepare('INSERT INTO stationsvelosutilisateurs VALUES (null,'.$_POST['ajouterVelo'].','.$row[0].',null)');
            $requete3->execute();
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Ajout du vélo dans la station ".$_POST['ajouterVelo']."<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button></div>";
        }
        if (!empty($_POST['enleverVelosNonUtilisés'])) {
            
        }
        if (!empty($_POST['supprimerStation'])) {
            $requete = $db->prepare('DELETE FROM stations WHERE id = '.$_POST['supprimerStation'].'');
            $requete->execute();
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Station ".$_POST['supprimerStation']." supprimé avec succès !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
          </button></div>";
        }
        ?>
    <?php 
        $requete = $db->prepare('SELECT * FROM stations');
        $requete->execute();
        $row = $requete->fetchall();
        foreach ($row as $value) {
            $requete = $db->prepare('SELECT * FROM velos JOIN stationsvelosutilisateurs ON velos.id = stationsvelosutilisateurs.idvelos JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE etat="non louer" AND stations.id = '.$value[0].'');
            $requete->execute();
            $count = $requete->rowCount();
            $requete2 = $db->prepare('SELECT * FROM velos JOIN stationsvelosutilisateurs ON velos.id = stationsvelosutilisateurs.idvelos JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE etat="louer" AND stations.id = '.$value[0].'');
            $requete2->execute();
            $count2 = $requete2->rowCount();
            echo '<div class="card" style="margin-bottom:3%;">
            <h5 class="card-header">'.$value[1].'</h5>
            <div class="card-body">
              <h5 class="card-title">Détails</h5>
              <p class="card-text">Longitude : '.$value[2].' et Latitude : '.$value[3].'</p>
              <p class="card-text">Vélos loué : '.$count2.'</p>
              <p class="card-text">Vélos non loués : '.$count.'</p>
              <form method="POST" action="">
                <div class="form-group">
                    <input type="hidden" class="form-control" id="ajouterVelo" name="ajouterVelo" value='.$value[0].'>
                    <button type="submit" class="btn btn-primary">
                    Ajouter vélo
                    </button>
                </div>
              </form>
              <form method="POST" action="">
                <div class="form-group">
                    <input type="hidden" class="form-control" id="enleverVelosNonUtilisés" name="enleverVelosNonUtilisés" value='.$value[0].'>
                    <button type="submit" class="btn btn-primary">
                    Enlever vélos non utilisés
                    </button>
                </div>
              </form>
              <form method="POST" action="">
                <div class="form-group">
                    <input type="hidden" name="supprimerStation" value='.$value[0].'>
                    <button type="submit"class="btn btn-primary">
                    Supprimer station
                    </button>
                </div>
              </form>
            </div>
          </div>';
        }
        
    ?>
</body>

<?php include ("footer.php");?>