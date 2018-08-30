<?php
/**
 * @author by liangjj
 * @Date 2014-06-11 13:34:07
 * @version 1.0
 * @description:������Ŀ C��
 */
class controller_projectmanagent_trialproject_trialprojectEqu extends controller_base_action {

	function __construct() {
		$this->objName = "trialprojectEqu";
		$this->objPath = "projectmanagent_trialproject";
		parent::__construct ();
	 }

	/*
	 * ��ת��������Ŀ�б�
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * ������Ŀ===�̻�tabl
     */
    function c_listChance(){
       $this->assign('chanceId',$_GET['chanceId']);
   	   $this->view("listchance");
    }
    /**
     * �鿴Tab ҳ
     */
    function c_viewTab(){
    	$this->assign("id",$_GET ['id']);
    	$this->view("viewTab");
    }
    /**
     * ������ �ύ����ҳ��
     */
    function c_salesAddlist(){
    	$this->view('salesAddlist');
    }
    /**
     * �ύ����
     */
    function c_subConproject(){
    	try {
			$this->service->subConproject_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
    }
    /**
     * ��ص���ҳ��
     */
    function c_toBackBill(){
    	$this->assign('id',$_GET['id']);
    	$this->assign('applyName',$_GET['applyName']);
    	$this->assign('serCon',$_GET['serCon']);
    	$this->view('backBill');
    }
    /**
     * ������Ϣ����
     */
    function c_sendMail(){
    	$obj = $_POST['mails'];
    	$arr = array('id'=>$obj['id'],'trialprojectMessage'=>$obj['content']);
    	$this->service->edit_d($arr);
//     	$obj['userName'] = $_SESSION ['USERNAME'];
//     	$obj['userId'] = $_SESSION ['USER_ID'];
//     	$obj['createTime'] = date ( "Y-m-d H:i:s" );
//     	$dataArr = $this->service->get_d($obj['id']);
//     	$itemNum=$dataArr['projectCode'];
//     	$content = $obj['content'];
		if($obj['serCon']==3){
    		$decide = $this->c_billBack($obj['id']);
		}
		else{
			$decide = $this->c_backConproject($obj['id']);
		}
		if($decide){
			//�����ʼ�
			$this->service->mailDeal_d('billBack',$obj['TO_ID'],array('id' =>$obj['id'] ));
// 			$emailDao = new model_common_mail();
// 			$msg = "<span style='color:blue'>��Ŀ���</span> ��$itemNum<br/><span style='color:blue'>���ݴ����Ϣ</span> �� $content";
// 			$emailInfo = $emailDao->backBillEmail(1,$obj['userName'],$_SESSION['EMAIL'],'billBack',null,null,$obj['TO_ID'],$msg);
			msg("�����Ѵ�أ��ȴ������������ύ");
		}
		else{
			msg("��ص���ʧ��");
		}

    }
    /**
     * �������뵥�ݴ��
     */
    function c_billBack($id){
    	try {
    		$this->service->backDelay_d ($id);
    		return 1;
    	} catch ( Exception $e ) {
    		return 0;
    	}
    }
    /**
     * ���
     */
    function c_backConproject($id){
    	try {
			$this->service->backConproject_d ($id);
			return 1;
		} catch ( Exception $e ) {
			return 0;
		}
    }
     /**
     * ���۾��� �鿴�б�
     */
    function c_salesList(){
    	$this->view('salesList');
    }

   /**
	 * ��ת������������Ŀҳ��
	 */
	function c_toAdd() {
	   $this->assign("applyName",$_SESSION['USERNAME']);
	   $this->assign("applyNameId",$_SESSION['USER_ID']);

	   $chanceId = isset($_GET['id']) ? $_GET['id'] : null;
		if($chanceId){
			$this->permCheck($chanceId,'projectmanagent_chance_chance');
          $chanceDao = new model_projectmanagent_chance_chance();
		  $rows = $chanceDao->get_d($chanceId);
		  //����license����
		  $licenseDao = new model_yxlicense_license_tempKey();
		  $rows = $licenseDao->copyLicense($rows);

		  foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
		  //��������Ҳ Դ������
		  $chanceType = "chance";
		}
		$this->assign('budgetMoney',$rows['chanceMoney']);
		$this->assign('planSignDate',$rows['predictContractDate']);
		$this->assign('chanceId' ,$chanceId );
        $this->assign('customerName' , isset($rows['customerName']) ? $rows['customerName'] : null );
        $this->assign('customerId' , isset($rows['customerId']) ? $rows['customerId'] : null );
        $this->assign('SingleType' , isset($chanceType) ? $chanceType : null );
        /*************�̻����ƽ�����������Ϣ*******************/
        $this->assign('chanceCode' , isset($rows['chanceCode']) ? $rows['chanceCode'] : null );
        $this->assign('chanceId' , isset($rows['id']) ? $rows['id'] : null );
        /***************************************************/
       $this->view ( 'add' );
   }

   /**
	 * ��ת���༭������Ŀҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   function c_serConedit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//����
			$this->assign("file",$this->service->getFilesByObjId ( $obj ['id'], true ));
			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $obj['customerType']);
		   $this->view ( 'serConedit');
   }


   /**
	 * ��ת���鿴������Ŀҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

   /**
	 * �����������
	 */
	function c_add($isAddInfo = true) {
		$arr =  $_POST [$this->objName];
//		echo "<pre>";
//		print_r($arr);
//		die();
		$id = $this->service->add_d ($arr , $isAddInfo );
//		$msg = $_POST ["msg"] ? $_POST ["msg"] : '���ӳɹ���';

        //��ȡ������չ�ֶ�ֵ
		$regionDao = new model_system_region_region();
		$expand = $regionDao->getExpandbyId($arr['areaCode']);
		if ($id && $_GET['act'] == "app") {
			if($expand == '1'){
				$sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
			    $this->service->_db->query($sql);
				succ_show('controller/projectmanagent/trialproject/ewf_index1.php?actTo=ewfSelect&billId=' . $id);
				 msg ( "��Ŀ���ύ����" );
			}else{
				$this->service->subConproject_d ( $id );
                msg ( "��Ŀ���ύ����һ�������ȷ�ϡ�" );
			}
		}else{
			msg ( "��Ŀ�ѱ��棬�ȴ��ύ" );
		}

		//$this->listDataDict();
	}
	//ajax �޸��ύ״̬
	function c_ajaxUpdateSer() {
		try {
			$id =  $_POST ['id'];
			$sql = "update oa_trialproject_trialproject set serCon=1 where id = $id ";
			    $this->service->_db->query($sql);
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$SingleType = $obj['SingleType'];
				switch($SingleType){
					case "" :
					    $this->assign('SingleType',"��");
					    $this->assign('singleCode',"��");
					    break;
					case "chance" :
					    $this->assign('SingleType',"�̻�");
					    $chacneId = $obj['chanceId'];
					    $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='.$chacneId.'&perm=view\')">'.$obj['chanceCode'].'</span>';
					    $this->assign('singleCode',$code);
					    break;
					case "order" :
					    $this->assign('SingleType',"��ͬ");
					    $orderId = $obj['contractId'];
					    $orderCode = $obj['contractNum'];
					    $code = '<span class="red" title="����鿴Դ��" onclick="showOpenWin(\'?model=contract_contract_contract&action=toViewTab&id='.$orderId.'&perm=view\')">'.$orderCode.'</span>';

					    $this->assign('singleCode',$code);
					    break;
				}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			//����
			$this->assign("file",$this->service->getFilesByObjId ( $obj ['id'], false ));
			$this->view ( 'view' );
		} else {
			//����
			$this->assign("file",$this->service->getFilesByObjId ( $obj ['id'], true ));
			$this->showDatadicts(array ( 'customerType' => 'KHLX' ), $obj['customerType']);
			$this->view ( 'edit' );
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //��ȫУ��
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			if ($_GET['act'] == "app") {
			  succ_show('controller/projectmanagent/trialproject/ewf_index1.php?actTo=ewfSelect&billId=' . $object['id'].'&flowMoney='.$object['affirmMoney']);
               msg ( '�ύ�ɹ�,��ȴ�ȷ�ϣ�' );
			}else{
			   msg ( '�༭�ɹ������ύȷ��' );
			}
		}
	}

	/**
	 * �ҵ���������
	 */
	function c_mytrialproject(){
		$this->assign("userId" , $_SESSION['USER_ID']);
		$this->view("mytrialproject");
	}


	 /**
     * ����ͨ����������
     */
     function c_confirmExa(){
        if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			$objId = $folowInfo ['objId'];
			if (! empty ( $objId )) {
				$rows= $this->service->get_d ( $objId );
				if ($rows ['ExaStatus'] == "���"){
                       //��ȡĬ�Ϸ�����
						include (WEB_TOR . "model/common/mailConfig.php");
						$toMailId = $mailUser['trialproject']['sendUserId']; //�ʼ�������ID
						$emailDao = new model_common_mail();
						$emailInfo = $emailDao->trialprojectEmail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "trialproject", $rows['projectCode'], $toMailId,"��������");
                 }else{
                 	$sql = "update oa_trialproject_trialproject set serCon=2 where id = $objId";
                 	$this->service->query($sql);
                 }
				}
			}
       echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
     }

    /**
     * AJAX��ȡ������Ϣ
     */
    function c_ajaxGetInfo(){
        $id = $_POST['id'];
        $obj = $this->service->get_d($id);
        echo util_jsonUtil::encode($obj);
        exit();
    }

    /**
   * �б�����
   */
  function c_exportExcel(){
  	  //�ύ״̬
  	  $serConArr = array(
  	      '0' => 'δ�ύ',
  	      '1' => '���ύ',
  	      '2' => '���',
  	      '3' => '��������',
  	      '4' => '����������'

  	  );
  	  //��Ŀ״̬
  	  $statusArr = array(
  	      '0' => 'δ�ύ',
  	      '1' => '������',
  	      '2' => '��ִ��',
  	      '3' => 'ִ����',
  	      '4' => '�����',
  	      '5' => '�ѹر�'
  	  );
  	  //�Ƿ���Ч
  	  $isUseArr = array(
  	      '0' => '��Ч',
  	      '1' => '��ת��ͬ',
  	      '2' => '�ֹ��ر�'
  	  );

//  	    $useStatusArr = array(
//			'0'=>'�ر�',
//			'1'=>'����'
//		);

		$service = $this->service;
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		$rows = array ();
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		$searchConditionKey = $_GET['searchConditionKey']; //��ͨ������Key
		$searchConditionVal = $_GET['searchConditionVal']; //��ͨ������Val
		$searchArr[$searchConditionKey] = $searchConditionVal;

		if( isset($_SESSION['advSql']) ){
			$_REQUEST['advSql'] = $_SESSION['advSql'];
		}

		$service->getParam($_REQUEST);
//		//��¼��
//		$appId = $_SESSION['USER_ID'];
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		//������Ȩ������
		if(!empty($this->service->searchArr)){
          	  $this->service->searchArr = array_merge($this->service->searchArr,$searchArr);
          }else{
              $this->service->searchArr = $searchArr;
          }

			//			$rows = $service->page_d();

		$rows = $service->listBySqlId ( 'select_default' );
		foreach($rows as $k => $v){
           //����������Ŀid ��ȡ��ͬ��Ϣ
           $conArr = $this->service->getContractBytrId($v['id']);
           $rows[$k]['contractId'] = $conArr[0]['id'];
           $rows[$k]['contractCode'] = $conArr[0]['contractCode'];;
		}
		$arr = array ();
		$arr['collection'] = $rows;
		foreach ($rows as $index => $row) {
			foreach ($row as $key => $val) {
				if ($key == 'serCon') {
						$rows[$index][$key] = $serConArr[$val];
				} else if ( $key == 'status' ){
					$rows[$index][$key] = $statusArr[$val];
				} else if ( $key == 'isFail'){
					$rows[$index][$key] = $isUseArr[$val];
				}
			}
		}
//		echo "<pre>";
//		print_R($rows);
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
//		echo "<pre>";
//		print_R($dataArr);
//		die();
		return model_projectmanagent_trialproject_common_ExcelUtil :: export2ExcelUtil($colArr, $dataArr);
	}

		/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonCombogrid() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$service->searchArr['isFail'] = '0';
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_mypagejson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ();
		foreach($rows as $k => $v){
               //����������Ŀid ��ȡ��ͬ��Ϣ
               $conArr = $this->service->getContractBytrId($v['id']);
               $rows[$k]['contractId'] = $conArr[0]['id'];
               $rows[$k]['contractCode'] = $conArr[0]['contractCode'];;
		}

		foreach($rows as $key => $val){
            //������չֵ
            //��ȡ������չ�ֶ�ֵ
			$regionDao = new model_system_region_region();
			$expand = $regionDao->getExpandbyId($val['areaCode']);
			$rows[$key]['expand'] = $expand;

		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_trialprojectPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


        //������Ȩ������
		$limit = $this->initLimit();
		if ($limit == true) {
			$rows = $service->page_d ();
			foreach($rows as $k => $v){
               //����������Ŀid ��ȡ��ͬ��Ϣ
               $conArr = $this->service->getContractBytrId($v['id']);
               $rows[$k]['contractId'] = $conArr[0]['id'];
               $rows[$k]['contractCode'] = $conArr[0]['contractCode'];;
			}
		}
		//$service->asc = false;
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}


	/**
	 * Ȩ������
	 * Ȩ�޷��ؽ������:
	 * �������Ȩ�ޣ�����true
	 * �����Ȩ��,����false
	 */
	function initLimit() {
		$service = $this->service;
		//Ȩ����������
		$limitConfigArr = array (
			'areaLimit' => 'c.areaCode'
		);
		//Ȩ������
		$limitArr = array ();
		//Ȩ��ϵͳ
		if (isset ($this->service->this_limit['��������']) && !empty ($this->service->this_limit['��������']))
			$limitArr['areaLimit'] = $this->service->this_limit['��������'];
		if (strstr($limitArr['areaLimit'], ';;')) {
			return true;
		} else {
			//�������˻�ȡ�������
			$regionDao = new model_system_region_region();
			$areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
			if (!empty ($areaPri)) {
				//����Ȩ�޺ϲ�
				$limitArr['areaLimit'] = implode(array_filter(array (
					$limitArr['areaLimit'],
					$areaPri
				)), ',');
			}
//						print_r($limitArr);
			if (empty ($limitArr)) {
				return false;
			}else{
				$i = 0;
				$sqlStr = "sql:and ( ";
				$k = 0;
				foreach ($limitArr as $key => $val) {
					$arr = explode(',', $val);
					if (is_array($arr)) {
						$val = "";
						foreach ($arr as $v) {
							$val .= "'" . $v . "',";
						}
						$val = substr($val, 0, -1);
					}
					if ($i == 0) {
						$sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
					} else {
						$sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
					}
					$i++;
				}
				$sqlStr .= ")";
				$service->searchArr['mySearchCondition'] = $sqlStr;
				return true;
			}
		}
	}
  /**
   * �ر�������Ŀ
   */
   function c_ajaxCloseTr() {
		try {
			$this->service->ajaxCloseTr_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->list_d();
        // ��ѯ��Ʒ��Ϣ
        $rows = $service->dealProduct_d($rows);
        echo util_jsonUtil::encode($rows);
    }

 }
?>