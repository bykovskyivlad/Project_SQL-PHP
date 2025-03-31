<?php
if (isset($_GET["department_id"])) {
    $department_id = $_GET["department_id"];

    // Walidacja ID
    if (!filter_var($department_id, FILTER_VALIDATE_INT)) {
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
    $stmt = $connection->prepare("DELETE FROM postal_department WHERE department_id = ?");
    if (!$stmt) {
        die("Błąd przygotowania zapytania: " . $connection->error);
    }

    $stmt->bind_param("i", $department_id);

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
    header("Location: /Poczta/department.php");
    exit;
} else {
    die("Brak ID do usunięcia.");
}
?>