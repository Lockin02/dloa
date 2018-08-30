<?php
/**
 * @author huangzf
 * @Date 2012��5��11�� ������ 13:38:37
 * @version 1.0
 * @description:���������嵥 Model��
 */
class model_produce_apply_produceapplyitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_produceapply_item";
		$this->sql_map = "produce/apply/produceapplyitemSql.php";
		parent::__construct ();
	}

	/*
	 * ��д���ҷ���
	 * */
	function get_maind($id) {
		$condition = array ("mainId" => $id );
		return $this->find ( $condition );
	}

	/**
	 *
	 * �������뵥�嵥�Ľ�����Ϣ
	 * @param  $newObj
	 */
	function updateProcess($newObj) {
		$oldObj = $this->get_d ( $newObj ['id'] );
		$qualityNum=$oldObj['qualityNum'];
		if(empty($qualityNum)){
			$qualityNum=0;
		}
		$sql = " update oa_produce_produceapply_item set qualityNum=qualityNum-" . $qualityNum . "+" . $newObj ['qualityNum'] . " ,qualifiedNum=qualifiedNum-" . $oldObj ['qualifiedNum'] . "+" . $newObj ['qualifiedNum'] . " ,stockNum=stockNum-" . $oldObj ['stockNum'] . "+" . $newObj ['stockNum'] . " where id=" . $newObj ['id'];
		//echo $sql;
		return $this->query ( $sql );
	}

	/**
	 * �´���������ʱ��ȡ�������ݷ���json
	 */
	function listJsonByTask_d($obj){
		if($obj['applyDocItemId'] != 'all'){
			$this->searchArr['idArr'] = $obj['applyDocItemId'];
		}else{
			$this->searchArr['mainId'] = $obj['applyDocId'];
		}
		$rs = $this->list_d();
		if($rs){
			$resultArr = array();
			foreach($rs as $key => $val){
				if($val['exeNum'] >= $val['produceNum']){
					unset($rs[$key]);
				}else{
					$rs[$key]['num'] = $val['produceNum'] - $val['exeNum'];
					$rs[$key]['planId'] = $val['id'];
					array_push($resultArr,$rs[$key]);
				}
			}
		}
		return $resultArr;
	}
}