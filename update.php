<?php
require 'database.php';
if(isset($_POST['enregistrer'])){
    $name = htmlspecialchars($_POST['pseudo']);
    var_dump($name);
    $mail = htmlspecialchars($_POST['mail']);
    var_dump($mail);
    $statut = htmlspecialchars($_POST['statut']);
    var_dump($statut);
    if((isset($name)) OR (isset($mail)) OR (isset($statut))){
        $database->update("users",[
            "name" => $name,
            "mail" => $mail,
            "statut" => $statut,
        ],[
            "id" => $_GET['id'],
        ]);
        header ('location: home.php');
    }

}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<fieldset class="container">
        <legend style="font-size:2em; font-weight:bold;">Modifier mon profil</legend>
        <br>
        <form action="" method="POST" class="form_connexion">
            <table>
                <tr>
                    <td style="text-align:right">
                        <label for="pseudo">Pseudo :</label>
                    </td>
                    <td>
                        <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" size="50" value="<?php echo $_SESSION['name'];?>" > 
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label for="mail">Mail :</label>
                    </td>
                    <td>
                        <input type="email" name="mail" id="mail" placeholder="votreemail@domaine.fr" size="50" value="<?php echo $_SESSION['mail'];?>" >
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label for="mdp">Mot de passe :</label>
                    </td>
                    <td>
                        <input type="password" name="mdp" id="mdp" placeholder="********************" size="50">
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label>Vous êtes :</label>
                    </td>
                    <td>
                        <input type="radio" name="statut" id="statut" value="particulier">
                        <label for="statut">un particulier</label>
                        <input type="radio" name="statut" id="statut" value="professionnel">
                        <label for="statut">un professionnel</label>
                    </td>
                </tr>

            </table>
            <div>
                <input type="submit" value="Enregistrer" name="enregistrer">
                <a href="home.php?id='.$data['id'].'">retour</a>
            </div>
    
</body>
</html>
<?php
?>