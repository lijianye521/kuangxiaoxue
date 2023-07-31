<?php
session_start();

// 设置上传目录
$upload_dir = 'uploads/';

$conn = new mysqli("localhost", "root", "123456", "class");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文件管理页面</title>

    <!-- 引入 Bootstrap CSS 文件 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="mt-5">文件管理页面</h1>
        <?php


// 如果有要删除的文件，进行删除
if (isset($_POST['delete_file'])) {
    $filename = $_POST['delete_file'];
    if (file_exists($upload_dir . $filename)) {
        // 检查是否是当前用户上传的文件
      
        $student_id = $_SESSION['student_id'];
        $sql = "SELECT * FROM uploads WHERE filename = ? AND student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $filename, $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0||$_SESSION['student_id']==0000) {
            // 删除文件
            unlink($upload_dir . $filename);
            // 从数据库中删除记录
            $sql = "DELETE FROM uploads WHERE filename = ? AND student_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $filename, $student_id);
            $stmt->execute();
            echo '<div class="alert alert-success" role="alert">文件 "' . $filename . '" 已被删除。</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">您没有权限删除此文件。</div>';
        }
        $stmt->close();
      
    } else {
        echo '<div class="alert alert-danger" role="alert">文件 "' . $filename . '" 不存在或无法删除。</div>';
    }
}

// 如果有要下载的文件，进行下载
if (isset($_GET['file'])) {
    $filename = $_GET['file'];
    if (file_exists($upload_dir . $filename)) {
        // 检查是否是当前用户上传的文件
        $conn = new mysqli("localhost", "root", "123456", "class");
        $student_id = $_SESSION['student_id'];
        $sql = "SELECT * FROM uploads WHERE filename = ? AND student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $filename, $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // 下载文件
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . basename($upload_dir . $filename) . "\"");
            readfile($upload_dir . $filename);
        } else {
            echo '<div class="alert alert-danger" role="alert">您没有权限下载此文件。</div>';
        }
        $stmt->close();
     
    } else {
        echo '<div class="alert alert-danger" role="alert">文件 "' . $filename . '" 不存在或无法下载。</div>';
    }
}

// 列出所有已上传的文件
$files = scandir($upload_dir);
echo '<h2>已上传的文件</h2>';
echo '<button type="button" class="btn btn-warning" onclick="location.href=\'download_everthing.php\'">菜业专属一键全部下载</button>';
echo '<table class="table">';
echo '<thead><tr><th>文件名</th><th>大小</th><th>操作</th><th>下载</th></tr></thead>';
echo '<tbody>';
foreach ($files as $file) {
if ($file != '.' && $file != '..') {
// 检查是否是该用户上传的文件
$conn = new mysqli("localhost", "root", "123456", "class");
if ($conn->connect_error) {
die("连接失败: " . $conn->connect_error);
}
$stmt = $conn->prepare("SELECT * FROM uploads WHERE filename = ? AND student_id = ?");
$stmt->bind_param("ss", $file, $_SESSION['student_id']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();
if($_SESSION['student_id']==0000)
{
    //管理员模式
    echo '<tr>';
    echo '<td>' . $file . '</td>';
    echo '<td>' . filesize($upload_dir . $file) . ' 字节</td>';
    echo '<td>';
    echo '<form method="post" onsubmit="return confirm(\'确定要删除此文件吗？\')">';
    echo '<input type="hidden" name="delete_file" value="' . $file . '">';
    echo '<button type="submit" class="btn btn-danger">删除</button>';
    echo '</form>';
    echo '</td>';
    echo '<td>';
    echo '<form method="get">';
    echo '<input type="hidden" name="filename" value="' . $file . '">';
    echo '<button type="submit" class="btn btn-primary">下载</button>';
    echo '</form>';
    if (isset($_GET['filename'])) {
            $filename = $_GET['filename'];
            if (file_exists($upload_dir . $filename)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $filename);
                readfile($upload_dir . $filename);
                exit;
            } else {
                echo '<div class="alert alert-danger" role="alert">文件 "' . $filename . '" 不存在或无法下载。</div>';
            }
        }
        
        echo '</td>';
        echo '</tr>';
}
else
{//普通用户
    if ($result->num_rows > 0) {
        echo '<tr>';
        echo '<td>' . $file . '</td>';
        echo '<td>' . filesize($upload_dir . $file) . ' 字节</td>';
        echo '<td>';
        echo '<form method="post" onsubmit="return confirm(\'确定要删除此文件吗？\')">';
        echo '<input type="hidden" name="delete_file" value="' . $file . '">';
        echo '<button type="submit" class="btn btn-danger">删除</button>';
        echo '</form>';
        echo '</td>';
        echo '<td>';
        echo '<form method="get">';
        echo '<input type="hidden" name="filename" value="' . $file . '">';
        echo '<button type="submit" class="btn btn-primary">下载</button>';
        echo '</form>';
        
        if (isset($_GET['filename'])) {
            $filename = $_GET['filename'];
            if (file_exists($upload_dir . $filename)) {
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . $filename);
                readfile($upload_dir . $filename);
                exit;
            } else {
                echo '<div class="alert alert-danger" role="alert">文件 "' . $filename . '" 不存在或无法下载。</div>';
            }
        }
        
        echo '</td>';
        echo '</tr>';
    } 
    else {
        echo '<tr>';
        echo '<td>' . $file . '</td>';
        echo '<td>' . filesize($upload_dir . $file) . ' 字节</td>';
        echo '<td>无权限操作</td>';
        echo '</tr>';
    }


}

}
}
echo '</tbody>';
echo '</table>';





?>

    </div>
</body>

</html>