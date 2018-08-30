<?php
/**
 * @author phz
 * @Date 2014年1月20日 星期一 10:37:38
 * @version 1.0
 * @description:工单 Model层 
 */
 class model_outsourcing_workorder_order  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_workorder_order";
		$this->sql_map = "outsourcing/workorder/orderSql.php";
		parent::__construct ();
	}   
	//新增工单数据
	function add_d(){
		try{
			$obj = $_POST['order'];
			$objequ = $obj['orderequ'];
			unset($obj['orderequ']);
			$id = parent::add_d($obj,true);
			if($objequ){
				$orderequ = new model_outsourcing_workorder_orderequ();
				foreach($objequ as $key =>$val){
					$objequ[$key]['parentId'] = $id;
				}
				$equId = $orderequ->saveDelBatch($objequ);
			}
			$this->commit_d ();
			return 1;
		}catch( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}  
	//编辑工单数据
	function edit_d(){
//		echo"<pre>";
//		print_r($_POST['order']);
//		die();
		try{
			$obj = $_POST['order'];
			$objequ = $obj['orderequ'];
			unset($obj['orderequ']);
			parent::edit_d($obj,true);
			$orderequ = new model_outsourcing_workorder_orderequ();
			if($objequ){
				$equ = $orderequ->saveDelBatch($objequ);
				$orderequ->searchArr = array("parentId"=>$obj['id']);
				$equList = $orderequ->list_d();
				$equList = $this->getId($equList);
				$equ = $this->getId($equ);
				$diff = array_diff($equList,$equ);
				if($diff){
					foreach($diff as $key => $val){
						$ids .= $val.",";
					}
					$ids = substr($ids,0,-1);
					$result = $orderequ->deletes($ids);
				}
			}
			else{
				$parentId = array("parentId"=>$obj['id']);
				$orderequ->delete($parentId);
			}
			$this->commit_d ();
			return 1;
		}catch( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}  
	/*
	 * 提取数组Id
	 */
	function getId($obj){
		if($obj){
			$idArr = array();
			foreach($obj as $key=>$val){
				array_push ( $idArr, $val['id'] );
			}
			return $idArr;
		}
		else{
			return null;
		}
	}
 }
?>