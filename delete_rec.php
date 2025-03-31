<?php
if (isset($_GET["recipient_id"])) {
    $recipient_id = $_GET["recipient_id"];

    // Walidacja ID
    if (!filter_var($recipient_id, FILTER_VALIDATE_INT)) {
        die("Nieprawidłowe ID.");
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "poczta";

    // Połączenie z bazą danych
    $connection = new mysqli($servername, $username, $password, $database);

    if ($connection->connect_error) {
        die("Błąd połączenia z bazą danych: " . $connection->connect_error);
    }

    // Przygotowanie zapytania SQL
    $stmt = $connection->prepare("DELETE FROM recipient WHERE recipient_id = ?");
    if (!$stmt) {
        die("Błąd przygotowania zapytania: " . $connection->error);
    }

    $stmt->bind_param("i", $recipient_id);

    // Wykonanie zapytania
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Rekord został usunięty.";
        } else {
            echo "Nie znaleziono rekordu do usunięcia.";
        }
    } else {
        echo "Błąd w zapytaniu SQL: " . $stmt->error;
    }

    $stmt->close();
    $connection->close();

    // Przekierowanie po udanym usunięciu
    header("Location: /Poczta/recipient.php" );
    exit;
} else {
    die("Brak ID do usunięcia.");
}
?>