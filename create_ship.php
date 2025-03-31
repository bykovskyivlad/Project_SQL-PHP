<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "poczta";

// Połączenie z bazą danych
$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Nie udało się połączyć z bazą danych: " . $connection->connect_error);
}

// Zmienne formularza
$sender_id = "";
$recipient_id = "";
$department_id = "";
$courier_id = "";
$current_status = "";
$current_location = "";
$status_updated_at = "";
$shipment_date = "";
$expected_delivery_date = "";
$tracking_number = "";
$comment = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_POST["sender_id"];
    $recipient_id = $_POST["recipient_id"];
    $department_id = $_POST["department_id"];
    $courier_id = $_POST["courier_id"];
    $current_status = $_POST["current_status"];
    $current_location = $_POST["current_location"];
    $status_updated_at = $_POST["status_updated_at"];
    $shipment_date = $_POST["shipment_date"];
    $expected_delivery_date = $_POST["expected_delivery_date"];
    $tracking_number = $_POST["tracking_number"];
    $comment = $_POST["comment"];

    do {
        // Проверка на заполнение поля Current Location
        if (empty($current_location)) {
            $errorMessage = "Pole 'Aktualna Lokalizacja' jest wymagane.";
            break;
        }

        // Проверка на существование sender_id в таблице sender
        $checkSenderSql = "SELECT COUNT(*) as count FROM sender WHERE sender_id = '$sender_id'";
        $checkSenderResult = $connection->query($checkSenderSql);
        $senderExists = $checkSenderResult->fetch_assoc()['count'] > 0;

        if (!$senderExists) {
            $errorMessage = "Nadawca o ID $sender_id nie istnieje.";
            break;
        }

        // Проверка на существование recipient_id в таблице recipient
        $checkRecipientSql = "SELECT COUNT(*) as count FROM recipient WHERE recipient_id = '$recipient_id'";
        $checkRecipientResult = $connection->query($checkRecipientSql);
        $recipientExists = $checkRecipientResult->fetch_assoc()['count'] > 0;

        if (!$recipientExists) {
            $errorMessage = "Odbiorca o ID $recipient_id nie istnieje.";
            break;
        }

        // Проверка на существование department_id в таблице postal_department
        $checkDepartmentSql = "SELECT COUNT(*) as count FROM postal_department WHERE department_id = '$department_id'";
        $checkDepartmentResult = $connection->query($checkDepartmentSql);
        $departmentExists = $checkDepartmentResult->fetch_assoc()['count'] > 0;

        if (!$departmentExists) {
            $errorMessage = "Oddział pocztowy o ID $department_id nie istnieje.";
            break;
        }

        // Проверка на существование courier_id в таблице courier
        $checkCourierSql = "SELECT COUNT(*) as count FROM courier WHERE courier_id = '$courier_id'";
        $checkCourierResult = $connection->query($checkCourierSql);
        $courierExists = $checkCourierResult->fetch_assoc()['count'] > 0;

        if (!$courierExists) {
            $errorMessage = "Kurier o ID $courier_id nie istnieje.";
            break;
        }

        // Wstawianie danych do tabeli shipment
        $sql = "INSERT INTO shipment (sender_id, recipient_id, department_id, courier_id, current_status, current_location, status_updated_at, shipment_date, expected_delivery_date, tracking_number, comment) " .
               "VALUES ('$sender_id', '$recipient_id', '$department_id', '$courier_id', '$current_status', '$current_location', '$status_updated_at', '$shipment_date', '$expected_delivery_date', '$tracking_number', '$comment')";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Błąd zapytania: " . $connection->error;
            break;
        }

        // Resetowanie formularza
        $sender_id = "";
        $recipient_id = "";
        $department_id = "";
        $courier_id = "";
        $current_status = "";
        $current_location = "";
        $status_updated_at = "";
        $shipment_date = "";
        $expected_delivery_date = "";
        $tracking_number = "";
        $comment = "";

        $successMessage = "Przesyłka została pomyślnie dodana.";

        header("Location: http://localhost/Poczta/shipment.php");
        exit;

    } while (false);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowa Przesyłka</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function enforceTrackingPrefix(input) {
            if (!input.value.startsWith('TRACK')) {
                input.value = 'TRACK' + input.value.replace(/[^0-9]/g, '');
            }
        }
    </script>
</head>
<body>
    <div class="container my-5">
        <h2>Nowa Przesyłka</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'> 
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Zamknij'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nadawca</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="sender_id" value="<?php echo $sender_id ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Odbiorca</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="recipient_id" value="<?php echo $recipient_id ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Oddział Pocztowy</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="department_id" value="<?php echo $department_id ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Kurier</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="courier_id" value="<?php echo $courier_id ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Aktualny Status</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="current_status" value="<?php echo $current_status ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Aktualna Lokalizacja</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="current_location" value="<?php echo $current_location ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Status Zaktualizowano</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="status_updated_at" value="<?php echo $status_updated_at ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Data Wysyłki</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="shipment_date" value="<?php echo $shipment_date ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Oczekiwana Data Dostawy</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="expected_delivery_date" value="<?php echo $expected_delivery_date ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Numer Śledzenia</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="tracking_number" value="<?php echo $tracking_number ?>" oninput="enforceTrackingPrefix(this)" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Komentarz</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="comment"><?php echo $comment ?></textarea>
                </div>
            </div>

            <?php
             if (!empty($successMessage)) {
                echo "
                   <div class='row mb-3'>
                        <div class='offset-sm-3 col-sm-6'>
                            <div class='alert alert-success alert-dismissible fade show' role='alert'> 
                              <strong>$successMessage</strong>
                             <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Zamknij'></button>
                            </div>    
                        </div>
                   </div>
                ";
             }
            ?>

            <div class="row-mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Zatwierdź</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/Poczta/shipment.php" role="button">Anuluj</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
