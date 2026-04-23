<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>To do list</title>
</head>
<body class="px-5 py-5">

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$conn = new mysqli("localhost", "root", "root", "php");

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

if ($conn->connect_error) {
    die("DB chyba: " . $conn->connect_error);
}

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
        echo '<div class="alert alert-danger">Neplatné meno alebo heslo!</div>';
    }
}

if (isset($_POST["logout"])) {  
    session_destroy();
    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}
?>

    <header>
    <?php 
    if (!isset($_SESSION["logged"])) {
        if (!isset($_SESSION["register"])) { ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Prihlasovacie Meno</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="mb-3">
                <label class="form-label">Heslo</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-primary" name="login">Prihlásiť sa</button>
            <button type="submit" class="btn btn-warning" name="register">Registrácia</button>  
        </form>
    <?php } ?>
    <?php } else { ?>
    
        <div>
            <?php echo "Vitaj " . $_SESSION["meno"] . "!"; ?>
        </div>
    
        <form method="POST">
            <button type="submit" class="btn btn-danger" name="logout">Odhlásiť sa</button>
        </form>
    
    <?php } ?>
    </header>
    <hr>
    <main>

    <?php
    if (isset($_SESSION["register"])) { ?>


        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Prihlasovacie Meno</label>
                <input type="text" class="form-control" name="name">
            </div>
            <div class="mb-3">
                <label class="form-label">Heslo</label>
                <input type="password" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-warning" name="register2">Registrovať sa</button>  
            <button type="submit" class="btn btn-primary" name="return">Späť</button> 
        </form>
    <?php
    }

    if (isset($_POST["register2"])) {
        $name = $_POST["name"];
        $password = $_POST["password"];

        $sql3 = "SELECT * FROM pouzivatelia WHERE Meno='$name'";
        $resultCheck = mysqli_query($conn, $sql3);

        if (mysqli_num_rows($resultCheck) > 0) {
            echo '<div class="alert alert-danger">Poúživateľ už existuje!</div>';
        } else {
            $sql4 = "INSERT INTO pouzivatelia (Meno, Heslo) VALUES ('$name', '$password')";
            if (mysqli_query($conn, $sql4)) {
                echo '<div class="alert alert-success">Registrácia úspešná! Môžete se prihlásiť.</div>';
                unset($_SESSION["register"]);
            } else {
                echo '<div class="alert alert-danger">Chyba při registraci: ' . mysqli_error($conn) . '</div>';
            }
        }
    }
    ?>
    
    
    <?php if (isset($_SESSION["logged"])) {
    
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
    
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">To do:</label>
                <textarea class="form-control" name="poznamky" style="height: 75px;"><?php echo htmlspecialchars($poznamky); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="post">Prepísať</button>
        </form>
    
    <?php } ?>
    </main>

</body>
</html>
