<?php
/**
 *高级搜索方案model层
 */
 class model_system_adv_advcase  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_adv_case";
		$this->sql_map = "system/adv/advcaseSql.php";
		parent::__construct ();
	}

	/**
	 * 添加高级搜索方案
	 */
	function add_d($case) {
		try{
			$this->start_d ();
			$this->processAdvsearch($case['detail']);
			$case['caseSql']=mysql_real_escape_string($this->searchArr['advSql']);
			$advcaseId=parent::add_d($case,true);
			if(is_array($case['detail'])){
				$detailDao=new model_system_adv_advcasedetail();
				foreach($case['detail'] as $key=>$val){
					$val['caseId']=$advcaseId;
					if (util_jsonUtil::is_utf8 ( $val['value'] )) {
						$val['value'] = util_jsonUtil::iconvUTF2GB ( $val['value'] );
					}
					$detailDao->add_d($val);
				}
			}
			$this->commit_d ();
			return $advcaseId;
		} catch ( Exception $e ) {
			$this->rollBack ();
		}

	}

	/**
	 * 编辑高级搜索方案
	 */
	function edit_d($case){
		try{
			$this->start_d ();
			$this->processAdvsearch($case['detail']);
			$case['caseSql']=mysql_real_escape_string($this->searchArr['advSql']);
			if(is_array($case['detail'])){
				$detailDao=new model_system_adv_advcasedetail();
				$mainArr=array("caseId"=>$case ['id']);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$case ['detail']);
				foreach($itemsArr as $key=>$val){
					if (util_jsonUtil::is_utf8 ( $itemsArr[$key]['value'] )) {
						 $itemsArr[$key]['value'] = util_jsonUtil::iconvUTF2GB (  $itemsArr[$key]['value'] );
					}
				}
				$detailDao->saveDelBatch($itemsArr);
			}
			parent::edit_d($case);
			$this->commit_d ();
		}catch ( Exception $e ) {
			$this->rollBack ();
		}

	}

	/**
	 * 批量删除对象
	 */
	function deletes_d($ids) {
		//先删除方案明细
		$this->query("delete from oa_adv_case_detail where caseId=".$ids);
		return parent::deletes_d($ids);
	}

 }
?>