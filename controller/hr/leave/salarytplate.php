<?php
/**
 * @author huangzf
 * @Date 2013��4��25�� ������ 15:29:40
 * @version 1.0
 * @description:�����嵥ģ����Ʋ�
 */
class controller_hr_leave_salarytplate extends controller_base_action {

	function __construct() {
		$this->objName = "salarytplate";
		$this->objPath = "hr_leave";
		parent::__construct ();
	}

	/**
	 * ��ת�������嵥ģ���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�����������嵥ģ��ҳ��
	 */
	function c_toAdd() {
		$otherDao=new model_common_otherdatas();     
		$arr=$otherDao->getCompanyAndAreaInfo();   
		$companyOpt="";
		for($i=0;$i<count($arr);$i++){
			$id=$arr[$i]['ID'];
			$companyOpt =$companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  
		}
		$this->assign('companyOpt',$companyOpt);     
		$this->view ( 'add' );
	}
	 
	/**
	 * ��ת���༭�����嵥ģ��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$otherDao=new model_common_otherdatas();     //�½�otherdatas����
		$arr=$otherDao->getCompanyAndAreaInfo();   //������й�˾�͹�˾��������
		$companyOpt="";
		for($i=0;$i<count($arr);$i++){
			$id=$arr[$i]['ID'];
			//������Ӧ�Ĺ�˾���ƣ���Ĭ��ѡ��
			if($arr[$i]['NameCN']==$obj['companyName']){
				$companyOpt =$companyOpt."<option value='$id' selected>".$arr[$i]['NameCN']."</option>";
			}else{
				$companyOpt =$companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //ƴ��option��ǩ
			}
		}
		$this->showDatadicts ( array ('leaveTypeCode' => 'HRLZLX' ),$obj['leaveTypeCode']);//��ְ����

		$this->assign('companyOpt',$companyOpt);
		$this->view ( 'edit');
	}
	 
	/**
	 * ��ת���鿴�����嵥ģ��ҳ��
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
	 * 
	 * ����ѡ��ģ�����������Ϣ
	 */
	function c_addItemList(){
		$service = $this->service;
		$itemDao=new model_hr_leave_salarytplateitem();
		$itemDao->searchArr=array("mainId"=>$_POST['mainId']);
		$service->asc=false;
		$rows = $itemDao->list_d ("select_default");
		$fromworkList = $this->service->showItemListAtChoose($rows);
		echo $fromworkList;
	}
}
?>