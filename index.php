<?php
session_start();

// Sprawdzamy, czy użytkownik jest zalogowany
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    // Ustawiamy sesję jako potwierdzenie logowania
    $_SESSION['logged_in'] = true;
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['logged_in'])) {
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Witamy w Panelu Administratora!</h1>
        <form method="post">
            <button type="submit" name="login" class="btn btn-primary">Zaloguj się</button>
        </form>
    </div>
</body>
</html>
<?php
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            background: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center text-light">Panel Administratora</h4>
        <a href="Po.php">Kurier</a>
        <a href="department.php">Dział</a>
        <a href="sender.php">Nadawca</a>
        <a href="recipient.php">Odbiorca</a>
        <a href="shipment.php">Przesyłka</a>
        <a href="?action=logout" class="text-danger">Wyloguj się</a>
    </div>
    <div class="content">
        <h1>Witamy w Panelu Administratora</h1>
        <p>Wybierz sekcję z paska bocznego, aby zarządzać danymi.</p>
    </div>
</body>
</html>