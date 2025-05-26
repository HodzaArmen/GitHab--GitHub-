<?php
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['files'])) {
        $uploaded_files = [];
        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['files']['name'][$key];
            $file_tmp = $_FILES['files']['tmp_name'][$key];
            $file_error = $_FILES['files']['error'][$key];

            if ($file_error === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/';
                $file_path = $upload_dir . basename($file_name);
                
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $uploaded_files[] = $file_name; 
                } else {
                    echo "Napaka pri nalaganju datoteke $file_name.<br>";
                }
            } else {
                echo "Napaka pri nalaganju datoteke $file_name: $file_error.<br>";
            }
        }

        $name = $_POST['name'];
        $description = $_POST['description'];
        $is_public = isset($_POST['is_public']) ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO repositories (user_id, name, description, is_public) VALUES (?, ?, ?, ?)");
        $stmt->execute([1, $name, $description, $is_public]); 

        $repo_id = $pdo->lastInsertId();

        foreach ($uploaded_files as $file_name) {
            $file_path = $upload_dir . basename($file_name);
            $stmt = $pdo->prepare("INSERT INTO uploads (repo_id, file_name, file_path) VALUES (?, ?, ?)");
            $stmt->execute([$repo_id, $file_name, $file_path]);
        }

        echo "Repozitorij '$name' je bil uspešno ustvarjen in datoteke so bile naložene.";
    } else {
        echo "Nobena datoteka ni bila izbrana.";
    }
}
?>
