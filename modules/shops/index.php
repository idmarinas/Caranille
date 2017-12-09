<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une jointure entre les 3 tables car_shops, car_towns, car_towns_shops pour récupérer les magasin lié à la ville
$shopQuery = $bdd->prepare("SELECT * FROM car_shops, car_towns, car_towns_shops
WHERE townShopShopId = shopId
AND townShopTownId = townId
AND townId = ?");
$shopQuery->execute([$townId]);
$shopRow = $shopQuery->rowCount();

//Si plusieurs magasins ont été trouvé
if ($shopRow > 0)
{
    ?>

    <form method="POST" action="selectedShop.php">
        Liste des magasins : <select name="shopId" class="form-control">

            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($shop = $shopQuery->fetch())
            {
                //On récupère les informations du magasin
                $shopId = stripslashes($shop['shopId']); 
                $shopName = stripslashes($shop['shopName']);
                ?>
                <option value="<?php echo $shopId ?>"><?php echo $shopName ?></option>
                <?php
            }
            ?>

        </select>
        <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
        <input type="submit" name="enter" class="btn btn-default form-control" value="Entrer dans le magasin">
    </form>
    
    <?php
}
//S'il n'y a aucun magasin de disponible on prévient le joueur
else
{
    echo "Il n'y a aucun magasin de disponible.";
}
$shopQuery->closeCursor();

require_once("../../html/footer.php"); ?>