<?php
/**
 * @author huangzf
 * @Date 2011��11��1�� 11:21:38
 * @version 1.0
 * @description:������־ Model��
 */
class model_syslog_operation_logoperation extends model_base {

	function __construct() {
		$this->tbl_name = "oa_syslog_operation";
		$this->sql_map = "syslog/operation/logoperationSql.php";
		parent::__construct ();
	}
	/**
	 *
	 * �鿴ҵ����Ϣ������־ģ��
	 * @param  $rows
	 */
	function showAtBusinessView($rows) {
		$str = "";
		$seNum = 1;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $value ) {
				$str .= <<<EOT
			<div align="left"  id="logTitle$key"  ><font size="2" color="#blue">$seNum. ������:{$value['createName']}&nbsp;&nbsp;&nbsp;&nbsp;����ʱ��:{$value['createTime']}
			&nbsp;&nbsp;&nbsp;&nbsp;��������:{$value['operationType']}&nbsp;&nbsp;&nbsp;&nbsp;ҵ�������ֶ�ֵ:{$value['pkValue']}
			&nbsp;&nbsp;&nbsp;<a href="#" onclick="showTrContent(this,$key)">������</a></font></div>
			<div id="logContent$key" style="display:none">
				<table  width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
										<td class="form_text_left">��־��ϸ����</td>
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
	 * ��ģ������켣�ϲ�
	 * ��ͨ�á�
	 */
	function showAtBusinessViewMore($rows) {
		$str = "";
		$seNum = 1;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $value ) {
				$str .= <<<EOT
			<div align="left"  id="logTitle$key"  ><font size="2" color="black">$seNum. ������:<span style="color:blue">{$value['createName']}</span>&nbsp;&nbsp;&nbsp;&nbsp;����ʱ��:<span style="color:blue">{$value['createTime']}</span>
			&nbsp;&nbsp;&nbsp;&nbsp;��������:<span style="color:blue">{$value['operationType']}</span>&nbsp;&nbsp;&nbsp;&nbsp;ģ��:<span style="color:blue">{$value['businessName']}</span>
			&nbsp;&nbsp;&nbsp;<a href="#" onclick="showTrContent(this,$key)">������</a></font></div>
			<div id="logContent$key" style="display:none">
				<table  width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
										<td class="form_text_left">��־��ϸ����</td>
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