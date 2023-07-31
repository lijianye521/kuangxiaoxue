<?php
// 设置文件上传目录，确保此目录存在并可写
$upload_dir = 'uploads/';

// 获取用户ID
session_start();
$student_id = $_SESSION['student_id'];

// 检查文件是否通过 HTTP POST 上传
if ($_FILES['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name'])) {
    // 移动文件到指定目录
    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . basename($_FILES['file']['name']))) {
        // 记录上传信息到数据库
        $filename = basename($_FILES['file']['name']);
        $conn = new mysqli('localhost', 'root', '123456', 'class');
        if ($conn->connect_error) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => '无法连接到数据库']);
            exit();
        }
        $stmt = $conn->prepare('INSERT INTO uploads (student_id, filename) VALUES (?, ?)');
        $stmt->bind_param('ss', $student_id, $filename);
        if ($stmt->execute()) {
            // 返回成功信息
            echo json_encode(['status' => 'success']);
        } else {
            // 返回错误信息
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => '无法记录上传信息到数据库']);
        }
        $stmt->close();
        $conn->close();
    } else {
        // 返回错误信息
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => '文件移动失败']);
    }
} else {
    // 返回错误信息
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '文件上传失败']);
}
?>