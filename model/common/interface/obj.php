<?php
/**
 * @description: 对象接口类
 * @date 2010-12-21 下午04:27:38
 * @author oyzx
 * @version V1.0
 */
class model_common_interface_obj {

	private $objArr;	//结构对象数组

	function __construct( $fileName='purchplan' ) {
		include(WEB_TOR.'model/common/interface/'.$fileName.'Arr.php');
		$this->objArr = isset( $interface_arr )? $interface_arr : null ;
	}

	/**
	 * @desription 通过Key获取类型名称
	 * @param tags
	 * @date 2010-12-21 下午04:50:09
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
	 * @desription 通过Key获取对象
	 * @param tags
	 * @date 2011-1-11 下午02:18:51
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
	 * @desription 从关系数组中找到中文类型名称
	 * @param tags
	 * @date 2010-12-21 下午05:30:19
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









//--------------------无效的开始
	/**
	 * 采购类型
	 * 通过value查找状态
	 */
	function purchTypeToVal($purchVal){
		$returnVal = false;
		foreach( $this->purchaseType as $key => $val ){
			if( $val['purchKey']== $purchVal ){
				$returnVal = $val['purchCName'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 采购类型
	 * 返回数组
	 */
	function purchTypeToArr($purchVal){
		$returnVal = false;
		foreach( $this->purchaseType as $key => $val ){
			if( $val['purchKey']== $purchVal ){
				$returnVal = $val;
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 采购类型
	 * 通过状态查找value
	 */
	function purchTypeToSta($purchVal){
		$returnVal = false;
		foreach( $this->purchaseType as $key => $val ){
			if( $val['purchCName']== $purchVal ){
				$returnVal = $val['purchKey'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 将数组中采购类型转换成中文
	 */
	function purchTypArrayCName($chiRows){
		foreach ( $chiRows as $chiKey => $chiVal ){
			$showStatus = $this->purchTypeToVal( $chiVal['typeTabName'] );
			$chiRows[$chiKey]['purchTypeCName']  = $showStatus;
		}
		return $chiRows;
	}
//--------------------无效的结束


}
?>
