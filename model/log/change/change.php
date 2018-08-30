<?php

/**
 * �����¼model����
 */
class model_log_change_change extends model_base {

	function __construct() {
		$this->tbl_name = "oa_rd_change_record";
		$this->sql_map = "log/change/changeSql.php";
		parent::__construct ();
	}

	/**
	 * @desription ��ʾ����б�
	 * @param tags
	 * @return return_type
	 * @date 2010-9-17 ����10:42:30
	 */
	function showlist($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ( $rows as $key => $val ) {
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i ++;
				$str .= <<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[changeManName]
						</td>
						<td>
							$val[changeTime]
						</td>
						<td>
							$val[changeReason]
						</td>
						<td>
							$val[changeLog]
						</td>

					</tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/*
	 * ��Ӳ�����¼
	 */
	function add_d($change) {
		$change ['changeManId'] = $_SESSION ['USER_ID'];
		$change ['changeManName'] = $_SESSION ['USERNAME'];
		$change ['changeTime'] = date ( "Y-m-d H:i:s" );
		$this->create ( $change );
	}

	/*
	 * ��ȡĳ��ҵ���������¼��/����
	 *  @param $objTab  ҵ��������/����
	 *  @param $objId   ҵ��id
	 */
	function getChangeNum($objTab, $objId) {
		$this->searchArr = array ("objTable" => $objTab, "objId" => $objId );
		$arr = $this->listBySqlId ("select_count");
		return $arr [0] ['num'];
	}
}
?>