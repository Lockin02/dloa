<?php
//����ӿ�
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';
/**
 * �����ù黹�ʼ�����
 * @author kuangzw
 */
class model_produce_quality_strategy_salereturnqualityapply extends model_base implements iqualityapply {
	/**
	 * ˽�г�Ա - ҵ�������
	 */
	private $mainClass = "model_projectmanagent_return_return";
	private $detailClass = "model_projectmanagent_return_returnequ";

	/**
	 * �����ʼ�����ʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$detailDao = new $this->detailClass();
		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $value ) {
				$sql = "update $detailDao->tbl_name set  qualityNum=qualityNum+{$value['qualityNum']} where id={$value['relDocItemId']}";
//				echo $sql;
				$detailDao->query ( $sql );
			}
            //�Ӹ�״̬���µķ��� - �´��ʼ��״̬��Ϊ�ʼ���
			$mainDao = new $this->mainClass();
			$mainDao->updateDisposeState_d($paramArr['relDocId'],1);
		}
	}

	/**
	 * �޸��ʼ�����ʱԴ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE) {

	}

	/**
	 * ͨ���ʼ���÷���
	 * �黹����id
	 * �黹��ϸid
	 * ͨ������
	 */
	function dealRelItemPass( $relDocId,$relDocItemId,$qualityNum ){
//		ʵ����������ϸ,������������
		$detailDao = new $this->detailClass();
		$detailDao->editQualityInfo("",$relDocItemId,$qualityNum);

        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($relDocId,3);//�ʼ����
	}

    /**
     * �𻵷��ε��÷���
     * @param $relDocId
     * @param $relDocItemId
     * @param $qualityNum
     */
    function dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum) {
        // ʵ����������ϸ,������������
        $detailDao = new $this->detailClass();
        $detailDao->editQualityBackInfo("", $relDocItemId, 0, $qualityNum);

        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($relDocId, 3); //�ʼ����
        // �����⳥״̬
        $mainDao->updateState_d($relDocId, 1);
    }

	/**
	 * ����ʼ�����ʱԴ����ҵ����
	 * @param  $paramArr �����ʼ�ϸ���������
	 */
	function dealRelInfoAtConfirm($paramArr = false) {
		$detailDao=new $this->detailClass();
		$detailDao->editQualityInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);

        //�ʼ����
        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],3);
	}

    /**
     * ���֮���˻�ʱ�������ϵ�
     * @param  $paramArr �����ʼ�ϸ���������
     */
    function dealRelInfoAtBack($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityBackInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);

        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],3);//�ʼ����
    }

    /**
     * ���֮���ò�����ʱ�������ϵ�
     * @param  $paramArr �����ʼ�ϸ���������
     * @param  $relItemArr �ʼ챨��������
     */
    function dealRelInfoAtReceive($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityReceiceInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);
        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],3);//�ʼ����
    }

    /**
     * �����ʼ챨��
     */
	function dealRelInfoAtUnconfirm($paramArr = false){
		$detailDao=new $this->detailClass();
        $detailDao->editQualityUnconfirmInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);
        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],1);//�ʼ����
	}

	/**
	 * ��ȡԴ���嵥��Ϣ
	 */
	function getRelDocInfo($id) {
		$mainDao = new $this->mainClass();
		$mainObj = $mainDao->get_d ( $id );
		$conDao = new model_contract_contract_contract();
		$conInfo = $conDao->get_d($mainObj['contractId']);
		$rtObj = array(
			'supplierName' => $conInfo['customerName'],
            'supplierId' => $conInfo['customerId'],
			'applyUserCode' => $mainObj['createId'],
            'applyUserName' => $mainObj['createName']
		);
		return $rtObj;
	}

	/**
	 * ��ȡ�ӱ�����
	 */
	function getRelDetailInfo($id){
		$detailDao = new $this->detailClass();
		$detailDao->searchArr = array('returnId' => $id);
        $detailArr = $detailDao->list_d();

        //��������
        $rtArr = array();
        if(!empty($detailArr)){
            //ʵ��������
            $productInfoDao = new model_stock_productinfo_productinfo();
            //�����ֵ�
            $datadictDao = new model_system_datadict_datadict();
            foreach($detailArr as $val){
            	$canQualityNum = $val['number'] - $val['qualityNum'];
                if($canQualityNum <= 0){
                    continue;
                }
                //�ʼ췽ʽ
                $productInfo = $productInfoDao->get_d($val['productId']);
                array_push($rtArr,array(
                    'productId' => $val['productId'],
                    'productCode' => $val['productCode'],
                    'productName' => $val['productName'],
                    'pattern' => $productInfo['pattern'],
                    'unitName' => $productInfo['unitName'],
                    'qualityNum' => $canQualityNum,
                    'relDocItemId' => $val['id'],
					'serialId' => "",
                    'serialName' => "",
                    'checkType' => $productInfo['checkType'],
                    'checkTypeName' => $datadictDao->getDataNameByCode($productInfo['checkType'])
                ));
            }
        }
		return $rtArr;
	}

	/**
	 * �ж��Ƿ�ɳ���
	 */
	function checkCanBack_d($itemIdArr){
		$itemIds = implode(',',$itemIdArr);
		$detailDao = new $this->detailClass();
		$sql = "select sum(disposeNumber) as disposeNumber from $detailDao->tbl_name where id in ($itemIds) ";
		$rs = $detailDao->_db->getArray($sql);
		//����Ѿ����������򷵻�false
		if($rs[0]['disposeNumber']){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * ������ҵ��״̬���·��� -- ���ʱֱ�Ӵ������,���������ݸ��´���
	 */
	function dealRelInfoCompleted($relDocId){
        return $relDocId;
	}

	/**
	 * �ʼ������ص��÷���
	 * Դ��id
	 * Դ����ϸid
	 * �������
	 */
	function dealRelItemBack( $relDocId,$relDocItemId,$qualityNum ){
		$detailDao = new $this->detailClass();
		return $detailDao->editQualityInfoAtBack("",$relDocItemId,$qualityNum);
	}

	/**
	 * ������ҵ��״̬���·��� -�����ʼ�������
	 */
	function dealRelInfoBack($relDocId){
		try{
			//���µ���״̬
			$mainDao = new $this->mainClass();
			$mainDao->updateBusinessByBack($relDocId);
		}catch(Exception $e){
			throw $e;
		}
	}
}