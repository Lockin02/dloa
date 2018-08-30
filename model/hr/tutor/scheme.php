<?php
/**
 * @author Administrator
 * @Date 2012��8��22�� 19:40:09
 * @version 1.0
 * @description:��ʦ���˱� Model��
 */
 class model_hr_tutor_scheme  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_scheme";
		$this->sql_map = "hr/tutor/schemeSql.php";
		parent::__construct ();
	}


  	/**
	 * ��д��������
	 */
		function add_d($object){
		try{
			$this->start_d();
			//��ȡ�ʼ�����
			if(isset($object['email'])){
				$emailArr = $object['email'];
				unset($object['email']);
			}
			$tutorId = $object['tutorId'];
			//������Ϣ
			$newId = parent::add_d($object,true);
            if (!empty ($object['schemeinfo'])) {
				$schemeDao = new model_hr_tutor_schemeinfo();
				$schemeDao->createBatch($object['schemeinfo'], array (
					'tutorassessId' => $newId
				), 'appraisal');
			}

			if(isset($emailArr)){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$object);
				}
			}
			if($object['userAccount']){
				$tutorEmail=$object['userAccount'].','.$object['studentAccount'];
				$this->tutorMail_d($tutorEmail);
			}

            //�ı䵼ʦ�����¼״̬
            $sql ="update oa_hr_tutor_records set status=3 where id=".$tutorId."";
            $this->query($sql);

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
			//�޸�������Ϣ
			parent::edit_d($object,true);
			$coachId = $object['id'];
			//������ϸ
			$schemeDao = new model_hr_tutor_schemeinfo();
			$schemeDao->delete(array ('tutorassessId' => $coachId));
			$schemeDao->createBatch($object['schemeinfo'], array ('tutorassessId' => $coachId), 'appraisal');

			$this->commit_d();
			return $coachId;
		}catch(exception $e){
			return false;
		}
	}

	/**
	 * �ж� Ȩ��ռ�Ȳ�Ϊ����������Ƿ�����д��������
	 */
	 function checkComplete_d($obj){
	 	//�жϵ�ʦ���� �Ƿ�����
	 	if($obj['tutProportion']!=0){
			foreach($obj['schemeinfo'] as $key => $val ){
				if(empty($val['selfgraded'])||$val['selfgraded']==0){

					return false;
				}
			}
	 	}
		//�жϲ�������
	 	if($obj['deptProportion']!=0){
			foreach($obj['schemeinfo'] as $key => $val ){
				if(empty($val['assistantgraded'])||$val['assistantgraded']==0){

					return false;
				}
			}
	 	}
	 	//�ж�HR
	 	if($obj['hrProportion']!=0){
			foreach($obj['schemeinfo'] as $key => $val ){
				if(empty($val['hrgraded'])||$val['hrgraded']==0){

					return false;
				}
			}
	 	}
	 	//�ж��ϼ�
         if($obj['supProportion']!=0){
             foreach($obj['schemeinfo'] as $key => $val ){
                 if(empty($val['superiorgraded'])||$val['superiorgraded']==0){

                     return false;
                 }
             }
         }

         //�ж���Ա��
         if($obj['stuProportion']!=0){
             foreach($obj['schemeinfo'] as $key => $val ){
                 if(empty($val['staffgraded'])||$val['staffgraded']==0){
                     return false;
                 }
             }
         }

	 	return true;
	 }
	/**
	 * ��������
	 */
	function gradeEdit_d($object){
		try{
			$this->start_d();
			  $schemeId = $object['id'];
              $selfgraded = 0;//��ʦ������
              $superiorgraded= 0;//ֱ���ϼ�����
              $staffgraded= 0;//Ա������
              $assistantgraded= 0;//������������
              $hrgraded=0;//HR����
             foreach($object['schemeinfo'] as $key => $val){
                 $selfgraded += $val['selfgraded'] * ($val['coefficient']/100);
                 $superiorgraded += $val['superiorgraded'] * ($val['coefficient']/100);
                 $assistantgraded += $val['assistantgraded'] * ($val['coefficient']/100);
                 $hrgraded += $val['hrgraded'] * ($val['coefficient']/100);
                 $staffgraded += $val['staffgraded']* ($val['coefficient']/100);//ѧԱֱ��
             }
             $schemeInfo = $this->get_d($schemeId);
//             //�����ܷ�
//             $assessmentScore = ($selfgraded + $superiorgraded + $assistantgraded + $hrgraded)/4;
             //��ʦ����
             if(empty($schemeInfo['tutProportion'])){
             	$selfgradedPoint = "0";
             }else{
             	$selfgradedPoint = $selfgraded * ($schemeInfo['tutProportion']/100);
             }
             //ֱ���ϼ�����
             if(empty($schemeInfo['supProportion'])){
             	$superiorgradedPoint = "0";
             }else{
             	$superiorgradedPoint = $superiorgraded * ($schemeInfo['supProportion']/100);
             }
             //������������
             if(empty($schemeInfo['deptProportion'])){
             	$assistantgradedPoint = "0";
             }else{
             	$assistantgradedPoint = $assistantgraded * ($schemeInfo['deptProportion']/100);
             }
             //hr����
             if(empty($schemeInfo['hrProportion'])){
             	$hrgradedPoint = "0";
             }else{
             	$hrgradedPoint = $hrgraded * ($schemeInfo['hrProportion']/100);
             }
            //��Ա������
            if(empty($schemeInfo['stuProportion'])){
                $staffgraded = "0";
            }else{
                $staffgradedPoint = $staffgraded * ($schemeInfo['stuProportion']/100);
            }
             $assessmentScore = ( $selfgradedPoint + $staffgradedPoint + $assistantgradedPoint + $hrgradedPoint)*10;

             $updateSql = "update oa_hr_tutor_scheme set selfgraded='".$selfgraded."',superiorgraded='".$superiorgraded."',assistantgraded='".$assistantgraded."',hrgraded='".$hrgraded."',assessmentScore='".$assessmentScore."',staffgraded='".$staffgraded."' where id=".$schemeId."";
             $this->query($updateSql);
             $tutorId = $schemeInfo['tutorId'];
             $updateTut = "update oa_hr_tutor_records set assessmentScore='".$assessmentScore."' where id=".$tutorId."";
             $this->query($updateTut);

			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){
			return false;
		}
	}


	/**
	 * �������ּ���
	 */
	function toScoreInfo_d($schemeId) {
		$schemeInfo = $this->get_d($schemeId);
		return $schemeInfo;
	}

	/**
	 * �ʼ�����
	 */
	function thisMail_d($emailArr,$object,$thisAct = '����'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = $object['deptName']." ��������ʦ [".$object['userName'] . "] ��ѧ�� [".$object['studentName'] . "]���𿼺� ��";
		$addMsg = '���ã�<br><br>'.
		'���Թ�ƽ��������̬�ȶԵ�ʦ'.$object['userName'] .'��ѧԱ'.$object['studentName'] .'�ĸ��������������ۡ�<br><br>' .
		'�����ڵĽ������������ڵĽ�������ʦ��ְ�ڼ䶼��ѧԱ�ĵ�ʦ��' .
		'����ѧԱ���պ�Ĺ����������ʻ�����ʱ�����ҵ�ʦ�����ġ�<br><br>' .
         '�����쵼�ڴ�OA·���������֣�������--->ҵ�����--->���¹���--->��ʦ����--->��ʦ����(����)��ѡ�м�¼�һ��ɼ���<br><br>' .
		'��ʦ����ͬ���ڴ�OA·���������֣�������--->���˰칫--->��������--->������--->��ʦ������ѡ�м�¼�һ��ɼ���<br><br>' .
		'ѧԱ���ϼ��ڴ�OA·���������֣�������--->���˰칫--->��������--->������--->��ʦ��¼��ѡ�м�¼�һ��ɼ���<br><br>' ;
		$emailDao = new model_common_mail();
		$title = '��Ե�ʦ'.$object['userName'].'��ѧԱ'.$object['studentName'].'�ĸ���������������';
		$emailDao->mailClear($title,$emailArr['TO_ID'],$addMsg,$emailArr['ADDIDS']);
	}

	/**
	 * �ʼ�����
	 */
	function tutorMail_d($emailIds){
		$addMsg ='��ȷ�ϸ����ƻ������������ڴ�OA·�����룺������--->���˰칫--->��������--->������--->��ʦ������ѡ�м�¼�һ��ɼ�����' ;
		$emailDao = new model_common_mail();
		$title = '��ȷ�ϸ����ƻ���������';
		$emailDao->mailClear($title,$emailIds,$addMsg);
	}


 }
?>