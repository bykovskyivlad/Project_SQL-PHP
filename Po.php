<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta first_name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poczta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="courier">
        <h2>List of couriers</h2>
        <div class="mb-3">
            <a href="/poczta/index.php" class="btn btn-info">Powrót</a>
        </div>
        <form id="filter-form" class="d-flex flex-column gap-3 mb-3">
            <div class="col-md-3">
                <input type="text" name="first_name" class="form-control" placeholder="Imię">
            </div>
            <div class="col-md-3">
                <input type="text" name="last_name" class="form-control" placeholder="Nazwisko">
            </div>
            <div class="col-md-3">
                <input 
                    type="text" 
                    class="form-control" 
                    name="contact_number" 
                    id="contact_number" 
                    placeholder="Telefon">
            </div>
            <div class="col-md-3">
                <input type="text" name="department_id" class="form-control" placeholder="Dział">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Filtruj</button>
            </div>
        </form>
        <div class="d-flex gap-3 mb-3">
            <a class="btn btn-primary" href="/Poczta/create.php" role="button">New Courier</a>
            <button id="reset-button" class="btn btn-danger">Reset</button>
        </div>
        <br>
        <table class="table"> 
            <thead>
                <tr>
                    <th><a href="?sort=courier_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Id</a></th>
                    <th><a href="?sort=first_name&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Imie</a></th>
                    <th><a href="?sort=last_name&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Nazwisko</a></th>
                    <th><a href="?sort=contact_number&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Telefon</a></th>
                    <th><a href="?sort=department_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Dział</a></th>
                    <th>Działanie</th>
                </tr>
            </thead>
            <tbody id="courier-results">
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

            // Obsługa parametrów GET
            $first_name = $_GET['first_name'] ?? '';
            $last_name = $_GET['last_name'] ?? '';
            $contact_number = $_GET['contact_number'] ?? '';
            $department_id = $_GET['department_id'] ?? '';
            $sort = $_GET['sort'] ?? 'courier_id';
            $order = $_GET['order'] ?? 'ASC';

            // Walidacja parametrów sortowania
            $valid_columns = ['courier_id', 'first_name', 'last_name', 'contact_number', 'department_id'];
            if (!in_array($sort, $valid_columns)) {
                $sort = 'courier_id';
            }
            if (!in_array($order, ['ASC', 'DESC'])) {
                $order = 'ASC';
            }

            // Tworzenie zapytania SQL
            $sql = "SELECT * FROM courier WHERE 1=1";

            if (!empty($first_name)) {
                $sql .= " AND first_name LIKE '%" . $connection->real_escape_string($first_name) . "%'";
            }
            if (!empty($last_name)) {
                $sql .= " AND last_name LIKE '%" . $connection->real_escape_string($last_name) . "%'";
            }
            if (!empty($contact_number)) {
                $sql .= " AND contact_number LIKE '%" . $connection->real_escape_string($contact_number) . "%'";
            }
            if (!empty($department_id)) {
                $sql .= " AND department_id LIKE '%" . $connection->real_escape_string($department_id) . "%'";
            }

            $sql .= " ORDER BY $sort $order";

            $result = $connection->query($sql);
            if (!$result) {
                die("Invalid query: " . $connection->error);
            }

            // Generowanie wierszy tabeli
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['courier_id']}</td>
                    <td>{$row['first_name']}</td>
                    <td>{$row['last_name']}</td>
                    <td>{$row['contact_number']}</td>
                    <td>{$row['department_id']}</td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='/Poczta/edit.php?courier_id={$row['courier_id']}'>Edit</a>
                        <a class='btn btn-danger btn-sm' href='/Poczta/delete.php?courier_id={$row['courier_id']}' onclick='return confirm(\"Czy na pewno chcesz usunąć tego kuriera?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('reset-button').addEventListener('click', function () {
            // Reset all filters and sorting
            window.location.href = window.location.pathname;
        });

        document.getElementById('filter-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Zatrzymaj domyślne zachowanie formularza

            // Pobierz dane z formularza
            const formData = new FormData(e.target);

            // Wyślij zapytanie AJAX
            fetch('?' + new URLSearchParams(formData))
                .then(response => response.text())
                .then(html => {
                    // Wstaw dynamicznie wygenerowaną tabelę do elementu #courier-results
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.querySelector('#courier-results').innerHTML;
                    document.getElementById('courier-results').innerHTML = newResults;
                });
        });
    </script>
</body>
</html>
