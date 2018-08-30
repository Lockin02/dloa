<?php
/**
 * @description: 变更通知Model
 * @date 2010-12-24 上午10:08:29
 * @author oyzx
 * @version V1.0
 */
class model_purchase_change_notice extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purch_change_notice";
		$this->sql_map = "purchase/change/noticeSql.php";
		//		$this->state = array (
		//			0 => array (
		//				'stateEName' => 'execute',
		//				'stateCName' => '未接收',
		//				'stateVal' => '0' ),
		//			1 => array (
		//				'stateEName' => 'Locking',
		//				'stateCName' => '已接收',
		//				 'stateVal' => '1' ) );
		//变更注册信息
		$this->changeArr = array ("plan" => array ("subject" => "采购计划变更" ), "task" => array ("subject" => "采购任务变更" ), "contract" => array ("subject" => "采购合同变更" ) );
		parent::__construct ();
	}

	/**
	 * 添加变更通知
	 */
	function add_d($notice) {
		$notice ['changeNumb'] = "changenotice-" . generatorSerial ();
		$notice ['subject'] = $this->getModelChangeSubject ( $notice ['modelCode'] );
		$notice ['state'] = 0; //默认未接收状态   1为已接收状态
		$notice = $this->addCreateInfo ( $notice );
		$newId = $this->create ( $notice );
		return $newId;
	}

	/**
	 * 根据模块key获取变更信息数组
	 */
	private function getModelChangeArr($modelCode) {
		return $this->changeArr [$modelCode];
	}

	/**
	 * 根据模块key获取变更主题
	 */
	private function getModelChangeSubject($modelCode) {
		$arr = $this->getModelChangeArr ( $modelCode );
		return $arr ['subject'];
	}

	/**
	 * 接收变更通知
	 */
	function receive_d($noticeId) {
		$sql = "update " . $this->tbl_name . " set state=1 where id=" . $noticeId;
		$this->query ( $sql );
	}

	/**
	 * 获取本变更的全部下属表单数据
	 *
	 * @param	$id		变更单Id
	 * @return return_type
	 */
	function getChildArr_d ( $id ) {
		//return true;
	}

}
?>
