<?php
/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:19:03
 * @version 1.0
 * @description:任职资格等级认证评价表模板 Model层
 */
class model_hr_baseinfo_certifytemplate extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_certifytemplate";
		$this->sql_map = "hr/baseinfo/certifytemplateSql.php";
		parent :: __construct();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'careerDirection','baseLevel','baseGrade'
    );

    //状态数组
    function rtStatus_d($status){
		if($status == 1){
			return '启用';
		}else{
			return '关闭';
		}
    }

	/**************** 增删改查 ************************/
	/**
	 * 重写add_d
	 */
	function add_d($object){
//		echo "<pre>";print_r($object);die();
        //获取行为要项
        $certifytemplatedetail = $object['certifytemplatedetail'];
        unset($object['certifytemplatedetail']);

		try {
			$this->start_d ();
			//数据字典中文处理
			$object = $this->processDatadict($object);

			//新增任务
			$newId = parent::add_d ( $object, true );

            //处理任务成员
            $certifytemplatedetailDao = new model_hr_baseinfo_certifytemplatedetail();
            $certifytemplatedetailDao->createBatch($certifytemplatedetail,array('modelId' => $newId));

			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 重写edit_d
	 */
	function edit_d($object){
        //获取行为要项
        $certifytemplatedetail = $object['certifytemplatedetail'];
        unset($object['certifytemplatedetail']);

		try {
			$this->start_d ();
			//数据字典中文处理
			$object = $this->processDatadict($object);

			//新增任务
			parent::edit_d ( $object, true );

            //处理任务成员
            $certifytemplatedetailDao = new model_hr_baseinfo_certifytemplatedetail();
            $certifytemplatedetailDao->saveDelBatch($certifytemplatedetail);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/********************** 业务逻辑 ***********************/
	/**
	 * 判断是否存在另一个模版
	 */
	function isAnotherTemplate_d($careerDirection,$baseLevel,$baseGrade,$id = null){
		//查询条件拼装
		$this->searchArr = array(
			'careerDirection' => $careerDirection,
			'baseLevel' => $baseLevel,
			'baseGrade' => $baseGrade
		);
		if($id){
			$this->searchArr['noId'] = $id;
		}
		$rs = $this->list_d();
		if($rs){
			return $rs[0]['id'];
		}else{
			return false;
		}
	}

	/**
	 * 关闭项目
	 */
	function close_d($id){
		$object['id'] = $id;
		$object['status'] = 0;

		return parent::edit_d($object,true);
	}
}
?>