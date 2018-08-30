<?php
/**
 *供应产品model层类
 */
class model_supplierManage_formal_sfproduct extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_prod";
		$this->sql_map = "supplierManage/formal/sfproductSql.php";
		parent::__construct ();
	}
	/**
	 * 查看供应产品
	 */
	function proInSupp($parentId) {
		$this->searchArr ['parentId'] = $parentId; //任务ID
		return $this->pageBySqlId ( 'readproInSupp' );
	}

	/*
	 * 重写批量新增供应商产品方法，新增产品前先删除供应产品信息
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
	 * 将临时库里的供应商产品转存到运营库里去。
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
	 * @desription 根据parentId获取相应的数据
	 * @param tags
	 * @date 2010-11-22 下午06:26:16
	 */
	function getProdByid_d ($parentId) {
		$parentId = isset($parentId)?$parentId:'';
		$sql = "select c.parentId,c.productId,c.productName,c.createTime,c.createName,c.createId,c.updateTime,c.updateName,c.updateId from oa_supp c where c.parentId=" . "'" . $parentId . "'";
		$rows = $this->pageBySql($sql);
		return $rows;
	}

}
?>
