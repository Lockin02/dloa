<?php
class controller_index extends model_index {
	public $show;
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
	}
	
	function c_index()
	{
		$this->show->display('index');
	}
	/*
	 * 顶部
	 */
	function c_top()
	{
		$this->show->display('top');
	}
	
	function c_body()
	{
		//$this->show->assign('menu',$this->model_menu());
		$this->show->display('body');
	}
	/**
	 * 菜单
	 */
	function c_menu()
	{
		//file_put_contents('j.txt',json_encode($_GET));
		/*header("Content-type: text/xml;charset=utf-8");
		$et = ">";
		echo "<?xml version='1.0' encoding='utf-8'?$et\n";
		echo "<rows>";
		echo "<page>1</page>";
		echo "<total>1</total>";
		echo "<records>1</records>";
		echo str_replace('&','&amp;',$this->model_menu());
		echo "</rows>";*/
		echo json_encode($this->model_menu_list());
	}
	/**
	 * 首页
	 */
	function c_main() {
		$this->show->assign ( 'title', 'OA首页！' );
		$this->tbl_name = 'my_table';
		$row = $this->find ( array(
									'userid'=>$_SESSION['USER_ID']), null, 'tab_l,tab_r' );
		$table_left_arr = $row['tab_l'] ? explode ( ',', $row['tab_l'] ) : explode ( ',', table_left );
		$table_right_arr = $row['tab_r'] ? explode ( ',', $row['tab_r'] ) : explode ( ',', table_right );
		global $table_title, $table_function;
		$left_content = '';
		foreach ( $table_left_arr as $val ) {
			if ($table_function[$val]) {
				$title = $table_title[$val];
				$id = $table_function[$val];
				$url = $table_function[$val] . '.php';
				$list = $this->{'model_' . $table_function[$val]} ();
				$left_content .= $this->module ( $title, $id, $url, $list );
			}
		}
		$this->show->assign('left_content',$left_content);
		$right_content = '';
		foreach ( $table_right_arr as $val ) {
			if ($table_function[$val]) {
				$title = $table_title[$val];
				$id = $table_function[$val];
				$url = $table_function[$val] . '.php';
				$list = $this->{'model_' . $table_function[$val]} ();
				$right_content .= $this->module ( $title, $id, $url, $list );
			}
		}
		$this->show->assign('right_content',$right_content);
		$this->show->display ( 'main' );
	}
	
	function module($title, $id, $url, $list) {
		return '<div class="module">
					<div class="title">
				    	<div class="title_left"><img src="images/menu/' . $id . '.gif" border="0" width="18" height="18" /> ' . $title . '</div>
				        <div class="title_right"><a href="' . $url . '"><img src="images/menu/more5.png" border="0" alt="查看更多" /></a> <a href="javascript:resize(\''.$id.'\')"><img id="right_' . $id . '" src="images/verpic_close.gif" border="0" /></a></div>
				    </div>
				    <div class="list">
				    <ul id="list_' . $id . '">
				    	'.$list.'
				    </ul>
				    </div>
				</div>
			';
	}

}
?>