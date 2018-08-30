<?php
/**
 *��Ӧ��Ʒmodel����
 */
class model_supplierManage_formal_sfproduct extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_prod";
		$this->sql_map = "supplierManage/formal/sfproductSql.php";
		parent::__construct ();
	}
	/**
	 * �鿴��Ӧ��Ʒ
	 */
	function proInSupp($parentId) {
		$this->searchArr ['parentId'] = $parentId; //����ID
		return $this->pageBySqlId ( 'readproInSupp' );
	}

	/*
	 * ��д����������Ӧ�̲�Ʒ������������Ʒǰ��ɾ����Ӧ��Ʒ��Ϣ
	 */
	function add_d($product) {
//		echo "<pre>";
//		print_r($product);
		try {
			$this->start_d ();
			$productNames = explode ( ",", $product ['productNames'] );
			$productIds = explode ( ",", $product ['productIds'] );
			$products = array ();
			$sql = "delete from " . $this->tbl_name . " where parentId=" . $product ['parentId'];
			$this->query ( $sql );
			if (is_array ( $productIds )) {
				foreach ( $productIds as $key => $value ) {
					$products [$key] ['productId'] = $value;
					$products [$key] ['productName'] = $productNames [$key];
					$products [$key] ['parentCode'] = $product ['parentCode'];
					$products [$key] ['parentId'] = $product ['parentId'];
				}
				$tag=$this->addBatch_d ( $products );
			}

			$this->commit_d ();
			return $tag;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/*
	 * ����ʱ����Ĺ�Ӧ�̲�Ʒת�浽��Ӫ����ȥ��
	 */
	function addprodFromTempToForm_d($product) {
		try {
			$this->start_d ();
			$productNames = explode ( ",", $product ['productName'] );
			$productIds = explode ( ",", $product ['productId'] );
			$products = array ();
			if (is_array ( $productIds )) {
				foreach ( $productIds as $key => $value ) {
					$products [$key] ['productId'] = $value;
					$products [$key] ['productName'] = $productNames [$key];
//					$products [$key] ['parentCode'] = $product ['parentCode'];
					$products [$key] ['parentId'] = $product ['parentId'];
				}
				$tag=$this->addBatch_d ( $products );
			}

			$this->commit_d ();
			return $tag;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * @desription ����parentId��ȡ��Ӧ������
	 * @param tags
	 * @date 2010-11-22 ����06:26:16
	 */
	function getProdByid_d ($parentId) {
		$parentId = isset($parentId)?$parentId:'';
		$sql = "select c.parentId,c.productId,c.productName,c.createTime,c.createName,c.createId,c.updateTime,c.updateName,c.updateId from oa_supp c where c.parentId=" . "'" . $parentId . "'";
		$rows = $this->pageBySql($sql);
		return $rows;
	}

}
?>
