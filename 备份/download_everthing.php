<?php
session_start();

// 检查用户是否为菜业专属账号
if ($_SESSION['student_id'] === '0000') {
    // 设置压缩文件的名称
    $zip_filename = 'uploads.zip';

    // 打开 zip 文件
    $zip = new ZipArchive();
    if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("无法创建zip文件");
    }

    // 向 zip 文件添加所有文件
    $upload_dir = 'uploads/';
    $files = scandir($upload_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $zip->addFile($upload_dir . $file, $file);
        }
    }

    // 关闭 zip 文件
    $zip->close();

    // 下载压缩文件
    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=\"" . basename($zip_filename) . "\"");
    header("Content-Length: " . filesize($zip_filename));
    readfile($zip_filename);

    // 删除压缩文件
    unlink($zip_filename);
} else {
    echo '您不是菜业专属账号，无法下载所有文件。';
}
?>