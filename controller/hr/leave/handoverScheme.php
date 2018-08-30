<?php
/**
 * @author Administrator
 * @Date 2012年10月26日 星期五 17:05:37
 * @version 1.0
 * @description:离职清单模板方案控制层
 */
class controller_hr_leave_handoverScheme extends controller_base_action {

	function __construct() {
		$this->objName = "handoverScheme";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * 跳转到离职清单模板方案列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增离职清单模板方案页面
	 */
	function c_toAdd() {
		$otherDao=new model_common_otherdatas();     //新建otherdatas对象
		$arr=$otherDao->getCompanyAndAreaInfo();   //获得所有公司和公司所属区域
		$companyOpt="";
		for($i=0;$i<count($arr);$i++){
			$id=$arr[$i]['ID'];
			$companyOpt =$companyOpt."<option value='$id'>".$arr[$i]['NameCN']."</option>";  //拼凑option标签
		}
		$this->assign('companyOpt',$companyOpt);     //将所有公司添加到select标签
	    $this->view ( 'add',true );
   }

   /**
	 * 跳转到编辑离职清单模板方案页面
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
		$this->showDatadicts ( array ('leaveTypeCode' => 'YGZTLZ' ),$obj['leaveTypeCode']);//离职类型

		$this->assign('companyOpt',$companyOpt);
        $this->view ( 'edit',true);
   }

   /**
	 * 跳转到查看离职清单模板方案页面
	 */
	function c_toView() {
        $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
     	$this->view ( 'view' );
   }
 }
?>