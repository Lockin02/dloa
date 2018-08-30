<?php
/**
 * @author jianjungki
 * @Date 2012��8��3�� 11:01:18
 * @version 1.0
 * @description:Ա�����˷��� Model�� 
 */
 class model_hr_permanent_scheme  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_permanent_scheme";
		$this->sql_map = "hr/permanent/schemeSql.php";
		parent::__construct ();
	}
	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			$code = "SC".date("YmdHis");
			$object['formCode'] = $code;
			$object['formDate'] = date("Y-m-d");
			$datadict = new model_system_datadict_datadict();
			$object['schemeTypeName'] = $datadict->getDataNameByCode($object['schemeTypeCode']);
			$newId = parent::add_d ($object,true);
			$listDao = new model_hr_permanent_schemelist();
			$standardDao = new model_hr_permanent_standard();
			$obj = $object['standard'];
			foreach ($obj as $Item) {
				$Item['schemeName'] = $object['schemeName'];
				$Item['schemeCode'] = $code;
				$Item['parentId'] = $newId;
				$getstand = $standardDao->find(array("standard"=>$Item['standard']));
				$Item['standardId'] = $getstand['id'];
				$Item['standardCode'] = $getstand['standardCode'];
				$listDao->add_d($Item,false);
			}
			$this->commit_d();
		}catch(exception $e){
			$this->rollback();
			return null;
		}
		return $newId;
	}
	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object, $isEditInfo = false) {
		try{
			$datadict = new model_system_datadict_datadict();
			$object['schemeTypeName'] = $datadict->getDataNameByCode($object['schemeTypeCode']);
			$id = parent::edit_d($object,true);
			$standardDao=new model_hr_permanent_standard();
			$listDao = new model_hr_permanent_schemelist();
			$getstand = $standardDao->find(array("standard"=>$object['standard']['standard']));
			$mainArr=array(
				"parentId"=>$object ['id'],
				'schemeName'=>$object['schemeName'],
				'schemeCode'=>$object['formCode'],
				'standardId' => $getstand['id'],
				'standardCode' => $getstand['standardCode']
			);
			$listArr = util_arrayUtil::setArrayFn($mainArr,$object ['standard']);
			$listDao->saveDelBatch ( $listArr );
		}catch(exception $e){
			echo $e;
			return null;
		}
		return $id;
	}
	
 }
?>