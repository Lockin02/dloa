<html>
<head>
		<meta charset="GBK" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Sticky Table Headers Revisited: Creating functional and flexible sticky table headers" />
		<meta name="keywords" content="Sticky Table Headers Revisited" />
		<meta name="author" content="Codrops" />
		<script type="text/javascript" src="js/jquery/jquery-1.4.2.js"></script>
        <title>��̬��ʾ���������г���Ľ�����</title>

<?php
include_once (WEB_TOR."model/contract/conproject/conproject.php");
include_once (WEB_TOR."model/contract/conproject/conprojectRecord.php");
//��ִֹ�г�ʱ
set_time_limit(0);

//��ղ��ر��������
ob_end_clean();

//��Ҫѭ��������
//for($i = 0; $i < 188; $i++)
//{
//	$rows[] = 'Tom_' . $i;
//}

//�������ݵĳ���
$total = count($rows);
//��ʾ�Ľ��������ȣ���λ px
$width = 500;

//ÿ����¼�Ĳ�����ռ�Ľ�������λ����
$pix = $width / $total;

//Ĭ�Ͽ�ʼ�Ľ������ٷֱ�
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
flush(); //��������͸��ͻ��������
    //��ȡ�汾
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

    updateProgress("(<?php echo $k; ?>/<?php echo $total; ?>) ���ڸ��� :  <?php echo $v['projectCode']; ?> ....   ",
    <?php echo min($width, intval($progress)); ?>);
</script>
<?php
	flush(); //��������͸��ͻ����������ʹ���������ִ�з������������ JavaScript ����

	$progress += $pix;
} //end foreach
?>
<script language="JavaScript">
	//��󽫽��������ó����ֵ $width��ͬʱ��ʾ�������
    updateProgress("(<?php echo $total; ?>/<?php echo $total; ?>)������ɣ�", <?php echo $width; ?>);
</script>
<?php
flush();
?>
<div style="text-align: center;">
     <input type="hidden" id="aaa" value="123">
     <input type="button" onclick="self.parent.tb_remove();self.parent.history.go(0);" value=" ��ɸ��� " />
</div>
</body>
</html>