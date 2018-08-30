<?php


/**
 * �̶��ʲ��ճ���������model����
 * @author zengzx
 * @since 1.0 - 2011-11-29
 */
class model_asset_daily_dailyCommon extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_relBusiness";
		//		$this->sql_map = "asset/daily/allocationSql.php";
		parent :: __construct();
			$this->relatedClassNameArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
		"oa_asset_allocation" => "asset_daily_allocation", //�ʲ�����
		"oa_asset_borrow" => "asset_daily_borrow", //�ʲ�����
		"oa_asset_charge" => "asset_daily_charge", //�ʲ�����
		"oa_asset_return" => "asset_daily_return", //�ʲ��黹
		"oa_asset_rent" => "asset_daily_rent", //�ʲ�����
		"oa_asset_keep" => "asset_daily_keep", //�ʲ�ά��
		"oa_asset_lose" => "asset_daily_lose", //�ʲ���ʧ

		);
			$this->relatedStrategyArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
		"oa_asset_allocation" => "model_asset_daily_allocation", //�ʲ�����
		"oa_asset_borrow" => "model_asset_daily_borrow", //�ʲ�����
		"oa_asset_charge" => "model_asset_daily_charge", //�ʲ�����
		"oa_asset_return" => "model_asset_daily_return", //�ʲ��黹
		"oa_asset_rent" => "model_asset_daily_rent", //�ʲ�����
		"oa_asset_keep" => "model_asset_daily_keep", //�ʲ�ά��
		"oa_asset_lose" => "model_asset_daily_lose", //�ʲ���ʧ

		);
			$this->relatedEquStrategyArr = array (//��ͬ���ͳ������������,������Ҫ���������׷��
		"oa_asset_allocation" => "model_asset_daily_allocationitem", //�ʲ������嵥
		"oa_asset_borrow" => "model_asset_daily_borrowitem", //�ʲ������嵥
		"oa_asset_charge" => "model_asset_daily_chargeitem", //�ʲ������嵥
		"oa_asset_return" => "model_asset_daily_returnitem", //�ʲ��黹�嵥
		"oa_asset_rent" => "model_asset_daily_rentitem", //�ʲ������嵥
		"oa_asset_keep" => "model_asset_daily_keepitem", //�ʲ�ά���嵥
		"oa_asset_lose" => "model_asset_daily_loseitem", //�ʲ���ʧ�嵥

		);
	}

	/**
	 *  �̶��ʲ��ճ���������ʱִ�в���
	 * 2012��3��2�� 11:08:26
	 */
	function ctUpdateRelInfoAtAudit($row, $docType) {
		if ($docType == 'oa_asset_lose') {
			$dailyStrategy = $this->relatedStrategyArr[$docType];
			$dailyDao = new $dailyStrategy ();
			//������Ե�ҵ���������
			$dailyObj = $dailyDao->updateRelInfoAtAudit($row);
		}
		return true;
	}

	/**
	 *  �̶��ʲ��ճ���������ͨ����ִ�в���
	 */
	function ctDealRelInfoAtAudit($id, $docType) {
		$dailyStrategy = $this->relatedStrategyArr[$docType];
		$dailyDao = new $dailyStrategy ();
		$relModelObj = $dailyDao->get_d($id);
		if ($relModelObj['ExaStatus'] == '���') {
			$condition = array (
				'businessCode' => $docType,

			);

			//����ҵ�������ж�����Ӧ�ı䶯��ʽ
			$relInfo = $this->find($condition);
			$relInfo['businessId'] = $id;
			$relInfo['businessType'] = $relInfo['businessCode'];
			$relInfo['businessCode'] = $relModelObj['billNo'];
			//������Ե�ҵ���������
			$dailyObj = $dailyDao->dealRelInfoAtAudit($id, $relInfo);
			/*�ʼ����� ����ȷ����*/
			$emailDao = new model_common_mail();
			$mailInfo = $relModelObj['recipient'] ." ����: <br/> ������������������һ���̶��ʲ�������Ϣ��ָ����Ϊ����ȷ����<br/>���ݱ�ţ� ".$relModelObj ['billNo']." " ;
	        $emailInfo = $emailDao->batchEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],"oa_asset_allocation","����","ͨ��",$relModelObj['recipientId'],$mailInfo);

			return true;
		} else {
			return false;
		}
	}
	/**
	 * �黹�ʲ����޸Ĺ��������ʲ��嵥��״̬λ��
	 */
	function setRelEquReturnStatus($id, $docType, $assetId) {
		$dailyEquStrategy = $this->relatedEquStrategyArr[$docType];
		$dailyEquDao = new $dailyEquStrategy ();
		return $dailyEquDao->setEquStatus($id, $assetId, 1);
	}

	/**
	 * �黹�ʲ����޸Ĺ������ݵ�״̬λ��
	 */
	function setRelReturnStatus($id, $docType) {
		$dailyStrategy = $this->relatedStrategyArr[$docType];
		$dailyDao = new $dailyStrategy ();
		return $dailyDao->setDocStatus($id);
	}

	function setRelEquAllocateStatus($assetId) {
		$allocationRelArr = array (
			'oa_asset_borrow' => 'borrow',
			'oa_asset_charge' => 'charge'
		);
		$alterDao = new model_asset_change_assetchange();
		$alterInfo = $alterDao->getSecondChangeRecord($assetId);
		$docType = $alterInfo['businessType'];
		$docId = $alterInfo['businessId'];
		$objType = array_search($docType, $allocationRelArr);
		if ($objType && $docId) {
			$dailyStrategy = $this->relatedEquStrategyArr[$objType];
			$dailyEquDao = new $dailyStrategy ();
			$dailyEquDao->setEquStatus($docId, $assetId, 3);
			$this->setRelReturnStatus($docId, $objType);
		}
		return true;
	}


	/**
	 *  �̶��ʲ��ճ�����ǩ�պ�ִ�в���
	 */
	function ctDealRelInfoAtSign($id, $docType) {
		$dailyStrategy = $this->relatedStrategyArr[$docType];
		$dailyDao = new $dailyStrategy ();
		$relModelObj = $dailyDao->get_d($id);
		if ($relModelObj['isSign'] == '1') {
			$condition = array (
				'businessCode' => $docType,

			);
			//����ҵ�������ж�����Ӧ�ı䶯��ʽ
			$relInfo = $this->find($condition);
			$relInfo['businessId'] = $id;
			$relInfo['businessType'] = $relInfo['businessCode'];
			$relInfo['businessCode'] = $relModelObj['billNo'];
			//������Ե�ҵ���������
			$dailyObj = $dailyDao->dealRelInfoAtAudit($id, $relInfo);
			return true;
		} else {
			return false;
		}
	}

}
?>