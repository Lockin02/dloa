<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
if(isset($todept)&&trim($todept)){
    $sql = "select * from department where dept_id in ($todept) and delflag='0' order by pdeptid ,PARENT_ID  ";
}else{
    $sql = "select * from department where 1=1 and delflag='0' order by pdeptid ,PARENT_ID ";
}
$rs = $msql->SelectLimit( $sql );
//读取登陆用户的信息
$sqlStr="select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
    $checkPriv=$msql->f("USER_PRIV");
    $checkArea=$msql->f("area");
}
?>
<html>
<head>
    <style>table {font-size = 10pt}td {height = 11px}</style>
    <script language="JavaScript">
    function sltDept(dept)
    {
        parent.user.location.href = "citation_user_table.php?id="+dept+"&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>";
    }
    </script>
</head>
<body <?php //echo base64_decode( $SHOWCODE );?>topmargin="5">
    <span id="menus">
    	<table>
<?php 
if(!empty($rs)){
	foreach ($rs as $val){
		echo '<tr><td onclick = "sltDept(\''.$val['Depart_x'].'\')" >'.$val['DEPT_NAME'].'</td></tr>';
	}
}
?>
    	</table>
    </span>
</body>
</html>