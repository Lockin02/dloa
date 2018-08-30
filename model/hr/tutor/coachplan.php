<?php
/**
 * @author Administrator
 * @Date 2012-08-23 17:15:29
 * @version 1.0
 * @description:员工辅导计划表 Model层
 */
 class model_hr_tutor_coachplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_coachplan";
		$this->sql_map = "hr/tutor/coachplanSql.php";
		parent::__construct ();
	}

	/**
	 * 获取员工辅导计划列表数据
	 */
	 function pageForCoachplan_d( $coachplan){

		$coachplaninfoDao = new model_hr_tutor_coachplaninfo();
		foreach($coachplan as $key =>$val){
			//通过员工辅导计划id获取从表信息
			$coachplaninfos = $coachplaninfoDao->findBy('coachplanId',$val['id']);
			if(!empty($coachplaninfos)){
				$reachinfoStu = 1;
				$reachinfoTut = 1;

				foreach($coachplaninfos as $iKey => $iVal){
					//如果 达成情况（员工）都不为空 则 赋值为 1，有一个为空则为0
					if($coachplaninfos[$iKey]['reachinfoStu']==null||$coachplaninfos[$iKey]['reachinfoStu']==''){
						$reachinfoStu = 0;
					}
					//如果 达成情况（导师）都不为空 则 赋值为 1，有一个为空则为0
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
		//判断是否有辅导计划
		$sql = "select id,ExaStatus  from oa_hr_tutor_coachplan where tutorId=" . $tutorId . "";
		$flagArr = $this->_db->getArray($sql);
		if (empty ($flagArr[0]['id'])) {
			return 0;
		} else {
			return 1;
		}

    }
  	/**
	 * 重写新增方法
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

			//主表信息
			$newId = parent::add_d($object,true);
            //插入明细
            $infoDoa = new model_hr_tutor_coachplaninfo();
            $infoDoa->createBatch($infoArr, array ( 'coachplanId' => $newId ), 'fosterGoal');

//            //改变导师管理记录状态
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
	 * 从写edit_D
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
			//修改主表信息
			parent::edit_d($object,true);
			$coachId = $object['id'];
			//插入明细
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
	 * 提交辅导计划的邮件发送
	 */
	function subPlanMail_d($object, $thisAct = '新增') {
		$addMsg = '导师已在OA系统为学员'.$object['studentName'].'制定了辅导计划表并已提交领导审批。';

		$emailDao = new model_common_mail();
		$emailDao->mailClear('学员'.$object['studentName'].'的辅导计划表', $object['studentAccount'], $addMsg, null);
	}
 }
?>