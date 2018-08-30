<?php

/**
 * 操作记录model层类
 */
class model_log_operation_operation extends model_base {

	function __construct() {
		$this->tbl_name = "oa_rd_operation_record";
		$this->sql_map = "log/operation/operationSql.php";
		parent::__construct ();
	}

/**
	 * @desription 显示变更列表
	 * @param tags
	 * @return return_type
	 * @date 2010-9-17 上午10:42:30
	 */
	function showlist($rows) {
		if ($rows) {
			$i = 0;
			$str = "";
			foreach ( $rows as $key => $val ) {
				if( $val['operateLog'] == 'NULL' || $val['operateLog'] == NULL )
					$val['operateLog'] = '无';
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
			$str = '<tr><td colspan="50">暂无相关记录</td></tr>';
		}
		return $str;
	}

	/*
	 * 添加操作记录
	 */
	function add_d($operation) {
		$operation ['operateManId'] = $_SESSION ['USER_ID'];
		$operation ['operateManName'] = $_SESSION ['USERNAME'];
		$operation ['operateTime'] = date ( "Y-m-d H:i:s" );

		$this->create ( $operation );
	}
}
?>