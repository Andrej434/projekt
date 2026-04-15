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
        <form method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Prihlasovacie Meno</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="name">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Heslo</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <button type="submit" class="btn btn-primary" name="login">Prihlásiť sa</button>
        </form>
        <?php

        /* Pripojenie na databázu*/
        $conn = mysqli_connect("localhost", "root", "root", "php");


        if (isset($_POST["login"])) {
            $name = $_POST["name"];
            $password = $_POST["password"]; 

            $sql1 = "SELECT Pouzivatel_id FROM pouzivatelia WHERE Meno = '$name' AND Heslo = '$password'";
            $result1 = mysqli_query($conn, $sql1);
            $sql2 = "SELECT poznamka FROM poznamky WHERE Pouzivatel_id = '$sql1'";
            $result2 = mysqli_query($conn, $sql2);
        }

        ?>
    </header> <br>
    <main>
        <div>
            <?php echo "Vytaj" . $name . "!"?> <br> <br>
        </div>
        <?php/*Poznamky*/?>
        <form method="POST">
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">To do:</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" style="height: 75px;" name="poznamky><?php echo "" . $poznamky . ""?></textarea>
            </div>
            <div>
                <button type="button class="btn btn-primary" name="post">Prepísať</button>
            </div>
        </form>

        <?php
        $poznamky = [];
        $result2 = mysqli_query($conn, $sql2);
        while ($row = mysqli_fetch_assoc($result2)){
            $poznamky[] = $row;
        }

        if (isset($_POST["post"])){
            $poznamky = $_POST['poznamky']
            $sql3 = "UPDATE poznamky SET poznamka = '$poznamky' WHERE Pouzivatel_id = '$sql1'";
            mysqli_query($conn, $sql3);
        }
        ?>
    </main>
</body>

</html>
