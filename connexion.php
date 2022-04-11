<?php 
include ("header.php");
?>

<body class="container" style="text-align:center;">
    <h2 style="margin-bottom:8%;">Connectez-vous</h2>
    <?php 
    if(!empty($_POST['pseudo']) && !empty($_POST['password'])) {

        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];
    
        $requete = $db->prepare('SELECT * FROM utilisateurs WHERE pseudo="'.$pseudo.'" AND password="'.$password.'"');
        $requete->execute();
        if (!($row = $requete->fetch(PDO::FETCH_ASSOC))) {
            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Identifiant ou Mot-de-passe incorrect !<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button></div>";
        }
        else {
            session_start();
            $_SESSION['pseudo']=$pseudo;   
            echo "Connexion avec succès !";
            header("location:carte.php");
            exit;
        }
    }
?>
    <form action="" method="post">
    <div class="form-group">
        <label for="exampleInputPseudo1">Pseudo :</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Mot de passe :</label>
        <input type="password" class="form-control" id="password" name="password"placeholder="Entrez votre mot de passe">
    </div>
    <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
    </br>

<a href="inscription.php">Pas de compte ? Veuillez-vous inscrire.</a>
</body>
<?php include ("footer.php");?>