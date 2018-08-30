<?php
//模版id 请查看 config.php
$pos = isset ( $pos ) ? $pos : "left";
?>
<DIV class='module'>
<DIV class='moduleHeader'>
<table width="100%" border="0">
	<tr>
		<td nowrap><span
			style="font-size: 12px; PADDING-TOP: 5px; padding-left: 5px; font-weight: normal;"><img
			style="border: 0px" src="../images/menu/notify.gif">公告通知</span></td>
		<td width="90%" class='more_info' align="right"><SPAN
			id='more_<?php
			echo $pos;
			?>_1'><A href="../index1.php?model=info_notice"><IMG src="../images/menu/more5.png"
			onmouseover="javascript:this.style.marginBottom ='3';this.style.marginLeft  ='2';"
			onmouseout="javascript:this.style.marginBottom ='0';this.style.marginLeft  ='0';"
			border=0 title='查看更多公告通知'></A></span></td>
		<td nowrap class="icon" align="left" valign="top"><span><A href="#"
			onClick="resize('<?php
			echo $pos;
			?>_1')"><IMG style='border: 0px'
			id='img_<?php
			echo $pos;
			?>_1' title=折叠
			src="../images/verpic_close.gif"></A></SPAN></td>
	</tr>
</table>
</DIV>
<DIV class='module_body' id=table_ <?php
echo $pos;
?> _1>
<UL style="list-style-type: none;">
<?php
$query = "	select  
				a.* 
			from 
				notice as a
				left join department b on b.dept_id in (a.dept_id_str)
			where 
				a.nametype!='admin'
				and a.nametype!='系统管理'
				and a.audit=0 
				and (a.effect=1 or a.start_date < " . time () . ") 
				and a.effect!=2 
				and ( 
						find_in_set('" . $_SESSION['USER_ID'] . "',a.user_id_str)
                        or ( 
                               (find_in_set('".$_SESSION['DEPT_ID']."',a.dept_id_str) or a.dept_id_str is null or a.dept_id_str='')
                                and 
                                (find_in_set('".$_SESSION['AREA']."',a.area_id_str) or a.area_id_str is null or a.area_id_str='')
                               	and 
                                (find_in_set('".$_SESSION['USER_JOBSID']."',a.jobs_id_str) or a.jobs_id_str is null or a.jobs_id_str='')
                                and 
                                (find_in_set('" . $_SESSION['USER_ID'] . "',a.user_id_str) or a.user_id_str is null or a.user_id_str='')  
                            )
                          or ( find_in_set('" . $_SESSION['USER_ID'] . "',b.ViceManager) or find_in_set('" . $_SESSION['USER_ID'] . "',b.MajorId) )
                       )
			order by a.edit_time desc , date desc
			limit 10 
		 ";
$fsql->query ( $query );
$i = 0;
if ($fsql->num_rows () > 0)
{
	while ( $fsql->next_record () )
	{
		$i ++;
		echo '<LI  style="color:#333333;">
				' . $i . '&nbsp;&nbsp;<A href="../index1.php?model=info_notice&action=showinfo&id='.$fsql->f ( 'id' ).'&rand_key='.$fsql->f ( 'rand_key' ).'&placeValuesBefore&TB_iframe=true&modal=false&height=650" class="thickbox" title="查看《'.$fsql->f ('title').'》">
					<span style="cursor:hand;">
						' . cut_str ( $fsql->f ( 'title' ), 20, 0, 'GBK' ) . '&nbsp;&nbsp;
						' . date ( 'Y-m-d H:i:s', $fsql->f ('date') ) . '&nbsp;&nbsp;
						' . (($fsql->f ('date') >= (time () - 3 * 24 * 3600)) ? '<IMG  style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 >' : '') . '
						</span></A> 
			 </LI>';
	}
}
?>
</UL>
</DIV>
</DIV>
</DIV>