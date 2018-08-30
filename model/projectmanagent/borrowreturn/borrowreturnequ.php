<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:32
 * @version 1.0
 * @description:�����ù黹���ϴӱ� Model��
 */
class model_projectmanagent_borrowreturn_borrowreturnequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_return_equ";
		$this->sql_map = "projectmanagent/borrowreturn/borrowreturnequSql.php";
		parent :: __construct();
	}

	/**
	 * ��ȡ�����ù黹������ϸ
	 */
	function getDetail_d($returnId){
		$this->searchArr = array('returnId' => $returnId);
		return $this->list_d();
	}

    /**
     * ��ȡ�����ù黹������ϸ
     */
    function getDamagePassDetail_d($returnId){
        $this->searchArr = array('returnId' => $returnId);
        return $this->list_d();
    }

	/**
	 * ����equIdͳ������
	 */
	function getNumByEquId_d($equId){
		$rs = $this->_db->getArray("select sum(number) as number from $this->tbl_name where equId = $equId");
		return $rs[0]['number'] ? $rs[0]['number'] : 0;
	}

	/**
	 * �������ϵ��ʼ�����.
	 * @param  $arrivalId ����֪ͨ��ID
	 * @param  $productId ����Id
	 * @param  $proNum   �ʼ�����
	 */
	function editQualityInfo($arrivalId,$equId,$proNum) {
    	$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum+$proNum) where c.id=$equId";
    	$this->query($sql);
	}

    /**
     * �������ϵ��������� - �����ʼ��˻�
     * @param  $arrivalId   ����֪ͨ��ID
     * @param  $productId   ����Id
     * @param  $proNum   �ʼ�����
     */
    function editQualityBackInfo($arrivalId,$equId,$passNum,$receiveNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $passNum),c.qBackNum=(c.qBackNum+$receiveNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * �������ϵ��ʼ�����. - �����ʼ��ò�����
     * @param  $arrivalId   ����֪ͨ��ID
     * @param  $productId   ����Id
     * @param  $proNum   �ʼ�����
     */
    function editQualityReceiceInfo($arrivalId,$equId,$proNum,$backNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $proNum),c.qBackNum=(c.qBackNum+$backNum) where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * �������ϵ��ʼ�����. - �����ʼ챨�泷��
     * @param  $arrivalId   ����֪ͨ��ID
     * @param  $productId   ����Id
     * @param  $proNum   �ʼ�����
     */
    function editQualityUnconfirmInfo($arrivalId,$equId,$proNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum-$proNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * �����´�����
     */
    function editDisposeNumber($equId,$disposeNum){
		$sql = "update $this->tbl_name set disposeNumber = disposeNumber+$disposeNum where id = '$equId'";
        $this->query($sql);
    }

    /**
     * �����´��������
     */
    function editOutNumber($equId,$outNum){
		$sql = "update $this->tbl_name set outNum = outNum+$outNum where id = '$equId'";
        $this->query($sql);
    }

    /**
     * �����´�����
     */
    function editCompensateNumber($equId,$num){
		$sql = "update $this->tbl_name set compensateNum = compensateNum+$num where id = '$equId'";
        $this->query($sql);
    }

    /**
     * ���ݹ���
     */
    function filterArr_d($rows,$applyType){
		if(!empty($rows)){
			//��ʼֵ�趨
            if($applyType == 'JYGHSQLX-01'){
                foreach($rows as &$val){
                    //��ʼ���黹���кŵ�ֵ
                    $failureSerailNums = $failureSerailIds = '';

                    $val['borrowequId'] = $val['equId'];
                    $val['disposeNum'] = $val['qPassNum'] + $val['qBackNum'] - $val['disposeNumber'];
                    //������ڲ��ϸ��������������´��������
                    $outNum = 0;
                    if(!empty($val['qBackNum'])){
                        if(!isset($returnDisEquDao)){
                            $returnDisEquDao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
                        }
                        //��ѯ�´���������
                        $outNum = $returnDisEquDao->getOutNum_d($val['equId']);

                        //���豸�������к�ʱ
                        if($val['serialName']){
                            //���в��ϸ�������ʱ���ȡ���ϸ����к�
                            if(!isset($failureItemDao)){
                                $failureItemDao = new model_produce_quality_failureitem();
                            }
                            $failureSerailNums = $failureItemDao->getSerailNums_d($val['id']);
                            //����������кţ�ƥ�����Ӧid
                            if($failureSerailNums){
                                $serialNameArr = array_flip(explode(',',$val['serialName']));// ���кŵķ�ת������ƥ��id���
                                $serialIdArr = explode(',',$val['serialId']);
                                $serialIdOutArr = array();
                                $failureSerailNumArrs = explode(',',$failureSerailNums);
                                foreach($failureSerailNumArrs as $v){
                                    if($serialNameArr[$v] !== false){
                                        array_push($serialIdOutArr,$serialIdArr[$serialNameArr[$v]]);
                                    }
                                }
                                //id����ת��string
                                $failureSerailIds = implode(',',$serialIdOutArr);
                            }
                        }
                    }
                    $val['outNum'] = $val['qBackNum'] - $outNum;
                    $val['outNum'] = $val['outNum'] > $val['disposeNum'] ? $val['disposeNum'] : $val['outNum'];
                    $val['serialOutName'] = $failureSerailNums;
                    $val['serialOutId'] = $failureSerailIds;
                }
            }else{
                foreach($rows as &$val){
                    $val['outNum'] = $val['number'];
                    $val['disposeNum'] = $val['number'];
                    $val['serialOutName'] = $val['serialName'];
                    $val['serialOutId'] = $val['serialId'];
                }
            }
		}
		return $rows;
    }

    /**
     * ���ݹ���
     */
    function filterArrCompensate_d($rows,$applyType){
		if(!empty($rows)){
			//��ʼֵ�趨
			foreach($rows as &$val){
				$val['borrowequId'] = $val['equId'];
				if($applyType == 'JYGHSQLX-01'){
					$val['disposeNum'] = $val['qPassNum'] + $val['qBackNum'] - $val['disposeNumber'];
					//������ڲ��ϸ��������������´��������
					if(!empty($val['qBackNum'])){
						if(!isset($compesateDetailDao)){
							$compesateDetailDao = new model_finance_compensate_compensatedetail();
						}
						//��ѯ�´���������
						$compensateNum = $compesateDetailDao->getCompensateNum_d($val['returnequId']);
					}
					$val['number'] = $val['qBackNum'] - $compensateNum;
					$val['allowNum'] = $val['number'];
				}else{
					$val['allowNum'] = $val['number'];
				}
			}
		}
		return $rows;
    }
    
    /**
     * �������ϵ��ʼ�����. - �����ʼ�������
     * @param  $mainId	����֪ͨ��ID
     * @param  $equId	��ϸId
     * @param  $proNum	�ʼ�����
     */
    function editQualityInfoAtBack($mainId, $equId, $proNum) {
    	$sql = "update $this->tbl_name c set c.qualityNum=(c.qualityNum-$proNum) where c.id=$equId";
    	$this->query($sql);
    }
}