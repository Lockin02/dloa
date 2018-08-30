<?php
class controller_lore_type extends model_lore_type
{
	public $show;
	
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> show = new show ( );
		$this -> show -> path = 'lore/';
	
	}
	
	function c_list ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_list ( ) );
		$this -> show -> display ( 'type-list' );
	}
	
	function c_add ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_save ( $_POST ) )
			{
				showmsg ( '操作成功' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' );
			}
		} else
		{
			$this -> show -> display ( 'type-add' );
		}
	}
	
	function c_edit ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_save ( $_POST ) )
			{
				showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败,请与管理员联系！' );
			}
		} else
		{
			$this -> tbl_name = 'lore_type';
			$data = $this -> find ( array ( 
											'id' => $_GET[ 'id' ] , 
											'rand_key' => $_GET[ 'key' ] 
			) );
			if ( $data )
			{
				if ( $data[ 'administrator' ] )
				{
					$gl = new includes_class_global ( );
					$userinfo = $gl -> GetUser ( explode ( ',' , $data[ 'administrator' ] ) );
					if ( $userinfo )
					{
						$usernamelist = '';
						$administrator = array ();
						foreach ( $userinfo as $key => $row )
						{
							$administrator[ ] = $row[ 'USER_ID' ];
							$usernamelist .= '<div id="user_' . $key . '">' . $row[ 'USER_NAME' ] . '<a href="javascript:del(' . $key . ')">删除</a></div>';
						}
					}
				}
				$this -> show -> assign ( 'selected_1' , ( $data[ 'level' ] == 1 ? 'selected' : '' ) );
				$this -> show -> assign ( 'selected_2' , ( $data[ 'level' ] == 2 ? 'selected' : '' ) );
				$this -> show -> assign ( 'selected_3' , ( $data[ 'level' ] == 3 ? 'selected' : '' ) );
				foreach ( $data as $key => $val )
				{
					$this -> show -> assign ( $key , $val );
				}
				if ( is_array ( $administrator ) )
				{
					$administrators = implode ( ',' , $administrator );
				} else
				{
					
					$administrators = '';
				}
				$this -> show -> assign ( 'administrator' , $administrators );
			}
			$this -> show -> assign ( 'usernamelist' , $usernamelist );
			$this -> show -> display ( 'type-edit' );
		}
	}
	
	function c_del ( )
	{
		if ( $this -> model_del ( $_GET[ 'id' ] , $_GET[ 'key' ] ) )
		{
			showmsg ( '操作成功！' , 'self.parent.location.reload();' , 'button' );
		} else
		{
			showmsg ( '操作失败，请与管理员联系！' );
		}
	}
	/**
	 * 显示管理版块
	 */
	function c_manager ( )
	{
		$this -> show -> assign ( 'list' , $this -> model_list ( ) );
		$this -> show -> display ( 'type-manager' );
	}
	/**
	 * 版块管理数据源
	 */
	function c_managerdata ( )
	{
		echo $this -> model_managerdata ( );
	
	}
	/**
	 * 显示新增版块
	 */
	function c_newtype ( )
	{
		$this -> show -> assign ( 'pkid' , $_GET[ 'pkid' ] );
		$this -> show -> assign ( 'key' , $_GET[ 'rand_key' ] );
		$this -> show -> display ( 'addtype' );
	
	}
	/**
	 * 增加版块
	 */
	function c_addtype ( )
	{
		if ( $_POST )
		{
			
			$_POST[ 'deptid' ] = $_POST[ 'dept_id_str' ] . '|' . $_POST[ 'deptid_str' ];
			$_POST[ 'userid' ] = $_POST[ 'user_id_str' ] . '|' . $_POST[ 'userid_str' ];
			$_POST[ 'deptname' ] = $_POST[ 'deptname_str' ] . '|' . $_POST[ 'dept_str' ];
			$_POST[ 'username' ] = $_POST[ 'username_str' ] . '|' . $_POST[ 'user_str' ];
			if ( isset ( $_FILES[ 'file' ] ) && $_FILES[ 'file' ][ 'size' ] != 0 && $_FILES[ 'file' ][ 'error' ] == 0 )
			{
				$file_size = $_FILES[ 'file' ][ 'size' ];
				$file_name = trim ( htmlspecialchars ( basename ( $_FILES[ 'file' ][ 'name' ] ) ) );
				//$file_type = $_FILES['imgs']['type'];
				$ext = strtolower ( strrchr ( $file_name , "." ) );
				$file_name = time ( ) . rand ( 10 , 100 ) . $ext;
				$allowedmimes = array ( 
										".gif" , 
										".jpg" , 
										".jpeg" , 
										".png" 
				);
				//chmod($_SERVER['DOCUMENT_ROOT'].'images/lore/', 0755);
				if ( in_array ( $ext , $allowedmimes ) )
				{
					if ( copy ( $_FILES[ 'file' ][ 'tmp_name' ] , 'images/lore/' . $file_name ) )
					{
						$_POST[ 'imgs' ] = $file_name;
					} else
					{
						$_POST[ 'imgs' ] = 'forum_news1.gif';
					}
				}
			} else
			{
				$_POST[ 'imgs' ] = 'forum_news.gif';
			}
			if ( $this -> model_addtype ( $_POST ) )
			{
				showmsg ( '操作成功' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' );
			}
		
		} else
		{
			$this -> show -> display ( 'addtype' );
		}
	}
	/**
	 * 显示编缉版块
	 */
	function c_modifytype ( )
	{
		$data = $this -> find ( array ( 
										'id' => $_GET[ 'id' ] , 
										'rand_key' => $_GET[ 'rand_key' ] 
		) );
		$deptI = array ();
		$administrator = array ();
		$deptName = array ();
		$adminName = array ();
		if ( $data )
		{
			$gl = new includes_class_global ( );
			if ( $data[ 'administrator' ] )
			{
				$userinfo = $gl -> GetUser ( explode ( ',' , $data[ 'administrator' ] ) );
				if ( $userinfo )
				{
					$usernamelist = '';
					
					foreach ( $userinfo as $key => $row )
					{
						$administrator[ ] = $row[ 'USER_ID' ];
						$adminName[ ] = $row[ 'USER_NAME' ];
						$usernamelist .= '<div id="user_' . $key . '">' . $row[ 'USER_NAME' ] . '<a href="javascript:del(' . $key . ',\'' . $row[ 'USER_NAME' ] . '\')">删除</a></div>';
					}
				}
			}
			if ( $data[ 'deptid' ] )
			{
				$dept_id = $gl -> GetDept ( is_array(explode ( ',' ,current ( explode ( '|' , $data[ 'deptid' ] ) ) )));
				$deptid = $gl -> GetDept ( is_array(explode ( ',' ,end ( explode ( '|' , $data[ 'deptid' ] ) ))) );
				if ( is_array ( $dept_id ) )
				{
					foreach ( $dept_id as $key => $row )
					{
						$dept_idI[ ] = $row[ 'DEPT_ID' ];
						$dept_nameI[ ] = $row[ 'DEPT_NAME' ];
					}
				}
				if ( is_array ( $deptid ) )
				{
					foreach ( $deptid as $key => $row )
					{
						$deptidI[ ] = $row[ 'DEPT_ID' ];
						$deptnameI[ ] = $row[ 'DEPT_NAME' ];
					}
				}
			
			}
			if ( $data[ 'userid' ] )
			{
				$user_id = $gl -> GetUser ( explode ( ',' ,current ( explode ( '|' , $data[ 'userid' ] )) ) );
				$userid = $gl -> GetUser ( explode ( ',' ,end ( explode ( '|' , $data[ 'userid' ] ) )) );
				if ( is_array ( $user_id ) )
				{
					foreach ( $user_id as $key => $row )
					{
						$user_idI[ ] = $row[ 'USER_ID' ];
						$user_nameI[ ] = $row[ 'USER_NAME' ];
					}
				}
				if ( is_array ( $userid ) )
				{
					foreach ( $userid as $key => $row )
					{
						$useridI[ ] = $row[ 'USER_ID' ];
						$usernameI[ ] = $row[ 'USER_NAME' ];
					}
				}
			
			}
			$administrators = is_array ( $administrator ) ? implode ( ',' , $administrator ) : '';
			$adminNames = is_array ( $adminName ) ? implode ( ',' , $adminName ) : '';
			
			$dept_ids = is_array ( $dept_idI ) ? implode ( ',' , $dept_idI ) : '';
			$deptids = is_array ( $deptidI ) ? implode ( ',' , $deptidI ) : '';
			$user_ids = is_array ( $user_idI ) ? implode ( ',' , $user_idI ) : '';
			$userids = is_array ( $useridI ) ? implode ( ',' , $useridI ) : '';
			$dept_names = is_array ( $dept_nameI ) ? implode ( ',' , $dept_nameI ) : '';
			$deptnames = is_array ( $deptnameI ) ? implode ( ',' , $deptnameI ) : '';
			$user_names = is_array ( $user_nameI ) ? implode ( ',' , $user_nameI ) : '';
			$usernames = is_array ( $usernameI ) ? implode ( ',' , $usernameI ) : '';
			
			$this -> show -> assign ( 'administrator' , $administrators );
			$this -> show -> assign ( 'adminname' , $adminNames );
			$this -> show -> assign ( 'dept_id_str' , $dept_ids );
			$this -> show -> assign ( 'deptid_str' , $deptids );
			$this -> show -> assign ( 'dept_name_str' , $dept_names );
			$this -> show -> assign ( 'deptn_str' , $deptnames );
			$this -> show -> assign ( 'user_id_str' , $user_ids );
			$this -> show -> assign ( 'userid_str' , $userids );
			$this -> show -> assign ( 'user_name_str' , $user_names );
			$this -> show -> assign ( 'usern_str' , $usernames );
			
			$this -> show -> assign ( 'styleid' , $data[ 'styleid' ] );
			$this -> show -> assign ( 'sortid' , $data[ 'sortid' ] );
			$this -> show -> assign ( 'typename' , $data[ 'typename' ] );
			$this -> show -> assign ( 'description' , $data[ 'description' ] );
			$this -> show -> assign ( 'imgs' , $data[ 'imgs' ] );
		}
		$this -> show -> assign ( 'usernamelist' , $usernamelist );
		$this -> show -> assign ( 'pkid' , $_GET[ 'id' ] );
		$this -> show -> assign ( 'key' , $_GET[ 'rand_key' ] );
		$this -> show -> assign ( 'typelist' , $this -> model_category ( $_GET[ 'pkid' ] ) , 0 , '' );
		$this -> show -> display ( 'edittype' );
	
	}
	/**
	 * 编缉版块
	 */
	function c_edittypes ( )
	{
		echo "<!--";
		print_r ( $_POST );
		echo "-->";
		if ( $_POST )
		{
			$_POST[ 'deptid' ] = $_POST[ 'dept_id_str' ] . '|' . $_POST[ 'deptid_str' ];
			$_POST[ 'userid' ] = $_POST[ 'user_id_str' ] . '|' . $_POST[ 'userid_str' ];
			$_POST[ 'deptname' ] = $_POST[ 'deptname_str' ] . '|' . $_POST[ 'dept_str' ];
			$_POST[ 'username' ] = $_POST[ 'username_str' ] . '|' . $_POST[ 'user_str' ];
			if ( isset ( $_FILES[ 'imgs' ] ) && $_FILES[ 'imgs' ][ 'size' ] != 0 && $_FILES[ 'imgs' ][ 'error' ] == 0 )
			{
				$file_size = $_FILES[ 'imgs' ][ 'size' ];
				$file_name = trim ( htmlspecialchars ( basename ( $_FILES[ 'imgs' ][ 'name' ] ) ) );
				$file_type = $_FILES[ 'imgs' ][ 'type' ];
				$ext = strtolower ( strrchr ( $file_name , "." ) );
				$file_name = time ( ) . rand ( 10 , 100 ) . $ext;
				$allowedmimes = array ( 
										".gif" , 
										".jpg" , 
										".jpeg" , 
										".png" 
				);
				if ( in_array ( $ext , $allowedmimes ) )
				{
					if ( copy ( $_FILES[ 'imgs' ][ 'tmp_name' ] , 'images/lore/' . $file_name ) )
					{
						$_POST[ 'imgs' ] = $file_name;
					} else
					{
						$_POST[ 'imgs' ] = $_POST[ 'upimgs' ];
					}
				}
			} else
			{
				$_POST[ 'imgs' ] = $_POST[ 'upimgs' ];
			}
			if ( $this -> model_edittypes ( $_POST ) )
			{
				showmsg ( '操作成功' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' );
			}
		} else
		{
			$this -> show -> display ( 'edittype' );
		}
	}
	/**
	 * 删除版块
	 */
	function c_deltype ( )
	{
		echo $this -> model_deltype ( );
	}
	/**
	 * 等级管理显示
	 */
	function c_Levelmanager ( )
	{
		$this -> show -> display ( 'levelmanager' );
	}
	/**
	 * 管理等级数据源
	 */
	function c_levelmanagerdata ( )
	{
		echo $this -> model_levelmanagerdata ( );
	}
	/**
	 * 显示修改等级
	 */
	function c_modifylevel ( )
	{
		$data = $this -> find ( array ( 
										'id' => $_GET[ 'id' ] , 
										'rand_key' => $_GET[ 'rand_key' ] 
		) );
		$deptI = array ();
		$administrator = array ();
		$deptName = array ();
		$adminName = array ();
		if ( $data )
		{
			$gl = new includes_class_global ( );
			if ( $data[ 'administrator' ] )
			{
				$userinfo = $gl -> GetUser ( explode ( ',' , $data[ 'administrator' ] ) );
				if ( $userinfo )
				{
					$usernamelist = '';
					
					foreach ( $userinfo as $key => $row )
					{
						$administrator[ ] = $row[ 'USER_ID' ];
						$adminName[ ] = $row[ 'USER_NAME' ];
						$usernamelist .= '<div id="user_' . $key . '">' . $row[ 'USER_NAME' ] . '<a href="javascript:del(' . $key . ',\'' . $row[ 'USER_NAME' ] . '\')">删除</a></div>';
					}
				}
			}
			if ( $data[ 'deptid' ] )
			{
				$deptinfo = $gl -> GetDept ( explode ( ',' , $data[ 'deptid' ] ) );
				
				if ( $deptinfo )
				{
					$deptidlist = '';
					foreach ( $deptinfo as $key => $row )
					{
						$deptI[ ] = $row[ 'DEPT_ID' ];
						$deptName[ ] = $row[ 'DEPT_NAME' ];
						$deptidlist .= '<div id="deptid_' . $key . '">' . $row[ 'DEPT_NAME' ] . '<a href="javascript:deldept(' . $key . ',\'' . $row[ 'DEPT_NAME' ] . '\')">删除</a></div>';
					}
				}
			
			}
			if ( is_array ( $administrator ) )
			{
				$administrators = implode ( ',' , $administrator );
			} else
			{
				$administrators = '';
			}
			if ( is_array ( $adminName ) )
			{
				$adminNames = implode ( ',' , $adminName );
			} else
			{
				$adminNames = '';
			}
			if ( is_array ( $deptName ) )
			{
				$deptNames = implode ( ',' , $deptName );
			} else
			{
				$deptNames = '';
			}
			if ( is_array ( $deptI ) )
			{
				$deptstr = implode ( ',' , $deptI );
			} else
			{
				$deptstr = '';
			}
			$this -> show -> assign ( 'deptment' , $deptstr );
			$this -> show -> assign ( 'adminname' , $adminNames );
			$this -> show -> assign ( 'deptname' , $deptNames );
			$this -> show -> assign ( 'administrator' , $administrators );
			$this -> show -> assign ( 'styleid' , $data[ 'styleid' ] );
			$this -> show -> assign ( 'sortid' , $data[ 'sortid' ] );
			$this -> show -> assign ( 'typename' , $data[ 'typename' ] );
			$this -> show -> assign ( 'description' , $data[ 'description' ] );
			$this -> show -> assign ( 'imgs' , $data[ 'imgs' ] );
		}
		
		$this -> show -> assign ( 'usernamelist' , $usernamelist );
		$this -> show -> assign ( 'deptidlist' , $deptidlist );
		$this -> show -> assign ( 'id' , $_GET[ 'id' ] );
		$this -> show -> assign ( 'key' , $_GET[ 'rand_key' ] );
		$this -> show -> assign ( 'levellist' , $this -> getuseradmincategoryidlist ( $_GET[ 'pid' ] ) , 0 , '' );
		$this -> show -> display ( 'editlevel' );
	}
	/**
	 * 显示新增等级
	 */
	function c_newlevel ( )
	{
		$this -> show -> assign ( 'pid' , $_GET[ 'id' ] );
		$this -> show -> display ( 'addlevel' );
	}
	/**
	 * 新增等级
	 */
	function c_addlevel ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_addlevel ( $_POST ) )
			{
				showmsg ( '操作成功' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' );
			}
		} else
		{
			$this -> show -> display ( 'addlevel' );
		}
	
	}
	/**
	 * 更新等级
	 */
	function c_editlevel ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_editlevel ( $_POST ) )
			{
				showmsg ( '操作成功' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' );
			}
		} else
		{
			$this -> show -> display ( 'editlevel' );
		}
	
	}
	/**
	 * 显示标签管理
	 */
	function c_tagmanager ( )
	{
		$this -> show -> display ( 'tagmanager' );
	
	}
	/**
	 * 管理标签数据源
	 */
	function c_tagmanagerdata ( )
	{
		echo $this -> model_tagmanagerdata ( );
	}
	/**
	 * 显示新增标签
	 */
	function c_newtag ( )
	{
		$this -> show -> assign ( 'typelist' , $this -> getuseradmincategoryidlist ( ) );
		$this -> show -> display ( 'addtag' );
	}
	/**
	 * 增加标签
	 */
	function c_addtag ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_addtag ( $_POST ) )
			{
				showmsg ( '操作成功' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' );
			}
		} else
		{
			$this -> show -> display ( 'addtag' );
		}
	
	}
	/**
	 * 等级列表
	 */
	function c_levellist ( $checked = 0 )
	{
		$str = '';
		$data = $this -> findAll ( array ( 
											'pid' => ( $_POST[ 'pid' ] ? $_POST[ 'pid' ] : $_GET[ 'tid' ] ) 
		) );
		$data = array_filter ( $data );
		if ( is_array ( $data ) )
		{
			foreach ( $data as $key => $val )
			{
				if ( is_array ( $val ) )
				{
					if ( $val[ 'id' ] == $checked )
					{
						$str .= "<option selected value='" . $val[ 'id' ] . "'>" . $val[ 'typename' ] . "</option>";
					} else
					{
						$str .= "<option value='" . $val[ 'id' ] . "'>" . $val[ 'typename' ] . "</option>";
					}
				}
			}
		}
		if ( $_POST[ 'pid' ] )
		{
			echo un_iconv ( $str );
		} else
		{
			return $str;
		}
	}
	/**
	 * 显示要编缉标签的数据
	 */
	function c_modifytag ( )
	{
		$data = $this -> find ( array ( 
										'id' => $_GET[ 'id' ] , 
										'rand_key' => $_GET[ 'rand_key' ] 
		) );
		$deptI = array ();
		$administrator = array ();
		$deptName = array ();
		$adminName = array ();
		if ( $data )
		{
			$gl = new includes_class_global ( );
			if ( $data[ 'administrator' ] )
			{
				$userinfo = $gl -> GetUser ( explode ( ',' , $data[ 'administrator' ] ) );
				if ( $userinfo )
				{
					$usernamelist = '';
					
					foreach ( $userinfo as $key => $row )
					{
						$administrator[ ] = $row[ 'USER_ID' ];
						$adminName[ ] = $row[ 'USER_NAME' ];
						$usernamelist .= '<div id="user_' . $key . '">' . $row[ 'USER_NAME' ] . '<a href="javascript:del(' . $key . ',\'' . $row[ 'USER_NAME' ] . '\')">删除</a></div>';
					}
				}
			}
			if ( $data[ 'deptid' ] )
			{
				$deptinfo = $gl -> GetDept ( explode ( ',' , $data[ 'deptid' ] ) );
				if ( $deptinfo )
				{
					$deptidlist = '';
					foreach ( $deptinfo as $key => $row )
					{
						$deptI[ ] = $row[ 'DEPT_ID' ];
						$deptName[ ] = $row[ 'DEPT_NAME' ];
						$deptidlist .= '<div id="deptid_' . $key . '">' . $row[ 'DEPT_NAME' ] . '<a href="javascript:deldept(' . $key . ',\'' . $row[ 'DEPT_NAME' ] . '\')">删除</a></div>';
					}
				}
			
			}
			if ( is_array ( $administrator ) )
			{
				$administrators = implode ( ',' , $administrator );
			} else
			{
				$administrators = '';
			}
			if ( is_array ( $adminName ) )
			{
				$adminNames = implode ( ',' , $adminName );
			} else
			{
				$adminNames = '';
			}
			if ( is_array ( $deptName ) )
			{
				$deptNames = implode ( ',' , $deptName );
			} else
			{
				$deptNames = '';
			}
			if ( is_array ( $deptI ) )
			{
				$deptstr = implode ( ',' , $deptI );
			} else
			{
				$deptstr = '';
			}
			$this -> show -> assign ( 'deptment' , $deptstr );
			$this -> show -> assign ( 'adminname' , $adminNames );
			$this -> show -> assign ( 'deptname' , $deptNames );
			$this -> show -> assign ( 'administrator' , $administrators );
			$this -> show -> assign ( 'styleid' , $data[ 'styleid' ] );
			$this -> show -> assign ( 'sortid' , $data[ 'sortid' ] );
			$this -> show -> assign ( 'tagname' , $data[ 'typename' ] );
			
			$this -> show -> assign ( 'description' , $data[ 'description' ] );
		}
		$this -> show -> assign ( 'usernamelist' , $usernamelist );
		$this -> show -> assign ( 'deptidlist' , $deptidlist );
		$this -> show -> assign ( 'id' , $_GET[ 'id' ] );
		$this -> show -> assign ( 'key' , $_GET[ 'rand_key' ] );
		$this -> show -> assign ( 'typelist' , $this -> getuseradmincategoryidlist ( $_GET[ 'tid' ] ) , 0 , '' );
		$this -> show -> assign ( 'levellist' , $this -> c_levellist ( $_GET[ 'lid' ] ) );
		$this -> show -> display ( 'edittag' );
	
	}
	/**
	 * 更新标签数据
	 */
	function c_edittag ( )
	{
		if ( $_POST )
		{
			if ( $this -> model_edittag ( $_POST ) )
			{
				showmsg ( '操作成功' , 'self.parent.location.reload();' , 'button' );
			} else
			{
				showmsg ( '操作失败，请与管理员联系！' );
			}
		} else
		{
			$this -> show -> display ( 'addtag' );
		}
	}
	function c_tagsorts ( )
	{
		$this -> tbl_name = 'lore_type';
		$data = $this -> find ( array ( 
										'rand_key' => $_POST[ 'key' ] 
		) );
		if ( $data )
		{
			if ( ( int ) $data[ 'sortid' ] == 0 || $_POST[ 'methods' ] == 'down' )
			{
				$sortid = ( int ) ( $data[ 'sortid' ] + 1 );
			} else
			{
				$sortid = ( int ) ( $data[ 'sortid' ] - 1 );
			}
			$dataI = $this -> findAll ( array ( 
												'pid' => $data[ 'pid' ] , 
												'sortid' => $sortid 
			) );
			if ( is_array ( $dataI ) )
			{
				foreach ( $dataI as $key => $val )
				{
					if ( is_array ( $val ) )
					{
						if ( $this -> model_tagsort ( $data , $sortid ) && $this -> model_tagsort ( $val , ( int ) $data[ 'sortid' ] != 0 ? $data[ 'sortid' ] : $sortid ) )
						{
							echo 1;
						} else
						{
						
						}
					}
				}
			}
			/*
			 if($datas){
				if($this->model_tagsort($data,$sortid)&& $this->model_tagsort($datas,$data ['sortid']))
				{
				echo 1;
				} else {
				echo 2;
				}
				}
				*/
		}
	}
	function c_test ( )
	{
		$id = $this -> model_getuseradmincategoryid ( );
		print_r ( $id );
	}
	function c_levelsort ( )
	{
		echo $this -> model_levelsort ( $_POST[ 'pid' ] );
	}

}

?>