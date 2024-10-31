<?php
$host = 'localhost'; 
$db = 'soccer';     
$user = 'root';    
$pass = '';

$mysqli = new mysqli($host, $user, $pass, $db);
session_start();

$register_message = "";

if (isset($_SESSION["is_login"])) {
    header("location: dashboard.php");
}

if (isset($_POST["register"])) {
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $hash_password = hash("sha256", $password);

    try {
        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash_password);

        if ($stmt->execute()) {
            $register_message = "Daftar akun berhasil, silahkan login";
        } else {
            $register_message = "Daftar akun gagal, silahkan coba lagi";
        }

        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        $register_message = "Username sudah digunakan";
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GOALDRUL REGISTER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('assets/bggd.jpg');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            background-attachment: fixed; 
            height: 100vh;
            margin: 0;
        }
    </style>
</head>
<body>

<section>
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-6 m-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-5 pt-4" style="text-align: center;">GOALDRUL</h3>
                        <form action="" method="POST"> <!-- Form action diarahkan ke halaman yang sama -->
                            <input type="email" class="form-control my-4 py-2" placeholder="example@gmail.com" name="email" required/>
                            <input type="text" class="form-control my-4 py-2" placeholder="Username" name="username" required/>
                            <input type="password" class="form-control my-4 py-2" placeholder="Password" name="password" required/>
                            <div class="text-center mt-3">
                                <button class="btn btn-primary" type="submit" name="register">Register</button>
                                <a href="login.php" class="nav-link mt-3">Already have an account?</a>
                            </div>
                        </form>
                        <!-- Display register message if exists -->
                        <?php if ($register_message != "") { ?>
                            <div class="alert alert-info mt-3">
                                <?php echo $register_message; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>