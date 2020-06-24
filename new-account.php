<?php
require __DIR__ . '/bootstrap.php';

function generateAccountNumber($data) {
    $IBAN = 'LT';
    foreach (range(1, 2) as $value) {
        $IBAN .= rand(0, 9);
    }
        $IBAN .= '70770';
    foreach (range(1, 11) as $value) {
        $IBAN .= rand(0, 9);
    }
    foreach ($data as $key => $account) {
        if ($data[$key]['account'] == $IBAN) {
            generateAccountNumber($data);
        } 
        else {
            return $IBAN;
        }
    }    
}

function verifyID($number) {
    $id_string = (string)$number;
    if (strlen($id_string) < 11) {
        _d('maziau uz 11');
        return false;
    }
    if ($id_string[0] < 1 || $id_string[0] > 6) {
        _d('neteisingas pirmas skaicius');
        return false;
    }
    if (substr($id_string, 3, 2) > 12) {
        _d('neteisingas menuo');
        return false;
    }
    if (substr($id_string, 5, 2) > 31) {
        _d('neteisinga diena');
        return false;
    }
    if ($id_string[0] == 5 || $id_string[0] == 6) {
        if (substr($id_string, 1, 2) > date('y')) {
            _d('dar negimes - metai');
            return false;
        }
        if (substr($id_string, 1, 2) == date('y') && substr($id_string, 3, 2) > date('m')) {
            _d('dar negimes - menuo');
            return false;
        }
        if (substr($id_string, 1, 2) == date('y') && substr($id_string, 3, 2) == date('m')
                && substr($id_string, 5, 2) > date('d')) {
            _d('dar negimes - diena');
            return false;
        }
    }
    // S = A*1 + B*2 + C*3 + D*4 + E*5 + F*6 + G*7 + H*8 + I*9 + J*1
    $sum = 0;    
    for ($i=0; $i < 10; $i++) { 
        if ($i == 9) {
            $sum += (int) $id_string[$i]*1;
        } 
        else {
            $sum += (int) $id_string[$i]*($i+1);
        }        
    }
    $remainder = $sum % 11;
    _d("remainder1: $remainder");
    if($remainder != 10 && $id_string[10] != $remainder) {
        _d('paskutinis skaicius - 1 patikrinimas');
        return false;
    }
    // S = A*3 + B*4 + C*5 + D*6 + E*7 + F*8 + G*9 + H*1 + I*2 + J*3
    if ($remainder == 10) {
        $sum = 0;    
        for ($i=0; $i < 10; $i++) { 
            if ($i >= 7) {
                $sum += (int) $id_string[$i]*($i-6);
            }
            else {
                $sum += (int) $id_string[$i]*($i+3);
            }            
        }
    }
    $remainder = $sum % 11;
    _d("remainder2: $remainder");
    if ($remainder != 10 && $id_string[10] != $remainder) {
        _d('paskutinis skaicius - 2 patikrinimas');
        return false;
    }
    if ($remainder == 10 && $id_string[10] != 0) {
        _d('paskutinis skaicius - 3 patikrinimas');
        return false;
    }
    else {
        return true;
    }
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

        
    elseif(verifyID($_POST['id']) === false) {
        $_SESSION['note'] = '<span style="color:red;font-weight:bold;">
                            Neteisingai įvestas asmens kodas</span>'; 
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['surname'] = $_POST['surname'];       
        header('Location: '.$URL.'new-account.php');
        die();
    }


    $data[] = ['name' => $_POST['name'], 'surname' => $_POST['surname'], 'id' => $_POST['id'], 'account' => $_POST['account'], 'balance' => $_POST['balance']]; 
    file_put_contents(__DIR__ .'/accounts.json', json_encode($data));
    $_SESSION['note'] = 'Pridėta nauja kliento sąskaita';
    header("Location: $URL"."new-account.php");
    die();
}




// if (isset($_POST['clear'])) {
    // header("Location: $URL"."new-account.php");
    // die();
// }

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
    <h2>Nauja sąskaita</h2>

<?php
if(isset($_SESSION['note'])) {
    echo $_SESSION['note'];
    unset($_SESSION['note']);    
}

?>
    <br>
    <form action="" method="post">
        <input type="text" name="name" value=<?= $_SESSION['name'] ?? '' ?>> Vardas<br>
<?php
unset($_SESSION['name']);
?>
        <input type="text" name="surname" value=<?= $_SESSION['surname'] ?? '' ?>> Pavardė<br>
<?php
unset($_SESSION['surname']);
?>
        <input type="text" name="account" value="<?= generateAccountNumber($data)?>" readonly> Sąskaitos numeris<br>
        <input type="number"  maxlength="11" name="id" value=<?= $_SESSION['id'] ?? ''?>> Asmens kodas<br><br>
<?php
unset($_SESSION['id']);
?>        
        <input type="hidden" name="balance" value="0">
        <button type="submit" name="submit">Pridėti naują sąskaitą</button>
        <button type="submit" name="clear">Išvalyti</button><br>
    </form>
</body>
</html>