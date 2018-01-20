<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPlaceMonsterPlaceId'])
&& isset($_POST['adminplaceMonsterMonsterId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminPlaceMonsterPlaceId'])
    && ctype_digit($_POST['adminplaceMonsterMonsterId'])
    && $_POST['adminPlaceMonsterPlaceId'] >= 1
    && $_POST['adminplaceMonsterMonsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminPlaceMonsterPlaceId = htmlspecialchars(addslashes($_POST['adminPlaceMonsterPlaceId']));
        $adminplaceMonsterMonsterId = htmlspecialchars(addslashes($_POST['adminplaceMonsterMonsterId']));

        //On fait une requête pour vérifier si le lieu choisie existe
        $placeQuery = $bdd->prepare('SELECT * FROM car_places 
        WHERE placeId = ?');
        $placeQuery->execute([$adminPlaceMonsterPlaceId]);
        $placeRow = $placeQuery->rowCount();

        //Si le lieu existe
        if ($placeRow == 1) 
        {
            //On fait une requête pour vérifier si le monstre choisit existe
            $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
            WHERE monsterId = ?');
            $monsterQuery->execute([$adminplaceMonsterMonsterId]);
            $monsterRow = $monsterQuery->rowCount();

            //Si le monstre choisit  exite
            if ($monsterRow == 1) 
            {
                //On fait une requête pour vérifier si le monstre choisit  existe bien dans le lieu
                $monsterQuery = $bdd->prepare('SELECT * FROM car_places_monsters 
                WHERE placeMonsterPlaceId = ?
                AND placeMonsterMonsterId = ?');
                $monsterQuery->execute([$adminPlaceMonsterPlaceId, $adminplaceMonsterMonsterId]);
                $monsterRow = $monsterQuery->rowCount();

                //Si l'équipement existe
                if ($monsterRow == 1) 
                {
                    //On supprime l'équipement de la base de donnée
                    $placeMonsterDeleteQuery = $bdd->prepare("DELETE FROM car_places_monsters
                    WHERE placeMonsterMonsterId = ?");
                    $placeMonsterDeleteQuery->execute([$adminplaceMonsterMonsterId]);
                    $placeMonsterDeleteQuery->closeCursor();
                    ?>

                    Le monstre a bien été retiré du lieu

                    <hr>
                        
                    <form method="POST" action="managePlaceMonster.php">
                        <input type="hidden" name="adminPlaceMonsterPlaceId" value="<?php echo $adminPlaceMonsterPlaceId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                    </form>
                    
                    <?php
                }
                //Si le monstre n'exite pas
                else
                {
                    echo "Erreur : Monstre indisponible";
                }
                $monsterQuery->closeCursor();
            }
            //Si le monstre existe pas
            else
            {
                echo "Erreur : Ce monstre n'existe pas";
            }
            $monsterQuery->closeCursor();
        }
        //Si le lieu existe pas
        else
        {
            echo "Erreur : Cette lieu n'existe pas";
        }
        $placeQuery->closeCursor();
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