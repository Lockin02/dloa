<?php
class controller_lore_index extends model_lore_index
{
	public $show;
	public $dir;
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> db = new mysql ( );
		$this -> show = new show ( );
		$this -> show -> path = 'lore/';
		$this->dir=substr(dirname(__FILE__),0,strlen(dirname(__FILE__))-15)."attachment\lore\\";

	}
	function c_list ( )
	{
		//$this->show->assign ( 'user', $this->model_username());
		//Global $func_limit; //获取当前登陆用户拥有函数的限制
		if ( $_GET[ 'pid' ] )
		{
			if ( in_array ( '1' , $this -> usepower ( $_GET[ 'pid' ]) ) )
			{
				if ( in_array ( '2' , $this -> usepower ( $_GET[ 'pid' ]) ) )
				{
					$this -> show -> assign ( 'showadmin' , '&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=manager" target="_self">版块管理</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=levelmanager" target="_self">等级管理</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=tagmanager" target="_self">标签管理</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_index&action=manager" target="_self">管理文章</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad2" href="?model=lore_index&action=count" target="_self">统计</a>&nbsp;&nbsp;|' );
				} else
				{
					$this -> show -> assign ( 'showadmin' , '&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=manager" target="_self">版块管理</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_index&action=manager" target="_self">管理文章</a>
									&nbsp;&nbsp;|&nbsp;&nbsp;' );
				}
			} else
			{
				if ( in_array ( '3' , $this -> usepower ( $_GET[ 'pid' ]) ) )
				{
					$this -> show -> assign ( 'showadmin' , '&nbsp;&nbsp;<a  id="ad1" href="?model=lore_index&action=approval" target="_self">管理文章</a>
									&nbsp;&nbsp;|' );
				} else
				{
					$this -> show -> assign ( 'showadmin' , '' );
				}
			}

			/*
			 If ( $func_limit[ '查看' ] == 1 ) //该数组的下标为控制名称
			 {
			 if ( $this -> model_admin ( ) === 0 || $this -> model_admin ( ) != '' )
			 {
				$this -> show -> assign ( 'showadmin' , '&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=manager" target="_self">版块管理</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=levelmanager" target="_self">等级管理</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=tagmanager" target="_self">标签管理</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_index&action=manager" target="_self">管理文章</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad2" href="?model=lore_index&action=count" target="_self">统计</a>&nbsp;&nbsp;|' );
				} else
				{
				$this -> show -> assign ( 'showadmin' , '&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=manager" target="_self">版块管理</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_index&action=manager" target="_self">管理文章</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;' );
				}
				} else
				{
				if ( $this -> model_admin ( ) === 0 || $this -> model_admin ( ) != '' )
				{
				$this -> show -> assign ( 'showadmin' , '&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=levelmanager" target="_self">等级管理</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_type&action=tagmanager" target="_self">标签管理</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad1" href="?model=lore_index&action=approval" target="_self">管理文章</a>
				&nbsp;&nbsp;|&nbsp;&nbsp;<a  id="ad2" href="?model=lore_index&action=count" target="_self">统计</a>&nbsp;&nbsp;|' );
				} else
				{
				$this -> show -> assign ( 'showadmin' , '' );
				}

				}
				*/
			$tagsI = $this -> model_tagsinfo ( $_GET[ 'pid' ] );
			$this -> show -> assign ( 'sf' , in_array ( '1' , $this -> usepower ( $_GET[ 'pid' ] ) ) || in_array ( '4' , $this -> usepower ( $_GET[ 'pid' ] ) ) || in_array ( '6' , $this -> usepower ( $_GET[ 'pid' ] ) ) ? true : false );
			$this -> show -> assign ( 'tag1' , $this -> model_tagslink ( $tagsI ) );
			//$this->show->assign ( 'tag2', $this->model_tagslink ( 2 ) );
			//$this->show->assign ( 'tag3', $this->model_tagslink ( 3 ) );
			//$taglist = $this->model_taglist ( 1 ) . $this->model_taglist ( 2 ) . $this->model_taglist ( 3 );
			//echo $this->model_heardlinks ($_GET['pid']?$_GET['pid']:'0');
			$this -> show -> assign ( 'titles' , $this -> model_heardlinks ( $_GET[ 'pid' ] ? $_GET[ 'pid' ] : 0 ) );
			$this -> show -> assign ( 'taglist' , $this -> model_taglist ( $tagsI ) );
			$this -> show -> assign ( 'userid' , $_GET[ 'userid' ] ? $_GET[ 'userid' ] : '' );
			$this -> show -> assign ( 'pid' , $_GET[ 'pid' ] ? $_GET[ 'pid' ] : '' );
			$this -> show -> display ( 'index' );
		} else
		{
			echo '<script type="text/javascript">window.history.go(-1);</script>';
		}

	}
	function c_listdata ( )
	{
		echo $this -> model_listdata ( );
	}
	function c_showposts ( )
	{
		$tagsI = $this -> model_tagsinfo ( $_GET[ 'pid' ] );
		$this -> show -> assign ( 'tag1' , $this -> model_tags ( $tagsI ) );
		//$this->show->assign ('pkid',$_GET['pid']);
		$this -> show -> display ( 'addposts' );
	}
	function c_searchcorrelation ( )
	{
		echo un_iconv ( $this -> model_searchcorrelation ( ) );
	}
	function c_addposts ( )
	{
		if ( $this -> model_addposts ( ) == 1 )
		{
			showmsg ( '操作成功' , '?model=lore_index&action=list&pid=' . $_GET[ 'pid' ] , 'link' );
		} else
		{
			showmsg ( '操作失败' , '?model=lore_index&action=showposts&pid=' . $_GET[ 'pid' ] , 'link' );
		}
	}
	function c_editposts ( )
	{
		echo $this -> model_editposts ( );
	}
	function c_refer ( )
	{
		$this -> show -> assign ( 'taglist' , $this -> model_refertlists ( ) );
		$this -> show -> display ( 'refer' );
	}
	function c_referdata ( )
	{
		echo $this -> model_referdata ( );
	}
	function c_manager ( )
	{
		$tagsI = $this -> model_tagsinfo ( $_GET[ 'pid' ] );
		$this -> show -> assign ( 'taglist' , $this -> model_taglist ( $tagsI ) );
		$this -> show -> display ( 'manager' );
	}
	function c_managerdata ( )
	{

		echo $this -> model_managerdata ( );
	}
	function c_operation ( )
	{
		echo un_iconv ( $this -> model_operation ( ) );
	}
	function c_viewthread ( )
	{
		$this -> show -> assign ( 'pid' , trim ( $_GET[ 'pid' ] ) );
		$this -> show -> assign ( 'key' , trim ( $_GET[ 'key' ] ) );
		$this -> show -> assign ( 'sby' , trim ( $_GET[ 'sby' ] ) );
		$this -> show -> assign ( 'titles' , $this -> model_heardlinks ( $_GET[ 'pid' ] ? $_GET[ 'pid' ] : 0 ) );
		$this -> show -> assign ( 'listherf' , $this -> model_randherf ( trim ( $_GET[ 'key' ] ) ) );
		$this -> show -> display ( 'viewthread' );
	}
	function c_viewthreaddata ( )
	{
		echo $this -> model_viewthreaddata ( );
	}
	function c_addreview ( )
	{
		echo un_iconv ( $this -> model_addreview ( ) );
	}

	function c_updatereview ( )
	{
		echo un_iconv ( $this -> model_updatereview ( ) );
	}
	function c_adoperation ( )
	{
		echo un_iconv ( $this -> model_adoperation ( ) );
	}
	function c_deladop ( )
	{
		echo un_iconv ( $this -> model_deladop ( ) );
	}
	function c_report ( )
	{
		echo un_iconv ( $this -> model_report ( ) );
	}
	function c_favorites ( )
	{
		echo un_iconv ( $this -> model_favorites ( ) );

	}
	function c_showapply ( )
	{
		$articleI = $this -> model_articleinfo ( $_GET[ 'rand_key' ] );
		$this -> show -> assign ( 'title' , $articleI[ 'title' ] );
		$this -> show -> assign ( 'key' , $_GET[ 'rand_key' ] );

		//$this->show->assign ( 'content',str_replace('"','&quot;',str_replace('&quot;','&acute&quot;',$articleI['content'])));
		$this -> show -> assign ( 'content' , htmlspecialchars ( $articleI[ 'content' ] ) );
		$this -> show -> assign ( 'levels1' , '' );
		$this -> show -> assign ( 'levels2' , '' );
		$this -> show -> assign ( 'levels3' , '' );
		$this -> show -> assign ( 'doctype1' , '' );
		$this -> show -> assign ( 'doctype2' , '' );
		if ( $articleI[ 'levels' ] == '1' )
		{
			$this -> show -> assign ( 'levels1' , 'checked' );

		} elseif ( $articleI[ 'levels' ] == '2' )
		{
			$this -> show -> assign ( 'levels2' , 'checked' );

		} elseif ( $articleI[ 'levels' ] == '2' )
		{
			$this -> show -> assign ( 'levels3' , 'checked' );

		}
		if ( $articleI[ 'doctype' ] == '1' )
		{
			$this -> show -> assign ( 'doctype1' , 'checked' );

		} elseif ( $articleI[ 'doctype' ] == '2' )
		{
			$this -> show -> assign ( 'doctype2' , 'checked' );

		}
		$tagsI = $this -> model_tagsinfo ( $_GET[ 'pid' ] );
		$this -> show -> assign ( 'tag1' , $this -> model_tagschecked ( $tagsI , $articleI[ 'typeid' ] ) );
		$this -> show -> assign ( 'rewords' , $articleI[ 'rewords' ] );
		$this -> show -> assign ( 'rewordtd' , $this -> model_searchrelationed ( $articleI[ 'reterms' ] ) );
		$this -> show -> assign ( 'attachment' , $this -> model_upfileslist ( $_GET[ 'rand_key' ] ) );
		$this -> show -> assign ( 'cid' , $_GET[ 'cid' ] );
		if ( $_GET[ 'so' ] )
		{
			$this -> show -> assign ( 'dishow' , 'none' );
		} else
		{
			$this -> show -> assign ( 'dishow' , 'block' );
		}

		$this -> show -> display ( 'editposts' );

	}
	function c_addmessage ( )
	{
		echo un_iconv ( $this -> model_addmessage ( ) );
	}
	function c_delfer ( )
	{
		echo un_iconv ( $this -> model_delfer ( ) );
	}
	function c_message ( )
	{
		$this -> show -> display ( 'message' );

	}
	function c_messagedata ( )
	{
		echo un_iconv ( $this -> model_messagedata ( ) );

	}
	function c_messageop ( )
	{
		echo un_iconv ( $this -> model_messageop ( ) );

	}
	function c_mymessages ( )
	{
		echo un_iconv ( $this -> model_mymessages ( ) );
	}
	function c_upfile ( )
	{
		echo $this -> model_upfile ( $this -> upfilebase );
	}
	function c_delupfiles ( )
	{
		$val = $this -> model_delupfile ( );
		if ( $val == 1 )
		{
			echo un_iconv ( $this -> model_upfileslist ( $_POST[ 'rand_key' ] ) );
		}

	}
	function c_count ( )
	{
		$tagsI = $this -> model_tagsinfo ( );
		$this -> show -> assign ( 'taglist' , $this -> model_taglist ( $tagsI ) );
		$this -> show -> display ( 'count' );
	}
	function c_countdata ( )
	{
		echo $this -> model_countdata ( );
	}
	function c_upbrowse ( )
	{
		echo $this -> model_upbrowse ( );
	}
	function c_userinfo ( )
	{
		echo un_iconv ( $this -> model_getuserid ( ) );
	}
	function c_exportword ( )
	{
		echo $this -> model_exportword ( );

	}
	function c_test ( )
	{
		echo $this -> model_admin ( );
		$this -> show -> display ( 'test' );
	}
	function c_comake ( )
	{
		echo un_iconv ( $this -> model_comake ( ) );
	}
	function c_makeleave ( )
	{
		echo un_iconv ( $this -> model_makeleave ( ) );
	}
	function c_delfavorites ( )
	{
		echo un_iconv ( $this -> model_delfavorites ( ) );
	}
	function c_showreturn ( )
	{
		echo un_iconv ( $this -> model_showreturn ( ) );
	}
	function c_showreport ( )
	{
		echo un_iconv ( $this -> model_showreport ( ) );
	}
	function c_reportmanager ( )
	{

		$this -> show -> display ( 'reportmanager' );
	}
	function c_reportmanagerdata ( )
	{

		echo $this -> model_reportmanagerdata ( );
	}
	function c_reportmanagerop ( )
	{
		echo un_iconv ( $this -> model_reportmanagerop ( ) );

	}
	function c_main ( )
	{
		$this -> show -> assign ( 'forum' , $this -> model_main ( ) );
		$this -> show -> display ( 'main' );
	}
	function c_approval ( )
	{
		//$this->show->assign ( 'forum', $this->model_main ());
		$this -> show -> display ( 'approval' );
	}
	function c_approvaldata ( )
	{
		echo $this -> model_approvaldata ( );
	}
	function c_countoexcel ( )
	{
		echo $this -> model_count_toexcel ( );
	}

	function c_docview ( )
	{
		$fileI=$this->model_upfileinfo(trim($_GET['key']));
		$name=$fileI['fakename'];
		$title=$fileI['filesname'];
		$type=end(explode('.', $name));
		if($name&&in_array($type,array('doc','pdf','txt','png','gif','xls','ppt','jpg','htm','html'), TRUE)){
			$names=substr($name, 0,strlen($name)-strlen($type)-1);
			if($name){
				$oldfile = str_replace('\\','\\\\',$this->dir).$name;
				$newfile = str_replace('\\','\\\\',$this->dir).$names.".swf";
				$command = "E:\AppServ\www\douding.com\FlashPaper2.2\FlashPrinter.exe ".$oldfile." -o ".$newfile;
				$command =str_replace('\n','',nl2br($command));
				if(!file_exists($this->dir.$names.".swf")){
					ini_set("memory_limit", "256M");
					ini_set('max_execution_time', '120');
					exec($command);
					//exec("exit(0)");
				}
			}
		}
		$this -> show -> assign ( 'keys' , $this -> upfilebase.($names?$names:'bb'));
		$this -> show -> assign ( 'title' , $title);
		$this -> show -> display ( 'docview' );
	}

}

?>