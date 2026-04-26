<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>To do list</title>
</head>

<body style="background: linear-gradient(135deg, #74ebd5, #ACB6E5);">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
<div class="col-md-6">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$conn = new mysqli("localhost", "root", "root", "php");

if ($conn->connect_error) {
    die("DB chyba: " . $conn->connect_error);
}

if (isset($_POST["register"])) {
    $_SESSION["register"] = 1;
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

if (isset($_POST["return"])) {
    unset($_SESSION["register"]);
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

$loginError = false;
$registerError = "";
$registerSuccess = "";

if (isset($_POST["login"])) {
    $name = $_POST["name"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM pouzivatelia WHERE Meno='$name' AND Heslo='$password'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION["logged"] = 1;
        $_SESSION["id"] = $row["pouzivatel_id"];
        $_SESSION["meno"] = $row["Meno"];

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        $loginError = true;
    }
}

if (isset($_POST["logout"])) {  
    session_destroy();
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

if (isset($_POST["register2"])) {
    $name = $_POST["name"];
    $password = $_POST["password"];

    $sql3 = "SELECT * FROM pouzivatelia WHERE Meno='$name'";
    $resultCheck = mysqli_query($conn, $sql3);

    if (mysqli_num_rows($resultCheck) > 0) {
        $registerError = "Používateľ už existuje!";
    } else {
        $sql4 = "INSERT INTO pouzivatelia (Meno, Heslo) VALUES ('$name', '$password')";
        if (mysqli_query($conn, $sql4)) {
            $registerSuccess = "Registrácia úspešná! Môžete sa prihlásiť.";
            unset($_SESSION["register"]);
        } else {
            $registerError = "Chyba pri registrácii!";
        }
    }
}
?>

<div class="card shadow-lg rounded-4">
<div class="card-body p-4">

<h3 class="text-center mb-4">To-Do List</h3>

<?php if (!isset($_SESSION["logged"])) { ?>

    <?php if ($loginError) { ?>
        <div class="alert alert-danger">Neplatné meno alebo heslo!</div>
    <?php } ?>

    <?php if ($registerError) { ?>
        <div class="alert alert-danger"><?php echo $registerError; ?></div>
    <?php } ?>

    <?php if ($registerSuccess) { ?>
        <div class="alert alert-success"><?php echo $registerSuccess; ?></div>
    <?php } ?>

<?php if (!isset($_SESSION["register"])) { ?>

    <!-- LOGIN -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">👤 Prihlasovacie meno</label>
            <input type="text" class="form-control rounded-3" name="name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Heslo</label>
            <input type="password" class="form-control rounded-3" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-3 mb-2" name="login">
            Prihlásiť sa
        </button>

        <button type="submit" class="btn btn-warning w-100 rounded-3" name="register">
            Registrácia
        </button>
    </form>

<?php } else { ?>

    <!-- REGISTER -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Meno</label>
            <input type="text" class="form-control rounded-3" name="name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Heslo</label>
            <input type="password" class="form-control rounded-3" name="password" required>
        </div>

        <button type="submit" class="btn btn-warning w-100 rounded-3 mb-2" name="register2">
            Registrovať sa
        </button>

        <button type="submit" class="btn btn-outline-secondary w-100 rounded-3" name="return">
            Späť
        </button>
    </form>

<?php } ?>

<?php } else { ?>

    <!-- LOGGED IN -->
    <div class="alert alert-success text-center">
        Vitaj <strong><?php echo $_SESSION["meno"]; ?></strong>!
    </div>

    <form method="POST">
        <button type="submit" class="btn btn-outline-danger w-100 mb-3" name="logout">
            Odhlásiť sa
        </button>
    </form>

    <hr>

<?php 
    $id = $_SESSION["id"];
    $poznamky = "";

    if (isset($_POST["post"])) {
        $poznamky = $_POST["poznamky"];

        $sql2 = "UPDATE poznamky SET poznamka='$poznamky' WHERE pouzivatel_id='$id'";
        mysqli_query($conn, $sql2);

        if (mysqli_affected_rows($conn) == 0) {
            $sqlInsert = "INSERT INTO poznamky (poznamka, pouzivatel_id) VALUES ('$poznamky', '$id')";
            mysqli_query($conn, $sqlInsert);
        }
    }

    $sql3 = "SELECT * FROM poznamky WHERE pouzivatel_id='$id'";
    $result2 = mysqli_query($conn, $sql3);

    if ($row2 = mysqli_fetch_assoc($result2)) {
        $poznamky = $row2["poznamka"];
    }
?>

    <!-- TODO -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">To do:</label>
            <textarea class="form-control rounded-3 shadow-sm" 
                      name="poznamky" 
                      style="height: 120px;"
                      placeholder="Napíš svoje úlohy..."><?php echo htmlspecialchars($poznamky); ?></textarea>
        </div>

        <button type="submit" class="btn btn-success w-100 rounded-3" name="post">
            Uložiť
        </button>
    </form>

<?php } ?>

</div>
</div>

</div>
</div>

</body>
</html>
