<?php
/**
 * @author Administrator
 * @Date 2012��11��8�� 11:04:46
 * @version 1.0
 * @description:�ջ�֪ͨ�����嵥 Model��
 */
class model_stock_withdraw_equ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_withdraw_equ";
		$this->sql_map = "stock/withdraw/equSql.php";
		parent :: __construct();
	}

	/**
	 * �������ϵ��ʼ�����.
	 * @param  $mainId ����֪ͨ��ID
	 * @param  $productId ����Id
	 * @param  $proNum   �ʼ�����
	 */
	function editQualityInfo($mainId, $equId, $proNum) {
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum+$proNum) where c.id=$equId";
		$this->query($sql);
	}

	/**
	 * �������ϵ��������� - �����ʼ��˻�
	 * @param  $mainId   ����֪ͨ��ID
	 * @param  $productId   ����Id
	 * @param  $proNum   �ʼ�����
	 */
	function editQualityBackInfo($mainId, $equId,$passNum,$receiveNum) {
		$completionTime = date('Y-m-d H:i:s'); //�ʼ����ʱ��
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $passNum),c.qBackNum=(c.qBackNum+$receiveNum)where  c.id=$equId";
		$this->query($sql);
	}

	/**
	 * �������ϵ��ʼ�����. - �����ʼ��ò�����
	 * @param  $mainId   ����֪ͨ��ID
	 * @param  $productId   ����Id
	 * @param  $proNum   �ʼ�����
	 */
	function editQualityReceiceInfo($mainId, $equId, $proNum, $backNum) {
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $proNum),c.qBackNum=(c.qBackNum+$backNum) where  c.id=$equId";
		$this->query($sql);
	}

	/**
	 * �������ϵ��ʼ�����. - �����ʼ챨�泷��
	 * @param  $mainId   ����֪ͨ��ID
	 * @param  $productId   ����Id
	 * @param  $proNum   �ʼ�����
	 */
	function editQualityUnconfirmInfo($mainId, $equId, $proNum) {
		$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum-$proNum) where c.id=$equId";
		$this->query($sql);
	}

	/**
	 * �����´�����
	 */
	function updateExecutedInfo($equId,$proNum){
		$sql = "update $this->tbl_name c set c.executedNum=(c.executedNum+$proNum) where c.id=$equId";
		return $this->query($sql);
	}

    /**
     * �����´�����
     */
    function editCompensateNumber($equId,$num){
        $sql = "update $this->tbl_name set compensateNum = compensateNum+$num where id = '$equId'";
        $this->query($sql);
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