<html>
<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="renderer" content="webkit|ie-comp|ie-stand" />
    <!--<meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    -->
	<meta http-equiv="Content-Type" content="text/html; charset=GB2312">
    <title>����</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->compDir;?>inc/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->compDir;?>css/nav_tab.css">
    <!-- ������� -->
    <script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="<?php echo $this->compDir;?>js/thickbox.js"></script>
<link rel="stylesheet" href="<?php echo $this->compDir;?>js/thickbox.css" type="text/css" media="screen" />


<!-- ������� -->
<link type="text/css" href="<?php echo $this->compDir;?>css/yxstyle.css" media="screen" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->compDir;?>js/jquery/style/yxgrid.css" media="screen" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->compDir;?>js/jquery/style/yxtree.css" media="screen" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->compDir;?>js/jquery/style/yxmenu.css" media="screen" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->compDir;?>js/jquery/style/yxmenu.theme.css" media="screen" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->compDir;?>js/jquery/tab/css/tabs.css" media="screen" rel="stylesheet"/>
<link type="text/css" href="<?php echo $this->compDir;?>js/jquery/validation/validationEngine.css" media="screen" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->compDir;?>js/jquery/style/yxwindow.css" media="screen" rel="stylesheet" />



<script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/woo.js"></script>
<script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/component.js"></script>
<script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/dump.js"></script>
<script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/window/yxwindow.js"></script>
<script type="text/javascript" src="<?php echo $this->compDir;?>js/jquery/combo/business/yxselect-user.js?v=20160615"></script>




<style>
body {margin:0px auto;font-size: 9pt; padding:10px; font-family:"΢���ź�","����",Verdana;}
.table {
    border-collapse: collapse;
    border:1px solid #D3E5FA;
    word-break:break-all;
}
.table td {
    border:1px solid #D3E5FA;
	white-space:normal; word-break:break-all;
	table-layout:fixed;
	padding:5px 5px;
	font-size: 9pt;
	
}
.table tr:first-child td{
	border-top:0px;
}

.table tr:last-child td{
	border-bottom:0px;
}
.table td:first-child{
	border-left:0px;
}

.table td:last-child {
	border-right:0px;
}

p{
	margin:0px;
	padding:3px 0px;	
}
iframe {
width:100%;
height:100%;
border:0px;
}
</style>
    <script language="JavaScript">
    function checkEmailTA()
    {
        var sm=document.getElementsByName("issend");
        var addressdiv=document.getElementById("maildiv");
        for (j=0;j<sm.length;j++)
        {
           if(sm[j].checked)
           {
            if(sm[j].value=="y"){
            addressdiv.style.display="block";
            }
           else
            addressdiv.style.display="none";
            }
        }
    }
    function check()
    {
//        alert(form1.action);
//        return false;
        
        // ���ҵ�������鿴ҳ��������ļ����,��ִ�й��������麯��,�����ؼ����
        var specialChk = true;
        if(window.frames["ifsummary"].hasSpecialChk == "1"){
            specialChk = window.frames["ifsummary"].auditSpecificChk();
        }

        if(specialChk){
            if(document.getElementById("content").value=="")
            {
                if(confirm("ȷ������д���������"))
                {
                    document.getElementById('sub').disabled=true;
                    return true;
                }else
                    return false;
            }
            document.getElementById('sub').disabled=true;
            return true;
        }
    }
    function showCostRecord(billno)
    {
      URL="cost_view.php?BillNo="+billno;
      var myleft=(screen.availWidth-500)/2;
      window.open(URL,"read_notify","height=450,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");   
    }
    function suggeclick(){
        width = 360;
        height = 220;
        document.getElementById("infoDiv").style.left=((document.body.clientWidth-width)>0?(document.body.clientWidth-width):0)/2+"px"; 
        document.getElementById("infoDiv").style.top=(((document.body.clientHeight-height)>0?(document.body.clientHeight-height):0)/2+300)+"px"; 
        document.getElementById("infoDiv").style.zIndex=9999999;
        document.getElementById("infoDiv").style.width=width; 
        document.getElementById("infoDiv").style.height=height; 
        document.getElementById("infoDiv").style.border="1px solid #9FBFE3";
        document.getElementById("tranDiv").style.height=document.body.clientHeight+ "px"; 
        document.getElementById("tranDiv").style.width=document.body.clientWidth+ "px"; 
        document.getElementById("tranDiv").style.display=""; 
        document.getElementById("tranDivBack").style.display=""; 
        document.getElementById("tranDivBack").style.zIndex=10000;
        document.getElementById("infoDiv").style.display=""; 
    }
    function closeWindow(){ 
        document.getElementById("tranDiv").style.display="none"; 
    }
    </script>
