<?php
require __DIR__ . '/bootstrap.php';

// _d($_GET);
// _d($_POST);

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
            // $id = $account['id'];
            // $balance = $account['balance'];
            $balance = number_format($account['balance'], 2, ',', ' ');//.' Eur';
        }
    }
}


if (isset($_POST['deduct'])) {
    foreach ($data as $key => $account) {
        if ($_POST['deduct'] == $account['account']) {

            if ($_POST['balance'] <= $data[$key]['balance'] && $_POST['balance'] > 0) {
                $data[$key]['balance'] = round(($data[$key]['balance'] - $_POST['balance']), 2);
                // $data[$key]['balance'] -= $_POST['balance'];
                $_SESSION['note'] = 'Lėšos nuskaitytos iš sąskaitos '.formatIban($IBAN);
            }
            elseif ($_POST['balance'] < 0) {
                $_SESSION['note'] = '<span style="color:red;">
                                    Suma turi būti teigiamas skaičius.</span>';
            }
            else {
                $_SESSION['note'] = '<span style="color:red;">
                                    Sąskaitoje nepakanka lėšų. Operacija neįvykdyta.</span>';
            }
            // round($account['balance'], 2);
            $balance = number_format($account['balance'], 2, ',', ' ');//.' Eur';

            $name = $account['name'];
            $surname = $account['surname'];
            $IBAN = $account['account'];
            // $id = $account['id'];
        }
    }
    file_put_contents(__DIR__ .'/accounts.json', json_encode($data));
    
    header('Location: '.$URL.'deduct.php?account='.$IBAN);
    die();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bankas</title>
    <link rel="stylesheet" href="./css/main.css">  
    <link rel="stylesheet" href="./css/font-awesome.min.css">  
</head>
<body>
    <header>
        <nav>
            <a href=<?=$URL.'accounts-list.php'?>>Sąskaitų sąrašas</a>
            <a href=<?=$URL.'new-account.php'?>>Nauja sąskaita</a>
            <a href=<?=$URL.'login.php?logout'?>>Atsijungti
                <i class="fa fa-sign-out"></i>
            </a>
        </nav>       
    </header>
    <h2>Lėšų nurašymas</h2>

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
            <!-- <th>Asmens kodas</th> -->
            <th>Sąskaitos numeris</th>
            <th>Balansas</th>
            <th>Valiuta</th>
            <th>Tvarkyti sąskaitą</th>
        </tr>
  
        <tr>
            <td><?= $name ?></td>
            <td><?= $surname ?></td>
            <!-- <td><?= $id ?></td> -->
            <td><?= formatIban($IBAN) ?></td>
            <td><?= $balance ?></td>
            <td>EUR</td>
            <td>
                <form action="" method="post">
                    <input type="number" step="0.01" name="balance">
                    <button type="submit" name="deduct" value=<?= $IBAN ?>>Nuskaičiuoti lėšas</button>
                </form>
            </td>
        </tr> 

    </table>
</body>
</html>