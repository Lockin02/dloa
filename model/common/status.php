<?php
/**
 * @description: 状态基础类
 * @date 2010-9-16 上午10:42:47
 * @author oyzx
 * @version V1.0
 */
class model_common_status {

	private $status = null;

	/**
	 * @desription 构造函数
	 * @date 2010-9-13 下午05:26:49
	 */
	function __construct() {
		$this->status = array(
			array(
				"statusEName" => "save",
				"statusCName" => "保存",
				"key" => "1"
			)
		);
	}

	/**
	 * @desription GET方法
	 * @date 2010-9-16 上午10:47:09
	 */
	function __get ($property_name) {
		return $property_name;
	}

	/**
	 * @desription SET方法
	 * @date 2010-9-16 上午10:50:54
	 */
	function __set ($property_name,$value) {
		$this->$property_name = $value;
	}


	/**
	 * @desription 通过引索值获取中文值
	 * @param 状态英文值
	 * @return 状态中文名
	 * @date 2010-9-16 上午10:08:39
	 */
	function statusEtoC ($statusEName) {
		$returnVal = null;
		foreach( $this->status as $key => $val ){
			if( $val['statusEName']== $statusEName ){
				$returnVal = $val['statusCName'];
			}
		}
		return $returnVal;
	}

	/**
	 * @desription 通过引索值获取KEY
	 * @param 状态英文值
	 * @return 状态Key
	 * @date 2010-9-16 上午10:09:43
	 */
	function statusEtoK ($statusEName) {
		$returnVal = null;
		foreach( $this->status as $key => $val ){
			if( $val['statusEName']== $statusEName ){
				$returnVal = $val['key'];
			}
		}
		return $returnVal;
	}

	/**
	 * @desription 通过Key获取中文值
	 * @param 状态Key
	 * @return 状态中文名
	 * @date 2010-9-16 上午10:35:25
	 */
	function statusKtoC ($statusKey) {
		$returnVal = null;
		foreach( $this->status as $key => $val ){
			if( $val['key']== $statusKey ){
				$returnVal = $val['statusCName'];
			}
		}
		return $returnVal;
	}
	/**
	 * @desription 通过中文值 获取 Key
	 * @param  状态中文名
	 * @return 状态Key
	 * @date 2012-10-23 18:35:25
	 */
	function statusCtoK($statusCName) {
		$returnVal = null;
		foreach( $this->status as $key => $val ){
			if( $val['statusCName']== $statusCName ){
				$returnVal = $val['key'];
			}
		}
		return $returnVal;
	}


}
?>
