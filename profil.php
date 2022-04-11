<?php 
include ("header.php");
?>

<body class="container" style="text-align:center;">
    <?php
        if(empty($_SESSION['pseudo'])) {
            header("location: connexion.php");
            exit;
        }
        else {
            echo "<h2>Bienvenue sur votre profil, ".$_SESSION['pseudo']."</h2>";
                if (!empty($_POST['idVelo']) && !empty($_POST['idStation']) && !empty($_POST['idUtilisateur'])) {
                    $requete = $db->prepare('UPDATE velos SET etat="non louer" WHERE id='.$_POST['idVelo'].'');
                    $requete->execute();
                    $requete2 = $db->prepare('UPDATE stationsvelosutilisateurs SET stationsvelosutilisateurs.dateRendu=NOW() WHERE stationsvelosutilisateurs.idvelos='.$_POST['idVelo'].' AND stationsvelosutilisateurs.idstations='.$_POST['idStation'].' AND stationsvelosutilisateurs.idutilisateurs='.$_POST['idUtilisateur'].'');
                    $requete2->execute();
                    $requete3 = $db->prepare('SELECT * FROM stations WHERE id='.$_POST['idStation'].'');
                    $requete3->execute();
                    $row = $requete3->fetchAll();
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Le vélo a été rendu à la station ".$row[0][1]."
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                    </button></div>";
                }
                $utilisateur = $db->prepare('SELECT id FROM utilisateurs WHERE pseudo="'.$_SESSION["pseudo"].'" LIMIT 1');
                $utilisateur->execute();
                $idUtilisateur = $utilisateur->fetchAll();
                $requete = $db->prepare('SELECT pseudo, idstations, idvelos , idutilisateurs, etat, stations.nom, stationsvelosutilisateurs.dateRendu, stationsvelosutilisateurs.dateLouer FROM utilisateurs JOIN stationsvelosutilisateurs ON utilisateurs.id = stationsvelosutilisateurs.idutilisateurs JOIN velos ON stationsvelosutilisateurs.idvelos = velos.id JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE velos.etat = "louer" AND stationsvelosutilisateurs.idutilisateurs='.$idUtilisateur[0][0].' LIMIT 1;');
                $requete->execute();
                $row = $requete->fetchAll();
                $requete2 = $db->prepare('SELECT pseudo, idstations, idvelos , idutilisateurs, etat, stations.nom, stationsvelosutilisateurs.dateRendu, stationsvelosutilisateurs.dateLouer FROM utilisateurs JOIN stationsvelosutilisateurs ON utilisateurs.id = stationsvelosutilisateurs.idutilisateurs JOIN velos ON stationsvelosutilisateurs.idvelos = velos.id JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE velos.etat = "non louer" AND stationsvelosutilisateurs.idutilisateurs='.$idUtilisateur[0][0].' ORDER BY dateRendu DESC;');
                $requete2->execute();
                $row2 = $requete2->fetchAll();
                foreach ($row as $value) {
                    echo '<div class="card" style="margin-bottom:3%;">
                    <h5 class="card-header">Vélo loué actuellement</h5>
                    <div class="card-body">
                    <h5 class="card-title">Détails</h5>
                    <p class="card-text">Vélo loué le '.$value[7].' chez '.$value[5].'</p>
                    <form method="POST" action="">
                        <div class="form-group">
                            <input type="hidden" class="form-control" id="idVelo" name="idVelo" value='.$value[2].'>
                            <input type="hidden" class="form-control" id="idStation" name="idStation" value='.$value[1].'>
                            <input type="hidden" class="form-control" id="idUtilisateur" name="idUtilisateur" value='.$value[3].'>
                            <button type="submit" class="btn btn-primary">
                            Rendre le vélo
                            </button>
                        </div>
                    </form>
                    </div>
                </div>';
                }
                echo '<hr>';
                echo '</br>';
                foreach ($row2 as $value) {
                    echo '<div class="card" style="margin-bottom:3%;">
                    <h5 class="card-header">Vélo rendu</h5>
                    <div class="card-body">
                    <h5 class="card-title">Détails</h5>
                    <p class="card-text">Le vélo avait été loué le '.$value[7].' chez '.$value[5].'</p>
                    <p class="card-text">Rendu le '.$value[6].'</P>
                    </div>
                </div>';
                }
        
    
        }
    ?>    
</body>

<?php include ("footer.php");?>