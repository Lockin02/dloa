<?PHP
//print_r($_REQUEST);
//echo $flowType;
//echo $formName;
//echo $this->billId;
    $fcarr=array();
    $obj_info=array();//ҵ������
    //ҵ����Ϣ�жϵ�
    $objkeyarr=array(
        'key_id'=>$this->billId
    );
    //print_r($objkeyarr);
    $sql="SELECT c.* , t.objsql FROM flow_form_condition  c
    left join flow_form_type t on ( t.form_id=c.formid )
    where t.form_name ='".$formName."' 
    order by c.conid ";
    $msql->query($sql);
    while($msql->next_record())
    {   
        $obj_sql = $msql->f('objsql');
        $fcarr[$msql->f('conid')][$msql->f('id')]['id']=$msql->f('id');
        $fcarr[$msql->f('conid')][$msql->f('id')]['conid']=$msql->f('conid');
        $fcarr[$msql->f('conid')][$msql->f('id')]['conpd']=$msql->f('conpd');
        $fcarr[$msql->f('conid')][$msql->f('id')]['conpdn']=$msql->f('conpdn');
        $fcarr[$msql->f('conid')][$msql->f('id')]['objcode']=$msql->f('objcode');
        $fcarr[$msql->f('conid')][$msql->f('id')]['objname']=$msql->f('objname');
        $fcarr[$msql->f('conid')][$msql->f('id')]['compare']=$msql->f('compare');
    }
    //���Ӷ�ȡ������ҵ���������
    if(!empty($obj_sql)&&$fcarr){
        foreach($objkeyarr as $key=>$val){
            $obj_sql=str_replace('$'.$key, $val, $obj_sql);
        }
        //echo $obj_sql;
        $obj_info=$fsql->get_one($obj_sql);
        $ckpoin=  findconid($fcarr, $obj_info);
        $ckpoin = empty($ckpoin)?'-':trim($ckpoin,',');
        $ckpoin=explode(',',$ckpoin);
//        print_r($ckpoin);
//        print_r($obj_info);
    }
//    print_r($fcarr);
    /**
     *ѭ����ȡ����id
     * @param type $fcarr
     * @param type $ckobj 
     */
    function findconid($fcarr,$ckobj,$fi=0){
        $res='';
        if(!empty($fcarr[$fi])){
            foreach($fcarr[$fi] as $key=>$val){
                $compary=null;
                if($val['compare']=='in'){
                    $compary=true;
                }elseif($val['compare']=='nin'){
                    $compary=false;
                }
                $ckp=  explode(',', $val['conpd']);
                if($val['conid']=='0'||in_array($ckobj[$val['objcode']], $ckp)==$compary){
                    if($fcarr[$key]){
                        $res.=  findconid($fcarr, $ckobj,$key);
                    }else{
                        $res.=$val['id'].',';
                    }
                }
            }
        }
        return $res;
    }
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=GB2312">
    <title>ѡ����������</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->compDir;?>inc/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->compDir;?>inc/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->compDir;?>css/yxstyle.css" />
    <script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/jquery-1.4.2.js"></script>
    <script language="JavaScript">
        $(function() {
            sltFlow();
        });
        function sltFlow()
        {
            var flow=$("#FLOW_ID").val();
            var fold_flow='&fold_flow=';
            $(".fold_flow").each(function(i){
                fold_flow+=this.value+',';
            });
            var url="<?php echo $this->baseDir; ?>process_view.php?flowid="
                +flow+"&proId=<?php echo $this->proId; ?>"
                +"&proSid=<?php echo $this->proSid; ?>&billDept=<?php echo $this->billDept; ?>"
                +"&billUser=<?php echo $this->billUser; ?>&billArea=<?php echo $this->billArea; ?>"
                +"&billId=<?php echo $this->billId; ?>&examCode=<?php echo $this->examCode; ?>"
                +"&cktype=<?php echo $this->cktype; ?>&flowMoney=<?php echo $flowMoney; ?>"
                +"&formName=<?php echo $formName; ?>&billCompany=<?php echo $this->billCompany; ?>"+fold_flow
                +"&eUserId=<?php echo $this->eUserId; ?>";
            document.getElementById("ife_sum").setAttribute("src",url);
        }
        function displaySub(){
			//if(confirm('�Ƿ�ȷ����������Ϣ��ȷ��')) {
        		$("#sub").attr("disabled", "none");
				var _Frame;
				if(!document.all){  //����еõ�IFRAME�Ķ���   
					_Frame=document.getElementById("ife_sum").contentWindow;   
				}else{   
					_Frame=document.frames["ife_sum"];
				}
				var prcsDiv='';
				var ckPass=true;
				var m1='';
				var m2='';
				$(".prcs_diy_h",_Frame.document).each(function(i){
					var hVal=this.value;
					prcsDiv+='<input type="hidden" name="prcs['+this.id+']" value="'+hVal+'" />';
					if(hVal==''){
						ckPass=false;
						m1='��˫��ѡ�������쵼��'
					}
				});
				$(".prcs_pop",_Frame.document).each(function(i){
					var hVal=this.value;
					if(hVal==''){
						ckPass=false;
						m2='���������̺������쵼Ϊ�գ���֪ͨOAרԱ��';
					}
				});
				if(ckPass){
	//                alert(prcsDiv);
					$("#prcs_div").html(prcsDiv);
					$("#sub").attr("disabled", "none");
					return true;
				}else{
					alert(m1+m2);
					$("#sub").attr("disabled", "");
					return false;
				}
				return true;
		 //}else{ return false; }
        }
    </script>
