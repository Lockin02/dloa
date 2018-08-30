<?php
class model_lore_index extends model_base
{
	public $dbids;
	public $vnum;
	public $upfilebase;
	public $page;
	public $num;
	public $start;
	public $db;
	public $gbl;
	public $noticepage; //公告分页
	public $toppage; //置顶分页
	public $cexceldata;

	function __construct ( )
	{
		parent :: __construct ( );
		$this -> db = new mysql ( );
		$this -> page = intval ( $_GET[ 'page' ] ) ? intval ( $_GET[ page ] ) : 1;
		$this -> start = ( $this -> page == 1 ) ? 0 : ( $this -> page - 1 ) * pagenum;
		$this -> num = intval ( $_GET[ 'num' ] ) ? intval ( $_GET[ 'num' ] ) : false;
		$this -> tbl_name = 'lore_type';
		$this -> vnum = 0;
		$this -> noticepage = 1;
		$this -> toppage = 10;
		$this -> upfilebase = "attachment/lore/";
		if ( file_exists ( $this -> upfilebase ) == "" )
		{
			@mkdir ( $this -> upfilebase , 511 );
		}
		$this -> cexceldata = array ();
	}

	/**
	 *首页列表数据源
	 */
	function model_listdata ( )
	{
		if ( stristr ( $_SERVER[ "HTTP_USER_AGENT" ] , "Firefox" ) )
		{
			$_GET = mb_iconv ( $_GET );
		}
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$tags = trim ( $_GET[ 'tags' ] );
		$tag2 = trim ( $_GET[ 'tag2' ] );
		$tag3 = trim ( $_GET[ 'tag3' ] );
		$dts = trim ( $_GET[ 'dts' ] );
		$levs = trim ( $_GET[ 'levs' ] );
		$tagsearch = trim ( $_GET[ 'tagsearch' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$userid = trim ( $_GET[ 'userid' ] );
		$pkid = trim ( $_GET[ 'pid' ] );
		$sqlstr = "";
		$arrS = array ();
		if ( $tags )
		{
			
			$arrS = explode ( ',' , $tags );
			$arrS = array_unique ( array_filter ( $arrS ) );
			if ( is_array ( $arrS ) )
			{
				foreach ( $arrS as $value )
				{
					$sqlstr .= " and lc.typeid like '%" . $value . "%' ";
				}
				$sqlstr = ltrim ( $sqlstr , ' and' );
				if ( trim ( $sqlstr ) )
				{
					$sqlstr = " and (" . $sqlstr . " ) ";
				}
			}
		}
		if ( $dts )
		{
			switch ( $dts )
			{
				case '7d' :
					{
						$sqlstr .= " and DATE_SUB(CURDATE(),  INTERVAL 7 DAY) <= date(lc.updatedt) ";
					}
					break;
				case '1m' :
					{
						$sqlstr .= " and DATE_SUB(CURDATE(),  INTERVAL 1 MONTH) <= date(lc.updatedt) ";
					}
					break;
				case '3m' :
					{
						$sqlstr .= " and DATE_SUB(CURDATE(),  INTERVAL 3 MONTH) <= date(lc.updatedt) ";
					}
					break;
				case '6m' :
					{
						$sqlstr .= " and DATE_SUB(CURDATE(),  INTERVAL 6 MONTH) <= date(lc.updatedt) ";
					}
					break;
				case '1y' :
					{
						$sqlstr .= " and DATE_SUB(CURDATE(),  INTERVAL 12 MONTH) <= date(lc.updatedt) ";
					}
					break;
			}
		}
		if ( $levs )
		{
			$sqlstr .= " and lc.levels ='$levs' ";
		}
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' )";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}
		if ( $tagsearch && $tagsearch != '-1' )
		{
			$sqltag = str_replace ( ',' , "%' or lc.typeid like '% " , $tagsearch );
			$sqlstr .= "and ( lc.typeid like '%" . $sqltag . "%') ";
		}
		if ( $userid )
		{
			$sqlstr .= " and lc.creater = '$userid' ";
		}
		if ( $pkid )
		{
			$sqlstr .= " and lc.pid = '$pkid' ";
		}
		$sql = " SELECT count(lc.cid)
                 FROM lore_article lc 
                 where  1  $sqlstr and lc.status=3";
		$res = $this -> db -> get_one ( $sql );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lc.cid)" ];
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
		$sql = " SELECT count(lc.cid)
                 FROM lore_article lc 
                 where  1  and lc.status=3 and class='3' LIMIT 0,$this->noticepage";

		$res = $this -> db -> get_one ( $sql );
		if (  ! empty ( $res ) )
		{
			$count1 = $res[ "count(lc.cid)" ];
			if ( $count1 > 1 )
			{
				$count1 = 1;
			}
		}
		$sql = " SELECT count(lc.cid)
                 FROM lore_article lc 
                 where  1 and lc.status=3  and class='2' LIMIT 0,$this->toppage";
		$res = $this -> db -> get_one ( $sql );
		if (  ! empty ( $res ) )
		{
			$count2 = $res[ "count(lc.cid)" ];
			if ( $count2 > 10 )
			{
				$count2 = 10;
			}
		}
		$counts = 0;
		if ( $page < 2 )
		{
			$counts = $count2 + $count1;
		}
		$start = $limit * $page - $limit - $counts;
		if ( $start < 0 )
		{
			$start = 0;
		}
		$i = 0;
		$responce -> records = $count;
		$responce -> page = $page;
		$responce -> total = $total_pages;
		if ( $page < 2 )
		{
			$sqlg = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
							,lc.reterms,lc.levels,lc.status,lc.browse,lc.rand_key,lc.class,s.referconut,u.user_name
	                 FROM lore_article lc  left join user u on(lc.creater=u.user_id)
	                 						left join ( SELECT count(rid) as referconut,cid
										                 FROM lore_review 
										                 where  1 group by cid ) s on s.cid=lc.cid 
	                 where 1  $sqlstr  and lc.status=3 and class='3'
	                 ORDER BY " . $sidx . " " . $sord . " ,lc.cid desc LIMIT 0,$this->noticepage";
			//echo $sql;
			$query = $this -> db -> query ( $sqlg );
			$dbid = '';
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				$referconut = $row[ "referconut" ] ? $row[ "referconut" ] : 0;
				$brows = $row[ "browse" ] ? $row[ "browse" ] : 0;
				$dbid .= $row[ "cid" ] . ',';
				$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
				$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
				$row[ "class" ] ,
				$row[ "title" ] ,
				$row[ "user_name" ] . '<br/>' . $row[ "updatedt" ] ,
				//$this->model_referconut ( $row["cid"] ) . ' / ' . $brows,
				$referconut . ' / ' . $brows ,
				$row[ "ascount" ] ,
				$row[ "browse" ] ,
				$row[ "levels" ] ,
				$row[ "status" ] ,
				$row[ "cid" ]
				) );
				$i ++ ;
			}

