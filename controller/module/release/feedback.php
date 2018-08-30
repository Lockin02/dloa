<?php
class controller_module_release_feedback extends model_module_release_feedback
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'module/release/';
	}
	/**
	 * 列表
	 */
	function c_module_list()
	{
		$module_id = $_GET['module_id'] ? $_GET['module_id'] : $_POST['module_id'];
		$data = $this->GetDataList("module_id=".$module_id);
		if ($data)
		{
			$str ='<div id="module_list">';
			$str .='<ul>';
			$str .='<li style="width:80px;height:20px;font-weight:700;">用户</li>';
			$str .='<li style="width:490px;height:20px;font-weight:700;text-align:center;">反馈的内容</li>';
			$str .='</ul>';
			foreach ($data as $rs)
			{
				$str .='<ul>';
				$str .='<li class="left"><span class="lt">用户：'.$rs['user_id'].'</span><span class="ri">发表：'.$rs['date'].'</span></li>';
				$str .='<li class="right">'.$rs['content'].'</li>';
				$str .='</ul>';
			}
			
			$str .='</div>';
			echo un_iconv($str);
		}else{
			echo un_iconv('<p>暂无反馈意见！</p>');
		}
	}
	
	/**
	 * 提交
	 */
	function c_add()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
			$_POST['user_id'] = $_SESSION['USER_ID'];
			$_POST['date'] = date('Y-m-d H:i:s');
			if ($this->Add($_POST))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			echo -2;
		}
	}
}

?>