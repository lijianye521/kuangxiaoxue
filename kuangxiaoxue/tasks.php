<?php
    include('dbconnect.php');
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['student_id'])) {
        header('Location: login.php');
        exit();
    }

    // Connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die('Connection Failed : '.$conn->connect_error);
    } else {
        // Get all tasks
        $stmt = $conn->prepare('SELECT * FROM tasks');
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
    }
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

    .button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 20px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        outline: none;
        color: #fff;
        background-color: #4CAF50;
        border: none;
        border-radius: 15px;
        box-shadow: 0 9px #999;
    }

    .button:hover {
        background-color: #3e8e41
    }

    .button:active {
        background-color: #3e8e41;
        box-shadow: 0 5px #666;
        transform: translateY(4px);
    }
    </style>
</head>

<body>
    <br>
    <div class="container">
        <a href="index.php" class="button">返回</a>




    </div>
    <div class="container mt-3">
        <h1 class="text-center">任务列表</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">标题</th>
                    <th scope="col">描述</th>
                    <th scope="col">状态</th>
                    <th scope="col">负责人</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task) { ?>
                <tr>
                    <td><a href="upload.php?task_id=<?php echo $task['task_id']; ?>"><?php echo $task['title']; ?></a>
                    </td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo $task['status']; ?></td>
                    <td><?php echo $task['assigned_to']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>