<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemId']) 
&& isset($_POST['adminItemPicture'])
&& isset($_POST['adminItemName'])
&& isset($_POST['adminItemDescription'])
&& isset($_POST['adminItemHpEffects'])
&& isset($_POST['adminItemMpEffect'])
&& isset($_POST['adminItemStrengthEffect'])
&& isset($_POST['adminItemMagicEffect'])
&& isset($_POST['adminItemAgilityEffect'])
&& isset($_POST['adminItemDefenseEffect'])
&& isset($_POST['adminItemDefenseMagicEffect'])
&& isset($_POST['adminItemWisdomEffect'])
&& isset($_POST['adminItemProspectingEffect'])
&& isset($_POST['adminItemPurchasePrice'])
&& isset($_POST['adminItemSalePrice'])
&& isset($_POST['token'])
&& isset($_POST['finalEdit']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['adminItemId'])
        && ctype_digit($_POST['adminItemHpEffects'])
        && ctype_digit($_POST['adminItemMpEffect'])
        && ctype_digit($_POST['adminItemStrengthEffect'])
        && ctype_digit($_POST['adminItemMagicEffect'])
        && ctype_digit($_POST['adminItemAgilityEffect'])
        && ctype_digit($_POST['adminItemDefenseEffect'])
        && ctype_digit($_POST['adminItemDefenseMagicEffect'])
        && ctype_digit($_POST['adminItemWisdomEffect'])
        && ctype_digit($_POST['adminItemProspectingEffect'])
        && ctype_digit($_POST['adminItemPurchasePrice'])
        && ctype_digit($_POST['adminItemSalePrice'])
        && $_POST['adminItemId'] >= 0
        && $_POST['adminItemHpEffects'] >= 0
        && $_POST['adminItemMpEffect'] >= 0
        && $_POST['adminItemStrengthEffect'] >= 0
        && $_POST['adminItemMagicEffect'] >= 0
        && $_POST['adminItemAgilityEffect'] >= 0
        && $_POST['adminItemDefenseEffect'] >= 0
        && $_POST['adminItemDefenseMagicEffect'] >= 0
        && $_POST['adminItemWisdomEffect'] >= 0
        && $_POST['adminItemProspectingEffect'] >= 0
        && $_POST['adminItemPurchasePrice'] >= 0
        && $_POST['adminItemSalePrice'] >= 0)
        {
            //On récupère l'id du formulaire précédent
            $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
            
            //On fait une requête pour vérifier si le parchemin choisit existe
            $itemQuery = $bdd->prepare("SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$adminItemId]);
            $itemRow = $itemQuery->rowCount();

            //Si le parchemin existe
            if ($itemRow == 1) 
            {
                //On récupère les informations du formulaire
                $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
                $adminItemPicture = htmlspecialchars(addslashes($_POST['adminItemPicture']));
                $adminItemName = htmlspecialchars(addslashes($_POST['adminItemName']));
                $adminItemDescription = htmlspecialchars(addslashes($_POST['adminItemDescription']));
                $adminItemHpEffects = htmlspecialchars(addslashes($_POST['adminItemHpEffects']));
                $adminItemMpEffect = htmlspecialchars(addslashes($_POST['adminItemMpEffect']));
                $adminItemStrengthEffect = htmlspecialchars(addslashes($_POST['adminItemStrengthEffect']));
                $adminItemMagicEffect = htmlspecialchars(addslashes($_POST['adminItemMagicEffect']));
                $adminItemAgilityEffect = htmlspecialchars(addslashes($_POST['adminItemAgilityEffect']));
                $adminItemDefenseEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseEffect']));
                $adminItemDefenseMagicEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseMagicEffect']));
                $adminItemWisdomEffect = htmlspecialchars(addslashes($_POST['adminItemWisdomEffect']));
                $adminItemProspectingEffect = htmlspecialchars(addslashes($_POST['adminItemProspectingEffect']));
                $adminItemPurchasePrice = htmlspecialchars(addslashes($_POST['adminItemPurchasePrice']));
                $adminItemSalePrice = htmlspecialchars(addslashes($_POST['adminItemSalePrice']));
        
                //On met à jour le parchemin dans la base de donnée
                $updateItems = $bdd->prepare("UPDATE car_items 
                SET itemPicture = :adminItemPicture,
                itemName = :adminItemName,
                itemDescription = :adminItemDescription,
                itemHpEffect = :adminItemHpEffects,
                itemMpEffect = :adminItemMpEffect,
                itemStrengthEffect = :adminItemStrengthEffect,
                itemMagicEffect = :adminItemMagicEffect,
                itemAgilityEffect = :adminItemAgilityEffect,
                itemDefenseEffect = :adminItemDefenseEffect,
                itemDefenseMagicEffect = :adminItemDefenseMagicEffect,
                itemWisdomEffect = :adminItemWisdomEffect,
                itemProspectingEffect = :adminItemProspectingEffect,
                itemPurchasePrice = :adminItemPurchasePrice,
                itemSalePrice = :adminItemSalePrice
                WHERE itemId = :adminItemId');
                $updateItems->execute([
                'adminItemPicture' => $adminItemPicture,
                'adminItemName' => $adminItemName,
                'adminItemDescription' => $adminItemDescription,
                'adminItemHpEffects' => $adminItemHpEffects,
                'adminItemMpEffect' => $adminItemMpEffect,
                'adminItemStrengthEffect' => $adminItemStrengthEffect,
                'adminItemMagicEffect' => $adminItemMagicEffect,
                'adminItemAgilityEffect' => $adminItemAgilityEffect,
                'adminItemDefenseEffect' => $adminItemDefenseEffect,
                'adminItemDefenseMagicEffect' => $adminItemDefenseMagicEffect,
                'adminItemWisdomEffect' => $adminItemWisdomEffect,
                'adminItemProspectingEffect' => $adminItemProspectingEffect,
                'adminItemPurchasePrice' => $adminItemPurchasePrice,
                'adminItemSalePrice' => $adminItemSalePrice,
                'adminItemId' => $adminItemId]);
                $updateItems->closeCursor();
                ?>

                le parchemin a bien été mit à jour

                <hr>
                    
                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php
            }
            //Si le parchemin n'exite pas
            else
            {
                echo "Erreur : Parchemin indisponible";
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
    echo "Erreur : Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");