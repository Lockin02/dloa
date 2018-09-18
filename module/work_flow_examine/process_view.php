<?php
include("../../includes/db.inc.php");
include("../../includes/msql.php");
include("../../includes/fsql.php");
include("../../includes/selltype.php");
$NetDeptI=array(309,312,315,318,321,324,326,330,294,295,296,297,298,299,300,301,302);
$NetODeptI=array(358,361,362,296,297,300,301,302);
$SaleDeptI=array(37,308,311,314,317,320,323,327,329);
$RDeptI=array(273,274,275,276,277,278,279,280);
$GroupDeptI=array();
$SMDeptI=array(295,296,297,298,299,300,301,302);
$SaleDeptOtherI=array(172);
$JKDeptI=array(382,383,384);
$deptGM=array(169,197,227,198);
$XSDeptI=array(364,365,366,367,368,369,370,371,372,373,374,375,376,377,378,379);
$DxDeptI=array(271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,296,297,300,301,302,303,304,305,306,331,358,361,362,381,385,386,387);
$YyDept=array(400,406,407,408);
$ZcDept=array(399,403,404,405,409,410,411,412,413,414,415,416,417);

$userGM=array("ljh","hua.yin","wei.ma","yanqing.wu","feng.guo");
$directorI=array('patrick.tsao','garret.chen','richard.ye','eric.ye','dafa.yu','danian.zhu');
$deptMgId='chen.chen';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>��������-����</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
    <link rel="stylesheet" type="text/css" href="../../css/yxstyle.css" />
    <script type="text/javascript" src="../../js/jquery/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="../../js/common/businesspage.js"> </script>
    <script type="text/javascript" src="../../js/jquery/woo.js"></script>
    <script type="text/javascript" src="../../js/jquery/component.js"></script>
    <script type="text/javascript" src="../../js/jquery/combo/business/yxselect-user.js"></script>
    <script>
        $(function() {
            $(".prcs_div").each(function(i){
                $("#p_"+this.name+"").yxselect_user({
                    hiddenId : this.name
                    ,oUrl:'../../index1.php'
                    ,mode : 'check'
                    ,formCode : 'expenseMember'
                });
            });
            
        });
        function changePrcs(){
            var prcsDiv='';
            var ckPass=true;
            $(".prcs_div").each(function(i){
                var hVal=$("#h_"+this.id+"").val(); 
                prcsDiv+='<input type="hidden" name="prcs['+this.id+']" value="'+hVal+'" />';
                if(hVal==''){
                    ckPass=false;
                }
            });
            if(ckPass){
                $("#prcs_div",parent.document).html(prcsDiv);
                $("#sub",parent.document).attr("disabled", "");
            }
            
        }
    </script>
    <style type="text/css">
    <!--
    .prcs_div{ color:#666666  }
    .title {  font-family: "����"; font-size: 18pt; font-weight: bold}
    -->
    </style>
</head>
<body class="bodycolor">
<table border="0" cellspacing="1" width="450" class="small" bgcolor="#000000" cellpadding="3" align="center">
    <tr class="<?php echo $TableHeader;?>">
        <td nowrap align="center"><b>�����</b></td>
        <td nowrap align='center'><b>��������</b></td>  
        <td nowrap align='center'><b>��������</b></td>   
        <td nowrap align='center'><b>������</b></td>    
    </tr>
    <tr class=TableLine1>
        <td nowrap align='center' colspan='6'><b>��ʼ</b></td>
    </tr>
<?php
$flow_step_arr=array();
$obj_info=array();//ҵ������
$obj_exa_info=array();//��������
if($flowMoney==='0'){
	$flowMoney='0.00';
}
$objkeyarr=array(
	'key_id'=>$billId
	,'ck_dept'=>$billDept
	,'billArea_Id'=>$billArea
);
//echo $billId .''.$examCode;
//������
$ckMoney='0,10000,200000';
//Ҫ�ߵĹ̶�����
$sql="select p.PRCS_NAME, p.PRCS_USER, p.PRCS_DEPT, p.PRCS_PRIV, p.PRCS_SPEC, p.PRCS_PROP , p.btcom , ft.FORM_NAME 
, ft.objsql , ft.objexasql , p.required,p.isCompany , p.id , '' as fold_type , ifnull(p.notSkip,0) as 'notSkip'
from flow_process p 
left join flow_type t on (p.flow_id=t.flow_id)
left join flow_form_type ft on ( ft.FORM_ID=t.FORM_ID )
where p. FLOW_ID='".$flowid."' order by p. PRCS_ID ";
//
if(!empty($_GET['fold_flow'])){
    $fold_flow= explode(',',trim( $_GET['fold_flow'] ,',') );
//    print_r($_GET['fold_flow']);
    $sql = " (".$sql.") ";
    $fold_i = 0;
    foreach($fold_flow as $val){
        if($val){
            $fold_i++;
            $sql.="union ( select p.PRCS_NAME, p.PRCS_USER, p.PRCS_DEPT, p.PRCS_PRIV, p.PRCS_SPEC, p.PRCS_PROP , p.btcom , ft.FORM_NAME 
                , ft.objsql , ft.objexasql , p.required,p.isCompany , p.id , '����".$fold_i."' as fold_type, ifnull(p.notSkip,0) as 'notSkip'
                from flow_process p 
                left join flow_type t on (p.flow_id=t.flow_id)
                left join flow_form_type ft on ( ft.FORM_ID=t.FORM_ID )
                where p. FLOW_ID='".$val."' order by p. PRCS_ID  limit 100 )";
        }
    }
}
//ECHO $sql;
$msql->query($sql);
$x=0;
$stepTotals=$msql->num_rows();
$stepNum=0;
$hasExtStep2 = 0;
$extStep2Prcsname = '';
$extStep2Prcsuser = '';
$extStep2PrcsuserId = '';
if($stepTotals==0)
    $isnull="true";
else
    $isnull="false";

