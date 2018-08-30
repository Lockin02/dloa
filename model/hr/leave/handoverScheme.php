<?php
/**
 * @author Administrator
 * @Date 2012��10��26�� ������ 17:05:37
 * @version 1.0
 * @description:��ְ�嵥ģ�巽�� Model��
 */
 class model_hr_leave_handoverScheme  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_leave_handover_scheme";
		$this->sql_map = "hr/leave/handoverSchemeSql.php";
		parent::__construct ();
	}

	 /**
    * ��дadd����
    */
    function add_d($obj){
		try{
			$this->start_d();
			//����������
			$obj['schemeCode']='LZFA'.$this->getRandNum();
			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			$obj['leaveTypeName'] = $datadictDao->getDataNameByCode($obj['leaveTypeCode']);
			//������������
			$schemeId=parent::add_d($obj,true);
			if(!empty($obj['attrvals'])){
			//����ģ��ֵ�ֶ�
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
	 * ��д�༭����
	 */
	function edit_d($obj){
		try{
			$this->start_d();

			//���������ֵ��ֶ�
			$datadictDao = new model_system_datadict_datadict();
			$obj['leaveTypeName'] = $datadictDao->getDataNameByCode($obj['leaveTypeCode']);
			//������������
			parent :: edit_d($obj, true);
			$parentCode = $obj['id'];
			//����ģ��ֵ�ֶ�
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
	 * ����ʮλ�������
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