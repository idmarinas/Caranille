<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownShopTownId'])
&& isset($_POST['adminTownShopShopId'])
&& isset($_POST['add']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownShopTownId'])
    && ctype_digit($_POST['adminTownShopShopId'])
    && $_POST['adminTownShopTownId'] >= 1
    && $_POST['adminTownShopShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownShopTownId = htmlspecialchars(addslashes($_POST['adminTownShopTownId']));
        $adminTownShopShopId = htmlspecialchars(addslashes($_POST['adminTownShopShopId']));

        //On fait une requête pour vérifier si la ville choisie existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId = ?');
        $townQuery->execute([$adminTownShopTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($town = $townQuery->fetch())
            {
                //On récupère les informations de la ville
                $adminTownShopTownPicture = stripslashes($town['townPicture']);
                $adminTownShopTownName = stripslashes($town['townName']);
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

                //On fait une requête pour vérifier si le magasin n'est pas déjà dans cette ville
                $townShopQuery = $bdd->prepare('SELECT * FROM car_towns_shops 
                WHERE townShopTownId = ?
                AND townShopShopId = ?');
                $townShopQuery->execute([$adminTownShopTownId, $adminTownShopShopId]);
                $townShopRow = $townShopQuery->rowCount();

                //Si le magasin n'est pas dans la ville
                if ($townShopRow == 0) 
                {
                    ?>
            
                    <p>ATTENTION</p> 

                    Vous êtes sur le point d'ajouter le magasin <em><?php echo $adminTownShopShopName ?></em> dans la ville <em><?php echo $adminTownShopTownName ?></em>.<br />
                    Confirmez-vous l'ajout ?

                    <hr>
                        
                    <form method="POST" action="addTownShopEnd.php">
                        <input type="hidden" class="btn btn-default form-control" name="adminTownShopTownId" value="<?php echo $adminTownShopTownId ?>">
                        <input type="hidden" class="btn btn-default form-control" name="adminTownShopShopId" value="<?php echo $adminTownShopShopId ?>">
                        <input type="submit" class="btn btn-default form-control" name="finalAdd" value="Je confirme">
                    </form>
                    
                    <hr>

                    <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                    </form>
                    
                    <?php
                }
                //Si le magasin est déjà dans cette ville
                else
                {
                    ?>
                    
                    Erreur : Ce magasin est déjà dans cette ville
                    
                    <form method="POST" action="manageTownShop.php">
                        <input type="hidden" name="adminTownShopTownId" value="<?php echo $adminTownShopTownId ?>">
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
        //Si la ville existe pas
        else
        {
            echo "Erreur : Cette ville n'existe pas";
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