</head>
<body>
<form id="ewfexamform" name="form1" method="post" action="?actTo=ewfExamSub" onSubmit="return check()">
<table border="1" width="98%" cellpadding="2" cellspacing="0" class="table" style="font-size:10pt;color:#000000;" bordercolorlight="#FFFFFF" bordercolordark="#9FBFE3" align="center">
   <tr class="TableLine2" align="left">
        <td colspan="2" style="padding:0px; height:30px;">
        
        <b style="padding-left:10px;">��ǰ���裺<font color="red"><?php
    $itemName="";
    $sql = "select Item from flow_step s,flow_step_partent p where s.ID=p.StepID and p.ID=".$spid;
    $msql->query($sql);
    if($msql->next_record())
    {
        echo $msql->f("Item");
        $itemName=$msql->f("Item");
    }
?></font></b>
             </td>
    </tr>
    <tr class="TableLine2" align="center">
        <td colspan="2" style="padding:0px;border:0px;">
<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="#d0d0c8" align="center" style="font-weight:550;padding:0px;margin:0px;"  style="border-top:0px; border-bottom:0px;">
    <tr class="" style="background:url(../../../images/lore/titlebg.png) repeat-x; height:30px; line-height:30px;font-size:14;font-weight:600; padding:0px; margin:0px; border-bottom:0px;">
        <td colspan="2" style="padding:0px; margin:0px; text-align:center; border-bottom:0px; ">
        ��������
        </td>
    </tr>
    <tr>
      <td  width="100%" colspan="2" height="460" style="padding:0px;BACKGROUND: #FFF;" style="border:0px;">
        <iframe id="ifsummary" name="ifsummary" onload="javascript:ifsummary.scrollTo(0,1000);" marginheight="0" marginwidth="0" frameborder=0  scrolling="auto" src="<?php echo $detailUrl;?>"  title="��������" height="100%"  width="100%">��֧�ֿ��,����������.</iframe>
      </td>
    </tr> 
 </table>
        </td>
    </tr>
    <?php 
        $flowProp="";
        $sql="select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop , u.has_left , u.user_name from flow_step f 
          left join wf_task wf on (f.wf_task_id=wf.task)
          left join user u on (wf.Creator = u.USER_ID)
          where  f.Wf_task_ID='$taskId' order by f.SmallID";
        $fsql->query($sql);
        if($fsql->num_rows()>0){
            ?>
<tr align="center" >
      <td colspan="2" style="padding:0px; margin:0px;" >
        <table class="table" bgcolor="#d0d0c8" border="0"  cellspacing="0" cellpadding="0" width="100%"  align="center" style="border:0px;font-size: 9pt;" >
                <tr align="center" class="TableContent" >
                    <td width="100%" align="center" colspan="6" style="font-size:14px;"><B>�������</B></td>
                </tr>
                    <tr align="center" class="TableLine2" style="color:blue;">
                        <td width="20%">������</td>
                        <td width="8%">��������</td>
                        <td width="10%">������</td>
                        <td width="20%">��������</td>
                        <td width="9%">�������</td>
                        <td width="27%">�������</td>
                    </tr>
             <?php
                while($fsql->next_record()){
                    $wfuser=$fsql->f('user_name');
                    $wfuserleft=$fsql->f('has_left');
                    $flowProp=$fsql->f("Flow_prop")==1?"��ǩ":"���";
                    $exaItem=$fsql->f("Item");
                    $exaFlag=$fsql->f("Flag");
                    $exaUser=$fsql->f("User");
                    $exaDate=$fsql->f("Endtime");
                    $exaResult="";
                    $exaContent="";
                    $sql="select p.User  , p.Content, p.Result, p.Endtime ,p.ID  from flow_step_partent p where p.Wf_task_ID='$taskId' and p.SmallID='".$fsql->f("SmallID")."'  ";
                    $qsql->query($sql);
                    if($qsql->num_rows()>0){
                        $x=0;
                        while($qsql->next_record()){
                            $x++;
                            $exaUser=$qsql->f("User");
                            $exaResult=$qsql->f("Result");
                            $exaContent=$qsql->f("Content");
                            $exaDate=$qsql->f("Endtime");
                        ?>
                    <tr class="extr TableLine2" >
                        <?php 
                        if($x==1){
                            ?>
                        <td rowspan="<?php echo $qsql->num_rows();?>" <?php if($itemName==$exaItem) echo "style='color:red;'";?>>&nbsp;<?php echo $exaItem;?></td>
                        <td rowspan="<?php echo $qsql->num_rows();?>" align="center">&nbsp;<?php echo $flowProp;?></td>
                            <?php
                        }
                        ?>
                        <td align="center">&nbsp;<?php echo trim(get_username_list($exaUser),",");?></td>
                        <td align="center" style='color:green;'>&nbsp;<?php echo $exaDate;?></td>
                        <td align="center">&nbsp;<?php if($exaResult=="ok") echo "<font color='green'>ͬ��</font>"; elseif($exaResult=="no") echo "<font color='red'>��ͬ��</font>";else echo "δ����"?></td>
                        <td>&nbsp;<?php echo $exaContent;?></td>
                    </tr>
                        <?php
                        }
                    }else{
                        ?>
                    <tr class="extr TableLine2" >
                        <td <?php if($itemName==$exaItem) echo "style='color:red;'";?> >&nbsp;<?php echo $exaItem;?></td>
                        <td align="center">&nbsp;<?php echo $flowProp;?></td>
                        <td align="center">&nbsp;<?php echo trim(get_username_list($exaUser),",");?></td>
                        <td align="center">&nbsp;<?php echo $exaDate;?></td>
                        <td align="center">&nbsp;<?php if($exaResult=="ok") echo "ͬ��"; elseif($exaResult=="no") echo "<font color='red'>��ͬ��</font>";else echo "δ����"?></td>
                        <td>&nbsp;<?php echo $exaContent;?></td>
                    </tr>
                    <?php
                    }
                }
             ?>
        </table>
      </td>
</tr>
            <?php
        }
