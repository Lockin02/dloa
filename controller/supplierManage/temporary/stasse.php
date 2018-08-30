<?php
/**
 * @description: ��Ӧ����ʱ������
 * @date 2010-11-9 ����02:51:01
 */
class controller_supplierManage_temporary_stasse extends controller_base_action{
   /**
   * @desription ���캯��
   * @param tags
   * @date 2010-11-9 ����08:53:05
   */
  function __construct() {
    $this->objName = "stasse";
    $this->objPath = "supplierManage_temporary";
    parent::__construct();
  }

/*******************************��ͨAction����**************************************/
  /*
   * @desription ��ת������Ӧ������
   * @param tags
   * @date 2010-11-8 ����02:18:04
   */
  function c_stsToAdd () {
    $parentId = isset( $_GET['parentId'] )?$_GET['parentId']:exit;
    $parentCode = isset( $_GET['parentCode'] )?$_GET['parentCode']:exit;
    $this->show->assign("parentId",$parentId);
    $this->show->assign("parentCode",$parentCode);
    $datadictDao = new model_system_datadict_datadict ();
    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
    $str = $this->service->add_s($datadictArr['lskpg']);
    $this->show->assign("str",$str);
    $this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
  }

/************************************ �����Ǳ༭��Ӧ������*********************************************/

  	/*
	 * @desription �༭���Ĺ�Ӧ��Ʒ������ת��ע�����һ��--��ӹ�Ӧ������
	 * @param tags
	 * @date 2010-11-25 ����10:35:35
	 */
	function c_ededit () {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$id = $this->service->edit_d( $_POST['stproduct'],true );
		if($id){
//			succ_show('?model=supplierManage_temporary_stasse&action=stsToAdd&parentId='. $parentId);
			$this->show->assign("parentId",$parentId);
		    $datadictDao = new model_system_datadict_datadict ();
		    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
		    $str = $this->service->add_s($datadictArr['lskpg']);
		    $this->show->assign("str",$str);
			$this->show->display ( $this->objPath . '_' . 'stasse-add' );

		}
	}

	/*
	 * @desription �༭��Ӧ�̳�������
	 * @param tags
	 * @date 2010-11-24 ����02:19:38
	 */
	function c_toEditAsse () {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//��ȫУ��
		//ͨ��parentId��ȡ��ص���Ϣ
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$service = $this->service;
		$service->asc = false;
		$service->searchArr = array( 'parentId'=>$parentId );
		$assArr = $service->stsList_d();
//		echo "<pre>";
//		print_r($assArr);
		$datadictDao = new model_system_datadict_datadict ();
	    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );

	    if($assArr){
//	    	echo "*****";
	    	$str = $this->service->addAsse_s($assArr);

	    }else if($datadictArr){
//	    	echo "#########";
	    	$str = $this->service->add_s($datadictArr['lskpg']);
	    }

	    //ģ���滻
	    //�ֱ��滻�ϰ벿�ֵ�����ѡ����°벿�ֵ��������
	    $this->show->assign("str",$str);
	    $this->show->assign('opinion',$assArr[0]['opinion']);

