<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2016/7/24
 * Time: 15:52
 */
// $_SERVER['DOCUMENT_ROOT'];
// $_SERVER['DOCUMENT_ROOT'];
require_once("include/uploadImg.php");
if(isset($_POST['tijiao'])) {
    $upload_dir = __dir__;
    $_relative_destination = "images/";
    $path = $upload_dir . "/" . $_relative_destination;
    if (!is_dir($path)) {
        mkdir($path);
    }
    $result = "";
    try {
        $upload = new UploadImg($path, $_relative_destination);
        $upload->move();
        $result = $upload->getMessages();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>ImageUpload</title>
</head>
<body>
<?php
    if(!empty($result)){
        $strResult = "";
        $strResult .="<div>";
        foreach ($result as $key => $val){
            $strResult .="<p>$val</p>";
        }
        $strResult .="</div>";
        echo $strResult;
    }
?>
<form action="" method="post" enctype="multipart/form-data">
    <p><input type="file" name="a[]" id="a"></p>
    <p><input type="file" name="a[]" id="a"></p>
    <p><input type="submit" name="tijiao" value="提交"></p>
</form>
</body>
</html>
