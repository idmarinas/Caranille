<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['token'])
&& isset($_POST['useFinal']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
		$_SESSION['token'] = NULL;
		
		//Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();
		
        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['itemId'])
        && $_POST['itemId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $itemId = htmlspecialchars(addslashes($_POST['itemId']));
    
            //On cherche à savoir si l'objet qui va se vendre appartient bien au joueur
            $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
            WHERE itemId = inventoryItemId
            AND inventoryCharacterId = ?
            AND itemId = ?");
            $itemQuery->execute([$characterId, $itemId]);
            $itemRow = $itemQuery->rowCount();
    
            //Si le personne possède cet objet
            if ($itemRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($item = $itemQuery->fetch())
                {
                    //On récupère les informations du parchemin
                    $inventoryId = stripslashes($item['inventoryId']);
                    $itemId = stripslashes($item['itemId']);
                    $itemPicture = stripslashes($item['itemPicture']);
                    $itemName = stripslashes($item['itemName']);
                    $itemDescription = stripslashes($item['itemDescription']);
                    $itemQuantity = stripslashes($item['inventoryQuantity']);
                    $itemHpEffect = stripslashes($item['itemHpEffect']);
                    $itemMpEffect = stripslashes($item['itemMpEffect']);
                    $itemStrengthEffect = stripslashes($item['itemStrengthEffect']);
                    $itemMagicEffect = stripslashes($item['itemMagicEffect']);
                    $itemAgilityEffect = stripslashes($item['itemAgilityEffect']);
                    $itemDefenseEffect = stripslashes($item['itemDefenseEffect']);
                    $itemDefenseMagicEffect = stripslashes($item['itemDefenseMagicEffect']);
                    $itemWisdomEffect = stripslashes($item['itemWisdomEffect']);
                    $itemProspectingEffect = stripslashes($item['itemProspectingEffect']);
                    $itemSalePrice = stripslashes($item['itemSalePrice']);
                }
                
                //On applique les ajouts du parchemin sur les stats du joueur
                $characterHpBonus = $characterHpBonus + $itemHpEffect;
                $characterMpBonus = $characterMpBonus + $itemMpEffect;
                $characterStrengthBonus = $characterStrengthBonus + $itemStrengthEffect;
                $characterMagicBonus = $characterMagicBonus + $itemMagicEffect;
                $characterAgilityBonus = $characterAgilityBonus + $itemAgilityEffect;
                $characterDefenseBonus = $characterDefenseBonus + $itemDefenseEffect;
                $characterDefenseMagicBonus = $characterDefenseMagicBonus + $itemDefenseMagicEffect;
                $characterWisdomBonus = $characterWisdomBonus + $itemWisdomEffect;
                $characterProspectingBonus = $characterProspectingBonus + $itemProspectingEffect;
    
                //Si le joueur possède plusieurs exemplaire de cet équipement/objet
                if ($itemQuantity > 1)
                {
                    //On met l'inventaire à jour
                    $updateInventory = $bdd->prepare("UPDATE car_inventory SET
                    inventoryQuantity = inventoryQuantity - 1
                    WHERE inventoryId = :inventoryId");
                    $updateInventory->execute(array(
                    'inventoryId' => $inventoryId));
                    $updateInventory->closeCursor();
                }
                //Si le joueur ne possède cet équipement/objet que en un seul exemplaire
                else
                {
                    //On supprime l'objet de l'inventaire
                    $updateInventory = $bdd->prepare("DELETE FROM car_inventory
                    WHERE inventoryId = :inventoryId");
                    $updateInventory->execute(array(
                    'inventoryId' => $inventoryId));
                    $updateInventory->closeCursor();
                }
                //On met le personnage à jour
                $updatecharacter = $bdd->prepare("UPDATE car_characters SET
                characterHpBonus = :characterHpBonus,
                characterMpBonus = :characterMpBonus,
                characterStrengthBonus = :characterStrengthBonus,
                characterMagicBonus = :characterMagicBonus,
                characterAgilityBonus = :characterAgilityBonus,
                characterDefenseBonus = :characterDefenseBonus,
                characterDefenseMagicBonus = :characterDefenseMagicBonus,
                characterWisdomBonus = :characterWisdomBonus,
                characterProspectingBonus = :characterProspectingBonus
                WHERE characterId = :characterId");
                $updatecharacter->execute(array(
                'characterHpBonus' => $characterHpBonus,
                'characterMpBonus' => $characterMpBonus,
                'characterStrengthBonus' => $characterStrengthBonus,
                'characterMagicBonus' => $characterMagicBonus,  
                'characterAgilityBonus' => $characterAgilityBonus,
                'characterDefenseBonus' => $characterDefenseBonus,
                'characterDefenseMagicBonus' => $characterDefenseMagicBonus,
                'characterWisdomBonus' => $characterWisdomBonus,  
                'characterProspectingBonus' => $characterProspectingBonus,
                'characterId' => $characterId));
                $updatecharacter->closeCursor();

                //On va maintenant finir par actualiser tous le personnage
                $updateCharacter = $bdd->prepare("UPDATE car_characters
                SET characterHpTotal = characterHpMax + characterHpSkillPoints + characterHpBonus + characterHpEquipments + characterHpGuild,
                characterMpTotal = characterMpMax + characterMpSkillPoints + characterMpBonus + characterMpEquipments + characterMpGuild,
                characterStrengthTotal = characterStrength + characterStrengthSkillPoints + characterStrengthBonus + characterStrengthEquipments + characterStrengthGuild,
                characterMagicTotal = characterMagic + characterMagicSkillPoints + characterMagicBonus + characterMagicEquipments + characterMagicGuild,
                characterAgilityTotal = characterAgility + characterAgilitySkillPoints + characterAgilityBonus + characterAgilityEquipments + characterAgilityGuild,
                characterDefenseTotal = characterDefense + characterDefenseSkillPoints + characterDefenseBonus + characterDefenseEquipments + characterDefenseGuild,
                characterDefenseMagicTotal = characterDefenseMagic + characterDefenseMagicSkillPoints + characterDefenseMagicBonus + characterDefenseMagicEquipments + characterDefenseMagicGuild,
                characterWisdomTotal = characterWisdom + characterWisdomSkillPoints + characterWisdomBonus + characterWisdomEquipments + characterWisdomGuild,
                characterProspectingTotal = characterProspecting + characterProspectingSkillPoints + characterProspectingBonus + characterProspectingEquipments + characterProspectingGuild
                WHERE characterId = :characterId");
                $updateCharacter->execute(['characterId' => $characterId]);
                $updateCharacter->closeCursor();
                ?>
                
                Vous venez d'utiliser le parchemin <?php echo $itemName ?>.
    
                <hr>
    
                <form method="POST" action="index.php">
                    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                    <input type="submit" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
            }
            else
            {
                echo "Erreur : Impossible d'utiliser un parchemin que vous ne possédez pas.";
            }
            $itemQuery->closeCursor(); 
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
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>