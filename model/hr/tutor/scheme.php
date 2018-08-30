<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 19:40:09
 * @version 1.0
 * @description:导师考核表 Model层
 */
 class model_hr_tutor_scheme  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_scheme";
		$this->sql_map = "hr/tutor/schemeSql.php";
		parent::__construct ();
	}


  	/**
	 * 重写新增方法
	 */
		function add_d($object){
		try{
			$this->start_d();
			//获取邮件内容
			if(isset($object['email'])){
				$emailArr = $object['email'];
				unset($object['email']);
			}
			$tutorId = $object['tutorId'];
			//主表信息
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

            //改变导师管理记录状态
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
	 * 重写edit_D
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//修改主表信息
			parent::edit_d($object,true);
			$coachId = $object['id'];
			//插入明细
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
	 * 判断 权重占比不为零的评分人是否已填写考核评分
	 */
	 function checkComplete_d($obj){
	 	//判断导师评分 是否已填
	 	if($obj['tutProportion']!=0){
			foreach($obj['schemeinfo'] as $key => $val ){
				if(empty($val['selfgraded'])||$val['selfgraded']==0){

					return false;
				}
			}
	 	}
		//判断部门助理
	 	if($obj['deptProportion']!=0){
			foreach($obj['schemeinfo'] as $key => $val ){
				if(empty($val['assistantgraded'])||$val['assistantgraded']==0){

					return false;
				}
			}
	 	}
	 	//判断HR
	 	if($obj['hrProportion']!=0){
			foreach($obj['schemeinfo'] as $key => $val ){
				if(empty($val['hrgraded'])||$val['hrgraded']==0){

					return false;
				}
			}
	 	}
	 	//判断上级
         if($obj['supProportion']!=0){
             foreach($obj['schemeinfo'] as $key => $val ){
                 if(empty($val['superiorgraded'])||$val['superiorgraded']==0){

                     return false;
                 }
             }
         }

         //判断新员工
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
	 * 处理评分
	 */
	function gradeEdit_d($object){
		try{
			$this->start_d();
			  $schemeId = $object['id'];
              $selfgraded = 0;//导师自评分
              $superiorgraded= 0;//直接上级评分
              $staffgraded= 0;//员工评分
              $assistantgraded= 0;//部门助理评分
              $hrgraded=0;//HR评分
             foreach($object['schemeinfo'] as $key => $val){
                 $selfgraded += $val['selfgraded'] * ($val['coefficient']/100);
                 $superiorgraded += $val['superiorgraded'] * ($val['coefficient']/100);
                 $assistantgraded += $val['assistantgraded'] * ($val['coefficient']/100);
                 $hrgraded += $val['hrgraded'] * ($val['coefficient']/100);
                 $staffgraded += $val['staffgraded']* ($val['coefficient']/100);//学员直接
             }
             $schemeInfo = $this->get_d($schemeId);
//             //考核总分
//             $assessmentScore = ($selfgraded + $superiorgraded + $assistantgraded + $hrgraded)/4;
             //导师分数
             if(empty($schemeInfo['tutProportion'])){
             	$selfgradedPoint = "0";
             }else{
             	$selfgradedPoint = $selfgraded * ($schemeInfo['tutProportion']/100);
             }
             //直接上级分数
             if(empty($schemeInfo['supProportion'])){
             	$superiorgradedPoint = "0";
             }else{
             	$superiorgradedPoint = $superiorgraded * ($schemeInfo['supProportion']/100);
             }
             //部门助理评分
             if(empty($schemeInfo['deptProportion'])){
             	$assistantgradedPoint = "0";
             }else{
             	$assistantgradedPoint = $assistantgraded * ($schemeInfo['deptProportion']/100);
             }
             //hr评分
             if(empty($schemeInfo['hrProportion'])){
             	$hrgradedPoint = "0";
             }else{
             	$hrgradedPoint = $hrgraded * ($schemeInfo['hrProportion']/100);
             }
            //新员工评分
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
	 * 处理评分计算
	 */
	function toScoreInfo_d($schemeId) {
		$schemeInfo = $this->get_d($schemeId);
		return $schemeInfo;
	}

	/**
	 * 邮件发送
	 */
	function thisMail_d($emailArr,$object,$thisAct = '新增'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = $object['deptName']." 部门已向导师 [".$object['userName'] . "] 和学生 [".$object['studentName'] . "]发起考核 。";
		$addMsg = '您好！<br><br>'.
		'请以公平、公正的态度对导师'.$object['userName'] .'给学员'.$object['studentName'] .'的辅导工作进行评价。<br><br>' .
		'试用期的结束不代表辅导期的结束，导师在职期间都是学员的导师，' .
		'所以学员在日后的工作中有疑问还是随时可以找导师交流的。<br><br>' .
         '部门领导在此OA路径进入评分：导航栏--->业务管理--->人事管理--->导师管理--->导师经历(部门)（选中记录右击可见）<br><br>' .
		'导师和新同事在此OA路径进入评分：导航栏--->个人办公--->工作任务--->人事类--->导师工作（选中记录右击可见）<br><br>' .
		'学员的上级在此OA路径进入评分：导航栏--->个人办公--->工作任务--->人事类--->导师记录（选中记录右击可见）<br><br>' ;
		$emailDao = new model_common_mail();
		$title = '请对导师'.$object['userName'].'给学员'.$object['studentName'].'的辅导工作进行评价';
		$emailDao->mailClear($title,$emailArr['TO_ID'],$addMsg,$emailArr['ADDIDS']);
	}

	/**
	 * 邮件发送
	 */
	function tutorMail_d($emailIds){
		$addMsg ='请确认辅导计划的完成情况，在此OA路径进入：导航栏--->个人办公--->工作任务--->人事类--->导师工作（选中记录右击可见）。' ;
		$emailDao = new model_common_mail();
		$title = '请确认辅导计划的完成情况';
		$emailDao->mailClear($title,$emailIds,$addMsg);
	}


 }
?>