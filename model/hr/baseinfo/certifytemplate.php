<?php
/**
 * @author Show
 * @Date 2012��8��20�� ����һ 20:19:03
 * @version 1.0
 * @description:��ְ�ʸ�ȼ���֤���۱�ģ�� Model��
 */
class model_hr_baseinfo_certifytemplate extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_baseinfo_certifytemplate";
		$this->sql_map = "hr/baseinfo/certifytemplateSql.php";
		parent :: __construct();
	}

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'careerDirection','baseLevel','baseGrade'
    );

    //״̬����
    function rtStatus_d($status){
		if($status == 1){
			return '����';
		}else{
			return '�ر�';
		}
    }

	/**************** ��ɾ�Ĳ� ************************/
	/**
	 * ��дadd_d
	 */
	function add_d($object){
//		echo "<pre>";print_r($object);die();
        //��ȡ��ΪҪ��
        $certifytemplatedetail = $object['certifytemplatedetail'];
        unset($object['certifytemplatedetail']);

		try {
			$this->start_d ();
			//�����ֵ����Ĵ���
			$object = $this->processDatadict($object);

			//��������
			$newId = parent::add_d ( $object, true );

            //���������Ա
            $certifytemplatedetailDao = new model_hr_baseinfo_certifytemplatedetail();
            $certifytemplatedetailDao->createBatch($certifytemplatedetail,array('modelId' => $newId));

			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * ��дedit_d
	 */
	function edit_d($object){
        //��ȡ��ΪҪ��
        $certifytemplatedetail = $object['certifytemplatedetail'];
        unset($object['certifytemplatedetail']);

		try {
			$this->start_d ();
			//�����ֵ����Ĵ���
			$object = $this->processDatadict($object);

			//��������
			parent::edit_d ( $object, true );

            //���������Ա
            $certifytemplatedetailDao = new model_hr_baseinfo_certifytemplatedetail();
            $certifytemplatedetailDao->saveDelBatch($certifytemplatedetail);

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/********************** ҵ���߼� ***********************/
	/**
	 * �ж��Ƿ������һ��ģ��
	 */
	function isAnotherTemplate_d($careerDirection,$baseLevel,$baseGrade,$id = null){
		//��ѯ����ƴװ
		$this->searchArr = array(
			'careerDirection' => $careerDirection,
			'baseLevel' => $baseLevel,
			'baseGrade' => $baseGrade
		);
		if($id){
			$this->searchArr['noId'] = $id;
		}
		$rs = $this->list_d();
		if($rs){
			return $rs[0]['id'];
		}else{
			return false;
		}
	}

	/**
	 * �ر���Ŀ
	 */
	function close_d($id){
		$object['id'] = $id;
		$object['status'] = 0;

		return parent::edit_d($object,true);
	}
}
?>