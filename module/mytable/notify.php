<?php 
//模版id 请查看 config.php
$pos=isset($pos)?$pos:"left";
?>
<script language="javascript">
    <!--//
function open_notify(NOTIFYID)                
{                                                  
    URL="./notify/show/read_notify.php?NOTIFYID="+NOTIFYID;                                                         
    myleft=(screen.availWidth-500)/2;       
    window.open(URL,"read_notify","height=350,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}         
    //-->        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/notify.gif">公告通知</span></td>
            <td width="90%" class='more_info' align="right"><SPAN id='more_<?php echo $pos;?>_1'><A  href="notify/show/"><IMG src="../images/menu/more5.png" onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';" onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"  border=0 title='查看更多公告通知'></A></span></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_1')"><IMG style='border:0px' id='img_<?php echo $pos;?>_1' title=折叠 src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id=table_<?php echo $pos;?>_1>
      <UL style="list-style-type:none;">
      <?php 
      $NowDate = date( "Y-m-d" );
      $sqlStr="select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='".$USER_ID."'";
        $fsql->query( $sqlStr );
        while ( $fsql->next_record( ) ){
            $checkDept=$fsql->f("DEPT_ID");
            $checkPriv=$fsql->f("USER_PRIV");
            $checkArea=$fsql->f("area");
        }
      if(false){
        $sql = "select notify.*,USER_NAME from notify ,user  where status='1' and BEGIN_DATE<='".$NowDate."' and END_DATE>='".$NowDate."' and user.USER_ID=notify.FROM_ID order by SEND_TIME DESC limit 0,$num ";
      }else{
        $sql="select 
        		notify.*,USER_NAME 
        	from 
        		notify,user 
        	where 
        		status='1' 
        		and BEGIN_DATE<='".$NowDate."' and END_DATE>='".$NowDate."' 
        		and ( 
        				( 
        					( 
        						find_in_set('".$checkDept."',COPY_TO_ID)>0 
        						or COPY_TO_ID='ALL_DEPT' 
        						or find_in_set('".$checkPriv."',PRIV_ID)>0 
        						or PRIV_ID='ALL_PRIV' 
        						or find_in_set('".$USER_ID."',TO_ID)>0 
        					) and (
        							find_in_set('$checkArea',AREA_ID)>0 
        							or AREA_ID='ALL_AREA' or AREA_ID='' 
        						  ) 
        					
        				) or ( 
        						COPY_TO_ID=''  
        						and  PRIV_ID=''  
        						and  TO_ID=''  
        						and (
        								find_in_set('$checkArea',AREA_ID)>0 
        								or AREA_ID='ALL_AREA' or AREA_ID='' 
        							) 
        						) 
        				) and user.USER_ID=notify.FROM_ID 
        			order by SEND_TIME DESC limit 0,$num ";
      }
      $fsql->query($sql);
      if($fsql->num_rows()>0){
          $x=1;
        while($fsql->next_record()){
            ?>
        <LI  style="color:#333333;"><?php echo $x;?>&nbsp;&nbsp;<A href="javascript:open_notify('<?php echo $fsql->f( 'NOTIFY_ID' );?>');"><span style="cursor:hand;"><?php echo cut_str($fsql->f('SUBJECT'), 20, 0, 'GBK') ;?>&nbsp;&nbsp;<?php echo $fsql->f( "SEND_TIME" );?>&nbsp;&nbsp;<?php if(strtotime($fsql->f( "SEND_TIME" ))>=time()-3 * 24 * 3600){
            ?><IMG  style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 ><?php
        }?></span></A> 
            <?php
            $x++;
        }
      }
      ?>  
</UL></DIV></DIV></DIV>
