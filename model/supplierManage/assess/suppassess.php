<?php
/*
 * 评估供应商model层方法
 */
class model_supplierManage_assess_suppassess extends model_base {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-11-10 下午04:39:47
	 */
	function __construct() {
		$this->tbl_name = "oa_supp_asses_supp";
		$this->sql_map = "supplierManage/assess/suppassessSql.php";
		parent::__construct ();
	}

	/**
	 * @desription 查看供应商列表
	 * @param tags
	 * @date 2010-11-12 下午05:20:30
	 */
	function sasReadList_d($suppArr) {
		$str = "";
		$i = 0;
		if ($suppArr) {
			foreach ( $suppArr as $key => $val ) {
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$i ++;
				$str .= <<<EOT
				<tr class="$classCss">
	                <td>
	                    $i
	                </td>
	                <td>
	                    $val[suppName]
	                </td>
	                <td>
	                    $val[sCode]
	                </td>
	                <td>
	                    $val[sPdt]
	                </td>
	                <td>
	                    $val[sTrade]
	                </td>
	                <td>
	                    $val[sMName]
	                </td>
	            </tr>
EOT;
			}
		} else {
			$str = '<tr><td colspan="50">暂无相关记录</td></tr>';
		}
		return $str;
	}

	/**
	 * @desription 通过方案Id获取所有数据
	 * @param tags
	 * @date 2010-11-12 下午02:31:52
	 */
	function getAllByAssesId($assesId) {
		$this->resetParam ();
		//$this->sort = "c.id";
		$this->searchArr = array ("assesId" => $assesId );
		return $this->list_d ( "select_readAll" );
	}

	/*
	 * 批量添加评估供应商，需要去除已经添加的供应商
	 */
	function addBatch_d($suppArr) {
		if (count ( $suppArr ) > 0) {
			$temp = reset ( $suppArr );
			$assesId = $temp ['assesId']; //拿到所属评估方案
			$suppIdArr = array ();
			foreach ( $suppArr as $key => $value ) {
				$suppIdArr [] = $value ['suppId'];
			}
			$suppIds = implode ( ",", $suppIdArr );
			$this->searchArr = array ("assesId" => $assesId, "suppIds" => $suppIds );
			$suppsInDataBase = $this->list_d ();//拿到该评估方案已经在数据库里的评估供应商
			if (is_array ( $suppsInDataBase )) {
				foreach ( $suppsInDataBase as $key => $value ) {
					unset ( $suppArr [$value ['suppId']] );//出去已经在数据库的评估供应商
				}
			}
			return parent::addBatch_d ( $suppArr );
		}
		return false;
	}

	/**
	 * @desription 显示分页列表
	 * @param tags
	 * @date 2010-11-18 上午10:26:44
	 */
	function sasPage_d () {
		$rows = $this->page_d ( "select_page" );
		return $rows;
	}

}
?>
