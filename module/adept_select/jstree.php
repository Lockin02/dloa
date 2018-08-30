<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
//读取登陆用户的信息
$sqlStr="select  DEPT_ID  from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
}
?>
<HTML>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<STYLE>TABLE {
    FONT-SIZE: 10pt
}
TD {
    HEIGHT: 11px
}
</STYLE>

<SCRIPT language=JavaScript>
if(window.parent==this)
{
    window.location="../../index.php";  
}

function sltDept(dept)
{
    parent.user.location.href = "user.php?id="+dept[2]+"&toid=<?php echo $toid;?>&toname=<?php echo $toname?>";
}
</SCRIPT>

<META content="MSHTML 6.00.2900.5726" name=GENERATOR>
</HEAD>
<BODY <?php echo base64_decode( $SHOWCODE );?> topMargin=5>
<TABLE class=small cellSpacing=0 cellPadding=3 width="100%" border=1>
  <THEAD class=TableControl>
  <TR>
    <TD align=middle bgColor=#d6e7ef colSpan=2><B>选择部门</B></TD></TR></THEAD>
  <TBODY></TBODY></TABLE><SPAN id=menus></SPAN>
<SCRIPT language=JavaScript>
//=====================================================================================================//
var icons = new Array("<img src='../../images/work/star.png'>","<img src='../../images/work/sub.png'>","<img src='../../images/work/plus.png'>","<img src='../../images/menu/hrms.gif'>"); //节点图标
/**
* 构造树，初值为0
*/
function tree(n) {
    var id = new Array("pad","bar",icons[2],icons[0],icons[3]);
    if(n == 0) { // 初始化变量
        n = 1;
        i = 0;
        s = "";
    }
    s += "<table>";
    for(;i<tree_ar.length-1;i++) {
        var k = (n >= tree_ar[i+1][0])?1:0;
        s += "<tr style='cursor:hand' id='"+id[k]+"' value="+i+"><td>"+id[k+2]+"</td><td>"+tree_ar[i][1]+"</td></tr>"; // 构造节点，注意这里的自定义属性value。作用是简化构造节点的描述，共享参数数组信息。
        if(n > tree_ar[i+1][0]) { // 若期望层次大于当前层次，结束本层次返回上一层次。
            s += "</td></tr></table>";
            return tree_ar[i+1][0];
        }
        if(n < tree_ar[i+1][0]) { // 若期望层次小于当前层次，递归进入下一层次。
            s += "<tr style='display:none' v=1><td></td><td>";
            var m = tree(tree_ar[++i][0]);
            s += "</td></tr>";
            if(m < n) { // 当递归返回值小于当前层次期望值时，将产生连续的返回动作。
                s += "</table>";
                return m;
            }
        }
    }
    s += "</table>";
    return s;
}
</SCRIPT>

<SCRIPT event=onclick for=pad>
// 分枝节点的点击响应
v = this.parentElement.rows[this.rowIndex+1].style;
if(v.display == 'block') {
    v.display = 'none';
    this.cells[0].innerHTML = icons[2];
    sltDept(tree_ar[this.value]); // 自行修改为参数数组定义的闭合动作
}else {
    v.display = 'block';
    this.cells[0].innerHTML = icons[1];
    sltDept(tree_ar[this.value]); // 自行修改为参数数组定义的展开动作
}

/**
* 以下代码用于关闭已展开的其他分枝
* 如需自行关闭展开的分枝则从这里直接返回或删去这段代码
*/
if(! tree_ar[this.value].type) // 如该节点为首次进入，则记录所在层次信息
    genTreeInfo(this);
var n = 1*this.value+1;
for(i=n;i<tree_ar.length-1;i++) { // 关闭排列在当前节点之后的树
    if(tree_ar[i].type == "pad") {
        tree_ar[i].obj2.style.display = 'none';
        tree_ar[i].obj1.cells[0].innerHTML = icons[2];
    }
}
while(tree_ar[--n][0] > 1); // 回溯到当前树的起点
while(--n >= 0) // 关闭排列在当前树的起点之前的树
if(tree_ar[n].type == "pad") {
    tree_ar[n].obj2.style.display = 'none';
    tree_ar[n].obj1.cells[0].innerHTML = icons[2];
}

/** 记录层次信息，用以简化遍历树时的复杂的节点描述 **/
function genTreeInfo(o) {
  var el = o.parentElement;
  for(var i=0;i<el.rows.length;i++) {
    if(el.rows[i].id != "") {
      tree_ar[el.rows[i].value].type = el.rows[i].id;
    }
    if(el.rows[i].id == "pad") {
      tree_ar[el.rows[i].value].obj1 = el.rows[i];
      tree_ar[el.rows[i].value].obj2 = el.rows[i+1];
    }
  }
}
</SCRIPT>

<SCRIPT event=onclick for=bar>
// 无分枝节点的点击响应
sltDept(tree_ar[this.value]); // 自行修改为参数数组定义的点击动作
</SCRIPT>

<!--* 基本参数数组，根据具体应用自行扩展
* 数据可较简单的由服务器端提供
* 列1：节点层次
* 列2：节点标题
* 其余自行扩充
*/ -->
<script >
<?php 
    $sql = "select * from department where 1=1 order by Depart_x";
    $rs = $msql->SelectLimit( $sql );
?>
    var tree_ar= new Array();
    var i=0;
    <?php 
    if($rs){ 
        foreach($rs as $r){ 
            if($r['DEPT_NAME']=="系统管理"&&$checkDept!=1){
                continue;
            }
            ?>
            tree_ar[i]=new Array("<?php echo strlen( $r['Depart_x'] ) / 2;?>","<?php echo $r['DEPT_NAME']; ?>","<?php echo $r['Depart_x'];?>");
            i++;
            <?php
        } 
}?>
    tree_ar[i]=new Array(1,""); 
    menus.innerHTML =tree(0);
</script>
</BODY></HTML>