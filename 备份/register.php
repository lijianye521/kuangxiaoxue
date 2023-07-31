<?php
    include('dbconnects.php'); 

    if (isset($_POST['submit'])){
        $student_id = $_POST['student_id'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $province = $_POST['province'];
        $political_status = $_POST['political_status'];
        $ethnicity = $_POST['ethnicity'];
        $dormitory = $_POST['dormitory'];
        $remark = $_POST['remark'];

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die('Connection Failed : '.$conn->connect_error);
        } else {
            $stmt = $conn->prepare('insert into user(student_id, password, name, gender, province, political_status, ethnicity, dormitory, remark) values(?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->bind_param('sssssssss', $student_id, $password, $name, $gender, $province, $political_status, $ethnicity, $dormitory, $remark);
            $stmt->execute();
            echo '<div class="alert alert-success mt-3" role="alert">注册成功！</div>';
            $stmt->close();
            $conn->close();
        }
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

    .container {
        max-width: 500px;
        margin-top: 100px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">注册</h1>
        <form method="post">
            <div class="form-group">
                <label for="student_id">学生ID</label>
                <input type="text" name="student_id" class="form-control" id="student_id" placeholder="请输入学生ID"
                    required>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="请输入密码" required>
            </div>
            <div class="form-group">
                <label for="name">姓名</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="请输入姓名">
            </div>
            <div class="form-group">
                <label for="gender">性别</label>
                <select class="form-control" id="gender" name="gender">
                    <option>男</option>
                    <option>女</option>
                </select>
            </div>
            <div class="form-group">
                <label for="province">省份</label>
                <input type="text" name="province" class="form-control" id="province" placeholder="请输入省份">
            </div>
            <div class="form-group">
                <label for="political_status">政治面貌</label>
                <input type="text" name="political_status" class="form-control" id="political_status"
                    placeholder="请输入政治面貌">
            </div>
            <div class="form-group">
                <label for="ethnicity">民族</label>
                <input type="text" name="ethnicity" class="form-control" id="ethnicity" placeholder="请输入民族">
            </div>
            <div class="form-group">
                <label for="dormitory">宿舍号</label>
                <input type="text" name="dormitory" class="form-control" id="dormitory" placeholder="请输入宿舍号">
            </div>
            <div class="form-group">
                <label for="remark">备注</label>
                <input type="text" name="remark" class="form-control" id="remark" placeholder="请输入备注">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">注册</button>
            <a href="login.php" class="btn btn-link">已有账号，去登录</a>
        </form>
    </div>
</body>

</html>