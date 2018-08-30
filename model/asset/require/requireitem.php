<?php
/**
 * @author Administrator
 * @Date 2012��5��11�� 11:41:42
 * @version 1.0
 * @description:�ʲ�����������ϸ Model��
 */
 class model_asset_require_requireitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requireitem";
		$this->sql_map = "asset/require/requireitemSql.php";
		parent::__construct ();
	}

	/**
	 * �������Ϸ�������
	 * 1�����ݶ�Ӧ1����������
	 */
	 function setExeNum($itemIdArr){
	 	if(is_array($itemIdArr) && count($itemIdArr)>0){
		 	foreach( $itemIdArr as $key => $itemId ){
		 		$sql = "
					UPDATE ".$this->tbl_name."
					SET executedNum = executedNum + 1
					WHERE
						id = '".$itemId."'";
				$this->_db->query ( $sql );
		 	}
	 		return true;
	 	}else{
			throw new Exception("������Ϣ������.");
	 		return false;
	 	}
	 }

	/**
	 * �´�ɹ�����ʱ��ȡ�������б�
	 */
	function requireJsonApply_d($mainId = null){
		if($mainId){
			$this->searchArr['mainId'] = $mainId;
		}
		$rs = $this->list_d("list_apply");
		if($rs){
			//�ɹ�������ϸʵ����
			$applyItemDao = new model_asset_purchase_apply_applyItem();
			//������������֤
			$resultArr = array();
			foreach($rs as $key => $val){
				$applyedNum = $applyItemDao->getApplyedNum_d($val['id']);
				if($applyedNum >= $val['applyAmount']){
					unset($rs[$key]);
				}else{
					$rs[$key]['maxAmount'] = $rs[$key]['applyAmount'] = $val['applyAmount'] - $applyedNum;
					array_push($resultArr,$rs[$key]);
				}
			}
		}
		return $resultArr;
	}
	
	/**
	 * �´�����ת�ʲ�����ʱ��ȡ�������б�
	 */
	function requireinJsonApply_d($mainId = null){
		if($mainId){
			$this->searchArr['mainId'] = $mainId;
		}
		$rs = $this->list_d("list_apply");
		if($rs){
			//����ת�ʲ�������ϸʵ����
			$requireinitemDao = new model_asset_require_requireinitem();
			//������������֤
			$resultArr = array();
			foreach($rs as $key => $val){
				$applyedNum = $requireinitemDao->getApplyedNum_d($val['id']);
				if($applyedNum >= $val['applyAmount']){
					unset($rs[$key]);
				}else{
					$rs[$key]['maxNum'] = $rs[$key]['number'] = $val['applyAmount'] - $applyedNum;
					array_push($resultArr,$rs[$key]);
				}
			}
		}
		return $resultArr;
	}
	
	/**
	 * �����ʲ�����ӱ�ɹ����ţ��ɹ�������Ϣ
	 */
	function updatePurchInfo($obj){
		$sql = 
			"UPDATE ".$this->tbl_name."
			SET purchDept = '".$obj['purchDept']."',
				purchAmount = IFNULL(purchAmount,0)+".$obj['purchAmount']."
			WHERE
				mainId = ".$obj['mainId'].
			" AND productId = ".$obj['productId'];
		$this->_db->query($sql);
	}
 }