<?php

/**
 * @author Show
 * @Date 2012��8��24�� ������ 11:43:39
 * @version 1.0
 * @description:��ְ�ʸ���ί��ֱ� - ���� Model��
 */
class model_hr_certifyapply_score extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyapplyassess_score";
		$this->sql_map = "hr/certifyapply/scoreSql.php";
		parent :: __construct();
	}
	/******************* �ⲿ���� ******************/
	/**
	 * ��ȡ���۱���Ϣ
	 */
	function getAssess_d($cassessId){
		$cassessDao = new model_hr_certifyapply_cassess();
		return $cassessDao->find(array('id' => $cassessId));
	}

	/******************* ��ɾ�Ĳ� ********************/
	/**
	 * �������ֱ���Ϣ - �������۱�ֱ��¼������ʱ����������Ϣ
	 */
	function createScoreInfo_d($object,$detail){
		//ȡ��������������������
		$cacheArr = $detail;
		$scoreArr = array_pop($cacheArr);
		try{
			$this->start_d();

			//�����ϼ�
			$score = 0;
			foreach($detail as $key => $val){
				$score = bcadd( $score , bcdiv(bcmul($val['weights'],$val['score'],2),100,2));
				unset($detail[$key]['scoreId']);
			}

			//������������
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
	 * ��дadd_d
	 */
	function add_d($object){
		//������ϸ��ȡ
		$scoredetail = $object['detailvals'];
		unset($object['detailvals']);

		$cassessId = $object['cassessId'];//��ȡ������ϸ��id

		try{
			$this->start_d();

			//����
			$newId = parent::add_d($object,true);

			//��ϸ����
			$scoredetailDao = new model_hr_certifyapply_scoredetail();
			$scoredetailDao->createBatch($scoredetail,array('scoreId' => $newId,'managerId' => $object['managerId'],'managerName' => $object['managerName']));

			//���·����ֱ�
			$cdetailDao = new model_hr_certifyapply_cdetail();
			$cdetailDao->updateByAssessId($cassessId);

			//�ж����ȫ�����Ѿ�����֣���������۱�״̬Ϊ������
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
	 * �༭���
	 */
	function edit_d($obj){
		try{
			$this->start_d();
			$scoreId = $obj['id'];
			$cassessId = $obj['cassessId'];//��ȡ������ϸ��id

			parent::edit_d($obj,true);//��������

			if($obj ['detailvals']!=null){
				$scoredetailDao = new model_hr_certifyapply_scoredetail();
				$mainArr=array("scoreId"=>$obj ['id']);//���ôӱ��е�scoreId
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$obj ['detailvals']);
				$scoredetailDao->saveDelBatch($itemsArr);//���´ӱ�����
			}

			//���ݴӱ��еķ�������������ϸ���е�����
			$cdetailDao = new model_hr_certifyapply_cdetail();
			$cdetailDao->updateByAssessId($cassessId);

			//�ж����ȫ�����Ѿ�����֣���������۱�״̬Ϊ������
			$this->isAllScore_d($cassessId);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/*********************** ҵ���߼� *********************/
	/**
	 * �ж�ȫ�����Ƿ�����֣�������������۱�״̬
	 */
	function isAllScore_d($cassessId){
		//ʵ�������۱���
		$cassessDao = new model_hr_certifyapply_cassess();

		//��ȡ��������Ϣ
		$cassessArr = $cassessDao->find(array('id' => $cassessId),null,'managerId,memberId');
		$managerNum = 0;//��������
		if($cassessArr['managerId']){
			$managerNum ++;
		}
		if($cassessArr['memberId']){
	 		$otherManagers = split(",",$cassessArr['memberId']);
	 		$managerNum += count($otherManagers);
		}

		//��ȡʵ����������
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
	 * ���·���ͳ��
	 */
	function updateScore_d($id){
		//��ȡ�����ϼ�
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
	 * ��ȡ����
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