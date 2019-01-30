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
&& isset($_POST['viewItem']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;
        
        //Comme il y a un nouveau formulaire on régénère un nouveau token
        $_SESSION['token'] = uniqid();
        
        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if(ctype_digit($_POST['itemId'])
        && $_POST['itemId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $itemId = htmlspecialchars(addslashes($_POST['itemId']));
                    
            //On fait une requête pour vérifier si l'objet choisit existe
            $itemQuery = $bdd->prepare("SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$itemId]);
            $itemRow = $itemQuery->rowCount();
    
            //Si l'objet existe
            if ($itemRow == 1) 
            {
                //On fait une requête pour avoir la liste des objets du personnage
                $itemInventoryQuery = $bdd->prepare("SELECT * FROM  car_items, car_items_types, car_inventory 
                WHERE itemItemTypeId = itemTypeId
                AND itemId = inventoryItemId
                AND itemTypeName = 'Parchment' 
                AND inventoryCharacterId = ?
                AND inventoryItemId = ?");
                $itemInventoryQuery->execute([$characterId, $itemId]);
                $itemInventoryRow = $itemInventoryQuery->rowCount();
                
                //Si le personnage possède cet objet
                if ($itemInventoryRow == 1) 
                {
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($itemInventory = $itemInventoryQuery->fetch())
                    {
                        //On récupère les informations de l'objet
                        $itemId = stripslashes($itemInventory['itemId']);
                        $itemTypeName = stripslashes($itemInventory['itemTypeName']);
                        $itemTypeNameShow = stripslashes($itemInventory['itemTypeNameShow']);
                        $itemPicture = stripslashes($itemInventory['itemPicture']);
                        $itemName = stripslashes($itemInventory['itemName']);
                        $itemDescription = stripslashes($itemInventory['itemDescription']);
                        $itemQuantity = stripslashes($itemInventory['inventoryQuantity']);
                        $itemHpEffect = stripslashes($itemInventory['itemHpEffect']);
                        $itemMpEffect = stripslashes($itemInventory['itemMpEffect']);
                        $itemStrengthEffect = stripslashes($itemInventory['itemStrengthEffect']);
                        $itemMagicEffect = stripslashes($itemInventory['itemMagicEffect']);
                        $itemAgilityEffect = stripslashes($itemInventory['itemAgilityEffect']);
                        $itemDefenseEffect = stripslashes($itemInventory['itemDefenseEffect']);
                        $itemDefenseMagicEffect = stripslashes($itemInventory['itemDefenseMagicEffect']);
                        $itemWisdomEffect = stripslashes($itemInventory['itemWisdomEffect']);
                        $itemProspectingEffect = stripslashes($itemInventory['itemProspectingEffect']);
                        $itemSalePrice = stripslashes($itemInventory['itemSalePrice']);
                        ?>
    
                        <p><img src="<?php echo $itemPicture ?>" height="100" width="100"></p>
                        
                        <table class="table">
                            
                            <tr>
                                <td>
                                    Type
                                </td>
                                
                                <td>
                                    <?php echo $itemTypeNameShow; ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    Nom
                                </td>
                                
                                <td>
                                    <?php echo $itemName; ?>
                                </td>
                            </tr>
                                
                            <tr>
                                <td>
                                    Description
                                </td>
                                
                                <td>
                                    <?php echo nl2br($itemDescription); ?>
                                </td>
                            </tr>
                                
                            <tr>
                                <td>
                                    Quantité
                                </td>
                                
                                <td>
                                    <?php echo $itemQuantity; ?>
                                </td>
                            </tr>
                                
                            <tr>
                                <td>
                                    Effet(s)
                                </td>
                                
                                <td>
                                <?php
                                    //Si l'équipement augmente les HP on l'affiche
                                    if ($itemHpEffect > 0)
                                    {
                                        echo "+ $itemHpEffect HP<br />";
                                    }
                                    
                                    //Si l'équipement augmente les MP on l'affiche
                                    if ($itemMpEffect > 0)
                                    {
                                        echo "+ $itemMpEffect MP<br />";
                                    }
                                    
                                    //Si l'équipement augmente la force on l'affiche
                                    if ($itemStrengthEffect > 0)
                                    {
                                        echo "+ $itemStrengthEffect Force<br />";
                                    }
                                    
                                    //Si l'équipement augmente la magie on l'affiche
                                    if ($itemMagicEffect > 0)
                                    {
                                        echo "+ $itemMagicEffect Magie<br />";
                                    }
                                    
                                    //Si l'équipement augmente l'agilité on l'affiche
                                    if ($itemAgilityEffect > 0)
                                    {
                                        echo "+ $itemAgilityEffect Agilité<br />";
                                    }
                                    
                                    //Si l'équipement augmente la défense on l'affiche
                                    if ($itemDefenseEffect > 0)
                                    {
                                        echo "+ $itemDefenseEffect Défense<br />";
                                    }
                                    
                                    //Si l'équipement augmente la défense magique on l'affiche
                                    if ($itemDefenseMagicEffect > 0)
                                    {
                                        echo "+ $itemDefenseMagicEffect Défense Magic<br />";
                                    }
                                    
                                    //Si l'équipement augmente la sagesse on l'affiche
                                    if ($itemWisdomEffect > 0)
                                    {
                                        echo "+ $itemWisdomEffect Sagesse<br />";
                                    }
                                    
                                    //Si l'équipement augmente la prospection on l'affiche
                                    if ($itemProspectingEffect > 0)
                                    {
                                        echo "+ $itemProspectingEffect Prospection<br />";
                                    }
                                    ?>
                                </td>
                            </tr>
                                
                            <tr>
                                <td>
                                    Prix de vente
                                </td>
                                
                                <td>
                                    <?php echo $itemSalePrice; ?>
                                </td>
                            </tr>
                                
                            <tr>
                                <td>
                                    Actions
                                </td>
                                
                                <td>
                                    <form method="POST" action="useParchment.php">
                                        <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                        <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                                        <input type="submit" class="btn btn-default form-control" name="use" value="Utiliser">
                                    </form>
                                    <form method="POST" action="../../modules/inventory/sale.php">
                                        Quantité à vendre : <input type="number" name="saleQuantity" value="1" class="form-control" required>
                                        <input type="hidden" name="itemId" value="<?php echo $itemId ?>">
                                        <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                                        <input type="submit" class="btn btn-default form-control" name="sale" value="Vendre">
                                    </form>
                                </td>
                            </tr>
                        </table>
                                    
                        <hr>
            
                        <form method="POST" action="index.php">
                            <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
                            <input type="submit" class="btn btn-default form-control" value="Retour">
                        </form>
                        
                        <?php
                    }
                }
                //Si le joueur ne possède pas ce parchemin
                else
                {
                    echo "Erreur : Impossible de visualiser un parchemin que vous ne possédez pas.";
                }
                $itemInventoryQuery->closeCursor();
            }
            //Si le parchemin n'exite pas
            else
            {
                echo "Erreur : Ce parchemin n'existe pas";
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