<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "poczta";

// Połączenie z bazą danych
$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Zmienne formularza
$shipment_id = "";
$sender_id = "";
$recipient_id = "";
$department_id = "";
$courier_id = "";
$current_status = "";
$current_location = "";
$status_updated_at = "";
$shipment_date = "";
$expected_delivery_date = "";
$delivery_date = "";
$tracking_number = "";
$comment = "";

$errorMessage = "";
$successMessage = "";

// Jeśli metoda to GET, pobierz dane przesyłki do edycji
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET["shipment_id"])) {
        header("location: /Poczta/shipment.php");
        exit;
    }

    $shipment_id = $_GET["shipment_id"];
    $sql = "SELECT * FROM shipment WHERE shipment_id = $shipment_id";
    $result = $connection->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /Poczta/shipment.php");
        exit;
    }

    // Przypisanie danych z bazy do zmiennych
    $sender_id = $row["sender_id"];
    $recipient_id = $row["recipient_id"];
    $department_id = $row["department_id"];
    $courier_id = $row["courier_id"];
    $current_status = $row["current_status"];
    $current_location = $row["current_location"];
    $status_updated_at = $row["status_updated_at"];
    $shipment_date = $row["shipment_date"];
    $expected_delivery_date = $row["expected_delivery_date"];
    $delivery_date = $row["delivery_date"];
    $tracking_number = $row["tracking_number"];
    $comment = $row["comment"];
} else {
    // Jeśli metoda to POST, aktualizuj dane przesyłki
    $shipment_id = $_POST["shipment_id"];
    $sender_id = $_POST["sender_id"];
    $recipient_id = $_POST["recipient_id"];
    $department_id = $_POST["department_id"];
    $courier_id = $_POST["courier_id"];
    $current_status = $_POST["current_status"];
    $current_location = $_POST["current_location"];
    $status_updated_at = $_POST["status_updated_at"];
    $shipment_date = $_POST["shipment_date"];
    $expected_delivery_date = $_POST["expected_delivery_date"];
    $delivery_date = $_POST["delivery_date"];
    $tracking_number = $_POST["tracking_number"];
    $comment = $_POST["comment"];

    do {
        // Walidacja - wszystkie pola muszą być wypełnione
        if (
            empty($shipment_id) || empty($sender_id) || empty($recipient_id) || 
            empty($department_id) || empty($courier_id) || empty($current_status) || 
            empty($current_location) || empty($shipment_date) || 
            empty($expected_delivery_date) || empty($tracking_number)
        ) {
            $errorMessage = "Wszystkie pola są wymagane.";
            break;
        }

        // Aktualizacja danych w bazie
        $sql = "UPDATE shipment 
                SET sender_id = '$sender_id', recipient_id = '$recipient_id', 
                    department_id = '$department_id', courier_id = '$courier_id', 
                    current_status = '$current_status', current_location = '$current_location', 
                    status_updated_at = '$status_updated_at', shipment_date = '$shipment_date', 
                    expected_delivery_date = '$expected_delivery_date', delivery_date = '$delivery_date', 
                    tracking_number = '$tracking_number', comment = '$comment' 
                WHERE shipment_id = $shipment_id";

        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Nieprawidłowe zapytanie: " . $connection->error;
            break;
        }

        $successMessage = "Przesyłka została pomyślnie zaktualizowana.";
        header("location: /Poczta/shipment.php");
        exit;
    } while (false);
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja przesyłki</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Edycja przesyłki</h2>

        <?php if (!empty($errorMessage)) : ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?php echo $errorMessage; ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="shipment_id" value="<?php echo htmlspecialchars($shipment_id); ?>">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nadawca</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="sender_id" value="<?php echo htmlspecialchars($sender_id); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Odbiorca</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="recipient_id" value="<?php echo htmlspecialchars($recipient_id); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Dział</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="department_id" value="<?php echo htmlspecialchars($department_id); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Kurier</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="courier_id" value="<?php echo htmlspecialchars($courier_id); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Aktualny status</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="current_status" value="<?php echo htmlspecialchars($current_status); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Aktualna lokalizacja</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="current_location" value="<?php echo htmlspecialchars($current_location); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Data aktualizacji statusu</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="status_updated_at" value="<?php echo htmlspecialchars($status_updated_at); ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Data wysyłki</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="shipment_date" value="<?php echo htmlspecialchars($shipment_date); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Przewidywana data dostawy</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="expected_delivery_date" value="<?php echo htmlspecialchars($expected_delivery_date); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Data dostawy</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="delivery_date" value="<?php echo htmlspecialchars($delivery_date); ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Numer śledzenia</label>
                <div class="col-sm-6">
                    <input type="text" id="tracking_number" class="form-control" name="tracking_number" value="<?php echo htmlspecialchars($tracking_number); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Komentarz</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="comment"><?php echo htmlspecialchars($comment); ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Zatwierdź</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/Poczta/shipment.php" role="button">Anuluj</a>
                </div>
            </div>
        </form>

        <?php if (!empty($successMessage)) : ?>
            <div class="alert alert-success mt-3" role="alert">
                <strong><?php echo $successMessage; ?></strong>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById("tracking_number").addEventListener("input", function(e) {
            let input = e.target.value;

            // Dodaj prefiks 'TRACK', jeśli go nie ma
            if (!input.toUpperCase().startsWith("TRACK")) {
                input = "TRACK" + input.replace(/track/gi, ""); // Usuń istniejące 'track'
            }

            // Ustaw wartość pola na dużą literę
            e.target.value = input.toUpperCase();
        });
    </script>
</body>
</html>

