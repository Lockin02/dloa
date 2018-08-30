<?php
/**
 * @filename	generalmail.php
 * @function	�ʼĹ������Model
 * @author		ouyang
 * @version	1.0
 * @datetime	2011-1-24
 * @lastmodify	2011-1-24
 * @package	oae/model/mail/external
 * @link		no
 */
class model_mail_external_generalmail extends model_base{

	/**
	 * ���캯��
	 *
	 */
	function __construct() {
		$this->tbl_name = "oa_mail_apply";
		$this->sql_map = "mail/mailapplySql.php";
		parent::__construct ();

		$this->mailStatus = array ("δ����", "�Ѵ���" ); //�ʼ�����״̬

	}

	function add_d($apply) {
		$apply = $this->addCreateInfo ( $apply );
		try {
			$this->start_d ();
			$apply ['mailDate'] = date ( "Y-m-d H:i:s" );
			$apply['ExaStatus']="���ύ";
			$inid = parent::add_d ( $apply, true );
			//�ʼĲ�Ʒ��ϸ
			if (is_array ( $apply [productsdetail] )) {
				$productsDetailDao = new model_mail_productsdetail ();
				foreach ( $apply [productsdetail] as $key => $shipproduct ) {
					if (! empty ( $shipproduct ['productName'] )) {
						$shipproduct ['mailApplyId'] = $inid;
						$productsDetailDao->add_d ( $shipproduct );
					}
				}
			}
			$this->commit_d ();
			return $inid;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
}
?>