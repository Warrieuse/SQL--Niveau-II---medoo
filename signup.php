<?php
require 'database.php';

if (isset($_POST['validation'])) {
    if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mailConf']) AND !empty($_POST['mdp']) AND !empty($_POST['mdpConf']) AND !empty($_POST['statut'] AND !empty($_POST['reponse_secret'])))
    {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mail = htmlspecialchars($_POST['mail']);
        $mailConf = htmlspecialchars($_POST['mailConf']);
        $mdp = ($_POST['mdp']); 
        $mdpConf = ($_POST['mdpConf']);
        $statut = htmlspecialchars($_POST['statut']);
        $CGVD = ($_POST['cgvd']);
        $question_secret = htmlspecialchars($_POST['question_secret']);
        $reponse_secret = htmlspecialchars($_POST['reponse_secret']);

        date_default_timezone_set('Europe/Paris');
        $date = strftime('%Y-%m-%d %H:%M:%S');

        $mdpRegex = '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/';
        $mdpTest = str_word_count($mdp);

        $pseudolength = strlen($pseudo);

        if($pseudolength <= 255)
        {
            $select_pseudo = $database->select("users","name",["name"=>$pseudo]);

            if($select_pseudo[0] == 0)
            {
                if($mail == $mailConf)
                {
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL))
                    {
                        $select_mail = $database->select("users","mail",["mail"=>$mail]);

                        if ($select_mail[0] == 0)
                        {
                            if (((preg_match($mdpRegex, $mdp)) == 1) AND ($mdpTest == 1)) // test regex mdp 1M 1n 1nbr & 1!.
                            {
                                if(isset($CGVD))
                                {
                                    if(!empty($statut))
                                    {
                                        if($mdp == $mdpConf) 
                                        {
                                            $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                                            $database->insert("users", [
                                                "name" => $pseudo,
                                                "mail" => $mail,
                                                "password" => $mdp,
                                                "date" => $date,
                                                "statut" => $statut,
                                                "question_secret" => $question_secret,
                                                "reponse_secret" => $reponse_secret,
                                            ]);
                                            //$welcome = echo "<script>alert("Bienvenue".$pseudo."<br>votre compte à bien été créé.")</script>"
                                            header ('location: login.php');
                                        }
                                        else
                                        {
                                            $error = "<p style = \"color: red;>\">Vos mots de passe ne sont pas identiques.</p>";
                                        }
                                    }
                                    else
                                    {
                                        $error = "<p style = \"color: red;>\">Vous n'avez pas définit votre statut !!!</p>";
                                    }
                                }
                                else
                                {
                                    $error = "<p style = \"color: red;>\">Vous n'avez pas accepté les conditions général de vente !!!</p>";
                                }
                            }
                            else
                            {
                                $error = "<p style = \"color: red;>\">Votre mot de passe n'est pas correct - Regardez les conditions ci-dessus.</p>";
                            }
                        }
                        else
                        {
                            $error = "<p style = \"color: red;>\">Cette adresse mail est déjà utilisé.</p>";
                        }
                    }
                    else
                    {
                        $error = "<p style = \"color: red;>\">Votre adresse mail n'est pas valide !</p>";
                    }

                }
                else
                {
                    $error = "<p style = \"color: red;>\">Vos adresses mail ne sont pas identiques.</p>";
                }
            }
            else
            {
                $error = "<p style = \"color: red;>\">Votre pseudo existe déjà !!</p>";
            }
        }
        else
        {
            $error = "<p style = \"color: red;>\">Votre pseudo ne dois pas dépasser 255 caractères</p>";
        }

    }
    else
    {
        $error = "<p style = \"color: red;>\">Tous les champs doivent être remplis</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body class="container-fluid">
<fieldset>
        <section class="row justify-content-center">
        <legend class="h1">Inscription</legend>
        <br>
        <form action="" method="POST" class="table-responsive col-10 col-md-8 col-lg-10">
            <table>
                <tr class="form-group">
                    <td style="text-align:right">
                        <label for="pseudo">Pseudo :</label>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" size="50" value="<?php if (isset($pseudo)) {echo $pseudo;}?>" > <!--Si Pseudo Sécurisé est remplis et valide alors on le garde affiché dans le form IDEM pour les uatres champs hormis MDP-->
                    </td>
                </tr>

                <tr class="form-group">
                    <td style="text-align:right">
                        <label for="mail">Mail :</label>
                    </td>
                    <td>
                        <input class="form-control" type="email" name="mail" id="mail" placeholder="votreemail@domaine.fr" size="50" value="

                        <?php if (isset($mail)) {echo $mail;} ?>" >
                    </td>
                </tr>

                <tr class="form-group">
                    <td style="text-align:right">
                        <label for="mailConf">Confirmation du mail :</label>
                    </td>
                    <td>
                        <input class="form-control" type="email" name="mailConf" id="mailConf" placeholder="Confirmez votre email" size="50" value="

                        <?php if (isset($mailConf)) {echo $mailConf;} ?>" >
                    </td>
                </tr>

                <tr class="form-group">
                    <td style="text-align:right">
                        <label for="mdp">Mot de passe :</label>
                    </td>
                    <td>
                        <input class="form-control" type="password" name="mdp" id="mdp" placeholder="EntrezVotreMotDePasse*1" size="50">
                    </td>
                </tr>

                <tr class="form-group">
                    <td style="text-align:right">
                        <label for="mdpConf">Confirmation du mot de passe :</label>
                    </td>
                    <td>
                        <input class="form-control" type="password" name="mdpConf" id="mdpConf" placeholder="Confirmez votre mot de passe" size="50">
                    </td>
                </tr>

                <tr>
                    <td style="text-align:right">
                        <label>Vous êtes :</label>
                    </td>
                    <td>
                    <div class="custom-control custom-radio custom-control-inline pl-5 mb-2">
                        <input class="custom-control-input"  type="radio" name="statut" id="statut" value="particulier">
                        <label class="custom-control-label" for="statut">un particulier</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input class="custom-control-input" type="radio" name="statut" id="statut" value="professionnel">
                        <label class="custom-control-label" for="statut">un professionnel</label>
                    </div>
                    </td>
                </tr>

            </table>
            <br><br>
            <div class="input-group input-group-lg">
                <div class="input-group-prepend">
                    <div class="input-group-addon p-2">
                    <input type="checkbox" id="cgvd" name="cgvd" required>
                    </div>
                    <div class="input-group-text p-2">
                    <label class="form-control" for="scales" name="cgvd">Je reconnais avoir pris connaissance des conditions d’utilisation et y adhère totalement.</label>
                    </div>
                </div>
            </div>
<hr>
            <table>
                <tr class="form-group m-1">
                    <td style="text-align:right">
                        <label for="question_secret">Veuillez choisir votre question secrète :</label>
                    </td>
                    <td>
                        <select class="form-control" name="question_secret" id="question_secret" required>
                            <option value="Quel est le nom de famille de votre mère ?">Quel est le nom de famille de votre mère ?</option>
                            <option value="Quel est votre animal favoris ?">Quel est votre animal favoris ?</option>
                            <option value="Le nom de votre animal de compagnie">Le nom de votre animal de compagnie</option>
                        </select>
                    </td>
                </tr>
                <tr class="form-group">
                    <td></td>
                    <td><input class="form-control" type="text" name="reponse_secret" id="reponse_secret" size="50" placeholder="Entrez votre réponse" required></td>
                </tr>
            </table>
            <br>

            <div>
                <input class="form-control" type="submit" value="Envoyer" name="validation">
            </div>
    </section>
        </form>
        <div id="ConditionMdp" style="display:block; text-align:center; text-decoration:none;" class="row">
            <ul class="list-group" class="col-12">Votre mot de passe doit contenir :
                <li class="list-group-item list-group-item-action list-group-item-danger">Au moins 10 caractères</li>
                <li class="list-group-item list-group-item-action list-group-item-danger">Au moins 1 majuscule</li>
                <li class="list-group-item list-group-item-action list-group-item-danger">Au moins 1 minuscule</li>
                <li class="list-group-item list-group-item-action list-group-item-danger">Au moins 1 chiffre</li>
                <li class="list-group-item list-group-item-action list-group-item-danger">Au moins 1 caractère spécial</li>
            </ul>
        </div>
        <?php

        if(isset($error) OR isset($message))
        {
            echo $error;
            echo $message;
        }
        ?>

        <script src="main.js"></script>
    </fieldset>

</body>
</html>