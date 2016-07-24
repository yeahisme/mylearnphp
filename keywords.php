<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>关键词描红</title>
</head>

<body>

<?php
$str = "生活是一场变幻无穷的盛宴，我们本不该为之神伤感怀，可是，我们又是如此的奢望、如此的留恋。 ";
echo $str;
$searchstr = "";
$count = 0;
$indexStr="";
if(!empty($_POST['sub'])){
	if(empty($_POST['keyword'])) echo "搜索结果:<br>无关键词";
	$word = '<span style="color:#009ee0;">'.$_POST['keyword'].'</span>';
	$searchstr = str_replace($_POST['keyword'],$word,$str);
	$count = substr_count($str,$_POST['keyword']);
	if($count){
		$n = 0;
		$indexStr = "搜索结果:<br>关键词<strong style=\"color:#009ee0;\">".$_POST['keyword']."</strong>共出现".$count."次，出现的位置如下:<br>";
		for($i = 1;$i <= $count;$i++) {
			$n = mb_strpos($str, $_POST['keyword'], $n,"utf-8");
			$indexStr .= "位置：<span style=\"color:#f00\">".$n."</span>:";
			$indexStr .= mb_substr($str,0,$n+mb_strlen($_POST['keyword'], "utf-8"),"utf-8")."<br>";
			$i != $count && $n++;
		}
	}
}
?>
<form id="form1" name="form1" method="post">
  <label for="keyword">请输入关键词:</label>
  <input type="text" name="keyword" id="keyword" value="<?php if(!empty($_POST['keyword'])) echo $_POST['keyword'];?>">
  <input name="sub" type="submit" value="搜索">
</form>
<?php
	echo "---------------------------------------------------<br>";
	echo $searchstr."<br>";
	echo "---------------------------------------------------<br>";
	echo $indexStr;
?>
</body>
</html>