?>
    <?php
    if(!empty($wfuserleft)&&$wfuserleft=='1'){
    ?>
    <tr>    
        <td class="TableData" height="20" colspan="2" style="padding-top:3px;">
            ���ѣ������� <?php echo $wfuser;?> <font color="red">����ְ</font>
        </td>    
    </tr>
    <?php
    }
    ?>
<input type="hidden" name="spid" value="<?php echo $spid;?>">
    <tr>    
        <td class="TableData" height="3%">
            �������
        </td> 
        <td class="TableData" colspan="5">
            <input type="radio" name="result" value="ok" checked="checked">ͬ��
            <input type="radio" name="result" value="no">��ͬ��
        </td>    
    </tr>
    <tr>    
        <td class="TableData" height="6%" >
            �������
        </td> 
        <td class="TableData" colspan="5">
            <textarea name="content" id="content" rows="3" cols="80" align="right" title="�������"></textarea>
        </td>    
    </tr>
    <tr>  
    
        <td class="TableData">
            �Ƿ�֪ͨ������
        </td> 
        <td class="TableData" colspan="5">
            <input type="radio" name="issend" value="y" checked="checked" onClick="checkEmailTA()">��
            <input type="radio" name="issend" value="n"  onclick="checkEmailTA()">��
            <?php
            require($this->baseDir."maildiv.php");
            ?>
        </td> 
            </div>
        </td>    
    </tr>
    <tr>  
        <td class="TableData">
            �Ƿ�֪ͨ��һ������������
        </td> 
        <td class="TableData" colspan="5">
            <input type="radio" name="isSendNext" value="y"  checked="checked" onClick="checkEmailTA()">��
            <input type="radio" name="isSendNext" value="n"  onclick="checkEmailTA()">��
        </td> 
            </div>
        </td>    
    </tr>
        <tr>  
        <td class="TableData">
            ��������Ƿ�֪ͨ��������
        </td> 
        <td class="TableData" colspan="5">
            <input type="radio" name="isSendApp" value="1"  >��
            <input type="radio" name="isSendApp" value="2" checked="checked" >��
        </td> 
            </div>
        </td>    
    </tr>
        <tr>  
        <td class="TableData">
            ��������Ƿ�֪ͨϵͳ����Ա
        </td> 
        <td class="TableData" colspan="5">
            <input type="radio" name="isSendSys" value="1"  >��
            <input type="radio" name="isSendSys" value="2" checked="checked" >��
        </td>    
    </tr>
    
    <tr>    
        <td class="TableControl" colspan="6" align="center" >
            <input type="button" name="sub" id="sub" value="�ύ����" onClick="onSubmit()" align="center" size="30" class="BigButtons" style="margin-right:10px;">
            <input type="button" name="addstep" id="addstep" alt="#TB_inline?height=200&width=450&inlineId=tranDiv" title="������һ������"  value="����������" align="center" class="thickbox"   style="margin-right:10px;"/>
            <input type="button" name="addCopyUserStep" id="addCopyUserStep" alt="#TB_inline?height=200&width=450&inlineId=addCopyUserDiv" title="�����������������"  value="�����������������" align="center" class="thickbox"   style="margin-right:10px;"/>
            <input type="button" value="����" align="center" size="30" class="BigButtona" onClick="history.go(-1)">
            <div id="appendHtml"></div>
        </td>    
    </tr>

