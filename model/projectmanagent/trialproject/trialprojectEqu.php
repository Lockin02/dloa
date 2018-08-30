<?php
/**
 * @author by liangjj
 * @Date 2014-06-11 13:34:07
 * @version 1.0
 * @description:������Ŀ M��
 */
 class model_projectmanagent_trialproject_trialprojectEqu  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_trialproject_trialproject_item";
		$this->sql_map = "projectmanagent/trialproject/trialprojectEquSql.php";
		parent::__construct ();
	}

    /**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		//���������ֵ��ֶ�
		$datadictDao = new model_system_datadict_datadict();
		$object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
        //��� add by chengl
		$codeRule=new model_common_codeRule();
		$object['projectCode']=$codeRule->pkCode($object);
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$newId = $this->create ( $object );
		//���������ƺ�Id
		$this->updateObjWithFile($newId);
		return $newId;
	}

	/**
	 * �ύ����
	 */
	function subConproject_d($id) {
		try {
			$sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
			$this->_db->query($sql);
			$arr = $this->get_d($id);

			//��ȡĬ�Ϸ�����
		   include (WEB_TOR."model/common/mailConfig.php");
		    $toMailId  = $mailUser['trialprojectCon']['TO_ID'];
		    $emailDao = new model_common_mail();
			$emailInfo = $emailDao->toStrialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_trialproject_trialproject", $arr['projectCode'], "ͨ��", $toMailId,"");

			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
    }
    /**
     * ���
     */
    function backConproject_d($id) {
		try {
			$sql = "update oa_trialproject_trialproject set serCon=2 where id = $id ";
			$this->_db->query($sql);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
    }
    /**
     * ����������
     */
    function backDelay_d($id) {
		try {
			$sql = "update oa_trialproject_trialproject set serCon=4 where id = $id ";
			$this->_db->query($sql);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
    }

    /**
     * ����������Ŀid ��ȡ��ͬ��Ϣ
     */
    function getContractBytrId($tid){
       if($tid){
       	 $sql = "select id,contractCode from oa_contract_contract " .
    			"where trialprojectId = '".$tid."' or " .
    			"chanceId = (select chanceId from oa_trialproject_trialproject where id = '".$tid."')";
        $Carr = $this->_db->getArray($sql);
        return $Carr;
       }
         return "";
    }

     /**
      * �ر�������Ŀ
      */
     function ajaxCloseTr_d($id) {
		try {
              $sql = "update oa_trialproject_trialproject set isFail = '2' where id = '".$id."' ";
			  $this->_db->query($sql);
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	}


	 /**
	  * ���ݺ�ͬID ��ȡ������������ĿID
	  */
	 function getTrialIdByconId($cid){
	 	$sql = "select chanceId,trialprojectId  from oa_contract_contract where id = '".$cid."'";
	 	$arr = $this->_db->getArray($sql);

	 	$chanceId = $arr[0]['chanceId'];
	 	$trialprojectId = $arr[0]['trialprojectId'];
	 	if(!empty($chanceId)){
	 		$sqlByChance = "select id from oa_trialproject_trialproject where chanceId = '".$chanceId."'";
	 		$arrA = $this->_db->getArray($sqlByChance);
	 	}
	 	$returnStr = "";
	 	if(!empty($arrA)){
	 		foreach($arrA as $k => $v){
	 			$tid = $v['id'];
	 			$returnStr .= $tid.",";
	 		}
	 	}
	 	if(!empty($trialprojectId)){
	 		$returnStr .= $trialprojectId;
	 	}
        //ȥ���ظ���id
	 	$tempArr = explode(",",$returnStr);
	 	$tempArr = array_flip($tempArr);
	 	$tempArr = array_flip($tempArr);

	 	$returnStr = implode(",",$tempArr);

	 	return $returnStr;

	 }

	//��ȡ����������ϸ
	function getTrialEqu_d($tid){
		$sql = "select conProductId from ".$this->tbl_name." where trialprojectId = '".$tid."'";
		return $this->_db->getArray($sql);
	}
     /**
      * ��ȡ��Ʒ��Ϣ
      * @param $data
      * @return mixed
      */
     function dealProduct_d($data) {
         if ($data) {
             $productIdArr = array();
             foreach ($data as $k => $v) {
                 if (!in_array($v['conProductId'], $productIdArr)) {
                     $productIdArr[] = $v['conProductId'];
                 }
             }

             // ��ʼ����Ʒ��ѯ
             $goodsDao = new model_goods_goods_goodsbaseinfo();
             $goodsInfo = $goodsDao->getGoodsHashInfo_d($productIdArr);
             foreach ($data as $k => $v) {
                 $data[$k]['proExeDeptName'] = $goodsInfo[$v['conProductId']]['auditDeptName'];
                 $data[$k]['proExeDeptId'] = $goodsInfo[$v['conProductId']]['auditDeptCode'];
                 $data[$k]['newExeDeptCode'] = $goodsInfo[$v['conProductId']]['exeDeptCode'];
                 $data[$k]['newExeDeptName'] = $goodsInfo[$v['conProductId']]['exeDeptName'];
             }
         }
         return $data;
     }
}
?>