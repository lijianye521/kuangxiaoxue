<?php
    include('dbconnect.php');
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['student_id'])) {
        header('Location: login.php');
        exit();
    }

    // Check if task_id is provided in the URL
    if (!isset($_GET['task_id'])) {
        header('Location: tasks.php');
        exit();
    }

    $task_id = $_GET['task_id'];

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die('Connection Failed : '.$conn->connect_error);
    } else {
        // Get the task information
        $stmt = $conn->prepare('SELECT title FROM tasks WHERE task_id = ?');
        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $task = $result->fetch_assoc();
        $stmt->close();

        // Get files uploaded by the user for the task
        $stmt = $conn->prepare('SELECT * FROM files WHERE task_id = ? AND student_id = ?');
        $stmt->bind_param('is', $task_id, $_SESSION['student_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $files = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    // Handle file deletion
    if (isset($_GET['delete']) && isset($_GET['file_id'])) {
        $file_id = $_GET['file_id'];

        // Get file information from the database
        $stmt = $conn->prepare('SELECT file_name FROM files WHERE file_id = ?');
        $stmt->bind_param('i', $file_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();
        $stmt->close();

        if ($file) {
            $file_name = $file['file_name'];

            // Delete file from the server
            $file_path = 'uploads/' . $task_id . '/' . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            // Delete file record from the database
            $stmt = $conn->prepare('DELETE FROM files WHERE file_id = ?');
            $stmt->bind_param('i', $file_id);
            $stmt->execute();
            $stmt->close();

            // Redirect back to the uploads page
            header('Location: uploads.php?task_id=' . $task_id);
            exit();
        }
    }

    // Handle file upload
    if (isset($_POST['submit'])) {
        // Get the uploaded file details
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_error = $_FILES['file']['error'];

        // Check if file was uploaded successfully
        if ($file_error === UPLOAD_ERR_OK) {
            // Generate a unique file name

            // Generate a unique file name
$file_name_new = uniqid('', true) . '_' . $file_name;

// Remove the random prefix from the file name
$file_name_new = substr($file_name_new, strpos($file_name_new, '_') + 1);
     // Define the file path
     $file_path = 'uploads/' . $task_id . '/' . $file_name_new;

            // Create the task folder if it doesn't exist
            if (!file_exists('uploads/' . $task_id)) {
                mkdir('uploads/' . $task_id);
            }

            // Move the uploaded file to the destination folder
            move_uploaded_file($file_tmp, $file_path);

            // Insert file information into the database
            $stmt = $conn->prepare('INSERT INTO files (task_id, student_id, file_name) VALUES (?, ?, ?)');
            $stmt->bind_param('iss', $task_id, $_SESSION['student_id'], $file_name_new);
            $stmt->execute();
            $stmt->close();

            // Store the success message in session
            $_SESSION['upload_success'] = '文件上传成功！';

            // Redirect back to the uploads page
            header('Location: upload.php?task_id=' . $task_id);
            exit();
        } else {
            // Store the error message in session
            $_SESSION['upload_error'] = '文件上传失败，请重试。';

            // Redirect back to the uploads page
            header('Location: upload.php?task_id=' . $task_id);
            exit();
        }
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Courgette', cursive;
    }

    .container {
        max-width: 500px;
        margin-top: 100px;
    }
    </style>
</head>

<body>
    <div class="container">
        <button onclick="history.go(-1);" class="btn btn-secondary btn-block mt-2">返回</button>

    </div>
    <div class="container">
        <h1 class="text-center">上传文件</h1>
        <h3 class="text-center"><?php echo $task['title']; ?></h3>

        <?php if (isset($_SESSION['upload_success'])) { ?>
        <div class="alert alert-success mt-3" role="alert">
            <?php echo $_SESSION['upload_success']; ?>
        </div>
        <?php unset($_SESSION['upload_success']); ?>
        <?php } ?>

        <?php if (isset($_SESSION['upload_error'])) { ?>
        <div class="alert alert-danger mt-3" role="alert">
            <?php echo $_SESSION['upload_error']; ?>
        </div>
        <?php unset($_SESSION['upload_error']); ?>
        <?php } ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="file">点击选择文件</label>
                <input type="file" name="file" id="file" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">点击上传</button>
        </form>
    </div>
    <div class="container">
        <h1 class="text-center">您已上传文件</h1>
        <h3 class="text-center"><?php echo $task['title']; ?></h3>

        <?php if (count($files) > 0) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">文件名</th>
                    <th scope="col">下载</th>
                    <th scope="col">删除</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file) { ?>
                <tr>
                    <td><?php echo $file['file_name']; ?></td>
                    <td>
                        <a href="uploads/<?php echo $task_id; ?>/<?php echo $file['file_name']; ?>" download
                            class="btn btn-primary">下载</a>

                    </td>
                    <td>
                        <form action="delete.php" method="post" onsubmit="return confirm('确定删除该文件吗？')">
                            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                            <input type="hidden" name="file_name" value="<?php echo $file['file_name']; ?>">
                            <button type="submit" name="submit" class="btn btn-danger">删除</button>
                        </form>


                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p class="text-center">没有上传的文件。</p>
        <?php } ?>
    </div>


</body>

</html>