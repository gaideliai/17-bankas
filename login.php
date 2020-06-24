<?php
require __DIR__ . '/bootstrap.php';

if (!empty($_POST)) {

    foreach ($loginData as $user) {
        
        if ($user['name'] === $_POST['user'] &&
            $user['pass'] === md5($_POST['password'])) {
            $_SESSION['login'] = 1;
            header('Location: '.$URL.'accounts-list.php');
            die();
        }
        else {
            $_SESSION['note'] = '<span style="color:red;font-weight:bold;">
                                Neteisingas prisijungimo vardas arba slaptažodis</span>';            
            header('Location: '.$URL.'login.php');
            die();
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: '.$URL.'login.php');
    die();
}



?>
<h2>Prisijungimas</h2>

<?php
if(isset($_SESSION['note'])) {
    echo $_SESSION['note'];
    unset($_SESSION['note']);
}

?>

<br>
<form action=<?=$URL.'login.php'?> method="post">
    <label for="">Prisijungimo vardas</label><br>
    <input type="text" name="user"><br><br>
    <label for="">Slaptažodis</label><br>
    <input type="password" name="password"><br><br>
    <button type="submit">Prisijungti</button>
</form>