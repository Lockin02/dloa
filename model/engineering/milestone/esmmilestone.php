<?php
/**
 * @author Show
 * @Date 2011��12��10�� ������ 13:45:07
 * @version 1.0
 * @description:��Ŀ��̱�(oa_esm_project_milestone) Model��
 */
class model_engineering_milestone_esmmilestone extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_milestone";
		$this->sql_map = "engineering/milestone/esmmilestoneSql.php";
		parent::__construct ();
    }

	/****************************ҵ�񷽷�***************************/
    /**
     * ��ȡ��̱�������Ϣ
     */
    function getObjInfo_d($projectId){
    	$serviceesmMilestoneDao = new model_engineering_project_esmproject();
    	$serviceMilestone = $serviceesmMilestoneDao->get_d($projectId);
    	return $serviceMilestone;
    }

	/**
	 * ��д��������
	 */
	function add_d($object){
		return parent::add_d($object,true);
	}

	/**
	 * �����µ���̱���
	 */
	function milestoneChange_d($object){
//		echo "<pre>";
//		print_r($object);
		//�����ڵ�id����
		$idsArr = array();
		//�����������
		$changeIdsArr = array();
		//��Ŀid
		$projectId = null;
		try{
			$this->start_d();

			//ѭ����������
			foreach($object as $key => $val){
				if(!empty($val['milestoneId'])){
					array_push($idsArr,$val['milestoneId']);
				}
				if(empty($projectId)){
					$projectId = $val['projectId'];
				}

				if(empty($val['milestoneId'])){//������²��������ݣ�ֱ������
					unset($val['id']);
					$val['preMilestoneId'] = $changeIdsArr[$val['changePreId']];
//					print_r($val);

					//����ʵ�ʿ�ʼ����
					if(empty($val['actBeginDate'] ) || $val['actBeginDate'] == '0000-00-00'){
						unset($val['actBeginDate']);
					}
					//����ʵ�ʽ�������
					if(empty($val['actEndDate'] ) || $val['actEndDate'] == '0000-00-00'){
						unset($val['actEndDate']);
					}
					//����ʵ�ʽ�������
					if(empty($val['confirmStatus'] )){
						unset($val['confirmStatus']);
					}

					$newId = $this->add_d($val);

					array_push($idsArr,$newId);

				}else{//����Ǵ�ԭ����̱����������ݣ��༭
					$changeIdsArr[$val['id']] = $val['milestoneId'];
					$val['id'] = $val['milestoneId'];
					unset($val['milestoneId']);
					$val['preMilestoneId'] = $changeIdsArr[$val['changePreId']];
					$this->edit_d($val);
				}
			}

			//ɾ���Ѳ����ڵ�����
			$ids = implode($idsArr,',');
			$conditionStr = "projectId = $projectId and id not in($ids)";
			$this->delete($conditionStr);

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}

	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		return parent::edit_d($object,true);
	}

	/**
	 *�޸���Ŀ�׸���̱����ʵ�ʿ�ʼʱ��
	 *@param $object ��Ŀ��Ϣ
	 */
	function  editFirstStone_d($object){
	 	//��ȡ�׸���̱���Ϣ
	 	$condition=array('projectId'=>$object['id'],'preMilestoneId'=>0);
	 	$row=$this->find($condition);
	 	if($row){
	 		$row['actBeginDate'] = $object['actBeginDate'];
	 		$row['status']='LCBZTB';//״̬��Ϊ��ִ���С�
	 		$this->edit_d($row,true);
	 	}
	}

	/**
	 * �����̱�
	 *@param $projectId ��ĿID
	 *@param $milestoneId ��̱�ID
	 *@param $endDate ���ʱ��
	 */
	function endStone_d($projectId,$milestoneId,$endDate){
		//�޸�����ɵ���̱�
		$object = array(
			'id' => $milestoneId,
			'status' => 'LCBZTC',
			'actEndDate' => $endDate

		);
		$this->edit_d($object,true);

	 	//��ȡ��һ����̱���Ϣ,�������,����,�������򷵻�
	 	$condition=array('projectId'=>$projectId,'preMilestoneId'=>$milestoneId);
	 	$row = $this->find($condition,null,'id');
	 	if(is_array($row)){
	 		$row['actBeginDate']=$endDate;
	 		$row['status']='LCBZTB';
	 		$this->edit_d($row,true);
	 	}
	}

	/**
	 * ��ȡ��Ŀ�е���̱��б�
	 */
	function getProjectMilestone_d($projectId){
		$this->searchArr = array('projectId'=>$projectId);
		$this->sort = 'c.planBeginDate';
		$this->asc = false;
		return $this->list_d();
	}
}
?>