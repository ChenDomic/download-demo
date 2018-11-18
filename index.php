<?php
$html = '
    <h3>文件现在</h3>
    <ul>
        <li><a href="' . $_SERVER['PHP_SELF'] . '?id=1">ctps_343_ea.zip</a></li>
        <li><a href="' . $_SERVER['PHP_SELF'] . '?id=7">文件2</a></li>
    </ul>
  ';
ob_start();
if (isset($_GET) and isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die($html);
}

require "d:/web/phplearn/download/src/db.php";
$sql = "select filename from dload_file where id = $id";
$db = DB::getInstance();

$row = $db->query($sql);
var_dump($row);
if ($row = $row[0]) {
    $filename = $row['filename'];
} else {
    die('没有文件');
}

$filepath = "E:/BaiduYunDownload/php学习/视频/PHP核心编程3/$filename";
$dir = getcwd();
print(str_replace(array('/','\\'),DIRECTORY_SEPARATOR,"$dir/file/$filename"));
if (file_exists(str_replace(array('/','\\'), DIRECTORY_SEPARATOR ,"$dir/file/$filename"))) {
    $file = fopen($filepath, 'r');
    // 输入文件标签
    Header("Content-type: video/mp4");
    Header("Accept-Ranges: bytes");
    Header("Accept-Length: " . filesize($filepath));
    Header("Content-Disposition: inline; filename=" . md5($filename) . '.' . explode('.', $filename)[1]);
    echo fread($file, filesize($filepath));
} else {
    die('不存在');
}

ob_flush();
