<?php
/**
 * @author zengq
 * @Date 2012年10月7日 星期日 15:16:42
 * @version 1.0
 * @description:导师考核方案 Model层
 */
 class model_hr_tutor_tutorScheme  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_tutorscheme";
		$this->sql_map = "hr/tutor/tutorSchemeSql.php";
		parent::__construct ();
	}

	/**
	 * 重写新增方法
	 */
	 function add_d($object){
	 	try{
	 		$this->start_d();
			//判断导师考核方案名称是否重复
	 		$schemeName = $object['schemeName'];
	 		$tutorScheme = $this->findBy('schemeName',$schemeName);
			if(!empty($tutorScheme)){
				throw new Exception("导师考核方案名称重复!");
			}

			//往主表中添加数据
			$parentId=parent::add_d($object,true);

			//处理从表
			if(!empty($object['attrvals'])){
				$schemeDetailDao = new model_hr_tutor_schemeDetail();
				$schemeDetailDao->createBatch($object['attrvals'],array(
						'parentId'=>$parentId
					),'appraisal');
			}
			$this->commit_d();
			return true;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		throw $e;
	 		//return false;
	 	}
	 }
	 /**
	  * 重写编辑方法
	  */
	  function edit_d($object){
	  		try{
	  			$this->start_d();

	  			//编辑主表数据
				parent :: edit_d($object, true);

				$parentId = $object['id'];
				//处理从表数据
				$schemeDetailDao = new model_hr_tutor_schemeDetail();
				$mainArr=array("parentId"=>$parentId);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object['attrvals']);

				$schemeDetailDao->saveDelBatch($itemsArr);
	  			$this->commit_d();
	  			return true;
	  		}catch(Exception $e){
	  			$this->rollBack();
	  			return false;
	  		}
	  }
 }
?>