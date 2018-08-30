<?php
/**
 * @author Show
 * @Date 2012年8月30日 星期四 14:38:15
 * @version 1.0
 * @description:员工试用计划模板明细 Model层
 */
 class model_hr_baseinfo_trialplantemdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_trialplantem_detail";
		$this->sql_map = "hr/baseinfo/trialplantemdetailSql.php";
		parent::__construct ();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'taskType'
    );

    /**
     * 根据任务类型返回关闭方式
     * 0 为需要审核
     * 1 为立即完成
     */
    function rtCloseType_c($thisVal){
		switch($thisVal){
			case 'HRSYRW-01' : return 1;break;
			case 'HRSYRW-02' : return 0;break;
			case 'HRSYRW-03' : return 0;break;
			case 'HRSYRW-04' : return 0;break;
			case 'HRSYRW-05' : return 0;break;
			default : return 0;
		}
    }

	/***************** 增删改查 ***************/
	//重写add_d
	function add_d($object){
		$object = $this->processDatadict($object);

		return parent::add_d($object);
	}

	//重写edit_d
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object);
	}

	/**************** 业务处理 ******************/
	/**
	 * 循环设值需要处理的信息
	 */
	function batchDeal_d($object,$addObj = array()){
		if($object){
			foreach($object as $key => $val){
				//数据加载
				$object[$key] = array_merge($object[$key],$addObj);

				//关闭规则设置
//				$object[$key]['closeType'] = $this->rtCloseType_c($object[$key]['taskType']);
			}
		}
		return $object;
	}
}
?>