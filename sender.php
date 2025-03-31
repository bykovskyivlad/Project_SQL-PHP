<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poczta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="sender">
        <h2>List of senders</h2>
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
                <input type="text" name="street" class="form-control" placeholder="Ulica">
            </div>
            <div class="col-md-3">
                <input type="text" name="city" class="form-control" placeholder="Miasto">
            </div>
            <div class="col-md-3">
                <input type="text" name="postal_code" class="form-control" id="postal_code" placeholder="Kod Pocztowy">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Filtruj</button>
            </div>
        </form>
        <div class="d-flex gap-3 mb-3">
            <a class="btn btn-primary" href="/Poczta/create_sen.php" role="button">New sender</a>
            <button id="reset-button" class="btn btn-danger">Reset</button>
        </div>
        <br>
        <table class="table"> 
            <thead>
                <tr>
                    <th><a href="?sort=sender_id&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Id</a></th>
                    <th><a href="?sort=first_name&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Imie</a></th>
                    <th><a href="?sort=last_name&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Nazwisko</a></th>
                    <th><a href="?sort=contact_number&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Telefon</a></th>
                    <th><a href="?sort=street&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Ulica</a></th>
                    <th><a href="?sort=city&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Miasto</a></th>
                    <th><a href="?sort=postal_code&order=<?php echo isset($_GET['order']) && $_GET['order'] === 'ASC' ? 'DESC' : 'ASC'; ?>">Kod Pocztowy</a></th>
                    <th>Działanie</th>
                </tr>
            </thead>
            <tbody id="sender-result">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "poczta";

                $connection = new mysqli($servername, $username, $password, $database);
                if ($connection->connect_error) {
                    die("Connection failed: " . $connection->connect_error);
                }
                $first_name = $_GET['first_name'] ?? '';
                $last_name = $_GET['last_name'] ?? '';
                $contact_number = $_GET['contact_number'] ?? '';
                $street = $_GET['street'] ?? '';
                $city = $_GET['city'] ?? '';
                $postal_code = $_GET['postal_code'] ?? '';

                $sort = $_GET['sort'] ?? 'sender_id'; // Domyślne sortowanie po sender_id
                $order = $_GET['order'] ?? 'ASC'; // Domyślnie porządek rosnący

                // Walidacja parametrów
                $valid_columns = ['sender_id', 'first_name', 'last_name', 'contact_number', 'street', 'city', 'postal_code'];
                if (!in_array($sort, $valid_columns)) {
                    $sort = 'sender_id';
                }
                if (!in_array($order, ['ASC', 'DESC'])) {
                    $order = 'ASC';
                }

                // Tworzenie zapytania SQL
                $sql = "SELECT * FROM sender WHERE 1=1";

                if (!empty($first_name)) {
                    $sql .= " AND first_name LIKE '%" . $connection->real_escape_string($first_name) . "%'";
                }
                if (!empty($last_name)) {
                    $sql .= " AND last_name LIKE '%" . $connection->real_escape_string($last_name) . "%'";
                }
                if (!empty($contact_number)) {
                    $sql .= " AND contact_number LIKE '%" . $connection->real_escape_string($contact_number) . "%'";
                }
                if (!empty($street)) {
                    $sql .= " AND street LIKE '%" . $connection->real_escape_string($street) . "%'";
                }
                if (!empty($city)) {
                    $sql .= " AND city LIKE '%" . $connection->real_escape_string($city) . "%'";
                }
                if (!empty($postal_code)) {
                    $sql .= " AND postal_code LIKE '%" . $connection->real_escape_string($postal_code) . "%'";
                }

                $sql .= " ORDER BY $sort $order";

                $result = $connection->query($sql);
                if (!$result) {
                    die("Invalid query: " . $connection->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>$row[sender_id]</td>
                        <td>$row[first_name]</td>
                        <td>$row[last_name]</td>
                        <td>$row[contact_number]</td>
                        <td>$row[street]</td>
                        <td>$row[city]</td>
                        <td>$row[postal_code]</td>
                        <td>
                            <a class='btn btn-primary btn-sm' href='/Poczta/edit_sen.php?sender_id=$row[sender_id]'>Edit</a>
                            <a class='btn btn-danger btn-sm' href='/Poczta/delete_sen.php?sender_id=$row[sender_id]' onclick='return confirm(\"Czy na pewno chcesz usunąć tego klijenta?\")'>Delete</a>
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
                    // Wstaw dynamicznie wygenerowaną tabelę do elementu 
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newResults = doc.querySelector('#sender-result').innerHTML;
                    document.getElementById('sender-result').innerHTML = newResults;
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

        document.getElementById('postal_code').addEventListener('input', function (e) {
            const input = e.target.value.replace(/\D/g, ''); // Usuwa wszystko, co nie jest cyfrą
            const formatted = input
                .match(/(\d{0,2})(\d{0,3})/)
                .slice(1, 4) // Pomija całe dopasowanie
                .filter(Boolean) // Usuń puste grupy
                .join('-'); // Łączy grupy kreskami
            e.target.value = formatted;
        });
    </script>
</body>
</html>
