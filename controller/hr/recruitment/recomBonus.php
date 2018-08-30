<?php
/**
 * @author Administrator
 * @Date 2012年7月20日 星期五 11:33:19
 * @version 1.0
 * @description:内部推荐奖金控制层
 */
class controller_hr_recruitment_recomBonus extends controller_base_action {

	function __construct() {
		$this->objName = "recomBonus";
		$this->objPath = "hr_recruitment";
		parent::__construct ();
	}

	/**
	 * 跳转到内部推荐奖金列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到内部推荐奖金列表
	 */
	function c_myPage() {
		$this->assign('userid',$_SESSION['USER_ID']);
		$this->view('mylist');
	}

	/**
	 * 跳转到新增内部推荐奖金页面
	 */
	function c_toAdd() {
		$setit = new model_hr_recruitment_recommend();
		$resume = new model_hr_recruitment_resume();
		$uploadFile = new model_file_uploadfile_management ();
		$recominfo = $setit->get_d($_GET['recomid']);
		$resume->getParam ( array("myinnerId"=>$recominfo['id']) );
		$resumeinfo = $resume->page_d();
		foreach ( $recominfo as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//显示附件信息
		$files = $uploadFile->getFilesByObjId ( $_GET ['recomid'], 'oa_hr_recruitment_recommend' );
		$fileStr='';
		if(is_array($files)){
			foreach($files as $fKey=>$fVal){
				$i=$fKey+1;
				//插入附件表
				$fileArr['serviceType']="oa_hr_recommend_bonus";
				$fileArr['originalName']=$fVal['originalName'];
				$fileArr['newName']="oa_hr_recommend_bonus"."-".$fVal['newName'];
				$UPLOADPATH2=UPLOADPATH;
				$newPath=str_replace('\\','/',$UPLOADPATH2);
				$destDir=$newPath."oa_hr_recommend_bonus/";
				$fileArr['uploadPath']=$destDir;
				$fileArr['tFileSize']=$fVal['tFileSize'];
				$test = $uploadFile->add_d ( $fileArr, true );
				$fileStr.='<div class="upload" id="fileDiv'.$test.'"><a title="点击下载" href="?model=file_uploadfile_management&amp;action=toDownFileById&amp;fileId='.$test.'">'.$fVal['originalName'].'</a>&nbsp;<img src="images/closeDiv.gif" onclick="delfileById('.$test.')" title="点击删除附件"><div></div></div><input type="hidden" name="fileuploadIds['.$i.']" value="'.$test.'">';
			}
		}

		$this->show->assign("file",$fileStr);
		$this->assign ( "parentId", $recominfo['id'] );
		$this->assign ( "recommendCode", $recominfo['formCode'] );
		$this->assign ( "resumeId", $resumeinfo[0]['id'] );
		$this->assign ( "resumeCode", $resumeinfo[0]['resumeCode'] );
		$this->assign ( "formManName", $_SESSION["USERNAME"] );
		$this->assign ( "formManId", $_SESSION["USER_ID"] );
		$this->showDatadicts(array('job'=>'YPZW'));
		$this->view ('add' ,true);
	}

	/**
	 * 跳转到编辑内部推荐奖金页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('job'=>'HRGWFL'));
		// $this->showDatadicts(array('job'=>'HRGWFL'),$obj['job']);
		$this->showDatadicts(array('job'=>'YPZW'),$obj['job']);
		$this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
		$this->view ('edit' ,true);
	}

	function c_postMail(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );

			if($folowInfo['examines']=="ok"){  //审批通过则指定供应商
				$obj = $this->service->get_d ( $folowInfo['objId'] );
				//发送邮件通知采购员
				$this->service->postMailto($obj);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //检查是否重复提交
		$_POST[$this->objName]['jobName'] = $this->getDataNameByCode($_POST[$this->objName]['job']);
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
			echo "<script>history.back(-1);</script>";
		}
		//$this->listDataDict();
	}

	/**
	 * 跳转到查看内部推荐奖金页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看内部推荐奖金页面
	 */
	function c_toRead() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->view ( 'read' );
	}

	/**
	 * 修改对象
	 */
	function c_handUp($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$object['state'] = 0;
		if ($this->service->add_d ( $object, $isAddInfo )) {
			msg ( '提交成功！' );
		}
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		if(is_array($rows)){
			foreach( $rows as $key => $val ){
				//转换成中文
				$rows[$key]['stateC']=$service->statusDao->statusKtoC($rows[$key]['state'] );
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/********************add chenrf 20150520***************/
	/**
	 * 跳转到相应的审批流
	 */
	function c_redirectEwf(){
		if($_GET['id']==''){
			msg('提交数据错误，无法指定对应审批流!');
			exit;
		}
		$url=$this->service->c_redirectEwf($_GET['id']);
		succ_show($url);
	}
	/**
	 * 提交操作
	 */
	function c_submit(){
		$obj['id']=$_GET['id'];
		$obj['state']=6;
		if($this->service->changeState($obj)){
			msg( '提交成功！');
		}else{
			msg( '提交失败！');
		}
	}
	/**
	 * 新增时直接提交
	 */
	function c_addSubmit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->addSubmit ( $object )) {
			msg ( '提交成功！' );
		}else{
			msg( '提交失败！');
		}
	}

}
?>