<?php
/*
 * ������Ӧ��model�㷽��
 */
class model_supplierManage_assess_suppassess extends model_base {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-11-10 ����04:39:47
	 */
	function __construct() {
		$this->tbl_name = "oa_supp_asses_supp";
		$this->sql_map = "supplierManage/assess/suppassessSql.php";
		parent::__construct ();
	}

	/**
	 * @desription �鿴��Ӧ���б�
	 * @param tags
	 * @date 2010-11-12 ����05:20:30
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
			$str = '<tr><td colspan="50">������ؼ�¼</td></tr>';
		}
		return $str;
	}

	/**
	 * @desription ͨ������Id��ȡ��������
	 * @param tags
	 * @date 2010-11-12 ����02:31:52
	 */
	function getAllByAssesId($assesId) {
		$this->resetParam ();
		//$this->sort = "c.id";
		$this->searchArr = array ("assesId" => $assesId );
		return $this->list_d ( "select_readAll" );
	}

	/*
	 * �������������Ӧ�̣���Ҫȥ���Ѿ���ӵĹ�Ӧ��
	 */
	function addBatch_d($suppArr) {
		if (count ( $suppArr ) > 0) {
			$temp = reset ( $suppArr );
			$assesId = $temp ['assesId']; //�õ�������������
			$suppIdArr = array ();
			foreach ( $suppArr as $key => $value ) {
				$suppIdArr [] = $value ['suppId'];
			}
			$suppIds = implode ( ",", $suppIdArr );
			$this->searchArr = array ("assesId" => $assesId, "suppIds" => $suppIds );
			$suppsInDataBase = $this->list_d ();//�õ������������Ѿ������ݿ����������Ӧ��
			if (is_array ( $suppsInDataBase )) {
				foreach ( $suppsInDataBase as $key => $value ) {
					unset ( $suppArr [$value ['suppId']] );//��ȥ�Ѿ������ݿ��������Ӧ��
				}
			}
			return parent::addBatch_d ( $suppArr );
		}
		return false;
	}

	/**
	 * @desription ��ʾ��ҳ�б�
	 * @param tags
	 * @date 2010-11-18 ����10:26:44
	 */
	function sasPage_d () {
		$rows = $this->page_d ( "select_page" );
		return $rows;
	}

}
?>
