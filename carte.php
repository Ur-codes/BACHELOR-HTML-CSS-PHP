<?php 
include ("header.php");
?>

<body class="container" style="text-align:center;">
    <?php
        //Si non connecté, rediriger vers la page connexion
        if(empty($_SESSION['pseudo'])) {
            header("location: connexion.php");
            exit;
        }
        else {
        //Si connecté, afficher contenu
    ?>
    <span>Bienvenue sur Vélibre</span>
    <?php
        $sql = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo="'.$_SESSION['pseudo'].'"');
        $sql->execute();
        //Lorsque l'admin demande a créé une station
        if (!empty($_POST['longitude']) && !empty($_POST['latitude']) && !empty($_POST['nom'])) {
            $requete = $db->prepare('SELECT * FROM stations WHERE longitude="'.$_POST['longitude'].'" AND latitude="'.$_POST['latitude'].'"');
            $requete->execute();
            if ($requete->fetch(PDO::FETCH_ASSOC)) {
                echo "</br>";
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>La station existe déjà !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button></div>";
                echo "</br>";
            }
            else {
                $requete2 = $db->prepare('INSERT INTO stations VALUES (null,"'.$_POST['nom'].'", "'.$_POST['longitude'].'","'.$_POST['latitude'].'")');
                $requete2->execute();
                echo "</br>";
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Station ajoutée avec succès !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button></div>";
                echo "</br>";
            }
        }

        //Lorsque l'utilisateur veut loué un vélo
        if (!empty($_POST['louerVelo'])) {
            $pseudo = trim($_SESSION['pseudo']," ");
            $sql = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo="'.$pseudo.'"');
            $sql->execute();
            $row = $sql->fetchAll();
            $utilisateur = $row[0][0];
            $sql2 = $db->prepare('SELECT * FROM stationsvelosutilisateurs JOIN velos ON stationsvelosutilisateurs.idvelos = velos.id WHERE stationsvelosutilisateurs.idutilisateurs = '.$utilisateur.' AND velos.etat="louer"');
            $sql2->execute();

            $sql3 = $db->prepare('SELECT velos.id,velos.etat,stations.nom FROM velos JOIN stationsvelosutilisateurs ON velos.id = stationsvelosutilisateurs.idvelos JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE stations.id='.$_POST['louerVelo'].' AND velos.etat ="non louer" LIMIT 1');
            $sql3->execute();
            
            if ($row2 = $sql3->fetchAll()) {
                if (!($result = $sql2->fetch(PDO::FETCH_ASSOC))) {
                    $sql3 = $db->prepare('SELECT velos.id,velos.etat,stations.nom FROM velos JOIN stationsvelosutilisateurs ON velos.id = stationsvelosutilisateurs.idvelos JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE stations.id='.$_POST['louerVelo'].' AND velos.etat ="non louer" LIMIT 1');
                    $sql3->execute();
                    $row2 = $sql3->fetchAll();
                    $sql4 = $db->prepare('UPDATE velos SET velos.etat = "louer" WHERE velos.id='.$row2[0][0].'');
                    $sql4->execute();
                    $sql5 = $db->prepare('UPDATE stationsvelosutilisateurs SET stationsvelosutilisateurs.idutilisateurs = '.$utilisateur.', stationsvelosutilisateurs.dateLouer=NOW()  WHERE stationsvelosutilisateurs.idvelos='.$row2[0][0].' AND stationsvelosutilisateurs.idstations='.$_POST['louerVelo'].'');
                    $sql5->execute();
                    echo "</br>";
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Vous avez loué un vélo '".$row2[0][2]."' !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button></div>";
                    echo "</br>";
                }
                else {
                    echo "</br>";
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Vous avez déjà loué un vélo actuellement !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button></div>";
                    echo "</br>";
                }
            }
            else {
                echo "</br>";
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Cette station n'a aucun vélo disponible !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button></div>";
                    echo "</br>";
            }
            
        }
    ?>

    <div id="map"></div>

    <?php 
            $requete = $db->prepare('SELECT stations.id,stations.nom,stations.longitude,stations.latitude,count(stationsvelosutilisateurs.idvelos) FROM stationsvelosutilisateurs RIGHT OUTER JOIN stations ON stationsvelosutilisateurs.idstations = stations.id GROUP BY stations.id');
            $requete->execute();
            $count = $requete->rowCount();
            $row = $requete->fetchAll();
            $to_encode[] = array();
            foreach ($row as $value) {
                array_push($to_encode,$value);
            }
    ?>
    <script>
        var map = L.map('map').setView([48.85558954429148, 2.352177100352884], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    </script>

    <?php
        //Point de vue Admin
        if (!empty($_SESSION)) {
            $requete = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo="'.$_SESSION['pseudo'].'" AND admin="oui"');
            $requete->execute();
            if ($row = $requete->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <script>
                    var station = <?php echo json_encode($to_encode);?>;
                    for (var i=1; i < station.length; i++) {
                        var info = station[i][1];
                        var info2 = "<br> Vélos dispos : ";
                        var info3 = station[i][4];
                        var resultString = info.concat('', info2);
                        var resultString2 = resultString.concat('', info3);
                        L.marker([Number(station[i][3]), Number(station[i][2])]).addTo(map).bindPopup(resultString2);
                    }
                    var popup = L.popup();
                    // map.doubleClickZoom.disable(); 
                    // function onMapDblClick(e) {
                    //     var marker = L.marker(e.latlng)
                    //         .bindPopup("You clicked the map at " + e.latlng.toString())
                    //         .addTo(map);
                    // }

                    function onMapClick(e) {
                        console.log(e.latlng)
                        document.getElementById("latitude").value = e.latlng.lat;
                        document.getElementById("longitude").value = e.latlng.lng;
                    }

                    map.on('click', onMapClick);
                </script>
                <form action="" method="post">
                <div class="form-group">
                    <label for="Nom">Nom :</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="">
                    <div class="row">
                        <div class="col">
                            <label for="longitudeLabel">Longitude :</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="">
                        </div>
                        <div class="col">
                            <label for="latitudeLabel">Latitude :</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Placer une station</button>
                </form>
                <?php
            }
            //Point de vue utilisateur
            $requete2 = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo="'.$_SESSION['pseudo'].'" AND admin="non"');
            $requete2->execute();
            if ($row = $requete2->fetch(PDO::FETCH_ASSOC)) {
                $tousLesStationsAvecVelos = $db->prepare('SELECT stations.id,stations.nom,stations.longitude,stations.latitude,count(stationsvelosutilisateurs.idvelos) as count FROM velos LEFT JOIN stationsvelosutilisateurs ON velos.id = stationsvelosutilisateurs.idvelos RIGHT JOIN stations ON stationsvelosutilisateurs.idstations = stations.id GROUP BY stations.id;');
                $tousLesStationsAvecVelos->execute();
                $row3 = $tousLesStationsAvecVelos->fetchAll();
                $tousLesStationsAvecVelosNonLouer = $db->prepare('SELECT stations.id,stations.nom,stations.longitude,stations.latitude,count(stationsvelosutilisateurs.idvelos) as count FROM velos LEFT JOIN stationsvelosutilisateurs ON velos.id = stationsvelosutilisateurs.idvelos RIGHT JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE velos.etat="non louer" GROUP BY stations.id;');
                $tousLesStationsAvecVelosNonLouer->execute();
                $row4 = $tousLesStationsAvecVelosNonLouer->fetchAll();
                $tousLesStationsAvecVelosLouer = $db->prepare('SELECT stations.id,stations.nom,stations.longitude,stations.latitude,count(stationsvelosutilisateurs.idvelos) FROM velos LEFT JOIN stationsvelosutilisateurs ON velos.id = stationsvelosutilisateurs.idvelos RIGHT JOIN stations ON stationsvelosutilisateurs.idstations = stations.id WHERE velos.etat="louer" GROUP BY stations.id;');
                $tousLesStationsAvecVelosLouer->execute();
                $row5 = $tousLesStationsAvecVelosLouer->fetchAll();
                $nbVelosDispos[] = array();
                for($i=0;$i < count($row3);$i++) {
                    $yes = "pas fait";
                    for($e=0;$e < count($row4);$e++)
                    {
                        if($row3[$i][0] == $row4[$e][0]) {
                            
                            if ($yes == "pas fait") {
                                array_push($nbVelosDispos,$row4[$e]);
                                $yes = "fait";
                            }
                        }

                    }
                    for($j=0;$j < count($row5);$j++)
                    {
                        if($row3[$i][4] == $row5[$j][4] && $row3[$i][0] == $row5[$j][0]) {
                            $listeTemporaire = array($row3[$i][0],$row3[$i][1],$row3[$i][2],$row3[$i][3],0);
                            array_push($nbVelosDispos,$listeTemporaire);
                            $yes = "fait";
                        }
                    }
                    if ($yes == "pas fait") {
                        array_push($nbVelosDispos,$row3[$i]);
                    }
                    
                }
                //print_r($nbVelosDispos);
                ?>
                <script>
                    var station = <?php echo json_encode($nbVelosDispos);?>;
                    console.log(station);
                    for (var i=1; i < station.length;i++) {
                        var info = station[i][1];
                        var info2 = "<br> Vélos dispos : ";
                        var info3 = station[i][4];
                        var info4 = `<br><form action="" method="post"><input type="hidden" class="form-control" id="louerVelo" name="louerVelo" value="${station[i][0]}"> <button type="submit" class="btn btn-primary">Louer un vélo</button></form>`;
                        var resultString = info.concat('', info2);
                        var resultString2 = resultString.concat('', info3);
                        var resultString3 = resultString2.concat('', info4);
                        
                        L.marker([Number(station[i][3]), Number(station[i][2])]).addTo(map).bindPopup(resultString3);
                    }
                </script>
                <?php
            }
        }
        
        ?>
    <?php
        }
    ?>
</body>

<?php include ("footer.php");?>