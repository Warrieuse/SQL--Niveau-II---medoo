<?php
require 'database.php';

//echo $_SESSION['mail'];

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
    <!--<link rel="stylesheet" href="style.css">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <header>
        <h1>Bienvenue dans votre espace     <?php echo $_SESSION['name'];?>         </h1>
        </header>
    <main class="container-fluid">
        <section class="row justify-content-center">
            <fieldset class="border col-10 col-md-8 col-lg-6 table-responsive">
            <legend class="title_form">Voici la liste de tous les membres</legend>
                <table class="table">
                        <thead class="thead-light">
                            <th scope="col">Id</th>
                            <th scope="col">Name</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Date d'inscription</th>
                            <th scope="col">Statut</th>
                            <th></th>
                            <th></th>
                        </thead>

                        <tbody>
                        <?php foreach ($users as $data){ ?><!--je boucle en piochant dans $users mes $data--> 
                            <tr class="table-info">
                                <td scope="row"><?= $data['id'];?></td><!-- le = est un raccourcci de echo-->
                                <td><?= $data['name'];?></td>
                                <td><?= $data['mail'];?></td>
                                <td><?= $data['date'];?></td>
                                <td><?= $data['statut'];?></td>
                                <td><a href="update.php?id='.$data['id'].'" class="btn btn-secondary">Modifier</a></td>
                                <!--button supprimer avec fenetre modal pour conf-->
                                <td><button class="btn btn-secondary" data-toggle="modal" data-target="#delete_modal">Supprimer</button></td>
                                        <div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer le compte de <?=$data['name'];?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                                <a type="button" href="delete.php?id='.$data['id'].'" class="btn btn-primary">Oui, j'en suis sûr</a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                            </tr>
                        <?php } //je ferme ma boucle?>
                        </tbody>
                </table>
                <div class="button">
                    <a href="signup.php">créer un nouvel utilisateur</a>
                </div>
            </fieldset>
        </section>
    </main>
    <footer></footer>
</body>
</html>