<?php
/**
 * @author Show
 * @Date 2011年12月27日 星期二 9:50:27
 * @version 1.0
 * @description:车辆信息(oa_carrental_carinfo) Model层 车辆状态 status
                                                  0 生效
                                                  1 失效
 */
 class model_carrental_carinfo_carinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_carinfo";
		$this->sql_map = "carrental/carinfo/carinfoSql.php";
		parent::__construct ();
	}

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'carType'
    );

    //是否
    function rtYesOrNo($val){
    	if($val == 1){
			return '是';
    	}else{
    		return '否';
    	}
    }

	/********************** 外部信息获取 ***********************/

	/**
	 * 获取租车单位信息
	 */
	function getUnitsItems_d($id) {
		$unitsDao = new model_carrental_units_units ();
		$units = $unitsDao->get_d ( $id );
		return $units;
	}


	/*********************** 增删改查 **************************/
	/**
	 * 新增方法
	 */
	function add_d($object){
		$object = $this->processDatadict($object);
		return parent::add_d($object,true);
	}

	/**
	 * 更新测试卡累计金额
	 */
	function updateUseDays_d($id,$useDays){
		try{
			$object = array(
				'id' => $id,
				'useDays' => $useDays
			);
			$this->edit_d($object,true);

			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}
}
?>