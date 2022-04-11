<?php include ("header.php");?>

<body>
<?php
    if (!empty($_SESSION)) {
        session_destroy();
        header("location: carte.php");
        exit;
    }
    else {?>
        <span>Vous n'êtes pas connecté ! Veuillez vous <a href="connexion.php">connecter</a> ou vous <a href="inscription.php">inscrire</a> !</span>
    <?php } ?>
?>
</body>

<?php include ("footer.php");?>