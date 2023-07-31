<?php
    include('dbconnect.php');

    // Check if task_id and file_name are provided
    if (isset($_POST['task_id']) && isset($_POST['file_name'])) {
        $task_id = $_POST['task_id'];
        $file_name = $_POST['file_name'];

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die('Connection Failed : '.$conn->connect_error);
        } else {
            // Delete file record from the database
            $stmt = $conn->prepare('DELETE FROM files WHERE task_id = ? AND file_name = ?');
            $stmt->bind_param('is', $task_id, $file_name);
            $stmt->execute();
            $stmt->close();

            // Delete file from the server
            $file_path = 'uploads/' . $task_id . '/' . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Redirect back to the uploads page
            header('Location: upload.php?task_id=' . $task_id);
            exit();
        }

        // Close the database connection
        $conn->close();
    } else {
        // Invalid request, redirect to the uploads page
        header('Location: upload.php');
        exit();
    }
?>