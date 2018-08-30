<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<script language="javascript">
    <!--//
function openurl(VOTE_ID)
{
 URL="vote/vote_action.php?Pid="+VOTE_ID;
 myleft=(screen.availWidth-100)/2;
 window.open(URL,"投票","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function openradiourl(VOTE_ID)
{
 URL="vote/vote_radio.php?Pid="+VOTE_ID;
 myleft=(screen.availWidth-100)/2;
 window.open(URL,"投票","height=600,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}       
function linked(key,sdate,edate,name){
	tb_show(name+'投票', '../index1.php?model=general_voteoffice&action=voting&key='+key+'&sdate='+sdate+'&edate='+edate
			+'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
}

    //-->        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/notify.gif">投票箱</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_2'><A href="../index1.php?model=general_voteoffice&action=list"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多投票信息'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_2')"><IMG style='border:0px' id='img_<?php echo $pos;?>_2' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <!--
      <DIV class='module_body' id=table_<?php echo $pos;?>_2>
      <UL style="list-style-type:none;">
      <?php 
      $NOW_DATE = date( "Y-m-d" );
      $sqlStr="select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='".$USER_ID."'";
      $fsql->query( $sqlStr );      
      while ( $fsql->next_record( ) ){
        $checkDept=$fsql->f("DEPT_ID");
        $checkPriv=$fsql->f("USER_PRIV");
        $checkArea=$fsql->f("area");
      }
      if($USER_ID=='admin'){
        $sql="SELECT vote.*,USER_NAME FROM vote,user where  user.USER_ID=vote.USER_ID and BEGIN_DATE<='$NOW_DATE' and (END_DATE>='$NOW_DATE' or END_DATE='0000-00-00') and  Flag='0' order by ID desc LIMIT 0, $num ";
      }else{
        $sql="SELECT vote.*,USER_NAME FROM vote ,user  where  user.USER_ID=vote.USER_ID and BEGIN_DATE<='$NOW_DATE' and (END_DATE>='$NOW_DATE' or END_DATE='0000-00-00') and Flag='0' and  ( ( ( find_in_set('".$checkDept."',vote.DEPT_ID)>0 or vote.DEPT_ID='ALL_DEPT' or find_in_set('".$checkPriv."',vote.PRIV_ID)>0 or vote.PRIV_ID='ALL_PRIV' or find_in_set('$USER_ID',vote.TO_ID)>0 ) and (find_in_set('".$checkArea."',vote.AREA_ID)>0 or vote.AREA_ID='ALL_AREA' or vote.AREA_ID='' ) ) or ( vote.DEPT_ID='' and vote.PRIV_ID='' and vote.TO_ID='' and (find_in_set('".$checkArea."',vote.AREA_ID)>0 or vote.AREA_ID='ALL_AREA' or vote.AREA_ID='' ) ) )  order by ID desc LIMIT 0, $num";
      }
      $fsql->query($sql);
      if($fsql->num_rows()>0){
        $x=0;
        while($fsql->next_record()){
            $x++;
            ?>
        <LI style="color:#333333"><?php echo $x;?>&nbsp;<?PHP
if( $fsql->f("TYPE")==0 ){
?><a href='#' onclick="javascript:openradiourl('<?php echo $fsql->f("Pid");?>');" title="点击查看投票情况">
<?php
}
if( $fsql->f("TYPE")==1 ){
?><a href='#' onclick="javascript:openurl('<?php echo $fsql->f("Pid");?>');" title="点击查看投票情况">
<?php
}
?><span style="cursor:hand;"><?php echo cut_str($fsql->f('SUBJECT'), 20, 0, 'GBK') ;?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;"><?php echo $fsql->f( "BEGIN_DATE" );?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "BEGIN_DATE" ))>=time()-3 * 24 * 3600){
            ?><IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
        }
      }
      ?>
      -->  
       <?php
             $sql = " SELECT vl.id as pid,vr.id,vl.title,vl.sdate,vl.edate
					 FROM   vote_list vl   
	                 		left join vote_results vr on(vl.id=vr.pid) and  vr.userid='" . $_SESSION['USER_ID'] . "' 
	                 where 1
	                 and vl.flag='1'  and vl.sdate<=NOW() and vl.edate>=NOW()
				   	 and (find_in_set('" . $_SESSION["AREA"] . "',vl.areaid) or find_in_set('" . $_SESSION["DEPT_ID"] . "',vl.deptid) or find_in_set('" . $_SESSION["USER_ID"] . "',vl.userareid))
				     group by   vl.id limit 0,5";  
		 $fsql->query($sql);
		while($fsql->next_record()){
			$x++;
		?>
		<LI style="color:#333333"><?php echo $x;?>&nbsp;
		<span style="cursor:hand;"><a href="javascript:linked('<?php echo $fsql->f("pid");?>','<?php echo $fsql->f("sdate");?>','<?php echo $fsql->f("edate");?>','<?php echo $fsql->f("title");?>');" title="点击查看投票情况">
			<?php echo $fsql->f("title");?>&nbsp;&nbsp;</span><span style="cursor:hand;padding-right: 10PX;">&nbsp;&nbsp;<?php echo date('Y-m-d',strtotime($fsql->f("sdate")));?>
			<?PHP
			if($fsql->f("pid")&&!$fsql->f("id")){
			?>
			<IMG style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 >
			<?php
			}
			?>
			</span></a>
		<?PHP
		}
  
  ?>
 
      
</UL></DIV></DIV></DIV>