<?php
/**
 * @description: ��Ӧ����ʱ���Ʒ��Ϣ
 * @date 2010-11-10 ����02:07:59
 * @author oyzx
 * @version V1.0
 */
class model_supplierManage_temporary_stproduct extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_prod_temp";
		$this->sql_map = "supplierManage/temporary/stproductSql.php";
		parent::__construct ();
	}

	/*
	 * ��д����������Ӧ�̲�Ʒ������������Ʒǰ��ɾ����Ӧ��Ʒ��Ϣ
	 */
	function add_d($product) {
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
				//print_r($products);
				$tag = $this->addBatch_d ( $products,true );
			}

			$this->commit_d ();
			return $tag;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/**
	 * ���������id�����ӱ��parentId����ȡ����
	 */
	function getProdByid_d($parentId) {
		$parentId = isset($parentId)?$parentId:'';
		$sql = "select c.parentId,c.productId,c.productName,c.createTime,c.createName,c.createId,c.updateTime,c.updateName,c.updateId from  oa_supp_prod_temp c where c.parentId=" . "'" . $parentId . "'";
		$rows = $this->pageBySql($sql);
		return $rows;

	}

}
?>
