<?php
//����ӿ�
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';
/**
 * �����ù黹�ʼ�����
 * @author kuangzw
 */
class model_produce_quality_strategy_exchangequalityapply extends model_base implements iqualityapply {
	/**
	 * ˽�г�Ա - ҵ�������
	 */
	private $mainClass = "model_stock_withdraw_withdraw";
	private $detailClass = "model_stock_withdraw_equ";

	/**
	 * �����ʼ�����ʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$detailDao = new $this->detailClass();
		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update $detailDao->tbl_name set  qualityNum=qualityNum+{$value['qualityNum']} where id={$value['relDocItemId']}";
//				echo $sql;
				$detailDao->query ( $sql );
			}
            //�Ӹ�״̬���µķ��� - �Ƿ��Ѿ���ȫ�´��ʼ죬�������״̬Ϊ�ʼ����
			$mainDao = new $this->mainClass();
			$mainDao->updateBusinessByNotice($paramArr['relDocId']);
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
		return $detailDao->editQualityInfo("",$relDocItemId,$qualityNum);
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
    }

	/**
	 * ����ʼ�����ʱԴ����ҵ����
	 * @param  $paramArr �����ʼ�ϸ���������
	 */
	function dealRelInfoAtConfirm($paramArr = false) {
		$detailDao=new $this->detailClass();
		$detailDao->editQualityInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);
	}

    /**
     * ���֮���˻�ʱ�������ϵ�
     * @param  $paramArr �����ʼ�ϸ���������
     */
    function dealRelInfoAtBack($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityBackInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);
        $mainDao = new $this->mainClass();
        $mainDao->updateDocStatus_d($paramArr['relDocId'],1);
    }

    /**
     * ���֮���ò�����ʱ�������ϵ�
     * @param  $paramArr �����ʼ�ϸ���������
     */
    function dealRelInfoAtReceive($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityReceiceInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);
        //�����⳥״̬
        if($paramArr['backNum']){
            $mainDao = new $this->mainClass();
            $mainDao->updateDocStatus_d($paramArr['relDocId'],1);
        }
    }

    /**
     * �����ʼ챨��
     */
	function dealRelInfoAtUnconfirm($paramArr = false){
		$detailDao=new $this->detailClass();
        $detailDao->editQualityUnconfirmInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);
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
            'applyUserCode' => $mainObj['docApplicantId'],
            'applyUserName' => $mainObj['docApplicant']
		);
		return $rtObj;
	}

	/**
	 * ��ȡ�ӱ�����
	 */
	function getRelDetailInfo($id){
		$detailDao = new $this->detailClass();
		$detailDao->searchArr = array('mainId' => $id);
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
                    'pattern' => $val['productModel'],
                    'unitName' => $productInfo['unitName'],
                    'qualityNum' => $canQualityNum,
                    'relDocItemId' => $val['id'],
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
		$sql = "SELECT
				ifnull(sum(sp.number), 0) AS spNum
			FROM
				oa_stock_withdraw_equ op
			LEFT JOIN oa_stock_innotice_equ sp ON (op.id = sp.planEquId)
			WHERE
				op.id in ($itemIds) ";
		$rs = $detailDao->_db->getArray($sql);
		//����Ѿ����������򷵻�false
		if($rs[0]['spNum']){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * ������ҵ��״̬���·���
	 */
	function dealRelInfoCompleted($relDocId){
		try{
			//���µ���״̬
			$mainDao = new $this->mainClass();
			$mainDao->updateBusinessByNotice($relDocId);
		}catch(Exception $e){
			throw $e;
		}
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