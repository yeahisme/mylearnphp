<?php

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2016/7/20
 * Time: 15:49
 */
class UploadImg
{
    protected $_uploaded = array();
    protected $_destination;
    protected $_max = 151200;
    protected $_message = array();
    protected $_permitted = array(
        'image/gif',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png'
    );
    protected $_name;
    protected $_relative_destination;
    protected $_success = false;
    protected $_renamed = false;

    public function __construct($path,$_relative_destination)
    {
        if (!is_dir($path) || !is_writable($path)) {
            throw new Exception("path must be a valid, writable directory.");
        }
        $this->_destination = $path;
        $this->_uploaded = $_FILES;
        $this->_relative_destination = $_relative_destination;
    }
    //获取文件的最大值
    public function getMaxSize() {
        return number_format($this->_max/1024, 1) . 'kB';
    }
    //设置文件的最大值
    public function setMaxSize($num) {
        if (!is_numeric($num)) {
            throw new Exception("Maximum size must be a number.");
        }
        $this->_max = (int) $num;
    }


    //文件移动
    public function move($overwrite = false) {
        $field = current($this->_uploaded);
        if (is_array($field['name'])) {
            foreach ($field['name'] as $number => $filename) {
                // process multiple upload
                $this->_renamed = false;
                $this->processFile($filename, $field['error'][$number], $field['size'][$number], $field['type'][$number], $field['tmp_name'][$number], $overwrite);
            }
        } else {
            $this->processFile($field['name'], $field['error'], $field['size'], $field['type'], $field['tmp_name'], $overwrite);
        }
    }

    //获取文件上传情况的信息
    public function getMessages() {
        return $this->_messages;
    }

    //获取文件上传情况的信息
    protected function checkError($filename, $error) {
        switch ($error) {
            case 0:
                return true;
            case 1:
            case 2:
                $this->_messages[] = "$filename exceeds maximum size: " . $this->getMaxSize();
                return true;
            case 3:
                $this->_messages[] = "Error uploading $filename. Please try again.";
                return false;
            case 4:
                $this->_messages[] = 'No file selected.';
                return false;
            default:
                $this->_messages[] = "System error uploading $filename. Contact webmaster.";
                return false;
        }
    }

    //检查文件的大小
    protected function checkSize($filename, $size) {
        if ($size == 0) {
            return false;
        } elseif ($size > $this->_max) {
            $this->_messages[] = "$filename exceeds maximum size: " . $this->getMaxSize();
            return false;
        } else {
            return true;
        }
    }

    //检查文件的类型
    protected function checkType($filename, $type) {
        if (empty($type)) {
            return false;
        } elseif (!in_array($type, $this->_permitted)) {
            $this->_messages[] = "$filename is not a permitted type of file.";
            return false;
        } else {
            return true;
        }
    }

    //添加文件格式
    public function addPermittedTypes($types) {
        $types = (array) $types;
        $this->isValidMime($types);
        $this->_permitted = array_merge($this->_permitted, $types);
    }

    //检查添加格式是否被允许
    protected function isValidMime($types) {
        $alsoValid = array();
        $valid = array_merge($this->_permitted, $alsoValid);
        foreach ($types as $type) {
            if (!in_array($type, $valid)) {
                throw new Exception("$type is not a permitted MIME type");
            }
        }
    }

    //检查文件名是否重复
    protected function checkName($name, $overwrite) {
        $nospaces = str_replace(' ', '_', $name);
        if ($nospaces != $name) {
            $this->_renamed = true;
        }
        if (!$overwrite) {
            $existing = scandir($this->_destination);
            if(mb_detect_encoding($existing[0])){
                $nospaces = iconv("utf-8","gb2312",$nospaces);
            }
            if (in_array($nospaces, $existing)) {
                $dot = strrpos($nospaces, '.');
                if ($dot) {
                    $base = substr($nospaces, 0, $dot);
                    $extension = substr($nospaces, $dot);
                } else {
                    $base = $nospaces;
                    $extension = '';
                }
                $i = 1;
                do {
                    $nospaces = $base . '_' . $i++ . $extension;
                } while (in_array($nospaces, $existing));
                $this->_renamed = true;
            }
        }
        if(mb_detect_encoding($nospaces)!="UTF-8") {
            $nospaces = iconv("gb2312", "utf-8", $nospaces);
        }
        return $nospaces;
    }

    //文件移动过程
    protected function processFile($filename, $error, $size, $type, $tmp_name, $overwrite) {
        $OK = $this->checkError($filename, $error);
        if ($OK) {
            $sizeOK = $this->checkSize($filename, $size);
            $typeOK = $this->checkType($filename, $type);
            if ($sizeOK && $typeOK) {
                $this->name = $this->checkName($filename, $overwrite);
                $success = move_uploaded_file($tmp_name, iconv("utf-8","gb2312",$this->_destination).$this->name);
                if ($success) {
                    $this->_success=true;
                    $message = "$filename uploaded successfully";
                    if ($this->_renamed) {
                        $message .= " and renamed $this->name";
                    }
                    $this->_messages[] = $message;
                    //将相对路径储存至数据库
                    $this->store_to_mysql();
                } else {
                    $this->_messages[] = "Could not upload $filename";
                }
            }
        }
    }

    protected function store_to_mysql(){
    }







}
