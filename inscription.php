<?php 
include ("header.php");
?>

<body class="container" style="text-align:center;">
    <h2 style="margin-bottom:8%;">Inscrivez-vous</h2>
    <form action="" method="post">
    <div class="form-group">
        <label for="exampleInputPseudo1">Pseudo :</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Mot de passe :</label>
        <input type="password" class="form-control" id="password" name="password"placeholder="Entrez votre mot de passe">
    </div>
    <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
    </br>
    <?php
    if(!empty($_POST['pseudo']) && !empty($_POST['password'])) {
        $requete = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo="'.$_POST['pseudo'].'"');
        $requete->execute();
        $requeteAdmin = $db->prepare('SELECT * FROM admins WHERE pseudo="'.$_POST['pseudo'].'"');
        $requeteAdmin->execute();
        if (!($row = $requete->fetch(PDO::FETCH_ASSOC)) && !($row2 = $requeteAdmin->fetch(PDO::FETCH_ASSOC))) {
            $requete2 = $db->prepare('INSERT INTO utilisateurs(id, pseudo, password) VALUES (null,"'.$_POST['pseudo'].'", "'.$_POST['password'].'")');
            $requete2->execute();
            echo "Compte créé avec succès !";
        }
        else {
            echo "Compte existe déjà !";
        }
    }
    ?>
    </br>
    <a href="connexion.php">Vous avez déjà un compte ? Connectez-vous.</a>
</body>

<?php include ("footer.php");?>