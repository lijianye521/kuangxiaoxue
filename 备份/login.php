<?php
    session_start();
    if (isset($_POST['submit'])){
        $student_id = $_POST['student_id'];
        $password = $_POST['password'];

        $conn = new mysqli('localhost', 'root', '123456', 'class');

        if ($conn->connect_error) {
            die('连接失败 : '.$conn->connect_error);
        } else {
            $stmt = $conn->prepare('select * from user where student_id = ?');
            $stmt->bind_param('s', $student_id);
            $stmt->execute();
            $stmt_result = $stmt->get_result();
            if ($stmt_result->num_rows > 0){
                $data = $stmt_result->fetch_assoc();
                if ($data['password'] === $password){
                    $_SESSION['student_id'] = $student_id;
                    $_SESSION['name'] = $data['name']; 
                    header('Location: tasks.php');
                } else {
                    echo '<h2>无效的学号或密码</h2>';
                }
            } else {
                echo '<h2>无效的学号或密码</h2>';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录页面</title>

    <!-- 引入 Google 字体 -->
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Bhaina+2&display=swap" rel="stylesheet">

    <!-- 引入 Bootstrap CSS -->
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Baloo Bhaina 2', cursive;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-4">
                <br>
                <br>
                <h2 class="text-center">登录</h2>
                <form method="post">
                    <div class="form-group">
                        <label for="student_id">学号</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" placeholder="请输入学号"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="password">密码</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="请输入密码"
                            required>
                    </div>
                    <br>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">登录</button>
                    <a href="register.php" class="btn btn-link btn-block">还没有账号，去注册一个</a>
                </form>
                <button onclick="history.go(-1);" class="btn btn-secondary btn-block mt-2">返回</button>
            </div>
        </div>
    </div>

    <!-- 引入 Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>