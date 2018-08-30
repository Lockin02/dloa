<?php
//模版id 请查看 config.php
$pos = isset ( $pos ) ? $pos : "left";
$itype=array(
    1=>'新闻速递',
    2=>'规范流程',
    3=>'营销资料',
    4=>'市场宣传', 
    5=>'知识共享'
);
?>
<DIV class='module'>
<DIV class='moduleHeader'>
<table width="100%" border="0">
	<tr>
		<td nowrap><span
			style="font-size: 12px; PADDING-TOP: 5px; padding-left: 5px; font-weight: normal;"><img
			style="border: 0px" src="../images/menu/notify.gif">市场部专栏</span></td>
		<td width="90%" class='more_info' align="right"></td>
		<td nowrap class="icon" align="left" valign="top"><span><A href="#"
			onClick="resize('<?php
			echo $pos;
			?>_18')"><IMG style='border: 0px'
			id='img_<?php
			echo $pos;
			?>_18' title=折叠
			src="../images/verpic_close.gif"></A></SPAN></td>
	</tr>
</table>
</DIV>
<DIV id=table_<?php
echo $pos;
?>_18>
<div style="margin-top: 5px;margin-left: 6px;margin-bottom: 5px;
     border-left: #83accf 1px solid ;border-right: #83accf 1px solid ;border-bottom: #83accf 1px solid ;width: 96%">
<?php
$query='';
$idata=array();
if($itype){
    foreach($itype as $key=>$val){
        $query.="( select  
				a.* 
			from 
				info_new as a
				left join (select * from department group by dept_id) as b on b.dept_id in (a.dept_id_str)
			where 
				 a.effect!=2 
                                and a.type='".$key."'
			order by a.edit_time desc , date desc
			limit 2 ) union";
    }
    $query=trim($query,'union');
}

$fsql->query ( $query );
if ($fsql->num_rows () > 0)
{
	while ( $fsql->next_record () )
	{
            $idata[$fsql->f('type')][$fsql->f('id')]=array(
                'title'=>$fsql->f('title'),
                'rand_key'=>$fsql->f('rand_key'),
                'date'=>$fsql->f('date'),
                'title'=>$fsql->f('title'),
            );
	}
}
if($itype){
    $res='';
    foreach($itype as $key=>$val){
        $i=1;
        $res.='<UL>
                <li style="font: 12px; border-bottom: #83accf 1px solid ;border-top: #83accf 1px solid ;background-color: #eeeeee;>'
                .'<div style="width:100%"><table border="0" style="width:100%;font-size:12px;">
                    <tr><td width="93%">'.$val.'</td>
                        <td ><A href="#" onclick="openTab(\'index1.php?model=info_new&itype='.$key.'\',\'市场部专栏\')"><IMG src="../images/menu/more5.png"
			onmouseover="javascript:this.style.marginBottom =\'3\';this.style.marginLeft  =\'2\';"
			onmouseout="javascript:this.style.marginBottom =\'0\';this.style.marginLeft  =\'0\';"
			border=0 title=\'查看更多市场部专栏\'></A></td></tr>
                </table><div></li>';
        if(!empty($idata[$key])){
            foreach($idata[$key] as $vkey=>$vval){
                $res.='<LI  style="color:#333333;">
                                    ' . $i . '&nbsp;&nbsp;<A href="../index1.php?model=info_new&action=showinfo&id='
                            .$vkey.'&rand_key='.$vval['rand_key']
                            .'&placeValuesBefore&TB_iframe=true&modal=false&height=650" class="thickbox" title="查看《'
                            .$vval['title'].'》">
                                            <span style="cursor:hand;">
                                                    ' . cut_str ( $vval['title'], 20, 0, 'GBK' ) . '&nbsp;&nbsp;
                                                    ' . date ( 'Y-m-d H:i:s', $vval['date'] ) . '&nbsp;&nbsp;
                                                    ' . (($vval['date'] >= (time () - 3 * 24 * 3600)) ? '<IMG  style="position:absolute;zIndex:100;" src="../images/new.gif"  border=0 >' : '') . '
                                                    </span></A> 
                             </LI>';
                $i++;
            }
        }else{
            $res.='<li>&nbsp;</li>';
        }
        $res.='</UL>';
    }
}
echo $res;
?>
</DIV>
</DIV>
</DIV>