</head>
<body>
<form id="form1" name="form1" action="?actTo=ewfBuild" method="post" onSubmit="return displaySub();">
    <div id="prcs_div"></div>
<input type="hidden" name="examCode" id="examCode" value="<?php echo $this->examCode;?>">
<input type="hidden" name="billId" id="billId" value="<?php echo $this->billId;?>">
<input type="hidden" name="passSqlCode" value="<?php echo addslashes($this->passSqlCode);?>">
<input type="hidden" name="disPassSqlCode" value="<?php echo addslashes($this->disPassSqlCode);?>">
<input type="hidden" name="proId" id="proId" value="<?php echo $this->proId;?>">
<input type="hidden" name="proSid" id="proSid" value="<?php echo $this->proSid;?>">
<input type="hidden" name="billDept" id="billDept" value="<?php echo $this->billDept;?>">
<input type="hidden" name="billArea" id="billArea" value="<?php echo $this->billArea;?>">
<input type="hidden" name="billUser" id="billUser" value="<?php echo $this->billUser;?>">
<input type="hidden" name="cktype" id="cktype" value="<?php echo $this->cktype;?>">
<input type="hidden" name="formName" id="formName" value="<?php echo $formName;?>">
<input type="hidden" name="flowMoney" id="flowMoney" value="<?php echo $flowMoney;?>">
<input type="hidden" name="billCompany" id="billCompany" value="<?php echo $this->billCompany;?>">
<input type="hidden" name="eUserId" id="eUserId" value="<?php echo $this->eUserId;?>">
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?php echo $this->compDir;?>images/dian1.gif" width="100%"></td>
 </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
        <img src="<?php echo $this->compDir;?>images/sys_config.gif" WIDTH="22" HEIGHT="20">
        <b><font color="#000000">��һ��:ѡ����������</font></b><br>
    </td>
  </tr>
