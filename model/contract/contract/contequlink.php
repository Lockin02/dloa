<?php


/**
 * @author Administrator
 * @Date 2012��3��22�� 21:49:56
 * @version 1.0
 * @description:��ͬ/�������������� Model��
 */
class model_contract_contract_contequlink extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_equ_link";
		$this->sql_map = "contract/contract/contequlinkSql.php";
		parent :: __construct();
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
		$object = $this->addCreateInfo($object);
		$newId = $this->create($object);
		return $newId;
	}


//	/**
//	 * ȷ�Ϻ�ͬ
//	 */
//	function confirmAudit($spid) {
//		try {
//			$this->start_d();
//			$otherdatas = new model_common_otherdatas();
//			$folowInfo = $otherdatas->getWorkflowInfo($spid);
//			$objId = $folowInfo['objId'];
//			if (!empty ($objId)) {
//				$contract = $this->get_d($objId);
//				if ($contract['ExaStatus'] == "���") {
//					$sql = "update oa_contract_contract set dealStatus='1' where id= '" . $contract['contractId'] . "'";
//					$this->query($sql);
//				}
//
//			}
//			$this->commit_d();
//		} catch (Exception $e) {
//			$this->rollBack();
//		}
//	}
	/**
	 * ȷ�Ϻ�ͬ
	 */
	function confirmAudit($objId) {
		if (!empty ($objId)) {
			$contract = $this->get_d($objId);
			if ($contract['ExaStatus'] == "���") {
				$sql = "update oa_contract_contract set dealStatus='1' where id= '" . $contract['contractId'] . "'";
				$this->query($sql);
			}

		}
	}


	/**
	 * ���ϱ������ͨ����ȷ��
	 */
	function confirmChange($objId) {
		if (!empty ($objId)) {
			$link = $this->get_d($objId);
			$contractId = $link['contractId'];
			//$oldlink = $this->get_d ( $link['originalId'] );
//				$changeLogDao = new model_common_changeLog('contractequ');
//				$changeLogDao->confirmChange_d($link);
//				//������������Ϲ���
//				$sql = "update oa_contract_equ o1 left join oa_contract_equ o2 on " .
//				"o1.contractId=o2.contractId and o1.id!=o2.id and o1.linkId=o2.linkId SET o1.parentEquId=o2.id " .
//				"where o1.isConfig=o2.isCon and o1.linkId=$originalId and o2.isCon!='' " .
//				"and o2.isCon is not null and o2.isDel=0 and o2.isTemp=0";
//				$this->query($sql);
//				//echo $sql;
//				//����parentEquId Ϊ0��
//				//1.�������������� 2.���ת����
//				$sql = "update oa_contract_equ o1 left join oa_contract_equ o2 on " .
//				"o1.contractId=o2.contractId and o1.id!=o2.id   SET o1.parentEquId=0 " .
//				"where  o1.linkId=$originalId and o2.originalId=o1.id " .
//				"and o1.contractId=$contractId and o2.parentEquId=0";
//				$this->query($sql);

//	      	 	$this->ctDealRelInfoAtChangeAudit($link);
		}
	}

	/**
	 * ��ȡ����ȷ�ϼ��豸��Ϣ
	 */
	function getLinkAndEqus_d($linkId) {
		$link = $this->get_d($linkId);
		$equDao = new model_contract_contract_equ();
		$equDao->searchArr['linkId'] = $linkId;
		$equs = $equDao->list_d();
		$link['equs'] = $equs;
		return $link;
	}

	/**
	 * �ָ���ɾ������
	 */
//	function ctDealRelInfoAtChangeAudit($contract){
//		$equDao = new model_contract_contract_equ();
//
//		$equDao->searchArr['contractId']=$contract['contractId'];
//		$equDao->searchArr['isTemp']=0;
//		$equDao->searchArr['isDel']=1;
//		$delRows = $equDao->list_d();
//		foreach( $equDao->searchArr as $key=>$val ){
//			unset($equDao->searchArr[$key]);
//		}
//		$equDao->searchArr['contractId']=$contract['contractId'];
//		$equDao->searchArr['isTemp']=0;
//		$equDao->searchArr['isDel']=0;
//		$tempRows = $equDao->list_d();
//			$licDao = new model_yxlicense_license_tempKey();
//			foreach( $tempRows as $key=>$val ){
//				echo $val['license'];
//				echo $delLic = $licDao->getLicenseVal($val['license']);
//				echo "**";
//			}
//		if( is_array($delRows)&&count($delRows)>0 ){
//			foreach( $delRows as $key=>$val ){
//				foreach( $tempRows as $index=>$row ){
//					if( $val['productId']==$row['productId']&&$val['contractId']==$row['contractId']){
//						echo $delLic = $licDao->getLicenseVal($val['license']);
//						echo "<br/>";
//						echo $tempLic = $licDao->getLicenseVal($row['license']);
//						if( $delLic==$tempLic ){
//							$equDao->deletes($row['id']);
//							$recoverArr = array(
//								'id'=>$val['id'],
//								'number'=>$row['number'],
//								'isDel'=>0
//							);
//							$equDao->edit_d($recoverArr,true);
//						}
//					}
//				}
//			}
//		}
//	}

	/**
	 * �ָ���ɾ������
	 */
	function ctDealRelInfoAtChangeAudit($contract){
		$equDao = new model_contract_contract_equ();

		$equDao->searchArr['contractId']=$contract['contractId'];
		$equDao->searchArr['isTemp']=0;
		$equDao->searchArr['isDel']=1;
		$delRows = $equDao->list_d();
		foreach( $equDao->searchArr as $key=>$val ){
			unset($equDao->searchArr[$key]);
		}
		$equDao->searchArr['contractId']=$contract['contractId'];
		$equDao->searchArr['isTemp']=0;
		$equDao->searchArr['isDel']=0;
		$tempRows = $equDao->list_d();
		if( is_array($delRows)&&count($delRows)>0 ){
			$licDao = new model_yxlicense_license_tempKey();
			foreach( $delRows as $key=>$val ){
				foreach( $tempRows as $index=>$row ){
					if( $val['productId']==$row['productId']&&$val['conProductId']==$row['conProductId']){
						$delLic = $licDao->getLicenseVal($val['license']);
						$tempLic = $licDao->getLicenseVal($row['license']);
						if( $delLic==$tempLic ){
							$equDao->deletes($row['id']);
							$recoverArr = array(
								'id'=>$val['id'],
								'number'=>$row['number'],
								'isDel'=>0
							);
							$equDao->edit_d($recoverArr,true);
						}
					}
				}
			}
		}
	}


}
?>