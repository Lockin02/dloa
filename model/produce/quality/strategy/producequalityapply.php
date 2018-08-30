<?php
//����ӿ�
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';
/**
 * �����ƻ��ʼ�����
 * @author kuangzw
 */
class model_produce_quality_strategy_producequalityapply extends model_base implements iqualityapply {
	/**
	 * ˽�г�Ա - ҵ�������
	 */
	private $mainClass = "model_produce_plan_produceplan";

	/**
	 * �����ʼ�����ʱ�������ҵ����Ϣ
	 * @param $paramArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$mainDao = new $this->mainClass();
		if (is_array ( $relItemArr )) {
			$sql = "update $mainDao->tbl_name set qualityNum=qualityNum+{$relItemArr[0]['qualityNum']},docStatus='9' where id={$relItemArr[0]['relDocItemId']}";
			$mainDao->query( $sql );
		}
	}

	/**
	 * �޸��ʼ�����ʱԴ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE) {
		return true;
	}

	/**
	 * ͨ���ʼ���÷���
	 * �����ƻ�id
	 * ͨ������
	 */
	function dealRelItemPass($relDocId ,$relDocItemId ,$qualityNum) {
		//ʵ����������������
		$mainDao = new $this->mainClass();
		$mainDao->editQualifiedNum_d($relDocId ,$qualityNum);
		//���¼ƻ���״̬
		$mainDao->updateDocStatusByQuality($relDocId);
	}

    /**
     * �𻵷��ε��÷���
     * @param $relDocId
     * @param $relDocItemId
     * @param $qualityNum
     */
    function dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum) {
        //ʵ����������������
        $mainDao = new $this->mainClass();
        $mainDao->editQualifiedNum_d($relDocId ,$qualityNum);
        //���¼ƻ���״̬
        $mainDao->updateDocStatusByQuality($relDocId);
    }

	/**
	 * ����ʼ�����ʱԴ����ҵ����
	 * @param  $paramArr �����ʼ�ϸ���������
	 */
	function dealRelInfoAtConfirm($paramArr = false) {
        //�����ʼ��������
        $mainDao = new $this->mainClass();
        $mainDao->editQualifiedNum_d($paramArr['relDocId'] ,$paramArr['thisCheckNum']);
        //���¼ƻ���״̬
        $mainDao->updateDocStatusByQuality($paramArr['relDocId']);
	}

    /**
     * ����ʼ��˻�ʱ�������ϵ�
     * @param  $paramArr �����ʼ�ϸ���������
     */
    function dealRelInfoAtBack($paramArr = false) {
		$mainDao = new $this->mainClass();
        $mainDao->editBackNum_d($paramArr);
    }

    /**
     * ����ʼ��ò�����ʱ�������ϵ�
     * @param  $paramArr �����ʼ�ϸ���������
     * @param  $relItemArr �ʼ챨��������
     */
    function dealRelInfoAtReceive($paramArr = false) {
    	return true;
    }

    /**
     * �����ʼ챨��
     */
	function dealRelInfoAtUnconfirm($paramArr = false){
		//�����ʼ��������
		$mainDao = new $this->mainClass();
        $mainDao->editQualityUnconfirmInfo($paramArr['relDocId'],$paramArr['thisCheckNum']);
        //���¼ƻ���״̬
        $mainDao->updateDocStatusByQuality($paramArr['relDocId']);
	}
	
	/**
	 * �ʼ������ص��÷���
	 * @param $relDocId Դ��id
	 * @param $relDocItemId Դ����ϸid
	 * @param $qualityNum �������
	 */
	function dealRelItemBack($relDocId,$relDocItemId,$qualityNum){
		//ʵ����
		$mainDao = new $this->mainClass();
		$mainDao->editQualityInfoAtBack($relDocId,$qualityNum);
		//���¼ƻ���״̬
		$mainDao->updateDocStatusByQuality($relDocId);
	}
	
	/**
	 * ������ҵ��״̬���·��� -�����ʼ�������
	 */
	function dealRelInfoBack($relDocId){
		return true;
	}
	
	/**
	 * ��ȡԴ���嵥��Ϣ
	 */
	function getRelDocInfo($id) {
		$mainDao = new $this->mainClass();
		$mainObj = $mainDao->get_d ( $id );
		$rtObj = array(
			'supplierName' => $mainObj['customerName'],
            'supplierId' => $mainObj['customerId'],
			'applyUserCode' => $_SESSION['USER_ID'],
            'applyUserName' => $_SESSION['USERNAME']
		);
		return $rtObj;
	}

	/**
	 * û�дӱ�,ȡ�����ʼ�����
	 */
	function getRelDetailInfo($id){
		$mainDao = new $this->mainClass();
		$mainObj = $mainDao->get_d ( $id );
		
		$canQualityNum = $mainObj['planNum'] - $mainObj['qualityNum'];
		//��������
		$rtArr = array();
		if($canQualityNum > 0){
			//ʵ��������
			$productInfoDao = new model_stock_productinfo_productinfo();
			$productInfo = $productInfoDao->get_d($mainObj['productId']);
			//�����ֵ�
			$datadictDao = new model_system_datadict_datadict();
			array_push($rtArr,array(
				'productId' => $mainObj['productId'],
				'productCode' => $mainObj['productCode'],
				'productName' => $mainObj['productName'],
				'pattern' => $mainObj['pattern'],
				'unitName' => $mainObj['unitName'],
				'qualityNum' => $canQualityNum,
				'relDocItemId' => $mainObj['id'],
				'serialId' => $mainObj['serialId'],
				'serialName' => $mainObj['serialName'],
				'checkType' => $productInfo['checkType'],
				'checkTypeName' => $datadictDao->getDataNameByCode($productInfo['checkType'])
			));
		}
		return $rtArr;
	}

	/**
	 * �ж��Ƿ�ɳ���
	 * @param $idArr �ƻ���id����
	 */
	function checkCanBack_d($idArr){
		$ids = implode(',',$idArr);
		//ʵ����
		$mainDao = new $this->mainClass();
		return $mainDao->checkCanBack_d($ids);
	}

	/**
	 * ������ҵ��״̬���·��� -- ���ʱֱ�Ӵ������,���������ݸ��´���
	 */
	function dealRelInfoCompleted($relDocId){
        return true;
	}
}