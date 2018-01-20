<?php
$timeStart = microtime(true);
session_start();
require_once("../../kernel/config.php");

//On récupère les informations de configuration du jeu
require_once("../../kernel/configuration/index.php");
//On récupère toutes les informations du compte
require_once("../../kernel/account/index.php");
//On récupère toutes les informations du personnage grâce au compte
require_once("../../kernel/character/index.php");
//On récupère toutes les informations des type d'équipement
require_once("../../kernel/equipmentType/index.php");
//On vérifie si le personnage est actuellement dans un combat
require_once("../../kernel/battle/index.php");
//On vérifie si le personnage est actuellement dans un lieu. Si c'est le cas on récupère toutes les informations du lieu
require_once("../../kernel/place/index.php");
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">

        <title><?php echo $gameName ?></title>

        <!-- Bootstrap core CSS -->
        <link href="../../css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="../../css/navbar-top-fixed.css" rel="stylesheet">
    </head>

    <body>
		<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
			<a class="navbar-brand" href="../../modules/main/index.php">Panel Administration</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ressources</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class="dropdown-item" href="../../administration/accounts/index.php">Comptes/Personnages</a>
							<a class="dropdown-item" href="../../administration/equipments/index.php">Equipements</a>
							<a class="dropdown-item" href="../../administration/items/index.php">Objets</a>
							<a class="dropdown-item" href="../../administration/monsters/index.php">Monstres</a>
							<a class="dropdown-item" href="../../administration/parchments/index.php">Parchemins</a>
							<a class="dropdown-item" href="../../administration/places/index.php">lieux</a>
							<a class="dropdown-item" href="../../administration/races/index.php">Classes</a>
							<a class="dropdown-item" href="../../administration/shops/index.php">Magasins</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Scénario</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class="dropdown-item" href="../../administration/chapters/index.php">Chapitres</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Communication</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class="dropdown-item" href="../../administration/news/index.php">News</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Offrir</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class="dropdown-item" href="../../administration/offerExperience/index.php">Expérience</a>
							<a class="dropdown-item" href="../../administration/offerGold/index.php">Pièce(s) d'or</a>
							<a class="dropdown-item" href="../../administration/offerItem/index.php">Objet</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Configuration</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class="dropdown-item" href="../../administration/configuration/index.php">Jeu</a>
							<a class="dropdown-item" href="../../administration/itemsTypes/index.php">Types d'objets</a>
						</div>
					</li>
				</ul>
				<ul class="navbar-nav pull-right"> 
					<li class="nav-item dropdown">
						 <a class="nav-link" href="../../index.php">Retour au jeu</a>
					</li>
				</ul>
			</div>
		</nav>

		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="container">
			<div class="jumbotron">