		$this->show->assign('parentId',$parentId);
		$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}


	/*
	 * @desription ��ע����ѡ��Ӧ�̽���༭tabҳ�����б༭���۵ı��淽��
	 * @param tags
	 * @date 2010-11-26 ����10:11:57
	 */
	function c_editAsse () {
		$service = $this->service;
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;

		for($i = 0;$i<3;$i++){
			$_POST['typeCode'][$i]['opinion'] = $_POST['stasse']['opinion'];
		}

		//���ݻ�ȡ�õ���parentId�ж����ݱ��Ƿ�����������
		//����bindBy������ֻҪ��������һ������Ϊtrue
		//�еĻ��������б༭��û��������
		$isExistAsse = $service->findBy(parentId,$parentId);
		if($isExistAsse){
			foreach($_POST['typeCode'] as $key=>$val){
				$id = $this->service->edit_d( $val,true );
			}
		}else{
			//��POST��������ת�浽����һ�����飬�����������
			$arrTemp = array(
				'hpzl' => $_POST['typeCode']['hpzl'],
				'fhsd' => $_POST['typeCode']['fhsd'],
				'fwtd' => $_POST['typeCode']['fwtd'],
				'opinion' => $_POST['typeCode'][0]['opinion']
			);
			//�������$i�ǹ�������ѡ��Ŀ��Ƶġ�
			//��������ѡ���������ȷ��ѭ���Ĵ�����
			//������ط�����������ѡ�����i�Ǵ�0��2����3��
			$i = 0;
			foreach($arrTemp as $key => $val){
				$arr[$i] = array(
					"objCode"=>generatorSerial(),
					"systemCode" => generatorSerial(),
					"parentId"=> $parentId,
					"typeCode"=> $val,
					"typeName"=> $key,
					"opinion"=>$_POST['stasse']['opinion']
				);
				$i++;
			}
			for($i = 0;$i<3;$i++){
				$id = $this->service->add_d($arr[$i],true);
			}
		}

		if($id){
			msgGo("�༭�ɹ�","?model=supplierManage_temporary_stasse&action=toEditAsse&parentId=".$parentId);
//			msg();
		}

	}


/************************************ �����Ǳ༭��Ӧ������*********************************************/


 	/*
	 * @description ��ʱ����ת����ʽ��--¼����ʽ��
	 * @2010-11-19 ����21:34:40
	 */
	function c_toFormal () {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
    	$parentCode = isset( $_GET['parentCode'] )?$_GET['parentCode']:null;

    	$this->show->assign('parentId',$parentId);
    	$this->show->assign('parentCode',$parentCode);

		$this->show->display($this->objPath .'_' . 'temptoformal');
	}

  /*
   * @description ע�ṩӦ����Ϣ����
   * @date 2010-9-20 ����02:06:22
   */
  function c_stsAdd() {
    $productNames = explode("," ,substr( $_POST['stasse']['productNames'],0,-1) );
    $productIds = explode("," ,substr( $_POST['stasse']['productIds'],1,-1 ) );
    $parentId = isset($_POST[$this->objName]['parentId'])?$_POST[$this->objName]['parentId']:null;
    $parentCode = isset($_POST[$this->objName]['parentCode'])?$_POST[$this->objName]['parentCode']:null ;

    $this->show->assign('parentId',$parentId);
    $this->show->assign('parentCode',$parentCode);

	$this->show->assign('productNames',$productNames);
	$this->show->assign('productIds',$productIds);

	foreach($_POST['typeCode'] as $key => $val){
		$arr = array(
			"objCode"=>generatorSerial(),
			"systemCode" => generatorSerial(),
			"parentCode" => $parentCode,
			"parentId"=> $parentId,
			"typeCode"=> $val,
			"typeName"=> $key,
			"opinion"=>$_POST['opinion']
		);
	$id = $this->service->add_d($arr,true);
	}


        //�ж��ǡ����桯���ǡ��ύ������
		  if($id && $_GET['act'] == 'save'){
		       msgGo( '����ɹ���','?model=supplierManage_temporary_temporary&action=myloglist');
		  }else if($id){
		       succ_show('controller/supplierManage/temporary/ewf_index.php?actTo=ewfSelect&billId=' . $parentId . '&examCode=oa_supp_lib_temp&formName=��Ӧ�����');
		  }else{
		       msgGo( '���ʧ�ܣ�','?model=supplierManage_temporary_temporary&action=myloglist' );
		  }
  }


/*
 * @desription �鿴��Ӧ�̳�������
 * @param tags
 * @date 2010-11-22 ����09:35:50
 */
	function c_toViewAsse () {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//��ȫУ��
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$searchTemp = array('parentId'=>$parentId);
		$asseInfoArr = $this->service->findAll($searchTemp);
		$this->show->assign('list',$this->service->showAsseList($asseInfoArr));
		$this->show->display($this->objPath . '_' . $this->objName . '-view');
	}




}
?>
