<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

try {
    $conn = getConnection();

    if(isset($_GET['id'])){
        $id=$_GET['id'];
        
        $sql = "DELETE FROM berita WHERE id='$id'";
        $sql2 = "DELETE FROM foto_berita WHERE berita_id='$id'";
        $result = $conn->query($sql);
        $result2 = $conn->query($sql2);

        if($result && $result2)
            header("Location: ../index.php");
        else
            echo "Error msg: " . $conn->error;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
