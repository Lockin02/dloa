<?php
/**
 * @description: ����ӿ���
 * @date 2010-12-21 ����04:27:38
 * @author oyzx
 * @version V1.0
 */
class model_common_interface_obj {

	private $objArr;	//�ṹ��������

	function __construct( $fileName='purchplan' ) {
		include(WEB_TOR.'model/common/interface/'.$fileName.'Arr.php');
		$this->objArr = isset( $interface_arr )? $interface_arr : null ;
	}

	/**
	 * @desription ͨ��Key��ȡ��������
	 * @param tags
	 * @date 2010-12-21 ����04:50:09
	 */
	function typeKToC($k) {
		if(is_array($this->objArr)){
			foreach( $this->objArr as $key =>$val){
				if($val['key']==$k){
					$returnVal = $val['objNameC'];
				}
			}

		}
		return isset( $returnVal )? $returnVal : null ;
	}

	/**
	 * @desription ͨ��Key��ȡ����
	 * @param tags
	 * @date 2011-1-11 ����02:18:51
	 */
	function typeKToObj ( $k ) {
		if(is_array($this->objArr)){
			foreach( $this->objArr as $key => $val ){
				if( $val['key']== $k ){
					$returnVal = $val['objClass'];
				}
			}
		}
		return isset( $returnVal )? $returnVal : null ;
	}

	/**
	 * @desription �ӹ�ϵ�������ҵ�������������
	 * @param tags
	 * @date 2010-12-21 ����05:30:19
	 */
	function typeArrToC ( $arr ) {
		foreach( $arr as $key => $val ){
			$retVal = $this->typeKToC( $val['objAssType'] );
			if( $retVal !=null ){
				return $retVal;
				break;
			}
		}
	}









//--------------------��Ч�Ŀ�ʼ
	/**
	 * �ɹ�����
	 * ͨ��value����״̬
	 */
	function purchTypeToVal($purchVal){
		$returnVal = false;
		foreach( $this->purchaseType as $key => $val ){
			if( $val['purchKey']== $purchVal ){
				$returnVal = $val['purchCName'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * �ɹ�����
	 * ��������
	 */
	function purchTypeToArr($purchVal){
		$returnVal = false;
		foreach( $this->purchaseType as $key => $val ){
			if( $val['purchKey']== $purchVal ){
				$returnVal = $val;
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * �ɹ�����
	 * ͨ��״̬����value
	 */
	function purchTypeToSta($purchVal){
		$returnVal = false;
		foreach( $this->purchaseType as $key => $val ){
			if( $val['purchCName']== $purchVal ){
				$returnVal = $val['purchKey'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * �������вɹ�����ת��������
	 */
	function purchTypArrayCName($chiRows){
		foreach ( $chiRows as $chiKey => $chiVal ){
			$showStatus = $this->purchTypeToVal( $chiVal['typeTabName'] );
			$chiRows[$chiKey]['purchTypeCName']  = $showStatus;
		}
		return $chiRows;
	}
//--------------------��Ч�Ľ���


}
?>
