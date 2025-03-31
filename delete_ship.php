<?php
if (isset($_GET["shipment_id"])) {
    $shipment_id = $_GET["shipment_id"];

    // Проверяем, что ID является числом
    if (!filter_var($shipment_id, FILTER_VALIDATE_INT)) {
        die("Invalid ID.");
    }

    // Подключение к базе данных
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "poczta";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Удаление записи
    $sql = "DELETE FROM shipment WHERE shipment_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $shipment_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Shipment deleted successfully.";
        } else {
            echo "No shipment found with this ID.";
        }
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Перенаправление обратно на страницу списка
    header("Location: shipment.php");
    exit;
} else {
    die("No shipment ID provided.");
}
?>