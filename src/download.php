<?php

/**
 * 文件下载类
 *
 * 设置下载类型
 */
class Download
{

    public $setting = [
        "filename" => '',
        "type" => '',
        "size" => '',
        "basepath" => '',
        "suffix" => '',
        "hidden" => false 
    ];

    private $DS = DIRECTORY_SEPARATOR;

    /**
     * __construct
     *
     * @param array $option 必须['filename', 'type' ,'basepath', 'hidden'=>'bool是否隐藏文件名']
     * @return void
     */
    public function __construct(array $option)
    {
        foreach ($option as $key => $val) {
            array_key_exists($key, $this->setting) and $this->setting[$key] = $val;
        }
    }
    /**
     * exec
     *
     * @return bool
     */
    public function exec()
    {
        if ($this->setting['filename'] == ''){
            return false;
        }

        $filepath = $this->setting['basepath'] . $this->DS . $this->setting['filename'];

        if (!file_exists($filepath)) {
            return false;
        }
        // 文件大小
        $size = $this->setting['size'] ?: filesize($filepath);

        ob_start();

        $this->setHeader($filepath, $size);

        try {
            $file = fopen($filepath, 'r');
            // 输出文件数据
            echo fread($file, filesize($filepath));
        } catch (Exception $e) {
            // 关闭文件流
            fclose($file);
            // 清除缓存数据
            ob_clean();
            return false;
        }

        fclose($file);
        // 向客户输出文件数据
        ob_flush();
        return true;
    }

    /**
     * setHeader
     *
     * @param string $filepath
     * @return void
     */
    private function setHeader($filepath, $size)
    {
        $filename = $this->setting['filename'];
        // 文件名进行MD5处理
        !$this->setting['hidden'] OR $filename = md5($filename) . substr($filename,strrpos($filename, '.'));
        // 输入文件标签
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: " . $size);
        Header("Content-Disposition: attachment; filename=" . $filename);
    }

}