</table>


<div style="position:absolute;display:none; left:0px; top:0px;width:100%; height:100%;" id="tranDiv">
        <table border="0" width="100%" height="100%" class="table" cellpadding="0" cellspacing="0" align="center" id="repTable">
            <tr  align="center"  >
                <td height="30" width="20%">������Ա</td>
                <td align="left" style="padding-left:10px;">
                    <input type="hidden" name="ADD_ID" id="principalId" value="" readonly/>
                    <input  type="text" name="ADD_NAME" id="principalName" style="width:300px" title="ѡ��" >
                </td>
            </tr>
            <tr  align="center">
                <td height="35" colspan="2">
                    <input type="hidden" id="awf" value="<?php echo $taskId;?>"/>
                    <input type="hidden" id="awfspid" value="<?php echo $spid;?>"/>
                    <input type="button"  name="Submit" onClick="subAdd()"  value="ȷ ��"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="button" name="Submit1"  value="ȡ ��" onClick="tb_remove()"/>
                </td>
            </tr>
        </table>
<div id='sug'>
</div>
</div>
<div style="position:absolute;display:none; left:0px; top:0px;width:100%; height:100%;" id="addCopyUserDiv">
        <table border="0" width="100%" height="100%" class="table" cellpadding="0" cellspacing="0" align="center" id="repTable">
            <tr  align="center"  >
                <td height="30" width="20%">�����������������</td>
                <td align="left" style="padding-left:10px;">
                <input type="hidden" name="copyUserId" id="copyUserId" value="" readonly/>
                <input  type="text" name="copyUserName" id="copyUserName" style="width:300px" title="ѡ��" >
                </td>
            </tr>
            <tr  align="center">
                <td height="35" colspan="2">
                    <input type="button"  name="Submit" onClick="tb_remove()"  value="ȷ ��"/>
                </td>
            </tr>
        </table>
