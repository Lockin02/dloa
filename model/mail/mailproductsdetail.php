<?php
/*
 * Created on 2010-7-28
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_mail_mailproductsdetail extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_mail_products";
		$this->sql_map = "mail/mailproductsdetailSql.php";
		parent::__construct ();
	}
	
	/**
	 * �����ʼ���Ϣ��ȡ��Ʒ�嵥
	 */
	function getProductsDetail($mailInfoId) {
		$this->searchArr = array ('mailInfoId' => $mailInfoId );
		return $this->list_d ();
	}
	
	/**
	 * ɾ���ʼĲ�Ʒ
	 */
	function deleteProductsByMailId($mailId) {
		$condition = array ("mailInfoId" => $mailId );
		$this->delete ( $condition );
	}
	
	/*****************************������ʾ��***********************/
	/**
	 * �ڱ༭���ⵥʱ��ʾ��Ʒ�嵥
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
									<input type="text" name="" value="$val[productName]" size="40"/>
								</td>
							 	<td nowrap align="center" width="12%">
							 		<input type="text" name="" value="$val[mailNum]" size="12"/>
						 		</td>
						 		<td nowrap align="center" width="12%">
							 		<input type="text" name="" value="$val[serialNum]" size="12"/>
						 		</td>
							 	<td nowrap align="center" width="8%">
							 		<img src="images/closeDiv.gif" onclick="mydel(this,'productslist')" title="ɾ����">
							 	</td>
							</tr>
EOT;
			}
		}
		return $str;
	}
	
	/**
	 * �ڲ鿴���ⵥʱ��ʾ��Ʒ�嵥
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
			return "<tr align='center'><td colspan='4'>���������Ϣ</td></tr>";
		}
	
	}
}
?>
