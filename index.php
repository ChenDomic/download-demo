<?php
require_once "d:/web/phplearn/download/src/db.php";
$db = DB::getInstance();

$list = $db->query("select id, filename from dload_file");

$list_html = '';

foreach($list as $item){
    $list_html .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?id=' . $item['id'] .'">' . $item['filename'] . '</a></li>';
}

$html = "
    <h3>文件列表</h3>
    <ul>
        {$list_html}
    </ul>
  ";
ob_start();
if (isset($_GET) and isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die($html);
}


$sql = "select filename from dload_file where id = $id";
$row = $db->query($sql);
if ($row = $row[0]) {
    $filename = $row['filename'];
} else {
    die('没有文件');
}

require_once "d:/web/phplearn/download/src/download.php";

defined('DS') OR define('DS', DIRECTORY_SEPARATOR);

$basepath = getcwd() . DS . 'file';
$option = array();
$option['basepath'] = $basepath;
$option['filename'] = $filename;
// $option['hidden'] = true;

$download = new Download($option);

$result =$download->exec();

if(!$result) {
    header('HTTP/1.1 404 Not Found');
    echo "404";
}


