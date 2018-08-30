<?php
header('Content-Type:text/html;charset=gbk');
header("Cache-Control: no-cache, must-revalidate");
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
if(isset($_GET['seaKey'])){
    $seaKey=$_GET['seaKey'];
    $seaTable=$_GET['seaTable'];
    $seaSel=$_GET['seaSel'];
    $seaSel_1=strtolower($seaTable)=='user' ? ' del=0  and '.$_GET['seaSel']:$_GET['seaSel'];
    $sql = 'select '.$seaSel.' from '.$seaTable.' where '.$seaSel_1.' like "%'.$seaKey.'%" ';
    $msql->query($sql);
    echo "<div id='show'>";
    while ($msql->next_record()){
        $tmpKey=str_ireplace($seaKey,"<font color='blue'>$seaKey</font>",$msql->f("$seaSel"));
        echo "<li onMouseOver=\"this.style.background='#d7ebff'\" onMouseOut=\"this.style.background='#ffffff'\"".
        "onclick=\"javascript:triem('".$msql->f("$seaSel")."');\"  value='".$msql->f("$seaSel")."'>".
        $tmpKey
        ."</li>";
    }
    echo "</div>";
}
?>