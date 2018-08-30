<?php

/**
 * @author Administrator
 * @Date 2012-10-19 10:32:11
 * @version 1.0
 * @description:售前支持申请 Model层
 */
class model_projectmanagent_support_support extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_support";
		$this->sql_map = "projectmanagent/support/supportSql.php";
		parent :: __construct();
	}


	/**
	 *编号自动生成
	 */
     function supportCode(){
        $billCode = "SQZC".date("Ymd");
//        $billCode = "JL201208";
		$sql="select max(RIGHT(c.supportCode,4)) as maxCode,left(c.supportCode,12) as _maxbillCode " .
				"from oa_sale_chance_support c group by _maxbillCode having _maxbillCode='".$billCode."'";

		$resArr=$this->findSql($sql);
		$res=$resArr[0];
		if(is_array($res)){
			$maxCode=$res['maxCode'];
			$maxBillCode=$res['maxbillCode'];
			$newNum=$maxCode+1;
			switch(strlen($newNum)){
				case 1:$codeNum="000".$newNum;break;
				case 2:$codeNum="00".$newNum;break;
				case 3:$codeNum="0".$newNum;break;
				case 4:$codeNum=$newNum;break;
			}
			$billCode.=$codeNum;
		}else{
			$billCode.="0001";
		}

		return $billCode;
	}

	/**
	 * 重写add_d方法
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//处理textarea换行
			$object['customerInfo'] = nl2br($object['customerInfo']);
			$object['customerRemark'] = nl2br($object['customerRemark']);
			$object['goals'] = nl2br($object['goals']);
			$object['exContent'] = nl2br($object['exContent']);
			$object['exPlan'] = nl2br($object['exPlan']);
			$object['prepared'] = nl2br($object['prepared']);

			$object['supportCode'] = $this->supportCode();
			$object['ExaStatus'] = "未审批";

			//插入主表信息
			$newId = parent :: add_d($object, true);

            //处理附件名称和Id
			$this->updateObjWithFile($newId);

			$this->commit_d();
//						$this->rollBack();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}
   /**
    * 重写edit_d
    */
   function edit_d($object) {
		try {
			$this->start_d();

			//插入主表信息
			parent :: edit_d($object, true);

			$this->commit_d();
//						$this->rollBack();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

   /**
    * 审批完成后确认方法
    */
   	function workflowCallBack($spid,$applyinfo) {
		try {
			$this->start_d();
			$otherdatas = new model_common_otherdatas();
			$folowInfo = $otherdatas->getWorkflowInfo($spid);
			$objId = $folowInfo['objId'];
			if (!empty ($objId)) {
				$objInfoOld = $this->get_d($objId);
				if ($objInfoOld['ExaStatus'] == "完成") {
				   if(!empty($applyinfo)){
				   	  //更新交流人员
		               parent :: edit_d($applyinfo, true);
				   }
				   $objInfo = $this->get_d($objId);
				   //自动将交流人员加入商机团队
				    $this->chanceTrackInfo($objInfo);
//					//审批完成后发送邮件
					//获取默认发送人
					$tomail = $objInfo['exchangeId'];;
					$addmsg = "".$objInfo['exchangeName']."  您好：<br/>编号为《" .$objInfo['supportCode']. "》的售前支持申请已审批通过。<br/>" .
							"同时已指定您为“建议交流人员” 并已将您加入对应商机的项目团队。<br/>" .
							"商机编号 :　<span style='color:blue'>".$objInfo['projectCode']."</span>   商机名称 ：<span style='color:blue'>".$objInfo['projectName']."</span>" .
						    "  您可以在“我跟踪的商机” 列表内找到该条商机信息";
					$emailDao = new model_common_mail();
					$emailInfo = $emailDao->supportMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_sale_chance_support", "审批", "通过", $tomail, $addmsg);
				}
			}
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
		}
	}

	/**
	 * 插入商机团队成员
	 */
	function chanceTrackInfo($objInfo){
		 //查找团队成员是否重复
		 $findRepeatSql = "select * from oa_sale_chance where id=".$objInfo['SingleId']." and FIND_IN_SET('".$objInfo['exchangeId']."',trackmanId);";
		 $Num = $this->_db->getArray($findRepeatSql);
       if(empty($Num)){
       	   //插入从表跟踪人
		    $tracker[0]['trackmanId'] = $objInfo['exchangeId'];
			$tracker[0]['trackman'] = $objInfo['exchangeName'];
			$tracker[0]['chanceId'] = $objInfo['SingleId'];
			$chanceequDao = new model_projectmanagent_chancetracker_chancetracker();
			$chanceequDao->createBatch($tracker);
			//权限
			$authorizeInfo[0]['trackman'] = $objInfo['exchangeName'];
			$authorizeInfo[0]['trackmanId'] = $objInfo['exchangeId'];
			$authorizeInfo[0]['limitInfo'] = "";
			$authorizeDao = new model_projectmanagent_chance_authorize();
			$authorizeDao->createBatch($authorizeInfo, array (
				'chanceId' => $objInfo['SingleId']
			));
			//判断是否有跟踪人
			$findIsNull = "select trackmanId from oa_sale_chance where id=".$objInfo['SingleId']."";
			$isNull = $this->_db->getArray($findIsNull);

		   if(empty($isNull[0]['trackmanId'])){
             $updateChanceSql = "update oa_sale_chance set trackman= '".$objInfo['exchangeName']."',trackmanId= ',".$objInfo['exchangeId']."' where id=".$objInfo['SingleId']."";
		     $this->query($updateChanceSql);
		   }else{
		   	 $updateChanceSql = "update oa_sale_chance set trackman= CONCAT(trackman,',".$objInfo['exchangeName']."'),trackmanId= CONCAT(trackmanId,',".$objInfo['exchangeId']."') where id=".$objInfo['SingleId']."";
		     $this->query($updateChanceSql);
		   }
       }
	}
}
?>