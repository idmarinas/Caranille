<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }
?>

<p>Offrir un objet</p>

<form method="POST" action="offerItem.php">
    Liste des personnages <select name="adminCharacterId" class="form-control">
        <option value="0">Tous les joueurs</option>
        
        <?php
        //On fait une recherche dans la base de donnée tous les personnages
        $characterQuery = $bdd->query("SELECT * FROM car_characters
        ORDER by characterName");
        
        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
        while ($character = $characterQuery->fetch())
        {
            $adminCharacterId = stripslashes($character['characterId']);
            $adminCharacterName =  stripslashes($character['characterName']);
            ?>
            <option value="<?php echo $adminCharacterId ?>"><?php echo "$adminCharacterName"; ?></option>
            <?php
        }
        $characterQuery->closeCursor();
        ?>
    
    </select>
    Liste des objets <select name="adminItemId" class="form-control">

        <?php
        //On fait une recherche dans la base de donnée tous les objets
        $itemQuery = $bdd->query("SELECT * FROM car_items, car_items_types
        WHERE itemItemTypeId = itemTypeId
        ORDER by itemItemTypeId, itemName");
        
        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
        while ($item = $itemQuery->fetch())
        {
            //On récupère les informations de l'objet
            $adminItemId = stripslashes($item['itemId']);
            $adminItemName = stripslashes($item['itemName']);
            $adminItemTypeName = stripslashes($item['itemTypeName']);
            $adminItemTypeNameShow = stripslashes($item['itemTypeNameShow']);
            ?>
            <option value="<?php echo $adminItemId ?>"><?php echo "[$adminItemTypeNameShow] - $adminItemName"; ?></option>
            <?php
        }
        $itemQuery->closeCursor();
        ?>
    
    </select>
    <input type="submit" name="manage" class="btn btn-default form-control" value="Offrir cet objet">
</form>

<?php require_once("../html/footer.php");