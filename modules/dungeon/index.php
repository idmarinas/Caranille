<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une jointure entre les 3 tables car_monsters, car_towns, car_towns_monsters pour récupérer les monstres lié à la ville
$monsterQueryList = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
WHERE townMonsterMonsterId = monsterId
AND townMonsterTownId = townId
AND townId = ?");
$monsterQueryList->execute([$townId]);
$monsterRow = $monsterQueryList->rowCount();

//Si plusieurs monstres ont été trouvé
if ($monsterRow > 0)
{
    ?>
    
    <form method="POST" action="selectedMonster.php">
        Liste des monstres : <select name="battleMonsterId" class="form-control">
                
            <?php
            //On fait une boucle sur tous les résultats
            while ($monster = $monsterQueryList->fetch())
            {
                //On récupère les informations du monstre
                $monsterId = stripslashes($monster['monsterId']); 
                $monsterName = stripslashes($monster['monsterName']);
                $monsterLevel = stripslashes($monster['monsterLevel']);
                $monsterLimited = stripslashes($monster['monsterLimited']);
                $monsterQuantity = stripslashes($monster['monsterQuantity']);

                //Si le monstre n'est pas limité on l'affiche
                if ($monsterLimited == "No")
                {
                    ?>
                    <option value="<?php echo $monsterId ?>"><?php echo "Niveau $monsterLevel - $monsterName" ?></option>
                    <?php
                }
                //Si le monstre est limité
                else
                {
                    //On vérifie si il en reste et si c'est le cas on l'affiche
                    if ($monsterQuantity > 0)
                    {
                        ?>
                        <option value="<?php echo $monsterId ?>"><?php echo "Niveau $monsterLevel - $monsterName" ?></option>
                        <?php
                    }
                }
                
            }
            ?>
                
        </select>
        <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
        <input type="submit" name="enter" class="btn btn-default form-control" value="Lancer le combat">
    </form>
    
    <?php
}
//S'il n'y a aucun monstre de disponible on prévient le joueur
else
{
    echo "Il n'y a aucun monstre de disponible.";
}
$monsterQueryList->closeCursor();

require_once("../../html/footer.php"); ?>