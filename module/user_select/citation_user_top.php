<html>
<head>
<?php 
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
//��֤��ֵ
if(!isset($toid)){
    $toid="PRIV_ID";
}
if(!isset($toname)){
    $toname="PRIV_NAME";
}
if(!isset($formname)){
    $formname="form1";
}
if(!isset($todept)){
    $todept='';
}
$sqlStr="select  DEPT_ID  from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
}
?>
<style>
    table {font-size = 10pt}
    td {height = 11px}
</style>
<script language="JavaScript">

function sltDept(dept)
{
    parent.user.location.href = "citation_user_table.php?id="+dept[2]+"&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>";
}
</script>
</head>

<body  topmargin="5">
<span id="menus"></span>
</body>
</html>


<script language="JavaScript">
//=====================================================================================================//
var icons = new Array("<img src='../../images/work/star.png'>","<img src='../../images/work/sub.png'>","<img src='../../images/work/plus.png'>","<img src='../../images/menu/hrms.gif'>"); //�ڵ�ͼ��
/**
* ����������ֵΪ0
*/
function tree(n) {
    var id = new Array("pad","bar",icons[2],icons[0],icons[3]);
    if(n == 0) { // ��ʼ������
        n = 1;
        i = 0;
        s = "";
    }
    s += "<table>";
    for(;i<tree_ar.length-1;i++) {
        var k = (n >= tree_ar[i+1][0])?1:0;
        s += "<tr style='cursor:hand' id='"+id[k]+"' value="+i+"><td>"+id[k+2]+"</td><td>"+tree_ar[i][1]+"</td></tr>"; // ����ڵ㣬ע��������Զ�������value�������Ǽ򻯹���ڵ���������������������Ϣ��
        if(n > tree_ar[i+1][0]) { // ��������δ��ڵ�ǰ��Σ���������η�����һ��Ρ�
            s += "</td></tr></table>";
            return tree_ar[i+1][0];
        }
        if(n < tree_ar[i+1][0]) { // ���������С�ڵ�ǰ��Σ��ݹ������һ��Ρ�
            s += "<tr style='display:none' v=1><td></td><td>";
            var m = tree(tree_ar[++i][0]);
            s += "</td></tr>";
            if(m < n) { // ���ݹ鷵��ֵС�ڵ�ǰ�������ֵʱ�������������ķ��ض�����
                s += "</table>";
                return m;
            }
        }
    }
    s += "</table>";
    return s;
}
</script>

<script for=pad event=onclick>
// ��֦�ڵ�ĵ����Ӧ
v = this.parentElement.rows[this.rowIndex+1].style;
if(v.display == 'block') {
    v.display = 'none';
    this.cells[0].innerHTML = icons[2];
    sltDept(tree_ar[this.value]); // �����޸�Ϊ�������鶨��ıպ϶���
}else {
    v.display = 'block';
    this.cells[0].innerHTML = icons[1];
    sltDept(tree_ar[this.value]); // �����޸�Ϊ�������鶨���չ������
}

/**
* ���´������ڹر���չ����������֦
* �������йر�չ���ķ�֦�������ֱ�ӷ��ػ�ɾȥ��δ���
*/
if(! tree_ar[this.value].type) // ��ýڵ�Ϊ�״ν��룬���¼���ڲ����Ϣ
    genTreeInfo(this);
var n = 1*this.value+1;
for(i=n;i<tree_ar.length-1;i++) { // �ر������ڵ�ǰ�ڵ�֮�����
    if(tree_ar[i].type == "pad") {
        tree_ar[i].obj2.style.display = 'none';
        tree_ar[i].obj1.cells[0].innerHTML = icons[2];
    }
}
while(tree_ar[--n][0] > 1); // ���ݵ���ǰ�������
while(--n >= 0) // �ر������ڵ�ǰ�������֮ǰ����
if(tree_ar[n].type == "pad") {
    tree_ar[n].obj2.style.display = 'none';
    tree_ar[n].obj1.cells[0].innerHTML = icons[2];
}

/** ��¼�����Ϣ�����Լ򻯱�����ʱ�ĸ��ӵĽڵ����� **/
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
</script>

<script for=bar event=onclick>
// �޷�֦�ڵ�ĵ����Ӧ
sltDept(tree_ar[this.value]); // �����޸�Ϊ�������鶨��ĵ������
</script>

<script>
/**
* �����������飬���ݾ���Ӧ��������չ
* ���ݿɽϼ򵥵��ɷ��������ṩ
* ��1���ڵ���
* ��2���ڵ����
* ������������
*/
<?php
if($todept&&$todept!=''){
    $sql = "select * from department where dept_id in (".trim($todept,',').") and delflag='0' order by Depart_x";
}else{
    $sql = "select * from department where 1=1 and delflag='0' order by Depart_x";
}
    $rs = $msql->SelectLimit( $sql );
?>
    var tree_ar= new Array();
    var i=0;
    <?php 
    if($rs){ 
        foreach($rs as $r){ 
            if($r['DEPT_NAME']=="���Ͷ���"){
                continue;
            }
            if(($r['DEPT_NAME']=="ϵͳ����")&&$checkDept!=1){
                continue;
            }
            if($todept&&$todept!=''){
                $tlen=1;
            }else{
                $tlen=strlen( $r['Depart_x'] ) / 2;
            }
            ?>
            tree_ar[i]=new Array("<?php echo $tlen;?>","<?php echo $r['DEPT_NAME']; ?>","<?php echo $r['Depart_x'];?>");
            i++;
            <?php
        } 
}?>
    tree_ar[i]=new Array(1,""); 
    menus.innerHTML =tree(0);
</script>