</table>
<?php
    $flow_arr=array();
    $flow_i=0;
    $sqlStr="";
    if($flowMoney!=0){
        $sqlStr.=" and  t.MinMoney<=".$flowMoney." and ( t.MaxMoney>=".$flowMoney." or t.MaxMoney=0  )";
    }
    if($flowType!=""){
        $sqlStr.=" and t.COST_FLOW_TYPE='$flowType' ";
    }
    if($formName!=""){
        $sqlStr.=" and ft.FORM_NAME='$formName' ";
    }
    if(isset($flowDept)&&$flowDept!=""){
        $sqlStr.=" and ( find_in_set('$flowDept',t.FLOW_DEPTS)>0 or t.FLOW_DEPTS='ALL_DEPT' )";
    }
    if(!empty($ckpoin)){
        $ck_top = array_shift($ckpoin);
        $sqlStr=" and find_in_set( '".$ck_top."' , t.conid ) ";
    }
    $sql="select t.FLOW_ID,t.FLOW_NAME from flow_type t,flow_form_type ft where ft.FORM_ID=t.FORM_ID and t.isdel=0 $sqlStr ";
    $msql->query($sql);
    $i=0;
    $flowid = isset($flowid)?$flowid:"";
    $disabled = "disabled";
    
    while($msql->next_record())
    {
        $flow_arr[$flow_i][$msql->f("FLOW_ID")]=$msql->f("FLOW_NAME");
    }
    
    if(!empty($ckpoin)){
        foreach($ckpoin as $val){
            $flow_i++;
            $sql="select t.FLOW_ID,t.FLOW_NAME from flow_type t,flow_form_type ft where ft.FORM_ID=t.FORM_ID and find_in_set( '".$val."' , t.conid ) and t.isdel=0 ";
            $msql->query($sql);
            while($msql->next_record())
            {
                $flow_arr[$flow_i][$msql->f("FLOW_ID")]=$msql->f("FLOW_NAME");
            }
        }
    }
?>
<div align="center">
    <table width="80%">
        <tr>
            <td width="50%">
<?php
//        print_r($flow_arr);
        if($flow_arr){
            foreach($flow_arr as $key=>$val){
                
                if($key=='0'){
                    echo '<div>';
                    $vi=0;
                    echo '<select name="FLOW_ID" id="FLOW_ID"  title="��������" onchange="sltFlow();" size="5" >';
                    foreach($val as $vkey=>$vval){
                        echo '<option value="'.$vkey.'" '.($vi=='0'?'selected':'').'>'.$vval.'</option>';
                        $vi++;
                    }
                    echo '</select>';
                    echo '</div>';
                }elseif(!empty($val)){
                    echo '<div> ������'.$key.'�� <img border="0" src="'.$this->compDir.'images/arrow_down.gif" width="11" height="13"></div>';
                    echo '<div>';
                    $vi=0;
                    echo '<select name="fold_flow['.$key.']" id="fold_flow" class="fold_flow" title="��������" onchange="sltFlow();" size="5" >';
                    foreach($val as $vkey=>$vval){
                        echo '<option value="'.$vkey.'" '.($vi=='0'?'selected':'').'>'.$vval.'</option>';
                        $vi++;
                    }
                    echo '</select>';
                    echo '</div>';
                }
            }
        }
?>
            </td>
            
            <td  style="text-align: center;" align="center">
                <iframe id="ife_sum" name="ife_sum" scrolling="auto" 
            src=""  
            title="��������" height="260" width="500" frameborder="0">
    ��֧�ֿ��,����������.</iframe>
            </td>
        </tr>
    </table>
</div>
<table width="95%" border="0" cellspacing="0" cellpadding="0" height="3">
 <tr>
   <td background="<?php echo $this->compDir;?>images/dian1.gif" width="100%"></td>
 </tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
        <img src="<?php echo $this->compDir;?>images/sys_config.gif" WIDTH="22" HEIGHT="20">
        <b><font color="#000000">�ڶ���:�ύ</font></b><br>
        </td>
  </tr>
  <tr>
    <td align="center">
        <br />�����ʼ�֪ͨ��1�������ߣ�
        <input type="radio" name="isSendNotify" value="y" checked>��
        <input type="radio" name="isSendNotify" value="n" >�� &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=1817432422&amp;site=qq&amp;menu=yes">
	                    	<img src="<?php echo $this->compDir;?>images/head/qq_contact.png" alt="������ѯOA����" title="������ѯOA����" width="18" border="0" height="18">
	                    	<span>��������ѯ</span>
                    </a><br /><hr width="30%" /><br /><br />
    </td>
  </tr>
</table>
<div align="center">
<input type="submit" name="sub" id="sub" value="�ύ" align="center"   class="BigButton" style="width:80px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" name="back" id="back" value="����" align="center"   class="BigButton" style="width:80px" onClick="history.go(-<?php echo  isset($_REQUEST["go"])?$_REQUEST["go"]:"1";?>)">
</div>
</table>
</form>
</body>
</html>