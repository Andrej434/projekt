<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "php";

/* PRIPOJENIE NA MYSQL */
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* VYTVORENIE DB AK NEEXISTUJE */
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";

if ($conn->query($sql) === TRUE) {
    echo "Databaza pripravena<br>";
} else {
    die("Chyba pri vytvarani DB: " . $conn->error);
}

$conn->close();

/* PRIPOJENIE NA DB */
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* TABULKA POUZIVATELIA */
$sql = "CREATE TABLE IF NOT EXISTS pouzivatelia (
    pouzivatel_id INT PRIMARY KEY AUTO_INCREMENT,
    Heslo VARCHAR(20) NOT NULL,
    Meno VARCHAR(20) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabulka pouzivatelia pripravena<br>";
} else {
    die("Chyba: " . $conn->error);
}

/* TABULKA POZNAMKY */
$sql = "CREATE TABLE IF NOT EXISTS poznamky (
    poznamka_id INT PRIMARY KEY AUTO_INCREMENT,
    poznamka VARCHAR(255),
    je_hotovo BOOLEAN DEFAULT 0,
    pouzivatel_id INT,
    FOREIGN KEY (pouzivatel_id) REFERENCES pouzivatelia(pouzivatel_id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabulka poznamky pripravena<br>";
} else {
    die("Chyba: " . $conn->error);
}

/*DUMMY USER*/
$sql = "INSERT INTO pouzivatelia (Heslo, Meno)
SELECT '1234', 'Admin'
WHERE NOT EXISTS (
    SELECT * FROM pouzivatelia WHERE Meno='Admin'
)";

if ($conn->query($sql) === TRUE) {
    echo "Admin skontrolovany / vytvoreny";
} else {
    echo "Chyba pri adminovi: " . $conn->error;
}

$conn->close();
?>
