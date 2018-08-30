<?php
/**
 * @author huangzf
 * @Date 2011��11��1�� 11:21:38
 * @version 1.0
 * @description:������־ Model��
 */
class model_syslog_operation_logoperationItem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_syslog_operation_item";
		$this->sql_map = "syslog/operation/logoperationItemSql.php";
		parent::__construct ();
	}

	/**
	 * �鿴ҵ����Ϣ������־ģ��
	 * @param  $rows
	 */
	function showAtBusinessView($rows) {
		$str = "";
		$seNum = 1;
		if (is_array ( $rows )) {
			$lastColumnName = "";
			$codeNum = 1;
			foreach ( $rows as $key => $value ) {
				if($value['columnCname'] != $lastColumnName) {
					if($key != 0) {//������һ���ֶ�
						$str.='</table>';
					}
					$str.='
						<div align="left"><font size="2" color="#blue">&nbsp;'.'��'.'&nbsp;��'.$value[columnCname].'��</font></div>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="text-align:center;" class="form_text_left"><font color="#4876FF">���</font></td>
							<td style="text-align:center;" class="form_text_left"><font color="#4876FF">����</font></td>
							<td style="text-align:center;" class="form_text_left"><font color="#4876FF">�޸�ʱ��</font></td>
							<td style="text-align:center;" class="form_text_left"><font color="#4876FF">�޸���</font></td>
						</tr>';

						$seNum++;
						$codeNum=1;
					}
					$str.='<tr>
						<td>('.$codeNum.')</td><td style="text-align:left;">'.$value['oldValue'].'&nbsp;>>>'.$value['newValue'].'</td><td>'.$value['createTime'].'</td><td>'.$value['createName'].'</td>
						</tr>';
				$codeNum++;
				$lastColumnName = $value['columnCname'];
			}
		}
		return $str;
	}

	/**
	 * ������־����id@param logSettingId�͹ؼ���@param pkValue��ȡ���һ�ε��޸ļ�¼
	 * ��ʱûʲô�ð취��ֻ���ҳ����һ�ε��޸�ʱ�䣬�ٲ����޸�ʱ��һ���ļ�¼������Ϊ�����û���������ORZ
	 */
	function findByLogAndPk($logSettingId ,$pkValue) {
		$obj = $this->find(array('logSettingId' => $logSettingId ,'pkValue' => $pkValue) ,' id DESC ');
		if ($obj) {
			$objs = $this->findAll(array('logSettingId' => $logSettingId ,'pkValue' => $pkValue ,'createTime' => $obj['createTime']));
		}
		return $objs;
	}
}
?>