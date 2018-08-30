<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:22:42
 * @version 1.0
 * @description:��Ʒ������ϸ����Ʋ�
 */
class controller_stockup_application_applicationMatter extends controller_base_action {

	function __construct() {
		$this->objName = "applicationMatter";
		$this->objPath = "stockup_application";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ʒ������ϸ���б�
	 */
    function c_page() {
     	$keyType=($_POST['keyType']?$_POST['keyType']:$_GET['keyType']);
		$keyWords=($_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords']);
		$keyTypeI=array('listNo,productCode,productName'=>'�� �� ','listNo'=>'���ݱ��','productCode'=>'��Ʒ����','productName'=>'��Ʒ����');
		foreach($keyTypeI as $key=>$val){
			$keyTypeStr.="<option value='$key'".($key==$keyType?'selected':'')." >$val</option>";
		}
		$this->assign ( 'keyType', $keyTypeStr);
		$this->assign ( 'keyWords', $keyWords);
		$this->assign ( 'appList', $this->service->showAppList() );
		$this->pageShowAssign();
      	$this->view('list');
    }

   /**
	 * ��ת��������Ʒ������ϸ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��Ʒ������ϸ��ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }


   /**
	 * ��ת���鿴��Ʒ������ϸ��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
    /**
	 * ��񷽷�
    *
    */
   function c_getJsonEdit(){
		$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('jsonEdit');
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil :: encode($rows);

	}
    /**
	 * ��񷽷�
	 */
	function c_pageItemJson(){
    	$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('pageItem');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
    }



 }
?>