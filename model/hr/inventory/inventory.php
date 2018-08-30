<?php
/**
 * @author chengl
 * @Date 2012��8��20�� 17:03:17
 * @version 1.0
 * @description:�̵���Ϣ
 */
class model_hr_inventory_inventory  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_inventory_inventory";
		$this->sql_map = "hr/inventory/inventorySql.php";
		parent::__construct ();
	}


	/**
	 * ���ݽ׶μ�����id��ȡ�̵���Ϣ
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
	 * ƴд�ӱ��ı���
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
	 * ��д�༭����
	 */
	function edit_d($obj){
		try{
			$this->start_d();
			$detailDao=new model_hr_inventory_inventorydetail();
			//����
			if(empty($obj['id'])){
				$id=parent::add_d($obj,true);
			}else{//�༭
				//��ɾ���ӱ���Ϣ
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
	 * ��д�༭����
	 * $isSave true�Ǳ��棬�����޸�  false��ȷ���ύ�������޸�
	 */
	function editsummary_d($obj,$isSave){
		try{
			$this->start_d();
			$summaryDao=new model_hr_inventory_inventorysummaryvalue();
			if(!empty($obj['id'])){ //������ڣ��ͱ༭
				parent::edit_d($obj);
				$summaryDao->delete(array("inventoryId"=>$obj['id']));  //ɾ���ӱ���Ϣ
				$id=$obj['id'];
			}else{  //�����ڣ�������
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
