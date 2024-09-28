<?php

try {
    $conn = new PDO("mysql:host=localhost;dbname=blog-project","root","");
} catch (PDOExeption $e) {
    echo "can not connect to database " . $e;
}

?>