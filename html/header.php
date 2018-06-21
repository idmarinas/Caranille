<?php
ob_start();
//On définit un emplacement de sauvegarde des sessions
ini_set('session.save_path', realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
//On démarre le module des sessions de PHP
session_start();
//On récupère le temps Unix actuel une première fois
$timeStart = microtime(true);
//On inclue le fichier de configuration qui contient les paramètre de connexion SQL ainsi que la création d'un objet $bdd pour les requêtes SQL
require_once("../../kernel/config.php");
//On récupère les informations de configuration du jeu
require_once("../../kernel/configuration/index.php");
//Si la session $_SESSION['token'] est vide c'est que le joueur à validé un formulaire
if (empty($_SESSION['token']))
{
	//On génère un token qu'on stock dans une session pour sécuriser les formulaires
	$_SESSION['token'] = uniqid(); 
}
//Si le joueur est connecté on va récupérer toutes les informations du joueur (Compte, Personnage, Combat en cours...)
if (isset($_SESSION['account']['id']))
{
    //On récupère toutes les informations du compte
    require_once("../../kernel/account/index.php");
    //On récupère toutes les informations du personnage grâce au compte
    require_once("../../kernel/character/index.php");
    //On vérifie si le personnage est actuellement dans un combat de monstre. Si c'est le cas on récupère toutes les informations du monstre
    require_once("../../kernel/battle/index.php");
    //On vérifie le nombre d'invitation de combat du joueur
    require_once("../../kernel/battleInvitation/index.php");
    //On récupère toutes les informations des équipements équipé au personnage
    require_once("../../kernel/equipment/index.php");
    //On récupère toutes les informations des type d'équipement
    require_once("../../kernel/equipmentType/index.php");
    //On vérifie le nombre d'offre dans le marché
    require_once("../../kernel/market/index.php");
    //On vérifie le nombre de message de notifications non lue
    require_once("../../kernel/notification/index.php");
    //On vérifie le nombre de message de conversation privée non lu
    require_once("../../kernel/privateConversation/index.php");
    //On vérifie si le personnage est actuellement dans un lieu. Si c'est le cas on récupère toutes les informations du lieu
    require_once("../../kernel/place/index.php");
    //On vérifie le nombre de d'échange en cours
    require_once("../../kernel/trade/index.php");
    //On vérifie le nombre de demande d'échange en cours
    require_once("../../kernel/tradeRequest/index.php");
}
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
			<a class="navbar-brand" href="../../modules/main/index.php"><?php echo $gameName ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accueil</a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<a class="dropdown-item" href="../../modules/main/index.php">Actualité</a>
							<a class="dropdown-item" href="../../modules/presentation/index.php">Présentation</a>
							<a class="dropdown-item" href="../../modules/race/index.php">Les classes</a>
							<a class="dropdown-item" href="../../modules/contact/index.php">Contact</a>
							<a class="dropdown-item" href="../../modules/about/index.php">A propos</a>
						</div>
					</li>

					<?php
					//Si le joueur est connecté on affiche le menu du jeu
					if (isset($_SESSION['account']['id']))
					{
						?>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Personnage (<?php echo $battleInvitationRow ?>)</a>
							<div class="dropdown-menu" aria-labelledby="dropdown01">
								<?php
								//Si le joueur possèdes une invtation de combaz
								if ($battleInvitationRow > 0)
								{
									?>
									<a class="dropdown-item" href="../../modules/battleInvitation/index.php">Invitation de combat (<?php echo $battleInvitationRow ?>)</a>
									<?php
								}
								?>
								<a class="dropdown-item" href="../../modules/character/index.php">Fiche complète</a>
								<a class="dropdown-item" href="../../modules/inventory/index.php">Inventaire</a>
								<a class="dropdown-item" href="../../modules/skillPoint/index.php">Points de compétences</a>
							</div>
						</li>
								
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aventure</a>
							<div class="dropdown-menu" aria-labelledby="dropdown01">
								<a class="dropdown-item" href="../../modules/story/index.php">Continuer l'aventure</a>

								<?php
								//Si characterplaceId est supérieur ou égal à un le joueur est dans un lieu. On met le raccourcit vers le lieu
								if($characterPlaceId >= 1)
								{
									?>

									<a class="dropdown-item" href="../../modules/place/index.php">Lieu actuel</a>

									<?php
								}
								//Si characterplaceId n'est pas supérieur ou égal à un le joueur est dans aucun lieu. On met le raccourcit vers la carte du monde
								else
								{
									?>

									<a class="dropdown-item" href="../../modules/map/index.php">Carte du monde</a>

									<?php
								}
								?>
								<a class="dropdown-item" href="../../modules/bestiary/index.php">Bestiaire</a>
								<a class="dropdown-item" href="../../modules/travelogue/index.php">Carnet de voyage</a>
							</div>
						</li>
								
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Communauté (<?php echo $privateConversationNumberRow + $tradeRequestRow + $tradeRow + $marketOfferQuantityRow ?>)</a>
							<div class="dropdown-menu" aria-labelledby="dropdown01">
								<a class="dropdown-item" href="../../modules/arena/index.php">Arène (PVP)</a>
								<a class="dropdown-item" href="../../modules/chat/index.php">Chat</a>
								<a class="dropdown-item" href="../../modules/privateConversation/index.php">Messagerie privée (<?php echo $privateConversationNumberRow ?>)</a>
								<a class="dropdown-item" href="../../modules/tradeRequest/index.php">Place des échanges (<?php echo $tradeRequestRow + $tradeRow ?>)</a>
								<a class="dropdown-item" href="../../modules/market/index.php">Le marché (<?php echo $marketOfferQuantityRow ?>)</a>
							</div>
						</li>      
					<?php
					}
					?>
				</ul>
				<ul class="navbar-nav pull-right"> 
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mon compte <?php if(isset($_SESSION['account']['id'])) { echo "($notificationNumberRow)"; } ?></a>
						<div class="dropdown-menu" aria-labelledby="dropdown01">
							<?php
							//Si le joueur est connecté on lui donne la possibilité de se déconnecter
							if (isset($_SESSION['account']['id']))
							{
								?>
								
								<a class="dropdown-item" href="../../modules/account/index.php">Informations</a>
								<a class="dropdown-item" href="../../modules/notification/index.php">Notifications (<?php echo $notificationNumberRow ?>)</a>
								
								<?php
								switch ($accountAccess)
								{
									case 0:
									
									break;

									case 1:
									?>
									<?php
									break;

									case 2:
									?>

									<a class="dropdown-item" href="../../administration/main/index.php">Administration</a>
									
									<?php
									break;
								}
								?>
								
								<a class="dropdown-item" href="../../modules/logout/index.php">Déconnexion</a>
									
								<?php
							}
							//Sinon on propose au joueur de s'inscrire ou se connecter
							else
							{
								?>

								<a class="dropdown-item" href="../../modules/login/index.php">Connexion</a>
								<a class="dropdown-item" href="../../modules/register/index.php">Inscription</a>
									
								<?php
							}
							?>
						</div>
					</li>
				</ul>
			</div>
		</nav>

		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="container">
			<div class="jumbotron">
