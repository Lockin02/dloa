<?php
/*
 * Created on 2011-6-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * ���к�ͬ������
 */

//include_once "module/phpExcel/classes/PHPExcel.php";
//include_once "module/phpExcel/Classes/PHPExcel/IOFactory.php";
//include_once "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
//include_once "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
//include_once "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
class model_contract_common_relcontract extends model_base{

	function __construct() {
		$this->contDao = new model_contract_contract_contract();
		parent::__construct ();
	}


   /**
    * ���ݺ�ͬ���жϺ�ͬ�Ƿ����
    */
    function orderBe($orderTempCode,$orderCode){
         if((empty($orderTempCode) && !empty($orderCode)) || (!empty($orderTempCode) && !empty($orderCode))){
             $sql = "select id from view_oa_order where orderCode = '$orderCode'";
         }else if(!empty($orderTempCode) && empty($orderCode)){
             $sql = "select id from view_oa_order where orderTempCode = '$orderTempCode'";
         }
         $orderCode = $this->_db->getArray($sql);
         return $orderCode;
    }

    //�Ƿ�
    function rtYesOrNo_d($value){
		if($value == 1){
			return '��';
		}else{
			return '��';
		}
    }


    /**
     * ����ɱ�ȷ�ϴ��
     */
    function ajaxBack_d($id,$remark = ''){
		try {
			$this->start_d();
			
        	$conDao = new model_contract_contract_contract();
            $row = $conDao->get_d($id);
			$sql = "update oa_contract_contract set RdproConfirm = '0',EngConfirm='0',isSubApp = '0',isSubAppChange='0' where id = $id ";

			$this->_db->query($sql);
			$findSql = "select dealStatus from oa_contract_contract where id = $id ";
			$arr = $this->_db->getArray($findSql);
		    if($arr[0]['dealStatus'] == '2'){
		   		$sqlA = "update oa_contract_contract set dealStatus = '1' where id = $id ";
			    $this->_db->query($sqlA);
		   	}
            if($row['isSubAppChange'] == '0'){
            	$isChange = 0;
            }else{
                $isChange = 2;
                //���±����ʷ����״̬Ϊ����ȷ�ϴ��
                $sql = "select max(id) as id from oa_contract_changlog where objType = 'contract' and objId = ". $id;
                $rs = $this->_db->getArray($sql);
                if(!empty($rs[0]['id'])){
                	$sql = "update oa_contract_changlog set ExaStatus = '����ȷ�ϴ��',ExaDT = '" . day_date . "' where id = ". $rs[0]['id'];
                	$this->_db->query($sql);
                }
            }
            $handleDao = new model_contract_contract_handle();
            $handleDao->handleAdd_d(array(
                "cid"=> $id,
                "stepName"=> "���������",
                "isChange"=> $isChange,
                "stepInfo"=> "",
                "remark" => $remark
            ));
//			$Code = $this->find(array (
//				"id" => $id
//			), null, "Code");
//			//��ȡĬ�Ϸ�����
//			include (WEB_TOR . "model/common/mailConfig.php");
//			$emailDao = new model_common_mail();
//			$emailInfo = $emailDao->subBorrowEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "subProBorrowMail", $Code['Code'], "ͨ��", $mailUser['subProBorrow']['subNameId']);
            $this->commit_d();
            return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}
