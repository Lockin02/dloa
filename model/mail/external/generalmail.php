<?php
/**
 * @filename	generalmail.php
 * @function	邮寄公用入口Model
 * @author		ouyang
 * @version	1.0
 * @datetime	2011-1-24
 * @lastmodify	2011-1-24
 * @package	oae/model/mail/external
 * @link		no
 */
class model_mail_external_generalmail extends model_base{

	/**
	 * 构造函数
	 *
	 */
	function __construct() {
		$this->tbl_name = "oa_mail_apply";
		$this->sql_map = "mail/mailapplySql.php";
		parent::__construct ();

		$this->mailStatus = array ("未处理", "已处理" ); //邮寄任务状态

	}

	function add_d($apply) {
		$apply = $this->addCreateInfo ( $apply );
		try {
			$this->start_d ();
			$apply ['mailDate'] = date ( "Y-m-d H:i:s" );
			$apply['ExaStatus']="待提交";
			$inid = parent::add_d ( $apply, true );
			//邮寄产品明细
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