			$sqlt = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
							,lc.reterms,lc.levels,lc.status,lc.browse,lc.rand_key,lc.class,s.referconut,u.user_name
	                 FROM lore_article lc  left join user u on(lc.creater=u.user_id)
							                 left join ( SELECT count(rid) as referconut,cid
										                 FROM lore_review 
										                 where  1 group by cid ) s on s.cid=lc.cid 
	                 where 1  $sqlstr  and lc.status=3 and class='2' 
	                 ORDER BY " . $sidx . " " . $sord . " ,lc.cid desc LIMIT 0,$this->toppage";
			//echo $sql;
			$query = $this -> db -> query ( $sqlt );
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				$referconut = $row[ "referconut" ] ? $row[ "referconut" ] : 0;
				$brows = $row[ "browse" ] ? $row[ "browse" ] : 0;
				$dbid .= $row[ "cid" ] . ',';
				$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
				$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
				$row[ "class" ] ,
				$row[ "title" ] ,
				$row[ "user_name" ] . '<br/>' . $row[ "updatedt" ] ,
				$referconut . ' / ' . $brows ,
				$row[ "ascount" ] ,
				$row[ "browse" ] ,
				$row[ "levels" ] ,
				$row[ "status" ] ,
				$row[ "cid" ]
				) );

				$i ++ ;
			}
			if ( $dbid )
			{
				$this -> dbids = substr ( $dbid , 0 , strlen ( $dbid ) - 1 );
			}
			if ( $this -> dbids )
			{
				$sqlstr1 = ' and lc.cid  NOT IN(' . $this -> dbids . ') ';
			}
		}
		$sql = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
						,lc.reterms,lc.levels,lc.status,lc.browse,lc.rand_key,lc.class,s.referconut,u.user_name
                 FROM lore_article lc left join user u on(lc.creater=u.user_id)
                 					left join (SELECT count(rid) as referconut,cid
								                 FROM lore_review 
								                 where  1  group by cid) s on s.cid=lc.cid 
                 where 1  $sqlstr  $sqlstr1 and lc.status=3
                 ORDER BY " . $sidx . " " . $sord . " ,lc.cid desc LIMIT $start," . $limit . "";
		//echo $sql;
		$query = $this -> db -> query ( $sql );
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			$referconut = $row[ "referconut" ] ? $row[ "referconut" ] : 0;
			$brows = $row[ "browse" ] ? $row[ "browse" ] : 0;
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "class" ] ,
			$row[ "title" ] ,
			$row[ "user_name" ] . '<br/>' . $row[ "updatedt" ] ,
			$referconut . ' / ' . $brows ,
			$row[ "ascount" ] ,
			$row[ "browse" ] ,
			$row[ "levels" ] ,
			$row[ "status" ] ,
			$row[ "cid" ]
			) );
			$i ++ ;
		}
		return json_encode ( $responce );

	}
	/*
	 * 我的消息类型列表
	 */
	function model_refertlists ( )
	{
		$startA = array (
						"2" => "我的提交" , 
						"3" => "我的发布" , 
						"4" => "草稿箱" , 
						"6" => "回收站" , 
						"7" => "我的收藏" 
						);
						foreach ( $startA as $key => $value )
						{
							$str .= "<option value='" . $key . "'";
							if ( $startType === $key )
							{
								$str .= " selected";
							}
							$str .= ">" . $value . "</option>";
						}
						return $str;
	}
	/*
	 * 标签数组源
	 */
	function model_tagsinfo ( $pid = 0 )
	{
		if ( $pid )
		{
			$sql = "select typename,id,pid,typename from lore_type where  pid='$pid' and flag='2' ORDER BY sortid";
			$query = $this -> db -> query ( $sql );
			$tagI = array ();
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				//$tagI[$row['pid']][$row['id']]=$row['typename'];
				if ( $row[ 'id' ] )
				{
					$sqlsub = "select typename,id,pid,typename from lore_type where pid='" . $row[ 'id' ] . "' and flag='3' ORDER BY sortid";
					$qrysub = $this -> db -> query ( $sqlsub );
					while ( ( $res = $this -> db -> fetch_array ( $qrysub ) ) != false )
					{
						$tagI[ $res[ 'pid' ] ][ 'pid' ] = $row[ 'id' ];
						$tagI[ $res[ 'pid' ] ][ 'typename' ] = $row[ 'typename' ];
						$tagI[ $res[ 'pid' ] ][ 'sub' ][ $res[ 'id' ] ] = $res[ 'typename' ];
					}
				}
			}
		}
		return $tagI;

		/*
		 $sql = "select typename,id,level from lore_type where 1 ";
		 $query = $this->db->query ( $sql );
		 $tagI=array();

		 while ( ($row = $this->db->fetch_array ( $query )) != false )
		 {
		 $tagI[$row['level']][$row['id']]=$row['typename'];
		 }
		 return $tagI;
		 */
	}
	/**
	 * 标题导航列表
	 */
	function model_heardlinks ( $pid )
	{
		if ( $pid )
		{
			$str = "<a href='?model=lore_index&action=main&pid=0'>知识库</a>-> ";
			$aI = $this -> model_heardlinksdata ( $pid );
			if ( is_array ( $aI ) )
			{
				foreach ( $aI as $key => $value )
				{
					$str .= "<a href='?model=lore_index&action=main&pid=" . $value[ 'id' ] . "'>" . $value[ 'typename' ] . "</a> -> ";
				}
			}
			return $str;
		}
	}

	function model_heardlinksdata ( $pid )
	{
		if ( $pid )
		{
			$sqlsub = "select typename,pid,id from lore_type where id='" . $pid . "'";
			$qrysub = $this -> db -> query ( $sqlsub );
			while ( ( $res = $this -> db -> fetch_array ( $qrysub ) ) != false )
			{
				$tagI = $this -> model_heardlinksdata ( $res[ 'pid' ] );
				$tagI[ $res[ 'pid' ] ][ 'pid' ] = $res[ 'pid' ];
				$tagI[ $res[ 'pid' ] ][ 'typename' ] = $res[ 'typename' ];
				$tagI[ $res[ 'pid' ] ][ 'id' ] = $res[ 'id' ];
			}
			return $tagI;
		}

	}
	/*
	 *增加
	 */
	function model_tags ( $tagsI )
	{
		if ( is_array ( $tagsI ) )
		{
			$i = 1;
			foreach ( $tagsI as $key => $keyI )
			{
				++ $i;
			}
			$str .= '<tr><td width="4%" rowspan="' . $i . '" align="center" valign="middle">属 性</td>';
			//$i=0;
			foreach ( $tagsI as $key => $keyI )
			{
				$str .= '<tr><td colspan="4" style="white-space: normal;" ><strong>';
				$str .= $keyI[ 'typename' ] . '：</strong>';
				foreach ( $keyI[ 'sub' ] as $keys => $value )
				{
					$str .= '<input name="tags[]" id="tag' . $key . $keys . '" type="checkbox" value="' . $keys . '" onclick="ontagsall(\'tag' . $key . $keys . '\')" />' . $value . '&nbsp;&nbsp;';
				}
				$str .= '</td>';
			}
			$str .= '</td></tr>';
		}

		return $str;
	}
	function model_tagschecked ( $tagsI , $vkey )
	{
		//$vkeyI=array();
		//$vkeyI=$vkey;
		$i = 1;
		$j = 1;
		if ( is_array ( $tagsI ) )
		{
			foreach ( $tagsI as $key => $keyI )
			{
				++ $i;
			}
			$str .= '<tr ><td width="4%" rowspan="' . $i . '" align="center" valign="middle">属 性</td>';
			$arr = $vkey ? explode ( "," , $vkey ) : false;
			foreach ( $tagsI as $key => $keyI )
			{
				$str .= '<tr><td colspan="4" style="white-space: normal;" ><strong>';
				$str .= $keyI[ 'typename' ] . '：</strong>';
				foreach ( $keyI[ 'sub' ] as $keys => $value )
				{
					$str .= '<input ' . ( $arr && in_array ( $keys , $arr ) ? 'checked' : '' ) . ' name="tags[]" id="tag' . $key . $keys . '" type="checkbox" value="' . $keys . '" onclick="ontagsall(\'tag' . $key . $keys . '\')" />' . $value . '&nbsp;&nbsp;';
				}
				$j ++ ;
				$str .= '</td></tr>';
			}
			$str .= '</tr>';
		}

		return $str;
	}
	function model_tagslink ( $tagsI )
	{
		if ( is_array ( $tagsI ) )
		{
			$i = 0;
			foreach ( $tagsI as $key => $keyI )
			{
				$str .= '<tr><td id="' . $key . '" align="left" class="classtages" >';
				$str .= '<strong style="color: #006699;">' . $keyI[ 'typename' ] . '： </strong>';
				foreach ( $keyI[ 'sub' ] as $keys => $value )
				{
					$str .= '<a id="tags' . $key . $i . '" href="javascript:tags(\'tags' . $key . $i . '\',' . $key . ',' . $keys . ',\'tags' . $key . 's\')"  />' . $value . '</a>&nbsp;&nbsp;';
					$i ++ ;
				}
				$str .= '</td></tr>';
					
			}
		}

		return $str;
	}
	function model_taglist ( $tagsI )
	{
		if ( is_array ( $tagsI ) )
		{
			foreach ( $tagsI as $key => $keyI )
			{
				foreach ( $keyI[ 'sub' ] as $keys => $value )
				{
					$str .= "<option value='$keys'>&nbsp;&nbsp;&nbsp;|--$value</option>";

					//$tags1.=$keys.',';
				}
				$tags1 = substr ( $tags1 , 0 , strlen ( $tags1 ) - 1 );
				$str .= "<option value='$tags1'>" . $keyI[ 'typename' ] . "</option>";
			}

		}

		return $str;
	}
	/**
	 * 被选中关联词 search
	 * Enter description here ...
	 */
	function model_searchrelationed ( $keyid )
	{
		if ( $keyid )
		{
			$sql = "select title,cid from lore_article where 1 and cid in ($keyid) ORDER BY cid";
			$query = $this -> db -> query ( $sql );
			$i = 0;
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				++ $i;
				$str .= '<input name="reterms[]" id="reterms[]" type="checkbox" value="' . $row[ 'cid' ] . '" checked />' . $row[ 'title' ] . '&nbsp;&nbsp;';
				if ( $i % 5 == 0 )
				{
					$str .= '<br>';
				}
			}
		}
		return $str;
	}
	/**
	 * 关联词 search
	 * Enter description here ...
	 */
	function model_searchcorrelation ( )
	{
		$_POST = mb_iconv ( $_POST );
		$rewords = trim ( $_POST[ 'rewords' ] );
		$rwordsI = array ();
		$sqlck = '';
		if ( $rewords )
		{
			$rwordsI = explode ( ' ' , $rewords );
		}
		if ( is_array ( $rwordsI ) )
		{
			foreach ( $rwordsI as $value )
			{
				$sqlck .= " title like'%$value%' or ";
			}
			$sqlck = ' and (' . substr ( $sqlck , 0 , strlen ( $sqlck ) - 4 ) . ')';
		}
		if ( $sqlck )
		{
			$sql = "select title,cid from lore_article where 1 and status='3'  $sqlck";
			//echo $sql;
			$query = $this -> db -> query ( $sql );
			$i = 0;
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				++ $i;
				$str .= '<input name="reterms[]" id="reterms[]" type="checkbox" value="' . $row[ 'cid' ] . '"/>' . $row[ 'title' ] . '&nbsp;&nbsp;';
				if ( $i % 5 == 0 )
				{
					$str .= '<br>';
				}
					
			}
		}
		return $str;
	}
	function model_split ( $arryI )
	{
		$str = '';
		if ( is_array ( $arryI ) )
		{
			foreach ( $arryI as $value )
			{
				$str .= $value . ',';
			}
		}
		return $str;
	}
	/**
	 * 新增文章
	 * Enter description here ...
	 */
	function model_addposts ( )
	{
		$tagI = array ();
		$tagI1 = array ();
		$tagI2 = array ();
		$tagI3 = array ();
		$retermsI = array ();
		$title = trim ( $_POST[ "title" ] );
		$tagI = $_POST[ "tags" ];
		$tagI1 = $_POST[ "tag1" ];
		$tagI2 = $_POST[ "tag2" ];
		$tagI3 = $_POST[ "tag3" ];
		$content = trim ( $_POST[ "body_content" ] );
		$levels = trim ( $_POST[ "levels" ] );
		$doctype = trim ( $_POST[ "doctype" ] );
		$rewords = trim ( $_POST[ "rewords" ] );
		$retermsI = $_POST[ "reterms" ];
		$cflag = $_POST[ "cflag" ];
		$pkid = ( int ) trim ( $_POST[ "pkid" ] );
		$sqlck = '';
		$tag = '';
		$reterms = '';
		$upcount = $_POST[ 'uploader_count' ];
		if ( is_array ( $tagI ) )
		{
			$tag .= $this -> model_split ( $tagI );
		}
		if ( is_array ( $tagI1 ) || is_array ( $tagI2 ) || is_array ( $tagI3 ) )
		{
			$tag .= $this -> model_split ( $tagI1 );
			$tag .= $this -> model_split ( $tagI2 );
			$tag .= $this -> model_split ( $tagI3 );
		}
		if ( is_array ( $retermsI ) )
		{
			$reterms .= $this -> model_split ( $retermsI );
		}
		$tag = substr ( $tag , 0 , strlen ( $tag ) - 1 );
		$reterms = substr ( $reterms , 0 , strlen ( $reterms ) - 1 );
		$fstartus = 1;
		if ( $cflag == 'conform' )
		{
			$fstartus = 2;
		}
		if (  ! empty ( $pkid ) &&  ! empty ( $title ) &&  ! empty ( $content ) &&  ! empty ( $cflag ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "insert into lore_article(typeid,pid,title,content,creater,creatdt
										,updatedt,rewords,reterms,levels,doctype,status)
								 values('$tag','$pkid','$title','$content','" . $_SESSION[ 'USER_ID' ] . "',now()
								       ,now(),'$rewords','$reterms','$levels','$doctype','$fstartus')";
			$this -> db -> query ( $sql );
			$reid = $this -> db -> insert_id ( );
			if (  ! empty ( $reid ) )
			{
				$msg = 1;
				if ( $upcount )
				{
					for ( $i = 0 ; $i < $upcount ; $i ++  )
					{
						$sqls = "insert into lore_upfiles(cid,filesname,fakename,creater,crdt,updt)values('" . $reid . "','" . trim ( $_POST[ "uploader_" . $i . "_name" ] ) . "','" . trim ( $_POST[ "uploader_" . $i . "_tmpname" ] ) . "','" . $_SESSION[ 'USER_ID' ] . "',now(),now())";
						$res = @$this -> db -> query ( $sqls );
					}
				}
				if ( $cflag == 'conform' )
				{
					$this -> model_operation ( '' , '2' , 'sendmail' , $reid );
				}
			} else
			{
				$msg = 2;
			}
		} else
		{
			$msg = 3;
		}
		return $msg;

	}
	/**
	 * 修改文章
	 *
	 */
	function model_editposts ( )
	{
		$tagI = array ();
		$tagI1 = array ();
		$tagI2 = array ();
		$tagI3 = array ();
		$retermsI = array ();
		$title = trim ( $_POST[ "title" ] );
		$tagI = $_POST[ "tags" ];
		$tagI1 = $_POST[ "tag1" ];
		$tagI2 = $_POST[ "tag2" ];
		$tagI3 = $_POST[ "tag3" ];
		$content = trim ( $_POST[ "body_content" ] );
		$levels = trim ( $_POST[ "levels" ] );
		$doctype = trim ( $_POST[ "doctype" ] );
		$rewords = trim ( $_POST[ "rewords" ] );
		$retermsI = $_POST[ "reterms" ];
		$key = trim ( $_POST[ "key" ] );
		$cid = ( int ) trim ( $_POST[ "cid" ] );
		$cflag = $_POST[ "cflag" ];
		$sqlck = '';
		$tag = '';
		$upcount = $_POST[ 'uploader_count' ];
		$reterms = '';
		if ( is_array ( $tagI ) )
		{
			$tag .= $this -> model_split ( $tagI );
		}
		if ( is_array ( $tagI1 ) || is_array ( $tagI2 ) || is_array ( $tagI3 ) )
		{
			$tag .= $this -> model_split ( $tagI1 );
			$tag .= $this -> model_split ( $tagI2 );
			$tag .= $this -> model_split ( $tagI3 );
		}
		if ( is_array ( $retermsI ) )
		{
			$reterms .= $this -> model_split ( $retermsI );
		}

		$tag = substr ( $tag , 0 , strlen ( $tag ) - 1 );
		$reterms = substr ( $reterms , 0 , strlen ( $reterms ) - 1 );
		$fstartus = 1;
		if ( $cflag == 'conform' )
		{
			$fstartus = 2;
		}
		if ( $cflag == 'submission' )
		{
			$fstartus = 3;
		}
		if (  ! empty ( $title ) &&  ! empty ( $content ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "update lore_article set typeid='$tag'
											,title='$title'
											,content='$content'
											,updatedt=now()
											,rewords='$rewords'
											,reterms='$reterms'
											,levels='$levels'
											,doctype='$doctype'
											,status='$fstartus'
					where rand_key='$key'";

			$re = $this -> db -> query ( $sql );
			if (  ! empty ( $re ) )
			{
				$msg = "更新成功!";

				if ( $upcount )
				{
					for ( $i = 0 ; $i < $upcount ; $i ++  )
					{
						$sqls = "insert into
								 lore_upfiles(cid,filesname,fakename,creater,crdt,updt)
								 values('" . $cid . "','" . trim ( $_POST[ "uploader_" . $i . "_name" ] ) . "'
								 		,'" . trim ( $_POST[ "uploader_" . $i . "_tmpname" ] ) . "'
								 		,'" . $_SESSION[ 'USER_ID' ] . "',now(),now())";
						$re = @$this -> db -> query ( $sqls );
					}
				}
				if ( $cflag == 'conform' )
				{
					$this -> model_operation ( '' , '2' , 'sendmail' , $reid );
				} else if ( $cflag == 'submission' )
				{
					$mcontent = "管理员修改了你的文章!";
					$mclass = 16;
					$this -> model_operation ( '' , '3' , 'sendmail' , $reid );
					$sqlas = "insert into lore_message(cid,mcontent,mcrdt,sender,mstatus,mclass)
								 values('" . $cid . "','$mcontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
								       ,'$mclass')";
					$re = $this -> db -> query ( $sqlas );

				}
			} else
			{
				$msg = "更新失败!";
			}
		} else
		{
			$msg = "更新失败 !";
		}
		$str = '<div style="width:98%; text-align: center;">
                        <div style="height: 80px;margin-top:20px; line-height: 80px; text-align: center; color: red;">' . $msg . '</div>
                        <div style="text-align: center; line-height: 30px;">
                        <input type="button" onclick="self.parent.location.reload();" value=" 确 定 " /></div>
             </div>';

		return $str;
	}
	/**
	 * 个人文章管理数据源
	 *
	 */
	function model_referdata ( )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$tag1 = trim ( $_GET[ 'tag1' ] );
		$tag2 = trim ( $_GET[ 'tag2' ] );
		$tag3 = trim ( $_GET[ 'tag3' ] );
		$dts = trim ( $_GET[ 'dts' ] );
		$levs = trim ( $_GET[ 'levs' ] );
		$status = ( int ) trim ( $_GET[ 'status' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$sqlstr = "";
		$key = '';
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' )";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}
		if ( $status && $status != '-1' && $status != '7' )
		{
			$sqlstr .= "and lc.status = '$status'";
		}
		$sqlstr .= "and lc.status in (1,2,3,4,5,6)";
		if ( $status == '7' )
		{
			$sqls = " SELECT count(lc.cid)
                 	  FROM lore_favorites lf left join lore_article lc on (lc.cid=lf.cid)
                 		                     left join user u on (lc.creater=u.user_id)
                      where  1  $sqlstr  and creater='" . $_SESSION[ 'USER_ID' ] . "'";
		} else
		{
			$sqls = " SELECT count(lc.cid)
                 	  FROM lore_article lc  left join user u on (lc.creater=u.user_id)
                      where  1  $sqlstr and creater='" . $_SESSION[ 'USER_ID' ] . "'";
		}

		$res = $this -> db -> get_one ( $sqls );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lc.cid)" ];
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
		if ( $status == '7' )
		{
			$sql = " SELECT  lc.cid,lc.pid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
							,lc.reterms,lc.levels,lc.status,lc.browse,lf.rand_key,lc.class,u.user_name
							,lc.rand_key as keyd,s.referconut
                 	 FROM lore_favorites lf left join lore_article lc on (lc.cid=lf.cid)
                 						left join user u on (lc.creater=u.user_id)
                 						left join ( SELECT count(rid) as referconut,cid
									                 FROM lore_review 
									                 where  1  group by cid) s on s.cid=lc.cid 
                 where 1  $sqlstr  and creater='" . $_SESSION[ 'USER_ID' ] . "'
                 ORDER BY " . $sidx . " " . $sord . " ,lc.status desc LIMIT $start," . $limit . "";
		} else
		{
			$sql = " SELECT  lc.cid,lc.pid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
						,lc.reterms,lc.levels,lc.status,lc.browse,lc.rand_key,lc.class,u.user_name,s.referconut 
                 FROM lore_article lc  left join user u on (lc.creater=u.user_id)
						                 left join ( SELECT count(rid) as referconut,cid
									                 FROM lore_review 
									                 where  1  group by cid) s on s.cid=lc.cid 
                 where 1  $sqlstr  and creater='" . $_SESSION[ 'USER_ID' ] . "'
                 ORDER BY " . $sidx . " " . $sord . " ,lc.status desc LIMIT $start," . $limit . "";
		}
		$query = $this -> db -> query ( $sql );
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			if ( $status == '7' )
			{
				$key = $row[ "keyd" ];
			}
			$referconut = $row[ "referconut" ] ? $row[ "referconut" ] : '0';
			$browse = $row[ "browse" ] ? $row[ "browse" ] : '0';
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "class" ] ,
			$row[ "title" ] ,
			$row[ "user_name" ] . '<br>' . $row[ "updatedt" ] ,
			//$this->model_referconut ( $row["cid"] ) . ' / ' . $browse,
			$referconut . ' / ' . $browse ,
																	'' , 
																	'' , 
			$row[ "updatedt" ] ,
			$row[ "ascount" ] ,
			$row[ "browse" ] ,
			$row[ "levels" ] ,
			$row[ "status" ] ,
			$row[ "cid" ] ,
			$key ,
			$row[ "pid" ]
			) );
			$i ++ ;
		}
		return json_encode ( $responce );
	}
	/**
	 *
	 * 文章管理数据源
	 */
	function model_managerdata ( )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$tag1 = trim ( $_GET[ 'tag1' ] );
		$tag2 = trim ( $_GET[ 'tag2' ] );
		$tag3 = trim ( $_GET[ 'tag3' ] );
		$dts = trim ( $_GET[ 'dts' ] );
		$levs = trim ( $_GET[ 'levs' ] );
		$status = trim ( $_GET[ 'status' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$sqlstr = "";
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' )";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}
		if ( $status && $status != '-1' )
		{
			$sqltag = str_replace ( ',' , "%' or lc.typeid like '% " , $status );
			$sqlstr .= "and ( lc.typeid like '%" . $sqltag . "%') ";

		}
		$sqlstr .= "and lc.status in (2,3,5)";

		$sqls = " SELECT count(lc.cid)
                 FROM lore_article lc 
                 where  1  $sqlstr";
		$res = $this -> db -> get_one ( $sqls );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lc.cid)" ];
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
		$sql = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
						,lc.reterms,lc.levels,lc.status,lc.browse,lc.rand_key,lc.class,u.user_name
						,lc.class,s.referconut,lc.pid
                 FROM lore_article lc  left join user u on (lc.creater=u.user_id) 
                 						left join ( 
							                 SELECT count(rid) as referconut,cid
							                 FROM lore_review 
							                 where  1  group by cid) s on s.cid=lc.cid 
                 where 1  $sqlstr 
                 ORDER BY " . $sidx . " " . $sord . " ,lc.updatedt desc LIMIT $start," . $limit . "";
		$query = $this -> db -> query ( $sql );
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			$referconut = $row[ "referconut" ] ? $row[ "referconut" ] : '0';
			$browse = $row[ "browse" ] ? $row[ "browse" ] : '0';
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "class" ] ,
			$row[ "title" ] ,
			$row[ "user_name" ] . '<br>' . $row[ "updatedt" ] ,
			//$this->model_referconut ( $row["cid"] ) . ' / ' . $browse,
			$referconut . ' / ' . $browse ,
																	'' , 
																	'' , 
			$row[ "updatedt" ] ,
			$row[ "ascount" ] ,
			$row[ "browse" ] ,
			$row[ "levels" ] ,
			$row[ "status" ] ,
			$row[ "cid" ] ,
			$row[ 'class' ] ,
			$row[ 'pid' ]
			) );
			$i ++ ;
		}
		return json_encode ( $responce );
	}
	/**
	 *
	 * @param  $key       rand_key
	 * @param  $ops		     操作类型(1,2,3,4,5,6,7)
	 * @param  $sendmails E-main
	 * @param  $ids 	  文章ID
	 */
	function model_operation ( $key = '' , $ops = '' , $sendmails = '' , $ids = '' )
	{
		if (  ! $key )
		{
			$key = $_POST[ 'rand_key' ];
		}
		$op = ( int ) $_POST[ 'op' ];
		$sendmail = $_POST[ 'flag' ];
		$senderI = array ();
		$sendstr = array ();

		if ( $ids )
		{
			$sqlcon = " and la.cid='$ids'";
		} else
		{
			$sqlcon = " and la.rand_key='$key'";
		}
		if ( $ops )
		{
			$op = $ops;
		}
		if ( $sendmails )
		{
			$sendmail = $sendmails;
		}
		if ( (  ! empty ( $key ) ||  ! empty ( $ids ) ) &&  ! empty ( $op ) )
		{
			$sql = "select la.typeid,la.title,la.creater,u.EMAIL,u.USER_NAME
					from lore_article la left join user u on (u.USER_ID=la.creater) 
					where 1  $sqlcon ";
			$re = $this -> db -> get_one ( $sql );
			if (  ! empty ( $re ) &&  ! empty ( $re[ 'typeid' ] ) && ( $op == 2 || $op == 7 ) )
			{
				$sqls = "select administrator,pid,MIN(pid) as mpid
						 from lore_type where 1 and id in (" . $re[ 'typeid' ] . ") group by id";
				$qcontent = $this -> db -> query ( $sqls );
				$user_arr = array ();
				while ( ( $res = $this -> db -> fetch_array ( $qcontent ) ) != false )
				{
					if ( $res[ 'pid' ] == $res[ 'mpid' ] )
					$user_arr = array_merge ( $user_arr , explode ( ',' , $res[ 'administrator' ] ) );
				}
				if ( $user_arr )
				{
					$gl = new includes_class_global ( );
					$semail = new includes_class_sendmail ( );
					$Address = $gl -> get_email ( array_unique ( $user_arr ) );
					//print_r($Address);
					if ( $op == 7 )
					{
						$flag = $semail -> send ( $re[ 'USER_NAME' ] . '发布的文章被纠错' , $re[ 'USER_NAME' ] . '发布的文章' . $re[ 'title' ] . '被' . $this -> model_username ( ) . '纠错' , $Address );
					} else
					{
						$flag = $semail -> send ( $re[ 'USER_NAME' ] . '发布文章审核通知' , $re[ 'USER_NAME' ] . '发表了' . $re[ 'title' ] . '文章请求审核' , $Address );
					}
				}
			}
			if ( $op == 1 || $op == 3 || $op == 5 || $op == 6 )
			{
				$gls = new includes_class_global ( );
				$semails = new includes_class_sendmail ( );
				$Address = $gls -> get_email ( $re[ 'creater' ] );
				$flag = $semails -> send ( $re[ 'USER_NAME' ] . '你的文章被操作' , $re[ 'USER_NAME' ] . '发表了' . $re[ 'title' ] . '文章被操作请查看消息' , $Address );
			}
			if ( $op != 7 )
			{
				$sql = "update lore_article set status='$op' where rand_key='" . $key . "'";
				$re = $this -> db -> query ( $sql );
				if ( $re )
				{
					$msg = "操作成功!";
				} else
				{
					$msg = "操作失败!";
				}
			}
		}

		return $msg;
	}
	/**
	 * 文章预览
	 *
	 */
	function model_viewthreaddata ( )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$tag1 = trim ( $_GET[ 'tag1' ] );
		$tag2 = trim ( $_GET[ 'tag2' ] );
		$tag3 = trim ( $_GET[ 'tag3' ] );
		$dts = trim ( $_GET[ 'dts' ] );
		$levs = trim ( $_GET[ 'levs' ] );
		$tagsearch = ( int ) trim ( $_GET[ 'tagsearch' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$key = trim ( $_GET[ 'key' ] );
		$sqlstr = "";
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' )";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}
		if ( $tagsearch && $tagsearch != '-1' )
		{
			$sqlstr .= "and  lc.typeid like '%" . $tagsearch . "%'";
		}

		$sqls = " SELECT count(lr.rid)
                 FROM lore_review lr left join lore_article lc on (lr.cid=lc.cid)
                 where  1  $sqlstr  and lc.rand_key='$key'";

		$res = $this -> db -> get_one ( $sqls );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lr.rid)" ];
		}
		if ( $count > 0 )
		{
			$total_pages = ceil ( ( $count + 2 ) / $limit );
		} else
		{
			$total_pages = 0;
		}
		if ( $page > $total_pages )
		{
			$page = $total_pages;
		}
		if ( $page < 2 )
		{
			$start = $limit * $page - $limit;
		} else
		{
			$start = $limit * $page - $limit - 2;
		}
		if ( $start < 0 )
		{
			$start = 0;
		}
		$i = 0;
		$cnum = 1;
		$responce -> records = $count;
		$responce -> page = $page;
		$responce -> total = $total_pages;
		$uflag = false;
		if ( $this -> model_admin ( ) === 0 || $this -> model_admin ( ) != '' )
		{
			$uflag = true;
		}
		$user_arr = array ();
		$auflag = false;
		if ( $page < 2 )
		{
			$i ++ ;
			$sqlcontent = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creatdt,lc.updatedt,lc.rewords
							 	,lc.reterms,lc.levels,lc.status,lc.browse,lr.rclass,u.user_name,lc.creater
							  	,lr.rcontent,lr.rcreatdt,lc.rand_key,b.sarticlecount,c.favoritesconut
							  	,d.assessment,s.referconut,lc.doctype
	                        FROM lore_article lc left join lore_review lr on lr.cid=lc.cid
	                 				     left join user u on lc.creater=u.user_id
	                 				     left join (select count(cid) as sarticlecount, creater
	                 				                 from lore_article 
	                 				                 where 1 and status='3' group by creater) b on b.creater=lc.creater  
	                 				     left join (SELECT count(fid) as favoritesconut,cid
									                FROM lore_favorites 
									                group by cid) c on c.cid=lc.cid
									     left join (SELECT us.user_name as assessment,lm.cid
									                FROM lore_message lm left join user us on lm.sender=us.user_id
									                where 1 order by lm.mcrdt desc ) d on d.cid=lc.cid 
									     left join (SELECT count(rid) as referconut,cid
									                FROM lore_review 
									                where  1  group by cid) s on s.cid=lc.cid                                  
	                 		where 1  $sqlstr  and lc.rand_key='$key'
	                 		ORDER BY " . $sidx . " " . $sord . " ,lr.rid desc LIMIT 0,1";
			//echo $sqlcontent;
			$qcontent = $this -> db -> query ( $sqlcontent );
			while ( ( $re = $this -> db -> fetch_array ( $qcontent ) ) != false )
			{
				$responce -> rows[ '0' ][ 'id' ] = $re[ "cid" ];
				$responce -> rows[ '0' ][ 'cell' ] = un_iconv ( array (
				$re[ "rclass" ] ,
				$re[ "title" ] ,
				$re[ "user_name" ] ,
				$re[ "creatdt" ] ,
				$re[ "referconut" ] ,
				//$this->model_referconut ( $re["cid"] ),
				$re[ "updatedt" ] ,
				$re[ "browse" ] ,
				$re[ "levels" ] ,
				$re[ "status" ] ,
																		'1' , 
																		'A' , 
																		'' , 
																		'' , 
																		'' , 
																		'' , 
				$uflag ,
				$auflag ,
																		'NULL' , 
				$re[ "creater" ]
				) );
				$responce -> rows[ '1' ][ 'id' ] = $re[ "rand_key" ];
				$responce -> rows[ '1' ][ 'cell' ] = un_iconv ( array (
				$re[ "rclass" ] ,
				$re[ "content" ] ,
				$re[ "user_name" ] ,
				$re[ "rcreatdt" ] ,
				$re[ "ascount" ] ,
				$re[ "updatedt" ] ,
				$re[ "browse" ] ,
				$re[ "levels" ] ,
				$re[ "status" ] ,
																		'2' , 
																		"H" , 
				$this -> model_favoritesconut ( $re[ "cid" ] ) ,
				$this -> model_ahref ( $re[ "reterms" ] ) ,
				$this -> model_upfileshref ( $re[ "cid" ], $re[ "doctype" ]) ,
				$this -> model_sarticlecount ( $re[ "creater" ] ) ,
				$uflag ,
				$auflag ,
				$re[ "assessment" ] ,
				$re[ "creater" ] ,
				$re[ "doctype" ]
				) );
				$i ++ ;
					
			}
		}

		$sql = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creatdt,lc.updatedt,lc.rewords,lc.creater
						,lc.reterms,lc.levels,lc.status,lc.browse,lr.rand_key,lr.rclass,u.user_name
						,lr.rcontent,lr.rcreatdt,lr.rid,lr.rstatus,b.sarticlecount,lr.rcreater
                 FROM 
                 		lore_review lr 
                 		left join lore_article lc on (lr.cid=lc.cid)
                 		left join user u on (lr.rcreater=u.user_id) 
                 		left join (select count(cid) as sarticlecount, creater
	                 				                 from lore_article 
	                 				                 where 1 and status='3'  group by creater) b on b.creater=lc.creater  
                 where 1  $sqlstr  and lc.rand_key='$key'
                 ORDER BY " . $sidx . " " . $sord . " ,lr.rid asc LIMIT $start," . $limit . "";
		//echo $sql;
		$query = $this -> db -> query ( $sql );
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			array_push ( $user_arr , $row[ 'rcreater' ] );
			$user_arr = array_unique ( $user_arr );
			if ( array_search ( $_SESSION[ 'USER_ID' ] , $user_arr , true ) === 0 || array_search ( $_SESSION[ 'USER_ID' ] , $user_arr , true ) != '' )
			{
				$auflag = true;
			}
			if ( $page > 1 )
			{
				$amttot = $cnum + ( ( $page - 1 ) * $limit - 2 );
			} else
			{
				$amttot = $cnum;
			}
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "rclass" ] ,
			$row[ "rcontent" ] ,
			$row[ "user_name" ] ,
			$row[ "rcreatdt" ] ,
			$row[ "ascount" ] ,
			$row[ "updatedt" ] ,
			$row[ "browse" ] ,
			$row[ "levels" ] ,
			$row[ "rstatus" ] ,
			'3' , 
			$amttot ,
			'' , 
			'' , 
			'' , 
			$row[ "sarticlecount" ] ,
			$uflag ,
			$auflag ,
			'NULL' , 
			$re[ "creater" ],
			'NULL' 
			) ); //$this->model_sarticlecount($row["creater"])


			$cnum ++ ;
			$i ++ ;
		}

		return json_encode ( $responce );
	}
	/**
	 * 发表评论
	 * Enter description here ...
	 */
	function model_addreview ( )
	{
		$_POST = mb_iconv ( $_POST );
		$scontent = trim ( $_POST[ 'scontent' ] );
		$sby = trim ( $_POST[ 'sby' ] );
		if (  ! empty ( $scontent ) &&  ! empty ( $sby ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "insert into lore_review(cid,rcontent,rcreatdt,rcreater,rstatus,rclass)
								 values('" . $sby . "','$scontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
								       ,'1')";
			$re = $this -> db -> query ( $sql );
			if (  ! empty ( $re ) )
			{
				$msg = "发表成功!";
			} else
			{
				$msg = "发表失败!";
			}
		} else
		{
			$msg = "发表失败 !";
		}
		return $msg;
	}
	/**
	 * 更新评论
	 * Enter description here ...
	 * @return string
	 */
	function model_updatereview ( )
	{
		$_POST = mb_iconv ( $_POST );
		$scontent = trim ( $_POST[ 'scontent' ] );
		$sby = trim ( $_POST[ 'skey' ] );
		//print_r ( $_POST );
		if (  ! empty ( $scontent ) &&  ! empty ( $sby ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "update lore_review set rcontent='$scontent'
										  ,rupdatedt=now()
							where rand_key='$sby'";
			$re = $this -> db -> query ( $sql );
			if ( $re )
			{
				$msg = "更新成功!";
			} else
			{
				$msg = "更新失败!";
			}
		} else
		{
			$msg = "更新失败 !";
		}
		return $msg;
	}
	/**
	 * 增加消息
	 * Enter description here ...
	 */
	function model_adoperation ( )
	{
		$_POST = mb_iconv ( $_POST );
		$listid = trim ( $_REQUEST[ 'listid' ] );
		$listI = array ();
		$listids = '';
		$listI = explode ( ',' , $listid );
		$listI = array_unique ( array_filter ( $listI ) );
		if ( is_array ( $listI ) )
		{
			foreach ( $listI as $value )
			{
				$listids .= "'" . $value . "',";
			}
			$listids = rtrim ( $listids , ',' );
		}
		$sqls = '';
		$op = trim ( $_POST[ 'op' ] );
		if ( $op == 2 )
		{
			$mclass = 10;
			$mcontent = '你的信息属不正当的关键字';
		} else
		{
			$mclass = 11;
			$mcontent = '你的文章被操作';
		}
		if (  ! empty ( $listids ) &&  ! empty ( $op ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "update lore_review set rstatus='$op'
							where rand_key in ($listids)";
			$re = $this -> db -> query ( $sql );
			if ( $re )
			{
				$msg = "更新成功!";
				$sql = "select cid
				        from lore_review     
						where rand_key in ($listids)";
				$query = $this -> db -> query ( $sql );
				while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
				{
					$sqls = "insert into lore_message(cid,mcontent,mcrdt,sender,mstatus,mclass)
								 values('" . $row[ 'cid' ] . "','$mcontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
								       ,'$mclass')";
					$re = $this -> db -> query ( $sqls );
				}
					
			} else
			{
				$msg = "更新失败!";
			}
		} else
		{
			$msg = "更新失败 !";
		}
		return $msg;
	}
	/**
	 * 删除消息
	 * Enter description here ...
	 */
	function model_deladop ( )
	{
		$_POST = mb_iconv ( $_POST );
		$listid = trim ( $_REQUEST[ 'listid' ] );
		$listids = '';
		$listI = explode ( ',' , $listid );
		$listI = array_unique ( array_filter ( $listI ) );
		if ( is_array ( $listI ) )
		{
			foreach ( $listI as $value )
			{
				$listids .= "'" . $value . "',";
			}
			$listids = rtrim ( $listids , ',' );
		}
		if (  ! empty ( $listids ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "delete  from  lore_review
			        where  rand_key in ($listids)";
			//echo $sql;
			$re = $this -> db -> query ( $sql );
			if ( $re )
			{
				$msg = "操作成功!";
			} else
			{
				$msg = "操作失败!";
			}
		} else
		{
			$msg = "操作失败 !";
		}
		return $msg;
	}
	/**
	 * 增加纠错
	 * Enter description here ...
	 */
	function model_report ( )
	{
		$_POST = mb_iconv ( $_POST );
		$rcontent = trim ( $_POST[ 'rcontent' ] );
		$sby = trim ( $_POST[ 'sby' ] );
		$key = trim ( $_POST[ 'key' ] );
		if (  ! empty ( $rcontent ) &&  ! empty ( $sby ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "insert into lore_report(cid,pcontent,pcreatdt,preporter,pstatus,plevels)
								 values('" . $sby . "','$rcontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
								       ,'1')";
			$re = $this -> db -> query ( $sql );
			if (  ! empty ( $re ) )
			{
				$msg = "纠错成功!";
				$this -> model_operation ( $key , '7' , '' , '' );
			} else
			{
				$msg = "纠错失败!";
			}
		} else
		{
			$msg = "纠错失败 !";
		}
		return $msg;

	}
	/**
	 * 增加收藏
	 * Enter description here ...
	 */
	function model_favorites ( )
	{
		$_POST = mb_iconv ( $_POST );
		$fcontent = trim ( $_POST[ 'fcontent' ] );
		$sby = trim ( $_POST[ 'sby' ] );
		if (  ! empty ( $sby ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "SELECT fid
	                 FROM lore_favorites 
	                 where  1  and cid='$sby' and freporter='" . $_SESSION[ 'USER_ID' ] . "'";
			$res = $this -> db -> get_one ( $sql );
			if ( empty ( $res ) )
			{
				$sql = "insert into lore_favorites(cid,fcontent,fcreatdt,freporter,fstatus,flevels)
									 values('" . $sby . "','$fcontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
									       ,'1')";
				$re = $this -> db -> query ( $sql );
				if (  ! empty ( $re ) )
				{
					$msg = "收藏成功!";
				} else
				{
					$msg = "收藏失败!";
				}
			} else
			{
				$msg = "你已收藏此文章 !";
			}
		} else
		{
			$msg = "收藏失败 !";
		}
		return $msg;

	}
	/**
	 * 收藏统计
	 * Enter description here ...
	 * @param unknown_type $key ID
	 */
	function model_favoritesconut ( $key )
	{
		$count = 0;
		if ( $key )
		{
			$sqls = " SELECT count(fid)
	                 FROM lore_favorites 
	                 where  1  and cid='$key'";

			$res = $this -> db -> get_one ( $sqls );
			if (  ! empty ( $res ) )
			{
				$count = $res[ "count(fid)" ];
			}
		}
		return $count;
	}
	/**
	 * 回复统计
	 * Enter description here ...
	 * @param unknown_type $key ID
	 */
	function model_referconut ( $key )
	{
		$count = 0;
		if ( $key )
		{
			$sqls = " SELECT count(rid)
	                 FROM lore_review 
	                 where  1  and cid='$key'";

			$res = $this -> db -> get_one ( $sqls );
			if (  ! empty ( $res ) )
			{
				$count = $res[ "count(rid)" ];
			}
		}
		return $count;
	}
	/**
	 * 文章信息
	 * Enter description here ...
	 * @param unknown_type $key  rand_key
	 */
	function model_articleinfo ( $key = 0 )
	{
		$articleI = array ();
		if ( $key )
		{
			$sql = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
							,lc.reterms,lc.levels,lc.status,lc.browse,lc.rand_key,lc.class,lc.doctype
	                 FROM lore_article lc 
		             where 1 and lc.rand_key='$key'";
			$res = $this -> db -> get_one ( $sql );
			if (  ! empty ( $res ) )
			{
				$articleI[ 'cid' ] = $res[ "cid" ];
				$articleI[ 'typeid' ] = $res[ "typeid" ];
				$articleI[ 'title' ] = $res[ "title" ];
				$articleI[ 'content' ] = $res[ "content" ];
				$articleI[ 'creater' ] = $res[ "creater" ];
				$articleI[ 'creatdt' ] = $res[ "creatdt" ];
				$articleI[ 'updatedt' ] = $res[ "updatedt" ];
				$articleI[ 'rewords' ] = $res[ "rewords" ];
				$articleI[ 'reterms' ] = $res[ "reterms" ];
				$articleI[ 'levels' ] = $res[ "levels" ];
				$articleI[ 'status' ] = $res[ "status" ];
				$articleI[ 'browse' ] = $res[ "browse" ];
				$articleI[ 'rand_key' ] = $res[ "rand_key" ];
				$articleI[ 'class' ] = $res[ "class" ];
				$articleI[ 'doctype' ] = $res[ "doctype" ];
			}
		}
		return $articleI;

	}
	/**
	 * 增加消息
	 * Enter description here ...
	 */
	function model_addmessage ( )
	{
		$_POST = mb_iconv ( $_POST );
		$mcontent = trim ( $_POST[ 'mcontent' ] );
		$key = trim ( $_POST[ 'key' ] );
		$class = trim ( $_POST[ 'mclass' ] );
		if (  ! empty ( $mcontent ) &&  ! empty ( $key ) &&  ! empty ( $class ) &&  ! empty ( $_SESSION[ 'USER_ID' ] ) )
		{
			$sql = "insert into lore_message(cid,mcontent,mcrdt,sender,mstatus,mclass)
								 values('" . $key . "','$mcontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
								       ,'$class')";
			$re = $this -> db -> query ( $sql );
			if (  ! empty ( $re ) )
			{
				$msg = "操作成功!";
			} else
			{
				$msg = "操作失败!";
			}
		} else
		{
			$msg = "操作失败 !";
		}
		return $msg;

	}
	function model_delfer ( )
	{
		$_POST = mb_iconv ( $_POST );
		$key = $_POST[ 'rand_key' ];
		if (  ! empty ( $key ) )
		{
			$sql = "select cid
			        from  lore_article  
			        where  rand_key='" . $key . "'";
			$re = $this -> db -> get_one ( $sql );
			if ( empty ( $re[ 'cid' ] ) )
			{
				$msg = "已删除!";
			} else
			{
				$sql = "delete from lore_article  where rand_key='" . $key . "'";
				$re = $this -> db -> query ( $sql );
				if ( $re )
				{
					$msg = "删除成功!";
				} else
				{
					$msg = "删除失败!";
				}
			}
		} else
		{
			$msg = "删除失败 !";
		}
		return $msg;

	}
	/**
	 * 消息数据源
	 * Enter description here ...
	 */
	function model_messagedata ( )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$tag1 = trim ( $_GET[ 'tag1' ] );
		$tag2 = trim ( $_GET[ 'tag2' ] );
		$tag3 = trim ( $_GET[ 'tag3' ] );
		$dts = trim ( $_GET[ 'dts' ] );
		$levs = trim ( $_GET[ 'levs' ] );
		$status = ( int ) trim ( $_GET[ 'status' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$sqlstr = "";
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' )";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}
		$pids = $this -> checkmessage ( );
		if ( $pids )
		{
			$sqlstr .= "and lm.mstatus in (1,2) and (lc.creater='" . $_SESSION[ 'USER_ID' ] . "' or pid in ($pids))";
		} else
		{
			$sqlstr .= "and lm.mstatus in (1,2) and  lc.creater='" . $_SESSION[ 'USER_ID' ] . "'";
		}
		$sqls = "SELECT count(lm.mid)
                 FROM lore_message lm  left join user u on (lm.sender=u.user_id)
                 			           left join lore_article lc on (lc.cid=lm.cid)
                 where 1  $sqlstr ";
		$res = $this -> db -> get_one ( $sqls );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lm.mid)" ];
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
		$sql = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
						,lc.reterms,lc.levels,lc.status,lc.browse,lc.class,u.user_name,lc.pid
						,lm.mcontent,lm.mstatus,lm.mclass,lm.mcrdt,lm.rand_key,lc.rand_key as rand_keys
                 FROM lore_message lm  left join user u on (lm.sender=u.user_id)
                 			           left join lore_article lc on (lc.cid=lm.cid)
                 where 1  $sqlstr 
                 ORDER BY " . $sidx . " " . $sord . " ,lm.mcrdt desc LIMIT $start," . $limit . "";
		$query = $this -> db -> query ( $sql );
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "class" ] ,
			$row[ "title" ] ,
			$row[ "mcontent" ] ,
			$row[ "mclass" ] ,
			$row[ "mcrdt" ] ,
			$row[ "user_name" ] ,
			'' , 
			$row[ "levels" ] ,
			$row[ "mstatus" ] ,
			$row[ "cid" ] ,
			$row[ "rand_keys" ] ,
			$row[ "pid" ]
			) );
			$i ++ ;
		}
		return json_encode ( $responce );
	}
	/**
	 * 文章操作
	 */
	function model_messageop ( )
	{
		$key = $_POST[ 'rand_key' ] ? $_POST[ 'rand_key' ] : $_GET[ 'rand_key' ];
		$op = ( int ) $_POST[ 'op' ] ? $_POST[ 'op' ] : $_GET[ 'op' ];
		if (  ! empty ( $key ) )
		{
			$sql = "select mid from lore_message where 1 and mstatus='$op' and rand_key='$key'";
			$re = $this -> db -> get_one ( $sql );
			if ( empty ( $re ) )
			{
				$sql = "update lore_message set mstatus='$op' where rand_key='" . $key . "'";
				$re = $this -> db -> query ( $sql );
				if ( $re )
				{
					$msg = "操作成功!";
				} else
				{
					$msg = "操作失败!";
				}
			} else
			{
				$msg = "操作失败";
			}
		} else
		{
			$msg = "操作失败 !";
		}
		return $msg;
	}

	function model_mymessages ( )
	{
		$sql = "select count(lm.mid)
				from lore_message lm  left join lore_article lc on (lc.cid=lm.cid)
				where 1 and lm.mstatus='1' and lc.creater='" . $_SESSION[ 'USER_ID' ] . "'";
		$re = $this -> db -> get_one ( $sql );
		if (  ! empty ( $re ) )
		{
			$str = $re[ 'count(lm.mid)' ];
		}
		return $str;
	}
	function model_ahref ( $reterms )
	{
		if ( $reterms )
		{
			$sql = "select title,cid,rand_key from lore_article where 1 and status='3' and cid in ($reterms)";
			$query = $this -> db -> query ( $sql );
			$i = 1;
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				$str .= '<span class="herfwidth"><a href="?model=lore_index&action=viewthread&sby=' . $row[ 'cid' ] . '&key=' . $row[ 'rand_key' ] . '" >' . $row[ 'title' ] . '</a></span>';
				if ( $i % 2 == 0 )
				{
					$str .= '<br>';
				}
				++ $i;
			}
		}
		return $str;
	}
	function model_randherf ( $key )
	{
		$rwordsI = array ();
		$sqlck = '';
		if ( $key )
		{
			$sql = "select title,cid,rewords from lore_article where 1 and status='3' and rand_key='$key'";
			$re = $this -> db -> get_one ( $sql );
			if (  ! empty ( $re[ 'rewords' ] ) )
			{
				if ( $re[ 'rewords' ] )
				{
					$rwordsI = explode ( ' ' , $re[ 'rewords' ] );
				}
				if ( is_array ( $rwordsI ) )
				{
					foreach ( $rwordsI as $value )
					{
						$sqlck .= " title like'%$value%' or ";
					}
					$sqlck = ' and (' . substr ( $sqlck , 0 , strlen ( $sqlck ) - 4 ) . ')';
				}
				if ( $sqlck )
				{
					$sql = "select title,cid,rand_key  from lore_article where 1 $sqlck order by rand() limit 0,6 ";
					$query = $this -> db -> query ( $sql );
					$i = 1;
					while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
					{
						$str .= '<span class="herfwidths"><a href="?model=lore_index&action=viewthread&sby=' . $row[ 'cid' ] . '&key=' . $row[ 'rand_key' ] . '" >' . $row[ 'title' ] . '</a></span>';
						if ( $i % 2 == 0 )
						{
							$str .= '<br>';
						}
						++ $i;
							
					}
				}
			}
		}
		return $str;
	}
	/**
	 * 上传附件列表
	 * Enter description here ...
	 * @param unknown_type $key
	 */
	function model_upfileslist ( $key )
	{
		if ( $key )
		{
			$sql = "select cid from lore_article where 1  and rand_key='$key'";
			$re = $this -> db -> get_one ( $sql );
			if (  ! empty ( $re ) )
			{
				if ( $re[ 'cid' ] )
				{
					$sql = "select fsid,cid,rand_key,filesname from lore_upfiles where 1 and cid='" . $re[ 'cid' ] . "'";
					$query = $this -> db -> query ( $sql );
					while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
					{
						$str .= '<input name="upfilesdel[]" id="upfilesdel[]" type="checkbox" value="' . $row[ 'fsid' ] . '"  />' . $row[ 'filesname' ] . '&nbsp;&nbsp;';
							
					}
				}
			}
		}
		return $str;
	}
	/**
	 * 文章关联
	 * Enter description here ...
	 * @param unknown_type $key
	 */
	function model_upfileshref ( $key,$flag )
	{
		if ( $key )
		{
			$sql = "select fsid,cid,rand_key,filesname,fakename from lore_upfiles where 1 and cid='" . $key . "'";
			$query = $this -> db -> query ( $sql );
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				if($flag=='2'){
					//$str .= '<span class="herfwidth"><a href="?model=lore_index&action=docview&key='.$row[ 'rand_key' ] . '"  target="_blank">' . $row[ 'filesname' ] . '</a></span>';
                   $str .= '<span class="herfwidth"><a href="' . $this -> upfilebase . $row[ 'fakename' ] . '" >' . $row[ 'filesname' ] . '</a></span>';

				}else{
					$str .= '<span class="herfwidth"><a href="' . $this -> upfilebase . $row[ 'fakename' ] . '" >' . $row[ 'filesname' ] . '</a></span>';
				}
			}
		}
		return $str;
	}
	/**
	 * 附件信息
	 * Enter description here ...
	 * @param unknown_type $key
	 */
	function model_upfileinfo ( $key )
	{
		$strI=array();
		if ( $key )
		{
			$sql = "select fsid,cid,rand_key,filesname,fakename from lore_upfiles where 1 and rand_key='" . $key . "'";
			$re = $this -> db -> get_one ( $sql );
			if($re){
				$strI['id']=$re['fsid'];
				$strI['cid']=$re['cid'];
				$strI['creater']=$re['creater'];
				$strI['filesname']=$re['filesname'];
				$strI['fakename']=$re['fakename'];
				$strI['status']=$re['status'];
			
			}
		}
		return $strI;
	}
	/**
	 * 删除附件
	 * Enter description here ...
	 */
	function model_delupfile ( )
	{
		$listid = $_POST[ 'listid' ];
		if ( $listid )
		{
			$sql = "delete from lore_upfiles  where  fsid in(" . $listid . ")";
			$re = $this -> db -> query ( $sql );
			if ( $re )
			{
				$msg = 1;
			} else
			{
				$msg = 2;
			}
		}
		return $msg;
	}
	/**
	 * 文章附件上传
	 */
	function model_upfile ( $path )
	{
		if ( empty ( $_FILES ) )
		{
			exit ( );
		}
		$targetDir = $path . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR;
		$targetDir = $path;
		$cleanupTargetDir = false;
		$maxFileAge = 60 * 60;
		@set_time_limit ( 5 * 60 );
		$chunk = isset ( $_POST[ "chunk" ] ) ? $_POST[ "chunk" ] : 0;
		$chunks = isset ( $_POST[ "chunks" ] ) ? $_POST[ "chunks" ] : 0;
		$fileName = isset ( $_POST[ "name" ] ) ? $_POST[ "name" ] : '';
		if (  ! file_exists ( $targetDir ) )
		@mkdir ( $targetDir );
		if ( is_dir ( $targetDir ) && ( $dir = opendir ( $targetDir ) ) )
		{
			while ( ( $file = readdir ( $dir ) ) !== false )
			{
				$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;
				if ( preg_match ( '/\\.tmp$/' , $file ) && ( filemtime ( $filePath ) < time ( ) - $maxFileAge ) )
				@unlink ( $filePath );
			}
			closedir ( $dir );
		} else
		die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}' );
		if ( isset ( $_SERVER[ "HTTP_CONTENT_TYPE" ] ) )
		$contentType = $_SERVER[ "HTTP_CONTENT_TYPE" ];
		if ( isset ( $_SERVER[ "CONTENT_TYPE" ] ) )
		$contentType = $_SERVER[ "CONTENT_TYPE" ];
		if ( strpos ( $contentType , "multipart" ) !== false )
		{
			if ( isset ( $_FILES[ 'file' ][ 'tmp_name' ] ) && is_uploaded_file ( str_replace ( '\\\\' , '\\' , $_FILES[ 'file' ][ 'tmp_name' ] ) ) )
			{
				$out = fopen ( $targetDir . DIRECTORY_SEPARATOR . $fileName , $chunk == 0 ? "wb" : "ab" );
				if ( $out )
				{
					$in = fopen ( str_replace ( '\\\\' , '\\' , $_FILES[ 'file' ][ 'tmp_name' ] ) , "rb" );
					if ( $in )
					{
						while ( $buff = fread ( $in , 4096 ) )
						fwrite ( $out , $buff );
					} else
					die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
					fclose ( $out );
					@unlink ( str_replace ( '\\\\' , '\\' , $_FILES[ 'file' ][ 'tmp_name' ] ) );
				} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
			} else
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}' );
		} else
		{
			$out = fopen ( $targetDir . DIRECTORY_SEPARATOR . $fileName , $chunk == 0 ? "wb" : "ab" );
			if ( $out )
			{
				$in = fopen ( "php://input" , "rb" );
				if ( $in )
				{
					while ( $buff = fread ( $in , 4096 ) )
					fwrite ( $out , $buff );
				} else
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
				fclose ( $out );
			} else
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
		}

	}
	function model_articleconut ( $key , $sqlstr )
	{
		$count = 0;
		if ( $key )
		{
			$sqls = " SELECT count(cid)
	                 FROM lore_article lc left join user u on (lc.creater=u.user_id) 
	                 where  1 $sqlstr  and  lc.creater='$key'";

			$res = $this -> db -> get_one ( $sqls );
			if (  ! empty ( $res ) )
			{
				$count = $res[ "count(cid)" ];
			}
		}
		return $count;
	}
	function model_reviewconut ( $key , $sqlstr = '' )
	{
		$count = 0;
		if ( $key )
		{
			$sqls = " SELECT count(rid)
	                 FROM lore_article lc left join lore_review lr  on(lr.cid=lc.cid)
	                                     left join user u on (lc.creater=u.user_id)   
	                 where  1 $sqlstr  and lc.creater='$key'";
			$res = $this -> db -> get_one ( $sqls );
			if (  ! empty ( $res ) )
			{
				$count = $res[ "count(rid)" ];
			}
		}
		return $count;
	}
	function model_browseconut ( $key , $sqlstr )
	{
		$count = 0;
		if ( $key )
		{
			$sqls = " SELECT sum(browse)
	                 FROM lore_article lc left join user u on (lc.creater=u.user_id) 
	                 where  1  $sqlstr and lc.creater='$key'";
			$res = $this -> db -> get_one ( $sqls );
			if (  ! empty ( $res ) )
			{
				$count = $res[ "sum(browse)" ];
			}
		}
		return $count;
	}
	/**
	 * 统计数据源
	 * Enter description here ...
	 * @param unknown_type $excel 是否导出
	 */
	function model_countdata ( $excel = false )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$status = trim ( $_GET[ 'status' ] );
		$userid = trim ( $_GET[ 'userid' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$typeclassid = trim ( $_GET[ 'typeclassid' ] );
		$sqlstr = '';
		if ( $status && $status != '-1' )
		{
			$sqltag = str_replace ( ',' , "%' or lc.typeid like '% " , $status );
			$sqlstr .= "and ( lc.typeid like '%" . $sqltag . "%') ";
		}
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' )";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}

		if ( $userid )
		{
			$sqlstr .= "and lc.creater ='$status'";
		}
		if ( $username )
		{
			$sqlstr .= "and u.USER_NAME ='$username'";
		}
		$sqlstr .= " and lc.status='3' ";
		if ( $typeclassid == '2' )
		{
			$sqls = " SELECT count(DISTINCT lm.sender)
                 FROM lore_message lm  left  join lore_article lc on (lc.cid=lm.cid) 
                                       left join user u on (lm.sender=u.user_id) 
                 where  1  $sqlstr";
			$res = $this -> db -> get_one ( $sqls );
			if (  ! empty ( $res ) )
			{
				$count = $res[ "count(DISTINCT lm.sender)" ];
			}

		} else
		{
			$sqls = " SELECT count(DISTINCT lc.creater)
                 FROM lore_article lc left join user u on (lc.creater=u.user_id) 
                 where  1  $sqlstr";
			$res = $this -> db -> get_one ( $sqls );
			if (  ! empty ( $res ) )
			{
				$count = $res[ "count(DISTINCT lc.creater)" ];
			}
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
		if ( $typeclassid == '2' )
		{
			$sql = " SELECT DISTINCT lm.sender,u.user_name,b.articleconut,c.reviewconut,d.browseconut,e.notifyconut,lm.rand_key
	                 FROM lore_message lm left join user u on (lm.sender=u.user_id)
	                 					  left  join lore_article lc on (lc.cid=lm.cid)
	                                      left join ( SELECT count(lc.cid) as articleconut,lm.sender
		                 							  FROM  lore_message lm left join lore_article lc on (lm.cid=lc.cid)
		                 							                        left join user u on (lm.sender=u.user_id)
		                 							  where  1 $sqlstr group by lm.sender) b on b.sender=lm.sender
		                 				  left join ( SELECT count(lr.rid) as reviewconut,lm.sender
		                							  FROM lore_message lm left join lore_review lr  on(lr.cid=lm.cid)
		                							                       left join lore_article lc on (lc.cid=lr.cid)
		                							                       left join user u on (lm.sender=u.user_id)
		                 							  where  1 $sqlstr group by lm.sender) c on c.sender=lm.sender
		                 				  left join ( SELECT sum(lc.browse) as browseconut,lm.sender
		                 							  FROM lore_message lm left  join lore_article lc on (lc.cid=lm.cid) 
		                 							   							left join user u on (lm.sender=u.user_id)
		                 							  where  1  $sqlstr  group by lm.sender) d on d.sender=lm.sender
		                 				  left join ( SELECT count(lt.pid) as notifyconut,lt.preporter
		                 							  FROM   lore_report lt  left join lore_article lc on (lc.cid=lt.cid)
	                 							             where  1 " . str_replace ( "and lc.status='3'" , '' , $sqlstr ) . " 	 
	                 							  		        group by lt.preporter) e on e.preporter=lc.creater 			  			   
	                 where 1 $sqlstr group by lm.sender
	                 ORDER BY " . $sidx . " " . $sord . " ,lc.status desc LIMIT $start," . $limit . "";
			//echo $sql;
			$query = $this -> db -> query ( $sql );
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{

				$browse = $row[ "browseconut" ] ? $row[ "browseconut" ] : '0';
				$notifyconut = $row[ "notifyconut" ] ? $row[ "notifyconut" ] : '0';
				if ( $excel )
				{
					$this -> cexceldata[ ] = array (
					$row[ "user_name" ] ,
					$row[ "articleconut" ] ,
					$row[ "reviewconut" ] ,
					$browse ,
					$notifyconut
					);
				}
				$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
				$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
				$row[ "user_name" ] ,
				//$this->model_articleconut ( $row["creater"],$sqlstr),
				//$this->model_reviewconut ( $row["creater"],$sqlstr),
				//$this->model_browseconut( $row["creater"],$sqlstr),
				$row[ "articleconut" ] ? $row[ "articleconut" ] : 0 ,
				$row[ "reviewconut" ] ? $row[ "reviewconut" ] : 0 ,
				$browse ,
				$row[ "sender" ] ,
				$row[ "ascount" ] ,
				$row[ "browse" ] ,
				$row[ "levels" ] ,
				$row[ "status" ] ,
				$row[ "cid" ] ,
																		'' , 
				$notifyconut
				) );
				$i ++ ;
			}
		} else
		{
			$sql = " SELECT DISTINCT lc.creater,u.user_name,b.articleconut,c.reviewconut,d.browseconut,e.backconut,lc.rand_key
	                 FROM lore_article lc left join user u on (lc.creater=u.user_id)
	                                      left join ( SELECT count(lc.cid) as articleconut,lc.creater
		                 							  FROM lore_article lc left join user u on (lc.creater=u.user_id) 
		                 							  where  1 $sqlstr group by lc.creater) b on b.creater=lc.creater
		                 				  left join ( SELECT count(lr.rid) as reviewconut,lc.creater
		                							  FROM lore_article lc left join lore_review lr  on(lr.cid=lc.cid)
		                                                                   left join user u on (lc.creater=u.user_id)   
		                 							  where  1 $sqlstr group by lc.creater) c on c.creater=lc.creater
		                 				  left join ( SELECT sum(lc.browse) as browseconut,lc.creater
		                 							  FROM lore_article lc left join user u on (lc.creater=u.user_id) 
		                 							  where  1  $sqlstr  group by lc.creater) d on d.creater=lc.creater
		                 				  left join ( SELECT count(lm.mid) as backconut,lm.sender
		                 							  FROM  lore_message lm left join lore_article lc on (lm.cid=lc.cid)
		                 							                           left join user u on (lm.sender=u.user_id)
		                 							  where  1 " . str_replace ( "and lc.status='3'" , '' , $sqlstr ) . " 
		                 							  		 and lm.mclass='1' group by lm.sender) e on e.sender=lc.creater			  			   
	                 where 1 $sqlstr group by lc.creater
	                 ORDER BY " . $sidx . " " . $sord . " ,lc.status desc LIMIT $start," . $limit . "";
			echo $sql;
			$query = $this -> db -> query ( $sql );
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{

				$browse = $row[ "browseconut" ] ? $row[ "browseconut" ] : '0';
				$backconut = $row[ "backconut" ] ? $row[ "backconut" ] : '0';

				if ( $excel )
				{
					$this -> cexceldata[ ] = array (
					$row[ "user_name" ] ,
					$row[ "articleconut" ] ,
					$row[ "reviewconut" ] ,
					$browse ,
					$backconut
					);
				}
				$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
				$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
				$row[ "user_name" ] ,
				//$this->model_articleconut ( $row["creater"],$sqlstr),
				//$this->model_reviewconut ( $row["creater"],$sqlstr),
				//$this->model_browseconut( $row["creater"],$sqlstr),
				$row[ "articleconut" ] ,
				$row[ "reviewconut" ] ,
				$browse ,
				$row[ "creater" ] ,
				$row[ "ascount" ] ,
				$row[ "browse" ] ,
				$row[ "levels" ] ,
				$row[ "status" ] ,
				$row[ "cid" ] ,
				$backconut ,
																		'' 
																		) );
																		$i ++ ;
			}
		}
		return json_encode ( $responce );
	}
	/**
	 * 浏览更新
	 * Enter description here ...
	 */
	function model_upbrowse ( )
	{
		$key = trim ( $_POST[ 'key' ] );
		if ( $key )
		{
			$sql = "select cid,browse,creater from lore_article where 1 and status='3' and  rand_key='$key'";
			$re = $this -> db -> get_one ( $sql );
			if (  ! empty ( $re[ 'cid' ] ) && ( $_SESSION[ 'USER_ID' ] != $re[ 'creater' ] ) )
			{
				$sql = "update lore_article set browse='" . ( $re[ 'browse' ] + 1 ) . "'  where 1 and cid='" . $re[ 'cid' ] . "'";
				$query = $this -> db -> query ( $sql );
			}
		}
		return $str;
	}
	/**
	 * 获取用户ID
	 * Enter description here ...
	 */
	function model_getuserid ( )
	{
		$id = '';
		$username = trim ( $_POST[ 'username' ] );
		if ( $username )
		{
			$sql = "select USER_ID from user where 1 and USER_NAME='$username'";
			$re = $this -> db -> get_one ( $sql );
			if (  ! empty ( $re ) )
			{
				$id = $re[ 'USER_ID' ];
			}
		}
		return $id;
	}
	/**
	 * 批量文章导出
	 * Enter description here ...
	 */
	function model_exportword ( )
	{
		$listidI = array ();
		$listid = trim ( $_POST[ 'listid' ] );
		$listidI = explode ( ',' , $listid );
		$constr = '';
		if ( is_array ( $listidI ) )
		{
			foreach ( $listidI as $key )
			{
				$constr .= "'" . $key . "',";
			}
			$constr = substr ( $constr , 0 , strlen ( $constr ) - 1 );
		}
		$word = new includes_class_word ( );
		if ( $listid && $constr )
		{
			$sql = "select  title,content  from lore_article where 1 and rand_key in (" . $constr . ")";
			//echo $sql;
			$query = $this -> db -> query ( $sql );
			$word -> start ( );
			while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
			{
				print ( '<p style="text-align: center"><strong>' . $row[ 'title' ] . '</strong></p>' ) ;
				print ( $row[ 'content' ] ) ;
				print ( "<span lang=EN-US style='font-size:10.5pt;mso-bidi-font-size:12.0pt;font-family:
					\"Times New Roman\";mso-fareast-font-family:宋体;mso-font-kerning:1.0pt;mso-ansi-language:
					EN-US;mso-fareast-language:ZH-CN;mso-bidi-language:AR-SA'><br clear=all
					style='page-break-before:always'>
					</span>" ) ;
					
				//$word->save("word/".$row['title'].".doc");
			}
			$word -> save ( "word/mywenzhang.doc" );
			echo "word/mywenzhang.doc";
		}

	}
	function model_sarticlecount ( $userid )
	{
		$counts = 0;
		if ( $userid )
		{
			$sql = "select count(cid) from lore_article where 1 and status='3' and creater='" . $userid . "'";
			//echo $sql;
			$res = $this -> db -> get_one ( $sql );
			if (  ! empty ( $res ) )
			{
				$counts = $res[ 'count(cid)' ];
			}
		}
		return $counts;
	}
	/**
	 * 文章类型更新
	 * Enter description here ...
	 */
	function model_comake ( )
	{
		$key = $_POST[ 'rand_key' ];
		$op = ( int ) $_POST[ 'op' ];
		$senderI = array ();
		$sendstr = array ();
		if ( $op == '1' )
		{
			$mcontent = '你的文章被置为普通';
			$mclass = 7;
		} elseif ( $op == '2' )
		{
			$mcontent = '你的文章被置顶';
			$mclass = 8;
		} elseif ( $op == '3' )
		{
			$mcontent = '你的文章被置为公告';
			$mclass = 9;
		}
		$sqlcon = " and la.rand_key='$key'";
		if (  ! empty ( $key ) &&  ! empty ( $op ) )
		{
			$sql = "select la.cid,la.title,la.creater,u.EMAIL,u.USER_NAME
					from lore_article la left join user u on (u.USER_ID=la.creater) 
					where 1  $sqlcon ";
			$re = $this -> db -> get_one ( $sql );
			if (  ! empty ( $re ) &&  ! empty ( $re[ 'EMAIL' ] ) )
			{
				$gls = new includes_class_global ( );
				$semails = new includes_class_sendmail ( );
				$Address = $gls -> get_email ( $re[ 'creater' ] );
				$flag = $semails -> send ( $re[ 'USER_NAME' ] . '你的文章被操作' , $re[ 'USER_NAME' ] . '发表了' . $re[ 'title' ] . '文章被操作查请登录OA查看消息' , $Address );
				$sqls = "insert into lore_message(cid,mcontent,mcrdt,sender,mstatus,mclass)
								 values('" . $re[ 'cid' ] . "','$mcontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
								       ,'$mclass')";
				$re = $this -> db -> query ( $sqls );
			}
			$sql = "update lore_article set class='$op' where rand_key='" . $key . "'";
			$re = $this -> db -> query ( $sql );
			if ( $re )
			{
				$msg = "操作成功!";
			} else
			{
				$msg = "操作失败!";
			}
		}
		return $msg;
	}
	/**
	 *文章等级更新
	 * @return string
	 */
	function model_makeleave ( )
	{
		$key = $_POST[ 'rand_key' ];
		$op = ( int ) $_POST[ 'op' ];
		$senderI = array ();
		$sendstr = array ();
		if ( $op == '1' )
		{
			$mcontent = '你的文章被置为常用';
			$mclass = 7;
		} elseif ( $op == '2' )
		{
			$mcontent = '你的文章被精华';
			$mclass = 8;
		} elseif ( $op == '3' )
		{
			$mcontent = '你的文章被置为重要';
			$mclass = 9;
		}
		$sqlcon = " and la.rand_key='$key'";
		if (  ! empty ( $key ) &&  ! empty ( $op ) )
		{
			$sql = "select la.cid,la.title,la.creater,u.EMAIL,u.USER_NAME
					from lore_article la left join user u on (u.USER_ID=la.creater) 
					where 1  $sqlcon ";
			$re = $this -> db -> get_one ( $sql );
			if (  ! empty ( $re ) &&  ! empty ( $re[ 'EMAIL' ] ) )
			{
				$gls = new includes_class_global ( );
				$semails = new includes_class_sendmail ( );
				$Address = $gls -> get_email ( $re[ 'creater' ] );
				$flag = $semails -> send ( $re[ 'USER_NAME' ] . '你的文章被操作' , $re[ 'USER_NAME' ] . '发表了' . $re[ 'title' ] . '文章被操作查请登录OA查看消息' , $Address );
				$sqls = "insert into lore_message(cid,mcontent,mcrdt,sender,mstatus,mclass)
								 values('" . $re[ 'cid' ] . "','$mcontent',now(),'" . $_SESSION[ 'USER_ID' ] . "','1'
								       ,'$mclass')";
				$re = $this -> db -> query ( $sqls );
			}
			$sql = "update lore_article set levels='$op' where rand_key='" . $key . "'";
			$re = $this -> db -> query ( $sql );
			if ( $re )
			{
				$msg = "操作成功!";
			} else
			{
				$msg = "操作失败!";
			}
		}
		return $msg;
	}
	/**
	 * 删除收藏
	 * Enter description here ...
	 */
	function model_delfavorites ( )
	{
		$_POST = mb_iconv ( $_POST );
		$key = $_POST[ 'rand_key' ];
		if (  ! empty ( $key ) )
		{
			$sql = "select cid
			        from  lore_favorites  
			        where  rand_key='" . $key . "'";
			$re = $this -> db -> get_one ( $sql );
			if ( empty ( $re[ 'cid' ] ) )
			{
				$msg = "已删除!";
			} else
			{
				$sql = "delete from lore_favorites  where rand_key='" . $key . "'";
				$re = $this -> db -> query ( $sql );
				if ( $re )
				{
					$msg = "删除成功!";
				} else
				{
					$msg = "删除失败!";
				}
			}
		} else
		{
			$msg = "删除失败 !";
		}
		return $msg;

	}
	/**
	 * 判断是否管理员
	 * Enter description here ...
	 */
	function model_admin ( )
	{
		if ( $_SESSION[ 'USER_ID' ] )
		{
			$sqls = "select administrator from lore_type where 1";
			$qcontent = $this -> db -> query ( $sqls );
			$user_arr = array ();
			//array_push($user_arr,'admin');
			while ( ( $res = $this -> db -> fetch_array ( $qcontent ) ) != false )
			{
				$user_arr = array_unique ( array_merge ( $user_arr , explode ( ',' , $res[ 'administrator' ] ) ) );
			}
			$flag = array_search ( $_SESSION[ 'USER_ID' ] , $user_arr , true );
		}
		return $flag;
	}
	function model_username ( )
	{
		$username = '';
		$sql = "select user_name
			        from  user  
			        where  user_id='" . $_SESSION[ 'USER_ID' ] . "'";
		$re = $this -> db -> get_one ( $sql );
		if (  ! empty ( $re[ 'user_name' ] ) )
		{
			$username = $re[ 'user_name' ];
		}
		return $username;
	}
	/**
	 * 纠错明细
	 * Enter description here ...
	 */
	function model_showreturn ( )
	{
		$userid = trim ( $_POST[ 'userid' ] );
		if ( $userid )
		{
			$page = $this -> page;
			$start = $this -> start;
			$num = $this -> num;
			if (  ! $num )
			{
				$sql = " select count(lm.mid)
 	   			         from lore_message lm left join user u on (u.USER_ID=lm.sender) 
 	   			                     left join lore_article  la on(la.cid=lm.cid)    
 	   					 where 1  and lm.mclass='1'  and lm.sender='$userid'";
				$res = $this -> db -> get_one ( $sql );
				if (  ! empty ( $res ) )
				{
					$num = $res[ "count(lm.mid)" ];
				}
			}
			if ( $num > 0 )
			{
				$sqls = "select u.USER_NAME,la.title,date_format(lm.mcrdt,'%Y-%m-%d') as mcrdt
 	   			from lore_message lm left join user u on (u.USER_ID=lm.sender) 
 	   			                     left join lore_article  la on(la.cid=lm.cid)   
 	   			where 1 and lm.mclass='1' and lm.sender='$userid'";
				$qcontent = $this -> db -> query ( $sqls );
				while ( ( $res = $this -> db -> fetch_array ( $qcontent ) ) != false )
				{
					$str .= '<tr><td style="width:10%; text-align:center">' . $res[ 'USER_NAME' ] . '</td><td style="width:10%; text-align:center">' . $res[ 'title' ] . '</td><td style="width:10%; text-align:center">' . $res[ 'mcrdt' ] . '</td></tr>';
				}
				$str .= "<tr><td colspan='8' align='center'>";
				$showpage = new includes_class_page ( );
				$showpage -> open_ajax ( 'showreturn' );
				$showpage -> show_page ( array (
												'total' => $num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $num . '&status=' . $_GET[ 'status' ] . '&userid=' . $userid );
				$str .= $showpage -> show ( 6 );
					
			} else
			{
				$str .= "<tr><td style='width:100%; text-align:center' colspan='8' align='center'>没有此记录</td></tr>";
			}
		}
		return $str;
	}
	/**
	 * 打回明细
	 * Enter description here ...
	 */
	function model_showreport ( )
	{
		$userid = trim ( $_POST[ 'userid' ] ? $_POST[ 'userid' ] : $_GET[ 'userid' ] );
		if ( $userid )
		{
			$page = $this -> page;
			$start = $this -> start;
			$num = $this -> num;
			if (  ! $num )
			{
				$sql = " select count(lr.pid)
 	   			     from lore_report lr  
 	   			                     left join user u on (u.USER_ID=lr.preporter) 
 	   			                     left join lore_article la on(la.cid=lr.cid)   
 	   			     where 1  and lr.preporter='$userid'";
				$res = $this -> db -> get_one ( $sql );
				if (  ! empty ( $res ) )
				{
					$num = $res[ "count(lr.pid)" ];
				}
			}
			if ( $num > 0 )
			{
				$i = 0;
				$sqls = "select u.USER_NAME,la.title,date_format(lr.pcreatdt,'%Y-%m-%d') as pcreatdt
 	   			from lore_report lr  
 	   			                     left join user u on (u.USER_ID=lr.preporter) 
 	   			                     left join lore_article la on(la.cid=lr.cid)   
 	   			where 1  and lr.preporter='$userid' ORDER BY lr.pcreatdt desc LIMIT $start," . pagenum;
				$qcontent = $this -> db -> query ( $sqls );
				while ( ( $res = $this -> db -> fetch_array ( $qcontent ) ) != false )
				{
					$str .= '<tr><td style="width:10%; text-align:center">' . $res[ 'USER_NAME' ] . '</td><td style="width:10%; text-align:center">' . $res[ 'title' ] . '</td><td style="width:10%; text-align:center">' . $res[ 'pcreatdt' ] . '</td></tr>';
				}

				$str .= "<tr><td colspan='8' align='center'>";
				$showpage = new includes_class_page ( );
				$showpage -> open_ajax ( 'showreport' );
				$showpage -> show_page ( array (
												'total' => $num , 
												'perpage' => pagenum 
				) );
				$showpage -> _set_url ( 'num=' . $num . '&status=' . $_GET[ 'status' ] . '&userid=' . $userid );
				$str .= $showpage -> show ( 6 );
					
			} else
			{
				$str .= "<tr><td style='width:100%; text-align:center' colspan='8' align='center'>没有此记录</td></tr>";
			}
		}
		return $str;
	}
	/**
	 *纠错数据源
	 * Enter description here ...
	 */
	function model_reportmanagerdata ( )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$tag1 = trim ( $_GET[ 'tag1' ] );
		$tag2 = trim ( $_GET[ 'tag2' ] );
		$tag3 = trim ( $_GET[ 'tag3' ] );
		$dts = trim ( $_GET[ 'dts' ] );
		$levs = trim ( $_GET[ 'levs' ] );
		$status = ( int ) trim ( $_GET[ 'status' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$sqlstr = "";
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' 
		                or lr.pcontent like '%" . $keyword . "%')";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}

		//$sqlstr .= "and lm.mstatus in (1,2) and  lc.creater='" . $_SESSION['USER_ID'] . "'";
		$sqls = "SELECT count(lr.pid)
                 FROM lore_report lr  left join user u on (lr.preporter=u.user_id)
                 			           left join lore_article lc on (lc.cid=lr.cid)
                 where 1  $sqlstr ";
		//echo $sqls;
		$res = $this -> db -> get_one ( $sqls );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lr.pid)" ];
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
		$sql = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
						,lc.reterms,lc.levels,lc.status,lc.browse,lc.class,u.user_name,lc.pid
						,lr.pcontent,lr.pstatus,lr.plevels,lr.pcreatdt,lr.rand_key,lc.rand_key as rand_keys
                 FROM lore_report lr  left join user u on (lr.preporter=u.user_id)
                 			           left join lore_article lc on (lc.cid=lr.cid)
                 where 1  $sqlstr 
                 ORDER BY " . $sidx . " " . $sord . " ,lr.pcreatdt desc LIMIT $start," . $limit . "";
		$query = $this -> db -> query ( $sql );
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "class" ] ,
			$row[ "title" ] ,
			$row[ "pcontent" ] ,
			$row[ "user_name" ] ,
																	'' , 
			$row[ "pcreatdt" ] ,
			$row[ "plevels" ] ,
			$row[ "pstatus" ] ,
			$row[ "cid" ] ,
			$row[ "rand_keys" ] ,
			$row[ "pid" ]
			) );
			$i ++ ;
		}
		return json_encode ( $responce );
	}
	/**
	 *纠错操作
	 * Enter description here ...
	 */
	function model_reportmanagerop ( )
	{
		$key = $_POST[ 'rand_key' ] ? $_POST[ 'rand_key' ] : $_GET[ 'rand_key' ];
		$op = ( int ) $_POST[ 'op' ] ? $_POST[ 'op' ] : $_GET[ 'op' ];
		if (  ! empty ( $key ) )
		{
			$sql = "select pid from lore_report where 1 and pstatus='$op' and rand_key='$key'";
			$re = $this -> db -> get_one ( $sql );
			if ( empty ( $re ) )
			{
				$sql = "update lore_report set pstatus='$op' where rand_key='" . $key . "'";
				$re = $this -> db -> query ( $sql );
				if ( $re )
				{
					$msg = "操作成功!";
				} else
				{
					$msg = "操作失败!";
				}
			} else
			{
				$msg = "操作失败";
			}
		} else
		{
			$msg = "操作失败 !";
		}
		return $msg;
	}
	/**
	 *
	 * 测试main()
	 */
	function model_main_lose ( )
	{
		$pid = $_GET[ 'pid' ];
		$sqlcon = '';
		if ( $pid )
		{
			$sqlcon = " and pid='$pid' or id='$pid'";
		} else
		{
			//$sqlcon=" and p.pid='0' or p.id=''";
		}
		//$sqlstr="select id,pid,typename,styleid from lore_type where 1 and flag='1' $sqlcon";
		//$sqlstr="select s.id,s.pid,s.typename,s.styleid
		//from lore_type p left join lore_type  s  on(p.id=s.pid)
		//where 1 and s.flag='1' $sqlcon";
		//echo $sqlstr;
		$query = $this -> db -> query ( $sqlstr );
		$forumI = array ();
		$i = 0;
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			if ( $row[ 'pid' ] != 0 && $row[ 'pid' ] != '' )
			{
				$forumI[ $row[ 'pid' ] ][ 'sid' ][ $row[ 'id' ] ][ 'id' ] = $row[ 'id' ];
				$forumI[ $row[ 'pid' ] ][ 'sid' ][ $row[ 'id' ] ][ 'pid' ] = $row[ 'pid' ];
				$forumI[ $row[ 'pid' ] ][ 'sid' ][ $row[ 'id' ] ][ 'typename' ] = $row[ 'typename' ];
				$forumI[ $row[ 'pid' ] ][ 'sid' ][ $row[ 'id' ] ][ 'styleid' ] = $row[ 'styleid' ];
			} else
			{
				$forumI[ $row[ 'id' ] ][ 'pid' ][ 'id' ] = $row[ 'id' ];
				$forumI[ $row[ 'id' ] ][ 'pid' ][ 'pid' ] = $row[ 'pid' ];
				$forumI[ $row[ 'id' ] ][ 'pid' ][ 'typename' ] = $row[ 'typename' ];
				$forumI[ $row[ 'id' ] ][ 'pid' ][ 'styleid' ] = $row[ 'styleid' ];
			}
		}
		$strTemp = '';
		$i = 0;
		print_r ( $forumI );
		if ( is_array ( array_filter ( $forumI ) ) )
		{
			foreach ( $this -> array_sort ( $forumI , 'id' ) as $keyI => $valueI )
			{
				//echo $valueI['pid']['pid'];
				if ( $valueI[ 'pid' ][ 'pid' ] == 0 )
				{
					foreach ( $valueI as $k => $val )
					{
						if ( $k == 'pid' )
						{
							$strTemp .= '<div class="countercust">';
							$strTemp .= '<div class="titlecust"> <span class="shrink">
									<img id="category_' . $val[ 'id' ] . '_img" src="images/lore/collapsed_no.gif" 
											title="收起/展開" alt="收起/展開" onclick="toggle_collapse(\'category_' . $val[ 'id' ] . '\');"> 
									</span><a class="titlefontcust" href="#" style="">' . $val[ 'typename' ] . '</a></div>';
							$strTemp .= '<div id="category_' . $val[ 'id' ] . '" class="countertextcust" style="width:100%;display:block">';
							if ( $val[ 'styleid' ] == 0 )
							{
								$width = 100 - 2;
							} else
							{
								$width = ( floor ( 100 / $val[ 'styleid' ] ) - 0.5 );
							}
							foreach ( $valueI[ 'sid' ] as $keyS => $valueS )
							{
								print_r ( $valueS );
								$strTemp .= '<div class="stylewidth" style="float:left;width:' . $width . '%;"><span >
										<a href="?model=lore_index&action=main&pid=' . $valueS[ 'id' ] . '">
													<img src="images/lore/forum_new.gif" alt=""></a></span>
													<span><a href="#">' . $valueS[ 'typename' ] . '</a></span>
													<span class="xi2">1</span><span class="xg1"> / 2</span>
													</div>';
									
							}

							$strTemp .= '</div></div>';
							$i ++ ;
						}
					}
				} else
				{

					foreach ( $valueI as $k => $val )
					{
						print_r ( $val );
						if ( $k == 'sid' )
						{
							$strTemp .= '<div class="countercust">';
							$strTemp .= '<div class="titlecust"> <span class="shrink">
									<img id="category_' . $val[ 'id' ] . '_img" src="images/lore/collapsed_no.gif" 
											title="收起/展開" alt="收起/展開" onclick="toggle_collapse(\'category_' . $val[ 'id' ] . '\');"> 
									</span><a class="titlefontcust" href="#" style="">' . $val[ 'typename' ] . '</a></div>';
							$strTemp .= '<div id="category_' . $val[ 'id' ] . '" class="countertextcust" style="width:100%;display:block">';
							if ( $val[ 'styleid' ] == 0 )
							{
								$width = 100 - 2;
							} else
							{
								$width = ( floor ( 100 / $val[ 'styleid' ] ) - 0.5 );
							}
							foreach ( $valueI[ 'sid' ] as $keyS => $valueS )
							{
								print_r ( $valueS );
								$strTemp .= '<div class="stylewidth" style="float:left;width:' . $width . '%;"><span >
										<a href="?model=lore_index&action=main&pid=' . $valueS[ 'id' ] . '">
													<img src="images/lore/forum_new.gif" alt=""></a></span>
													<span><a href="#">' . $valueS[ 'typename' ] . '</a></span>
													<span class="xi2">1</span><span class="xg1"> / 2</span>
													</div>';
									
							}

							$strTemp .= '</div></div>';
							$i ++ ;
						}
					}
				}
			}
		}
		return $strTemp;
	}
	/**
	 *首页版块管理界面
	 *
	 */
	function model_main ( )
	{
		//print_r($_SESSION);
		$pid = $_GET[ 'pid' ];
		$sqlcon = '';
		if ( $pid )
		{
			$sqlcon = " and pid='$pid' or id='$pid'";
		}
		$sqlstr = "select id,pid,typename,styleid,description,imgs from lore_type where 1 and flag='1' $sqlcon";
		$query = $this -> db -> query ( $sqlstr );
		$forumI = array ();
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			$forumI[ $row[ 'pid' ] ][ 'id' ][ ] = $row[ 'id' ];
			$forumI[ $row[ 'pid' ] ][ 'pid' ][ ] = $row[ 'pid' ];
			$forumI[ $row[ 'pid' ] ][ 'typename' ][ ] = $row[ 'typename' ];
			$forumI[ $row[ 'pid' ] ][ 'styleid' ][ ] = $row[ 'styleid' ];
			$forumI[ $row[ 'pid' ] ][ 'description' ][ ] = $row[ 'description' ];
			$forumI[ $row[ 'pid' ] ][ 'imgs' ][ ] = $row[ 'imgs' ];

			//}
		}
		$strTemp = '';
		$desc = '';
		if ( is_array ( array_filter ( $forumI ) ) )
		{
			foreach ( $this -> array_sort ( $forumI , 'pid' ) as $keyI => $valueI )
			{
				if ( $valueI[ 'pid' ][ '0' ] != $pid )
				{
					$ppid = $valueI[ 'pid' ][ '0' ];
					$vpid = $valueI[ 'id' ][ '0' ];
				}
				if ( $keyI == 0 && $forumI[ $vpid ][ 'pid' ][ '0' ] != $pid )
				{
					$i = 1;
					foreach ( $valueI[ 'id' ] as $k => $val )
					{
						if ( is_array ( $forumI[ $val ][ 'id' ] ) )
						{
							$strTemp .= '<div class="countercust">';
							$strTemp .= '<div class="titlecust"> <span class="shrink">
									<img id="category_' . $valueI[ 'id' ][ $k ] . '_img" src="images/lore/collapsed_no.gif" 
											title="收起/展開" alt="收起/展開" onclick="toggle_collapse(\'category_' . $valueI[ 'id' ][ $k ] . '\');"> 
									</span><a class="titlefontcust" href="?model=lore_index&action=main&pid=' . $valueI[ 'id' ][ $k ] . '" style="">' . $valueI[ 'typename' ][ $k ] . '</a></div>';
							$strTemp .= '<div id="category_' . $valueI[ 'id' ][ $k ] . '" class="countertextcust" style="width:100%;display:block">';
							if ( $valueI[ 'styleid' ][ $k ] == 0 || $valueI[ 'styleid' ][ $k ] == 1 )
							{
								$width = 100 - 2;
							} else
							{
								$width = ( floor ( 100 / $valueI[ 'styleid' ][ $k ] ) - 0.5 );
							}
							$widtht = $width;
							if ( $valueI[ 'styleid' ][ $k ] == 0 || $valueI[ 'styleid' ][ $k ] == 1 )
							{
								$snum = 202;
							} else if ( $valueI[ 'styleid' ][ $k ] == 2 )
							{
								$snum = 112;
							} else if ( $valueI[ 'styleid' ][ $k ] == 3 )
							{
								$snum = 62;
							}
							//$cun=count($forumI[$val]['id'])%$valueI['styleid'][$k];
							//echo $cun;
							foreach ( $forumI[ $val ][ 'id' ] as $keyS => $valueS )
							{
								if ( strlen ( $forumI[ $val ][ 'description' ][ $keyS ] ) > $snum )
								{
									$desc = '...';
								} else
								{
									$desc = '';
								} /*
								if(($cun<$i&&$i < $cun*$valueI['styleid'][$k])&&$cun*$valueI['styleid'][$k]!=0)
								{
								$width='100';
								}else {
								$width=$widtht;
								}*/
								if ( trim ( $forumI[ $val ][ 'imgs' ][ $keyS ] ) )
								{
									$imgs = trim ( $forumI[ $val ][ 'imgs' ][ $keyS ] );
								} else
								{
									$imgs = 'forum_new.gif';
								}
								$strTemp .= '<div class="stylewidth" style="float:left!important;width:' . $width . '%;"><span class="fimgs">
										<a href="?model=lore_index&action=main&pid=' . $forumI[ $val ][ 'id' ][ $keyS ] . '">
													<img src="images/lore/' . $imgs . '" width="47" height="57"></a></span>
													<span class="fcount"><div class="ftitles"><a href="?model=lore_index&action=main&pid=' . $forumI[ $val ][ 'id' ][ $keyS ] . '">' . $forumI[ $val ][ 'typename' ][ $keyS ] . '</a></div>
													<div class="des">' . substr ( $forumI[ $val ][ 'description' ][ $keyS ] , 0 , $snum ) . $desc . '</div></span></div>';
								$i ++ ;
							}
							$strTemp .= '</div></div>';
						}
					}
				} else if ( $pid && $valueI[ 'id' ][ '0' ] != $pid )
				{
					$strTemp .= '<div class="countercust">';
					$strTemp .= '<div class="titlecust"> <span class="shrink">
									<img id="category_' . $valueI[ 'id' ][ $k ] . '_img" src="images/lore/collapsed_no.gif" 
											title="收起/展開" alt="收起/展開" onclick="toggle_collapse(\'category_' . $forumI[ $ppid ][ 'id' ][ '0' ] . '\');"> 
									</span><a class="titlefontcust" href="?model=lore_index&action=main&pid=' . $forumI[ $ppid ][ 'id' ][ '0' ] . '" style="">' . $forumI[ $ppid ][ 'typename' ][ '0' ] . '</a></div>';
					$strTemp .= '<div id="category_' . $forumI[ $ppid ][ 'id' ][ '0' ] . '" class="countertextcust" style="width:100%;display:block">';
					if ( $forumI[ $ppid ][ 'styleid' ][ '0' ] == 0 || $forumI[ $ppid ][ 'styleid' ][ '0' ] == 1 )
					{
						$width = 100 - 2;
					} else
					{
						$width = ( floor ( 100 / $forumI[ $ppid ][ 'styleid' ][ '0' ] ) - 0.5 );
					}
					if ( $forumI[ $ppid ][ 'styleid' ][ '0' ] == 0 || $forumI[ $ppid ][ 'styleid' ][ '0' ] == 1 )
					{
						$snum = 202;
					} else if ( $forumI[ $ppid ][ 'styleid' ][ '0' ] == 2 )
					{
						$snum = 112;
					} else if ( $forumI[ $ppid ][ 'styleid' ][ '0' ] == 3 )
					{
						$snum = 62;
					}
					foreach ( $forumI[ $pid ][ 'id' ] as $keyS => $valueS )
					{
						if ( strlen ( $forumI[ $pid ][ 'description' ][ $keyS ] ) > $snum )
						{
							$desc = '...';
						} else
						{
							$desc = '';
						}
						if ( trim ( $forumI[ $pid ][ 'imgs' ][ $keyS ] ) )
						{
							$imgs = trim ( $forumI[ $pid ][ 'imgs' ][ $keyS ] );
						} else
						{
							$imgs = 'forum_new.gif';
						}
						$strTemp .= '<div class="stylewidth" style="float:left;width:' . $width . '%;"><span class="fimgs">
										<a href="?model=lore_index&action=main&pid=' . $forumI[ $pid ][ 'id' ][ $keyS ] . '">
													<img src="images/lore/' . $imgs . '" alt=""></a></span>
													<span class="fcount"><div class="ftitles"><a href="?model=lore_index&action=main&pid=' . $forumI[ $pid ][ 'id' ][ $keyS ] . '">' . $forumI[ $pid ][ 'typename' ][ $keyS ] . '</a></div>
													<div class="des">' . substr ( $forumI[ $pid ][ 'description' ][ $keyS ] , 0 , $snum ) . $desc . '</div></span>
													</div>';
					}
					$strTemp .= '</div></div>';

				}
			}
			if (  ! $strTemp && $pid )
			{
						
				if ( count ( $this -> usepower ( $pid ) ) )
				{
					header ( "Location:?model=lore_index&action=list&pid=$pid" );
				} else
				{
					echo '<script type="text/javascript">alert("本区暂未对你浏览！");history.back(-1);</script>';

				}
			}
		}
		return $strTemp;
	}
	/**
	 * 测试数据 main
	 *
	 */
	function model_maintest ( )
	{
		$pid = $_GET[ 'pid' ];
		$sqlcon = '';
		if ( $pid )
		{
			$sqlcon = " and pid='$pid' or id='$pid'";
		}
		//$tmp='<div class="mn"> </div>';
		$sqlstr = "select id,pid,typename,styleid from lore_type where 1 and flag='1' $sqlcon";
		$query = $this -> db -> query ( $sqlstr );
		$forumI = array ();
		$i = 0;
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{

			$forumI[ $row[ 'pid' ] ][ 'id' ][ 'id' ] = $row[ 'id' ];
			$forumI[ $row[ 'pid' ] ][ 'id' ][ 'pid' ] = $row[ 'pid' ];
			$forumI[ $row[ 'pid' ] ][ 'id' ][ 'typename' ][ ] = $row[ 'typename' ];
			$forumI[ $row[ 'pid' ] ][ 'id' ][ 'styleid' ][ ] = $row[ 'styleid' ];

			//}
		}
		$strTemp .= '<div class="counter">';
		$i = 0;
		if ( is_array ( $forumI ) )
		{
			foreach ( $forumI as $keyI => $valueI )
			{
				if ( $valueI[ 'pid' ][ '0' ] != $pid )
				{
					$ppid = $valueI[ 'pid' ][ '0' ];
					echo $ppid;
				}
				if ( $keyI == 0 )
				{
					foreach ( $valueI[ 'id' ] as $k => $val )
					{
						$strTemp .= '<div class="countercust">';
						$strTemp .= '<div class="titlecust"> <span class="shrink">
									<img id="category_' . $valueI[ 'id' ][ $k ] . '_img" src="images/lore/collapsed_no.gif" 
											title="收起/展開" alt="收起/展開" onclick="toggle_collapse(\'category_' . $valueI[ 'id' ][ $k ] . '\');"> 
									</span><a class="titlefontcust" href="#" style="">' . $valueI[ 'typename' ][ $k ] . '</a></div>';
						$strTemp .= '<div id="category_' . $valueI[ 'id' ][ $k ] . '" class="countertextcust" style="width:100%;display:block">';
						if ( $valueI[ 'styleid' ][ $k ] == 0 )
						{
							$width = 100 - 2;
						} else
						{
							$width = ( floor ( 100 / $valueI[ 'styleid' ][ $k ] ) - 0.5 );
						}
						foreach ( $forumI[ $val ][ 'id' ] as $keyS => $valueS )
						{
							$strTemp .= '<div class="stylewidth" style="float:left;width:' . $width . '%;"><span >
										<a href="?model=lore_index&action=main&pid=' . $forumI[ $val ][ 'id' ][ $keyS ] . '">
													<img src="images/lore/forum_new.gif" alt=""></a></span>
													<span><a href="#">' . $forumI[ $val ][ 'typename' ][ $keyS ] . '</a></span>
													<span class="xi2">1</span><span class="xg1"> / 2</span>
													</div>';
						}
						$strTemp .= '</div></div>';
						$i ++ ;
					}
				} else if ( $pid && $valueI[ 'id' ][ '0' ] != $pid )
				{
					$strTemp .= '<div class="countercust">';
					$strTemp .= '<div class="titlecust"> <span class="shrink">
									<img id="category_' . $valueI[ 'id' ][ $k ] . '_img" src="images/lore/collapsed_no.gif" 
											title="收起/展開" alt="收起/展開" onclick="toggle_collapse(\'category_' . $forumI[ $ppid ][ 'id' ][ '0' ] . '\');"> 
									</span><a class="titlefontcust" href="?model=lore_index&action=main&pid=' . $forumI[ $pid ][ 'id' ][ $keyS ] . '" style="">' . $forumI[ $ppid ][ 'typename' ][ '0' ] . '</a></div>';
					$strTemp .= '<div id="category_' . $forumI[ $ppid ][ 'id' ][ '0' ] . '" class="countertextcust" style="width:100%;display:block">';
					if ( $forumI[ $ppid ][ 'styleid' ][ '0' ] == 0 )
					{
						$width = 100 - 2;
					} else
					{
						$width = ( floor ( 100 / $forumI[ $ppid ][ 'styleid' ][ '0' ] ) - 0.5 );
					}
					foreach ( $forumI[ $pid ][ 'id' ] as $keyS => $valueS )
					{
						$strTemp .= '<div class="stylewidth" style="float:left;width:' . $width . '%;"><span >
										<a href="?model=lore_index&action=main&pid=' . $forumI[ $pid ][ 'id' ][ $keyS ] . '">
													<img src="images/lore/forum_new.gif" alt=""></a></span>
													<span><a href="?model=lore_index&action=main&pid=' . $forumI[ $pid ][ 'id' ][ $keyS ] . '">' . $forumI[ $pid ][ 'typename' ][ $keyS ] . '</a></span>
													<span class="xi2">1</span><span class="xg1"> / 2</span>
													</div>';
					}
					$strTemp .= '</div></div>';

				}
			}
			$strTemp .= '</div>';

		}
		return $strTemp;
	}
	/**
	 * 数组排序
	 * @param  $array   数组源
	 * @param  $on   按KEY键排序
	 * @param  $order   排序的方式
	 */
	function array_sort ( $array , $on , $order = SORT_ASC )
	{
		$new_array = array ();
		$sortable_array = array ();
		if ( count ( $array ) > 0 )
		{
			foreach ( $array as $k => $v )
			{
				if ( is_array ( $v ) )
				{
					foreach ( $v as $k2 => $v2 )
					{
						if ( $k2 == $on )
						{
							$sortable_array[ $k ] = $v2;
						}
					}
				} else
				{
					$sortable_array[ $k ] = $v;
				}
			}
			switch ( $order )
			{
				case SORT_ASC :
					asort ( $sortable_array );
					break;
				case SORT_DESC :
					arsort ( $sortable_array );
					break;
			}

			foreach ( $sortable_array as $k => $v )
			{
				$new_array[ $k ] = $array[ $k ];
			}
		}
		return $new_array;
	}
	/**
	 * 审批文章数据源
	 *
	 *
	 */
	function model_approvaldata ( )
	{
		$page = $_GET[ 'page' ] ? $_GET[ 'page' ] : 1;
		$limit = $_GET[ 'rows' ] ? $_GET[ 'rows' ] : 20;
		$sidx = $_GET[ 'sidx' ] ? $_GET[ 'sidx' ] : 1;
		$sord = $_GET[ 'sord' ] ? $_GET[ 'sord' ] : " desc";
		$tag1 = trim ( $_GET[ 'tag1' ] );
		$tag2 = trim ( $_GET[ 'tag2' ] );
		$tag3 = trim ( $_GET[ 'tag3' ] );
		$dts = trim ( $_GET[ 'dts' ] );
		$levs = trim ( $_GET[ 'levs' ] );
		$status = trim ( $_GET[ 'status' ] );
		$username = trim ( $_GET[ 'username' ] );
		$sdate = trim ( $_GET[ 'sdate' ] );
		$edate = trim ( $_GET[ 'edate' ] );
		$keyword = trim ( $_GET[ 'keyword' ] );
		$sqlstr = "";
		if ( $keyword )
		{
			$sqlstr .= " and (lc.title like '%" . $keyword . "%' or lc.content like '%" . $keyword . "%'
		                or lc.creater like '%" . $keyword . "%' or lc.rewords like '%" . $keyword . "%' )";
		}
		if ( $sdate )
		{
			$sqlstr .= " and lc.updatedt >= '$sdate' ";
		}
		if ( $edate )
		{
			$sqlstr .= " and lc.updatedt <= '$edate' ";
		}
		if ( $status && $status != '-1' )
		{
			$sqltag = str_replace ( ',' , "%' or lc.typeid like '% " , $status );
			$sqlstr .= "and ( lc.typeid like '%" . $sqltag . "%') ";
		}
		$idlist = $this -> model_approvaler ( );
		$sqlstr .= " and lc.status in (2,3,5) and lc.cid in ($idlist)";
		$sqls = " SELECT count(lc.cid)
                 FROM lore_article lc 
                 where  1  $sqlstr";
		$res = $this -> db -> get_one ( $sqls );
		if (  ! empty ( $res ) )
		{
			$count = $res[ "count(lc.cid)" ];
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
		$sql = " SELECT  lc.cid,lc.typeid,lc.title,lc.content,lc.creater,lc.creatdt,lc.updatedt,lc.rewords
						,lc.reterms,lc.levels,lc.status,lc.browse,lc.rand_key,lc.class,u.user_name
						,lc.class,s.referconut,lc.pid
                 FROM lore_article lc  left join user u on (lc.creater=u.user_id) 
                 						left join ( 
							                 SELECT count(rid) as referconut,cid
							                 FROM lore_review 
							                 where  1  group by cid) s on s.cid=lc.cid 
                 where 1  $sqlstr 
                 ORDER BY " . $sidx . " " . $sord . " ,lc.updatedt desc LIMIT $start," . $limit . "";
		$query = $this -> db -> query ( $sql );
		while ( ( $row = $this -> db -> fetch_array ( $query ) ) != false )
		{
			$referconut = $row[ "referconut" ] ? $row[ "referconut" ] : '0';
			$browse = $row[ "browse" ] ? $row[ "browse" ] : '0';
			$responce -> rows[ $i ][ 'id' ] = $row[ "rand_key" ];
			$responce -> rows[ $i ][ 'cell' ] = un_iconv ( array (
			$row[ "class" ] ,
			$row[ "title" ] ,
			$row[ "user_name" ] . '<br>' . $row[ "updatedt" ] ,
			//$this->model_referconut ( $row["cid"] ) . ' / ' . $browse,
			$referconut . ' / ' . $browse ,
																	'' , 
																	'' , 
			$row[ "updatedt" ] ,
			$row[ "ascount" ] ,
			$row[ "browse" ] ,
			$row[ "levels" ] ,
			$row[ "status" ] ,
			$row[ "cid" ] ,
			$row[ 'class' ] ,
			$row[ 'pid' ]
			) );
			$i ++ ;
		}
		return json_encode ( $responce );
	}
	/**
	 * 审批人审批文章id
	 *
	 */
	function model_approvaler ( )
	{
		$typeidlist = '';
		$tyidL = array ();
		$sqltype = "select id from lore_type where find_in_set('" . $_SESSION[ 'USER_ID' ] . "',administrator)";
		$qtype = $this -> db -> query ( $sqltype );
		while ( ( $row = $this -> db -> fetch_array ( $qtype ) ) != false )
		{
			$typeidlist .= $row[ 'id' ] . ',';
		}
		$tyidL = explode ( ',' , $typeidlist );
		$tyidL = array_unique ( array_filter ( $tyidL ) );
		if ( is_array ( $tyidL ) )
		{
			foreach ( $tyidL as $value )
			{
				$sqlcon .= " typeid like '%$value%' or";
			}
			$sqlcon = "and ( " . rtrim ( $sqlcon , 'or' ) . ")";
		}
		$sqlsub = "select cid from lore_article where 1  $sqlcon";
		$qsub = $this -> db -> query ( $sqlsub );
		while ( ( $res = $this -> db -> fetch_array ( $qsub ) ) != false )
		{
			$datalist .= $res[ 'cid' ] . ',';
		}
		$datalist = rtrim ( $datalist , ',' );

		return $datalist;
	}
	/**
	 * 用户版块权限限制
	 *
	 * @param  $pid 版块id
	 * @return  boolean
	 *
	 */
	function usepower ( $pid )
	{

		global $func_limit;
		$adI = array ();
		$adminI = array ();
		$atI = array ();
		$deptI = array ();
		$deptsI = array ();
		$userI = array ();
		$usersI = array ();

		if ( $pid && $_SESSION[ 'USER_ID' ] )
		{
			$sql = "select id,administrator,userid,deptid,flag
						from lore_type 
						where  id='$pid' or pid='$pid' 
					  	";
			
 			$query = $this -> db -> query ( $sql );
			while ( ( $res = $this -> db -> fetch_array ( $query ) ) != false )
			{
				if ( $res[ 'administrator' ] && ( $res[ 'flag' ] == '1' || $res[ 'flag' ] == '2' ) )
				{
					$adminI = array_unique ( array_merge ( $adminI , explode ( ',' , $res[ 'administrator' ] ) ) );
				} else
				{
					$atI = array_unique ( array_merge ( $atI , explode ( ',' , $res[ 'administrator' ] ) ) );
				}
				if ( $res[ 'dept' ] && $res[ 'flag' ] == '1' )
				{
					$deptI = array_unique ( array_merge ( $deptI , explode ( ',' , current ( explode ( '|' , $res[ 'dept' ] ) ) ) ) );
					$deptsI = array_unique ( array_merge ( $deptsI , explode ( ',' , end ( explode ( '|' , $res[ 'dept' ] ) ) ) ) );
				}
				if ( $res[ 'userid' ] && $res[ 'flag' ] == '1' )
				{
					$userI = array_unique ( array_merge ( $userI , explode ( ',' , current ( explode ( '|' , $res[ 'userid' ] ) ) ) ) );
					$usersI = array_unique ( array_merge ( $usersI , explode ( ',' , end ( explode ( '|' , $res[ 'userid' ] ) ) ) ) );
				}
			}
			$adI = array_unique ( array_filter ( array (
			$func_limit[ '查看' ] == '1' ? 1 : false ,  //高级管理员
			in_array ( $_SESSION[ 'USER_ID' ] , $adminI , true ) ? 2 : false ,  //等级管理员和标签管理员
			in_array ( $_SESSION[ 'USER_ID' ] , $atI , true ) ? 3 : false ,  //文章审批管理员
			in_array ( $_SESSION[ 'DEPT_ID' ] , $deptI , true ) ? 4 : false ,  //使用部门
			in_array ( $_SESSION[ 'DEPT_ID' ] , $deptsI , true ) ? 5 : false ,  //浏览部门
			in_array ( $_SESSION[ 'USER_ID' ] , $userI , true ) ? 6 : false ,  //使用人
			in_array ( $_SESSION[ 'USER_ID' ] , $usersI , true ) ? 7 : false  //浏览人
			) ) );

		}
		return $adI;
	}

	function checkuser ( $pid )
	{
		$count = false;
		global $func_limit;
		if ( $pid )
		{
			$sqltype = "select id
						from lore_type 
						where  (id='$pid' and  find_in_set('" . $_SESSION[ 'DEPT_ID' ] . "',deptid) and flag='1')
					  	or ( find_in_set('" . $_SESSION[ 'USER_ID' ] . "',administrator) and  pid='$pid' and flag='3' )
					  	or ( find_in_set('" . $_SESSION[ 'USER_ID' ] . "',administrator) and  id='$pid' and flag='1' )
					  	or ( find_in_set('" . $_SESSION[ 'USER_ID' ] . "',userid) and  id='$pid' and flag='1' )";
			$res = $this -> db -> get_one ( $sqltype );
			if (  ! empty ( $res ) || $func_limit[ '查看' ] == 1 )
			{
				$count = true;
			}
			return $count;
		}
		return false;
	}
	function model_admind ( )
	{
		if ( $_SESSION[ 'USER_ID' ] )
		{
			$sqls = "select administrator from lore_type where 1";
			$qcontent = $this -> db -> query ( $sqls );
			$user_arr = array ();
			//array_push($user_arr,'admin');
			while ( ( $res = $this -> db -> fetch_array ( $qcontent ) ) != false )
			{
				$user_arr = array_unique ( array_merge ( $user_arr , explode ( ',' , $res[ 'administrator' ] ) ) );
			}
			$flag = array_search ( $_SESSION[ 'USER_ID' ] , $user_arr , true );
		}
		return $flag;
	}
	/**
	 * 消息管理员验证
	 */
	function checkmessage ( )
	{
		$pid = '';
		if ( $_SESSION[ 'USER_ID' ] )
		{
			$sqltype = "select id
						from lore_type
						where  find_in_set('" . $_SESSION[ 'USER_ID' ] . "',administrator) and flag='1' ";
			$qtype = $this -> db -> query ( $sqltype );
			while ( ( $row = $this -> db -> fetch_array ( $qtype ) ) != false )
			{
				$pid .= $row[ 'id' ] . ',';
			}
			return rtrim ( $pid , ',' );
		}
		return false;
	}
	/**
	 * 统计文章导出
	 * Enter description here ...
	 */
	function model_count_toexcel ( )
	{
		if ( trim ( $_GET[ 'typeclassid' ] ) == '1' )
		{
			$tits = '打回数';
			$titname = 'Statistics published articles';
		} else
		{
			$tits = '纠错数';
			$titname = 'Statistics of approval';
		}
		$Title = array (
		array (
								'员工名称' , 
								'文章数' , 
								'回复数' , 
								'浏览数' , 
		$tits
		)
		);
		$this -> model_countdata ( true );
		$data = $this -> cexceldata;
		$xls = new includes_class_excel ( );
		$xls -> SetTitle ( array (
		$titname
		) , $Title );
		$xls -> SetContent ( array (
		$data
		) );
		$xls -> OutPut ( );
	}
}

?>