<?php
/**
 * @description: ���֪ͨModel
 * @date 2010-12-24 ����10:08:29
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
		//				'stateCName' => 'δ����',
		//				'stateVal' => '0' ),
		//			1 => array (
		//				'stateEName' => 'Locking',
		//				'stateCName' => '�ѽ���',
		//				 'stateVal' => '1' ) );
		//���ע����Ϣ
		$this->changeArr = array ("plan" => array ("subject" => "�ɹ��ƻ����" ), "task" => array ("subject" => "�ɹ�������" ), "contract" => array ("subject" => "�ɹ���ͬ���" ) );
		parent::__construct ();
	}

	/**
	 * ��ӱ��֪ͨ
	 */
	function add_d($notice) {
		$notice ['changeNumb'] = "changenotice-" . generatorSerial ();
		$notice ['subject'] = $this->getModelChangeSubject ( $notice ['modelCode'] );
		$notice ['state'] = 0; //Ĭ��δ����״̬   1Ϊ�ѽ���״̬
		$notice = $this->addCreateInfo ( $notice );
		$newId = $this->create ( $notice );
		return $newId;
	}

	/**
	 * ����ģ��key��ȡ�����Ϣ����
	 */
	private function getModelChangeArr($modelCode) {
		return $this->changeArr [$modelCode];
	}

	/**
	 * ����ģ��key��ȡ�������
	 */
	private function getModelChangeSubject($modelCode) {
		$arr = $this->getModelChangeArr ( $modelCode );
		return $arr ['subject'];
	}

	/**
	 * ���ձ��֪ͨ
	 */
	function receive_d($noticeId) {
		$sql = "update " . $this->tbl_name . " set state=1 where id=" . $noticeId;
		$this->query ( $sql );
	}

	/**
	 * ��ȡ�������ȫ������������
	 *
	 * @param	$id		�����Id
	 * @return return_type
	 */
	function getChildArr_d ( $id ) {
		//return true;
	}

}
?>
