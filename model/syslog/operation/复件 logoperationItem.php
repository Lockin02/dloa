<?php
/**
 * @author huangzf
 * @Date 2011年11月1日 11:21:38
 * @version 1.0
 * @description:操作日志 Model层 
 */
class model_syslog_operation_logoperationItem extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_syslog_operation_item";
		$this->sql_map = "syslog/operation/logoperationItemSql.php";
		parent::__construct ();
	}
	/**
	 * 
	 * 查看业务信息操作日志模板
	 * @param  $rows
	 */
	function showAtBusinessView($rows) {
		$str = "";
		$seNum = 1;
		if (is_array ( $rows )) {
			$lastColumnName="";
			$codeNum=1;
			foreach ( $rows as $key => $value ) {
				if($value['columnCname']!=$lastColumnName){
					if($key!=0){//结束上一个字段
						$str.='</table>';
					}
					$str.='
										<div align="left"><font size="2" color="#blue">&nbsp;'.'★'.'&nbsp;【'.$value[columnCname].'】</font></div>
										<table width="100%" border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td  style="text-align:center;" class="form_text_left"><font color="#4876FF">序号</font></td>
												<td  style="text-align:center;" class="form_text_left"><font color="#4876FF">内容</font></td>
												<td  style="text-align:center;" class="form_text_left"><font color="#4876FF">修改时间</font></td>
												<td style="text-align:center;" class="form_text_left" ><font color="#4876FF">修改人</font></td>
											</tr>';	
					
					$seNum++;
					$codeNum=1;								
				}
				$str.='<tr>
							<td>('.$codeNum.')</td><td style="text-align:left;">'.$value['oldValue'].'&nbsp;>>>'.$value['newValue'].'</td><td>'.$value['createTime'].'</td><td>'.$value['createName'].'</td>
						</tr>';
				$codeNum++;
				
				
				$lastColumnName=$value['columnCname'];
			}
		}
		return $str;
	}
}
?>