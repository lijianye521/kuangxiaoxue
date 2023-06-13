<?php
    session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <title>班级作业管理系统</title>
    <link href="node_modules/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
     <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .navbar-brand {
        font-size: 1.5rem;
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        
     <a class="navbar-brand" href=" ">
        <img src="logo.jpg" width="30" height="30" class="d-inline-block align-top" alt="">
        计科四班班级作业管理系统
    </a >
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">

   
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['name'])) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo $_SESSION['student_id']; ?></a>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">登录</a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link" href="register.php">注册</a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="tasks.php" style="color:red;">需上传的作业任务</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="developer.php">开发者下载</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_task.php">创建收集任务</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="file_status.php">任务上传进度</a>
                </li>
                  <li class="nav-item">
                    <a class="nav-link" href="change_password.html">修改密码</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Introduction Section -->
    <div class="container mt-4 main-content">
        <h1 class="text-center">班级作业管理系统</h1>
        <p>这是一个用于班级作业管理的系统，在这一版本中使用 PHP 和 Bootstrap 开发。已经更新了管理员任务上传进度查看功能和任务创建功能。</p>

        <h2 class="mt-4">关于 PHP</h2>
        <p>PHP 是一种开源的脚本语言，特别适合于网络开发，并且可以嵌入到 HTML 中。PHP 可以用于创建动态网页内容，以及处理各种表单，包括用户输入和文件上传等。</p>

        <h2 class="mt-4">关于 Bootstrap</h2>
        <p>Bootstrap 是一种免费且开源的前端框架，用于创建响应式和移动优先的网站。它包含 HTML 和 CSS 的模板设计，用于各种元素，例如按钮、导航栏和其他界面组件，还附带了可选的 JavaScript 插件。
            页面布局不太完美 是因为我懒得调,手机端访问不知道为何导航栏不能点击了，回头我再改吧，你们先用着电脑登录，整个项目源码会在下一版本之后开源。
        </p>


    </div>


    <!-- Footer Section -->
    <footer class="bg-light text-center py-4 mt-4">
        <p>版权所有 &copy; <?php echo date('Y'); ?>caiye <a href="https://lijianye521.github.io">我的博客</a></p>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>