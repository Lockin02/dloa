<?php
/**
 * @author Administrator
 * @Date 2012-08-29 17:09:19
 * @version 1.0
 * @description:导师奖励管理 Model层
 */
 class model_hr_tutor_reward  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_reward";
		$this->sql_map = "hr/tutor/rewardSql.php";
		parent::__construct ();
	}

	//编号自动生成 （临时）
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
	 * 获取导师考核表明细信息
	 */
	function getRewardInfo_d(){
        //当前登录人部门
        $deptId = $_SESSION['DEPT_ID'];
        $findSql = "select t.id as tutorId,t.userNo,t.userAccount,t.userName,t.assessmentScore,t.studentNo,t.studentAccount,t.studentName,s.tryEndDate" .
        		" from oa_hr_tutor_records t left join oa_hr_tutor_scheme s on t.id=s.tutorId where t.deptId='".$deptId."' and t.assessmentScore between 0 and 100 and isReward=0";
        $infoArr = $this->_db->getArray($findSql);
        return $infoArr;
	}
	/**
	 * 获取导师考核表明细信息(不按部门过滤)
	 */
	 function getRewardList_d(){
	 	$findSql = "select t.id as tutorId,t.deptId as tutorDeptId,t.deptName as tutorDeptName,t.userNo,t.userAccount,t.userName,t.assessmentScore,t.studentNo,t.studentAccount,t.studentName,s.tryEndDate" .
        		" from oa_hr_tutor_records t left join oa_hr_tutor_scheme s on t.id=s.tutorId where  t.assessmentScore between 0 and 100 and isReward=0";
        $infoArr = $this->_db->getArray($findSql);
        return $infoArr;
	 }

  	/**
	 * 重写新增方法
	 */
		function add_d($object){
		try{

			$this->start_d();
            $object['code'] = $this->rewardCode();
            $object['isPublish'] = 0;
			//主表信息
			$newId = parent::add_d($object,true);
            //插入明细
            $infoDoa = new model_hr_tutor_rewardinfo();
            $infoDoa->createBatch($object['rewardinfo'], array ( 'rewardId' => $newId ), 'userName');

//            //改变导师管理记录状态
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
	 * 从写edit_D
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//修改主表信息
			parent::edit_d($object,true);
			$id = $object['id'];
			//插入明细
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
	 * 根据奖励id获取从表详细更新奖励状态
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
	 * 确认奖励发放
	 */
	 function conGrant_d($object){
	 	try{
	 		  $this->start_d();
		 	  $flag = 1;//用于判断是否全部导师奖励都已发放
		 	  $rewardInfoDao = new model_hr_tutor_rewardinfo();
		 	  foreach($object['rewardinfo'] as $key=>$val){
		 	  	//如果有一个为空，则$flag置为0
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
	 * 确认发布奖励相关信息
	 */
	 function publish_d($id){
	 	try{
			$obj = $this->get_d($id);
			$obj['isPublish']=1;
			parent::edit_d($obj);

	        //根据rewardId获取导师获奖记录
	       	$rewardInfoDao = new model_hr_tutor_rewardinfo();
	       	$rewardInfo = $rewardInfoDao->findAll(array('rewardId'=>$id));
	       	$emailDao = new model_common_mail();
	       	//邮件提醒导师查阅导师考核成绩
	       	if(is_array($rewardInfo)){

		       	foreach($rewardInfo as $key=>$val){
					$msg ='导师'.$val['userName'].'给学员'.$val['studentName'].'的辅导工作已经完成考核，
							请登陆OA系统查看考核成绩。<br>OA路径：导航栏--->个人办公--->工作任务--->人事类--->导师工作（选中记录右击可见）';

					$title = '导师'.$val['userName'].'（辅导'.$val['studentName'].'学员）的考核成绩';

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