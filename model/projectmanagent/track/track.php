<?php
/**
 * @author Administrator
 * @Date 2011年3月3日 11:28:42
 * @version 1.0
 * @description:跟踪记录 Model层 跟踪记录
 */
 class model_projectmanagent_track_track  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_track";
		$this->sql_map = "projectmanagent/track/trackSql.php";
		parent::__construct ();
	}


	/**
	 * 添加对象
	 */
	function add_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$newId = $this->create ( $object );

        $chanceDao = new model_projectmanagent_chance_chance();
        $chanceDao->updateChanceNewDate($object['chanceId']);

		//处理附件名称和Id
		$this->updateObjWithFile($newId);
		return $newId;
	}
	

 }
?>