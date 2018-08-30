<?php
class controller_svn_index extends model_svn_index
{
	public $show;
	function __construct()
	{
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'svn/';
	}
	/**
	 * 默认访问
	 */
	function c_index()
	{
		$this->show->assign ( 'admin', $this->is_admin );
		$this->show->assign ( 'svn_username', $this->svn_username );
		$this->show->display ( 'index' );
	}
	/**
	 * SVN管理列表
	 */
	function c_list_data()
	{
		$dir = $_GET ['dir'] ? $_GET ['dir'] : $_POST ['dir'];
		$data = $this->GetList ( './' . $dir );
		$files_status = $this->FileStatus ( './' . $dir );
		$path = $data ? ( string ) $data->list->attributes ()->path : '';
		$dir_list = array ();
		$file_list = array ();
		$json = array ();
		$attributes = "@attributes";
		if ($data->list->entry)
		{
			foreach ( $data->list->entry as $key => $row )
			{
				$tmp = array ();
				$tmp ['path'] = $path;
				$tmp ['name'] = ( string ) mb_iconv($row->name);
				$tmp ['status'] = '';
				$tmp ['filenamepath'] = $path . '/' . $tmp ['name'];
				$tmp ['size'] = ( string ) $row->size;
				$tmp ['revision'] = ( string ) $row->commit->attributes ()->revision;
				$tmp ['author'] = ( string ) $row->commit->author;
				$date = ( string ) $row->commit->date;
				$tmp ['date'] = $date ? date ( 'Y-m-d H:i:s', strtotime ( $date ) ) : '';
				$tmp ['types'] = ( string ) $row->attributes ()->kind;
				if (( string ) $row->attributes ()->kind == 'dir')
				{
					$tmp ['state'] = 'closed';
					$dir_list [] = $tmp;
				} else
				{
					$tmp ['state'] = 'open';
					$tmp ['status'] = $files_status [$tmp ['name']] ['status'] == 'modified' ? '已更改(' . $files_status [$tmp ['name']] ['new_revision'] . ')' : '';
					$tmp ['svn_revision'] = $files_status [$tmp ['name']] ['new_revision'] ? $files_status [$tmp ['name']] ['new_revision'] : '';
					$file_list [] = $tmp;
				}
			}
		}
		$list_data = $dir_list;
		if ($file_list)
		{
			foreach ( $file_list as $val )
			{
				$list_data [] = $val;
			}
		}
		//var_dump($file_list);
		echo json_encode ( un_iconv ( $list_data ) );
	}
	/**
	 * 查看SVN的文件
	 */
	function c_cat()
	{
		
		$filenamepath = $_POST ['filenamepath'] ? $_POST ['filenamepath'] : $_GET ['filenamepath'];
		$revision = $_POST ['revision'] ? $_POST ['revision'] : $_GET ['revision'];
		if ($filenamepath)
		{
			$reulst = $this->svn->cat ( $this->svn->svn_url . $filenamepath, $revision );
			$arr = explode ( '.', $filenamepath );
			$types = $arr [count ( $arr ) - 1];
			$types = $types == 'htm' ? 'html' : $types;
			$types = $types == 'txt' || $types == 'log' ? 'text' : $types;
		}
		$highlight = array ();
		if ($reulst)
		{
			$highlight = $this->check ( $reulst );
		}
		$highlight = $highlight ? 'highlight:[' . implode ( ',', $highlight ) . ']' : '';
		$this->show->assign ( 'warning', $highlight ? 'true' : 'false' );
		$this->show->assign ( 'highlight', $highlight );
		$this->show->assign ( 'types', $types );
		$this->show->assign ( 'code', $reulst ? htmlspecialchars ( implode ( "\r\n", $reulst ) ) : '' );
		$this->show->display ( 'code' );
	}
	/**
	 * 本地文件与SVN最新版本比较
	 */
	function c_diff()
	{
		$filenamepath = $_POST ['filenamepath'] ? $_POST ['filenamepath'] : $_GET ['filenamepath'];
		$revision = $_POST ['revision'] ? $_POST ['revision'] : $_GET ['revision'];
		$reulst = $this->Diff ( $filenamepath );
		$this->show->assign ( 'code', $reulst ? htmlspecialchars ( implode ( "\r\n", $reulst ) ) : '' );
		$this->show->display ( 'diff' );
	
	}
	/**
	 * 更新文件
	 */
	function c_update_file()
	{
		$file_list = $_POST ['file_list'] ? trim ( $_POST ['file_list'], '|' ) : trim ( $_GET ['file_list'], '|' );
		$file_revision = $_POST ['file_revision'] ? trim ( $_POST ['file_revision'], '|' ) : trim ( $_GET ['file_revision'], '|' );
		if ($file_list)
		{
			$check_file_arr = array ();
			$update_file_arr = array ();
			$revision_arr = array ();
			$file_arr = explode ( '|', $file_list );
			$rev_arr = explode("|", $file_revision);
			$error_log = '';
			foreach ( $file_arr as $key => $file )
			{
				if ($this->is_admin)
				{
					try
					{
						$this->svn->update ( $file );
					} catch ( Exception $e )
					{
						$error_log = $e->getMessage ();
					}
				
				} else
				{
					$tmp = array ();
					$reulst = $this->svn->cat ( $file );
					$tmp = $this->check ( $reulst );
					if ($tmp)
					{
						if ($this->check_audit ( $file, $rev_arr [$key] ))
						{
							try
							{
								$this->svn->update ( $file );
							} catch ( Exception $e )
							{
								$error_log = $e->getMessage ();
							}
							$update_file_arr [] = $file;
						} else
						{
							$check_file_arr [$file] = $tmp;
							$revision_arr [$file] = $rev_arr [$key];
						}
					} else
					{
						try
						{
							$this->svn->update ( $file );
						} catch ( Exception $e )
						{
							$error_log = $e->getMessage ();
						}
						$update_file_arr [] = $file;
					}
				}
			}
			if ($this->is_admin)
			{
				if (!$error_log)
				{
					echo $error_log;
				} 
			} else
			{
				if ($check_file_arr)
				{
					$error_file = array ();
					$error_row = array ();
					$error_rev = array ();
					$str = '<div id="error">';
					foreach ( $check_file_arr as $file => $val )
					{
						$error_file [] = $file;
						$error_row [] = implode ( ',', $val );
						$error_rev [] = $revision_arr [$file];
						$str .= '<p>文件：' . $file . '的第[' . implode ( ',', $val ) . ']行存在系统设置的危险关键字，需要审批后才可以更新。</p>';
					}
					$str .= $error_file ? '<input type="hidden" id="error_file" name="error_file" value="' . implode ( '|', $error_file ) . '" />' : '';
					$str .= $error_file ? '<input type="hidden" id="error_row" name="error_row" value="' . implode ( '|', $error_row ) . '" />' : '';
					$str .= $error_rev ? '<input type="hidden" id="error_rev" name="error_rev" value="' . implode ( '|', $error_rev ) . '" />' : '';
					$str .= '</div>';
					
					if ($update_file_arr)
					{
						$str .= '<div id="success">';
						$str .= '<br />以下文件已经成功更新！<br />';
						$str .= implode ( '<br />', $update_file_arr );
						$str .= '</div>';
					}
					echo un_iconv ( $str );
				} else
				{
					if (!$error_log)
					{
						echo $error_log;
					}
				
				}
			}
		}
	}
	/**
	 * 检出
	 */
	function c_checkout()
	{
		$url = $_POST ['path'] ? $_POST ['path'] : $_GET ['path'];
		if ($url)
		{
			$error_file_arr = array ();
			$error_rows_arr = array ();
			$error_file_revision_arr = array ();
			$list_obj = $this->GetList ( $this->svn->svn_url . $url . ' --depth=infinity ' );
			if ($list_obj->list)
			{
				foreach ( $list_obj->list->entry as $row )
				{
					if ($row->attributes ()->kind == 'file')
					{
						$filename = ( string ) $row->name;
						$arr = explode ( '.', $filename );
						$types = $arr [count ( $arr ) - 1];
						if ($types == 'php' || $types == 'php3' || $types == 'php4' || $types == 'php5' || $types == 'inc' || $types == 'asp' || $types == 'aspx')
						{
							$audit = $this->check_audit ( $url . '/' . $filename, ( string ) $row->commit->attributes ()->revision );
							if (! $audit)
							{
								$code = $this->svn->cat ( $this->svn->svn_url . $url . '/' . $filename );
								$highlight = $this->check ( $code );
								unset ( $code );
								if ($highlight)
								{
									$error_file_arr [] = $url . '/' . $filename;
									$error_rows_arr [] = $highlight ? implode ( ',', $highlight ) : '';
									$error_file_revision_arr [] = ( string ) $row->commit->attributes ()->revision;
								}
							}
						}
					}
				}
			}
			if ($error_file_arr)
			{
				$str = '<div id="error">';
				foreach ( $error_file_arr as $key => $file )
				{
					$str .= '<p>文件：' . $file . '的第[' . $error_rows_arr [$key] . ']行存在系统设置的危险关键字，需要审批后才可以更新。</p>';
				}
				$str .= $error_file_arr ? '<input type="hidden" id="error_file" name="error_file" value="' . implode ( '|', $error_file_arr ) . '" />' : '';
				$str .= $error_rows_arr ? '<input type="hidden" id="error_row" name="error_row" value="' . implode ( '|', $error_rows_arr ) . '" />' : '';
				$str .= $error_file_revision_arr ? '<input type="hidden" id="error_rev" name="error_rev" value="' . implode ( '|', $error_file_revision_arr ) . '" />' : '';
				$str .= '<div />';
				echo un_iconv ( $str );
			} else
			{
				$dir = WEB_TOR . $url;
				$result = $this->svn->checkout ( $url, $dir );
				echo $result ? un_iconv ( implode ( '<br />', $result ) ) : '';
			}
		}
	}
	
