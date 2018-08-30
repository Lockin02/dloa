
<div id="maildiv" style="display:none;">
    <script language="JavaScript">
    function OpenUserWindow()
    {
    URL="<?php echo $this->compDir;?>module/user_select";
    loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
    loc_y=document.body.scrollTop+event.clientY-event.offsetY+140;
    window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:265px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
    }
	function setUser(userId,userNameId)
{
	userIdStr=$('#'+userId).val();
	url="../../../index1.php?model=deptuser_user_user&action=selectuser&mode=check&showDialog=1&isOnlyCurDept=false&deptIds=&formCode=stampConfig&userVal="+userIdStr;
	revalue=window.showModalDialog(url,'','dialogWidth:670px;dialogHeight:500px;');
	if(revalue){
		$('#'+userId).val(revalue.val);
        $('#'+userNameId).val(revalue.text)
	}
  
}
    function clearTO()
    {
        document.getElementById("TO_ID").value="";
        document.getElementById("TO_NAME").value="";
    }   
    </script>
    &nbsp;&nbsp;发送到：
<?php
    $sql="select USER_ID,USER_NAME from user u, wf_task l where u.USER_ID=l.Enter_user  and l.task='$taskId' group by USER_ID";
    $msql->query($sql);
    $userids="";
    $usernms="";
    while($msql->next_record())
    {
        $userids .= $msql->f("USER_ID").",";
        $usernms .= $msql->f("USER_NAME").",";
    }
?>

    <input type="hidden" name="TO_ID" id="TO_ID" value="<?php echo $userids;?>" readonly="readonly"/>
    <textarea name="TO_NAME" id="TO_NAME" rows="1" cols="36" align="right" title="eaddress" readonly="readonly"><?php echo $usernms;?></textarea>
    <input type="button" value="选 择" class="SmallButtons" onClick="setUser('TO_ID','TO_NAME')" title="选择" name="button1">
    <input type="button" value="清 空" class="SmallButtons" onClick="clearTO()" title="清空" name="button1">
    <br>
    附加信息：
    <textarea name="extraMessage" rows="3" cols="50" align="right" title="附加信息"></textarea>
</div>