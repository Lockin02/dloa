<?php
class model_lore_type extends model_base
{
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> tbl_name = 'lore_type';
		$this -> upfileimgs = "/images/lore/";
		if ( file_exists ( $this -> upfilebase ) == "" )
		{
			@mkdir ( $this -> upfilebase , 511 );
		}
	}

	/**
	 * 列表
	 */
	function model_list ( )
	{
		$where = $_GET[ 'level' ] ? "where level=" . $_GET[ 'level' ] : '';
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from lore_type $where" );
			$this -> num = $rs[ 'num' ];
		}
		if ( $this -> num )
		{
			$query = $this -> query ( "select * from lore_type $where" );
			$gl = new includes_class_global ( );
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				$str .= '<tr id="tr_' . $rs[ 'id' ] . '">';
				$str .= '<td>' . $rs[ 'id' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'level' ] == 1 ? '标签一' : ( $rs[ 'level' ] == 2 ? '标签二' : '标签三' ) ) . '</td>';
				$str .= '<td>' . $rs[ 'typename' ] . '</td>';
				$str .= '<td>' . ( $rs[ 'administrator' ] ? implode ( '、' , $gl -> GetUser ( explode ( ',' , $rs[ 'administrator' ] ) , true ) ) : '' ) . '</td>';
				$str .= '<td>' . thickbox_link ( '修改 ' , 'a' , 'id=' . $rs[ 'id' ] . '&key=' . $rs[ 'rand_key' ] , '修改 ' . $rs[ 'typename' ] , null , 'edit' , 500 , 500 ) . '
							| <a href="javascript:del(' . $rs[ 'id' ] . ',\'' . $rs[ 'rand_key' ] . '\')">删除</a></td>';
				$str .= '</tr>';
			}
		}

		if ( $this -> num > pagenum )
		{
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array (
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&level=' . $_GET[ 'level' ] );
			return $str . '<tr><td colspan="20">' . $showpage -> show ( 6 ) . '</td></tr>';
		} else
		{
			return $str;
		}
	}
	/**
	 * 添加或修改
	 * @param $data
	 */
	function model_save ( $data )
	{
		if ( $data[ 'id' ] && $data[ 'key' ] )
		{
			return $this -> update ( array (
											'id' => $data[ 'id' ] , 
											'rand_key' => $data[ 'key' ] 
			) , array (
						'level' => $data[ 'level' ] , 
						'typename' => $data[ 'typename' ] , 
						'administrator' => trim ( $data[ 'administrator' ] , ',' ) 
			) );
		} else
		{
			return $this -> create ( array (
											'level' => $data[ 'level' ] , 
											'typename' => $data[ 'typename' ] , 
											'administrator' => trim ( $data[ 'administrator' ] , ',' ) 
			) );
		}
	}
	/**
	 * 删除
	 */
	function model_del ( $id , $key )
	{
		if ( $id && $key )
		{
			$rs = $this -> get_one ( "select cid from lore_article where typeid=$id" );
			if (  ! $rs )
			{
				return $this -> delete ( array (
												'id' => $id , 
												'rand_key' => $key 
				) ) ? 1 : 0;
			} else
			{
				return  - 1;
			}
		} else
		{
			return  - 2;
		}

	}
	/**
	 * 版块管理数据源
	 */
	function model_managerdata ( )
	{

		$node = ( integer ) $_REQUEST[ "nodeid" ] ? $_REQUEST[ "nodeid" ] : 0;
		$n_lft = ( integer ) $_REQUEST[ "n_left" ];
		$n_rgt = ( integer ) $_REQUEST[ "n_right" ];
		$n_lvl = ( integer ) $_REQUEST[ "n_level" ];
		$n_lvl ++ ;
		$sql = "SELECT lt.id,lt.typename,lt.pid,lt.administrator,lt.sortid,lt.styleid
		   				 ,lt.rand_key,lt.deptid,lt.adminname,lt.deptname,lt.userid,lt.username
			    FROM lore_type lt 
			    WHERE  pid='$node' and flag='1'  order by id";
		$query = $this -> query ( $sql );
		$i = 0;
		$gl = new includes_class_global ( );
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			$leaf = 'true';
			$did = $this -> model_file_leftdatas ( $row[ 'id' ] );
			if ( $did == 0 )
			{
				$leaf = 'true';
			} else
			{
				$leaf = 'false';
			}
			$responce -> rows[ $i ][ 'id' ] = $row[ "id" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ 'id' ] ,
			$row[ 'rand_key' ] ,
			$row[ 'typename' ] ,
			$row[ 'adminname' ] ,
			$row[ 'sortid' ] ,
			$row[ 'styleid' ] ,
			$row[ 'pid' ] ,
			current ( explode ( '|' , $row[ 'deptname' ] ) ) ,
			current ( explode ( '|' , $row[ 'username' ] ) ) ,
			end ( explode ( '|' , $row[ 'deptname' ] ) ) ,
			end ( explode ( '|' , $row[ 'username' ] ) ) ,
																	'' , 
			$n_lvl ,
			$row[ 'pid' ] ,
			$leaf ,
																	'false' 
																	) );
																	$i ++ ;
		}
		return json_encode ( $responce );
	}
	/**
	 * 判断是否有子节点
	 * @param $node
	 * @return int
	 */
	function model_file_leftdatas ( $node )
	{
		$sql = "SELECT count(id)
			        FROM lore_type
			        WHERE pid='$node' and flag='1'  order by id";
		$res = $this -> get_one ( $sql );
		if ( $res )
		{
			return $res[ 'count(id)' ];
		} else
		{
			return 0;
		}

	}
	/**
	 * 增加版块数据
	 * @param $data
	 * @return boolean
	 */
	function model_addtype ( $data )
	{

		return $this -> create ( array (
										'flag' => 1 , 
										'sortid' => $data[ 'sortid' ] , 
										'pid' => ( $data[ 'pkid' ] ? $data[ 'pkid' ] : 0 ) , 
										'styleid' => $data[ 'styleid' ] , 
										'typename' => $data[ 'typename' ] , 
										'adminname' => trim ( $data[ 'adminname' ] , ',' ) , 
										'administrator' => trim ( $data[ 'administrator' ] , ',' ) , 
										'deptid' => trim ( $data[ 'deptid' ] , ',' ) , 
										'deptname' => trim ( $data[ 'deptname' ] , ',' ) , 
										'userid' => trim ( $data[ 'userid' ] , ',' ) , 
										'username' => trim ( $data[ 'username' ] , ',' ) , 
										'description' => trim ( $data[ 'description' ] , ',' ) , 
										'imgs' => trim ( $data[ 'imgs' ] , ',' ) 
		) );

	}
	/**
	 * 保存版块数据
	 * @param $data
	 * @return boolean
	 */
	function model_edittypes ( $data )
	{
		if ( $data[ 'id' ] && $data[ 'key' ] )
		{
			return $this -> update ( array (
											'id' => $data[ 'id' ] , 
											'rand_key' => $data[ 'key' ] 
			) , array (
						'flag' => 1 , 
						'sortid' => $data[ 'sortid' ] , 
						'pid' => ( $data[ 'pkid' ] ? $data[ 'pkid' ] : 0 ) , 
						'styleid' => $data[ 'styleid' ] , 
						'typename' => $data[ 'typename' ] , 
						'adminname' => trim ( $data[ 'adminname' ] , ',' ) , 
						'administrator' => trim ( $data[ 'administrator' ] , ',' ) , 
						'deptid' => trim ( $data[ 'deptid' ] , ',' ) , 
						'deptname' => trim ( $data[ 'deptname' ] , ',' ) , 
						'userid' => trim ( $data[ 'userid' ] , ',' ) , 
						'username' => trim ( $data[ 'username' ] , ',' ) , 
						'description' => trim ( $data[ 'description' ] , ',' ) , 
						'imgs' => trim ( $data[ 'imgs' ] , ',' ) 
			) );
		}
	}
	/**
	 * 首页版数据源
	 * @param $pid=0
	 * @return array()
	 */
	function model_typelist ( $pid = 0 )
	{
		$sqlstr = "select id,pid,typename,styleid from lore_type where 1 and flag='1' $sqlcon";
		$query = $this -> query ( $sqlstr );
		$forumI = array ();
		$i = 0;

		$arr = array ();
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			$arr[ ] = array (
			$row[ id ] ,
			$row[ pid ] ,
			$row[ typename ]
			);
		}
		return $arr;
	}
	/**
	 * 版块列表 select
	 * @param $checked  为选中id
	 * @param $f_id     为父ID
	 * @param $tag      为缩进格
	 * @return String
	 */
	function model_category ( $checked = 0 , $f_id = 0 , $tag = ' ' )
	{
		$arr = $this -> model_typelist ( );
		for ( $i = 0 ; $i < count ( $arr ) ; $i ++  )
		{
			if ( $arr[ $i ][ 1 ] == $f_id )
			{
				if ( $arr[ $i ][ 0 ] == $checked )
				{
					$str .= "<option selected value='" . $arr[ $i ][ 0 ] . "'>" . $tag . $arr[ $i ][ 2 ] . "</option>";
				} else
				{
					$str .= "<option value='" . $arr[ $i ][ 0 ] . "'>" . $tag . $arr[ $i ][ 2 ] . "</option>";
				}
				$str .= $this -> model_category ( $checked , $arr[ $i ][ 0 ] , $tag . ' |--' );
			}
		}
		return $str;
	}
	/**
	 * 版块删除方法
	 * @return Int
	 */
	function model_deltype ( )
	{
		$pid = ( int ) trim ( $_POST[ 'pid' ] );
		$key = trim ( $_POST[ 'key' ] );
		if ( $pid && $key )
		{
			$rs = $this -> get_one ( "select cid from lore_article where pid='$pid'" );
			$res = $this -> get_one ( "select id from lore_type where pid='$pid'" );
			if (  ! $rs &&  ! $res )
			{
				return $this -> delete ( array (
												'id' => $pid , 
												'rand_key' => $key 
				) ) ? 1 : 0;
			} else
			{
				return  - 1;
			}
		} else
		{
			return  - 2;
		}
	}
	/**
	 * 属于版块管理ID
	 * @return String
	 */
	function model_getuseradmincategoryid ( $pid = '' )
	{
		$arrI = array ();
		if ( $pid )
		{
			$sqlstr = " and pid='$pid'";
		} else
		{
			$sqlstr = " and find_in_set('" . $_SESSION[ 'USER_ID' ] . "',administrator)";
			if ( $_SESSION[ 'USER_ID' ] == 'admin' )
			{
				$sqlstr = " ";
			}
		}
		$sql = "select id from lore_type where flag='1' $sqlstr";
		$query = $this -> query ( $sql );
		//echo $sql;
		while ( ( $res = $this -> fetch_array ( $query ) ) != false )
		{
			$ids = $this -> model_getuseradmincategoryid ( $res[ 'id' ] );
			if ( $ids )
			{
				$id .= $ids . ',';
			} else
			{
				$id .= $res[ 'id' ] . ',';
			}
		}
		$arrI = explode ( ',' , $id );
		$id = implode ( ',' , array_unique ( array_filter ( $arrI ) ) );
		return $id;
	}
	/**
	 * 测试属于版块管理ID
	 * @return String
	 */
	function model_getuseradmincategoryidtest ( )
	{
		$arrI = array ();
		$sqlstr = "select id from lore_type where flag='1' and   find_in_set('" . $_SESSION[ 'USER_ID' ] . "',administrator)";
		$query = $this -> query ( $sqlstr );
		while ( ( $res = $this -> fetch_array ( $query ) ) != false )
		{
			$ids = $this -> model_getadminsubcategoryid ( $res[ 'id' ] );
			if ( $ids )
			{
				$id .= $ids . ',';
			} else
			{
				$id .= $res[ 'id' ] . ',';
			}
		}
		$arrI = explode ( ',' , $id );
		$id = implode ( ',' , array_unique ( array_filter ( $arrI ) ) );
		return $id;
	}
	function model_getadminsubcategoryid ( $pid )
	{
		if ( $pid )
		{
			$sqlsub = "select id from lore_type where pid='$pid' and flag='1'";
			$qsub = $this -> query ( $sqlsub );
			while ( ( $row = $this -> fetch_array ( $qsub ) ) != false )
			{
				$ids = $this -> model_getadminsubcategoryid ( $row[ 'id' ] );
				if ( $ids )
				{
					$id .= $ids . ',';
				} else
				{
					$id .= $row[ 'id' ] . ',';
				}
					
			}
		}
		return $id;
	}

	/**
	 * 等级列表 select
	 * @param $checked  为选中id
	 * @return String
	 */
	function getuseradmincategoryidlist ( $checked = 0 )
	{
		$id = $this -> model_getuseradmincategoryid ( );
		if ( $id )
		{
			$sql = "SELECT lt.id,lt.typename,lt.pid,lt.administrator,lt.sortid,lt.styleid
		   				   ,lt.rand_key,lt.deptid
			       FROM lore_type lt	
			       WHERE 1 and lt.flag='1' and lt.id in ($id) order by lt.id";
			$query = $this -> query ( $sql );
			while ( ( $row = $this -> fetch_array ( $query ) ) != false )
			{
				if ( $row[ 'id' ] == $checked )
				{
					$str .= "<option selected value='" . $row[ 'id' ] . "'>" . $row[ 'typename' ] . "</option>";
				} else
				{
					$str .= "<option value='" . $row[ 'id' ] . "'>" . $row[ 'typename' ] . "</option>";
				}
			}
		}
		return $str;
	}
	/**
	 * 版块列表 select
	 * @param $checked  为选中id
	 * @param $f_id     为父ID
	 * @param $tag      为缩进格
	 * @return String
	 */
	function model_categoryid ( $checked = 0 , $f_id = 0 , $tag = ' ' )
	{
		$arr = $this -> model_typelist ( );
		for ( $i = 0 ; $i < count ( $arr ) ; $i ++  )
		{
			if ( $arr[ $i ][ 1 ] == $f_id )
			{
				if ( $arr[ $i ][ 0 ] == $checked )
				{
					$str .= "<option selected value='" . $arr[ $i ][ 0 ] . "'>" . $tag . $arr[ $i ][ 2 ] . "</option>";
				} else
				{
					$str .= "<option value='" . $arr[ $i ][ 0 ] . "'>" . $tag . $arr[ $i ][ 2 ] . "</option>";
				}
				$str .= $this -> model_category ( $checked , $arr[ $i ][ 0 ] , $tag . ' |--' );
			}
		}
		return $str;
	}
	/**
	 * 等级管理数据
	 * @return Json
	 */
	function model_levelmanagerdata ( )
	{
		$node = ( integer ) $_REQUEST[ "nodeid" ] ? $_REQUEST[ "nodeid" ] : 0;
		$n_lft = ( integer ) $_REQUEST[ "n_left" ];
		$n_rgt = ( integer ) $_REQUEST[ "n_right" ];
		$n_lvl = ( integer ) $_REQUEST[ "n_level" ];
		$n_lvl ++ ;
		$url = '  ';

		if (  ! $node )
		{
			$id = $this -> model_getuseradmincategoryid ( );
			$sqlcon = " and id in ($id) and flag='1'";
		} else
		{
			$sqlcon = " and pid = '$node' and flag='2'";
		}
		$sql = "	SELECT lt.id,lt.typename,lt.pid,lt.administrator,lt.sortid,lt.styleid
		   				   ,lt.rand_key,lt.deptid,lt.adminname,lt.deptname
			    FROM lore_type lt 
			    WHERE 1 $sqlcon   order by id";
		$query = $this -> query ( $sql );
		$i = 0;
		$gl = new includes_class_global ( );

		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			$leaf = 'true';
			$flag = 1;
			if ( $node )
			{
				$flag = 2;
				$leaf = 'true';
			} else
			{
				$leaf = 'false';
			}
			//$username=$row['user_name']?$row['user_name']:implode ( '、', $gl->GetUser ( explode ( ',', $row ['administrator'] ), true ) );
			//$deptname=$row['deptid']?implode ( '、', $gl->GetDept ( explode ( ',', $row ['deptid'] ), true ) ):'';
			$responce -> rows[ $i ][ 'id' ] = $row[ "id" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "id" ] ,
			$row[ 'rand_key' ] ,
			$row[ 'typename' ] ,
			$row[ 'adminname' ] ,
			$row[ 'deptname' ] ,
			$row[ 'sortid' ] ,
			$row[ 'styleid' ] ,
			$flag ,
			$row[ 'pid' ] ,
																	'' , 
			$n_lvl ,
			$row[ 'pid' ] ,
			$leaf ,
																	'false' 
																	) );
																	$i ++ ;
		}
		return json_encode ( $responce );

	}
	/**
	 * 增加等级
	 * @param $data
	 * @return Boolean
	 */
	function model_addlevel ( $data )
	{
		if ( $data[ 'id' ] &&  ! $data[ 'key' ] )
		{
			return $this -> create ( array (
											'flag' => 1 , 
											'sortid' => $data[ 'sortid' ] , 
											'pid' => ( $data[ 'pkid' ] ? $data[ 'pkid' ] : 0 ) , 
											'styleid' => $data[ 'styleid' ] , 
											'typename' => $data[ 'typename' ] , 
											'administrator' => trim ( $data[ 'administrator' ] , ',' ) , 
											'deptid' => trim ( $data[ 'deptment' ] , ',' ) , 
											'adminname' => trim ( $data[ 'adminname' ] , ',' ) , 
											'deptname' => trim ( $data[ 'deptname' ] , ',' ) 
			) );
		} else
		{
			return $this -> create ( array (
											'flag' => 2 , 
											'sortid' => $data[ 'sortid' ] , 
											'pid' => ( $data[ 'pid' ] ? $data[ 'pid' ] : 0 ) , 
											'level' => $data[ 'styleid' ] , 
											'typename' => $data[ 'typename' ] , 
											'administrator' => trim ( $data[ 'administrator' ] , ',' ) , 
											'deptid' => trim ( $data[ 'deptment' ] , ',' ) , 
											'adminname' => trim ( $data[ 'adminname' ] , ',' ) , 
											'deptname' => trim ( $data[ 'deptname' ] , ',' ) 
			) );
		}

	}
	/**
	 * 保存等级
	 * @param $data
	 * @return Boolean
	 */
	function model_editlevel ( $data )
	{
		if ( $data[ 'id' ] && $data[ 'key' ] )
		{
			return $this -> update ( array (
											'id' => $data[ 'id' ] , 
											'rand_key' => $data[ 'key' ] 
			) , array (
						'flag' => 2 , 
						'level' => $data[ 'sortid' ] , 
						'pid' => ( $data[ 'pkid' ] ? $data[ 'pkid' ] : 0 ) , 
						'styleid' => $data[ 'styleid' ] , 
						'typename' => $data[ 'typename' ] , 
						'administrator' => trim ( $data[ 'administrator' ] , ',' ) , 
						'deptid' => trim ( $data[ 'deptment' ] , ',' ) , 
						'adminname' => trim ( $data[ 'adminname' ] , ',' ) , 
						'deptname' => trim ( $data[ 'deptname' ] , ',' ) 
			) );
		}

	}
	/**
	 * 标签管理数据源
	 * @return Json
	 */
	function model_tagmanagerdata ( )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$ids = $this -> model_getuseradmincategoryid ( );
		$sqls = "SELECT count(lt.id)
				 FROM lore_type lt left join lore_type   a  on  (a.id=lt.pid  and a.flag='2'   )
							    left join lore_type   b  on  (b.id=a.pid   and  b.id in ($ids)  and b.flag='1'   ) 
			        WHERE 1  and lt.flag='3' and  a.typename is not null ";
		$res = $this -> get_one ( $sqls );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lt.id)" ];
		}
		if ( $count > 0 )
		{
			$total_pages = ceil ( $count / $limit );
		} else
		{
			$total_pages = 0;
		}
		if ( $page > $total_pages )
		{
			$page = $total_pages;
		}
		$start = $limit * $page - $limit;
		if ( $start < 0 )
		{
			$start = 0;
		}
		$i = 0;
		$responce -> records = $count;
		$responce -> page = $page;
		$responce -> total = $total_pages;
		$sql = "SELECT lt.id,lt.typename,lt.pid,lt.administrator,lt.sortid,lt.styleid,lt.deptname,lt.adminname
		   				   ,lt.rand_key, a.id as lid ,a.typename as lname,b.typename as tname,b.id as tid
		   				   ,max(c.sortid) as maxs,min(c.sortid) as mins
	   	        FROM lore_type lt left join lore_type   a  on  (a.id=lt.pid  and a.flag='2' )
							    	 left join lore_type   b  on  (b.id=a.pid   and  b.id in ($ids)  and b.flag='1'   )
							    	 left join lore_type   c  on  (c.pid=lt.pid) 
			    WHERE 1  and lt.flag='3' and  a.typename is not null  group by lt.id 
                ORDER BY " . $sidx . " " . $sord . " ,a.typename asc,lt.sortid asc LIMIT $start," . $limit . "";
		$query = $this -> query ( $sql );
		while ( ( $row = $this -> fetch_array ( $query ) ) != false )
		{
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "id" ] ,
			$row[ "tname" ] ,
			$row[ "lname" ] ,
			$row[ "typename" ] ,
			$row[ "adminname" ] ,
			$row[ "deptname" ] ,
			$row[ "sortid" ] ,
			$row[ "styleid" ] ,
			$row[ "tid" ] ,
			$row[ "lid" ] ,
																	'' , 
			$row[ "maxs" ] ,
			$row[ "mins" ]
			) );
			$i ++ ;
		}
		return json_encode ( $responce );
	}
	/**
	 * 增加标签
	 * @return Boolean
	 */
	function model_addtag ( $data )
	{
		return $this -> create ( array (
										'flag' => 3 , 
										'sortid' => $data[ 'sortid' ] , 
										'pid' => $data[ 'levelname' ] , 
										'level' => $data[ 'styleid' ] , 
										'typename' => $data[ 'tagname' ] , 
										'administrator' => trim ( $data[ 'administrator' ] , ',' ) , 
										'deptid' => trim ( $data[ 'deptment' ] , ',' ) , 
										'adminname' => trim ( $data[ 'adminname' ] , ',' ) , 
										'deptname' => trim ( $data[ 'deptname' ] , ',' ) 
		) );
	}
	/**
	 * 修改标签
	 * @return String
	 */
	function model_edittag ( $data )
	{
		return $this -> update ( array (
										'id' => $data[ 'id' ] , 
										'rand_key' => $data[ 'key' ] 
		) , array (
					'flag' => 3 , 
					'sortid' => $data[ 'sortid' ] , 
					'pid' => $data[ 'levelname' ] , 
					'level' => $data[ 'styleid' ] , 
					'typename' => $data[ 'tagname' ] , 
					'administrator' => trim ( $data[ 'administrator' ] , ',' ) , 
					'deptid' => trim ( $data[ 'deptment' ] , ',' ) , 
					'adminname' => trim ( $data[ 'adminname' ] , ',' ) , 
					'deptname' => trim ( $data[ 'deptname' ] , ',' ) 
		) );

	}
	/**
	 * 标签排序
	 * @return String
	 */
	function model_tagsort ( $data , $sort )
	{
		return $this -> update ( array (
										'id' => $data[ 'id' ] , 
										'rand_key' => $data[ 'rand_key' ] 
		) , array (
					'sortid' => $sort 
		) );

	}
	/**
	 * 标签排序
	 * @return String
	 */
	function model_levelsort ( $pid = 0 )
	{
		if ( $pid )
		{
			$sql = "select max(sortid) from lore_type where pid='$pid' and flag='3'";
			$res = $this -> get_one ( $sql );
			if ( $res )
			{
				return $res[ 'max(sortid)' ] + 1;
			} else
			{
				return 1;
			}
		}
	}
}
?>