	/**
	 * 申请审批文件
	 */
	function c_apply()
	{
		$file_list = $_POST ['file_list'] ? explode ( "|", $_POST ['file_list'] ) : ($_GET ['file_list'] ? explode ( "|", $_GET ['file_list'] ) : array ());
		$file_rows = $_POST ['file_rows'] ? explode ( "|", $_POST ['file_rows'] ) : ($_GET ['file_rows'] ? explode ( "|", $_GET ['file_rows'] ) : array ());
		$file_revision = $_POST ['file_revision'] ? explode ( "|", $_POST ['file_revision'] ) : ($_GET ['file_revision'] ? explode ( "|", $_GET ['file_revision'] ) : array ());
		if ($file_list && $file_revision)
		{
			$tmp = '';
			foreach ( $file_list as $key => $file )
			{
				if ($this->add_apply ( $file, $file_revision [$key], $file_rows [$key] ))
				{
					$tmp .= '<p>File：' . $file . ' Revision：' . $file_revision [$key] . ' </p>';
				}
			}
			if ($tmp)
			{
				$AddAddress = $this->get_admin_emal ();
				if ($AddAddress)
				{
					$body = '<p>以下SVN文件更新需要您审批的:</p>' . $tmp;
					$email = new includes_class_sendmail ();
					$email->send ( $_SESSION ['USERNAME'] . '提交了SVN文件更新申请，需要您审批！', $body, $AddAddress );
				}
			}
			echo 1;
		}
	
	}
	/**
	 * 文件审批管理
	 */
	function c_apply_list()
	{
		$this->show->display ( 'apply-list' );
	}
	/**
	 * 申请审批列表数据
	 */
	function c_apply_list_data()
	{
		$this->tbl_name = 'svn_file_audit';
		$data = $this->get_apply_data ( NULL, $_POST ['page'], $_POST ['rows'] );
		$json = array (
					'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	//审批
	function c_audit()
	{
		$id = $_POST ['id'] ? trim ( $_POST ['id'], ',' ) : trim ( $_GET ['id'], ',' );
		$status = $_POST ['status'] ? $_POST ['status'] : $_GET ['status'];
		if ($id)
		{
			if ($this->Audit ( $id, $status ))
			{
				echo 1;
			} else
			{
				echo - 1;
			}
		} else
		{
			echo - 1;
		}
	}
	/**
	 * 删除申请
	 */
	function c_del_apply()
	{
		$id = $_POST['id'] ? $_POST['id'] : $_GET['id'];
		$this->tbl_name = 'svn_file_audit';
		if ($this->Del($id))
		{
			echo 1;
		}else{
			echo -1;
		}
	
	}
	
	/**
	 * 用户管理
	 */
	function c_user_list()
	{
		$this->show->display ( 'user' );
	}
	/**
	 * 用户列表JOSN数据
	 */
	function c_user_list_data()
	{
		$data = $this->GetDataList ( NULL, $_POST ['page'], $_POST ['rows'] );
		$json = array (
					'total' => $this->num 
		);
		if ($data)
		{
			$json ['rows'] = un_iconv ( $data );
		} else
		{
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	/**
	 * 保存数据
	 */
	function c_save_user()
	{
		
		$_POST = mb_iconv ( $_POST );
		if ($_POST)
		{
			if ($_POST ['id'])
			{
				if($_POST['svn_password']==''){
					$_POST['svn_password']=$_POST['svn_password_hidden'];
				}else{
					$_POST['svn_password']=crypt_util($_POST['svn_password'],'encode');
				}
				unset($_POST['svn_password_hidden']);
				if ($this->Edit ( $_POST, $_POST ['id'] ))
				{
					echo 1;
				} else
				{
					echo - 1;
				}
			} else
			{
				unset($_POST['svn_password_hidden']);
				$_POST['svn_password']=crypt_util($_POST['svn_password'],'encode');
				if ($this->Add ( $_POST ))
				{
					echo 1;
				} else
				{
					echo - 1;
				}
			}
		} else
		{
			echo - 1;
		}
	}
	/**
	 * 删除
	 */
	function c_del_user()
	{
		$id = $_GET ['id'] ? $_GET ['id'] : $_POST ['id'];
		if ($this->Del ( $id ))
		{
			echo 1;
		} else
		{
			echo - 1;
		}
	}
}
?>