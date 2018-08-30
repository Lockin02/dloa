<?php
/**
 * @author Show
 * @Date 2011��5��9�� ����һ 19:44:55
 * @version 1.0
 * @description:������Ŀ����Ʋ�
 */
class controller_techsupport_tstask_tstask extends controller_base_action {

	function __construct() {
		$this->objName = "tstask";
		$this->objPath = "techsupport_tstask";
		parent::__construct ();
	 }

	/*
	 * ��ת��������Ŀ��
	 */
    function c_page() {
      $this->display('list');
    }

    /**
     * ѡ������ҳ��
     */
    function c_toSelect(){
    	$this->showDatadicts( array('formType' => 'FWXMLX') );
		$this->display( 'toadd' );
    }

    /**
     * ��дtoadd
     */
    function c_toAdd(){
    	//���Ե�������ҳ��
        $formType = isset($_GET['formType'])? $_GET['formType'] : $_GET['obj']['formType'];
		$this->assign('formType' ,$formType);
		$thisObjCode = $this->service->getBusinessCode($formType);

		$this->showDatadicts(array( 'needEat' => 'YANDN' ));
		$this->showDatadicts(array( 'needPresents' => 'YANDN' ));
		$this->assign('salesmanId' ,$_SESSION['USER_ID']);
		$this->assign('salesman' ,$_SESSION['USERNAME']);

		$this->display( $thisObjCode . '-add');
    }
     /**
      * �ҵ���ǰ֧��
      */
      function c_mybeforelist(){
         $this->display('mybeforelist');
      }
    /**
     * �ⲿ��������������
     */
    function c_toAddWin(){
		//���Ե�������ҳ��//���Ե�������ҳ��
//		$this->assign('formType' ,$_GET['formType']);
		$this->permCheck($_GET['obj']['objId'],'projectmanagent_chance_chance');

		//��ȡ�ϼ���Ϣ
        $chanceDao = new model_projectmanagent_chance_chance();
        $chance = $chanceDao->find(array('id' => $_GET['obj']['objId'] ), null);
        $this->assignFunc($chance);


		$this->assignFunc($_GET['obj']);
		$thisObjCode = $this->service->getBusinessCode($_GET['obj']['formType']);
		$this->assign('salesmanId' ,$_SESSION['USER_ID']);
		$this->assign('salesman' ,$_SESSION['USERNAME']);
		$this->showDatadicts(array( 'needEat' => 'YANDN' ));
		$this->showDatadicts(array( 'needPresents' => 'YANDN' ));

		$this->display( $thisObjCode . '-addwin');
    }

    /**
     *  �ⲿ������add����
     */
    function c_addWin(){
		$id = $this->service->add_d ( $_POST [$this->objName] );
		if ($id) {
			msgRf ( '��ӳɹ�' );
		}else{
			msgRf ( '���ʧ��');
		}
    }

    /**
     * ��дinit
     */
    function c_init(){
        //URLȨ�޿���
        $this->permCheck();
		$perm = isset($_GET['perm']) ? $_GET['perm'] : null ;
		$obj = $this->service->get_d ( $_GET ['id'] );
		//��Ⱦ��������
		$this->assignFunc($obj);

		$thisObjCode = $this->service->getBusinessCode($obj['formType']);

		if ($perm == 'view') {
			$this->assign( 'needEatCN' ,$this->getDataNameByCode( $obj['needEat']) );
			$this->assign( 'needPresentsCN' ,$this->getDataNameByCode( $obj['needPresents']) );
			$this->display ( $thisObjCode . '-view' );
		} else {
			$this->showDatadicts(array( 'needEat' => 'YANDN' ),$obj['needEat']);
			$this->showDatadicts(array( 'needPresents' => 'YANDN' ),$obj['needPresents']);
			$this->showDatadicts ( array ('payType' => 'DKFS' ),$obj['payType'] );
			$this->display ( $thisObjCode . '-edit' );
		}
    }

    /**
     * ��ϸ������Ϣ(�ڶ�Ӧҵ���в鿴)
     */
    function c_listForObj(){
    	$this->assignFunc($_GET['obj']);
		$this->display( 'listforobj' );
    }

    /**
     * ��д�����¼
     */
    function c_handup(){
        //URLȨ�޿���
        $this->permCheck();
		$obj = $this->service->get_d ( $_GET ['id'] );
		//��Ⱦ��������
		$this->assignFunc($obj);

		$thisObjCode = $this->service->getBusinessCode($obj['formType']);

		$this->assign('techniciansId',$_SESSION['USER_ID']);
		$this->assign('technicians',$_SESSION['USERNAME']);

		$this->assign( 'needEatCN' ,$this->getDataNameByCode( $obj['needEat']) );
		$this->assign( 'needPresentsCN' ,$this->getDataNameByCode( $obj['needEat']) );
		$this->display ( $thisObjCode . '-handup' );
    }

    /**
     * �ύ
     */
    function c_pushUp(){
		if($this->service->update(array( 'id' => $_POST['id']),array('status' => 'XMZT-03'))){
			echo 1;
		}else{
			echo 0;
		}
    }

    /**
     * ����
     */
    function c_pushDown(){
		if($this->service->update(array( 'id' => $_POST['id']),array('status' => 'XMZT-01'))){
			echo 1;
		}else{
			echo 0;
		}
    }


/*************************************************************************************************/
   /**
    * �ҵ���ǰ֧��
    */
    function c_MyBeforeListPageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['createId'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('select_default');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

}
?>