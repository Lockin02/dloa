<?php
/**
 * @description: ״̬������
 * @date 2010-9-16 ����10:42:47
 * @author oyzx
 * @version V1.0
 */
class model_common_status {

	private $status = null;

	/**
	 * @desription ���캯��
	 * @date 2010-9-13 ����05:26:49
	 */
	function __construct() {
		$this->status = array(
			array(
				"statusEName" => "save",
				"statusCName" => "����",
				"key" => "1"
			)
		);
	}

	/**
	 * @desription GET����
	 * @date 2010-9-16 ����10:47:09
	 */
	function __get ($property_name) {
		return $property_name;
	}

	/**
	 * @desription SET����
	 * @date 2010-9-16 ����10:50:54
	 */
	function __set ($property_name,$value) {
		$this->$property_name = $value;
	}


	/**
	 * @desription ͨ������ֵ��ȡ����ֵ
	 * @param ״̬Ӣ��ֵ
	 * @return ״̬������
	 * @date 2010-9-16 ����10:08:39
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
	 * @desription ͨ������ֵ��ȡKEY
	 * @param ״̬Ӣ��ֵ
	 * @return ״̬Key
	 * @date 2010-9-16 ����10:09:43
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
	 * @desription ͨ��Key��ȡ����ֵ
	 * @param ״̬Key
	 * @return ״̬������
	 * @date 2010-9-16 ����10:35:25
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
	 * @desription ͨ������ֵ ��ȡ Key
	 * @param  ״̬������
	 * @return ״̬Key
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
