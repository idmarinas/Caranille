<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPlaceShopPlaceId'])
&& isset($_POST['adminTownShopShopId'])
&& isset($_POST['add']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminPlaceShopPlaceId'])
    && ctype_digit($_POST['adminTownShopShopId'])
    && $_POST['adminPlaceShopPlaceId'] >= 1
    && $_POST['adminTownShopShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminPlaceShopPlaceId = htmlspecialchars(addslashes($_POST['adminPlaceShopPlaceId']));
        $adminTownShopShopId = htmlspecialchars(addslashes($_POST['adminTownShopShopId']));

        //On fait une requête pour vérifier si le lieu choisie existe
        $townQuery = $bdd->prepare('SELECT * FROM car_places 
        WHERE placeId = ?');
        $townQuery->execute([$adminPlaceShopPlaceId]);
        $townRow = $townQuery->rowCount();

        //Si le lieu existe
        if ($townRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($town = $townQuery->fetch())
            {
                //On récupère les informations du lieu
                $adminTownShopplacePicture = stripslashes($town['placePicture']);
                $adminTownShopplaceName = stripslashes($town['placeName']);
            }
            $townQuery->closeCursor();

            //On fait une requête pour vérifier si le magasin choisit existe
            $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
            WHERE shopId = ?');
            $shopQuery->execute([$adminTownShopShopId]);
            $shopRow = $shopQuery->rowCount();

            //Si le magasin existe
            if ($shopRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($shop = $shopQuery->fetch())
                {
                    //On récupère les informations du magasin
                    $adminTownShopShopPicture = stripslashes($shop['shopPicture']);
                    $adminTownShopShopName = stripslashes($shop['shopName']);
                }

                //On fait une requête pour vérifier si le magasin n'est pas déjà dans cette lieu
                $townShopQuery = $bdd->prepare('SELECT * FROM car_places_shops 
                WHERE townShopplaceId = ?
                AND townShopShopId = ?');
                $townShopQuery->execute([$adminPlaceShopPlaceId, $adminTownShopShopId]);
                $townShopRow = $townShopQuery->rowCount();

                //Si le magasin n'est pas dans le lieu
                if ($townShopRow == 0) 
                {
                    ?>
            
                    <p>ATTENTION</p> 

                    Vous êtes sur le point d'ajouter le magasin <em><?php echo $adminTownShopShopName ?></em> dans le lieu <em><?php echo $adminTownShopplaceName ?></em>.<br />
                    Confirmez-vous l'ajout ?

                    <hr>
                        
                    <form method="POST" action="addPlaceShopEnd.php">
                        <input type="hidden" class="btn btn-default form-control" name="adminPlaceShopPlaceId" value="<?php echo $adminPlaceShopPlaceId ?>">
                        <input type="hidden" class="btn btn-default form-control" name="adminTownShopShopId" value="<?php echo $adminTownShopShopId ?>">
                        <input type="submit" class="btn btn-default form-control" name="finalAdd" value="Je confirme">
                    </form>
                    
                    <hr>

                    <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                    </form>
                    
                    <?php
                }
                //Si le magasin est déjà dans cette lieu
                else
                {
                    ?>
                    
                    Erreur : Ce magasin est déjà dans cette lieu
                    
                    <form method="POST" action="managePlaceShop.php">
                        <input type="hidden" name="adminPlaceShopPlaceId" value="<?php echo $adminPlaceShopPlaceId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                    </form>
                    
                    <?php
                }
                $townShopQuery->closeCursor();
            }
            //Si le magasin existe pas
            else
            {
                echo "Erreur : Ce magasin n'existe pas";
            }
            $townShopQuery->closeCursor();
        }
        //Si le lieu existe pas
        else
        {
            echo "Erreur : Cette lieu n'existe pas";
        }
        $shopQuery->closeCursor();
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
    echo "Erreur : Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");