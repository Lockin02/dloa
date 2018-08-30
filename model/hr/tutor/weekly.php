<?php
/**
 * @author Administrator
 * @Date 2012-08-24 14:38:04
 * @version 1.0
 * @description:��Ա���ܱ��� Model��
 */
 class model_hr_tutor_weekly  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_weekly";
		$this->sql_map = "hr/tutor/weeklySql.php";
		parent::__construct ();
	}

	/**
	 * ��ȡԱ�������ܱ����� ��������Ƿ�׼ʱ����isOnTime
	 */
	 function pageForRead($rows){

		foreach($rows as $key => $val ){
			if($val['signDate']!=null&&$val['signDate']!=''){
				if($this->get_weekend_days($val['submitDate'],$val['signDate'],true)>2){
					$rows[$key]['isOnTime']= 0 ;
				}else{
					$rows[$key]['isOnTime']= 1 ;
				}
			}else{
				if($this->get_weekend_days($val['submitDate'],date("Y-m-d"),true)>2){
					$rows[$key]['isOnTime']= 0 ;
				}else{
					$rows[$key]['isOnTime']= null ;
				}
			}
		}
		return $rows;
	 }
	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
	 	try{
			$this->start_d();
		//��ȡ�ʼ�����
		if(isset($object['email'])){
			$emailArr = $object['email'];
			unset($object['email']);
		}

		$newId = parent::add_d( $object,true );
		if($object['state']==1&&isset($emailArr)){
			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
				$this->thisMail_d($emailArr,$object);
			}
		}
			$this->commit_d();
			return $newId;
		 }catch(Exception $e){
			$this->rollBack();
			return $newId;
		}
	}


	/**
	 * ���������޸Ķ���
	 */
	function editWeekly_d($object, $isEditInfo) {
	 	try{
			$this->start_d();
			//��ȡ�ʼ�����
			if(isset($object['email'])){
				$emailArr = $object['email'];
				unset($object['email']);
			}
			$id=parent::edit_d($object,true);
			if($object['state']==1&&isset($emailArr)){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$object);
				}
			}
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}


	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object, $isEditInfo) {
		//��ȡ�ʼ�����
		if(isset($object['email'])){
			$emailArr = $object['email'];
			unset($object['email']);
		}

		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		if(isset($emailArr)){
			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
				$this->replyMail_d($emailArr,$object);
			}
		}

		return $this->updateById ( $object );
	}


	/**
	 * �ʼ�����
	 */
	function thisMail_d($emailArr,$object,$thisAct = '����'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = "ѧԱ [". $object['studentName'] ."] ����ʦ [".$object['userName'] . "] �ύ�ܱ� �� ��ظ���";

		$emailDao = new model_common_mail();
		$title = '���'.$object['studentName'].'ѧԱ���ܱ��ظ�ָ���������';
		$emailDao->mailClear($title,$emailArr['TO_ID'],$addMsg,$emailArr['ADDIDS']);
	}

	/**
	 * �ʼ�����
	 */
	function replyMail_d($emailArr,$object,$thisAct = '����'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = "��ʦ [". $object['userName'] ."] ������ѧԱ [".$object['studentName'] . "] �ύ���ܱ� ��";

		$emailDao = new model_common_mail();
		$emailDao->mailClear('�����ܱ�',$emailArr['TO_ID'],$addMsg,$emailArr['ADDIDS']);
	}
	/**
	 * @param char|int $start_date һ����Ч�����ڸ�ʽ�����磺20091016��2009-10-16
     * @param char|int $end_date ͬ��
     * $is_workday Ϊtrueʱ�������ڼ�Ĺ������� false ʱ�������ڼ����ĩ����
	 */
	function get_weekend_days($start_date,$end_date,$is_workday = false){

			if (strtotime($start_date) > strtotime($end_date)) list($start_date, $end_date) = array($end_date, $start_date);
				$start_reduce = $end_add = 0;
				$start_N = date('N',strtotime($start_date));
				$start_reduce = ($start_N == 7) ? 1 : 0;
				$end_N = date('N',strtotime($end_date));
				in_array($end_N,array(6,7)) && $end_add = ($end_N == 7) ? 2 : 1;
				$alldays = abs(strtotime($end_date) - strtotime($start_date))/86400 + 1;
				$weekend_days = floor(($alldays + $start_N - 1 - $end_N) / 7) * 2 - $start_reduce + $end_add;
			if ($is_workday){
				$workday_days = $alldays - $weekend_days;
				return $workday_days;
				}
			return $weekend_days;
	}

 }
?>