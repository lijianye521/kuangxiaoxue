<?php
    include('dbconnect.php');
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['student_id'])) {
        header('Location: login.php');
        exit();
    }

    // Check if user is admin
    if ($_SESSION['student_id'] != '12203743' && $_SESSION['student_id'] != '04203232' ) {
        header('Location: login.php');
        exit();
    }

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die('Connection Failed : '.$conn->connect_error);
    }

    // Get all tasks
    $stmt = $conn->prepare('SELECT task_id, title FROM tasks');
    $stmt->execute();
    $result = $stmt->get_result();
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get selected task ID from the URL
    $selected_task_id = $_GET['task_id'] ?? '';

    // Get file upload status for each student
    $stmt = $conn->prepare('SELECT t.task_id, t.title, u.name, f.file_name
                            FROM tasks t
                            LEFT JOIN user u ON 1=1
                            LEFT JOIN files f ON t.task_id = f.task_id AND u.student_id = f.student_id
                            WHERE t.task_id = ?');
    $stmt->bind_param('s', $selected_task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $file_status = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
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

    .status-uploaded {
        color: green;
        font-weight: bold;
    }

    .status-not-uploaded {
        color: red;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">文件上传状态</h1>
        <form action="file_status.php" method="get">
            <div class="form-group">
                <label for="task">选择任务</label>
                <select name="task_id" class="form-control" id="task" onchange="this.form.submit()">
                    <option value="">请选择任务</option>
                    <?php foreach ($tasks as $task) { ?>
                    <option value="<?php echo $task['task_id']; ?>"
                        <?php if ($task['task_id'] == $selected_task_id) echo 'selected'; ?>>
                        <?php echo $task['title']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </form>
        <?php if (!empty($selected_task_id)) { ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">用户名称</th>
                    <th scope="col">上传状态</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($file_status as $status) { ?>
                <tr>
                    <td><?php echo $status['name']; ?></td>
                    <td class="<?php echo $status['file_name'] ? 'status-uploaded' : 'status-not-uploaded'; ?>"
                        style="color: <?php echo $status['file_name'] ? 'green' : 'red'; ?>">
                        <?php if ($status['file_name']) { ?>
                        已上传
                        <?php } else { ?>
                        未上传
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
        <?php } ?>
    </div>
</body>

</html>