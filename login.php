<?php
include 'database.php';

if (isset($_POST['submit'])) {
    $mail_connect = htmlspecialchars($_POST['mail_connect']);
    $password_connect = htmlspecialchars(($_POST['password_connect']));
    date_default_timezone_set('Europe/Paris');
    $date = strftime('%Y-%m-%d %H:%M:%S');
    
    if (isset($mail_connect) AND isset($password_connect)) {
        $password_connect_hash = password_hash($password_connect, PASSWORD_DEFAULT);

        $database->insert("connexions", [
            "mail" => $mail_connect,
            "password" => $password_connect_hash,
            "date" => $date,
            ]);

        $user = $database->get("users","*",['mail' => $mail_connect]);

        if ($user)
        {
            $user_not_blocked = $database->get("users_blocked", ['mail'=>$mail_connect]);

            if ($user_not_blocked) {
                $error = "<p style = \"color: red;\">Votre compte est bloqué pour le moment !!</p>";
            }
            else
            {
            $nbr_connexion_failed = $database->count("connexions_failed",["mail"=>$mail_connect]);

                if($nbr_connexion_failed < 6)
                {
                    $times = $user['block_or_null'];

                    if ($times == null) {

                        if (password_verify($password_connect,$user['password']))
                        {
                            $database->delete("users_blocked",['mail'=>$mail_connect]);
                            //$token = substr(md5(uniqid($user['id']))0,4);
                            $_SESSION['mail'] = $user['mail'];
                            echo '<script>alert("Bienvenue")</script>';
                            header("Location: home.php");
                        }
                        else
                        {
                            $error = "<p style = \"color: red;\">Votre mot de passe est incorrect !!</p>";
                            $database->insert("connexions_failed", [
                                "mail" => $mail_connect,
                                "date" => $date,
                                ]);
                        }
                    }
                    else
                    {
                        $error = "<p style = \"color: red;\">Votre compte est toujours bloqué, veuillez retentez plus tard.</p>";
                    }
                }
                else
                {
                    $error = "<p style = \"color: red;\">Votre nombre de tentatives de connexions est trop grand, votre compte est bloqué pendant 15 minutes, merci de votre compréhension.</p>";
                    $database->insert("users_blocked", [
                        "mail" => $mail_connect,
                        "date" => $date,
                        ]);
                    $database->insert("users", [
                        "block_or_null" => $mail_connect,
                        ]);
                }
            }
            
        }
        else
        {
            $error = "<p style = \"color: red;\">Votre identifiant n'existe pas !!</p>";
        }
    
    }
    else
    {
        $error = "<p style = \"color: red;\">Tous les champs doivent être remplis</p>";
    }
};
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,400;0,700;1,100;1,400;1,700&display=swap" rel="stylesheet"> 
</head>
<body>

    <h1>ENREGISTREMENT DE LOGS</h1>

    <form action="" method="post" class="form_connexion">
        <h3 class="title_form">connexion</h3>
            <table summary="formulaire de connexion" class="table_connexion">
                <tbody>
                    <tr>
                        <td style="text-align:right">
                            <label for="login">login : </label>
                        </td>
                        <td>
                            <input type="email" name="mail_connect" id="mail_connect" placeholder="your_mail@domaine.fr">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:right">
                            <label for="login">mot de passe : </label>
                        </td>
                        <td>
                            <input type="password" name="password_connect" id="password_connect" placeholder="mot de passe">
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="validé" name="submit">
            <?php
    if(isset($error)){
        echo $error;
    };
    ?>
    </form>
   
</body>
</html>