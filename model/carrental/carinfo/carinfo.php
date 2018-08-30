<?php
/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 9:50:27
 * @version 1.0
 * @description:������Ϣ(oa_carrental_carinfo) Model�� ����״̬ status
                                                  0 ��Ч
                                                  1 ʧЧ
 */
 class model_carrental_carinfo_carinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_carinfo";
		$this->sql_map = "carrental/carinfo/carinfoSql.php";
		parent::__construct ();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'carType'
    );

    //�Ƿ�
    function rtYesOrNo($val){
    	if($val == 1){
			return '��';
    	}else{
    		return '��';
    	}
    }

	/********************** �ⲿ��Ϣ��ȡ ***********************/

	/**
	 * ��ȡ�⳵��λ��Ϣ
	 */
	function getUnitsItems_d($id) {
		$unitsDao = new model_carrental_units_units ();
		$units = $unitsDao->get_d ( $id );
		return $units;
	}


	/*********************** ��ɾ�Ĳ� **************************/
	/**
	 * ��������
	 */
	function add_d($object){
		$object = $this->processDatadict($object);
		return parent::add_d($object,true);
	}

	/**
	 * ���²��Կ��ۼƽ��
	 */
	function updateUseDays_d($id,$useDays){
		try{
			$object = array(
				'id' => $id,
				'useDays' => $useDays
			);
			$this->edit_d($object,true);

			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}
}
?>