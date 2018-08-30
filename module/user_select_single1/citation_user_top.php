<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
$sql = "select * from department where 1=1 order by Depart_x";
$rs = $msql->SelectLimit( $sql );
echo "<html>\r\n<head>\r\n<style>\r\n\ttable {font-size = 10pt}\r\n\ttd {height = 11px}\r\n</style>\r\n<script language=\"JavaScript\">\r\n\r\nfunction sltDept(dept)\r\n{\r\n\tparent.user.location.href = \"citation_user_table.php?id=\"+dept[2]+\"&toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "\";\r\n}\r\n</script>\r\n</head>\r\n\r\n<body ";
echo base64_decode( $SHOWCODE );
echo " topmargin=\"5\">\r\n\r\n<span id=\"menus\"></span>\r\n</body>\r\n</html>\r\n\r\n\r\n<script language=\"JavaScript\">\r\n//=====================================================================================================//\r\nvar icons = new Array(\"<img src='../../images/work/star.png'>\",\"<img src='../../images/work/sub.png'>\",\"<img src='../../images/work/plus.png'>\",\"<img src='../../images/menu/hrms.gif'>\"); //节点图标\r\n/**\r\n* 构造树，初值为0\r\n*/\r\nfunction tree(n) {\r\n    var id = new Array(\"pad\",\"bar\",icons[2],icons[0],icons[3]);\r\n    if(n == 0) { // 初始化变量\r\n        n = 1;\r\n        i = 0;\r\n        s = \"\";\r\n    }\r\n    s += \"<table>\";\r\n    for(;i<tree_ar.length-1;i++) {\r\n        var k = (n >= tree_ar[i+1][0])?1:0;\r\n        s += \"<tr style='cursor:hand' id='\"+id[k]+\"' value=\"+i+\"><td>\"+id[k+2]+\"</td><td>\"+tree_ar[i][1]+\"</td></tr>\"; // 构造节点，注意这里的自定义属性value。作用是简化构造节点的描述，共享参数数组信息。\r\n        if(n > tree_ar[i+1][0]) { // 若期望层次大于当前层次，结束本层次返回上一层次。\r\n            s += \"</td></tr></table>\";\r\n            return tree_ar[i+1][0];\r\n        }\r\n        if(n < tree_ar[i+1][0]) { // 若期望层次小于当前层次，递归进入下一层次。\r\n            s += \"<tr style='display:none' v=1><td></td><td>\";\r\n            var m = tree(tree_ar[++i][0]);\r\n            s += \"</td></tr>\";\r\n            if(m < n) { // 当递归返回值小于当前层次期望值时，将产生连续的返回动作。\r\n                s += \"</table>\";\r\n                return m;\r\n            }\r\n        }\r\n    }\r\n    s += \"</table>\";\r\n    return s;\r\n}\r\n</script>\r\n\r\n<script for=pad event=onclick>\r\n// 分枝节点的点击响应\r\nv = this.parentElement.rows[this.rowIndex+1].style;\r\nif(v.display == 'block') {\r\n    v.display = 'none';\r\n    this.cells[0].innerHTML = icons[2];\r\n    sltDept(tree_ar[this.value]); // 自行修改为参数数组定义的闭合动作\r\n}else {\r\n    v.display = 'block';\r\n    this.cells[0].innerHTML = icons[1];\r\n    sltDept(tree_ar[this.value]); // 自行修改为参数数组定义的展开动作\r\n}\r\n\r\n/**\r\n* 以下代码用于关闭已展开的其他分枝\r\n* 如需自行关闭展开的分枝则从这里直接返回或删去这段代码\r\n*/\r\nif(! tree_ar[this.value].type) // 如该节点为首次进入，则记录所在层次信息\r\n    genTreeInfo(this);\r\nvar n = 1*this.value+1;\r\nfor(i=n;i<tree_ar.length-1;i++) { // 关闭排列在当前节点之后的树\r\n    if(tree_ar[i].type == \"pad\") {\r\n        tree_ar[i].obj2.style.display = 'none';\r\n        tree_ar[i].obj1.cells[0].innerHTML = icons[2];\r\n    }\r\n}\r\nwhile(tree_ar[--n][0] > 1); // 回溯到当前树的起点\r\nwhile(--n >= 0) // 关闭排列在当前树的起点之前的树\r\nif(tree_ar[n].type == \"pad\") {\r\n    tree_ar[n].obj2.style.display = 'none';\r\n    tree_ar[n].obj1.cells[0].innerHTML = icons[2];\r\n}\r\n\r\n/** 记录层次信息，用以简化遍历树时的复杂的节点描述 **/\r\nfunction genTreeInfo(o) {\r\n  var el = o.parentElement;\r\n  for(var i=0;i<el.rows.length;i++) {\r\n    if(el.rows[i].id != \"\") {\r\n      tree_ar[el.rows[i].value].type = el.rows[i].id;\r\n    }\r\n    if(el.rows[i].id == \"pad\") {\r\n      tree_ar[el.rows[i].value].obj1 = el.rows[i];\r\n      tree_ar[el.rows[i].value].obj2 = el.rows[i+1];\r\n    }\r\n  }\r\n}\r\n</script>\r\n\r\n<script for=bar event=onclick>\r\n// 无分枝节点的点击响应\r\nsltDept(tree_ar[this.value]); // 自行修改为参数数组定义的点击动作\r\n</script>\r\n\r\n<script>\r\n/**\r\n* 基本参数数组，根据具体应用自行扩展\r\n* 数据可较简单的由服务器端提供\r\n* 列1：节点层次\r\n* 列2：节点标题\r\n* 其余自行扩充\r\n*/\r\ntree_ar = new Array(\r\n";
if ( $rs )
{
    foreach ( $rs as $r )
    {
        echo "new Array(".strlen( $r['Depart_x'] ) / 2;
        echo ",'".$r['DEPT_NAME']."','".$r['Depart_x']."'),";
    }
}
echo "  new Array(1,\"\") // 为简化终止判断附加的空数据项\r\n);\r\n/*** 创建菜单 ***/\r\nmenus.innerHTML =tree(0);\r\n</script>";
?>
