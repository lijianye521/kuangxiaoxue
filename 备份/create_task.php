<?php
    include('dbconnect.php');
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['student_id'])) {
        header('Location: login.php');
        exit();
    }
// Check if user is admin
if ($_SESSION['student_id'] != '12203743' && $_SESSION['student_id'] != '04203232' )
  {
    header('Location: login.php');
    exit();
}
    // Check if form is submitted
    if (isset($_POST['submit'])){
        $title = $_POST['title'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $assigned_to = $_POST['assigned_to'];
        $created_by = $_SESSION['student_id'];

        // Connect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die('Connection Failed : '.$conn->connect_error);
        } else {
            // Insert task into the database
            $stmt = $conn->prepare('insert into tasks(title, description, status, assigned_to, created_by) values(?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $title, $description, $status, $assigned_to, $created_by);
            $stmt->execute();
            echo '<div class="alert alert-success mt-3" role="alert">任务创建成功！</div>';
            $stmt->close();
            $conn->close();
        }
    }

    // Fetch all users
    $conn = new mysqli($servername, $username, $password, $dbname);
    $stmt = $conn->prepare('select student_id from user');
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h1 class="text-center">创建任务</h1>
        <form method="post">
            <div class="form-group">
                <label for="title">标题</label>
                <input type="text" name="title" class="form-control" id="title" placeholder="请输入任务标题" required>
            </div>
            <div class="form-group">
                <label for="description">描述</label>
                <textarea name="description" class="form-control" id="description" placeholder="请输入任务描述" rows="5"
                    required></textarea>
            </div>
            <div class="form-group">
                <label for="status">状态</label>
                <select name="status" class="form-control" id="status" required>
                    <option value="未开始">未开始</option>
                    <option value="进行中">进行中</option>
                    <option value="已完成">已完成</option>
                </select>
            </div>
            <div class="form-group">
                <label for="assigned_to">负责人</label>
                <select name="assigned_to" class="form-control" id="assigned_to" required>
                    <option value="班长">班长</option>
                    <option value="学委">学委</option>
                </select>
                <br>
            </div>
            <br>
            <button type="submit" name="submit" class="btn btn-primary">创建</button>
        </form>
    </div>
</body>

</html>