</div>
<!--�����ֲ��ֽ���-->
    </form>
    
 <!--�����ֲ���-->
<script language="JavaScript">

function onSubmit(){
	if(check()){
		$('#ewfexamform').submit();
	}	
}


function OpenUserAddWindow()
{
	URL="../../../module/user_select?toid=ADD_ID&toname=ADD_NAME";
	loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
	loc_y=document.body.scrollTop+event.clientY-event.offsetY+140;
	window.open(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:265px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
}
	
function setUserd(userId,userNameId)
{
	userIdStr=$('#'+userId).val();
	url="../../../index1.php?model=deptuser_user_user&action=selectuser&mode=check&showDialog=1&isOnlyCurDept=false&deptIds=&formCode=stampConfig&userVal="+userIdStr;
	revalue=window.showModelDialog(url,'newwindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,top=150,left=150,width=370,height=520');
	
	if(revalue){
		//$('#'+userId).val(revalue.val);
        //$('#'+userNameId).val(revalue.text)selectedUser
	    //$('#'+userId).val(selectedUser);
        //$('#'+userNameId).val(revalue.text)
	}
  
}
function setUser(userId,userNameId)
{
	var obj = new Object(); //��ģ̬�Ӵ���,����ȡ����ֵ 
	userIdStr=$('#'+userId).val();
	url="../../../index1.php?model=deptuser_user_user&action=selectuser&mode=check&showDialog=1&isOnlyCurDept=false&deptIds=&formCode=stampConfig&userVal="+userIdStr;
	revalue=window.showModalDialog(url,obj,'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,top=150,left=150,width=370,height=520');
	if(revalue == undefined) { revalue = window.returnValue; }
	 window.addEventListener("message", function( event ) { 
		 // �Ѹ����ڷ��͹�����������ʾ���Ӵ�����
	   document.getElementById("content").innerHTML+=event.data+"<br/>"; 
	 }, false ); 
	//alert("A="+revalue.val);
	if(revalue){
		//$('#'+userId).val(revalue.val);
        //$('#'+userNameId).val(revalue.text)selectedUser
	    //$('#'+userId).val(selectedUser);
        //$('#'+userNameId).val(revalue.text)
	}
  
}
function openwindows(){
	var obj = new Object(); //��ģ̬�Ӵ���,����ȡ����ֵ 
	var retval = window.showModalDialog("ordervideo.jsp?rderIds="+"0010,0020,0030",obj,"dialogWidth=500px;dialogHeight=500px"); //for chrome 
	if(retval == undefined) { retval = window.returnValue; } 
	alert(retval); 
	} 
	
    function clearAdd()
    {
        document.getElementById("ADD_ID").value="";
        document.getElementById("ADD_NAME").value="";
    }
    function subAdd(){
        var ai=$('#principalId').val();
        var awf=$('#awf').val();
        var awfspid=$('#awfspid').val();
        if(ai==''){
            alert('��ѡ��������������Ϣ��');
            return false;
        }
		
        if($('#principalId').val()&&confirm('�Ƿ�ȷ��������������Ա��Ϣ��')) {
            var rand=Math.random()*100000;
            $.post('../../../index1.php?model=module_wf&action=add_exa_step',{
                rand:rand,awf:awf,awfspid:awfspid,ai:ai
                },
                function (data)
                {
                    if(data=='1'){
                        alert('���ӳɹ���');
                        location.reload();
                    }else{
                        alert('����ʧ�ܣ�'+data);
                    }
                }
            );
        }
    }
	
function clearUserSelect(id,name)
{
	$("#"+id).val('');
	$("#"+name).val('');
}
	
	
$(function(){
	$("#principalName").yxselect_user({
        hiddenId: 'principalId',
        mode: 'check',
        hostUrl: '../../../index1.php'
    });
	$("#copyUserName").yxselect_user({
        hiddenId: 'copyUserId',
        mode: 'check',
        hostUrl: '../../../index1.php'
    });
	
	
});
	   	
    </script> 
    
    
</body>
</html>