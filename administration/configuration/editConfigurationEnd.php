<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si l'utilisateur à cliqué sur le bouton finalEdit
if (isset($_POST['adminGameName'])
&& isset($_POST['adminGamePresentation'])
&& isset($_POST['adminGameMaxLevel'])
&& isset($_POST['adminGameExperience'])
&& isset($_POST['adminGameSkillPoint'])
&& isset($_POST['adminGameExperienceBonus'])
&& isset($_POST['adminGameGoldBonus'])
&& isset($_POST['adminGameDropBonus'])
&& isset($_POST['adminGameAccess'])
&& isset($_POST['token'])
&& isset($_POST['editEnd']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['adminGameMaxLevel'])
        && ctype_digit($_POST['adminGameExperience'])
        && ctype_digit($_POST['adminGameSkillPoint'])
        && ctype_digit($_POST['adminGameExperienceBonus'])
        && ctype_digit($_POST['adminGameGoldBonus'])
        && ctype_digit($_POST['adminGameDropBonus'])
        && $_POST['adminGameExperience'] >= 0
        && $_POST['adminGameSkillPoint'] >= 0
        && $_POST['adminGameExperienceBonus'] >= 0
        && $_POST['adminGameGoldBonus'] >= 0
        && $_POST['adminGameDropBonus'] >= 0)
        {
            //On récupère les informations du formulaire
            $adminGameName = htmlspecialchars(addslashes($_POST['adminGameName']));
            $adminGamePresentation = htmlspecialchars(addslashes($_POST['adminGamePresentation']));
            $adminGameMaxLevel = htmlspecialchars(addslashes($_POST['adminGameMaxLevel']));
            $adminGameExperience = htmlspecialchars(addslashes($_POST['adminGameExperience']));
            $adminGameSkillPoint = htmlspecialchars(addslashes($_POST['adminGameSkillPoint']));
            $adminGameExperienceBonus = htmlspecialchars(addslashes($_POST['adminGameExperienceBonus']));
            $adminGameGoldBonus = htmlspecialchars(addslashes($_POST['adminGameGoldBonus']));
            $adminGameDropBonus = htmlspecialchars(addslashes($_POST['adminGameDropBonus']));
            $adminGameAccess = htmlspecialchars(addslashes($_POST['adminGameAccess']));

            //On fait une requête dans la base de donnée pour récupérer les informations du jeu
            $configurationQuery = $bdd->query("SELECT * FROM car_configuration");

            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($configuration = $configurationQuery->fetch())
            {
                //On récupère les informations du jeu
                $adminGameId = stripslashes($configuration['configurationId']);
                $adminOldGameName = stripslashes($configuration['configurationGameName']);
                $adminOldGamePresentation = stripslashes($configuration['configurationPresentation']);   
                $adminOldGameExperience = stripslashes($configuration['configurationExperience']);
                $adminOldGameSkillPoint = stripslashes($configuration['configurationSkillPoint']);
                $adminOldGameExperienceBonus = stripslashes($configuration['configurationExperienceBonus']);
                $adminOldGameGoldBonus = stripslashes($configuration['configurationGoldBonus']);
                $adminOldGameDropBonus = stripslashes($configuration['configurationDropBonus']);
                $adminOldGameAccess = stripslashes($configuration['configurationAccess']);
            }
            $configurationQuery->closeCursor();

            //Si la base d'experience ou les PC par niveau ont changé (ou les deux)
            if ($adminGameExperience != $adminOldGameExperience 
            || $adminGameSkillPoint != $adminOldGameSkillPoint
            || $adminGameExperience != $adminOldGameExperience && $adminGameSkillPoint != $adminOldGameSkillPoint)
            {
                //On fait une recherche de tous les joueurs dans la base de donnée
                $characterQuery = $bdd->query("SELECT * FROM car_characters");

                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $adminCharacterId = stripslashes($character['characterId']);
                    $adminCharacterName = stripslashes($character['characterName']);

                    //On remet les stats du joueurs à zéro
                    $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                    characterLevel = 1,
                    characterHpMin = 100,
                    characterHpMax = 100,
                    characterHpSkillPoints = 0,
                    characterHpEquipments = 0,
                    characterHpTotal = 100,
                    characterMpMin = 10,
                    characterMpMax = 10,
                    characterMpSkillPoints = 0,
                    characterMpEquipments = 0,
                    characterMpTotal = 10,
                    characterStrength = 1,
                    characterStrengthSkillPoints = 0,
                    characterStrengthEquipments = 0,
                    characterStrengthTotal = 1,
                    characterMagic = 1,
                    characterMagicSkillPoints = 0,
                    characterMagicEquipments = 0,
                    characterMagicTotal = 1,
                    characterAgility = 1,
                    characterAgilitySkillPoints = 0,
                    characterAgilityEquipments = 0,
                    characterAgilityTotal = 1,
                    characterDefense = 1,
                    characterDefenseSkillPoints = 0,
                    characterDefenseEquipments = 0,
                    characterDefenseTotal = 1,
                    characterDefenseMagic = 1,
                    characterDefenseMagicSkillPoints = 0,
                    characterDefenseMagicEquipments = 0,
                    characterDefenseMagicTotal = 1, 
                    characterWisdom = 0,
                    characterWisdomSkillPoints = 0,
                    characterWisdomEquipments = 0,
                    characterWisdomTotal = 0,
                    characterProspecting = 0,
                    characterProspectingSkillPoints = 0,
                    characterProspectingEquipments = 0,
                    characterProspectingTotal = 0,
                    characterSkillPoints = 0,
                    characterExperience = characterExperienceTotal
                    WHERE characterId = :adminCharacterId");
                    $updateCharacter->execute(array(
                    'adminCharacterId' => $adminCharacterId));
                    $updateCharacter->closeCursor();

                    $adminNotificationDate = date('Y-m-d H:i:s');
                    $adminNotificationMessage = "Suite à une mise à jour de la base d'expérience pour monter de niveau ou du changement du nombre de PC obtenu par niveau, votre personnage a été remit au niveau 1. Toutes votre expérience accumulée vous a été redistribuée et vous conservez toujours vos objets, équipements (qui sont actuellement non équipé) et votre avancée dans le jeu.";

                    //On envoie une notification au joueur
                    $addNotification = $bdd->prepare("INSERT INTO car_notifications VALUES(
                    NULL,
                    :adminCharacterId,
                    :adminNotificationDate,
                    :adminNotificationMessage,
                    'No')");
                    $addNotification->execute(array(
                    'adminCharacterId' => $adminCharacterId,  
                    'adminNotificationDate' => $adminNotificationDate,
                    'adminNotificationMessage' => $adminNotificationMessage));
                    $addNotification->closeCursor();

                    echo "Remise à zéro du compte $adminCharacterName <br />";
                }

                //On fait une recherche de tous les équipements qui sont équipé pour les déséquipper
                $inventoryQuery = $bdd->prepare("SELECT * FROM car_inventory 
                WHERE inventoryEquipped = '1'");
                $inventoryQuery->execute([$characterId]);

                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($inventory = $inventoryQuery->fetch())
                {
                    //On récupère les informations de l'inventaire
                    $adminInventoryId = stripslashes($inventory['inventoryId']);

                    //On va rendre l'équipement non équipé
                    $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                    inventoryEquipped = 0
                    WHERE inventoryId = :adminInventoryId");
                    $updateInventory->execute(array(
                    'adminInventoryId' => $adminInventoryId));
                    $updateInventory->closeCursor();
                }
            }
            
            //On met à jour la configuration dans la base de donnée
            $updateConfiguration = $bdd->prepare("UPDATE car_configuration
            SET configurationGameName = :adminGameName,
            configurationPresentation = :adminGamePresentation,
            configurationMaxLevel = :adminGameMaxLevel,
            configurationExperience = :adminGameExperience,
            configurationSkillPoint = :adminGameSkillPoint,
            configurationExperienceBonus = :adminGameExperienceBonus,
            configurationGoldBonus = :adminGameGoldBonus,
            configurationDropBonus = :adminGameDropBonus,
            configurationAccess = :adminGameAccess');
            $updateConfiguration->execute([
            'adminGameName' => $adminGameName,
            'adminGamePresentation' => $adminGamePresentation,
            'adminGameMaxLevel' => $adminGameMaxLevel,
            'adminGameExperience' => $adminGameExperience,
            'adminGameSkillPoint' => $adminGameSkillPoint,
            'adminGameExperienceBonus' => $adminGameExperienceBonus,
            'adminGameGoldBonus' => $adminGameGoldBonus,
            'adminGameDropBonus' => $adminGameDropBonus,
            'adminGameAccess' => $adminGameAccess]);
            $updateConfiguration->closeCursor();
            ?>
            
            <br />La configuration a bien été mise à jour

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si tous les champs numérique ne contiennent pas un nombre
        else
        {
            echo "Erreur : Les champs de type numérique ne peuvent contenir qu'un nombre entier";
        }
    }
    //Si le token de sécurité n'est pas correct
    else
    {
        echo "Erreur : Impossible de valider le formulaire, veuillez réessayer";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");
