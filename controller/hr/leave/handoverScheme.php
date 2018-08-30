<?php
/**
 * @author Administrator
 * @Date 2012��10��26�� ������ 17:05:37
 * @version 1.0
 * @description:��ְ�嵥ģ�巽�����Ʋ�
 */
class controller_hr_leave_handoverScheme extends controller_base_action {

	function __construct() {
		$this->objName = "handoverScheme";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * ��ת����ְ�嵥ģ�巽���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������ְ�嵥ģ�巽��ҳ��
	 */
	function c_toAdd() {
		$otherDao=new model_common_otherdatas();     //�½�otherdatas����
		$arr=$otherDao->getCompanyAndAreaInfo();   //������й�˾�͹�˾��������
		$companyOpt="";
		for($i=0;$i<count($arr);$i++){
			$id=$arr[$i]['ID'];
			$companyOpt =$companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //ƴ��option��ǩ
		}
		$this->assign('companyOpt',$companyOpt);     //�����й�˾��ӵ�select��ǩ
	    $this->view ( 'add',true );
   }

   /**
	 * ��ת���༭��ְ�嵥ģ�巽��ҳ��
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
		$this->showDatadicts ( array ('leaveTypeCode' => 'YGZTLZ' ),$obj['leaveTypeCode']);//��ְ����

		$this->assign('companyOpt',$companyOpt);
        $this->view ( 'edit',true);
   }

   /**
	 * ��ת���鿴��ְ�嵥ģ�巽��ҳ��
	 */
	function c_toView() {
        $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
     	$this->view ( 'view' );
   }
 }
?>