<?php 
require __DIR__.'/bootstrap.php';

if (!isset($_SESSION['login']) || $_SESSION['login'] != 1) {
    header('Location: '.$URL.'login.php');
    die();
}


if(isset($_POST['delete'])) {

    foreach ($data as $key => $account) {
        if ($account['account'] == $_POST['delete']) {
            if ($account['balance'] == 0) {
                unset($data[$key]);
                $_SESSION['note'] = "Sąskaita ". $_POST['delete']. " ištrinta";
            }
            else {
                $_SESSION['note'] = '<span style="color:red;font-weight:bold;">Sąskaitos '. $_POST['delete']. ' ištrinti negalima</span>';
            }
            
        }
    }    

    file_put_contents(__DIR__ .'/accounts.json', json_encode($data));

    header('Location: '.$URL.'accounts-list.php');
    die();
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table {        
        border-collapse: collapse;
        max-width: 100%;
    }

    td, th {
        border: 1px solid #ddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #eee;
    }
</style>
<body>
    <header>
        <nav>
            <a href=<?=$URL.'new-account.php'?>>Nauja sąskaita</a>
            <a href=<?=$URL.'login.php?logout'?>>Atsijungti</a>
        </nav>       
    </header>
    <h2>Sąskaitų sąrašas</h2>

<?php
if(isset($_SESSION['note'])) {
    echo $_SESSION['note'];
    unset($_SESSION['note']);
}

?>
    <br>
    <table>
        <tr>
            <th>Vardas</th>
            <th>Pavardė</th>
            <th>Asmens kodas</th>
            <th>Sąskaitos numeris</th>
            <th>Tvarkyti sąskaitą</th>
        </tr>

    <?php foreach ($data as $account) :?>
        <tr>
            <td><?= $account['name'] ?></td>
            <td><?= $account['surname'] ?></td>
            <td><?= $account['id'] ?></td>
            <td><?= $account['account'] ?></td>
            <td>
                <form action="" method="post">
                    <button type="submit" name="delete" value="<?= $account['account'] ?>">Ištrinti sąskaitą</button><br>
                    <a href=<?=$URL.'add.php?account='.$account['account']?>>Pridėti lėšų</a><br>
                    <a href=<?=$URL.'deduct.php?account='.$account['account']?>>Nuskaičiuoti lėšas</a>
                </form>
            </td>
        </tr>
    <?php endforeach ?>

    </table>
</body>
</html>