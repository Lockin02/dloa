<html>
<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Sticky Table Headers Revisited: Creating functional and flexible sticky table headers" />
		<meta name="keywords" content="Sticky Table Headers Revisited" />
		<meta name="author" content="Codrops" />
		<script type="text/javascript" src="js/jquery/jquery-1.4.2.js"></script>
        <title>动态显示服务器运行程序的进度条</title>

<?php
include_once (WEB_TOR."model/contract/conproject/conproject.php");
include_once (WEB_TOR."model/contract/conproject/conprojectRecord.php");
//防止执行超时
set_time_limit(0);

//清空并关闭输出缓存
ob_end_clean();

//需要循环的数据
//for($i = 0; $i < 188; $i++)
//{
//	$rows[] = 'Tom_' . $i;
//}

//计算数据的长度
$total = count($rows);
//显示的进度条长度，单位 px
$width = 500;

//每条记录的操作所占的进度条单位长度
$pix = $width / $total;

//默认开始的进度条百分比
$progress = 0;
?>

<style>
body,div input {
	font-family: Tahoma;
	font-size: 9pt
}
</style>
<script language="JavaScript">
    <!--
    window.onload = function(){

    }
    function updateProgress(sMsg, iWidth)
    {
        document.getElementById("status").innerHTML = sMsg;
        document.getElementById("progress").style.width = iWidth + "px";
        document.getElementById("percent").innerHTML = parseInt(iWidth / <?php echo $width; ?> * 100) + "%";
     }
    -->
    </script>
</head>
<body>
	<div style="margin:50px auto;  padding: 8px; border: 1px solid gray; background: #EAEAEA; width: <?php echo $width+8; ?>px">
		<div style="padding: 0; background-color: white; border: 1px solid navy; width: <?php echo $width; ?>px">
			<div id="progress"
				style="padding: 0; background-color: #FFCC66; border: 0; width: 0px; text-align: center; height: 16px"></div>
		</div>
		<div id="status">&nbsp;</div>
		<div id="percent"
			style="position: relative; top: -30px; text-align: center; font-weight: bold; font-size: 8pt">0%</div>
	</div>

<?php
flush(); //将输出发送给客户端浏览器
    //获取版本
    $recordDao = new model_contract_conproject_conprojectRecord();
    $maxNum=$recordDao->getMaxVersion();
foreach($rows as $k=>$v)
{
//	for($i = 0; $i < 500000; $i++)
//	{
//
//	}

	$obj = new model_contract_conproject_conproject();
	$flag = $obj->storeHandle_d($v,$maxNum);
	?>
<script language="JavaScript">

    updateProgress("(<?php echo $k; ?>/<?php echo $total; ?>) 正在更新 :  <?php echo $v['projectCode']; ?> ....   ",
    <?php echo min($width, intval($progress)); ?>);
</script>
<?php
	flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。

	$progress += $pix;
} //end foreach
?>
<script language="JavaScript">
	//最后将进度条设置成最大值 $width，同时显示操作完成
    updateProgress("(<?php echo $total; ?>/<?php echo $total; ?>)操作完成！", <?php echo $width; ?>);
</script>
<?php
flush();
?>
<div style="text-align: center;">
     <input type="hidden" id="aaa" value="123">
     <input type="button" onclick="self.parent.tb_remove();self.parent.history.go(0);" value=" 完成更新 " />
</div>
</body>
</html>