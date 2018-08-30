<?php

/**
 * @author Administrator
 * @Date 2012-10-19 10:32:11
 * @version 1.0
 * @description:��ǰ֧������ Model��
 */
class model_projectmanagent_support_support extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_support";
		$this->sql_map = "projectmanagent/support/supportSql.php";
		parent :: __construct();
	}


	/**
	 *����Զ�����
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
	 * ��дadd_d����
	 */
	function add_d($object) {
		try {
			$this->start_d();
			//����textarea����
			$object['customerInfo'] = nl2br($object['customerInfo']);
			$object['customerRemark'] = nl2br($object['customerRemark']);
			$object['goals'] = nl2br($object['goals']);
			$object['exContent'] = nl2br($object['exContent']);
			$object['exPlan'] = nl2br($object['exPlan']);
			$object['prepared'] = nl2br($object['prepared']);

			$object['supportCode'] = $this->supportCode();
			$object['ExaStatus'] = "δ����";

			//����������Ϣ
			$newId = parent :: add_d($object, true);

            //���������ƺ�Id
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
    * ��дedit_d
    */
   function edit_d($object) {
		try {
			$this->start_d();

			//����������Ϣ
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
    * ������ɺ�ȷ�Ϸ���
    */
   	function workflowCallBack($spid,$applyinfo) {
		try {
			$this->start_d();
			$otherdatas = new model_common_otherdatas();
			$folowInfo = $otherdatas->getWorkflowInfo($spid);
			$objId = $folowInfo['objId'];
			if (!empty ($objId)) {
				$objInfoOld = $this->get_d($objId);
				if ($objInfoOld['ExaStatus'] == "���") {
				   if(!empty($applyinfo)){
				   	  //���½�����Ա
		               parent :: edit_d($applyinfo, true);
				   }
				   $objInfo = $this->get_d($objId);
				   //�Զ���������Ա�����̻��Ŷ�
				    $this->chanceTrackInfo($objInfo);
//					//������ɺ����ʼ�
					//��ȡĬ�Ϸ�����
					$tomail = $objInfo['exchangeId'];;
					$addmsg = "".$objInfo['exchangeName']."  ���ã�<br/>���Ϊ��" .$objInfo['supportCode']. "������ǰ֧������������ͨ����<br/>" .
							"ͬʱ��ָ����Ϊ�����齻����Ա�� ���ѽ��������Ӧ�̻�����Ŀ�Ŷӡ�<br/>" .
							"�̻���� :��<span style='color:blue'>".$objInfo['projectCode']."</span>   �̻����� ��<span style='color:blue'>".$objInfo['projectName']."</span>" .
						    "  �������ڡ��Ҹ��ٵ��̻��� �б����ҵ������̻���Ϣ";
					$emailDao = new model_common_mail();
					$emailInfo = $emailDao->supportMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_sale_chance_support", "����", "ͨ��", $tomail, $addmsg);
				}
			}
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
		}
	}

	/**
	 * �����̻��Ŷӳ�Ա
	 */
	function chanceTrackInfo($objInfo){
		 //�����Ŷӳ�Ա�Ƿ��ظ�
		 $findRepeatSql = "select * from oa_sale_chance where id=".$objInfo['SingleId']." and FIND_IN_SET('".$objInfo['exchangeId']."',trackmanId);";
		 $Num = $this->_db->getArray($findRepeatSql);
       if(empty($Num)){
       	   //����ӱ������
		    $tracker[0]['trackmanId'] = $objInfo['exchangeId'];
			$tracker[0]['trackman'] = $objInfo['exchangeName'];
			$tracker[0]['chanceId'] = $objInfo['SingleId'];
			$chanceequDao = new model_projectmanagent_chancetracker_chancetracker();
			$chanceequDao->createBatch($tracker);
			//Ȩ��
			$authorizeInfo[0]['trackman'] = $objInfo['exchangeName'];
			$authorizeInfo[0]['trackmanId'] = $objInfo['exchangeId'];
			$authorizeInfo[0]['limitInfo'] = "";
			$authorizeDao = new model_projectmanagent_chance_authorize();
			$authorizeDao->createBatch($authorizeInfo, array (
				'chanceId' => $objInfo['SingleId']
			));
			//�ж��Ƿ��и�����
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