<?php
class controller_system_portal_usercustomize extends controller_base_action{
	function __construct() {
		$this->objName = "usercustomize";
		$this->objPath = "system_portal";
		parent::__construct ();
	 }


	 /**
	 * 设置portlet显示格式
	 * 查找当前用户的portlet
	 */
	 function c_setPortlet(){
	 	$this->permCheck (); //安全校验
	 	$obj = $this->service->findBy("userId",$_SESSION['USER_ID']);
		if(!empty($obj)){
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
		}else{
			$this->assign ( "customizeStr", "0.5,0.5" );
			$this->assign ( "id", "" );
			$this->assign ( "colNum", "2" );
		}
		$this->view("set");
	 }

	 //设置用户修改portal信息
	 function c_save(){
		$object = $_POST [$this->objName];
		if(empty($object['id'])){
			$object['userId']=$_SESSION['USER_ID'];
			$object['userName']=$_SESSION['USERNAME'];
			$this->service->add_d($object);
		}else{
			$this->service->edit_d ( $object,true );
		}
		echo "<script>alert('保存成功！');parent.tb_remove();parent.location.reload();</script>";
		//header('Location: http:// '.$_SESSION['HTTP_HOST'].$_SESSION['PHP_SELF']);
	 }

//	 function msg2($title, $url = '') {
//	if(empty($url)){
//		echo "<script type='text/javascript' src='js/jquery/jquery-1.4.2.js'></script>";
//		echo "<script>alert('".$title."');if(parent.$('#TB_window').length==1){parent.show_page();parent.tb_remove();}else{if(window.opener!=undefined){try{window.opener.show_page();}catch(e){}}
//		window.close();}</script>";
//		return;
//	}else if($url=='debug'){
//		$url = '<input type="button" onclick="self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);" value=" 返回 " />';
//	}else{
//		$url =  '<input type="button" onclick="self.parent.tb_remove();self.parent.location=\''.$url.'\'" value=" 返回 " />';
//	}
//	$html = file_get_contents ( TPL_DIR . '/showmsg.htm' );
//	$html = str_replace ( '{title}', $title, $html );
//	$html = str_replace ( '{url}', $url, $html );
//	echo $html;
//	exit ();
//	}

}
?>
