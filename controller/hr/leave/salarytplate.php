<?php
/**
 * @author huangzf
 * @Date 2013年4月25日 星期四 15:29:40
 * @version 1.0
 * @description:工资清单模板控制层
 */
class controller_hr_leave_salarytplate extends controller_base_action {

	function __construct() {
		$this->objName = "salarytplate";
		$this->objPath = "hr_leave";
		parent::__construct ();
	}

	/**
	 * 跳转到工资清单模板列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增工资清单模板页面
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
	 * 跳转到编辑工资清单模板页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$otherDao=new model_common_otherdatas();     //新建otherdatas对象
		$arr=$otherDao->getCompanyAndAreaInfo();   //获得所有公司和公司所属区域
		$companyOpt="";
		for($i=0;$i<count($arr);$i++){
			$id=$arr[$i]['ID'];
			//带出对应的公司名称，并默认选中
			if($arr[$i]['NameCN']==$obj['companyName']){
				$companyOpt =$companyOpt."<option value='$id' selected>".$arr[$i]['NameCN']."</option>";
			}else{
				$companyOpt =$companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //拼凑option标签
			}
		}
		$this->showDatadicts ( array ('leaveTypeCode' => 'HRLZLX' ),$obj['leaveTypeCode']);//离职类型

		$this->assign('companyOpt',$companyOpt);
		$this->view ( 'edit');
	}
	 
	/**
	 * 跳转到查看工资清单模板页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}
	
	/**
	 * 
	 * 根据选中模板带出具体信息
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