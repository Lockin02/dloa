<?php
/**
 * @author chengl
 * @Date 2012年8月20日 17:03:17
 * @version 1.0
 * @description:盘点信息
 */
class model_hr_inventory_inventory  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_inventory";
		$this->sql_map = "hr/inventory/inventorySql.php";
		parent::__construct ();
	}


	/**
	 * 根据阶段及部门id获取盘点信息
	 */
	function getByStageAndDept($stageId,$deptId){
		$this->searchArr['stageId']=$stageId;
		$this->searchArr['deptId']=$deptId;
		$list=$this->list_d();
		if(count($list)>0){
			return $list[0];
		}
		return null;
	}


	/*
	 * 拼写从表文本域
	 */
	function trtd($templateId,$inventoryId){
		$templatesummary=new model_hr_inventory_templatesummary();
		$templatesummaryArr=$templatesummary->list_d();
		$inventorysummaryvalueDao=new model_hr_inventory_inventorysummaryvalue();
		$inventorysummaryvalueArr=$inventorysummaryvalueDao->findAll(array("inventoryId"=>$inventoryId));
		$question="";
		$count=0;
		foreach($templatesummaryArr as $key => $val){
			if($val['templateId']==$templateId){
				foreach($inventorysummaryvalueArr as $k =>$v){
					if($val['question']==$v['question']){
						$answer=$v['answer'];
						break;
					}
				}
				$count++;
				$question.="<tr><td align='left' colspan='6'>" .$count.".   "."<span style=color:blue>$val[question]</span><input type='hidden' class='rimless_text' value=$val[question] name='inventory[$key][question]' readonly='readonly' /><br/><textarea name='inventory[$key][answer]' class=txt_txtarea_biglong>$answer</textarea></td></tr>";
			}
		}
		return $question;
	}

	/**
	 * 重写编辑方法
	 */
	function edit_d($obj){
		try{
			$this->start_d();
			$detailDao=new model_hr_inventory_inventorydetail();
			//新增
			if(empty($obj['id'])){
				$id=parent::add_d($obj,true);
			}else{//编辑
				//先删掉从表信息
				$detailDao->delete(array("inventoryId"=>$obj['id']));
				parent::edit_d($obj);
				$id=$obj['id'];
			}
			$attIdArr=explode(",", $obj['attrIds']);
			$attNameArr=explode(",", $obj['attrNames']);
			$detailValueDao=new model_hr_inventory_inventorydetailvalue();
			foreach($obj['details'] as $key=>$val){
				$val['inventoryId']=$id;
				$detailId=$detailDao->add_d($val,true);
				foreach($attIdArr as $k=>$v){
					$vArr=array(
						"detailId"=>$detailId,
						"attrId"=>$v,
						"attrName"=>$attNameArr[$k],
						"attrVal"=>$val["attr_".$v]
					);
					$detailValueDao->add_d($vArr,true);
				}
			}
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	function getState_d($id){
		$inventoryArr=$this->find(array("stageId"=>$id));
		$inventorySV=new model_hr_inventory_inventorysummaryvalue();
		if($inventoryArr){
			$inventorySVArr=$inventorySV->find(array("inventoryId"=>$inventoryArr['id']));
		}
		if($inventorySVArr){
			return $inventorySVArr['state'];
		}else{
			return -1;
		}
	}


	/**
	 * 重写编辑方法
	 * $isSave true是保存，可以修改  false是确认提交，不能修改
	 */
	function editsummary_d($obj,$isSave){
		try{
			$this->start_d();
			$summaryDao=new model_hr_inventory_inventorysummaryvalue();
			if(!empty($obj['id'])){ //如果存在，就编辑
				parent::edit_d($obj);
				$summaryDao->delete(array("inventoryId"=>$obj['id']));  //删除从表信息
				$id=$obj['id'];
			}else{  //不存在，就新增
				$id=parent::add_d($obj,true);
			}
		$templatesummary=new model_hr_inventory_templatesummary();
		$templatesummaryArr=$templatesummary->list_d();
		$length=count($templatesummaryArr);
			for($i=0;$i<$length;$i++){
				if($obj[$i]==null)
					continue;
				else{
					$obj[$i]['inventoryId']=$id;
					if($isSave){
						$obj[$i]['state']=0;
					}else{
						$obj[$i]['state']=1;
					}
					$summaryDao->add_d($obj[$i],true);
				}

			}
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}

	}



}
?>
