<?php

/**
 * ������¼model����
 */
class model_log_operation_operation extends model_base {

	function __construct() {
		$this->tbl_name = "oa_rd_operation_record";
		$this->sql_map = "log/operation/operationSql.php";
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
				if( $val['operateLog'] == 'NULL' || $val['operateLog'] == NULL )
					$val['operateLog'] = '��';
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i ++;
				$str .= <<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[operateManName]
						</td>
						<td>
							$val[operateTime]
						</td>
						<td>
							$val[operateType]
						</td>
						<td>
							$val[operateLog]
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
	function add_d($operation) {
		$operation ['operateManId'] = $_SESSION ['USER_ID'];
		$operation ['operateManName'] = $_SESSION ['USERNAME'];
		$operation ['operateTime'] = date ( "Y-m-d H:i:s" );

		$this->create ( $operation );
	}
}
?>