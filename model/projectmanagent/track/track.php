<?php
/**
 * @author Administrator
 * @Date 2011��3��3�� 11:28:42
 * @version 1.0
 * @description:���ټ�¼ Model�� ���ټ�¼
 */
 class model_projectmanagent_track_track  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_track";
		$this->sql_map = "projectmanagent/track/trackSql.php";
		parent::__construct ();
	}


	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$newId = $this->create ( $object );

        $chanceDao = new model_projectmanagent_chance_chance();
        $chanceDao->updateChanceNewDate($object['chanceId']);

		//���������ƺ�Id
		$this->updateObjWithFile($newId);
		return $newId;
	}
	

 }
?>