<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['token']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
		$_SESSION['token'] = NULL;
		
		//Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();
		
        //On fait une requête pour avoir la liste des équipements du personnage
        $equipmentQuery = $bdd->prepare("SELECT * FROM  car_items, car_items_types, car_inventory 
        WHERE itemItemTypeId = itemTypeId
        AND itemId = inventoryItemId
        AND (itemTypeName = 'Armor' 
        OR itemTypeName = 'Boots' 
        OR itemTypeName = 'Gloves' 
        OR itemTypeName = 'Helmet' 
        OR itemTypeName = 'Weapon')
        AND inventoryCharacterId = ?
		ORDER BY itemItemTypeId, itemName");
        $equipmentQuery->execute([$characterId]);
        $equipmentRow = $equipmentQuery->rowCount();
        
        //Si un ou plusieurs équipements ont été trouvé
        if ($equipmentRow > 0)
        {
            ?>
            
            <form method="POST" action="viewEquipment.php">
                Liste des équipements : <select name="itemId" class="form-control">
                        
                    <?php
                    //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
                    while ($equipment = $equipmentQuery->fetch())
                    {
                        //On récupère les informations de l'équippement
                        $equipmentId = stripslashes($equipment['itemId']); 
                        $equipmentName = stripslashes($equipment['itemName']);
                        $equipmentQuantity = stripslashes($equipment['inventoryQuantity']);
                        $equipmentTypeName = stripslashes($equipment['itemTypeName']);
                        $equipmentTypeNameShow = stripslashes($equipment['itemTypeNameShow']);
                        ?>
                        <option value="<?php echo $equipmentId ?>"><?php echo "[$equipmentTypeNameShow] - $equipmentName (Quantité: $equipmentQuantity)" ?></option>
                        <?php
                    }
                    ?>
                        
                </select>
                <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                <center><input type="submit" name="viewEquipment" class="btn btn-default form-control" value="Plus d'information"></center>
            </form>
            
            <?php
        }
        //Si toutes les variables $_POST n'existent pas
        else
        {
            echo "Vous ne possédez aucun équipements.";
        }
        $equipmentQuery->closeCursor();
        
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