<?php
/**
 * @author Administrator
 * @Date 2012-08-29 17:09:19
 * @version 1.0
 * @description:��ʦ�������� Model��
 */
 class model_hr_tutor_reward  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_reward";
		$this->sql_map = "hr/tutor/rewardSql.php";
		parent::__construct ();
	}

	//����Զ����� ����ʱ��
	function rewardCode() {
		$billCode = "DSJL" . date("Ym");
		//        $billCode = "JL201208";
		$sql = "select max(RIGHT(c.code,4)) as maxCode,left(c.code,8) as _maxbillCode " .
		"from oa_hr_tutor_reward c group by _maxbillCode having _maxbillCode='" . $billCode . "'";

		$resArr = $this->findSql($sql);
		$res = $resArr[0];
		if (is_array($res)) {
			$maxCode = $res['maxCode'];
			$maxBillCode = $res['maxbillCode'];
			$newNum = $maxCode +1;
			switch (strlen($newNum)) {
				case 1 :
					$codeNum = "000" . $newNum;
					break;
				case 2 :
					$codeNum = "00" . $newNum;
					break;
				case 3 :
					$codeNum = "0" . $newNum;
					break;
				case 4 :
					$codeNum = $newNum;
					break;
			}
			$billCode .= $codeNum;
		} else {
			$billCode .= "0001";
		}

		return $billCode;
	}
	/**
	 * ��ȡ��ʦ���˱���ϸ��Ϣ
	 */
	function getRewardInfo_d(){
        //��ǰ��¼�˲���
        $deptId = $_SESSION['DEPT_ID'];
        $findSql = "select t.id as tutorId,t.userNo,t.userAccount,t.userName,t.assessmentScore,t.studentNo,t.studentAccount,t.studentName,s.tryEndDate" .
        		" from oa_hr_tutor_records t left join oa_hr_tutor_scheme s on t.id=s.tutorId where t.deptId='".$deptId."' and t.assessmentScore between 0 and 100 and isReward=0";
        $infoArr = $this->_db->getArray($findSql);
        return $infoArr;
	}
	/**
	 * ��ȡ��ʦ���˱���ϸ��Ϣ(�������Ź���)
	 */
	 function getRewardList_d(){
	 	$findSql = "select t.id as tutorId,t.deptId as tutorDeptId,t.deptName as tutorDeptName,t.userNo,t.userAccount,t.userName,t.assessmentScore,t.studentNo,t.studentAccount,t.studentName,s.tryEndDate" .
        		" from oa_hr_tutor_records t left join oa_hr_tutor_scheme s on t.id=s.tutorId where  t.assessmentScore between 0 and 100 and isReward=0";
        $infoArr = $this->_db->getArray($findSql);
        return $infoArr;
	 }

  	/**
	 * ��д��������
	 */
		function add_d($object){
		try{

			$this->start_d();
            $object['code'] = $this->rewardCode();
            $object['isPublish'] = 0;
			//������Ϣ
			$newId = parent::add_d($object,true);
            //������ϸ
            $infoDoa = new model_hr_tutor_rewardinfo();
            $infoDoa->createBatch($object['rewardinfo'], array ( 'rewardId' => $newId ), 'userName');

//            //�ı䵼ʦ�����¼״̬
//            $sql ="update oa_hr_tutor_records set status=1 where id=".$tutorId."";
//            $this->query($sql);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
				$this->rollBack();
			return false;
		}
	}
	/**
	 * ��дedit_D
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//�޸�������Ϣ
			parent::edit_d($object,true);
			$id = $object['id'];
			//������ϸ
            $infoDoa = new model_hr_tutor_rewardinfo();
            $infoDoa->delete(array ('rewardId' => $id));
            $infoDoa->createBatch($object['rewardinfo'], array ( 'rewardId' => $id ), 'userName');

			$this->commit_d();
//			$this->rollBack();
			return $id;
		}catch(exception $e){
			return false;
		}
	}

	/**
	 * ���ݽ���id��ȡ�ӱ���ϸ���½���״̬
	 */
	function updateRewardState($rewardId,$state){
         $sql = "select id,rewardId,tutorId from oa_hr_tutor_rewardinfo where rewardId = ".$rewardId."";
         $arr = $this->_db->getArray($sql);
         foreach($arr as $k=>$v){
         	$tutorId = $v['tutorId'];
            $updateSql = "update oa_hr_tutor_records set isReward=".$state." where id=".$tutorId."";
            $this->query($updateSql);
         }
	}
	/*
	 * ȷ�Ͻ�������
	 */
	 function conGrant_d($object){
	 	try{
	 		  $this->start_d();
		 	  $flag = 1;//�����ж��Ƿ�ȫ����ʦ�������ѷ���
		 	  $rewardInfoDao = new model_hr_tutor_rewardinfo();
		 	  foreach($object['rewardinfo'] as $key=>$val){
		 	  	//�����һ��Ϊ�գ���$flag��Ϊ0
					if($val['isGrant']==null){
						$flag = 0;
					}
					if(is_array($val)){
						$rewardInfoDao->edit_d($val);
					}
		 	  }
			  if($flag==1){
			  	$object['isGrant']=1;
			  	parent::edit_d($object);
			  }
			  $this->commit_d();
			  return true;
	 	}catch(exception $e){
	 		$this->rollBack();
			return false;
	 	}
	 }
	 /*
	 * ȷ�Ϸ������������Ϣ
	 */
	 function publish_d($id){
	 	try{
			$obj = $this->get_d($id);
			$obj['isPublish']=1;
			parent::edit_d($obj);

	        //����rewardId��ȡ��ʦ�񽱼�¼
	       	$rewardInfoDao = new model_hr_tutor_rewardinfo();
	       	$rewardInfo = $rewardInfoDao->findAll(array('rewardId'=>$id));
	       	$emailDao = new model_common_mail();
	       	//�ʼ����ѵ�ʦ���ĵ�ʦ���˳ɼ�
	       	if(is_array($rewardInfo)){

		       	foreach($rewardInfo as $key=>$val){
					$msg ='��ʦ'.$val['userName'].'��ѧԱ'.$val['studentName'].'�ĸ��������Ѿ���ɿ��ˣ�
							���½OAϵͳ�鿴���˳ɼ���<br>OA·����������--->���˰칫--->��������--->������--->��ʦ������ѡ�м�¼�һ��ɼ���';

					$title = '��ʦ'.$val['userName'].'������'.$val['studentName'].'ѧԱ���Ŀ��˳ɼ�';

					$emailDao->mailClear($title, $val['userAccount'].','.$val['studentAccount'], $msg);
		       	}
	       	}
	       	return true;
	 	}catch(exception $e){
			return false;
	 	}
	 }
 }
?>