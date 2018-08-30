<?php
/**
 * @author Administrator
 * @Date 2012-08-23 17:15:29
 * @version 1.0
 * @description:Ա�������ƻ��� Model��
 */
 class model_hr_tutor_coachplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_coachplan";
		$this->sql_map = "hr/tutor/coachplanSql.php";
		parent::__construct ();
	}

	/**
	 * ��ȡԱ�������ƻ��б�����
	 */
	 function pageForCoachplan_d( $coachplan){

		$coachplaninfoDao = new model_hr_tutor_coachplaninfo();
		foreach($coachplan as $key =>$val){
			//ͨ��Ա�������ƻ�id��ȡ�ӱ���Ϣ
			$coachplaninfos = $coachplaninfoDao->findBy('coachplanId',$val['id']);
			if(!empty($coachplaninfos)){
				$reachinfoStu = 1;
				$reachinfoTut = 1;

				foreach($coachplaninfos as $iKey => $iVal){
					//��� ��������Ա��������Ϊ�� �� ��ֵΪ 1����һ��Ϊ����Ϊ0
					if($coachplaninfos[$iKey]['reachinfoStu']==null||$coachplaninfos[$iKey]['reachinfoStu']==''){
						$reachinfoStu = 0;
					}
					//��� ����������ʦ������Ϊ�� �� ��ֵΪ 1����һ��Ϊ����Ϊ0
					if($coachplaninfos[$iKey]['reachinfoTut']==null||$coachplaninfos[$iKey]['reachinfoTut']==''){
						$reachinfoTut = 0;
					}
				}
				$coachplan[$key]['reachinfoStu']=$reachinfoStu;
				$coachplan[$key]['reachinfoTut']=$reachinfoTut;
			}
		}
		return $coachplan;
	 }
	    function isAddPlan_d($tutorId){
		//�ж��Ƿ��и����ƻ�
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			return 0;
		} else {
			return 1;
		}

    }
  	/**
	 * ��д��������
	 */
		function add_d($object){
		try{
			$this->start_d();
			$tutorId = $object['tutorId'];
			if(!isset($object['infoA'])){
                $object['infoA'] = array();
			}
			if(!isset($object['infoB'])){
                $object['infoB'] = array();
			}
			if(!isset($object['infoC'])){
                $object['infoC'] = array();
			}
            $infoArr = array_merge($object['infoA'],$object['infoB'],$object['infoC']);

			//������Ϣ
			$newId = parent::add_d($object,true);
            //������ϸ
            $infoDoa = new model_hr_tutor_coachplaninfo();
            $infoDoa->createBatch($infoArr, array ( 'coachplanId' => $newId ), 'fosterGoal');

//            //�ı䵼ʦ�����¼״̬
//            $sql ="update oa_hr_tutor_records set status=1 where id=".$tutorId."";
//            $this->query($sql);

			$this->commit_d();
//			$this->rollBack();
			return $newId;
		}catch(exception $e){
			return false;
		}
	}
	/**
	 * ��дedit_D
	 */
	function edit_d($object){
		try{
			$this->start_d();
			if(!isset($object['infoA'])){
                $object['infoA'] = array();
			}
			if(!isset($object['infoB'])){
                $object['infoB'] = array();
			}
			if(!isset($object['infoC'])){
                $object['infoC'] = array();
			}
            $infoArr = array_merge($object['infoA'],$object['infoB'],$object['infoC']);
			//�޸�������Ϣ
			parent::edit_d($object,true);
			$coachId = $object['id'];
			//������ϸ
            $infoDoa = new model_hr_tutor_coachplaninfo();
            $infoDoa->delete(array ('coachplanId' => $coachId));

            $infoDoa->createBatch($infoArr, array ( 'coachplanId' => $coachId ), 'fosterGoal');

			$this->commit_d();
//			$this->rollBack();
			return $coachId;
		}catch(exception $e){
			return false;
		}
	}


	/**
	 * �ύ�����ƻ����ʼ�����
	 */
	function subPlanMail_d($object, $thisAct = '����') {
		$addMsg = '��ʦ����OAϵͳΪѧԱ'.$object['studentName'].'�ƶ��˸����ƻ������ύ�쵼������';

		$emailDao = new model_common_mail();
		$emailDao->mailClear('ѧԱ'.$object['studentName'].'�ĸ����ƻ���', $object['studentAccount'], $addMsg, null);
	}
 }
?>