<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>To do list</title>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="px-5 py-5 m-3 border-0 bd-example border-0">

    <header>

        <?php
        session_start();
        $conn = mysqli_connect("localhost", "root", "root", "php");


        if (isset($_POST["login"])) {
            $name = $_POST["name"];
            $password = $_POST["password"]; 
            
            $sql = "SELECT * FROM pouzivatelia WHERE Meno = '$name' AND Heslo = '$password'";
            $result = mysqli_query($conn, $sql);

            if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION["logged"] = 1;
            $_SESSION["id"] = $row["Pouzivatel_id"];
            $_SESSION["meno"] = $row["Meno"];
            }
        }
        ?>
        
        <?php if (!isset($_SESSION["logged"])) { ?>
        <form method="POST">
            <div class="mb-3">
                <label for="exampleInputtext1" class="form-label">Prihlasovacie Meno</label>
                <input type="text" class="form-control" id="exampleInputtext1" aria-describedby="textHelp" name="name">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Heslo</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <button type="submit" class="btn btn-primary" name="login">Prihlásiť sa</button>
        </form>
        <?php } else { ?>
        <form method="POST">
            <button type="submit" class="btn btn-danger" name="logout">Odhlásiť sa</button>
        </form>
        <?php }
        if (isset($_POST["logout"])) {  
            session_destroy($_SESSION["logged"]);
        }
        ?>

    </header> <br>
    <main>
        <?php
        if (isset($_SESSION["logged"])){ ?>
        <div>
            <?php echo "Vytaj" . $_SESSION["meno"] . "!"?> <br> <br>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">To do:</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" style="height: 75px;" name="poznamky"><?php echo "" . $poznamky . ""?></textarea>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" name="post">Prepísať</button>
            </div>
        </form>
        <?php
            $id = $_SESSION["id"];
            $poznamky = "";

            if (isset($_POST["post"])) {
            $poznamky = $_POST["poznamky"];
            $sql2 = "UPDATE poznamky SET poznamka='$poznamky' WHERE Pouzivatel_id='$id'";
            mysqli_query($conn, $sql2);
            }

            $sql3 = "SELECT * FROM poznamky WHERE Pouzivatel_id='$id'";
            $result2 = mysqli_query($conn, $sql3);

            if ($row2 = mysqli_fetch_assoc($result2)) {
                $poznamky = $row2["poznamka"];
            }
        }
        ?>
    </main>
</body>

</html>
