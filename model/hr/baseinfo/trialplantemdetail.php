<?php
/**
 * @author Show
 * @Date 2012��8��30�� ������ 14:38:15
 * @version 1.0
 * @description:Ա�����üƻ�ģ����ϸ Model��
 */
 class model_hr_baseinfo_trialplantemdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_trialplantem_detail";
		$this->sql_map = "hr/baseinfo/trialplantemdetailSql.php";
		parent::__construct ();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'taskType'
    );

    /**
     * �����������ͷ��عرշ�ʽ
     * 0 Ϊ��Ҫ���
     * 1 Ϊ�������
     */
    function rtCloseType_c($thisVal){
		switch($thisVal){
			case 'HRSYRW-01' : return 1;break;
			case 'HRSYRW-02' : return 0;break;
			case 'HRSYRW-03' : return 0;break;
			case 'HRSYRW-04' : return 0;break;
			case 'HRSYRW-05' : return 0;break;
			default : return 0;
		}
    }

	/***************** ��ɾ�Ĳ� ***************/
	//��дadd_d
	function add_d($object){
		$object = $this->processDatadict($object);

		return parent::add_d($object);
	}

	//��дedit_d
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object);
	}

	/**************** ҵ���� ******************/
	/**
	 * ѭ����ֵ��Ҫ�������Ϣ
	 */
	function batchDeal_d($object,$addObj = array()){
		if($object){
			foreach($object as $key => $val){
				//���ݼ���
				$object[$key] = array_merge($object[$key],$addObj);

				//�رչ�������
//				$object[$key]['closeType'] = $this->rtCloseType_c($object[$key]['taskType']);
			}
		}
		return $object;
	}
}
?>