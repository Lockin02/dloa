<?php
/**
 * @author by liangjj
 * @Date 2014-06-11 13:34:07
 * @version 1.0
 * @description:试用项目 M层
 */
 class model_projectmanagent_trialproject_trialprojectEqu  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_trialproject_trialproject_item";
		$this->sql_map = "projectmanagent/trialproject/trialprojectEquSql.php";
		parent::__construct ();
	}

    /**
	 * 添加对象
	 */
	function add_d($object, $isAddInfo = false) {
		//处理数据字典字段
		$datadictDao = new model_system_datadict_datadict();
		$object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
        //编号 add by chengl
		$codeRule=new model_common_codeRule();
		$object['projectCode']=$codeRule->pkCode($object);
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$newId = $this->create ( $object );
		//处理附件名称和Id
		$this->updateObjWithFile($newId);
		return $newId;
	}

	/**
	 * 提交方法
	 */
	function subConproject_d($id) {
		try {
			$sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
			$this->_db->query($sql);
			$arr = $this->get_d($id);

			//获取默认发送人
		   include (WEB_TOR."model/common/mailConfig.php");
		    $toMailId  = $mailUser['trialprojectCon']['TO_ID'];
		    $emailDao = new model_common_mail();
			$emailInfo = $emailDao->toStrialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_trialproject_trialproject", $arr['projectCode'], "通过", $toMailId,"");

			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
    }
    /**
     * 打回
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
     * 延期申请打回
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
     * 根据试用项目id 获取合同信息
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
      * 关闭试用项目
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
	  * 根据合同ID 获取关联的试用项目ID
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
        //去除重复的id
	 	$tempArr = explode(",",$returnStr);
	 	$tempArr = array_flip($tempArr);
	 	$tempArr = array_flip($tempArr);

	 	$returnStr = implode(",",$tempArr);

	 	return $returnStr;

	 }

	//获取试用物料明细
	function getTrialEqu_d($tid){
		$sql = "select conProductId from ".$this->tbl_name." where trialprojectId = '".$tid."'";
		return $this->_db->getArray($sql);
	}
     /**
      * 获取产品信息
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

             // 初始化产品查询
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