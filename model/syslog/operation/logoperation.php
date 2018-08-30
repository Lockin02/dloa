<?php
/**
 * @author huangzf
 * @Date 2011年11月1日 11:21:38
 * @version 1.0
 * @description:操作日志 Model层
 */
class model_syslog_operation_logoperation extends model_base {

	function __construct() {
		$this->tbl_name = "oa_syslog_operation";
		$this->sql_map = "syslog/operation/logoperationSql.php";
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
			foreach ( $rows as $key => $value ) {
				$str .= <<<EOT
			<div align="left"  id="logTitle$key"  ><font size="2" color="#blue">$seNum. 操作人:{$value['createName']}&nbsp;&nbsp;&nbsp;&nbsp;操作时间:{$value['createTime']}
			&nbsp;&nbsp;&nbsp;&nbsp;操作类型:{$value['operationType']}&nbsp;&nbsp;&nbsp;&nbsp;业务主键字段值:{$value['pkValue']}
			&nbsp;&nbsp;&nbsp;<a href="#" onclick="showTrContent(this,$key)">＞＞＞</a></font></div>
			<div id="logContent$key" style="display:none">
				<table  width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
										<td class="form_text_left">日志详细内容</td>
										<td class="form_text_right" colspan="4">
											{$value['logContent']}
										</td>
								</tr>
				</table>
			</div>
EOT;
				$seNum ++;

			}
		}
		return $str;
	}


	/**
	 * 多模块操作轨迹合并
	 * 不通用。
	 */
	function showAtBusinessViewMore($rows) {
		$str = "";
		$seNum = 1;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $value ) {
				$str .= <<<EOT
			<div align="left"  id="logTitle$key"  ><font size="2" color="black">$seNum. 操作人:<span style="color:blue">{$value['createName']}</span>&nbsp;&nbsp;&nbsp;&nbsp;操作时间:<span style="color:blue">{$value['createTime']}</span>
			&nbsp;&nbsp;&nbsp;&nbsp;操作类型:<span style="color:blue">{$value['operationType']}</span>&nbsp;&nbsp;&nbsp;&nbsp;模块:<span style="color:blue">{$value['businessName']}</span>
			&nbsp;&nbsp;&nbsp;<a href="#" onclick="showTrContent(this,$key)">＞＞＞</a></font></div>
			<div id="logContent$key" style="display:none">
				<table  width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
										<td class="form_text_left">日志详细内容</td>
										<td class="form_text_right" colspan="4">
											{$value['logContent']}
										</td>
								</tr>
				</table>
			</div>
EOT;
				$seNum ++;

			}
		}
		return $str;
	}
}
?>