while ( $msql->next_record( ) )
{
    $stepNum++;
    $ckthis=true;
    $PRCS_NAME = $msql->f( "PRCS_NAME" );
    $PRCS_USER = $msql->f( "PRCS_USER" );
    $PRCS_DEPT = $msql->f( "PRCS_DEPT" );
    $PRCS_PRIV = $msql->f( "PRCS_PRIV" );
    $PRCS_SPEC = $msql->f( "PRCS_SPEC" );
    $prcs_prop = $msql->f("PRCS_PROP");
    $prcs_form = $msql->f("FORM_NAME");//����
    $required = $msql->f("required");//������������
    $isCompany = $msql->f("isCompany");
    $notSkip = $msql->f("notSkip");
    $company='';
    $obj_sql = $msql->f("objsql");//���Ӷ�ȡ������ҵ���������
    
    $prcs_id = $msql->f( "id" );
    $flod_type = $msql->f( "fold_type" );
    
    $flowMoneyTmp=0;
    
    if(!empty($obj_sql)){
    	foreach($objkeyarr as $key=>$val){
	       $obj_sql=str_replace('$'.$key, $val, $obj_sql);
	    }

    	$obj_info=$fsql->get_one($obj_sql);
    	//print_r($obj_info);
    }
    
    $obj_data_sql = $msql->f("objexasql");//���Ӷ�ȡҵ����������
    if(!empty($obj_data_sql)&&empty($obj_exa_info)){
    		foreach($obj_info as $key=>$val){//�������
		       $obj_data_sql=str_replace('$'.$key, $val, $obj_data_sql);
		    }
    		$obj_data_sql_arr=explode(';',$obj_data_sql);
    		if(!empty($obj_data_sql_arr)){
    			foreach($obj_data_sql_arr as $val){
    				if(!empty($val)){
	    				$obj_info_tmp=$fsql->get_one($val);
						//$obj_info_tmp=array_filter($obj_info_tmp);
	    				if(is_array($obj_info_tmp)&&!empty($obj_info_tmp)){
	    					$obj_exa_info=array_merge_recursive($obj_exa_info,$obj_info_tmp);
	    				}
	    			}
    			}
    		}
    		//print_r($obj_exa_info);die();
    }
    
    //���˹�˾����
    $prcs_com=$msql->f('btcom');
    if(!empty($prcs_com)&&$prcs_com!=$_SESSION['USER_COM']){
        continue;
    }
    $x++;
    $PRCS_USER_NAME="";
	$PRCS_USER_ID="";
    $wherestr="";
    if ( $PRCS_USER != NULL )
    {
        $wherestr .= "or USER_ID in(".towhere($PRCS_USER).") ";
    }
    if($PRCS_PRIV != null && $PRCS_DEPT != null)
    {
        $wherestr .= "or USER_PRIV in(".towhere($PRCS_PRIV).") or DEPT_ID in (".towhere($PRCS_DEPT).") ";
    }else if($PRCS_PRIV != null)
    {
        $wherestr .= "or USER_PRIV in(".towhere($PRCS_PRIV).") ";
    }else if($PRCS_DEPT != null)
    {
        $wherestr .= "or DEPT_ID in (".towhere($PRCS_DEPT).") ";
    }
    if($PRCS_SPEC != NULL )
    {
        $PRCS_SPEC_ARR = explode( ",", rtrim($PRCS_SPEC,',') );
        $specids = "";
        for ( $i = 0;$i < count( $PRCS_SPEC_ARR ); ++$i )
        {
            if($PRCS_SPEC_ARR[$i]=='@diy')//�Զ���
            {
                $tempUserId='';
                $specids .= $specids=="" ? towhere($tempUserId) : ",".towhere($tempUserId);
            }
            
            if($PRCS_SPEC_ARR[$i]=='@obj')//ҵ�����
            {
                if(isset($obj_exa_info[$PRCS_NAME])){
                    $tempUserId=$obj_exa_info[$PRCS_NAME];
                    $specids .= $specids=="" ? towhere($tempUserId) : ",".towhere($tempUserId);
                }else{
                    continue;
                }
            }
			if($PRCS_SPEC_ARR[$i]=='@objArr')//ҵ�������
            {
			    if($obj_exa_info&&is_array($obj_exa_info)){
					$vm=0;
					foreach($obj_exa_info as $key => $val){
						if($val){
							$flowMoneyTmp=1;
							$ckarray[]=trim($val,',');
							$ckname[trim($val,',')]=$key;
						$vm++;
						}
					}
                        $ckarray = array_diff($ckarray, array(null,''));
                    	$ckarray = array_unique($ckarray);
                    	$ckarray = array_values($ckarray);
                    	$PRCS_NAME=$ckname[$ckarray[0]];
                    	$specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@bmld')//�����쵼
            {
                if(isset($billDept)){
                    $tempUserId="";
                    $fsql->query2("select a.Leader_id from department a where  a.DEPT_ID='$billDept' ");
                    while($fsql->next_record()){
                        $tempUserId.=$fsql->f("Leader_id");
                    }
                    $specids .= $specids=="" ? towhere($tempUserId) : ",".towhere($tempUserId);
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@bmjl')//����������
            {
                if(isset($billDept)){
                    $tempUserId="";
                    $fsql->query2("select a.LeaderId from area_leader a where  a.DEPT_ID='$billDept' ");
                    while($fsql->next_record()){
                        $tempUserId.=$fsql->f("LeaderId");
                    }
                    $specids .= $specids=="" ? towhere($tempUserId) : ",".towhere($tempUserId);
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@qyjl')
            {
                if(isset($billDept)){
                    if(!empty($billArea)){
                        $fsql->query2("select a.LeaderId from area_leader a where a.DEPT_ID='$billDept' and a.AreaId='$billArea'");
                    }else{
                        $fsql->query2("select a.LeaderId from area_leader a,user u where u.AREA=a.AreaId and a.DEPT_ID='$billDept' and u.USER_ID='$USER_ID'");
                    }
                    if($fsql->next_record( ))
                    {
                        $specids .= $specids=="" ? towhere($fsql->f("LeaderId")) : ",".towhere($fsql->f("LeaderId"));
                    }
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@xmjl')
            {
//�ҳ���������Ŀ����
                if(isset($proSid)&&$proSid!=""){
                    $xmjlids = $fsql->getrow("select x.Manager from xm_lx x where x.ID='".$proSid."'");
                }elseif(isset($proId)&&$proId!=""){
                    $xmjlids = $fsql->getrow("select x.Manager from xm_lx x where x.ProId='".$proId."' order by Flag , BeginDate desc limit 0 , 1  ");
                }else{
                    continue;
                }
                $specids .= $specids=="" ? towhere($xmjlids["Manager"]):",".towhere($xmjlids["Manager"]);
            }
            if($PRCS_SPEC_ARR[$i]=='@bmzj')
            {
//�����ܼ�
                if(isset($billDept)){
                    $fsql->query2("select d.MajorId , d.ViceManager ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                    if($fsql->next_record( ))
                    {
                        $bmzj=$fsql->f("MajorId");
                        if(empty($bmzj)||trim($bmzj,',')==$_SESSION['USER_ID']){
                          $bmzj=$fsql->f("ViceManager");
                        }
                        $specids .= $specids=="" ? towhere($bmzj) : ",".towhere($bmzj);
                    }
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@bmfz')
            {
//���Ÿ���/�ܾ���
                if(isset($billDept)){
                    $fsql->query2("select a.ViceManager from department a where a.DEPT_ID='$billDept' ");
                    if($fsql->next_record( ))
                    {   
                        if($fsql->f("ViceManager")!=""){
                            $specids .= $specids=="" ? towhere($fsql->f("ViceManager")) : ",".towhere($fsql->f("ViceManager"));
                        }else{
                            $fsql->query2("select u.USER_ID from user u, user_priv p where u.USER_PRIV=p.USER_PRIV and p.PRIV_NAME='�ܲ�' ");
                            while($fsql->next_record()){
                                $specids .= $specids=="" ? towhere($fsql->f("USER_ID")) : ",".towhere($fsql->f("USER_ID"));
                            }
                        }
                    }
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@bmfzj')
            {
//��zongjian
                if(isset($billDept)){
                    $fsql->query2("select d.MajorId , d.vicemagor , d.ViceManager ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                    if($fsql->next_record( ))
                    {
                        $bmfzj=$fsql->f("vicemagor");
                        if(empty($bmfzj)||trim($bmfzj,',')==$_SESSION['USER_ID']){
                          $bmfzj=$fsql->f("MajorId");
                        }
                        if(empty($bmfzj)||trim($bmfzj,',')==$_SESSION['USER_ID']){
                          $bmfzj=$fsql->f("ViceManager");
                        }
                        $specids .= $specids=="" ? towhere($bmfzj) : ",".towhere($bmfzj);
                    }
                }else{
                    continue;
                }
            }
            //            �ಿ�ž����ܼ� 
            if($PRCS_SPEC_ARR[$i]=='@mdla')
            {
                $billDepts=explode(',', $billDept);
               if($billDepts&&is_array($billDepts)){
                    foreach($billDepts as $key=>$val){
                        if(isset($val)){
                        	//���ž���
				                if(!empty($billCom)){
				                    $fsql->query2("SELECT a.manager,a.userid,b.DEPT_NAME FROM dept_com a LEFT JOIN department b ON a.dept=b.DEPT_ID WHERE a.dept='$val' and a.compt='$billCom'");
				                }else{
				                    $fsql->query2("SELECT a.manager,a.userid,b.DEPT_NAME FROM dept_com a,department b,user u WHERE u.company=a.compt AND a.dept=b.DEPT_ID  and a.dept='$val' and u.USER_ID='$USER_ID'");
				                }
				                if($fsql->next_record( ))
				                {
									if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
				                    	$flowMoneyArr[$fsql->f('DEPT_NAME').'������']=0;
				                    	$ckarray[]=trim($fsql->f('manager'),',');
				                        $ckname[trim($fsql->f('manager'),',')]=$fsql->f('DEPT_NAME').'������';
				                    }  
				                    if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
				                    	$flowMoneyArr[$fsql->f('DEPT_NAME').'����']=0;
				                    	$ckarray[]=trim($fsql->f('userid'),',');
				                        $ckname[trim($fsql->f('userid'),',')]=$fsql->f('DEPT_NAME').'����';
				                    }                   
				                }
                            $fsql->query2("select d.MajorId , d.vicemagor , d.ViceManager ,dept_name ,d.generalManager from department d where d.DEPT_ID='$val' ");
                            if($fsql->next_record( ))
                            {
                                $bmfzj=$fsql->f("vicemagor");
                                $bmfzjn=$fsql->f('dept_name').'���ܼ�';
                                if(empty($bmfzj)){
                                  $bmfzj=$fsql->f("MajorId");
                                  $bmfzjn=$fsql->f('dept_name').'�ܼ�';
                                }
                                //if(empty($bmfzj)&&!in_array($val,$NetDeptI)){
								if(empty($bmfzj)){
                                  $bmfzj=$fsql->f("ViceManager");
                                  $bmfzjn=$fsql->f('dept_name').'���ܾ���';
                                }
                                $ckarray[]=trim($bmfzj,',');
                                $ckname[trim($bmfzj,',')]=$bmfzjn;
                            }
                        }
                    }
                    $ckarray = array_diff($ckarray, array(null,''));
                    $ckarray = array_unique($ckarray);
                    $ckarray = array_values($ckarray);
                    $PRCS_NAME=$ckname[$ckarray[0]];
                    $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@pl2a')
            {
//��zongjian
                if($billArea){
                	$flowMoneyArr['ʡ�ݸ�����']=0; 
					$flowMoneyArr['��������']=0;					
                        $fsql->query2("SELECT mainManagerId,managerId,headId FROM oa_esm_office_range where id='$billArea'");
                    while($fsql->next_record()){
                        ///$specids .= $specids=="" ? towhere($fsql->f("managerCode")) : ",".towhere($fsql->f("managerCode"));
                        if($fsql->f("managerId")){
                          $ckarray[]=trim($fsql->f('managerId'),',');
                          $ckname[trim($fsql->f('managerId'),',')]='ʡ�ݸ�����';	
                        }else if($fsql->f("mainManagerId")){
						 $ckarray[]=trim($fsql->f('mainManagerId'),',');
                          $ckname[trim($fsql->f('mainManagerId'),',')]='��������';
						
						}
                    }
                $ckarray = array_diff($ckarray, array(null,''));
            	$ckarray = array_unique($ckarray);
            	$ckarray = array_values($ckarray);
            	$PRCS_NAME=$ckname[$ckarray[0]];
            	$specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                }else{
                  continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@gcqy')
            {
                if(isset($examCode)){
                if(trim($examCode)=='oa_esm_project'){
                    $fsql->query2("SELECT m.usercode FROM 
                         oa_esm_project p 
                        left join oa_esm_office_managerinfo m on (p.officeid=m.officeid) 
                        where p.id='".$billId."' ");
                }else{
                    $fsql->query2("SELECT m.usercode FROM oa_esm_change_baseinfo cb
                        left join oa_esm_project p on (cb.projectid=p.id)
                        left join oa_esm_office_managerinfo m on (p.officeid=m.officeid)
                        where cb.id='".$billId."' ");
                }

                    while($fsql->next_record()){
                        $specids .= $specids=="" ? towhere($fsql->f("usercode")) : ",".towhere($fsql->f("usercode"));
                    }
                }else{
                    continue;
                }
            }
			if($PRCS_SPEC_ARR[$i]=='@wgcqy')
                    {
//��zongjian
                        if($billArea){
                        	$flowMoneyArr['ʡ�ݸ�����']=0;
                        	$flowMoneyArr['��������']=10000;
                        	$flowMoneyArr['���Ÿ�����']=50000;                           
                            $fsql->query2("SELECT mainManagerId,managerId,headId,productLine FROM oa_esm_office_range where id='$billArea'");
                            while($fsql->next_record()){
                                ///$specids .= $specids=="" ? towhere($fsql->f("managerCode")) : ",".towhere($fsql->f("managerCode"));
                                if($fsql->f("managerId")){
                                  $ckarray[]=trim($fsql->f('managerId'),',');
                                  $ckname[trim($fsql->f('managerId'),',')]='ʡ�ݸ�����';	
                                }
                                if($fsql->f("mainManagerId")){
                                 $ckarray[]=trim($fsql->f('mainManagerId'),',');
                                 $ckname[trim($fsql->f('mainManagerId'),',')]='��������';	
                                }
                                if($fsql->f("headId")&&($fsql->f("headId")!=$fsql->f("mainManagerId"))){
                                 $ckarray[]=trim($fsql->f('headId'),',');
                                 $ckname[trim($fsql->f('headId'),',')]='���Ÿ�����';	
                                }
                                if($_SESSION['USER_ID'] == $fsql->f("headId") && $fsql->f("headId") == $fsql->f("mainManagerId")) {
                                 $ckarray[]=trim('zhongliang.hu',',');
                                 $ckname[trim('zhongliang.hu',',')]='���ܾ���';	
                                }
                            }
                        $ckarray = array_diff($ckarray, array(null,''));
                    	$ckarray = array_unique($ckarray);
                    	$ckarray = array_values($ckarray);
                    	$PRCS_NAME=$ckname[$ckarray[0]];
                    	$ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
                    	$specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                        }else{
                          continue;
                        }
                    }
				
            if($PRCS_SPEC_ARR[$i]=='@gcfz')
                    {
//���̷�������
                        if($billArea){
                        	$flowMoneyArr['��������']=0;
                        	$fsql->query2("SELECT mainManagerId,managerId,headId,productLine FROM oa_esm_office_range where id='$billArea'");
                            while($fsql->next_record()){
                                
                                if($fsql->f("mainManagerId")){
                                 $ckarray[]=trim($fsql->f('mainManagerId'),',');
                                 $ckname[trim($fsql->f('mainManagerId'),',')]='��������';	
                                }
                                
                            }
                        $ckarray = array_diff($ckarray, array(null,''));
                    	$ckarray = array_unique($ckarray);
                    	$ckarray = array_values($ckarray);
                    	$PRCS_NAME=$ckname[$ckarray[0]];
                    	$ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
                    	$specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                        }else{
                          continue;
                        }
                    }

            if($PRCS_SPEC_ARR[$i]=='@gcqu')
                    {
//��zongjian
                        if($billArea){
                        	$flowMoneyArr['ʡ�ݸ�����']=0;
                        	$flowMoneyArr['��������']=10000;
                        	$flowMoneyArr['���Ÿ�����']=20000;                           
                            $fsql->query2("SELECT mainManagerId,managerId,headId FROM oa_esm_office_range where id='$billArea'");
                            while($fsql->next_record()){
                                ///$specids .= $specids=="" ? towhere($fsql->f("managerCode")) : ",".towhere($fsql->f("managerCode"));
                                if($fsql->f("managerId")){
                                  $ckarray[]=trim($fsql->f('managerId'),',');
                                  $ckname[trim($fsql->f('managerId'),',')]='ʡ�ݸ�����';	
                                }
                                if($fsql->f("mainManagerId")){
                                 $ckarray[]=trim($fsql->f('mainManagerId'),',');
                                 $ckname[trim($fsql->f('mainManagerId'),',')]='��������';	
                                }
                                
                            }
                        $ckarray = array_diff($ckarray, array(null,''));
                    	$ckarray = array_unique($ckarray);
                    	$ckarray = array_values($ckarray);
                    	$PRCS_NAME=$ckname[$ckarray[0]];
                    	$ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
                    	$specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                        }else{
                          continue;
                        }
                    }  		

            if($PRCS_SPEC_ARR[$i]=='@htsp')
            {
            //��zongjian
                if($sellCon){
                  $fsql->query2(" SELECT expand1 FROM oa_system_datadict where datacode='".$cktype."' ");
                  while($fsql->next_record()){
                      $specids .= $specids=="" ? towhere($fsql->f("expand1")) : ",".towhere($fsql->f("expand1"));
                  }
                }else{
                    continue;
                }
            }
            if($PRCS_SPEC_ARR[$i]=='@mord')//������ܼ�������
            {
                if(empty($billDept)){
                    $billDept=$_SESSION['DEPT_ID'];
                }
				
                //���ž���
				if(!empty($billCom)){
					$fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
				}else{
					$fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
				}
				if($fsql->next_record( ))
				{
					if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')&&strpos($fsql->f('manager'), $_SESSION['USER_ID'])=== false){
						$flowMoneyArr['���Ÿ�����']=0;
						$ckarray[]=trim($fsql->f('manager'),',');
						$ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
					} 
					
					if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')&&strpos($fsql->f('userid'), $_SESSION['USER_ID'])=== false){
						$flowMoneyArr['���ž���']=0;
						$ckarray[]=trim($fsql->f('userid'),',');
						$ckname[trim($fsql->f('userid'),',')]='���ž���';
					}                   
				}
                
                $fsql->query2("select d.MajorId , d.ViceManager , d.otherman  ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                if($fsql->next_record( ))
                {
                	$flowMoneyArr['�����ܼ�']=0;
                    $ckarray[]=trim(($fsql->f('MajorId')?$fsql->f('MajorId'):$fsql->f('ViceManager')),',');
                    $ckname[trim(($fsql->f('MajorId')?$fsql->f('MajorId'):$fsql->f('ViceManager')),',')]='�����ܼ�';
                    $om=$fsql->f('otherman');
                    if(!empty($om)){
                    	$flowMoneyArr['�����쵼']=0;
                    	$flowMoneyArr['�����쵼']=$ckMoney[1];
                    	$ckarray[]=trim($om,',');
                    	$ckname[trim($om,',')]='�����쵼';
                    }
                }
                $ckarray = array_diff($ckarray, array(null,''));
                $ckarray = array_unique($ckarray);
                $ckarray = array_values($ckarray);
                if($ckarray[0]){
                	$specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
               		$PRCS_NAME=$ckname[$ckarray[0]];
                }
                $ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
            }
			
			if($PRCS_SPEC_ARR[$i]=='@dlm')//������ܼ�������
            {
                if(empty($billDept)){
                    $billDept=$_SESSION['DEPT_ID'];
                }
				
                //���ž���
				if(!empty($billCom)){
					$fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
				}else{
					$fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
				}
				if($fsql->next_record( ))
				{
					if(in_array($billDept,$NetDeptI)){
						if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
						$flowMoneyArr['���Ÿ�����']=0;
						$ckarray[]=trim($fsql->f('manager'),',');
						$ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
					}  
						
						
					}
					if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
							$flowMoneyArr['���ž���']=0;
							$ckarray[]=trim($fsql->f('userid'),',');
							$ckname[trim($fsql->f('userid'),',')]='���ž���';
					} 
					                
				}
                
                $fsql->query2("select d.MajorId , d.ViceManager , d.otherman  ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                if($fsql->next_record( ))
                {
                	//if(!in_array($billDept,$NetDeptI)){
						$flowMoneyArr['�����ܼ�']=0;
						$ckarray[]=trim(($fsql->f('MajorId')?$fsql->f('MajorId'):$fsql->f('ViceManager')),',');
						$ckname[trim(($fsql->f('MajorId')?$fsql->f('MajorId'):$fsql->f('ViceManager')),',')]='�����ܼ�';
						$om=$fsql->f('otherman');
						if(!empty($om)){
							$flowMoneyArr['�����쵼']=0;
							$flowMoneyArr['�����쵼']=$ckMoney[1];
							$ckarray[]=trim($om,',');
							$ckname[trim($om,',')]='�����쵼';
						}
					//}
                }
                $ckarray = array_diff($ckarray, array(null,''));
                $ckarray = array_unique($ckarray);
                $ckarray = array_values($ckarray);
                if($ckarray[0]){
                	$specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
               		$PRCS_NAME=$ckname[$ckarray[0]];
                }
                $ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
            }
			
            if($PRCS_SPEC_ARR[$i]=='@bmauto' && !in_array($prcs_form, array('�������','��')) )//�����쵼������
            {
                //=============================����ѧԺ���ߴ˽ڵ�begin
                if(in_array($prcs_form, array('������ͬ����','������ͬ�������'))) {
                    $fsql->query2("select 1 from oa_sale_other where id = '". $billId ."' and payForBusiness = 'FKYWLX-08'");
                    if($fsql->next_record( ))
                    {
                        continue;
                    }
                }
                //=============================����ѧԺ���ߴ˽ڵ�end
            	
                $ckarray=array();
                $ckname=array();
                $flowMoneyArr=array();
				$ckMoney='0,10000,200000,500000';
                $ckMoney=explode(',', $ckMoney);
                $flowMoneyArr['���ž���']=$ckMoney[0];
                $flowMoneyArr['�����ܼ�']=$ckMoney[1];
                $flowMoneyArr['���ܾ���']=$ckMoney[2];
				$flowMoneyArr['�ܾ���']=$ckMoney[2];
				$flowMoneyArr['�ܲ�']=$ckMoney[3];
				$flowMoneyArr['���³�']=$ckMoney[3];
                if(empty($billDept)){
                    $billDept=$_SESSION['DEPT_ID'];
                }
                $billArea=trim($billArea);
                //���ž���
                /*
                //����
                if(in_array($billDept,$SaleDeptI)){//����
                  if(!empty($sellEday)){
                      foreach($sellEday as $eekey=>$eeval){
                          if(in_array($_SESSION['USERNAME'],$eeval)){
                              $flowMoneyArr['�����쵼']=0;
                              $ckarray[]=trim($eekey,',');
                              $ckname[trim($eekey,',')]='�����쵼';
                          }
                      }
                  }
                }
                */
                //������ͬ
                //$ck_dept_arr=$SaleDeptI;
                $ck_dept_arr=array(271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300,301,302,304,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,320,321,322,323,324,325,326,327,328,329,330,331,358);
				$earr=array( 
                	'��������'=>$NetDeptI
                	 //,'�����ͬ���������'=>$NetDeptI
                	,'�����ͬ����'=>$ck_dept_arr
                	,'�����ͬ�������'=>$ck_dept_arr
                	,'������ͬ����'=>$NetDeptI
					,'������ͬ�������'=>$NetDeptI
                	,'������ͬ���������'=>$NetDeptI
                	//,'������ͬ�������'=>$ck_dept_arr
                );
        		if(!$billCom){
							$billCom=$billCompany?$billCompany:$_SESSION['USER_COM'];
				}
				if(!empty($billDept)){
					$fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
				}else{
					$fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
				}
				if($fsql->next_record( ))
				{
					
					if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
						//$flowMoneyArr['���Ÿ�����']=0;
						//$ckarray[]=trim($fsql->f('manager'),',');
						//$ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
					} 
					if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
						$flowMoneyArr['���ž���']=0;
						$ckarray[]=trim($fsql->f('userid'),',');
						$ckname[trim($fsql->f('userid'),',')]='���ž���';
					}
					if(trim($fsql->f('userid'),',')==$_SESSION['USER_ID']){
						$ckarray=array();
						$ckname=array();
					}     
					
				}
				$sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCom' ";
				$fsql->query2($sqlcom);
				if ($fsql->num_rows() > 0) {
					$fsql->next_record();
					$gmanager = $fsql->f('gmanager');
					$ceo = $fsql->f('ceo');
					$chairman = $fsql->f('chairman');
				}
				
				if(in_array($prcs_form, array('������ͬ����','������ͬ�������'))) {
				    if(in_array($billDept,$YyDept) || in_array($billDept,$ZcDept)){
				        unset($flowMoneyArr);
				        $ckMoney='0,10000,50000,200000,500000';
				        $ckMoneyI=explode(',', $ckMoney);
				        $flowMoneyArr['��������']=0;
				        $flowMoneyArr['�������۾���']=0;
				        $flowMoneyArr['�������۸�����']=0;
				        $flowMoneyArr['���Ÿ�����']=0;
				        $flowMoneyArr['���ž���']=0;
				        $flowMoneyArr['�����ܼ�']=$ckMoneyI[1];
				        $flowMoneyArr['���ĸ�����']=$ckMoneyI[2];
				        $flowMoneyArr['���ܾ���']=$ckMoneyI[3];
				        $flowMoneyArr['�ܾ���']=$ckMoneyI[3];
				        $flowMoneyArr['�ܲ�']=$ckMoneyI[4];
				    }
				}
				
				if(in_array($billDept,$SaleDeptI)){
						$flowMoneyArr['���ž���']=$ckMoney[0];
						$flowMoneyArr['�����ܼ�']=$ckMoney[1];
						$flowMoneyArr['���ܾ���']=$ckMoney[2];
						$flowMoneyArr['�ܾ���']=$ckMoney[2];
						$flowMoneyArr['�ܲ�']=$ckMoney[3];
						$flowMoneyArr['���³�']=$ckMoney[3];
				}
                $fsql->query2("select d.MajorId , d.ViceManager , d.otherman , d.leader_id  ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                if($fsql->next_record( ))
                {
					 /*if($fsql->f('leader_id')&&!in_array($billDept,$earr[$prcs_form])){
						$ckarray[]=trim($fsql->f('leader_id'),',');
						$ckname[trim($fsql->f('leader_id'),',')]='���ž���';
					  }
                	*/
				 
					if(trim($fsql->f('MajorId'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('MajorId'),',')){
							$ckarray[]=trim($fsql->f('MajorId'),',');
							$ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
					}
					if(trim($fsql->f('MajorId'),',')==$_SESSION['USER_ID']){
						$ckarray=array();
						$ckname=array();
					}
					
					if(in_array($billDept,$earr[$prcs_form])){
					        $flowMoneyArr['�����ܼ�']=$ckMoney[1];
							$flowMoneyArr['���ܾ���']=$ckMoney[1];
							$flowMoneyArr['�ܾ���']=$ckMoney[2];
							$flowMoneyArr['�ܲ�']=$ckMoney[2];
							if($prcs_form=='�����ͬ����'||$prcs_form=='�����ͬ�������'||(($billDept==297||$billDept==358)&&$prcs_form=='��������')){
							    $flowMoneyArr['�����ܼ�']=$ckMoney[0];
							    $flowMoneyArr['���ܾ���']=$ckMoney[2];
							    $flowMoneyArr['�ܾ���']=$ckMoney[3];
							    $flowMoneyArr['�ܲ�']=$ckMoney[3];
							}
							
							$ckarray[]='zhongliang.hu';
							$ckname['zhongliang.hu']='���ܾ���';
							//$flowMoneyArr['���Ź�����Ա']=50000;
					}
					
                    $om=$fsql->f('otherman');
                    if(!empty($om)){
                    	$flowMoneyArr['�����ܼ�']=0;
                    	$flowMoneyArr['�����쵼']=$ckMoney[1];
                    	$ckarray[]=trim($om,',');
                    	$ckname[trim($om,',')]='�����쵼';
                    }
                    if(in_array($prcs_form, array('������ͬ����','������ͬ�������'))) {
                        if(in_array($billDept,$YyDept)){
                            $ckarray[]='chen.chen';
                            $ckname['chen.chen']='���ĸ�����';
                        }else if(in_array($billDept,$ZcDept)){
                            $ckarray[]='tianlin.zhang';
                            $ckname['tianlin.zhang']='���ĸ�����';
                        }
                    }
					if(trim($fsql->f('ViceManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('ViceManager'),',')){
							$ckarray[]=trim($fsql->f('ViceManager'),',');
							$ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
					}
					if(trim($fsql->f('ViceManager'),',')==$_SESSION['USER_ID']){
						$ckarray=array();
						$ckname=array();
					}
					if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
					   $ckarray[]=trim($fsql->f('generalManager'),',');
					   $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
					 }
					if($billCom=='xs'||$billCom=='jk'){
						$flowMoneyArr['���ž���']=0;
						$flowMoneyArr['�����ܼ�']=$ckMoney[1];
						$flowMoneyArr['���ܾ���']=50000;
						$flowMoneyArr['�ܾ���']=$ckMoney[2];
						$flowMoneyArr['�ܲ�']=$ckMoney[2];
						$flowMoneyArr['���³�']=$ckMoney[2];
					}  
                }
				if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
					$ckarray[]=$gmanager;
					$ckname[$gmanager]='�ܾ���';	
				 }
				
                if(!empty($flowMoney)&&$flowMoney!=' '){ 
					if(in_array($billDept,$GroupDeptI)){
                    	$ckarray[]=$chairman;
						$ckname[$chairman]='���³�';
					}else{
						$ckarray[]=$ceo;
						$ckname[$ceo]='�ܲ�';
					}
                }
                
				
                $ckarray = array_diff($ckarray, array(null,''));
                $ckarray = array_unique($ckarray);
                $ckarray = array_values($ckarray);
                $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                $PRCS_NAME=$ckname[$ckarray[0]];
                $ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
                //print_r($ckarray);
                //print_r($ckname);
            }
			
			if($PRCS_SPEC_ARR[$i]=='@bmauto' && in_array($prcs_form, array('�������','��')) )//�����쵼������
            {								//		0	 1	  2	   3     4     5      6      7      8
										$ckMoneys='500,1000,5000,10000,20000,50000,100000,200000,500000';
						            	if(!$billDept){
											$billDept=$DEPT_ID?$DEPT_ID:$_SESSION['DEPT_ID'];
										}
						                $ckarray=array();
						                $ckname=array();
						                $flowMoneyArr=array();
						                $ckMoney=explode(',', $ckMoney);
										$ckMoneyI=explode(',', $ckMoneys);
										
										$flowMoneyArr['��Ŀ����']=0;//������
										  $flowMoneyArr['ʡ�ݸ�����']=0;
										  $flowMoneyArr['��������']=0;
										  $flowMoneyArr['���Ÿ�����']=0;
										  $flowMoneyArr['���ž���']=$ckMoneyI[1];
										  $flowMoneyArr['�����ܼ�']=$ckMoneyI[3]; 
										  $flowMoneyArr['���ܾ���']=$ckMoneyI[5];
										  $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
										  $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
										  
										  if($_SESSION['USER_COM'] == 'dl') {
											$flowMoneyArr['��Ŀ����']=0;//������
										  	$flowMoneyArr['ʡ�ݸ�����']=0;
										  	$flowMoneyArr['��������']=0;
										  	$flowMoneyArr['���Ÿ�����']=0;
										  	$flowMoneyArr['���ž���']=$ckMoneyI[1];
										  	$flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
										  	$flowMoneyArr['���ܾ���']=$ckMoneyI[7];
										  	$flowMoneyArr['�ܲ�']=$ckMoneyI[8];
										  }
										  
										  if(in_array($billDept,$DxDeptI)){
											$flowMoneyArr['��Ŀ����']=0;//������
											$flowMoneyArr['ʡ�ݸ�����']=0;
											$flowMoneyArr['��������']=0;
											$flowMoneyArr['���Ÿ�����']=0;
											$flowMoneyArr['���ž���']=0;
											$flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
											$flowMoneyArr['���ܾ���']=$ckMoneyI[5];
											$flowMoneyArr['�ܾ���']=$ckMoneyI[7];
											$flowMoneyArr['�ܲ�']=$ckMoneyI[7];
										  }
										  
										  if(in_array($billDept,$NetDeptI)){
										  	unset($flowMoneyArr);
											$flowMoneyArr['��Ŀ����']=0;//������
											$flowMoneyArr['ʡ�ݸ�����']=0;
											$flowMoneyArr['���Ÿ�����']=0;
											$flowMoneyArr['���ž���']=0;
										  	$flowMoneyArr['��������']=$ckMoneyI[3];
											$flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
											$flowMoneyArr['���ܾ���']=$ckMoneyI[5];
											$flowMoneyArr['�ܾ���']=$ckMoneyI[7];
											$flowMoneyArr['�ܲ�']=$ckMoneyI[8];
										  }
										  if(in_array($billDept,$SaleDeptI)){
											$flowMoneyArr['��Ŀ����']=0;//������
											$flowMoneyArr['ʡ�ݸ�����']=0;
											$flowMoneyArr['��������']=0;
											$flowMoneyArr['���Ÿ�����']=0;
											$flowMoneyArr['���ž���']=0;
											$flowMoneyArr['�����ܼ�']=0;
											$flowMoneyArr['���ܾ���']=$ckMoneyI[7];
											$flowMoneyArr['�ܾ���']=$ckMoneyI[7];
											$flowMoneyArr['�ܲ�']=$ckMoneyI[8];
										  }
										  
										  if(in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){
												$flowMoneyArr['��Ŀ����']=0;//������
												$flowMoneyArr['ʡ�ݸ�����']=0;
												$flowMoneyArr['�������۾���']=$ckMoneyI[0];
												$flowMoneyArr['�������۸�����']=$ckMoneyI[3];
												$flowMoneyArr['���Ÿ�����']=0;
												$flowMoneyArr['���ž���']=$ckMoneyI[1];
												$flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
												$flowMoneyArr['���ܾ���']=$ckMoneyI[3];
												$flowMoneyArr['�ܾ���']=$ckMoneyI[6];						
												$flowMoneyArr['�ܲ�']=$ckMoneyI[6];						
											}	
											


											if($_SESSION['USER_COM'] == 'xs' || $_SESSION['USER_COM'] == 'jk') {
												$flowMoneyArr['��Ŀ����']=0;//������
												$flowMoneyArr['ʡ�ݸ�����']=0;
												$flowMoneyArr['��������']=0;
												unset($flowMoneyArr['���Ÿ�����']);
												$flowMoneyArr['���ž���']=$ckMoneyI[1];
												$flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
												$flowMoneyArr['���ܾ���']=$ckMoneyI[5];
												$flowMoneyArr['�ܾ���']=$ckMoneyI[7];
												$flowMoneyArr['�ܲ�']=$ckMoneyI[8];
											}
											
											if(in_array($billDept,$YyDept) || in_array($billDept,$ZcDept)){
											    unset($flowMoneyArr);
											    $flowMoneyArr['��������']=0;
											    $flowMoneyArr['�������۾���']=0;
											    $flowMoneyArr['�������۸�����']=0;
											    $flowMoneyArr['���Ÿ�����']=0;
											    $flowMoneyArr['���ž���']=$ckMoneyI[1];
											    $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
											    $flowMoneyArr['���ĸ�����']=$ckMoneyI[5];
											    $flowMoneyArr['���ܾ���']=$ckMoneyI[7];
											    $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
											    $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
											}
											
										if(empty($billDept)){
						                    $billDept=$_SESSION['DEPT_ID'];
						                }
										
										if(!empty($billCom)){
											$sqlDept="SELECT a.manager,a.userid,b.DEPT_NAME FROM dept_com a LEFT JOIN department b ON a.dept=b.DEPT_ID WHERE a.dept='$billDept' and a.compt='$billCom'";				                
										}else{
											$sqlDept="SELECT a.manager,a.userid,b.DEPT_NAME FROM dept_com a,department b,user u WHERE u.company=a.compt AND a.dept=b.DEPT_ID  and a.dept='$billDept' and u.USER_ID='$USER_ID'";
										}
									     $deptComI = $fsql->getrow($sqlDept);
								
										
						                $deptI = $fsql->getrow("select d.MajorId , d.ViceManager,d.generalManager , d.otherman , d.leader_id  from department d where d.DEPT_ID='$billDept' "); 
						                if($deptI)
						                {
						                	  if(in_array($billDepts,array())&&$appArea){ 
							                	   	 $fsql->query2("SELECT mainManagerId,managerId,headId FROM oa_esm_office_range where id='$appArea'");
							                            while($fsql->next_record()){
							                                ///$specids .= $specids=="" ? towhere($fsql->f("managerCode")) : ",".towhere($fsql->f("managerCode"));
							                                if($fsql->f("managerId")&&$fsql->f("managerId")!=$_SESSION['USER_ID']){
							                                  $ckarray[]=trim($fsql->f('managerId'),',');
							                                  $ckname[trim($fsql->f('managerId'),',')]='ʡ�ݸ�����';	
							                                }
							                                if($fsql->f("mainManagerId")&&$fsql->f("mainManagerId")!=$_SESSION['USER_ID']){
							                                 $ckarray[]=trim($fsql->f('mainManagerId'),',');
							                                 $ckname[trim($fsql->f('mainManagerId'),',')]='��������';	
							                                }
							                                if($fsql->f("headId")&&$fsql->f("mainManagerId")!=$fsql->f("headId")&&$fsql->f("headId")!=$_SESSION["USER_ID"]){
							                                 $flowMoneyArr['���Ÿ�����']=$ckMoney[2];
							                                 $ckarray[]=trim($fsql->f('headId'),',');
							                                 $ckname[trim($fsql->f('headId'),',')]='���Ÿ�����';	
							                                }
							                                
							                            }
						                	   }else{
						                	   	  if(in_array($billDepts,array())&&$workArea){
							                	   	 $fsql->query2("SELECT  group_concat( personid )  as 'ʡ������',group_concat( areanameid ) as '������'  , group_concat(d.MajorId)   as '�����ܼ�'  , group_concat( d.ViceManager )  as '���Ÿ���'  FROM oa_system_saleperson s  LEFT JOIN department d on ( s.deptId = d.DEPT_ID ) where s.id ='".$workArea."' ");
					                                if($fsql->next_record( ))
					                                {   
				                                			$flowMoneyArr['ʡ������']=0;
															if(trim($fsql->f('ʡ������'),',')!=$_SESSION['USER_ID']){
																$ckarray[]=trim($fsql->f('ʡ������'),',');
																$ckname[trim($fsql->f('ʡ������'),',')]='ʡ������';
															}
							                                $flowMoneyArr['������']=0;
							                                $ckarray[]=trim($fsql->f('������'),',');
							                                $ckname[trim($fsql->f('������'),',')]='������';
					                                }
					                                if(trim($deptI['MajorId'],',')!=$_SESSION['USER_ID']&&$deptI['MajorId'] && !in_array($billDept,$SaleDeptI)){
							                			$ckarray[]=trim($deptI['MajorId'],',');
							                    		$ckname[trim($deptI['MajorId'],',')]='�����ܼ�';
								                     }
													 if(trim($deptI['ViceManager'],',')!=$_SESSION['USER_ID']&&$deptI['ViceManager']){
							                			$ckarray[]=trim($deptI['ViceManager'],',');
							                    		$ckname[trim($deptI['ViceManager'],',')]='���ܾ���';
								                     }
													 if(trim($deptI['generalManager'],',')!=$_SESSION['USER_ID']&&trim($deptI['generalManager'],',')){
														   $ckarray[]=trim($deptI['generalManager'],',');
														   $ckname[trim($deptI['generalManager'],',')]='�ܾ���';
													 }
					                            
							                     }else{
													if(!in_array($billDept,$SaleDeptI)){
													 if(trim($deptComI['manager'],',')!=$_SESSION['USER_ID']&&$deptComI['manager']
													 &&trim($deptComI['userid'],',')!=$_SESSION['USER_ID']
													 &&trim($deptI['MajorId'],',')!=$_SESSION['USER_ID']
													 &&trim($deptI['ViceManager'],',')!=$_SESSION['USER_ID']){
														$ckarray[]=trim($deptComI['manager'],',');
														$ckname[trim($deptComI['manager'],',')]='���Ÿ�����';
													 }
													 
													 if(trim($deptComI['userid'],',')!=$_SESSION['USER_ID']&&$deptComI['userid']
													 &&trim($deptI['MajorId'],',')!=$_SESSION['USER_ID']
													 &&trim($deptI['ViceManager'],',')!=$_SESSION['USER_ID']){
														$ckarray[]=trim($deptComI['userid'],',');
														$ckname[trim($deptComI['userid'],',')]='���ž���';
													 }
													
													 
													}

													$fsql->query2("select headid from oa_esm_office_baseinfo where feedeptid ='$billDept';");
													while($fsql->next_record()){
														if($fsql->f("headid")&&$fsql->f("headid")!=$_SESSION['USER_ID']){
															$ckarray[]=trim($fsql->f('headid'),',');
															$ckname[trim($fsql->f('headid'),',')]='��������';
														}
													}
													
													 if(trim($deptI['MajorId'],',')!=$_SESSION['USER_ID']&&$deptI['MajorId']&&trim($deptI['ViceManager'],',')!=$_SESSION['USER_ID'] && !in_array($billDept,$SaleDeptI)){
														$ckarray[]=trim($deptI['MajorId'],',');
														$ckname[trim($deptI['MajorId'],',')]='�����ܼ�';
													 }
													 
													 
													 if((in_array($billDept,$NetDeptI)||in_array($billDept,$NetODeptI))&&trim($deptI['ViceManager'],',')!=$_SESSION['USER_ID']){
														 $flowMoneyArr['���ž���']=0;
														 //$flowMoneyArr['�����ܼ�']=$ckMoney[1];
														 if($_SESSION['USER_ID']!="zhongliang.hu"){
															 $ckarray[]=trim('zhongliang.hu',',');
														 	$ckname[trim('zhongliang.hu',',')]='���ܾ���';
														 }
													 }
													 
													 if(in_array($billDept,$SaleDeptI)){
													 	$fsql->query2("select r.areaPrincipalId,r.areaPrincipal,m.productLineName from oa_system_region r inner join oa_esm_office_managerinfo m on r.areaName = m.province  inner join department d on d.pdeptname = m.productLineName where r.isstart = 0 and d.DEPT_ID = '$billDept' ");
													 	if($fsql->next_record( ))
													 	{
															$ckarray[]=trim($fsql->f('areaPrincipalId'),',');
															$ckname[trim($fsql->f('areaPrincipalId'),',')]='�����ܼ�';
													 	}
														 
													 }
													 
													 if(trim($deptI['ViceManager'],',')!=$_SESSION['USER_ID']&&$deptI['ViceManager']){
							                			$ckarray[]=trim($deptI['ViceManager'],',');
							                    		$ckname[trim($deptI['ViceManager'],',')]='���ܾ���';
								                     }
													 
													 if(in_array($billDept,$YyDept)){
													     $ckarray[]='chen.chen';
													     $ckname['chen.chen']='���ĸ�����';
													 }else if(in_array($billDept,$ZcDept)){
													     $ckarray[]='tianlin.zhang';
													     $ckname['tianlin.zhang']='���ĸ�����';
													 }
													 
													 if(trim($deptI['ViceManager'],',')==$_SESSION['USER_ID']){
							                    		$flowMoneyArr['�ܲ�']=0;
								                     }
													 
												   if(trim($deptI['generalManager'],',')!=$_SESSION['USER_ID']&&trim($deptI['generalManager'],',')){
														   $ckarray[]=trim($deptI['generalManager'],',');
														   $ckname[trim($deptI['generalManager'],',')]='�ܾ���';
													 }
													 
												    if($_SESSION['Company']=='xs'){
															$ckarray[]='rlchen';
															$ckname['rlchen']='�ܾ���';
															$manager = $ckarray[0];
															if($ckname[$manager] == '���Ÿ�����') {
																unset($ckarray[0]);
																unset($ckname[$manager]);
															}
													}
													if($_SESSION['Company']=='jk'){
															$ckarray[]='renliang';
															$ckname['renliang']='�ܾ���';
															$manager = $ckarray[0];
															if($ckname[$manager] == '���Ÿ�����') {
																unset($ckarray[0]);
																unset($ckname[$manager]);
															}
													}											   
												   
												   
												    if(!empty($flowMoney)&&$flowMoney!=''){
															$ckarray[]='danian.zhu';
															$ckname['danian.zhu']='�ܲ�';	
													}
												  
													
							                     }
												 
						                	    }
						                	   
						                    
						                }
						                $subLength = 0 ;
						                $i = 0;
						                foreach($ckarray as $key=>$val){
						                	$i++ ;
						                	if($val){
							                	$valarr = explode(',', $val);
							                	if( in_array( $_SESSION['USER_ID'] ,$valarr) ){
							                		$subLength = $i ;
							                	}
						                	}
						                	
						                }
						                if($subLength){
						                	$ckarray = array_slice($ckarray,$subLength);
						                }
						                $subLength;
						                $ckarray = array_diff($ckarray, array(null,''));
						                $ckarray = array_unique($ckarray);
						                $ckarray = array_values($ckarray);
						                $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
						                $PRCS_NAME=$ckname[$ckarray[0]];
						                $ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
// 						                print_r($ckarray);
// 						                print_r($ckname);
// 										print_r($flowMoneyArr);
						            }
//            ���������쵼
            if($PRCS_SPEC_ARR[$i]=='@qtbmld')
            {
                $billDepts=explode(',', $billDept);
                if(!empty($billDepts)){
                    $ckarray=array();
                    $ckname=array();
                    foreach($billDepts as $key=>$val){
                        if(isset($val)){
                            $fsql->query2("select d.Leader_id,d.MajorId , d.vicemagor , d.ViceManager ,dept_name ,d.generalManager from department d where d.DEPT_ID='$val' ");
                            if($fsql->next_record( ))
                            {
                                $bmfzj=$fsql->f("vicemagor");
                                $bmfzjn=$fsql->f('dept_name').'���ܼ�';
                                if(empty($bmfzj)){
									if(145==$val){
										$bmfzj=$fsql->f("Leader_id");
                                        $bmfzjn=$fsql->f('dept_name').'����';
									}else{
										$bmfzj=$fsql->f("MajorId");
                                        $bmfzjn=$fsql->f('dept_name').'�ܼ�';
									}
                                  
                                }
                                if(empty($bmfzj)){
                                  $bmfzj=$fsql->f("ViceManager");
                                  $bmfzjn=$fsql->f('dept_name').'���ܾ���';
                                }
                                $ckarray[]=trim($bmfzj,',');
                                $ckname[trim($bmfzj,',')]=$bmfzjn;
                            }
                        }
                    }
                    $ckarray = array_diff($ckarray, array(null,''));
                    $ckarray = array_unique($ckarray);
                    $ckarray = array_values($ckarray);
                    $PRCS_NAME=$ckname[$ckarray[0]];
                    $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                }else{
                    continue;
                }
            }
//            ��ϲ����쵼
            if($PRCS_SPEC_ARR[$i]=='@hhbmld')
            {
                $billDepts=explode(',', $billDept);
                if(!empty($billDepts)){
                    $tempu='';
                    $tempn='ҵ���쵼';
                    foreach($billDepts as $key=>$val){
                        if(isset($val)){
                            $fsql->query2("select d.MajorId , d.vicemagor , d.ViceManager ,dept_name ,d.generalManager from department d where d.DEPT_ID='$val' ");
                            if($fsql->next_record( ))
                            {
                                $bmfzj=$fsql->f("vicemagor");
                                $bmfzjn=$fsql->f('dept_name').'���ܼ�';
                                if(empty($bmfzj)){
                                  $bmfzj=$fsql->f("MajorId");
                                  $bmfzjn=$fsql->f('dept_name').'�ܼ�';
                                }
                                if(empty($bmfzj)){
                                  $bmfzj=$fsql->f("ViceManager");
                                  $bmfzjn=$fsql->f('dept_name').'���ܾ���';
                                }
                                $tempu.=trim($bmfzj,',').',';
                            }
                        }
                    }
                    $PRCS_NAME=$tempn;
                    $specids .= $specids=="" ? towhere($tempu) : ",".towhere($tempu);
                }else{
                    continue;
                }
            }
            
//            �ֲ���Ա
            if($PRCS_SPEC_ARR[$i]=='@qtry')
            {
                $billUsers=explode(',', $billUser);
                //print_r($billUsers);
                if(!empty($billUsers)){
                    $ckarray=array();
                    $ckname=array();
                    foreach($billUsers as $key=>$val){
                        if($val){
                            $ckarray[]=$val;
                            $ckname[$val]='�����쵼';
                        }
                    }
                    $ckarray = array_diff($ckarray, array(null,''));
                    $ckarray = array_unique($ckarray);
                    $ckarray = array_values($ckarray);
                    $PRCS_NAME=$ckname[$ckarray[0]];
                    $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                }else{
                    continue;
                }
            }
//            �����Ա
            if($PRCS_SPEC_ARR[$i]=='@hhry')
            {
                //$billUsers=explode(',', $billUser);
                if(!empty($billUser)){
                    $tempu=$billUser;
                    $tempn='�����쵼';
                    $PRCS_NAME=$tempn;
                    $specids .= $specids=="" ? towhere($tempu) : ",".towhere($tempu);
                }else{
                    continue;
                }
            }
            
            //�±���
            if($PRCS_SPEC_ARR[$i]=='@bx')
            {
                //$billUsers=explode(',', $billUser);
                if(!empty($billId)){
                    //$ckMoney='1000,10000,200000';
                    //$ckMoneys='500,1000,5000,10000,50000,200000,500000';
                    $ckMoneys='500,1000,5000,10000,20000,50000,100000,200000,500000';
                    $ckarray=array();
                    $ckname=array();
                    $flowMoneyArr=array();
                    //$ckMoney=explode(',', $ckMoney);
                    $ckMoneyI=explode(',', $ckMoneys);
                    $xsbm = $fsql->getrow("select belongDeptIds from oa_system_configurator_item where groupBelongName = '���۲���'");
                    $serviceMajorId = $fsql->getrow("select GROUP_CONCAT(config_itemSub2) as 'majorids' from oa_system_configurator_item where groupBelongName = '�ܼ�����' and config_item1 = '�����ܼ�'");
                    $sessionDept = $_SESSION['DEPT_ID'];
                    $isSaleDept = 0;
                    if(in_array($sessionDept, explode(',',$xsbm['belongDeptIds'])) || in_array($_SESSION['USER_ID'], explode(',',$serviceMajorId['majorids']))) {
                        $isSaleDept = 1;
                    }
                    $fsql->query2("select a.CostMan,a.detailtype , a.costbelongdeptid , a.costbelongcomid , a.projectid , a.projecttype
                                    , a.promanagerid , a.contractid , a.costbelongerid , a.province , a.city , a.chanceid , a.customertype , a.isproject
                                    , a.billno , a.costman,a.isSpecial,b.DEPT_ID,a.salesAreaId
                                    from cost_summary_list  a LEFT JOIN `user` b ON a.CostMan=b.USER_ID
                    where id='".$billId."' ");
                    if($fsql->next_record( ))
                    {
                        $CostMan=$fsql->f('CostMan');
                        $tmpdetailtype=$fsql->f('detailtype');
                        $billDept=$fsql->f('costbelongdeptid');
                        $billCom=$fsql->f('costbelongcomid');
                        $billProId=$fsql->f('projectid');
                        $billProType=$fsql->f('projecttype');
                        $billProManager=$fsql->f('promanagerid');
                        $billConId=$fsql->f('contractid');
                        $billBelonger=$fsql->f('costbelongerid');
                        $billProvince=$fsql->f('province');
                        $billCity=$fsql->f('city');
                        $billChance=$fsql->f('chanceid');
                        $billCusType=$fsql->f('customertype');
                        $isproject= $fsql->f('isproject');
                        $billBxno = $fsql->f('billno');
                        $billCostMan=$fsql->f('costman');
                        $isSpecial=$fsql->f('isSpecial');
                        $costManDeptId=$fsql->f('DEPT_ID');
                        $salesAreaId=$fsql->f('salesAreaId');
                        if($isproject=='1'){
                            $fsql->query2("SELECT t.exauser  FROM cost_detail_project d
                                left join cost_type t on ( d.costtypeid=t.costtypeid )
                                where d.billno ='".$billBxno."' and t.exauser is not null
                                group by t.exauser");
                        }else{
                            $fsql->query2("SELECT t.exauser  FROM cost_detail d
                                left join cost_type t on ( d.costtypeid=t.costtypeid )
                                where d.billno ='".$billBxno."' and t.exauser is not null
                                group by t.exauser");
                        }
                        $bxi=1;
                        while($fsql->next_record()){
                            $exI=trim($fsql->f('exauser'),',');
                            $exaI=explode(';',$exI);
                            if($exaI&&is_array($exaI)){
                                foreach($exaI as $ekey=>$eVal){
                                    if($eVal){
                                        $exasI=explode('@',$eVal);
                                        if($exasI[0]&&$exasI[1]&&in_array($billDept,(array)$exasI[1])){
                                            $flowMoneyArr['�����쵼'.$bxi]=0;
                                            $ckarraytype[]=trim($exasI[0],',');
                                            $ckname[trim($exasI[0],',')]='�����쵼'.$bxi;
                                            $bxi++;
                                        }else if($exasI[0]&&!$exasI[1]){
                                            $flowMoneyArr['�����쵼'.$bxi]=0;
                                            $ckarraytype[]=trim($fsql->f('exauser'),',');
                                            $ckname[trim($fsql->f('exauser'),',')]='�����쵼'.$bxi;
                                            $bxi++;
                                        }
                                        
                                    }
                                }
                            }
                        }
                        if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                            $flowMoneyArr['��Ŀ����']=0;//������
                            $flowMoneyArr['ʡ�ݸ�����']=0;
                        }
                        $flowMoneyArr['��������']=0;
                        $flowMoneyArr['�������۾���']=0;
                        $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                        $flowMoneyArr['���Ÿ�����']=0;
                        $flowMoneyArr['���ž���']=$ckMoneyI[1];
                        $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
                        $flowMoneyArr['���ܾ���']=$ckMoneyI[7];
                        $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                        $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
                        
                        if(in_array($billDept,$DxDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                                $flowMoneyArr['ʡ�ݸ�����']=0;
                            }
                            $flowMoneyArr['��������']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=0;
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[5];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[7];
                        }
                        if(in_array($billDept,$NetDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                                $flowMoneyArr['ʡ�ݸ�����']=0;
                            }
                            $flowMoneyArr['��������']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=0;
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=0;
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[4];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
                        }
                        if(in_array($billDept,$SaleDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                                $flowMoneyArr['ʡ�ݸ�����']=0;
                            }
                            $flowMoneyArr['��������']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=0;
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[5];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[7];
                        }
                        
                        if(in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                                $flowMoneyArr['ʡ�ݸ�����']=0;
                            }
                            $flowMoneyArr['�������۾���']=$ckMoneyI[0];
                            $flowMoneyArr['�������۸�����']=$ckMoneyI[3];
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=$ckMoneyI[1];
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[3];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[6];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[6];
                        }
                        
                        if(in_array($billDept,$YyDept) || in_array($billDept,$ZcDept)){
                            unset($flowMoneyArr);
                            $flowMoneyArr['��������']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=0;
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=$ckMoneyI[1];
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
                            $flowMoneyArr['���ĸ�����']=$ckMoneyI[5];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
                        }
                        
                        if($tmpdetailtype=='1'){//����
                            
                            if(empty($billDept)){
                                $billDept=$_SESSION['DEPT_ID'];
                            }
                            $billArea=trim($billArea);
                            $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                            $fsql->query2($sqlcom);
                            if ($fsql->num_rows() > 0) {
                                $fsql->next_record();
                                $gmanager = $fsql->f('gmanager');
                                $ceo = $fsql->f('ceo');
                                $chairman = $fsql->f('chairman');
                            }
                            
                            
                            if(in_array($billDept,$NetDeptI)&&$billArea&&$billProId&&false){
                                if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                    $flowMoneyArr['��Ŀ����']=0;//������
                                    $flowMoneyArr['ʡ�ݸ�����']=0;
                                }
                                $flowMoneyArr['��������']=0;
                                $flowMoneyArr['���ܾ���']=$ckMoney[4];
                                $sql="SELECT p.managerid  as proman  , r.managerid , r.mainmanagerid ,r.headId, d.vicemanager
                                  FROM
                                  oa_esm_project p
                                  left join oa_esm_office_range r on (p.officeId=r.officeId)
                                  left join department d on (p.deptid=d.dept_id)
                                  where p.id='".$billProId."' and r.id='$billArea'";
                                $fsql->query2($sql);
                                if($fsql->next_record( ))
                                {
                                    if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                        $ckarray[]=trim($fsql->f('proman'),',');
                                        $ckname[trim($fsql->f('proman'),',')]='��Ŀ����';
                                        $ckarray[]=trim($fsql->f('managerid'),',');
                                        $ckname[trim($fsql->f('managerid'),',')]='ʡ�ݸ�����';
                                    }
                                    $ckarray[]=trim($fsql->f('mainmanagerid'),',');
                                    $ckname[trim($fsql->f('mainmanagerid'),',')]='��������';
                                    $ckarray[]=trim($fsql->f('vicemanager'),',');
                                    $ckname[trim($fsql->f('vicemanager'),',')]='���ܾ���';
                                }
                                
                            }else{
                                //����
                                if(!in_array($billDept,$SaleDeptI)&&!in_array($billDept,$SaleDeptOtherI)&&false){//����
                                    
                                    $billCompanyTemp='';
                                    if($billDept=='123'){
                                        $billCompanyTemp='bx';
                                    }else{
                                        $billCompanyTemp='dl';
                                    }
                                    $fsql->query2("SELECT  group_concat( s.areanameid ) as anid  , group_concat(s.personid )  as pid
                                                       FROM oa_system_saleperson s
                                                       where   s.province like '%$billProvince%' AND                    IF(s.businessBelong,s.businessBelong='$billCompanyTemp','1=1')  AND s.isUse=0 AND  IF(s.UserIds,FIND_IN_SET('$CostMan',s.UserIds),(s.UserIds='' OR s.UserIds IS NULL)) ");
                                    if($fsql->next_record( ))
                                    {
                                        $flowMoneyArr['�������۾���']=0;
                                        $ckarray[]=trim($fsql->f('pid'),',');
                                        $ckname[trim($fsql->f('pid'),',')]='�������۾���';
                                        $flowMoneyArr['�������۸�����']=$ckMoneyI[3];
                                        $ckarray[]=trim($fsql->f('anid'),',');
                                        $ckname[trim($fsql->f('anid'),',')]='�������۸�����';
                                    }
                                }
                                elseif(!empty($billProvince)){//���̣���ʱ����
                                    $fsql->query2("SELECT managerId  FROM oa_esm_office_range where proName='$billProvince'");
                                    if($fsql->next_record( ))
                                    {
                                        if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                            $flowMoneyArr['������']=0;
                                            $ckarray[]=trim($fsql->f('managerId'),',');
                                            $ckname[trim($fsql->f('managerId'),',')]='������';
                                        }
                                    }
                                }
                                else{
                                    //���ž���
                                    if(!empty($billCom)){
                                        $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                    }else{
                                        $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                    }
                                    /*
                                     if(in_array($billDept,$NetDeptI)){
                                     $flowMoneyArr['���ž���']=$ckMoney[1];
                                     $flowMoneyArr['�����ܼ�']=20000;
                                     }*/
                                    
                                    if($fsql->next_record( )&&!in_array($billDept,$SaleDeptI))
                                    {
                                        if(trim($fsql->f('manager'),',')!=$CostMan&&trim($fsql->f('manager'),',')){
                                            $ckarray[]=trim($fsql->f('manager'),',');
                                            $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                        }
                                        
                                        if(trim($fsql->f('userid'),',')!=$CostMan&&trim($fsql->f('userid'),',')){
                                            $ckarray[]=trim($fsql->f('userid'),',');
                                            $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                        }
                                        
                                    }
                                }
                                // if(!in_array($billDept,$SaleDeptI)||in_array($billDept,$SaleDeptOtherI)){
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    
                                    
                                    if(trim($fsql->f('MajorId'),',')!=$CostMan&&$fsql->f('MajorId')){
                                        $ckarray[]=trim($fsql->f('MajorId'),',');
                                        $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    }
                                    
                                    if(in_array($CostMan,(array)explode(',',trim($fsql->f('MajorId'),',')))){
                                        if(array_search(trim($fsql->f('manager'),','), $ckarray)!==""){
                                            unset($flowMoneyArr['���Ÿ�����']);
                                            unset($ckarray[array_search(trim($fsql->f('manager'),','), $ckarray)]);
                                            unset($ckname[trim($fsql->f('manager'),',')]);
                                        }
                                        if(array_search(trim($fsql->f('MajorId'),','), $ckarray)!==""){
                                            unset($flowMoneyArr['���ž���']);
                                            unset($ckarray[array_search(trim($fsql->f('userid'),','), $ckarray)]);
                                            unset($ckname[trim($fsql->f('userid'),',')]);
                                        }
                                    }
                                    
                                    if(in_array($billDept,$NetDeptI)||in_array($billDept,$NetODeptI)){
                                        $flowMoneyArr['���ž���']=0;
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }
                                        if($CostMan!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
                                    
                                    if($billDept=='357'&&$costManDeptId!='357'){
                                        $ckarray[]='zequan.xu';
                                        $ckname['zequan.xu']='���ܾ���';
                                        
                                    }else{
                                        if(in_array($billDept,$YyDept)){
                                            $ckarray[]='chen.chen';
                                            $ckname['chen.chen']='���ĸ�����';
                                        }else if(in_array($billDept,$ZcDept)){
                                            $ckarray[]='tianlin.zhang';
                                            $ckname['tianlin.zhang']='���ĸ�����';
                                        }
                                        if(trim($fsql->f('ViceManager'),',')!=$CostMan&&$fsql->f('ViceManager')&&!in_array($CostMan,$directorI)){
                                            $ckarray[]=trim($fsql->f('ViceManager'),',');
                                            $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                            if($billCompany=='xs'){
                                                $flowMoneyArr['���ܾ���']=$ckMoneyI[4];
                                            }
                                        }
                                    }
                                    
                                    if(trim($fsql->f('ViceManager'),',')==$CostMan){
                                        $flowMoneyArr['�ܲ�']=0;
                                    }
                                    
                                    
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    
                                    
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                    
                                }
                                // }
                            }
                            
                            
                            if(!empty($flowMoney)&&$flowMoney!=' '){
                                $ckarray[]=$ceo;
                                $ckname[$ceo]='�ܲ�';
                                
                            }
                            if(in_array($CostMan,$directorI)){
                                unset($ckarray[array_search($ceo, $ckarray)]);
                                $flowMoneyArr['���³�']=0;
                                $ckarray[]=$chairman;
                                $ckname[$chairman]='���³�';
                            }
                            if($chairman==$CostMan){
                                $ckarray=array();
                                $ckname=array();
                            }
                            
                        }elseif($tmpdetailtype=='2'||$tmpdetailtype=='3'){//��ͬ��Ŀ���� + ��ǰ
                            if($billProType=='esm'&&$billArea){//����
                                
                                //$flowMoneyArr['��Ŀ����']=0;//������
                                //$flowMoneyArr['ʡ�ݸ�����']=0;
                                //$flowMoneyArr['���ܾ���']=$ckMoney[2];
                                //$flowMoneyArr['�ܾ���']=$ckMoney[2];
                                //$flowMoneyArr['�ܲ�']=$ckMoney[2];
                                $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                                $fsql->query2($sqlcom);
                                if ($fsql->num_rows() > 0) {
                                    $fsql->next_record();
                                    $gmanager = $fsql->f('gmanager');
                                    $ceo = $fsql->f('ceo');
                                    $chairman = $fsql->f('chairman');
                                }
                                
                                $sql="SELECT p.managerid  as proman  , r.managerid , r.mainmanagerid ,r.headId,d.DEPT_ID, d.vicemanager,d.generalManager
                              FROM
                              oa_esm_project p
                              left join oa_esm_office_range r on (p.officeId=r.officeId)
                              left join department d on (p.deptid=d.dept_id)
                              where p.id='".$billProId."' and r.id='$billArea'";
                                $fsql->query2($sql);
                                if($fsql->next_record( ))
                                {
                                    $flowMoneyArr['��������']=0;
                                    $flowMoneyArr['���Ÿ�����']=$ckMoneyI[2];
                                    $flowMoneyArr['���ž���']=0;
                                    $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                    $flowMoneyArr['���ܾ���']=$ckMoneyI[4];
                                    $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                                    $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
                                    if($tmpdetailtype == 2 && $isSaleDept==1) {
                                        unset($flowMoneyArr);
                                        $flowMoneyArr['���Ÿ�����']=0;
                                        $flowMoneyArr['���ž���']=0;
                                        $flowMoneyArr['�����ܼ�']=0;
                                        $flowMoneyArr['���ܾ���']=$ckMoneyI[2];
                                        $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                                        $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
                                    }
                                    if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                        $ckarray[]=trim($fsql->f('proman'),',');
                                        $ckname[trim($fsql->f('proman'),',')]='��Ŀ����';
                                        $ckarray[]=trim($fsql->f('managerid'),',');
                                        $ckname[trim($fsql->f('managerid'),',')]='ʡ�ݸ�����';
                                    }
                                    $ckarray[]=trim($fsql->f('mainmanagerid'),',');
                                    $ckname[trim($fsql->f('mainmanagerid'),',')]='��������';
                                    if($fsql->f('headId')){
                                        $flowMoneyArr['���Ÿ�����']=$ckMoneyI[2];
                                        
                                        if($tmpdetailtype == 2 && $isSaleDept==1) {
                                            $flowMoneyArr['���Ÿ�����']=0;
                                        }
                                        $ckarray[]=trim($fsql->f('headId'),',');
                                        $ckname[trim($fsql->f('headId'),',')]='���Ÿ�����';
                                    }else{
                                        $flowMoneyArr['��������']=$ckMoneyI[3];
                                        
                                        if($tmpdetailtype == 2 && $isSaleDept==1) {
                                            $flowMoneyArr['��������']=0;
                                        }
                                    }
                                    if(in_array($billDept,$NetDeptI)){
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                            
                                            if($tmpdetailtype == 2 && $isSaleDept==1) {
                                                $flowMoneyArr['�����ܼ�']=0;
                                            }
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                            if($tmpdetailtype == 2 && $isSaleDept==1) {
                                                $flowMoneyArr['�����ܼ�']=0;
                                            }
                                        }
                                        
                                        if($CostMan!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
									
									//��������Ǵ�����/֧��
                                    if($fsql->f('DEPT_ID') == '303'){
                                        $flowMoneyArr['���Ÿ�����']=$ckMoneyI[3];
                                        $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
                                    }
                                    
                                    $ckarray[]=trim($fsql->f('vicemanager'),',');
                                    $ckname[trim($fsql->f('vicemanager'),',')]='���ܾ���';
                                    
                                    if($billCompany=='xs'){
                                        $flowMoneyArr['���ܾ���']=$ckMoneyI[4];
                                    }
                                    if(trim($fsql->f('ViceManager'),',')==$CostMan){
                                        $flowMoneyArr['�ܲ�']=0;
                                    }
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                }
                                
                                if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                    $ckarray[]=$gmanager;
                                    $ckname[$gmanager]='�ܾ���';
                                }
                                
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                //print_r($ckarray);
                                
                            }elseif($billProType=='rd'||$tmpdetailtype=='3'){//�з�
                                
                                
                                //
                                if($tmpdetailtype=='4'){//��ǰ
                                    $flowMoneyArr['���۸�����']=0;
                                    $ckarray[]=trim($billBelonger,',');
                                    $ckname[trim($billBelonger,',')]='���۸�����';
                                }else{//��Ŀ����
                                    if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                        $flowMoneyArr['��Ŀ����']=0;
                                        $ckarray[]=trim($billProManager,',');
                                        $ckname[trim($billProManager,',')]='��Ŀ����';
                                    }
                                }
                                $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                                $fsql->query2($sqlcom);
                                if ($fsql->num_rows() > 0) {
                                    $fsql->next_record();
                                    $gmanager = $fsql->f('gmanager');
                                    $ceo = $fsql->f('ceo');
                                    $chairman = $fsql->f('chairman');
                                }
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('manager'),',')!=$CostMan&&trim($fsql->f('manager'),',')){
                                        $ckarray[]=trim($fsql->f('manager'),',');
                                        $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                    }
                                    
                                    if(trim($fsql->f('userid'),',')!=$CostMan&&trim($fsql->f('userid'),',')){
                                        $ckarray[]=trim($fsql->f('userid'),',');
                                        $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                    }
                                }
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    
                                    $ckarray[]=trim($fsql->f('MajorId'),',');
                                    $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    $ckarray[]=trim($fsql->f('ViceManager'),',');
                                    $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    
                                    if($billCompany=='xs'){
                                        $flowMoneyArr['���ܾ���']=$ckMoneyI[4];
                                    }
                                    
                                    if(trim($fsql->f('ViceManager'),',')==$CostMan){
                                        $flowMoneyArr['�ܲ�']=0;
                                    }
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                }
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                
                            }
                        }elseif($tmpdetailtype=='4'){// ��ǰ
                            
                            $flowMoneyArr['��������']=0;
                            $flowMoneyArr['���Ÿ�����']=$ckMoneyI[2];
                            $flowMoneyArr['���ž���']=0;
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[4];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
                            $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                            $fsql->query2($sqlcom);
                            if ($fsql->num_rows() > 0) {
                                $fsql->next_record();
                                $gmanager = $fsql->f('gmanager');
                                $ceo = $fsql->f('ceo');
                                $chairman = $fsql->f('chairman');
                            }
                            if(trim($billBelonger,',')!=$CostMan&&trim($billBelonger,',')&&$billChance){
                                //$flowMoneyArr['�̻�������']=0;
                                //$ckarray[]=trim($billBelonger,',');
                                //$ckname[trim($billBelonger,',')]='�̻�������';
                            }
                            
                            if(in_array($billDept,$SaleDeptI)||in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){//����
                                if(!empty($billProId)||!empty($billChance) ) {
                                    if(!empty($billProId)){
                                        $sqlb="SELECT  group_concat( s.areanameid ) as anid , group_concat( s.personid )  as pid
                                        FROM oa_system_saleperson s
                                        left join customer c on ( ( s.provinceid = c.provid or s.provinceid= 0 ) and find_in_set( c.typeone , s.customertype ) )
                                        left join oa_trialproject_trialproject t on (  c.id=t.customerid  )
                                        left join oa_esm_project p on (p.contractcode=t.projectcode  ),
                                        (
                                        SELECT  group_concat(s.businessBelong) AS com, group_concat( s.UserIds )  as usd
                                        FROM oa_system_saleperson s
                                        left join customer c on ( ( s.provinceid = c.provid or s.provinceid= 0 ) and find_in_set( c.typeone , s.customertype ) )
                                        left join oa_trialproject_trialproject t on (  c.id=t.customerid  )
                                        left join oa_esm_project p on (p.contractcode=t.projectcode  )
                                        where p.id='".$billProId."' AND s.isUse = 0 GROUP BY t.id
                                        ) a
                                        where
                                        p.id='".$billProId."' AND s.isUse = 0
                                        AND IF (FIND_IN_SET('$billCompany',a.com),FIND_IN_SET('$billCompany',s.businessBelong),(s.businessBelong = ''OR s.businessBelong IS NULL))
                                        AND IF (FIND_IN_SET('$CostMan', a.usd),FIND_IN_SET('$CostMan', s.UserIds),(s.UserIds = ''OR s.UserIds IS NULL))   ";
                                    }elseif(!empty($billChance)){
                                        $sqlb="SELECT   group_concat(s.areanameid) AS anid, group_concat(s.personid) AS pid
                                                FROM    oa_system_saleperson s LEFT JOIN oa_sale_chance t ON
                                                (s.provinceid = t.ProvinceId OR s.provinceid = 0)
                                                AND find_in_set(t.customerType, s.customertype)
                                                , (SELECT group_concat(s.businessBelong) AS com, group_concat( s.UserIds )  as usd,t.id
                                                 FROM   oa_system_saleperson s LEFT JOIN oa_sale_chance t ON
                                                (s.provinceid = t.ProvinceId OR s.provinceid = 0)
                                                AND find_in_set(t.customerType, s.customertype)
                                                WHERE   t.id = '".$billChance."' AND t.areaCode=s.salesAreaId  AND s.isUse = 0 GROUP BY t.id) a
                                                WHERE   t.id = '".$billChance."' AND t.areaCode=s.salesAreaId  AND IF (FIND_IN_SET('$billCompany',a.com),FIND_IN_SET('$billCompany',s.businessBelong),(s.businessBelong = ''OR s.businessBelong IS NULL )) AND s.isUse = 0
                                                AND IF (FIND_IN_SET('$CostMan', a.usd),FIND_IN_SET('$CostMan', s.UserIds),  (s.UserIds = ''OR s.UserIds IS NULL )) ";
                                    }
                                    $fsql->query2($sqlb);
                                    if($fsql->next_record( ))
                                    {
                                        if(trim($fsql->f('pid'),',')!=$CostMan&&trim($fsql->f('pid'),',')&&trim($fsql->f('anid'),',')!=$CostMan){
                                            $ckarray[]=trim($fsql->f('pid'),',');
                                            $ckname[trim($fsql->f('pid'),',')]='�������۾���';
                                        }
                                        if(trim($fsql->f('anid'),',')!=$CostMan&&trim($fsql->f('anid'),',')){
                                            $ckarray[]=trim($fsql->f('anid'),',');
                                            $ckname[trim($fsql->f('anid'),',')]='�������۸�����';
                                        }
                                    }/*
                                    if(in_array($CostMan,(array)explode(',',trim($fsql->f('pid'),',')))&&!in_array(trim($billBelonger,','),(array)explode(',',trim($fsql->f('pid'),',')))){
                                    if(array_search(trim($billBelonger,','), $ckarray)!==""){
                                    unset($flowMoneyArr['�̻�������']);
                                    unset($ckarray[array_search(trim($billBelonger,','), $ckarray)]);
                                    unset($ckname[trim($billBelonger,',')]);
                                    }
                                    }
                                    if(in_array($CostMan,(array)explode(',',trim($fsql->f('anid'),',')))){
                                    if(array_search(trim($billBelonger,','), $ckarray)!==""){
                                    unset($flowMoneyArr['�̻�������']);
                                    unset($ckarray[array_search(trim($billBelonger,','), $ckarray)]);
                                    unset($ckname[trim($billBelonger,',')]);
                                    }
                                    if(array_search(trim($fsql->f('pid'),','), $ckarray)!==""){
                                    unset($flowMoneyArr['�������۾���']);
                                    unset($ckarray[array_search(trim($fsql->f('pid'),','), $ckarray)]);
                                    unset($ckname[trim($fsql->f('pid'),',')]);
                                    }
                                    }*/
                                }else{
                                    $sqlStr="SELECT GROUP_CONCAT(s.personId) AS personIds, GROUP_CONCAT(s.areaNameId) AS areaNameIds
                                            FROM oa_system_saleperson s,
                                            (SELECT group_concat(e.businessBelong) AS com,group_concat(e.UserIds) AS usd
                                            FROM    oa_system_saleperson e
                                            WHERE    FIND_IN_SET('$billCusType',e.customerTypeName)
                                            AND (FIND_IN_SET('$billProvince',e.province) OR e.province='ȫ��')
                                            AND e.isUse = 0 AND e.salesAreaId='$salesAreaId') a
                                            WHERE FIND_IN_SET('$billCusType',s.customerTypeName)
                                            AND (FIND_IN_SET('$billProvince',s.province) OR s.province='ȫ��') AND s.isUse = 0 AND s.salesAreaId='$salesAreaId'
                                            AND IF (FIND_IN_SET('$billCompany',a.com),FIND_IN_SET('$billCompany',s.businessBelong),(s.businessBelong = ''OR s.businessBelong IS NULL))
                                            AND  IF(FIND_IN_SET('$CostMan',a.usd),FIND_IN_SET('$CostMan',s.UserIds),(s.UserIds = ''OR s.UserIds IS NULL))";
                                    //echo $sqlStr;
                                    $fsql->query2($sqlStr);
                                    if($fsql->next_record( ))
                                    {
                                        if(trim($fsql->f('personIds'),',')!=$CostMan&&trim($fsql->f('personIds'),',')&&!in_array($CostMan,(array)explode(',',trim($fsql->f('areaNameIds'),',')))){
                                            $ckarray[]=trim($fsql->f('personIds'),',');
                                            $ckname[trim($fsql->f('personIds'),',')]='�������۾���';
                                        }
                                        if(trim($fsql->f('areaNameIds'),',')!=$CostMan&&trim($fsql->f('areaNameIds'),',')){
                                            $ckarray[]=trim($fsql->f('areaNameIds'),',');
                                            $ckname[trim($fsql->f('areaNameIds'),',')]='�������۸�����';
                                        }
                                        /*
                                         if(in_array($CostMan,(array)explode(',',trim($fsql->f('personIds'),',')))&&!in_array(trim($billBelonger,','),(array)explode(',',trim($fsql->f('personIds'),',')))){
                                         if(array_search(trim($billBelonger,','), $ckarray)!==""){
                                         unset($flowMoneyArr['�̻�������']);
                                         unset($ckarray[array_search(trim($billBelonger,','), $ckarray)]);
                                         unset($ckname[trim($billBelonger,',')]);
                                         }
                                         }
                                         if(in_array($CostMan,(array)explode(',',trim($fsql->f('areaNameIds'),',')))){
                                         if(array_search(trim($billBelonger,','), $ckarray)!==""){
                                         unset($flowMoneyArr['�̻�������']);
                                         unset($ckarray[array_search(trim($billBelonger,','), $ckarray)]);
                                         unset($ckname[trim($billBelonger,',')]);
                                         }
                                         if(array_search(trim($fsql->f('pid'),','), $ckarray)!==""){
                                         unset($flowMoneyArr['�������۾���']);
                                         unset($ckarray[array_search(trim($fsql->f('pid'),','), $ckarray)]);
                                         unset($ckname[trim($fsql->f('pid'),',')]);
                                         }
                                         } */
                                    }
                                    
                                    
                                }
                                //if(!in_array($billDept,$SaleDeptI)){
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    if(in_array($billDept,$SaleDeptI)){
                                        $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                                        //$flowMoneyArr['���ܾ���']=0;
                                    }else{
                                        $ckarray[]=trim($fsql->f('MajorId'),',');
                                        $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    }
                                    
                                    $ckarray[]=trim($fsql->f('ViceManager'),',');
                                    $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                    
                                    
                                }
                                // }
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                if($isSpecial=='1'&&(!$ckname['feng.guo'])){
                                    $flowMoneyArr['���ܾ���']=0;
                                    $ckarray[]='feng.guo';
                                    $ckname['feng.guo']='���۸��ܾ���';
                                }
                            }elseif( !empty($billProId) &&$billArea){
                                if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                    $ckarray[]=trim($billProManager,',');
                                    $ckname[trim($billProManager,',')]='��Ŀ����';
                                }
                                $fsql->query2("SELECT mainManagerId,managerId,headId FROM oa_esm_office_range where id='$billArea'");
                                
                                while($fsql->next_record()){
                                    ///$specids .= $specids=="" ? towhere($fsql->f("managerCode")) : ",".towhere($fsql->f("managerCode"));
                                    if($fsql->f("managerId")){
                                        if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                            $ckarray[]=trim($fsql->f('managerId'),',');
                                            $ckname[trim($fsql->f('managerId'),',')]='ʡ�ݸ�����';
                                        }
                                    }
                                    if($fsql->f("mainManagerId")){
                                        $ckarray[]=trim($fsql->f('mainManagerId'),',');
                                        $ckname[trim($fsql->f('mainManagerId'),',')]='��������';
                                    }
                                    if($fsql->f("headId")){
                                        $ckarray[]=trim($fsql->f('headId'),',');
                                        $ckname[trim($fsql->f('headId'),',')]='���Ÿ�����';
                                    }
                                }
                                if(in_array($billDept,$NetDeptI)){
                                    if(in_array($billDept,$SMDeptI)){
                                        $flowMoneyArr['��������']=$ckMoneyI[2];
                                        $flowMoneyArr['���Ÿ�����']=$ckMoneyI[2];
                                    }else{
                                        $flowMoneyArr['��������']=0;
                                        $flowMoneyArr['���Ÿ�����']=$ckMoneyI[2];
                                    }
                                    if($CostMan!="zhongliang.hu"){
                                        $ckarray[]=trim('zhongliang.hu',',');
                                        $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                    }
                                }
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    $ckarray[]=trim($fsql->f('ViceManager'),',');
                                    $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    
                                }
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                            }else{
                                if(empty($billDept)){
                                    $billDept=$_SESSION['DEPT_ID'];
                                }
                                
                                //���ž���
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('manager'),',')!=$CostMan&&trim($fsql->f('manager'),',')&&trim($fsql->f('userid'),',')!=$CostMan){
                                        $flowMoneyArr['���Ÿ�����']=$ckMoneyI[2];
                                        $ckarray[]=trim($fsql->f('manager'),',');
                                        $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                    }
                                    
                                    if(trim($fsql->f('userid'),',')!=$CostMan&&trim($fsql->f('userid'),',')){
                                        $ckarray[]=trim($fsql->f('userid'),',');
                                        $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                    }
                                    
                                }
                                
                                
                                //if(!in_array($billDept,$SaleDeptI)){
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    
                                    $ckarray[]=trim($fsql->f('MajorId'),',');
                                    $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    
                                    if(in_array($billDept,$NetDeptI)){
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }
                                        if($CostMan!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
                                    $ckarray[]=trim($fsql->f('ViceManager'),',');
                                    $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                }
                                // }
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                            }
                            if($isSpecial=='1'&&(!$ckname['feng.guo'])){
                                $flowMoneyArr['���ܾ���']=0;
                                $ckarray[]='feng.guo';
                                $ckname['feng.guo']='���۸��ܾ���';
                            }
                        }
                        elseif($tmpdetailtype=='5'){//�ۺ�
                            
                            $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                            $fsql->query2($sqlcom);
                            if ($fsql->num_rows() > 0) {
                                $fsql->next_record();
                                $gmanager = $fsql->f('gmanager');
                                $ceo = $fsql->f('ceo');
                                $chairman = $fsql->f('chairman');
                            }
                            /*
                             $flowMoneyArr['�����쵼']=0;
                             $flowMoneyArr['���ž���']=$ckMoney[0];
                             $flowMoneyArr['�����ܼ�']=$ckMoney[1];
                             $flowMoneyArr['���ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܲ�']=$ckMoney[2];
                             if(in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){
                             $flowMoneyArr['�����쵼']=0;
                             $flowMoneyArr['���Ÿ�����']=0;
                             $flowMoneyArr['���ž���']=5000;
                             $flowMoneyArr['�����ܼ�']=5000;
                             $flowMoneyArr['���ܾ���']=5000;
                             $flowMoneyArr['�ܾ���']=200000;
                             $flowMoneyArr['�ܲ�']=$ckMoney[2];
                             }
                             */
                            if(in_array($billDept,$SaleDeptI)||in_array($billDept,$XSDeptI)){//����
                                $sql="SELECT  group_concat( s.areanameid ) as anid , group_concat( s.personid ) as pid
                                    FROM oa_contract_contract c left join  oa_system_saleperson s
                                    on ( ( s.provinceid = c.contractprovinceid or s.provinceid= 0 ) and find_in_set( c.customertype , s.customertype )  ),
                                    (SELECT  group_concat(s.businessBelong) AS com,group_concat(s.UserIds) AS usr,c.id
                                    FROM oa_contract_contract c left join  oa_system_saleperson s
                                    on ( ( s.provinceid = c.contractprovinceid or s.provinceid= 0 ) and find_in_set( c.customertype , s.customertype )  )
                                    where c.id='".$billConId."' AND c.areaCode=s.salesAreaId AND s.isUse = 0 GROUP BY c.id)  a
                                    where c.id='".$billConId."' AND c.areaCode=s.salesAreaId AND IF (FIND_IN_SET(c.signSubject,a.com),FIND_IN_SET(c.signSubject,s.businessBelong),(s.businessBelong='' OR s.businessBelong IS NULL)) AND s.isUse = 0
                                    AND IF (FIND_IN_SET('$CostMan', a.usr),FIND_IN_SET('$CostMan',s.UserIds),(s.UserIds='' OR s.UserIds IS NULL));  ";                          
								$fsql->query2($sql);
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('pid'),',')!=$CostMan&&trim($fsql->f('pid'),',')&&trim($fsql->f('anid'),',')!=$CostMan){
                                        //$flowMoneyArr['�������۾���']=0;
                                        $ckarray[]=trim($fsql->f('pid'),',');
                                        $ckname[trim($fsql->f('pid'),',')]='�������۾���';
                                    }
                                    if(trim($fsql->f('anid'),',')!=$CostMan&&trim($fsql->f('anid'),',')){
                                        //$flowMoneyArr['�������۸�����']=5000;
                                        $ckarray[]=trim($fsql->f('anid'),',');
                                        $ckname[trim($fsql->f('anid'),',')]='�������۸�����';
                                    }
                                    $fsql->query2("select d.MajorId , d.ViceManager , d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                    if($fsql->next_record( ))
                                    {
                                        
                                        if(in_array($billDept,$SaleDeptI)){
                                            //$flowMoneyArr['�����ܼ�']=10000;
                                            //$flowMoneyArr['���ܾ���']=0;
                                        }
                                        //$ckarray[]=trim($fsql->f('MajorId'),',');
                                        //$ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                        $ckarray[]=trim($fsql->f('ViceManager'),',');
                                        $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                        
                                        if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                            $ckarray[]=trim($fsql->f('generalManager'),',');
                                            $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                        }
                                        if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                            $ckarray[]=$gmanager;
                                            $ckname[$gmanager]='�ܾ���';
                                        }
                                    }
                                }
                                
                            }
                            else{//����
                                //���ž���
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('manager'),',')!=$CostMan&&trim($fsql->f('manager'),',')){
                                        $flowMoneyArr['���Ÿ�����']=0;
                                        $ckarray[]=trim($fsql->f('manager'),',');
                                        $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                    }
                                    
                                    if(trim($fsql->f('userid'),',')!=$CostMan&&trim($fsql->f('userid'),',')){
                                        
                                        if(in_array($billDept,$NetDeptI)){
                                            $flowMoneyArr['���ž���']=0;
                                        }
                                        $ckarray[]=trim($fsql->f('userid'),',');
                                        $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                    }
                                }
                                
                                $fsql->query2("select d.MajorId , d.ViceManager , d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    
                                    $ckarray[]=trim($fsql->f('MajorId'),',');
                                    $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    
                                    if(in_array($billDept,$NetDeptI)){
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }
                                        if($CostMan!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
                                    
                                    
                                    $ckarray[]=trim($fsql->f('ViceManager'),',');
                                    $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                    
                                }
                            }
                            
                            if(!empty($flowMoney)&&$flowMoney!=' '){
                                $ckarray[]=$ceo;
                                $ckname[$ceo]='�ܲ�';
                                
                            }
                            if(in_array($CostMan,$directorI)){
                                unset($ckarray[array_search($ceo, $ckarray)]);
                                $flowMoneyArr['���³�']=0;
                                $ckarray[]=$chairman;
                                $ckname[$chairman]='���³�';
                            }
                            if($chairman==$CostMan){
                                $ckarray=array();
                                $ckname=array();
                            }
                            
                        }
                        if(!empty($ckarraytype)){
                            $ckarray=array_merge($ckarraytype,$ckarray);
                        }
                        //print_r($ckarray);
                        $ckarray = array_diff($ckarray, array(null,''));
                        $ckarray = array_unique($ckarray);
                        $cki=0;
                        $ckf=0;
                        $ckp=0;
                        //===========������������Ŀ���� begin
                        foreach($ckarray as $val){
                            $cki++;
                            if(strripos($val,$billCostMan)!==false){
                                $ckf=1;
                                $ckp=$cki;
                            }
                            
                        }
                        $ckarray = array_values($ckarray);
                        if($ckf==1&&$ckp!=0&&count($ckarray)!=1 ){
                            for($i1 = 0; $i1<$ckp; $i1++) {
                                if($ckname[$ckarray[$i1]] == '��Ŀ����' && strripos($ckarray[$i1],$billCostMan) !== false) {
                                    unset($ckarray[$i1]);
                                }else if($ckname[$ckarray[$i1]] != '��Ŀ����') {
                                    unset($ckarray[$i1]);
                                }
                            }
                            //$ckarray=array_slice($ckarray,$ckp);
                        }
                        //===========������������Ŀ���� end
                        //print_r($ckarray);
                        $ckarray = array_values($ckarray);
                        $extStep = $fsql->getrow("select i.remarks,i.config_itemSub3,i.config_item3 from cost_summary_list c inner join cost_detail d on (c.ID = '".$billId."' and c.BillNo = d.BillNo) inner join oa_system_configurator_item i on (i.groupBelongName = '����С������������' and config_itemSub4 = 1 and i.config_itemSub1 = c.DetailType and find_in_set(d.CostTypeID,i.config_itemSub2))");
                        if($extStep) {
                            $flowMoneyArr[$extStep['remarks']] = 0;
                            array_unshift($ckarray,$extStep['config_itemSub3']);
                            $ckname[$extStep['config_itemSub3']]=$extStep['remarks'];
                        }
                        $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                        $PRCS_NAME=$ckname[$ckarray[0]];
                        $ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
                        $extStep2 = $fsql->getrow("select i.remarks,i.config_itemSub3,i.config_item3 from cost_summary_list c inner join cost_detail d on (c.ID = '".$billId."' and c.BillNo = d.BillNo) inner join oa_system_configurator_item i on (i.groupBelongName = '����С������������' and config_itemSub4 = 2 and i.config_itemSub1 = c.DetailType and find_in_set(d.CostTypeID,i.config_itemSub2))");
                        if($extStep2) {
                            $hasExtStep2 = 1;
                            $extStep2Prcsname = $extStep2['remarks'];
                            $extStep2Prcsuser = $extStep2['config_item3'];
                            $extStep2PrcsuserId = $extStep2['config_itemSub3'];
                        }
                    }
                }else{
                    continue;
                }
            }//�±���
			
			
			 //�ر�����
            if($PRCS_SPEC_ARR[$i]=='@tbsx')
            {
                //$billUsers=explode(',', $billUser);
                if(!empty($billId)){
                    //$ckMoney='500,5000,100000';
                    $ckMoneys='500,1000,5000,10000,20000,50000,100000,200000,500000';
                    $ckarray=array();
                    $ckname=array();
                    $flowMoneyArr=array();
                    //$ckMoney=explode(',', $ckMoney);
                    $ckMoneyI=explode(',', $ckMoneys);
                    $xsbm = $fsql->getrow("select belongDeptIds from oa_system_configurator_item where groupBelongName = '���۲���'");
                    $serviceMajorId = $fsql->getrow("select GROUP_CONCAT(config_itemSub2) as 'majorids' from oa_system_configurator_item where groupBelongName = '�ܼ�����' and config_item1 = '�����ܼ�'");
                    $sessionDept = $_SESSION['DEPT_ID'];
                    $isSaleDept = 0;
                    if(in_array($sessionDept, explode(',',$xsbm['belongDeptIds'])) || in_array($_SESSION['USER_ID'], explode(',',$serviceMajorId['majorids']))) {
                        $isSaleDept = 1;
                    }
                    $fsql->query2("SELECT a.applyUserId,b.detailtype ,b.costBelongDeptId , b.costBelongComId , b.projectId , b.projectType
                    	, b.proManagerId ,  b.contractid ,  b.costbelongerid ,  b.province ,  b.city, b.chanceid ,  b.customertype ,
             			b.costBelongerId,b.salesAreaId
						FROM oa_general_specialapply  a
						LEFT JOIN oa_general_specialcostbelong b  ON a.id=b.mainId
                    	where a.id='".$billId."' ");
                    if($fsql->next_record( ))
                    {
                        $CostMan=$fsql->f('applyUserId');
                        $tmpdetailtype=$fsql->f('detailtype');
                        $billDept=$fsql->f('costBelongDeptId');
                        $billCom=$fsql->f('costBelongComId');
                        $billProId=$fsql->f('projectId');
                        $billProType=$fsql->f('projectType');
                        $billProManager=$fsql->f('proManagerId');
                        $billConId=$fsql->f('contractid');
                        $billBelonger=$fsql->f('costbelongerid');
                        $billProvince=$fsql->f('province');
                        $billCity=$fsql->f('city');
                        $billChance=$fsql->f('chanceid');
                        $billCusType=$fsql->f('customertype');
                        $isproject= $fsql->f('isproject');
                        $billBxno = $fsql->f('billno');
                        $billCostMan=$fsql->f('costBelongerId');
                        $salesAreaId=$fsql->f('salesAreaId');
                        
                        if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                            $flowMoneyArr['��Ŀ����']=0;//������
                        }
                        $flowMoneyArr['ʡ�ݸ�����']=0;
                        $flowMoneyArr['�������۾���']=0;
                        $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                        $flowMoneyArr['���Ÿ�����']=0;
                        $flowMoneyArr['���ž���']=$ckMoneyI[0];
                        $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                        $flowMoneyArr['���ܾ���']=$ckMoneyI[6];
                        $flowMoneyArr['�ܾ���']=$ckMoneyI[6];
                        $flowMoneyArr['�ܲ�']=$ckMoneyI[6];
                        
                        if(in_array($billDept,$DxDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                            }
                            $flowMoneyArr['ʡ�ݸ�����']=0;
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                            $flowMoneyArr['���ž���']=0;
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[7];
                        }
                        if(in_array($billDept,$NetDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                            }
                            $flowMoneyArr['ʡ�ݸ�����']=0;
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                            $flowMoneyArr['���ž���']=0;
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[5];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[6];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[6];
                        }
                        if(in_array($billDept,$SaleDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                            }
                            $flowMoneyArr['ʡ�ݸ�����']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=$ckMoneyI[2];
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=0;
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[6];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[6];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[6];
                        }
                        
                        if(in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){
                            if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                $flowMoneyArr['��Ŀ����']=0;//������
                            }
                            $flowMoneyArr['ʡ�ݸ�����']=0;
                            $flowMoneyArr['�������۾���']=$ckMoneyI[0];
                            $flowMoneyArr['�������۸�����']=$ckMoneyI[3];
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=$ckMoneyI[1];
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[3];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[5];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[7];
                        }
                        
                        
                        if($tmpdetailtype == 1 && (in_array($billDept,$YyDept) || in_array($billDept,$ZcDept))){
                            unset($flowMoneyArr);
                            $ckMoney='0,500,5000,20000,100000,1000000';
                            $ckMoneyI=explode(',', $ckMoney);
                            $flowMoneyArr['��������']=0;
                            $flowMoneyArr['�������۾���']=0;
                            $flowMoneyArr['�������۸�����']=0;
                            $flowMoneyArr['���Ÿ�����']=0;
                            $flowMoneyArr['���ž���']=$ckMoneyI[1];
                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                            $flowMoneyArr['���ĸ�����']=$ckMoneyI[3];
                            $flowMoneyArr['���ܾ���']=$ckMoneyI[4];
                            $flowMoneyArr['�ܾ���']=$ckMoneyI[4];
                            $flowMoneyArr['�ܲ�']=$ckMoneyI[5];
                        }
                        
                        $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                        $fsql->query2($sqlcom);
                        if ($fsql->num_rows() > 0) {
                            $fsql->next_record();
                            $gmanager = $fsql->f('gmanager');
                            $ceo = $fsql->f('ceo');
                            $chairman = $fsql->f('chairman');
                        }
                        
                        if($tmpdetailtype=='1'){//����
                            /*$flowMoneyArr['���Ÿ�����']=0;
                             $flowMoneyArr['���ž���']=$ckMoney[0];
                             $flowMoneyArr['�����ܼ�']=$ckMoney[1];
                             $flowMoneyArr['���ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܲ�']=$ckMoney[2];
                             */
                            if(empty($billDept)){
                                $billDept=$_SESSION['DEPT_ID'];
                            }
                            $billArea=trim($billArea);
                            //���ž���
                            
                            $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCom' ";
                            $fsql->query2($sqlcom);
                            if ($fsql->num_rows() > 0) {
                                $fsql->next_record();
                                $gmanager = $fsql->f('gmanager');
                                $ceo = $fsql->f('ceo');
                                $chairman = $fsql->f('chairman');
                            }
                            
                            
                            if(!empty($billCom)){
                                $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                            }else{
                                $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                            }
                            if($fsql->next_record( )&&!in_array($billDept,$SaleDeptI))
                            {
                                if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
                                    $flowMoneyArr['���Ÿ�����']=0;
                                    $ckarray[]=trim($fsql->f('manager'),',');
                                    $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                }
                                
                                if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
                                    $ckarray[]=trim($fsql->f('userid'),',');
                                    $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                }
                                if(strpos(trim($fsql->f('userid'),','), $_SESSION['USER_ID'])!==false)
                                {
                                    $ckarray=array();
                                    $ckname=array();
                                    $flowMoneyArr['���ž���']=0;
                                }
                            }
                            
                            $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                            if($fsql->next_record( ))
                            {
                                
                                
                                if(trim($fsql->f('MajorId'),',')!=$_SESSION['USER_ID']&&$fsql->f('MajorId')){
                                    $ckarray[]=trim($fsql->f('MajorId'),',');
                                    $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                }
                                if(strpos(trim($fsql->f('MajorId'),','), $_SESSION['USER_ID'])!==false)
                                {
                                    $ckarray=array();
                                    $ckname=array();
                                    $flowMoneyArr['�����ܼ�']=0;
                                }
                                
                                if(in_array($billDept,$NetDeptI)){
                                    $flowMoneyArr['���ž���']=0;
                                    if(in_array($billDept,$SMDeptI)){
                                        $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                    }else{
                                        $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                    }
                                    if($_SESSION['USER_ID']!="zhongliang.hu"){
                                        $ckarray[]=trim('zhongliang.hu',',');
                                        $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                    }
                                }
                                if(in_array($billDept,$YyDept)){
                                    $ckarray[]='chen.chen';
                                    $ckname['chen.chen']='���ĸ�����';
                                }else if(in_array($billDept,$ZcDept)){
                                    $ckarray[]='tianlin.zhang';
                                    $ckname['tianlin.zhang']='���ĸ�����';
                                }
                                if($billDept=='357'&&$_SESSION['DEPT_ID']!='357'){
                                    $ckarray[]='zequan.xu';
                                    $ckname['zequan.xu']='���ܾ���';
                                    
                                }else{
                                    if(trim($fsql->f('ViceManager'),',')!=$_SESSION['USER_ID']&&$fsql->f('ViceManager')&&!in_array($CostMan,$directorI)){
                                        $ckarray[]=trim($fsql->f('ViceManager'),',');
                                        $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                        if($billCom=='xs'||$billCom=='jk'){
                                            $flowMoneyArr['���ܾ���']=$ckMoneyI[5];
                                        }
                                    }
                                    if(strpos(trim($fsql->f('ViceManager'),','), $_SESSION['USER_ID'])!==false){
                                        $ckarray=array();
                                        $ckname=array();
                                        $flowMoneyArr['���ܾ���']=0;
                                    }
                                }
                                
                                if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                    $ckarray[]=trim($fsql->f('generalManager'),',');
                                    $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    
                                }
                                
                                if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                    $ckarray[]=$gmanager;
                                    $ckname[$gmanager]='�ܾ���';
                                }
                            }
                            
                            if(!empty($flowMoney)&&$flowMoney!=' '){
                                $ckarray[]=$ceo;
                                $ckname[$ceo]='�ܲ�';
                                
                            }
                            if(in_array($CostMan,$directorI)){
                                unset($ckarray[array_search($ceo, $ckarray)]);
                                $flowMoneyArr['���³�']=0;
                                $ckarray[]=$chairman;
                                $ckname[$chairman]='���³�';
                            }
                            if($chairman==$CostMan){
                                $ckarray=array();
                                $ckname=array();
                            }
                            
                        }
                        elseif($tmpdetailtype=='2'||$tmpdetailtype=='3'){//��ͬ��Ŀ���� + ��ǰ
                            if($billProType=='esm'){//����
                                
                                if($tmpdetailtype == 2 && $isSaleDept==1) {
                                    $flowMoneyArr['ʡ�ݸ�����']=0;
                                    $flowMoneyArr['��������']=0;
                                    $flowMoneyArr['�������۾���']=0;
                                    $flowMoneyArr['�������۸�����']=0;
                                    $flowMoneyArr['���Ÿ�����']=0;
                                    $flowMoneyArr['���ž���']=0;
                                    $flowMoneyArr['�����ܼ�']=0;
                                    $flowMoneyArr['���ܾ���']=$ckMoneyI[2];
                                    $flowMoneyArr['�ܾ���']=$ckMoneyI[7];
                                    $flowMoneyArr['�ܲ�']=$ckMoneyI[8];
                                }
                                /* $flowMoneyArr['��Ŀ����']=0;//������
                                 $flowMoneyArr['���Ÿ�����']=0;
                                 $flowMoneyArr['���ž���']=$ckMoney[0];
                                 $flowMoneyArr['�����ܼ�']=$ckMoney[1];
                                 $flowMoneyArr['���ܾ���']=$ckMoney[2];
                                 $flowMoneyArr['�ܲ�']=$ckMoney[2];
                                 */
                                if(empty($billDept)){
                                    $billDept=$_SESSION['DEPT_ID'];
                                }
                                if($billProManager&&$_SESSION['USER_ID']!=$billProManager){
                                    if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                        $ckarray[]=trim($billProManager,',');
                                        $ckname[trim($billProManager,',')]='��Ŀ����';
                                    }
                                }
                                $billArea=trim($billArea);
                                //���ž���
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                if($fsql->next_record( )&&!in_array($billDept,$SaleDeptI))
                                {
                                    if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                        if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
                                            $ckarray[]=trim($fsql->f('manager'),',');
                                            $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                        }
                                        
                                        if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
                                            $ckarray[]=trim($fsql->f('userid'),',');
                                            $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                        }
                                    }
                                }
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    if($fsql->f('MajorId')){
                                        $ckarray[]=trim($fsql->f('MajorId'),',');
                                        $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    }
                                    
                                    if(in_array($_SESSION['USER_ID'],(array)explode(',',trim($fsql->f('MajorId'),',')))){
                                        if(array_search(trim($fsql->f('manager'),','), $ckarray)!==""){
                                            unset($flowMoneyArr['���Ÿ�����']);
                                            unset($ckarray[array_search(trim($fsql->f('manager'),','), $ckarray)]);
                                            unset($ckname[trim($fsql->f('manager'),',')]);
                                        }
                                        if(array_search(trim($fsql->f('MajorId'),','), $ckarray)!==""){
                                            unset($flowMoneyArr['���ž���']);
											//��Ӧunset userid  ��Ӧ��unset MajorId
                                            //unset($ckarray[array_search(trim($fsql->f('userid'),','), $ckarray)]);
                                            //unset($ckname[trim($fsql->f('userid'),',')]);
											unset($ckarray[array_search(trim($fsql->f('MajorId'),','), $ckarray)]);
                                            unset($ckname[trim($fsql->f('MajorId'),',')]);
                                        }
                                    }
                                    if(in_array($billDept,$NetDeptI)){
                                        $flowMoneyArr['���ž���']=0;
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                            if($tmpdetailtype == 2 && $isSaleDept==1) {
                                                $flowMoneyArr['�����ܼ�']=0;
                                            }
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                            if($tmpdetailtype == 2 && $isSaleDept==1) {
                                                $flowMoneyArr['�����ܼ�']=0;
                                            }
                                        }
                                        if($_SESSION['USER_ID']!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
                                    
                                    if(trim($fsql->f('ViceManager'),',')!=$_SESSION['USER_ID']&&$fsql->f('ViceManager')){
                                        $ckarray[]=trim($fsql->f('ViceManager'),',');
                                        $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    }
                                    
                                    if(trim($fsql->f('ViceManager'),',')==$_SESSION['USER_ID']){
                                        $flowMoneyArr['�ܲ�']=0;
                                    }
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    
                                    
                                }
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]='danian.zhu';
                                    $ckname['danian.zhu']='�ܲ�';
                                    
                                }
                                if('danian.zhu'==$CostMan){
                                    $ckarray[]='joe.wang';
                                    $ckname['joe.wang']='���³�';
                                }
                                if('joe.wang'==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                //var_dump($flowMoney);die();
                                
                            }
							elseif($billProType=='rd'||$tmpdetailtype=='3'){//�з�
                                
                                /*$flowMoneyArr['���ž���']=$ckMoney[0];
                                 $flowMoneyArr['�����ܼ�']=$ckMoney[1];
                                 $flowMoneyArr['���ܾ���']=$ckMoney[2];
                                 $flowMoneyArr['�ܲ�']=$ckMoney[2];
                                 */
                                //
                                if($tmpdetailtype=='4'){//��ǰ
                                    //$flowMoneyArr['���۸�����']=0;
                                    $ckarray[]=trim($billBelonger,',');
                                    $ckname[trim($billBelonger,',')]='���۸�����';
                                }else{//��Ŀ����
                                    //$flowMoneyArr['��Ŀ����']=0;
                                    if($billProManager&&$_SESSION['USER_ID']!=$billProManager){
                                        if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                            $ckarray[]=trim($billProManager,',');
                                            $ckname[trim($billProManager,',')]='��Ŀ����';
                                        }
                                    }
                                }
                                
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
                                        //$flowMoneyArr['���Ÿ�����']=0;
                                        $ckarray[]=trim($fsql->f('manager'),',');
                                        $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                    }
                                    
                                    if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
                                        //$flowMoneyArr['���ž���']=0;
                                        $ckarray[]=trim($fsql->f('userid'),',');
                                        $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                        
                                    }
                                }
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    
                                    if(trim($fsql->f('MajorId'),',')!=$_SESSION['USER_ID']){
                                        $ckarray[]=trim($fsql->f('MajorId'),',');
                                        $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    }
                                    if(trim($fsql->f('ViceManager'),',')!=$_SESSION['USER_ID']){
                                        $ckarray[]=trim($fsql->f('ViceManager'),',');
                                        $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    }
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                }
                                
                                if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                    $ckarray[]=$gmanager;
                                    $ckname[$gmanager]='�ܾ���';
                                }
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                //print_r($ckarray);
                            }
                        }
						elseif($tmpdetailtype=='4'){// ��ǰ
                            
                            $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                            $fsql->query2($sqlcom);
                            if ($fsql->num_rows() > 0) {
                                $fsql->next_record();
                                $gmanager = $fsql->f('gmanager');
                                $ceo = $fsql->f('ceo');
                                $chairman = $fsql->f('chairman');
                            }
                            /*
                             $flowMoneyArr['�������۾���']=$ckMoney[0];
                             $flowMoneyArr['�������۸�����']=$ckMoney[1];
                             $flowMoneyArr['���ž���']=$ckMoney[0];
                             $flowMoneyArr['�����ܼ�']=$ckMoney[1];
                             $flowMoneyArr['���ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܲ�']=$ckMoney[2];
                             if(in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){
                             $flowMoneyArr['���Ÿ�����']=0;
                             $flowMoneyArr['�������۾���']=$ckMoney[0];
                             $flowMoneyArr['�������۸�����']=$ckMoney[1];
                             $flowMoneyArr['���ž���']=$ckMoney[0];
                             $flowMoneyArr['�����ܼ�']=5000;
                             $flowMoneyArr['���ܾ���']=50000;
                             $flowMoneyArr['�ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܲ�']=$ckMoney[2];
                             }
                             */
                            if(in_array($billDept,$SaleDeptI)||in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){//����
                                if(!empty($billProId)||!empty($billChance) ) {
                                    $billCompanyTemp='';
                                    if($billDept=='123'){
                                        $billCompanyTemp='bx';
                                    }else{
                                        $billCompanyTemp='dl';
                                    }
                                    if(!empty($billProId)){
                                        $sqlb="SELECT  group_concat( s.areanameid ) as anid , group_concat( s.personid )  as pid
											FROM oa_system_saleperson s
											left join customer c on ( ( s.provinceid = c.provid or s.provinceid= 0 ) and find_in_set( c.typeone , s.customertype ) )
											left join oa_trialproject_trialproject t on (  c.id=t.customerid  )
											left join oa_esm_project p on (p.contractcode=t.projectcode  ),
											(
											SELECT  group_concat(s.businessBelong) AS com, group_concat( s.UserIds )  as usd
											FROM oa_system_saleperson s
											left join customer c on ( ( s.provinceid = c.provid or s.provinceid= 0 ) and find_in_set( c.typeone , s.customertype ) )
											left join oa_trialproject_trialproject t on (  c.id=t.customerid  )
											left join oa_esm_project p on (p.contractcode=t.projectcode  )
											where p.id='".$billProId."' AND s.isUse = 0 GROUP BY t.id
											) a
											where
											p.id='".$billProId."' AND s.isUse = 0
											AND IF (FIND_IN_SET('$billCompany',a.com),FIND_IN_SET('$billCompany',s.businessBelong),(s.businessBelong = ''OR s.businessBelong IS NULL	))
											AND IF (FIND_IN_SET('$CostMan', a.usd),FIND_IN_SET('$CostMan', s.UserIds),(s.UserIds = ''OR s.UserIds IS NULL	))   ";
                                    }elseif(!empty($billChance)){
                                        $sqlb="SELECT 	group_concat(s.areanameid) AS anid,	group_concat(s.personid) AS pid
													FROM	oa_system_saleperson s LEFT JOIN oa_sale_chance t ON
													(s.provinceid = t.ProvinceId OR s.provinceid = 0)
													AND find_in_set(t.customerType, s.customertype)
													, (SELECT group_concat(s.businessBelong) AS com, group_concat( s.UserIds )  as usd,t.id
													 FROM 	oa_system_saleperson s LEFT JOIN oa_sale_chance t ON
													(s.provinceid = t.ProvinceId OR s.provinceid = 0)
													AND find_in_set(t.customerType, s.customertype)
													WHERE	t.id = '".$billChance."' AND t.areaCode=s.salesAreaId  AND s.isUse = 0 GROUP BY t.id) a
													WHERE	t.id = '".$billChance."' AND t.areaCode=s.salesAreaId  AND IF (FIND_IN_SET('$billCompany',a.com),FIND_IN_SET('$billCompany',s.businessBelong),(s.businessBelong = ''OR s.businessBelong IS NULL	)) AND s.isUse = 0
													AND IF (FIND_IN_SET('$CostMan', a.usd),FIND_IN_SET('$CostMan', s.UserIds),	(s.UserIds = ''OR s.UserIds IS NULL	)) ";
                                    }
                                    $fsql->query2($sqlb);
                                    if($fsql->next_record( ))
                                    {
                                        if(trim($fsql->f('pid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('pid'),',')&&trim($fsql->f('anid'),',')!=$_SESSION['USER_ID']){
                                            
                                            $ckarray[]=trim($fsql->f('pid'),',');
                                            $ckname[trim($fsql->f('pid'),',')]='�������۾���';
                                        }
                                        if(trim($fsql->f('anid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('anid'),',')){
                                            
                                            $ckarray[]=trim($fsql->f('anid'),',');
                                            $ckname[trim($fsql->f('anid'),',')]='�������۸�����';
                                        }
                                    }
                                    if(in_array($_SESSION['USER_ID'],(array)explode(',',trim($fsql->f('pid'),',')))&&!in_array(trim($billBelonger,','),(array)explode(',',trim($fsql->f('pid'),',')))){
                                        if(array_search(trim($billBelonger,','), $ckarray)!==""){
                                            unset($flowMoneyArr['�̻�������']);
                                            unset($ckarray[array_search(trim($billBelonger,','), $ckarray)]);
                                            unset($ckname[trim($billBelonger,',')]);
                                        }
                                    }
                                    if(in_array($_SESSION['USER_ID'],(array)explode(',',trim($fsql->f('anid'),',')))){
                                        if(array_search(trim($billBelonger,','), $ckarray)!==""){
                                            unset($flowMoneyArr['�̻�������']);
                                            unset($ckarray[array_search(trim($billBelonger,','), $ckarray)]);
                                            unset($ckname[trim($billBelonger,',')]);
                                        }
                                        if(array_search(trim($fsql->f('pid'),','), $ckarray)!==""){
                                            unset($flowMoneyArr['�������۾���']);
                                            unset($ckarray[array_search(trim($fsql->f('pid'),','), $ckarray)]);
                                            unset($ckname[trim($fsql->f('pid'),',')]);
                                        }
                                    }
                                }else{
                                    $sqlStr="SELECT GROUP_CONCAT(s.personId) AS personIds, GROUP_CONCAT(s.areaNameId) AS areaNameIds
											FROM oa_system_saleperson s,
											(SELECT group_concat(e.businessBelong) AS com,group_concat(e.UserIds) AS usd
											FROM 	oa_system_saleperson e
											WHERE	 FIND_IN_SET('$billCusType',e.customerTypeName)
											AND (FIND_IN_SET('$billProvince',e.province) OR e.province='ȫ��')
											AND e.isUse = 0 AND e.salesAreaId='$salesAreaId') a
											WHERE FIND_IN_SET('$billCusType',s.customerTypeName)
											AND (FIND_IN_SET('$billProvince',s.province) OR s.province='ȫ��') AND s.isUse = 0 AND s.salesAreaId='$salesAreaId'
											AND IF (FIND_IN_SET('$billCompany',a.com),FIND_IN_SET('$billCompany',s.businessBelong),(s.businessBelong = ''OR s.businessBelong IS NULL))
											AND  IF(FIND_IN_SET('$CostMan',a.usd),FIND_IN_SET('$CostMan',s.UserIds),(s.UserIds = ''OR s.UserIds IS NULL))";
                                    $fsql->query2($sqlStr);
                                    if($fsql->next_record( ))
                                    {
                                        if(trim($fsql->f('personIds'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('personIds'),',')&&!in_array($_SESSION['USER_ID'],(array)explode(',',trim($fsql->f('areaNameIds'),',')))){
                                            
                                            $ckarray[]=trim($fsql->f('personIds'),',');
                                            $ckname[trim($fsql->f('personIds'),',')]='�������۾���';
                                        }
                                        if(trim($fsql->f('areaNameIds'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('areaNameIds'),',')){
                                            
                                            $ckarray[]=trim($fsql->f('areaNameIds'),',');
                                            $ckname[trim($fsql->f('areaNameIds'),',')]='�������۸�����';
                                        }
                                    }
                                    
                                    
                                }
                                //if(!in_array($billDept,$SaleDeptI)){
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    /*
                                     if(in_array($billDept,$SaleDeptI)){
                                     $flowMoneyArr['�������۸�����']=5000;
                                     //$flowMoneyArr['���ܾ���']=0;
                                     }*/
                                    if(trim($fsql->f('MajorId'),',')!=$_SESSION['USER_ID']){
                                        $ckarray[]=trim($fsql->f('MajorId'),',');
                                        $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    }
                                    if(trim($fsql->f('ViceManager'),',')!=$_SESSION['USER_ID']){
                                        $ckarray[]=trim($fsql->f('ViceManager'),',');
                                        $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    }
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                    
                                    
                                }
                                // }
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                
                            }elseif( !empty($billProId)){
                                /*$flowMoneyArr['��Ŀ����']=0;
                                 $flowMoneyArr['ʡ�ݸ�����']=0;
                                 $flowMoneyArr['��������']=0;
                                 $flowMoneyArr['���Ÿ�����']=$ckMoney[0];
                                 $flowMoneyArr['��Ŀ����']=0;
                                 */
                                if($billProManager&&$_SESSION['USER_ID']!=$billProManager){
                                    if(!($tmpdetailtype == 2 && $isSaleDept==1)) {
                                        $ckarray[]=trim($billProManager,',');
                                        $ckname[trim($billProManager,',')]='��Ŀ����';
                                    }
                                }
                                
                                
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
                                        $flowMoneyArr['���Ÿ�����']=0;
                                        $ckarray[]=trim($fsql->f('manager'),',');
                                        $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                    }
                                    
                                    if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
                                        $flowMoneyArr['���ž���']=0;
                                        $ckarray[]=trim($fsql->f('userid'),',');
                                        $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                        
                                    }
                                }
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    
                                    
                                    if(trim($fsql->f('MajorId'),',')!=$_SESSION['USER_ID']&&$fsql->f('MajorId')){
                                        $ckarray[]=trim($fsql->f('MajorId'),',');
                                        $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    }
                                    
                                    if(in_array($_SESSION['USER_ID'],(array)explode(',',trim($fsql->f('MajorId'),',')))){
                                        if(array_search(trim($fsql->f('manager'),','), $ckarray)!==""){
                                            unset($flowMoneyArr['���Ÿ�����']);
                                            unset($ckarray[array_search(trim($fsql->f('manager'),','), $ckarray)]);
                                            unset($ckname[trim($fsql->f('manager'),',')]);
                                        }
                                        if(array_search(trim($fsql->f('MajorId'),','), $ckarray)!==""){
                                            unset($flowMoneyArr['���ž���']);
                                            unset($ckarray[array_search(trim($fsql->f('userid'),','), $ckarray)]);
                                            unset($ckname[trim($fsql->f('userid'),',')]);
                                        }
                                    }
                                    
                                    if(in_array($billDept,$NetDeptI)){
                                        $flowMoneyArr['���ž���']=0;
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }
                                        if($_SESSION['USER_ID']!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
                                    
                                    if(trim($fsql->f('ViceManager'),',')!=$_SESSION['USER_ID']&&$fsql->f('ViceManager')){
                                        $ckarray[]=trim($fsql->f('ViceManager'),',');
                                        $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    }
                                    
                                    if(trim($fsql->f('ViceManager'),',')==$_SESSION['USER_ID']){
                                        $flowMoneyArr['�ܲ�']=0;
                                    }
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                    
                                }
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                
                            }else{
                                if(empty($billDept)){
                                    $billDept=$_SESSION['DEPT_ID'];
                                }
                                
                                //���ž���
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                /*if(in_array($billDept,$NetDeptI)){
                                 $flowMoneyArr['���ž���']=$ckMoney[1];
                                 $flowMoneyArr['�����ܼ�']=20000;
                                 }*/
                                
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')&&trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']){
                                        $flowMoneyArr['���Ÿ�����']=0;
                                        $ckarray[]=trim($fsql->f('manager'),',');
                                        $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                    }
                                    
                                    if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
                                        $ckarray[]=trim($fsql->f('userid'),',');
                                        $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                    }
                                    
                                }
                                
                                
                                //if(!in_array($billDept,$SaleDeptI)){
                                $fsql->query2("select d.MajorId , d.ViceManager ,d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    $ckarray[]=trim($fsql->f('MajorId'),',');
                                    $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    
                                    if(in_array($billDept,$NetDeptI)){
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];;
                                        }
                                        if($_SESSION['USER_ID']!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
                                    $ckarray[]=trim($fsql->f('ViceManager'),',');
                                    $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                }
                                // }
                                
                                if(!empty($flowMoney)&&$flowMoney!=' '){
                                    $ckarray[]=$ceo;
                                    $ckname[$ceo]='�ܲ�';
                                    
                                }
                                if(in_array($CostMan,$directorI)){
                                    unset($ckarray[array_search($ceo, $ckarray)]);
                                    $flowMoneyArr['���³�']=0;
                                    $ckarray[]=$chairman;
                                    $ckname[$chairman]='���³�';
                                }
                                if($chairman==$CostMan){
                                    $ckarray=array();
                                    $ckname=array();
                                }
                                if($isSpecial=='1'&&(!$ckname['feng.guo'])){
                                    $flowMoneyArr['���ܾ���']=0;
                                    $ckarray[]='feng.guo';
                                    $ckname['feng.guo']='���۸��ܾ���';
                                }
                            }
                        }
						elseif($tmpdetailtype=='5'){//�ۺ�
                            
                            $sqlcom = "select  NamePT,gmanager,ceo,chairman from branch_info where NamePT='$billCompany' ";
                            $fsql->query2($sqlcom);
                            if ($fsql->num_rows() > 0) {
                                $fsql->next_record();
                                $gmanager = $fsql->f('gmanager');
                                $ceo = $fsql->f('ceo');
                                $chairman = $fsql->f('chairman');
                            }
                            /*
                             $flowMoneyArr['�������۾���']=$ckMoney[0];
                             $flowMoneyArr['���ž���']=$ckMoney[0];
                             $flowMoneyArr['�����ܼ�']=$ckMoney[1];
                             $flowMoneyArr['���ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܾ���']=$ckMoney[2];
                             $flowMoneyArr['�ܲ�']=$ckMoney[2];
                             if(in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){
                             $flowMoneyArr['���Ÿ�����']=0;
                             $flowMoneyArr['���ž���']=1000;
                             $flowMoneyArr['�����ܼ�']=10000;
                             $flowMoneyArr['���ܾ���']=50000;
                             $flowMoneyArr['�ܾ���']=200000;
                             $flowMoneyArr['�ܲ�']=$ckMoney[2];
                             }
                             */
                            
                            if(in_array($billDept,$SaleDeptI)||in_array($billDept,$XSDeptI)||in_array($billDept,$JKDeptI)){//����
                                $flowMoneyArr['�������۾���']=$ckMoney[0];
                                $flowMoneyArr['�������۸�����']=$ckMoney[1];
                                $sql="SELECT  group_concat( s.areanameid ) as anid , group_concat( s.personid ) as pid
									FROM oa_contract_contract c left join  oa_system_saleperson s
									on ( ( s.provinceid = c.contractprovinceid or s.provinceid= 0 ) and find_in_set( c.customertype , s.customertype )  ),
									(SELECT  group_concat(s.businessBelong) AS com,group_concat(s.UserIds) AS usr,c.id
									FROM oa_contract_contract c left join  oa_system_saleperson s
									on ( ( s.provinceid = c.contractprovinceid or s.provinceid= 0 ) and find_in_set( c.customertype , s.customertype )  )
									where c.id='".$billConId."' AND c.areaCode=s.salesAreaId AND s.isUse = 0 GROUP BY c.id)  a
									where c.id='".$billConId."' AND IF (FIND_IN_SET(c.signSubject,a.com),FIND_IN_SET(c.signSubject,s.businessBelong),(s.businessBelong='' OR s.businessBelong IS NULL)) AND s.isUse = 0
									AND IF (FIND_IN_SET('$CostMan', a.usr),FIND_IN_SET('$CostMan', s.UserIds),(s.UserIds='' OR s.UserIds IS NULL))
									AND c.areaCode=s.salesAreaId;  ";
                                $fsql->query2($sql);
                                if($fsql->next_record( ))
                                {
                                    
                                    
                                    if(trim($fsql->f('pid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('pid'),',')&&trim($fsql->f('anid'),',')!=$_SESSION['USER_ID']){
                                        
                                        $ckarray[]=trim($fsql->f('pid'),',');
                                        $ckname[trim($fsql->f('pid'),',')]='�������۾���';
                                    }
                                    if(trim($fsql->f('anid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('anid'),',')){
                                        
                                        $ckarray[]=trim($fsql->f('anid'),',');
                                        $ckname[trim($fsql->f('anid'),',')]='�������۸�����';
                                    }
                                    $fsql->query2("select d.MajorId , d.ViceManager , d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                    if($fsql->next_record( ))
                                    {
                                        
                                        
                                        //$ckarray[]=trim($fsql->f('MajorId'),',');
                                        //$ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                        $ckarray[]=trim($fsql->f('ViceManager'),',');
                                        $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                        
                                        if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                            $ckarray[]=trim($fsql->f('generalManager'),',');
                                            $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                        }
                                        if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                            $ckarray[]=$gmanager;
                                            $ckname[$gmanager]='�ܾ���';
                                        }
                                    }
                                }
                                
                            }else{//����
                                
                                //���ž���
                                if(!empty($billCom)){
                                    $fsql->query2("select a.manager,a.userid from dept_com a where a.dept='$billDept' and a.compt='$billCom'");
                                }else{
                                    $fsql->query2("select a.manager,a.userid from dept_com a,user u where u.company=a.compt and a.dept='$billDept' and u.USER_ID='$USER_ID'");
                                }
                                if($fsql->next_record( ))
                                {
                                    if(trim($fsql->f('manager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('manager'),',')){
                                        $flowMoneyArr['���Ÿ�����']=0;
                                        $ckarray[]=trim($fsql->f('manager'),',');
                                        $ckname[trim($fsql->f('manager'),',')]='���Ÿ�����';
                                    }
                                    
                                    if(trim($fsql->f('userid'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('userid'),',')){
                                        
                                        if(in_array($billDept,$NetDeptI)){
                                            $flowMoneyArr['���ž���']=0;
                                        }
                                        $ckarray[]=trim($fsql->f('userid'),',');
                                        $ckname[trim($fsql->f('userid'),',')]='���ž���';
                                    }
                                }
                                
                                $fsql->query2("select d.MajorId , d.ViceManager , d.leader_id ,d.generalManager from department d where d.DEPT_ID='$billDept' ");
                                if($fsql->next_record( ))
                                {
                                    
                                    $ckarray[]=trim($fsql->f('MajorId'),',');
                                    $ckname[trim($fsql->f('MajorId'),',')]='�����ܼ�';
                                    
                                    if(in_array($billDept,$NetDeptI)){
                                        if(in_array($billDept,$SMDeptI)){
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }else{
                                            $flowMoneyArr['�����ܼ�']=$ckMoneyI[2];
                                        }
                                        if($_SESSION['USER_ID']!="zhongliang.hu"){
                                            $ckarray[]=trim('zhongliang.hu',',');
                                            $ckname[trim('zhongliang.hu',',')]='���ܾ���';
                                        }
                                    }
                                    
                                    
                                    $ckarray[]=trim($fsql->f('ViceManager'),',');
                                    $ckname[trim($fsql->f('ViceManager'),',')]='���ܾ���';
                                    
                                    if(trim($fsql->f('generalManager'),',')!=$_SESSION['USER_ID']&&trim($fsql->f('generalManager'),',')){
                                        $ckarray[]=trim($fsql->f('generalManager'),',');
                                        $ckname[trim($fsql->f('generalManager'),',')]='�ܾ���';
                                    }
                                    
                                    if($gmanager&&$gmanager!=$_SESSION['USER_ID']){
                                        $ckarray[]=$gmanager;
                                        $ckname[$gmanager]='�ܾ���';
                                    }
                                    
                                    
                                }
                            }
                            
                            if(!empty($flowMoney)&&$flowMoney!=' '){
                                $ckarray[]=$ceo;
                                $ckname[$ceo]='�ܲ�';
                                
                            }
                            if(in_array($CostMan,$directorI)){
                                unset($ckarray[array_search($ceo, $ckarray)]);
                                $flowMoneyArr['���³�']=0;
                                $ckarray[]=$chairman;
                                $ckname[$chairman]='���³�';
                            }
                            if($chairman==$CostMan){
                                $ckarray=array();
                                $ckname=array();
                            }
                            
                        }
                        if(!empty($ckarraytype)){
                            $ckarray=array_merge($ckarraytype,$ckarray);
                        }
                        //print_r($ckarray);
                        $ckarray = array_diff($ckarray, array(null,''));
                        $ckarray = array_unique($ckarray);
                        $cki=0;
                        $ckf=0;
                        $ckp=0;
                        foreach($ckarray as $val){
                            $cki++;
                            if(strripos($val,$billCostMan)!==false){
                                $ckf=1;
                                $ckp=$cki;
                            }
                            
                        }
                        if($ckf==1&&$ckp!=0&&count($ckarray)!=1 ){
                            //$ckarray=array_slice($ckarray,$ckp);
                        }
                        //var_dump($ckarray);die();
                        $ckarray = array_values($ckarray);
                        $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
                        $PRCS_NAME=$ckname[$ckarray[0]];
                        $ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
                    }
                }else{
                    continue;
                }
            }//�ر�����
			
			
			
            //������������
            if($PRCS_SPEC_ARR[$i]=='@spe'){
            	if($prcs_form=='��ְ��������'){
            		//print_r($obj_info);
            		//print_r($obj_exa_info);
            		if($obj_info['���ʼ���']=='GZJBFGL') {
            			if($obj_info['ֱ������ID']=='35'&&($obj_info['��Ա�ȼ�']!='��'&&!empty($obj_info['��Ա�ȼ�']))){//������
            				if(in_array($obj_info['��Ա�ȼ�'], array('A1','A2','B0','B1','B2'))){
            					$ckarray[]=$obj_exa_info['������'];
                      $ckname[$obj_exa_info['������']]='������';
                      $ckarray[]=$obj_exa_info['���¾���'];
                      $ckname[$obj_exa_info['���¾���']]='���¾���';
            				}else{
            					$ckarray[]=$obj_exa_info['������'];
                      $ckname[$obj_exa_info['������']]='������';
                      $ckarray[]=$obj_exa_info['�����ܼ�'];
                      $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
                      $ckarray[]=$obj_exa_info['���¾���'];
                      $ckname[$obj_exa_info['���¾���']]='���¾���';
            				}
            			}else{//��������
            			  $ckarray[]=$obj_exa_info['���ž���'];
                    $ckname[$obj_exa_info['���ž���']]='���ž���';
          					$ckarray[]=$obj_exa_info['�����ܼ�'];
                    $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
                    $ckarray[]=$obj_exa_info['���¾���'];
                    $ckname[$obj_exa_info['���¾���']]='���¾���';
            			}
            		}elseif($obj_info['���ʼ���']=='GZJBJL'){//����
            			$ckarray[]=$obj_exa_info['�����ܼ�'];
                  $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
                  $ckarray[]=$obj_exa_info['���¾���'];
                  $ckname[$obj_exa_info['���¾���']]='���¾���';
                  $ckarray[]=$obj_exa_info['�����ܼ�'];
                  $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
            			
            		}elseif($obj_info['���ʼ���']=='GZJBZG'){//����*���ܼ�
            			$ckarray[]=$obj_exa_info['�����ܼ�'];
                  $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
                  $ckarray[]=$obj_exa_info['���ܾ���'];
                  $ckname[$obj_exa_info['���ܾ���']]='���ܾ���';
                  $ckarray[]=$obj_exa_info['�����ܼ�'];
                  $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
            			
            		}elseif($obj_info['���ʼ���']=='GZJBZJ'){//�ܼ�
                  $ckarray[]=$obj_exa_info['���ܾ���'];
                  $ckname[$obj_exa_info['���ܾ���']]='���ܾ���';
                  $ckarray[]=$obj_exa_info['�ܲ�'];
                  $ckname[$obj_exa_info['�ܲ�']]='�ܲ�';
                  $ckarray[]=$obj_exa_info['�����ܼ�'];
                  $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
            			
            		}elseif($obj_info['���ʼ���']=='GZJBFZ'){//����
            			$ckarray[]=$obj_exa_info['�ܲ�'];
                  $ckname[$obj_exa_info['�ܲ�']]='�ܲ�';
                  $ckarray[]=$obj_exa_info['�����ܼ�'];
                  $ckname[$obj_exa_info['�����ܼ�']]='�����ܼ�';
            		}
            	}
            	//print_r($ckarray);
              $ckarray = array_diff($ckarray, array(null,''));
              $ckarray = array_unique($ckarray);
              $cki=0;
              $ckf=0;
              $ckp=0;
              foreach($ckarray as $val){
              	$cki++;
              	if(strripos($val,$billCostMan)!==false){
              		$ckf=1;
              		$ckp=$cki;
              	}
              	
              }
              if($ckf==1&&$ckp!=0&&count($ckarray)!=1 ){
              	$ckarray=array_slice($ckarray,$ckp);
              }
              if(!empty($ckarraytype)){
                $ckarray=array_merge($ckarraytype,$ckarray);
              }
              //print_r($ckarray);
              $ckarray = array_values($ckarray);
              $specids .= $specids=="" ? towhere($ckarray[0]) : ",".towhere($ckarray[0]);
              $PRCS_NAME=$ckname[$ckarray[0]];
              $ckMoney=$flowMoneyArr[$ckname[$ckarray[0]]];
            
            }
            
        }

        if($specids!="")
            $wherestr .= "or ( USER_ID in ($specids) or USER_NAME in ($specids) ) ";
    }

    //�ܾ����������쵽��Ȩ
    $posstr = strpos($wherestr,'danian.zhu');
    if( $posstr!==false ){
        $wherestr.="or ( USER_ID='danian.zhu' ) ";
    }
    
    if($wherestr=="")
        $wherestr=" and USER_ID =''";
    else
        $wherestr=" and ( ".trim($wherestr,"or")." ) ";
	//��˾�б�	
	if($isCompany=='2'&&$billCompany){
		$company=$billCompany;
	}else{
		$company=$_SESSION['USER_COM'];
	}
    $sql="select USER_NAME,USER_ID from user where HAS_LEFT='0' AND IF('$isCompany'='2',IF(handcom<>'',find_in_set('$company',handcom),Company='$company'),'1=1') ".$wherestr;
    $fsql->query2($sql);
    while($fsql->next_record( ))
    {
        $PRCS_USER_NAME .= $fsql->f( "USER_NAME" )."/";
		$PRCS_USER_ID.= $fsql->f( "USER_ID" ).",";
    };
    $PRCS_USER_NAME = rtrim($PRCS_USER_NAME,"/");
	$PRCS_USER_ID = rtrim($PRCS_USER_ID,",");
/*    
    if(empty($PRCS_USER_NAME)){
        $sql="select USER_NAME from user where HAS_LEFT='0' and company='".$company."' ".$wherestr;
        $fsql->query2($sql);
        while($fsql->next_record( ))
        {
            $PRCS_USER_NAME .= $fsql->f( "USER_NAME" )."/";
        };
    }   
//    if($PRCS_USER_NAME=="")
//        $isnull="true";
 */

    if(strpos($PRCS_NAME,'����')!==false && $hasExtStep2 == 1) {
        $hasExtStep2 = 0;
        $flow_step_arr[]=array(
            'type'=>$prcs_prop=="1"?"��ǩ":"���"
            ,'prcs_name'=>$extStep2Prcsname
            ,'user_id'=>$extStep2PrcsuserId
            ,'user_name'=>$extStep2Prcsuser
            ,'required'=>$required
            ,'prcs_spec'=>explode(',', trim($PRCS_SPEC,','))
            ,'prcs_id'=>$prcs_id
            ,'fold_type'=>$flod_type
            ,'notSkip'=>$notSkip
        );
    }
	
    if($ckthis){
	//��������
		if($prcs_id==2240&&$_SESSION['USER_ID']=='chen.chen'&&$PRCS_USER_NAME=='��Z�'){
			continue;
		}
        $flow_step_arr[]=array(
            'type'=>$prcs_prop=="1"?"��ǩ":"���"
            ,'prcs_name'=>$PRCS_NAME
			,'user_id'=>$PRCS_USER_ID
            ,'user_name'=>$PRCS_USER_NAME
            ,'required'=>$required
            ,'prcs_spec'=>explode(',', trim($PRCS_SPEC,','))
            ,'prcs_id'=>$prcs_id
            ,'fold_type'=>$flod_type
            ,'notSkip'=>$notSkip
        );
    }
	//print_r($flow_step_arr);
    if($ckarray&&!empty($ckarray)&&count($ckarray)>1){
        $cki=$x;
        
        foreach($ckarray as $key=>$val){
        		$PRCS_USER_NAME='';
				$PRCS_USER_ID='';
            if(($cki!=$x)&&( empty($flowMoney)|| ($flowMoney==$ckMoney&&0==$ckMoney)  || $flowMoney>$ckMoney ||$formName=='���ݼ�'||$formName=='���ݼ�A' || $flowMoneyTmp==1 ) ){
				$ckMoney=$flowMoneyArr[$ckname[$val]];
//�ܾ����������쵽��Ȩ
								$posstr = strpos($val,'danian.zhu');
								if( $posstr!==false ){
									$sql="select USER_NAME,USER_ID from user where HAS_LEFT='0' and (user_id in ( ".towhere($val.',danian.zhu',',')."  )  OR USER_NAME IN ( ".towhere($val,',').")) ";
								}else{
									$sql="select USER_NAME,USER_ID from user where HAS_LEFT='0' and (user_id in ( ".towhere($val,',').") OR USER_NAME IN ( ".towhere($val,',').")) ";
								}
                $fsql->query2($sql);
                while($fsql->next_record( ))
                {
                    $PRCS_USER_NAME = empty($PRCS_USER_NAME)?$fsql->f( "USER_NAME" ):$PRCS_USER_NAME.'/'.$fsql->f( "USER_NAME" );
					$PRCS_USER_ID=	empty($PRCS_USER_ID)?$fsql->f( "USER_ID" ):$PRCS_USER_ID.','.$fsql->f( "USER_ID" );
				};
                $flow_step_arr[]=array(
                    'type'=>"���"
                    ,'prcs_name'=>$ckname[$val]
					,'user_id'=>$PRCS_USER_ID
                    ,'user_name'=>$PRCS_USER_NAME
                    ,'required'=>0
                    ,'prcs_spec'=> explode(',', trim($PRCS_SPEC,','))
                    ,'prcs_id'=>$prcs_id
                    ,'fold_type'=>$flod_type
                );
                
            }
              $cki++; 
        }
        
         $x=$cki-1; 
        unset($ckarray);
    }
}
//print_r($flow_step_arr);
if(!empty($flow_step_arr)){
    $sumstep=count($flow_step_arr);
    //��ȡ�Լ���������
    /**
    $mypoin='-';
    $i=0;
    
    foreach($flow_step_arr as $val){
        if(strpos($val['user_name'], $_SESSION['USER_NAME'])!==false&&$val['required']==0&&$i!==$sumstep-1){
            $mypoin=$i;
        }
        $i++;
    }
    //�����Լ�������ǰ��Ĳ���
    if($mypoin!=='-'){
        $i=0;
        foreach($flow_step_arr as $key=>$val){
            if($i<=$mypoin&&$val['required']==0){
                unset($flow_step_arr[$key]);
            }
            $i++;
        }
    }*/
    //��շǱ������������˵�����
    $i=1;
    foreach($flow_step_arr as $key=>$val){
        if(1!=$sumstep&&$val['required']==0&&empty($val['user_name'])){
            unset($flow_step_arr[$key]);
        }
        $i++;
    }
	
	//ɾ���ظ�������==begin  2016-06-02
    $new_user_name = array();
    foreach ($flow_step_arr as $key=>$val){
    	array_push($new_user_name,$val['user_name']);
    }
    $unique_arr = array_unique($new_user_name);
    $repeat_arr = array_diff_assoc($new_user_name, $unique_arr);
    foreach ($repeat_arr as $key=>$val){
    	foreach ($flow_step_arr as $f_key=>$f_val){
    		if($val==$f_val['user_name']){
    			unset($flow_step_arr[$f_key]);
    			break;
    		}
    	}
    }
    //ɾ���ظ�������==end
	
	//��ȡ�Լ���������==begin
	$mypoin='-';
	$si=0;    
    foreach($flow_step_arr as $kys=>$val){
        if(strpos($val['user_id'], $_SESSION['USER_ID'])!==false&&!in_array($val['prcs_name'],array('������','�����ܼ�','������','��������','�������','���','�����쵼','��������','������'))&&count($flow_step_arr)>1){
            $mypoin=$si;
        }
        $si++;
    }
    //�����Լ�������ǰ��Ĳ��裨���ã�
//     if($mypoin!=='-'){
//         $mi=0;
//         foreach($flow_step_arr as $key=>$val){
//             if($mi<=$mypoin && !in_array($val['prcs_name'],array('�ⷿ����ȷ����')) && ($val['notSkip']!=1 && ($val['user_id']!="" || isset($val['user_id']) != false))){
//                 unset($flow_step_arr[$key]);
//             }
//             $mi++;
//         }
//     }
    //�����Լ�������ǰ��Ĳ���==end
    
    //�����Լ��������Ĳ���
    if($mypoin!=='-'){
        $mi=0;
        foreach($flow_step_arr as $key=>$val){
            if($mi==$mypoin && !in_array($val['prcs_name'],array('�ⷿ����ȷ����'))){
//                 if($val['type'] == 1 || $val['notSkip'] == 1) { //��ǩ���Ͳ������������Լ��������ڵ�ɾ���ͺã�����������
                    //�����ǩ���裬ֻ���������Լ�һ���������
                    if(strpos($val['user_id'],',') == false) {
                        unset($flow_step_arr[$key]);
                    }else {
                        $flow_step_arr[$key] = str_replace($_SESSION['USER_ID'].",","",$flow_step_arr[$key]);
                        $flow_step_arr[$key] = str_replace(",".$_SESSION['USER_ID'] ,"",$flow_step_arr[$key]);
                        $flow_step_arr[$key] = str_replace($_SESSION['USER_ID'],"",$flow_step_arr[$key]);
                        
                        $flow_step_arr[$key] = str_replace($_SESSION['USER_NAME']."/","",$flow_step_arr[$key]);
                        $flow_step_arr[$key] = str_replace("/".$_SESSION['USER_NAME'] ,"",$flow_step_arr[$key]);
                        $flow_step_arr[$key] = str_replace($_SESSION['USER_NAME'],"",$flow_step_arr[$key]);
                    }
//                 }else if($val['type'] == 0) { //������ͽڵ㣬��������
//                     unset($flow_step_arr[$key]);
//                 }
            }
            $mi++;
        }
    }
    //�����Լ�������ǰ��Ĳ���==end
	//print_r($flow_step_arr);
    //����Ż����޸ĵ�����=========begin
    if($_SESSION['USER_ID'] == 'huanning.zhang') {
        $hi = 0;
        foreach($flow_step_arr as $kys=>$val){
            if(!in_array($val['prcs_name'],array('������','�����ܼ�','������','��������','���','�����쵼','��������','������'))&&count($flow_step_arr)>1){
                if($hi==0 && $flow_step_arr[$kys]['user_id'] != 'danian.zhu') {
                    $flow_step_arr[$kys]['user_id'] = 'zequan.xu';
                    $flow_step_arr[$kys]['user_name'] = '����Ȩ';
                    $flow_step_arr[$kys]['prcs_name'] = '���ܲ�';
                    $hi++;
                } else if($flow_step_arr[$kys]['user_id'] != 'danian.zhu') {
                    unset($flow_step_arr[$kys]);
                }
            }
        }
    }
    //����Ż����޸ĵ�����=========end
    $i=1;
    foreach($flow_step_arr as $key=>$val){
        if(empty($val['user_name'])){
            $isnull="true";
        }
		if($val['user_id']==$_SESSION['USER_ID']&&count($flow_step_arr)>1){
			continue;
		}
        ?>
    <tr class=TableLine2>
        <td nowrap align='center' colspan='4'>
            <img border=0 src='../../images/arrow_down.gif' width='11' height='13'>
        </td>
    </tr>
    <tr class=TableLine1>
        <td nowrap align='center'>
            ��<b><font color='red'><?php echo $i;?></font></b>��<?php echo $val['fold_type']?'��'.$val['fold_type'].'��':'';?>
        </td>
        <td align='center'>
            <?php echo $val['type'];?>
        </td>
        <td align='center'>
            <?php echo $val['prcs_name'];?>
        </td>
        <td nowrap align='left'><?php 
            if(in_array('@diy', $val['prcs_spec'])){
                echo '<input type="text" id="p_'.$val['prcs_id'].'" name="'.$val['prcs_id'].'" class="prcs_div" value="��˫��ѡ�������쵼" /> 
                    <input type="hidden" id="'.$val['prcs_id'].'" class="prcs_diy_h" value="" />';
            }else{
                echo $val['user_name']
                .'<input type="hidden" class="prcs_pop" value="'.$val['user_name'].'" />';
            }
            ?>
        </td>
    </tr>
         <?php
         $i++;
    }
}
?>    

    <tr class=TableLine2>
        <td nowrap align='center' colspan='4'>
            <img border=0 src='../../images/arrow_down.gif' width='11' height='13'>
        </td>
    </tr>
    <tr class=TableLine1>
        <td nowrap align='center' colspan='6'><b>����</b></td>
    </tr>
</table>
<script language="javascript" type="text/javascript">
function isNull()
{
    if(<?php echo  $isnull;?>)
    {
        $("#sub",parent.document).attr("disabled", "disabled");
//        parent.document.getElementById('sub').disabled=true;
    }else
    {
        $("#sub",parent.document).attr("disabled", "");
//        parent.document.getElementById('sub').disabled=false;
    }
}
//isNull();
</script>
</body>
</html>