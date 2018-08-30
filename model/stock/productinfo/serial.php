<?php
class model_stock_productinfo_serial extends model_base{
	function __construct() {
		$this->tbl_name = "oa_stock_product_serial";
		$this->sql_map = "stock/productinfo/serialSql.php";
		parent :: __construct();
	}

	/**
	 * �����ʽ������к�-����
	 * ��������ر�������
	 * 1.���к� serial (��ʽΪ�ַ�����������","�������","��������λ����Ҫ",") �磺 a,b,c,d,
	 * 2.ҵ���� businessNo (�����ı�ţ��Ǵӱ�)
	 * 3.ҵ������ businessType (����ҵ�����ͣ�һ��Ϊ����)
	 * 4.��ƷID productId
	 * 5.��Ʒ���� productName
	 * 6.�ֿ�ID stockId
	 * 7.�ֿ����� stockName
	 */
	function batchIn($rows){

	}

	/**
	 * ������ʽ������к�-����
	 * ��������ر�������
	 * 1.���к� serial (��ʽΪ�ַ�����������","�������","��������λ����Ҫ",") �磺 a,b,c,d,
	 * 2.ҵ���� businessNo (�����ı�ţ��Ǵӱ�)
	 * 3.ҵ������ businessType (����ҵ�����ͣ�һ��Ϊ����)
	 * 4.��ƷID productId
	 * 5.��Ʒ���� productName
	 * 6.�ֿ�ID stockId
	 * 7.�ֿ����� stockName
	 */
	function batchOut($rows){
		if(!empty($rows['serial'])){
			$rows['serial'] = substr($rows['serial'],0,-1);
			$arr = explode(',',$rows['serial']);
			$sql = " insert into ".$this->tbl_name ."( serial ,businessId,businessType,productId,productName,stockId,isInStock,outOrIn) values " ;
			try{
				foreach($arr as $val ){
					$sql .= " ('$val','$rows[businessId]','$rows[businessType]','$rows[productId]','$rows[productName]','$rows[stockId]','0','0'),";
				}
				$sql = substr($sql,0,-1);
				$this->query($sql);
//			echo $sql;
			}catch(exception $e){
				throw $e;
			}
		}
	}

	/**
	 * ����ҵ���ţ�ҵ�����ͣ��Ͳ�ƷID��ȡ���к��б�
	 */
	function getSerial($businessId,$businessType,$productId=null){
		$rows = $this->findAll(array('businessId' => $businessId,'businessType' => $businessType),null,'serial,productId');
//		$arr = array();
		if($rows){
			foreach($rows as $val ){
				if(!isset($arr[$val['productId']])){
					$arr[$val['productId']] = $val['serial'].",";
				}else{
					$arr[$val['productId']] .= $val['serial'].",";
				}
			}
//			print_r($arr);
			return $arr;
		}
	}

	/**
	 * ����ͬ�ϣ�������
	 */
	function getSerialForRead($businessId,$businessType){
		$rows = $this->findAll(array('businessId' => $businessId,'businessType' => $businessType),null,'serial,productId');
//		$arr = array();
		if($rows){
			foreach($rows as $val ){
				if(!isset($arr[$val['productId']])){
					$arr[$val['productId']] = $val['serial'].",<br />";
				}else{
					$arr[$val['productId']] .= $val['serial'].",<br />";
				}
			}
//			print_r($arr);
			return $arr;
		}
	}

	/**
	 * Ϊ�豸�б�������к�
	 */
	function addSerial($rows,$srows){
		if($rows){
			foreach($rows as $key => $val){
				if(isset($srows[$val['productId']])){
					$rows[$key]['serial'] = $srows[$val['productId']];
				}
			}
			return $rows;
		}

	}

	/**
	 * ����ҵ��ID��ҵ������ɾ�����к�
	 */
	function delByOutStockId($businessId,$businessType){
		$this->delete(array ( 'businessId' => $businessId,'businessType' =>$businessType));
	}

}
?>
