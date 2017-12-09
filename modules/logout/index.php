<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }

//On détruit la session complète
session_destroy();

//On redirige le joueur vers la page d'accueil
header("Location: ../../index.php");

require_once("../../html/footer.php"); ?>