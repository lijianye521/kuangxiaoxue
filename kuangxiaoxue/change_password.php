<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "class";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$student_id = $_POST['student_id'];
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];

$sql = "SELECT * FROM user WHERE student_id = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $student_id, $old_password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql = "UPDATE user SET password = ? WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $student_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "密码修改成功！";
    } else {
        echo "发生错误，无法更新密码。";
    }
} else {
    echo "学号或旧密码错误，请重试。";

}

$stmt->close();
$conn->close();
?>