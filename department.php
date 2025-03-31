<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poczta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="department">
        <h2>List of Post Departments</h2>
        <div class="mb-3">
            <a href="/poczta/index.php" class="btn btn-info">Powrót</a>
        </div>
        <form id="filter-form" class="d-flex flex-column gap-3 mb-3">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Nazwa Wydziału">
            </div>
            <div class="col-md-3">
                <input type="text" name="location" class="form-control" placeholder="Lokacja">
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
                <button type="submit" class="btn btn-success">Filtruj</button>
            </div>
        </form>
        <div class="d-flex gap-3 mb-3">
            <a class="btn btn-primary" href="/Poczta/create_dep.php" role="button">New Post Department</a>
            <button id="reset-button" class="btn btn-danger">Reset</button>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th><a href="?sort=department_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Id</a></th>
                    <th><a href="?sort=name&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Nazwa Wydziału</a></th>
                    <th><a href="?sort=location&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Lokacja</a></th>
                    <th><a href="?sort=contact_number&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Telefon</a></th>
                    <th>Działanie</th>
                </tr>
            </thead>
            <tbody id="department-results">
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

                // Obsługa sortowania
                $name = $_GET['name'] ?? '';
                $location = $_GET['location'] ?? '';
                $contact_number = $_GET['contact_number'] ?? '';
                $sort = $_GET['sort'] ?? 'department_id';
                $order = $_GET['order'] ?? 'ASC';

                // Walidacja parametrów
                $valid_columns = ['department_id', 'name', 'location', 'contact_number'];
                if (!in_array($sort, $valid_columns)) {
                    $sort = 'department_id';
                }
                if (!in_array($order, ['ASC', 'DESC'])) {
                    $order = 'ASC';
                }

                $sql = "SELECT * FROM postal_department WHERE 1=1";

                if (!empty($name)) {
                    $sql .= " AND name LIKE '%" . $connection->real_escape_string($name) . "%'";
                }
                if (!empty($location)) {
                    $sql .= " AND location LIKE '%" . $connection->real_escape_string($location) . "%'";
                }
                if (!empty($contact_number)) {
                    $sql .= " AND contact_number LIKE '%" . $connection->real_escape_string($contact_number) . "%'";
                }

                // Dodanie sortowania
                $sql .= " ORDER BY $sort $order";

                $result = $connection->query($sql);
                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                // Generowanie wierszy tabeli
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>$row[department_id]</td>
                        <td>$row[name]</td>
                        <td>$row[location]</td>
                        <td>$row[contact_number]</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/Poczta/edit_dep.php?department_id=$row[department_id]'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/Poczta/delete_dep.php?department_id=$row[department_id]'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        document.getElementById('filter-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Zatrzymaj domyślne zachowanie formularza

            // Pobierz dane z formularza
            const formData = new FormData(e.target);

            // Wyślij zapytanie AJAX
            fetch('?' + new URLSearchParams(formData))
                .then(response => response.text())
                .then(html => {
                    // Wstaw dynamicznie wygenerowaną tabelę do elementu #department-results
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.querySelector('#department-results').innerHTML;
                    document.getElementById('department-results').innerHTML = newResults;
                });
        });

        document.getElementById('reset-button').addEventListener('click', function () {
            // Reset all filters and sorting
            window.location.href = window.location.pathname;
        });

        document.getElementById('contact_number').addEventListener('input', function (e) {
            const input = e.target.value.replace(/\D/g, ''); // Usuwa wszystko, co nie jest cyfrą
            const formatted = input
                .match(/(\d{0,3})(\d{0,3})(\d{0,3})/)
                .slice(1, 4) // Pomija całe dopasowanie
                .filter(Boolean) // Usuń puste grupy
                .join('-'); // Łączy grupy kreskami
            e.target.value = formatted;
        });
    </script>
</body>
</html>
