<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPlaceShopPlaceId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminPlaceShopPlaceId'])
    && $_POST['adminPlaceShopPlaceId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminPlaceShopPlaceId = htmlspecialchars(addslashes($_POST['adminPlaceShopPlaceId']));

        //On fait une requête pour vérifier si le lieu choisit existe
        $townQuery = $bdd->prepare('SELECT * FROM car_places 
        WHERE placeId = ?');
        $townQuery->execute([$adminPlaceShopPlaceId]);
        $townRow = $townQuery->rowCount();

        //Si le lieu existe
        if ($townRow == 1)
        {
            //On fait une requête pour afficher la liste des magasins de cette lieu
            $townShopQuery = $bdd->prepare("SELECT * FROM car_shops, car_places, car_places_shops
            WHERE townShopShopId = shopId
            AND townShopplaceId = placeId
            AND placeId = ?
			ORDER BY shopName");
            $townShopQuery->execute([$adminPlaceShopPlaceId]);
            $townShopRow = $townShopQuery->rowCount();

            //S'il existe un ou plusieurs magasins dans le lieu on affiche le menu déroulant
            if ($townShopRow > 0) 
            {
                ?>
                
                <form method="POST" action="deletePlaceShop.php">
                    Magasins présent dans le lieu : <select name="adminTownShopShopId" class="form-control">
                            
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($townShop = $townShopQuery->fetch())
                        {
                            //On récupère les informations du magasin
                            $adminTownShopShopId = stripslashes($townShop['shopId']);
                            $adminTownShopShopName = stripslashes($townShop['shopName']);
                            ?>
                            <option value="<?php echo $adminTownShopShopId ?>"><?php echo "$adminTownShopShopName"; ?></option>
                            <?php
                        }
                        $townShopQuery->closeCursor();
                        ?>
                        
                    </select>
                    <input type="hidden" name="adminPlaceShopPlaceId" value="<?php echo $adminPlaceShopPlaceId ?>">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer le magasin">
                </form>
                
                <hr>

                <?php
            }
            $townShopQuery->closeCursor();

            //On fait une requête pour afficher la liste des magasins du jeu qui ne sont pas dans le lieu
            $shopQuery = $bdd->prepare("SELECT * FROM car_shops
            WHERE (SELECT COUNT(*) FROM car_places_shops
            WHERE townShopplaceId = ?
            AND townShopShopId = shopId) = 0
			ORDER BY shopName");
            $shopQuery->execute([$adminPlaceShopPlaceId]);
            $shopRow = $shopQuery->rowCount();
            //S'il existe un ou plusieurs magasin on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($shopRow > 0) 
            {
                ?>
                
                <form method="POST" action="addPlaceShop.php">
                    Magasins disponible : <select name="adminTownShopShopId" class="form-control">
                            
                            <?php
                            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                            while ($shop = $shopQuery->fetch())
                            {
                                //On récupère les informations du magasin
                                $adminTownShopShopId = stripslashes($shop['shopId']);
                                $adminTownShopShopName = stripslashes($shop['shopName']);
                                ?>
                                <option value="<?php echo $adminTownShopShopId ?>"><?php echo "$adminTownShopShopName"; ?></option>
                                <?php
                            }
                            ?>
                            
                        </select>
                    
                    <input type="hidden" name="adminPlaceShopPlaceId" value="<?php echo $adminPlaceShopPlaceId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter le magasin">
                </form>
                
                <?php
            }
            else
            {
                echo "Il n'y a actuellement aucun magasin";
            }
            $shopQuery->closeCursor();
            ?>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le lieu n'exite pas
        else
        {
            echo "Erreur : lieu indisponible";
        }
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur : Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");