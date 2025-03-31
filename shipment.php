<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="shipment">
        <h2>List of Shipments</h2>
        <div class="mb-3">
            <a href="/poczta/index.php" class="btn btn-info">Powrót</a>
        </div>
        <form id="filter-form" class="row g-3 mb-3">
            <div class="col-md-4">
                <label for="sender_id" class="form-label">Sender</label>
                <input type="text" name="sender_id" id="sender_id" class="form-control" placeholder="Enter sender">
            </div>
            <div class="col-md-4">
                <label for="recipient_id" class="form-label">Recipient</label>
                <input type="text" name="recipient_id" id="recipient_id" class="form-control" placeholder="Enter recipient">
            </div>
            <div class="col-md-4">
                <label for="department_id" class="form-label">Department</label>
                <input type="text" name="department_id" id="department_id" class="form-control" placeholder="Enter department">
            </div>
            <div class="col-md-4">
                <label for="courier_id" class="form-label">Courier</label>
                <input type="text" name="courier_id" id="courier_id" class="form-control" placeholder="Enter courier">
            </div>
            <div class="col-md-4">
                <label for="current_status" class="form-label">Current Status</label>
                <input type="text" name="current_status" id="current_status" class="form-control" placeholder="Enter current status">
            </div>
            <div class="col-md-4">
                <label for="current_location" class="form-label">Current Location</label>
                <input type="text" name="current_location" id="current_location" class="form-control" placeholder="Enter current location">
            </div>
            <div class="col-md-4">
                <label for="status_updated_at" class="form-label">Status Updated At</label>
                <input type="date" name="status_updated_at" id="status_updated_at" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="shipment_date" class="form-label">Shipment Date</label>
                <input type="date" name="shipment_date" id="shipment_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
                <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="delivery_date" class="form-label">Delivery Date</label>
                <input type="date" name="delivery_date" id="delivery_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" name="tracking_number" id="tracking_number" class="form-control" placeholder="Enter tracking number">
            </div>
            <div class="col-md-4">
                <label for="comment" class="form-label">Comment</label>
                <input type="text" name="comment" id="comment" class="form-control" placeholder="Enter comment">
            </div>
            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-success">Filtruj</button>
            </div>
        </form>
        <div class="d-flex gap-3 mb-3">
            <a class="btn btn-primary" href="/Poczta/create_ship.php" role="button">New Shipment</a>
            <button id="reset-button" class="btn btn-danger">Reset</button>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th><a href="?sort=shipment_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Id</a></th>
                    <th><a href="?sort=sender_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Sender</a></th>
                    <th><a href="?sort=recipient_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Recipient</a></th>
                    <th><a href="?sort=department_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Department</a></th>
                    <th><a href="?sort=courier_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Courier</a></th>
                    <th><a href="?sort=current_status&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Current status</a></th>
                    <th><a href="?sort=current_location&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Current location</a></th>
                    <th><a href="?sort=status_updated_at&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Status updated at</a></th>
                    <th><a href="?sort=shipment_date&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Shipment date</a></th>
                    <th><a href="?sort=expected_delivery_date&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Expected delivery date</a></th>
                    <th><a href="?sort=delivery_date&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Delivery date</a></th>
                    <th><a href="?sort=tracking_number&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Tracking number</a></th>
                    <th><a href="?sort=comment&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Comment</a></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="shipment-result">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "poczta";

                $connection = new mysqli($servername, $username, $password, $database);

                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }

                $sender_id = $_GET['sender_id'] ?? '';
                $recipient_id = $_GET['recipient_id'] ?? '';
                $department_id = $_GET['department_id'] ?? '';
                $courier_id = $_GET['courier_id'] ?? '';
                $current_status = $_GET['current_status'] ?? '';
                $current_location = $_GET['current_location'] ?? '';
                $status_updated_at = $_GET['status_updated_at'] ?? '';
                $shipment_date = $_GET['shipment_date'] ?? '';
                $expected_delivery_date = $_GET['expected_delivery_date'] ?? '';
                $delivery_date = $_GET['delivery_date'] ?? '';
                $tracking_number = $_GET['tracking_number'] ?? '';
                $comment = $_GET['comment'] ?? '';

                $sort = $_GET['sort'] ?? 'shipment_id';
                $order = $_GET['order'] ?? 'ASC';

                $valid_columns = ['shipment_id', 'sender_id', 'recipient_id', 'department_id', 'courier_id', 'current_status', 'current_location', 'status_updated_at', 'shipment_date', 'expected_delivery_date', 'delivery_date', 'tracking_number', 'comment'];
                if (!in_array($sort, $valid_columns)) {
                    $sort = 'shipment_id';
                }
                if (!in_array($order, ['ASC', 'DESC'])) {
                    $order = 'ASC';
                }

                $sql = "SELECT * FROM shipment WHERE 1=1";

                if (!empty($sender_id)) {
                    $sql .= " AND sender_id LIKE '%" . $connection->real_escape_string($sender_id) . "%'";
                }
                if (!empty($recipient_id)) {
                    $sql .= " AND recipient_id LIKE '%" . $connection->real_escape_string($recipient_id) . "%'";
                }
                if (!empty($department_id)) {
                    $sql .= " AND department_id LIKE '%" . $connection->real_escape_string($department_id) . "%'";
                }
                if (!empty($courier_id)) {
                    $sql .= " AND courier_id LIKE '%" . $connection->real_escape_string($courier_id) . "%'";
                }
                if (!empty($current_status)) {
                    $sql .= " AND current_status LIKE '%" . $connection->real_escape_string($current_status) . "%'";
                }
                if (!empty($current_location)) {
                    $sql .= " AND current_location LIKE '%" . $connection->real_escape_string($current_location) . "%'";
                }
                if (!empty($status_updated_at)) {
                    $sql .= " AND status_updated_at LIKE '%" . $connection->real_escape_string($status_updated_at) . "%'";
                }
                if (!empty($shipment_date)) {
                    $sql .= " AND shipment_date LIKE '%" . $connection->real_escape_string($shipment_date) . "%'";
                }
                if (!empty($expected_delivery_date)) {
                    $sql .= " AND expected_delivery_date LIKE '%" . $connection->real_escape_string($expected_delivery_date) . "%'";
                }
                if (!empty($delivery_date)) {
                    $sql .= " AND delivery_date LIKE '%" . $connection->real_escape_string($delivery_date) . "%'";
                }
                if (!empty($tracking_number)) {
                    // Убедимся, что введены только числа
                    $tracking_number = preg_replace('/[^0-9]/', '', $tracking_number);
                    $sql .= " AND tracking_number LIKE '%" . $connection->real_escape_string($tracking_number) . "%'";
                }
                if (!empty($comment)) {
                    $sql .= " AND comment LIKE '%" . $connection->real_escape_string($comment) . "%'";
                }

                $sql .= " ORDER BY $sort $order";

                $result = $connection->query($sql);
                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['shipment_id']}</td>";
                    echo "<td>{$row['sender_id']}</td>";
                    echo "<td>{$row['recipient_id']}</td>";
                    echo "<td>{$row['department_id']}</td>";
                    echo "<td>{$row['courier_id']}</td>";
                    echo "<td>{$row['current_status']}</td>";
                    echo "<td>{$row['current_location']}</td>";
                    echo "<td>{$row['status_updated_at']}</td>";
                    echo "<td>{$row['shipment_date']}</td>";
                    echo "<td>{$row['expected_delivery_date']}</td>";
                    echo "<td>{$row['delivery_date']}</td>";
                    echo "<td>{$row['tracking_number']}</td>";
                    echo "<td>{$row['comment']}</td>";
                    echo "<td>";
                    echo "<a class='btn btn-primary btn-sm' href='/Poczta/edit_ship.php?shipment_id={$row['shipment_id']}'>Edit</a> ";
                    echo "<a class='btn btn-danger btn-sm' href='/Poczta/delete_ship.php?shipment_id={$row['shipment_id']}' onclick='return confirm(\"Czy na pewno chcesz usunąć tą przesyłkę?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('filter-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('?' + new URLSearchParams(formData))
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.querySelector('#shipment-result').innerHTML;
                    document.getElementById('shipment-result').innerHTML = newResults;
                });
        });

        document.getElementById('reset-button').addEventListener('click', function () {
            // Reset all filters and sorting
            window.location.href = window.location.pathname;
        });
    </script>
</body>
</html>
