<?php
/**
 * @author Administrator
 * @Date 2012年10月26日 星期五 17:05:37
 * @version 1.0
 * @description:离职清单模板方案 Model层
 */
 class model_hr_leave_handoverScheme  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_leave_handover_scheme";
		$this->sql_map = "hr/leave/handoverSchemeSql.php";
		parent::__construct ();
	}

	 /**
    * 重写add方法
    */
    function add_d($obj){
		try{
			$this->start_d();
			//编号随机生成
			$obj['schemeCode']='LZFA'.$this->getRandNum();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$obj['leaveTypeName'] = $datadictDao->getDataNameByCode($obj['leaveTypeCode']);
			//处理主表数据
			$schemeId=parent::add_d($obj,true);
			if(!empty($obj['attrvals'])){
			//处理模板值字段
			$formworkDao = new model_hr_leave_formwork();
			$formworkDao->createBatch($obj['attrvals'],array(
					'parentCode'=>$schemeId
				),'items');
			}
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
    }
	/**
	 * 重写编辑方法
	 */
	function edit_d($obj){
		try{
			$this->start_d();

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$obj['leaveTypeName'] = $datadictDao->getDataNameByCode($obj['leaveTypeCode']);
			//处理主表数据
			parent :: edit_d($obj, true);
			$parentCode = $obj['id'];
			//处理模板值字段
			$formworkDao = new model_hr_leave_formwork();
			$mainArr=array("parentCode"=>$obj['id']);
			$itemsArr=util_arrayUtil::setArrayFn($mainArr,$obj ['attrvals']);


			$formworkDao->saveDelBatch($itemsArr);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

    /**
	 * 生成十位的随机数
	 */
	function getRandNum(){
		$num = '';
		for($i=0;$i<10;$i++){
			$num.=rand(0,9);
		}
		return $num;
	}
 }
?>