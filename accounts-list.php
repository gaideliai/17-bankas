<?php 
require __DIR__.'/bootstrap.php';

// _d($_POST);

if (!isset($_SESSION['login']) || $_SESSION['login'] != 1) {
    header('Location: '.$URL.'login.php');
    die();
}


if(isset($_POST['delete'])) {
    
    _d($data);

    foreach ($data as $key => $account) {
        if ($account['account'] == $_POST['delete']) {
            unset($data[$key]);
            $_SESSION['note'] = "Sąskaita nr. ". $_POST['delete']. " ištrinta";
        }
    }
    
   
    _d($data);

    file_put_contents(__DIR__ .'/accounts.json', json_encode($data));

    header('Location: '.$URL.'accounts-list.php');
    die();
}

if(isset($_SESSION['note'])) {
    echo $_SESSION['note'];
    unset($_SESSION['note']);
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
                    <a href=<?=$URL.'add.php?account='.$account['account']?> style="font-family:arial;font-size:13px;">Pridėti lėšų</a><br>
                    <a href=<?=$URL.'deduct.php?account='.$account['account']?> style="font-family:arial;font-size:13px;">Nuskaičiuoti lėšas</a>
                </form>
            </td>
        </tr>
    <?php endforeach ?>

    </table>
</body>
</html>