<?php

/**
 * @author Show
 * @Date 2012年8月24日 星期五 11:43:39
 * @version 1.0
 * @description:任职资格评委打分表 - 主表 Model层
 */
class model_hr_certifyapply_score extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyapplyassess_score";
		$this->sql_map = "hr/certifyapply/scoreSql.php";
		parent :: __construct();
	}
	/******************* 外部数据 ******************/
	/**
	 * 获取评价表信息
	 */
	function getAssess_d($cassessId){
		$cassessDao = new model_hr_certifyapply_cassess();
		return $cassessDao->find(array('id' => $cassessId));
	}

	/******************* 增删改查 ********************/
	/**
	 * 创建评分表信息 - 用于评价表直接录入评分时创建本表信息
	 */
	function createScoreInfo_d($object,$detail){
		//取出部分数组做基础数据
		$cacheArr = $detail;
		$scoreArr = array_pop($cacheArr);
		try{
			$this->start_d();

			//分数合计
			$score = 0;
			foreach($detail as $key => $val){
				$score = bcadd( $score , bcdiv(bcmul($val['weights'],$val['score'],2),100,2));
				unset($detail[$key]['scoreId']);
			}

			//构建插入数组
			$inArr = array(
				'cassessId' => $object['id'],
				'managerId' => $scoreArr['managerId'],
				'managerName' => $scoreArr['managerName'],
				'assessDate' => day_date,
				'userName' => $object['userName'],
				'userAccount' => $object['userAccount'],
				'score' => $score
			);
			$newId = parent::add_d($inArr,true);

			$scoredetailDao = new model_hr_certifyapply_scoredetail();
			$scoredetailDao->createBatch($detail,array('scoreId' => $newId));

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * 重写add_d
	 */
	function add_d($object){
		//评分明细获取
		$scoredetail = $object['detailvals'];
		unset($object['detailvals']);

		$cassessId = $object['cassessId'];//获取评分明细表id

		try{
			$this->start_d();

			//新增
			$newId = parent::add_d($object,true);

			//明细处理
			$scoredetailDao = new model_hr_certifyapply_scoredetail();
			$scoredetailDao->createBatch($scoredetail,array('scoreId' => $newId,'managerId' => $object['managerId'],'managerName' => $object['managerName']));

			//更新份评分表
			$cdetailDao = new model_hr_certifyapply_cdetail();
			$cdetailDao->updateByAssessId($cassessId);

			//判断如果全部人已经评完分，则更新评价表状态为已评分
			$this->isAllScore_d($cassessId);


			$this->commit_d();
			return $newId;
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	/*
	 * 编辑打分
	 */
	function edit_d($obj){
		try{
			$this->start_d();
			$scoreId = $obj['id'];
			$cassessId = $obj['cassessId'];//获取评分明细表id

			parent::edit_d($obj,true);//更新主表

			if($obj ['detailvals']!=null){
				$scoredetailDao = new model_hr_certifyapply_scoredetail();
				$mainArr=array("scoreId"=>$obj ['id']);//设置从表中的scoreId
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$obj ['detailvals']);
				$scoredetailDao->saveDelBatch($itemsArr);//更新从表数据
			}

			//根据从表中的分数更新评分明细表中的数据
			$cdetailDao = new model_hr_certifyapply_cdetail();
			$cdetailDao->updateByAssessId($cassessId);

			//判断如果全部人已经评完分，则更新评价表状态为已评分
			$this->isAllScore_d($cassessId);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/*********************** 业务逻辑 *********************/
	/**
	 * 判断全部分是否评完分，评完则更新评价表状态
	 */
	function isAllScore_d($cassessId){
		//实例化评价表类
		$cassessDao = new model_hr_certifyapply_cassess();

		//获取评审人信息
		$cassessArr = $cassessDao->find(array('id' => $cassessId),null,'managerId,memberId');
		$managerNum = 0;//审评人数
		if($cassessArr['managerId']){
			$managerNum ++;
		}
		if($cassessArr['memberId']){
	 		$otherManagers = split(",",$cassessArr['memberId']);
	 		$managerNum += count($otherManagers);
		}

		//获取实际评分人数
		$actScoreUserNum = $this->findCount(array('cassessId' => $cassessId));

		if($managerNum == $actScoreUserNum){
			try{

				$cassessDao->updateStatus_d($cassessId,4);

				return true;
			}catch(exception $e){
				throw $e;
				return false;
			}
		}else{
			return true;
		}
	}

	/**
	 * 更新分数统计
	 */
	function updateScore_d($id){
		//获取分数合计
		$score = $this->getScore_d($id);
		try{
			$object = array('id' => $id,'score' => $score);
			parent::edit_d($object,true);

			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/**
	 * 获取分数
	 */
	function getScore_d($id){
		$sql = "select sum((weights*score)/100) as score  from oa_hr_certifyapplyassess_scoredetail where scoreId = ".$id ." group by scoreId";
		$rs = $this->_db->getArray($sql);
		if($rs){
			return $rs[0]['score'];
		}else{
			return 0;
		}
	}
}
?>