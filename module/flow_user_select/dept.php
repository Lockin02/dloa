<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
$sql = "select * from ".$JeoaDb_tableprefix."department where 1=1 order by Depart_x";
$rs = $msql->SelectLimit( $sql );
echo "<html>\r\n<head>\r\n<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<style>\r\n\t.menulines{}\r\n\ttable {font-size = 10pt}\r\n\ttd {height = 11px}\r\n</style>\r\n\r\n<script>\r\nif(window.parent==this)\r\n{\r\n    window.location=\"../../index.php\";  \r\n}\r\nvar menu_enter=\"\";\r\n\r\nfunction borderize_on(e)\r\n{\r\n color=\"#708DDF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\" && source3!=menu_enter)\r\n    source3.style.backgroundColor=color;\r\n}\r\n\r\nfunction borderize_on1(e)\r\n{\r\n for (i=0; i<document.all.length; i++)\r\n { document.all(i).style.borderColor=\"\";\r\n   document.all(i).style.backgroundColor=\"\";\r\n   document.all(i).style.color=\"\";\r\n   document.all(i).style.fontWeight=\"\";\r\n }\r\n\r\n color=\"#003FBF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\")\r\n { source3.style.borderColor=\"black\";\r\n   source3.style.backgroundColor=color;\r\n   source3.style.color=\"white\";\r\n   source3.style.fontWeight=\"bold\";\r\n }\r\n\r\n menu_enter=source3;\r\n}\r\n\r\nfunction borderize_off(e)\r\n{\r\n source4=event.srcElement\r\n\r\n if(source4.className==\"menulines\" && source4!=menu_enter)\r\n    {source4.style.backgroundColor=\"\";\r\n     source4.style.borderColor=\"\";\r\n    }\r\n}\r\n\r\nfunction sltDept(dept)\r\n{\r\n\tparent.user.location.href = \"user.php?DEPT_ID=\"+dept[3]+\"&DEPT_NM=\"+dept[1]\r\n\t\t+\"&toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "\";\r\n}\r\n</script>\r\n</head>\r\n\r\n<body class=\"bodycolor\" onMouseover=\"borderize_on(event)\" onMouseout=\"borderize_off(event)\" onclick=\"borderize_on1(event)\" topmargin=\"5\" ";
echo base64_decode( $SHOWCODE );
echo ">\r\n<table border=\"1\" cellspacing=\"0\" width=\"95%\" class=\"small\" cellpadding=\"3\"  bordercolorlight=\"#000000\" bordercolordark=\"#FFFFFF\" align=\"center\">\r\n<thead class=\"TableControl\">\r\n  <th bgcolor=\"#d6e7ef\" align=\"center\"><b>ѡ����</b></th>\r\n</thead>\r\n</table>\r\n<span id=\"menus\"></span>\r\n</body>\r\n</html>\r\n\r\n<script language=\"JavaScript\">\r\n//=====================================================================================================//\r\nvar icons = new Array(\"<img src='/images/work/star.png'>\",\"<img src='/images/work/sub.png'>\",\"<img src='/images/work/plus.png'>\",\"<img src='/images/menu/hrms.gif'>\"); //�ڵ�ͼ��\r\n/**\r\n* ����������ֵΪ0\r\n*/\r\nfunction tree(n) {\r\n    var id = new Array(\"pad\",\"bar\",icons[2],icons[0],icons[3]);\r\n    if(n == 0) { // ��ʼ������\r\n        n = 1;\r\n        i = 0;\r\n        s = \"\";\r\n    }\r\n    s += \"<table>\";\r\n    for(;i<tree_ar.length-1;i++) {\r\n        var k = (n >= tree_ar[i+1][0])?1:0;\r\n        s += \"<tr style='cursor:hand' id='\"+id[k]+\"' value=\"+i+\"><td>\"+id[k+2]+\"</td><td>\"+tree_ar[i][1]+\"</td></tr>\"; // ����ڵ㣬ע��������Զ�������value�������Ǽ򻯹���ڵ����������������������Ϣ��\r\n        if(n > tree_ar[i+1][0]) { // ��������δ��ڵ�ǰ��Σ���������η�����һ��Ρ�\r\n            s += \"</td></tr></table>\";\r\n            return tree_ar[i+1][0];\r\n        }\r\n        if(n < tree_ar[i+1][0]) { // ���������С�ڵ�ǰ��Σ��ݹ������һ��Ρ�\r\n            s += \"<tr style='display:none' v=1><td></td><td>\";\r\n            var m = tree(tree_ar[++i][0]);\r\n            s += \"</td></tr>\";\r\n            if(m < n) { // ���ݹ鷵��ֵС�ڵ�ǰ�������ֵʱ�������������ķ��ض�����\r\n                s += \"</table>\";\r\n                return m;\r\n            }\r\n        }\r\n    }\r\n    s += \"</table>\";\r\n    return s;\r\n}\r\n</script>\r\n\r\n<script for=pad event=onclick>\r\n// ��֦�ڵ�ĵ����Ӧ\r\nv = this.parentElement.rows[this.rowIndex+1].style;\r\nif(v.display == 'block') {\r\n    v.display = 'none';\r\n    this.cells[0].innerHTML = icons[2];\r\n    sltDept(tree_ar[this.value]); // �����޸�Ϊ�������鶨��ıպ϶���\r\n}else {\r\n    v.display = 'block';\r\n    this.cells[0].innerHTML = icons[1];\r\n    sltDept(tree_ar[this.value]); // �����޸�Ϊ�������鶨���չ������\r\n}\r\n\r\n/**\r\n* ���´������ڹر���չ����������֦\r\n* �������йر�չ���ķ�֦�������ֱ�ӷ��ػ�ɾȥ��δ���\r\n*/\r\nif(! tree_ar[this.value].type) // ��ýڵ�Ϊ�״ν��룬���¼���ڲ����Ϣ\r\n    genTreeInfo(this);\r\nvar n = 1*this.value+1;\r\nfor(i=n;i<tree_ar.length-1;i++) { // �ر������ڵ�ǰ�ڵ�֮�����\r\n    if(tree_ar[i].type == \"pad\") {\r\n        tree_ar[i].obj2.style.display = 'none';\r\n        tree_ar[i].obj1.cells[0].innerHTML = icons[2];\r\n    }\r\n}\r\nwhile(tree_ar[--n][0] > 1); // ���ݵ���ǰ�������\r\nwhile(--n >= 0) // �ر������ڵ�ǰ�������֮ǰ����\r\nif(tree_ar[n].type == \"pad\") {\r\n    tree_ar[n].obj2.style.display = 'none';\r\n    tree_ar[n].obj1.cells[0].innerHTML = icons[2];\r\n}\r\n\r\n/** ��¼�����Ϣ�����Լ򻯱�����ʱ�ĸ��ӵĽڵ����� **/\r\nfunction genTreeInfo(o) {\r\n  var el = o.parentElement;\r\n  for(var i=0;i<el.rows.length;i++) {\r\n    if(el.rows[i].id != \"\") {\r\n      tree_ar[el.rows[i].value].type = el.rows[i].id;\r\n    }\r\n    if(el.rows[i].id == \"pad\") {\r\n      tree_ar[el.rows[i].value].obj1 = el.rows[i];\r\n      tree_ar[el.rows[i].value].obj2 = el.rows[i+1];\r\n    }\r\n  }\r\n}\r\n</script>\r\n\r\n<script for=bar event=onclick>\r\n// �޷�֦�ڵ�ĵ����Ӧ\r\n\tsltDept(tree_ar[this.value]);  // �����޸�Ϊ�������鶨��ĵ������\r\n</script>\r\n\r\n<script>\r\n/**\r\n* �����������飬���ݾ���Ӧ��������չ\r\n* ���ݿɽϼ򵥵��ɷ��������ṩ\r\n* ��1���ڵ���\r\n* ��2���ڵ����\r\n* ������������\r\n*/\r\ntree_ar = new Array(\r\n";
if ( $rs )
{
    foreach ( $rs as $r )
    {
        echo "new Array(".strlen( $r[Depart_x] ) / 2.",'".$r[DEPT_NAME]."','".$r[Depart_x]."','".$r[DEPT_NO]."'),";
    }
}
echo "  new Array(1,\"\") // Ϊ����ֹ�жϸ��ӵĿ�������\r\n);\r\n/*** �����˵� ***/\r\nmenus.innerHTML =tree(0);\r\n</script>";
?>