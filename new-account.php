<?php
require __DIR__ . '/bootstrap.php';

function generateAccountNumber() {
    $IBAN = 'LT';
    foreach (range(1, 2) as $value) {
        $IBAN .= rand(0, 9);
    }
        $IBAN .= '70770';
    foreach (range(1, 11) as $value) {
        $IBAN .= rand(0, 9);
    }
    return $IBAN;
}


if (!isset($_SESSION['login']) || $_SESSION['login'] != 1) {
    header('Location: '.$URL.'login.php');
    die();
}


if (isset($_POST['submit'])) {

    if(strlen($_POST['name']) < 3) {
        $_SESSION['note'] = '<span style="color:red;font-weight:bold;">Įveskite vardą</span>';
        $_SESSION['surname'] = $_POST['surname'];
        $_SESSION['id'] = $_POST['id'];
        header('Location: '.$URL.'new-account.php');
        die();
    }

    elseif(strlen($_POST['surname']) < 3) {
        $_SESSION['note'] = '<span style="color:red;font-weight:bold;">Įveskite vardą</span>'; 
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['id'] = $_POST['id'];       
        header('Location: '.$URL.'new-account.php');
        die();
    }

    // elseif(strlen($_POST['id']) < 11) {
    //     $_SESSION['note'] = '<span style="color:red;font-weight:bold;">Įveskite vardą</span>'; 
    //     $_SESSION['name'] = $_POST['name'];
    //     $_SESSION['surname'] = $_POST['surname'];       
    //     header('Location: '.$URL.'new-account.php');
    //     die();
    // }


    $data[] = ['name' => $_POST['name'], 'surname' => $_POST['surname'], 'id' => $_POST['id'], 'account' => $_POST['account'], 'balance' => $_POST['balance']]; 
    file_put_contents(__DIR__ .'/accounts.json', json_encode($data));
    $_SESSION['note'] = 'Pridėta nauja kliento sąskaita';
    header("Location: $URL"."new-account.php");
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
<body>
    <header>
        <nav>
            <a href=<?=$URL.'accounts-list.php'?>>Sąskaitų sąrašas</a>
            <a href=<?=$URL.'login.php?logout'?>>Atsijungti</a>
        </nav>       
    </header>
    <form action="" method="post">
        <input type="text" name="name" value=<?= $_SESSION['name'] ?? '' ?>> Vardas<br>
<?php
unset($_SESSION['name']);
?>
        <input type="text" name="surname" value=<?= $_SESSION['surname'] ?? '' ?>> Pavardė<br>
<?php
unset($_SESSION['surname']);
?>
        <input type="text" name="account" value="<?= generateAccountNumber()?>" readonly> Sąskaitos numeris<br>
        <input type="text" name="id" value=<?= $_SESSION['id'] ?? '' ?>> Asmens kodas<br>
<?php
unset($_SESSION['id']);
?>        
        <input type="hidden" name="balance" value="0">
        <button type="submit" name="submit">Pridėti naują sąskaitą</button>
    </form>
</body>
</html>