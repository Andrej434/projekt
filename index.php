<!DOCTYPE html>
<html lang="sk">

<head>
  <meta charset="UTF-8">
  <title>Login stránka - Cookies úloha</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <?php
  $conn = mysqli_connect("localhost", "root", "root", "auth");

  if(!$conn){
    echo "Chyba pripojenia" . mysqli_connect_error();
  }
  ?>

<?php

// LOGIN
if (isset($_POST["login"])) {
  $username = $_POST["username"];
  $password = $_POST["password"]; 

  $sql = "SELECT * FROM user WHERE name = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $sql);

  if(mysqli_num_rows($result) == 1){
    setcookie("logged", "1", time() + 3600);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }
}

// LOGOUT
if (isset($_POST["logout"])) {
  setcookie("logged", "", time() - 3600);
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    
    <h3 class="text-center mb-3">Prihlásenie používateľa</h3>
    <p class="text-muted text-center">Použitie cookies</p>

    <?php if (!isset($_COOKIE["logged"])){ ?>
      
      <!-- LOGIN FORM -->
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Používateľské meno</label>
          <input type="text" class="form-control" name="username" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Heslo</label>
          <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100">
          Prihlásiť sa
        </button>
      </form>

    <?php }else{ ?>

      <!-- LOGOUT -->
      <form method="post">
        <button type="submit" name="logout" class="btn btn-outline-danger w-100">
          Odhlásiť sa
        </button>
      </form>

    <?php } ?>

    <hr class="my-3">

    <!-- STATUS -->
    <div class="text-center">
      <?php
      if (isset($_COOKIE["logged"])) {
        echo "Používateľ je prihlásený";
      } else {
        echo "Používateľ nie je prihlásený ";
        echo "<a href='register.php'>Registrovať sa</a>";
      }
      ?>
    </div>

    <?php
    include "footer.php";
    ?>

  </div>
</div>

</body>
</html>
