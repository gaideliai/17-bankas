<?php
require __DIR__ . '/bootstrap.php';

_d($_GET);
_d($_POST);

if (!isset($_SESSION['login']) || $_SESSION['login'] != 1) {
    header('Location: '.$URL.'login.php');
    die();
}

if (isset($_GET['account'])) {
    foreach ($data as $key => $account) {
        if ($account['account'] == $_GET['account']) {
            $name = $account['name'];
            $surname = $account['surname'];
            $IBAN = $account['account'];
            $id = $account['id'];
            // $balance = $account['balance'];
            $balance = number_format($account['balance'], 2, ',', ' ').' Eur';
        }
    }
}


if (isset($_POST['deduct'])) {
    foreach ($data as $key => $account) {
        if ($_POST['deduct'] == $account['account']) {
            $data[$key]['balance'] -= $_POST['balance']; 
            $balance = number_format($account['balance'], 2, ',', ' ').' Eur';

            $name = $account['name'];
            $surname = $account['surname'];
            $IBAN = $account['account'];
            $id = $account['id'];
        }
    }
    file_put_contents(__DIR__ .'/accounts.json', json_encode($data));
    $_SESSION['note'] = 'Lėšos nurašytos iš sąskaitos nr. '.$IBAN;
    header("Location: $URL"."deduct.php?account=".$IBAN);
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
</head>
<body>
    <header>
        <nav>
            <a href=<?=$URL.'accounts-list.php'?>>Sąskaitų sąrašas</a>
            <a href=<?=$URL.'new-account.php'?>>Nauja sąskaita</a>
            <a href=<?=$URL.'login.php?logout'?>>Atsijungti</a>
        </nav>       
    </header>
<h2>Lėšų nurašymas</h2>
    <table>
        <tr>
            <th>Vardas</th>
            <th>Pavardė</th>
            <th>Asmens kodas</th>
            <th>Sąskaitos numeris</th>
            <th>Balansas</th>
            <th>Tvarkyti sąskaitą</th>
        </tr>
  
        <tr>
            <td><?= $name ?></td>
            <td><?= $surname ?></td>
            <td><?= $id ?></td>
            <td><?= $IBAN ?></td>
            <td><?= $balance ?></td>
            <td>
                <form action="" method="post">
                    <input type="number" step="0.01" name="balance">
                    <button type="submit" name="deduct" value=<?= $IBAN?>>Nurašyti lėšas</button>
                </form>
            </td>
        </tr> 

    </table>
</body>
</html>