<?php
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

$zip_filename = 'uploads.zip';
$upload_dir = 'uploads/';

// 创建新的 zip 文件
$zip = new ZipArchive();
if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("无法创建zip文件");
}

// 创建递归添加文件和子目录的函数
function addDirToZip($dir, $zip, $zipPath) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dir . $file)) {
                    addDirToZip($dir . $file . '/', $zip, $zipPath . $file . '/');
                } else {
                    $zip->addFile($dir . $file, $zipPath . $file);
                }
            }
        }
    }
}

// 添加上传目录及其子目录和文件
addDirToZip($upload_dir, $zip, '');

// 关闭 zip 文件
$zip->close();

// 下载压缩文件
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=\"" . basename($zip_filename) . "\"");
header("Content-Length: " . filesize($zip_filename));
readfile($zip_filename);

// 删除压缩文件
unlink($zip_filename);

?>