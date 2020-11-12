<?php
require 'database.php';

echo $_SESSION['mail'];

//1er étape lire et afficher la BDD sous forme de tableau html
$users = $database->query("SELECT * FROM <users>")->fetchAll(PDO::FETCH_ASSOC);
//var_dump($users);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Bienvenue dans votre espace     <?php echo $_SESSION['name'];?>         </h1>
        </header>
    <main>
        <fieldset class="border">
        <legend class="title_form">Voici la liste de tous les membres</legend>
            <table class="table_connexion">
                    <thead>
                        <th>Id</th>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Date d'inscription</th>
                        <th>Statut</th>
                        <th></th>
                        <th></th>
                    </thead>

                    <tbody>
                    <?php foreach ($users as $data){ ?><!--je boucle en piochant dans $users mes $data--> 
                        <tr>
                            <td><?= $data['id'];?></td><!-- le = est un raccourcci de echo-->
                            <td><?= $data['name'];?></td>
                            <td><?= $data['mail'];?></td>
                            <td><?= $data['date'];?></td>
                            <td><?= $data['statut'];?></td>
                            <td><a href="update.php?id='.$data['id'].'">Modifier</a></td>
                            <td><a href="delete.php?id='.$data['id'].'">Supprimer</a></td>
                        </tr>
                    <?php }?>
                    </tbody>
            </table>
        </fieldset>
    </main>
    <footer></footer>
</body>
</html>