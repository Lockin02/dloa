<?php
/**
 * @author huangzf
 * @Date 2012年5月11日 星期五 13:38:37
 * @version 1.0
 * @description:生产申请清单 Model层
 */
class model_produce_apply_produceapplyitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_produceapply_item";
		$this->sql_map = "produce/apply/produceapplyitemSql.php";
		parent::__construct ();
	}

	/*
	 * 重写查找方法
	 * */
	function get_maind($id) {
		$condition = array ("mainId" => $id );
		return $this->find ( $condition );
	}

	/**
	 *
	 * 更新申请单清单的进度信息
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
	 * 下达生产任务时获取所有数据返回json
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