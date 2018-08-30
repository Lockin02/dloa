<?php
/**
 * @author Administrator
 * @Date 2012-07-18 19:15:30
 * @version 1.0
 * @description:职位申请表控制层
 */
class controller_hr_recruitment_employment extends controller_base_action {

	function __construct() {
		$this->objName = "employment";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到职位申请表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增职位申请表页面
	 */
	function c_toAdd() {
		$this->permCheck (); //安全校验
		//获取照片
		$this->assign("photo","images/no_pic.gif");
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		$this->assign("resumeId",$_GET['id']);
		//邮件信息渲染
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->showDatadicts ( array ('post' => 'YPZW' ) );

		$this->view ('add' ,true);
	}

	/**
	 * 外网跳转到职位申请表页面
	 */
	function c_toOuterAdd() {
		$this->permCheck (); //安全校验
		//获取照片
		$this->assign("photo","images/no_pic.gif");
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		$this->assign("resumeId",$_GET['id']);
		//邮件信息渲染
		$mailArr = $this->service->getMailInfo_d();
		$this->assignFunc($mailArr);

		$this->view ('outeradd' ,true);
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			msg('您的职位申请已成功提交！');
		}

		//$this->listDataDict();
	}

	/**
	 * 外网新增对象操作
	 */
	function c_outeradd($isAddInfo = true) {
		$this->checkSubmit(); //检查是否重复提交
		$obj = $_POST [$this->objName];

		$id = $this->service->add_d ( $obj, $isAddInfo );

		if ($id) {
			msg('提交成功！');
		}
		//$this->listDataDict();
	}
	/**
	 * 跳转到编辑职位申请表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isMedicalHistory'] == '是') {
			$this->assign ( 'isYes', 'checked' );
		} else if ($obj ['isMedicalHistory'] == '否') {
			$this->assign ( 'isNo', 'checked' );
		}
		if ($obj ['isIT'] == '1') {
			$this->assign ( 'isITY', 'checked' );
		} else{
			$this->assign ( 'isITN', 'checked' );
		}
		//获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id'],'oa_hr_personnel');

		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$str=str_replace(WEB_TOR,'',UPLOADPATH);
		if(substr($str,0,1)=="/"){
			$str=substr($str,1);
		}
		$this->assign("photoUrl",$str);
		//附件
		$file2 = $this->service->getFilesByObjId($obj['id'], true, 'oa_hr_recruitment_employment2');
		$this->assign("file2",$file2);
		$this->showDatadicts ( array ('healthStateCode' => 'HRJKZK' ), $obj ['healthStateCode'] );
		$this->showDatadicts ( array ('politicsStatusCode' => 'HRZZMM' ), $obj ['politicsStatusCode'] );
		$this->showDatadicts ( array ('highEducation' => 'HRJYXL' ), $obj ['highEducation'] );
		$this->showDatadicts ( array ('englishSkill' => 'HRYYDJ' ), $obj ['englishSkill'] );
		$this->showDatadicts ( array ('post' => 'YPZW' ), $obj ['post'] );
		$this->view ('edit' ,true);
	}

	/**
	 * 跳转到查看职位申请表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if ($obj ['isIT'] == '1') {
			$this->assign ( 'isITName', '是' );
		} else if ($obj ['isIT'] == '0') {
			$this->assign ( 'isITName', '否' );
		}
		//获取照片
		$photo= $this->service->getFilePhoto_d ( $obj ['id'],'oa_hr_personnel');
		if(empty($photo)){
			$this->assign("photo","images/no_pic.gif");
		}else{
			$this->assign("photo",$photo);
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false,'oa_hr_recruitment_employment2' ) );
		$this->assign("address",$obj['appointPro'].$obj['appointCity'].$obj['appointAddress']);
		$this->view ( 'view' );
	}

	/**
	 * 入职申请表 特别说明
	 */
	function c_specialVersion(){
		$this->view("specialVersion");
	}

	/**
	 * 入职申请选择
	 */
	function c_selectEmployment(){
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}

	/**判断是否已提交职位申请
	 *author can
	 *2010-12-29
	 */
	function c_isSumbitForm() {
		$identityCard=isset($_POST['identityCard'])?$_POST['identityCard']:exit;
		$id =$this->service->get_table_fields($this->service->tbl_name, "identityCard='".$identityCard."'", 'id');
		//如果删除成功输出1，否则输出0
		if($id>0){
			echo 0;
		}else{
			echo 1;
		}
	}

	/**
	 * 职位申请弹出窗口选择
	 */
	function c_selectEmp(){
		$this->view('selectList');
	}
}
?>