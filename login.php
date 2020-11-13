<?php
include 'database.php';

if (isset($_POST['submit'])) {
    // je sécurise  mes données et les affectes à une variable.
    $mail_connect = htmlspecialchars($_POST['mail_connect']);
    $password_connect = htmlspecialchars(($_POST['password_connect']));
    date_default_timezone_set('Europe/Paris');
    $date = strftime('%Y-%m-%d %H:%M:%S');

    //je crée une VARIABLE qui enregistre l'adresse IP de l'utilisateur.
    $ip = $_SERVER['REMOTE_ADDR'];
    // je vérifie si mon user est dans la table ip_block avec mon adress ip récupéré au dessus.
    $ip_block = $database->get("ip_block", "*", ['ip' => $ip]);

        //Si l'ip n'est pas présente en BDD alors je continue.
        if ($ip_block == false) {

            if (isset($mail_connect) and isset($password_connect)) {
                //je hache le password pour le comparer par la suite
                $password_connect_hash = password_hash($password_connect, PASSWORD_DEFAULT);
                // je rentre la tentative de connection en base de données avec la date qui comprend l'heure également
                $database->insert("connexions", [
                    "mail" => $mail_connect,
                    "password" => $password_connect_hash,
                    "date" => $date,
                ]);
                //je crée une VARIABLE qui récupère les données de mon users SI celui si existe en base de données USERS.
                $user = $database->get("users", "*", ['mail' => $mail_connect]);
                //je vérifie que l'email entré est bien dans la base de données de mes users.
                if ($user) {
                    //je vérifie si l'adresse ip de mon users est dans la table connexion_failed et si elle est présente à plusieurs reprises.
                    $ip_failed = $database->count("connexion_failed", "*", ['ip' => $ip,]);
                    // je vérifie si cette adresse ip est présente moins de 5 fois
                    if ($ip_failed < 5) {
                        // c'est le cas alors je continue et donc je vérifie si le password est correct


                        if (password_verify($password_connect, $user['password'])) {
                            //toutes les conditions de connections sont remplis alors je peut drop mon ip dans connexions_failed.
                            $database->delete("connexion_failed", [
                                "AND" => [
                                    "ip" => $ip,
                                ]
                            ]);

                            //$token = substr(md5(uniqid($user['id']))0,4);
                            $_SESSION['id'] = $user['id'];
                            $_SESSION['name'] = $user['name'];
                            $_SESSION['mail'] = $user['mail'];
                            $_SESSION['date'] = $user['date'];
                            $_SESSION['statut'] = $user['statut'];
                            echo '<script>alert("Bienvenue")</script>';
                            header("Location: home.php");
                        } else {
                            $error = "<p style = \"color: red;\">Votre mot de passe est incorrect !!</p>";
                            //j'enregistre la tentative de connexions dans la table connexion_failed.
                            $database->insert("connexion_failed", [
                                "ip" => $ip,
                                "date" => $date,
                            ]);
                        }
                    } else {
                        // j'enregistre cette ip dans une nouvelle table ip_block
                        $database->insert("ip_block", [
                            "ip" => $ip,
                            "mail"=>$mail_connect,
                            "date" => $date,
                        ]);
                        $error = "<p style = \"color: red;\">Votre compte est bloqué pendant une durée de 15 min</p>";
                    }
                } else {   //pas de email dans ma base de données donc je prévient l'utilisateur
                    $error = "<p style = \"color: red;\">Votre identifiant n'existe pas !!</p>";
                }
            } else {
                $error = "<p style = \"color: red;\">Tous les champs doivent être remplis</p>";
            }
            } else {
                $error = "<p style = \"color: red;\">Votre compte est bloqué !!</p>";
            }
            
        } else {
            //Sachant que le compte est bloqué pendant une certaine durée je dois avant tout vérifier si le temps d'attente est écoulé ou pas.
            //je compare la date d'entrée  de l'ip_block avec l'heure actuelle.
            //pour ca je transforme mes dates en secondes écoulées depuis 1970.
            $date_ip_block = date_timestamp_get($ip_block['date']);
            //var_dump($date_ip_block);
            $date_now = date_timestamp_get($date);
            //var_dump($date_now);
            $fifteen_minute = 60 * 15;
            $date_ip_unblock = $date_ip_block + $fifteen_minute;
            // je compare les 2 dates en secondes il faut que 15 min se soit déroulé depuis l'enregistrement de ip dans ip_block
                if ($date_now >= $date_ip_unblock) {
                    //Et donc 15 min ce sont écoulés je vais donc DROP mes entrées.
                    $database->delete("ip_block", [
                        "AND" => [
                            "ip" => $ip,
                            "mail"=>$mail_connect,
                        ]
                    ]);
                    $error = "<p style = \"color: red;\">Votre compte est débloqué vous pouvez retentez.</p>";
                } else {
                    $error = "<p style = \"color: red;\">Votre compte est bloqué !!</p>";
            }
    
};
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body class="container-fluid m-3">
    <div class="row p-4">
    <h1 class="col-12 col-md-8 col-lg-6">ENREGISTREMENT DE LOGS</h1>
    </div>
    <form action="" method="post" class="row table-responsive">
        <h3 class="col-12 col-md-8 col-lg-6">connexion</h3>
        <table class="table col-10 col-md-8 ">
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
        if (isset($error)) {
            echo $error;
        };
        ?>
    </form>

</body>

</html>