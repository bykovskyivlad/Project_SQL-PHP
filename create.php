<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "poczta";

$connection = new mysqli($servername, $username, $password, $database);

$first_name = "";
$last_name = "";
$contact_number = "";
$department_id = "";


$errorMessege = "";
$successMessege = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $contact_number = $_POST["contact_number"];
    $department_id = $_POST["department_id"];

    do {

        if ( empty($first_name) || empty($last_name) || empty($contact_number) || empty($department_id) ) {
            $errorMessege = "All the field are required";
            break;
        }
        
        if (!preg_match('/^\d{3}-\d{3}-\d{3}$/', $contact_number)) {
            $errorMessege = "Invalid phone number format. Use 123-456-789.";
            break;
        }

        $sql = "INSERT INTO courier (first_name, last_name, contact_number, department_id) " .
                "VALUES ('$first_name', '$last_name', '$contact_number', '$department_id')";
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessege = "Invalid query: " . $connection->error;
            break;
        }

        $first_name = "";
        $last_name = "";
        $contact_number = "";
        $department_id = "";

        $successMessege = "Client added correctly";

        header("Location: http://localhost/Poczta/Po.php");
        exit;

    } while (false);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poczta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class= "cont my-5">
        <h2>Nowy curier</h2>

        <?php
        if ( !empty($errorMessege) ) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'> 
                <strong>$errorMessege</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>
        <form method="post" >
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Imię</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="first_name" value="<?php echo $first_name ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nazwisko</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="last_name" value="<?php echo $last_name ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Telefon</label>
                <div class="col-sm-6">
                    <input 
                        type="text" 
                        class="form-control" 
                        name="contact_number" 
                        id="contact_number" 
                        placeholder="123-456-789" 
                        value="<?php echo $contact_number ?>" 
                        pattern="\d{3}-\d{3}-\d{3}" 
                        title="Numer telefonu musi mieć format: 123-456-789"
                        required
                    >
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Dział</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="department_id" value="<?php echo $department_id ?>">
                </div>
            </div>
              <?php
             if ( !empty($successMessege) ) {
                echo "
                   <div class='row mb-3'>
                        <div class='offset-sm-3 col-sm-6'>
                            <div class='alert alert-success alert-dismissible fade show' role='alert'> 
                              <strong>$successMessege</strong>
                             <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>    
                        </div>
                   </div>
                ";
             }
            ?>
            <div class="row-mb-3">
                <div class="offset=sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/Poczta/Po.php" role="button">Cansel</a>
                </div>
            </div>
        </form>
    </div>
    <script>
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