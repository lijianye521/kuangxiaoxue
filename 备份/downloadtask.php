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
?>
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 引入Bootstrap CSS -->
      <link href="node_modules/bootstrap/dist/css/bootstrap.css" rel="stylesheet">
     <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
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
    .disabled {
  color: #999999;
  cursor: not-allowed;
}
    </style>
    <title>文件下载页面</title>

</head>
<body>
    <div class="container">
        <a href="index.php" class="button">返回</a>
</div>

 <div class="container mt-3">
        <h1 class="text-center">任务列表</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">目录编号</th>
                    <th scope="col">标题</th>
                    <th scope="col">描述</th>
                    <th scope="col">状态</th>
                    <th scope="col">负责人</th>
                </tr>
            </thead>
            <tbody> 
            <tr>
                  <?php foreach ($tasks as $task) { ?>
                 <td><?php echo $task['task_id']; ?></td>
              
               
                   
                     <td><?php echo $task['title']; ?></td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo $task['status']; ?></td>
                    <td><?php echo $task['assigned_to']; ?></td>
               
                    
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<div class="container">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 获取用户输入的数字
        $dir_number = $_POST['dir_number'];
        // 确保目录存在
        if (file_exists("uploads/$dir_number")) {
            // 生成一个唯一的文件名
            $zip_file = "uploads/tmp_" . uniqid() . ".zip";
            // 创建一个新的zip对象
            $zip = new ZipArchive();
            // 打开压缩文件，如果文件不存在则创建一个
            if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
                // 递归添加目录下的所有文件到压缩文件
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator("uploads/$dir_number"),
                    RecursiveIteratorIterator::LEAVES_ONLY
                );
                foreach ($files as $name => $file) {
                    // 跳过目录，只压缩文件
                    if ($file->isDir()) continue;
                    // 获取文件的真实路径
                    $file_path = $file->getRealPath();
                    // 获取文件的相对路径（相对于要压缩的目录）
                    $relative_path = substr($file_path, strlen("uploads/$dir_number") + 1);
                    // 将文件添加到zip对象中
                    $zip->addFile($file_path, $relative_path);
                }
                // 关闭zip对象
                $zip->close();
                // 提供一个下载链接
                echo "<p class='alert alert-success'>下载链接: <a href=\"$zip_file\">点击这里下载</a></p>";
            } else {
                echo "<p class='alert alert-danger'>无法打开或创建压缩文件</p>";
            }
        } else {
            echo "<p class='alert alert-danger'>该目录不存在!</p>";
        }
    } else {
        // 显示输入表单
    ?>
    <form method="POST" class="mt-5">
        <h1 class="h3 mb-3">输入要下载的目录编号:</h1>
        <input type="text" name="dir_number" class="form-control"/><br/>
        <button type="submit" class="btn btn-primary">下载</button>
    </form>
    <?php
    }
    ?>
</div>
<!-- 引入Bootstrap JS -->

    <!-- Bootstrap JavaScript -->
    <script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="node_modules/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>