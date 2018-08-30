<?php
/*
 * Created on 2010-7-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_mail_productsdetail extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_mailapply_products";
		$this->sql_map = "mail/productsdetailSql.php";
		parent::__construct ();
	}
	
	/**
	 * 插入邮寄申请单产品清单
	 */
	function addProducts($inid, $object) {
		if ($object) {
			try {
				foreach ( $object as $key => $val ) {
					if ($val [productNo] != "" || $val [productName] != "" || $val [mailNum] != "") {
						$val [productId] = empty ( $val [productId] ) ? 0 : $val [productId];
						$str = "insert into " . $this->tbl_name . " (mailApplyId,productId,productNo,productName,mailNum) values  ( '$inid','$val[productId]','$val[productNo]','$val[productName]','$val[mailNum]' )";
						$this->query ( $str );
					} else {
						continue;
					}
				}
				return true;
			} catch ( exception $e ) {
				throw $e;
			}
		
		}
	}
	
	/**
	 * 根据邮寄申请获取产品清单
	 */
	function getProductsDetail($mailApplyId) {
		return $this->findAll ( array ('mailApplyId' => $mailApplyId ) );
	}
	
	/**
	 * 删除邮寄产品
	 */
	function deleteProductsByApplyId($applyId) {
		$condition = array ("mailApplyId" => $applyId );
		$this->delete ( $condition );
	}
	
	/*****************************数据显示层***********************/
	/**
	 * 在编辑出库单时显示产品清单
	 */
	function showProductsDetailInEdit($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ( $rows as $val ) {
				$i ++;
				$str .= <<<EOT
							<tr align="center">
								<td width="8%">$i</td>
								<td nowrap align="center">
							 		<input type="text" name="" value="$val[productNo]" size="30"/>
								</td>
							 	<td nowrap align="center">
									<input type="text" name="" value="$val[productName]" size="40"/>
								</td>
							 	<td nowrap align="center" width="12%">
							 		<input type="text" name="" value="$val[mailNum]" size="12"/>
						 		</td>
							 	<td nowrap align="center" width="8%">
							 		<img src="images/closeDiv.gif" onclick="mydel(this,'productslist')" title="删除行">
							 	</td>
							</tr>
EOT;
			}
		}
		return $str;
	}
	
	/**
	 * 在查看出库单时显示产品清单
	 */
	function showProductsDetailInRead($rows) {
		$i = 0;
		if ($rows) {
			$i ++;
			$str = "";
			foreach ( $rows as $val ) {
				$str .= <<<EOT
							<tr align="center">
								<td width="8%">$i</td>
								<td nowrap align="center">
							 		$val[productNo]
								</td>
							 	<td nowrap align="center">
									$val[productName]
								</td>
							 	<td nowrap align="center" width="12%">
							 		$val[mailNum]
						 		</td>
							</tr>
EOT;
			}
			return $str;
		} else {
			return "<tr align='center'><td colspan='4'>暂无相关信息</td></tr>";
		}
	
	}
}
?>
