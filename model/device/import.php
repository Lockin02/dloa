<?php
class model_device_import extends model_base
{
	function __construct ( )
	{
		parent :: __construct ( );
	}
	/**
	 * 读取EXCEL文件
	 * @param $filename
	 */
	function get_excel ( $filename , $sheet = '' )
	{
		if ( file_exists ( $filename ) )
		{
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';
			$PHPExcel = new PHPExcel ( );
			$PHPReader = new PHPExcel_Reader_Excel2007 ( $PHPExcel );
			if (  ! $PHPReader -> canRead ( $filename ) )
			{
				$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			}
			if ( $PHPReader -> canRead ( $filename ) )
			{
				$Excel = $PHPReader -> load ( $filename );
				$data = array ();
				if ( $sheet || $sheet == 0 )
				{
					$data[ $sheet ] = $Excel -> getSheet ( $sheet ) -> toArray ( );
					$data[ $sheet ][ 'title' ] = $Excel -> getSheet ( $sheet ) -> getTitle ( );
					unset ( $Excel , $PHPReader , $PHPExcel );
					return $data;
				}
				
				$countnum = $Excel -> getSheetCount ( );
				for ( $i = 0 ; $i < $countnum ; $i ++  )
				{
					$data[ $i ] = $Excel -> getSheet ( $i ) -> toArray ( );
					$data[ $i ][ 'title' ] = $Excel -> getSheet ( $i ) -> getTitle ( );
				
				}
				return $data;
			} else
			{
				return false;
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 保存数据到指定数据库表中
	 * @param unknown_type $table
	 * @param unknown_type $data
	 */
	function model_save_data ( $table , $data )
	{
		$this -> tbl_name = $table;
		try
		{
			$this -> createAll ( $data );
			return true;
		} catch ( Exception $e )
		{
			return false;
		}
	}
	
	function import_data ( $data )
	{
		global $func_limit;
		if ( $data[ 'filename' ] && file_exists ( $data[ 'filename' ] ) )
		{
			$sheet = ( $data[ 'sheet' ] - 1 );
			$Excel = $this -> get_excel ( $data[ 'filename' ] , $sheet );
			$area = array ( 
							
							'珠海' => 1 , 
							'北京' => 2 , 
							'上海' => 3 , 
							'广州' => 4 , 
							'西安' => 6 , 
							'沈阳' => 7 ,
							'杭州' => 8 ,
							'成都' => 9 ,
							'昆明' => 10 ,
							'长沙' => 11 ,
							'新疆' => 12 ,
							'南京' => 13 ,
							'全国' => 14 ,
							'河南' => 15
							
			);
			$dept_id = $data[ 'dept_id' ] ? $data[ 'dept_id' ] : ( $func_limit[ '管理部门' ] ? $func_limit[ '管理部门' ] : $_SESSION[ 'DEPT_ID' ] );
			if ( $Excel )
			{
				$Excel = mb_iconv ( $Excel );
				$this -> _db -> ping ( );
				try
				{
					$this -> _db -> query ( "START TRANSACTION" );
					echo "<!---";
					foreach ( $Excel[ $sheet ] as $key => $row )
					{
						$excBorrowNum='';
						$excProjectNo='';
						$excBorrower='';
						$excBorrowDate='';
						$amount='';
						$date='';
						$price='';
						$depreciationYear='';
						$temp = is_array ( $row ) ? implode ( '' , $row ) : false;
						if ( $key >= ( $data[ 'start_data' ] - 1 ) && $temp )
						{
							$typeid = $data[ 'typeid' ] ? $data[ 'typeid' ] : ( $data[ 'field_id_typeid' ] == 'title' ? $this -> insert_type ( $Excel[ $sheet ][ 'title' ] , $dept_id ) : $this -> insert_type ( $row[ $data[ 'field_id_typeid' ] ] , $dept_id ) );
							$list_id = $data[ 'list_id' ] ? $data[ 'list_id' ] : ( $data[ 'field_id_device' ] == 'title' ? $this -> insert_device ( $typeid , $Excel[ $sheet ][ 'title' ] , $dept_id ) : $this -> insert_device ( $typeid , $row[ $data[ 'field_id_device' ] ] , $dept_id ) );
							$area_id = $data[ 'area' ] ? $data[ 'area' ] : ( $data[ 'field_id_area' ] == 'title' ? $area[ $Excel[ $sheet ][ 'title' ] ] : $area[ $row[ $data[ 'field_id_area' ] ] ] );
							if ( $data[ 'field' ] )
							{
								$field_content_arr = array ();
								$field_update_content = array ();
								$key_table_content_arr = array ();
								$key_table_update_content_arr = array ();
								$key_table_content_arr[ ] = $data[ 'operation' ] == 'update' ? "dept_id in ($dept_id)" : "dept_id ='$dept_id'";
								if ( $list_id && $list_id != 'on' )
									$key_table_content_arr[ ] = "list_id='$list_id'";
								$key_table_content_arr[ ] = "area='$area_id'";
								
								foreach ( $data[ 'field' ] as $k => $v )
								{
									$str = '';
									if ( is_numeric ( $v ) )
									{
										$field_content_arr[ $v ] = trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
										$field_update_content[ ] = sprintf ( "content='%s'" , trim ( $row[ ( $data[ 'content' ][ $k ] ) ] ) );
									} else
									{
										$row[ ( $data[ 'content' ][ $k ] ) ] = $v == 'amount' ? intval ( $row[ ( $data[ 'content' ][ $k ] ) ] ) : $row[ ( $data[ 'content' ][ $k ] ) ];
										$str = sprintf ( "%s='%s'" , $v , ( $v == 'date' ? strtotime ( trim ( $row[ ( $data[ 'content' ][ $k ] ) ] ) ) : trim ( $row[ ( $data[ 'content' ][ $k ] ) ] ) ) );
										if ( $data[ 'operation' ] != 'update' )
										{
											if ($v=='excBorrowNum'){
												$excBorrowNum=trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											if ($v=='excProjectNo'){
												$excProjectNo=trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											if ($v=='excBorrower'){
												$excBorrower=trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											if ($v=='excBorrowDate'){
												$excBorrowDate=trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											if ($v=='amount'){
												$amount=trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											if ($v=='date'){
												$date=trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											if ($v=='price'){
												$price=trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											if ($v=='depreciationYear'){
												$depreciationYear=(int)trim ( $row[ ( $data[ 'content' ][ $k ] ) ] );
											}
											
											if ($v!='excBorrowNum'&&$v!='excProjectNo'&&$v!='excBorrower'&&$v!='excBorrowDate')
											{
												$key_table_content_arr[ ] = $str;
											}	
										}
										$key_table_update_content_arr[ ] = $str;
									}
								}
								if ( $data[ 'operation' ] == 'update' )
								{
									//更新字段条件
									$where = '';
									$where .= ' and ';
									if ( $data[ 'term_field' ] )
									{
										foreach ( $data[ 'term_field' ] as $m => $j )
										{
											if ( $m >= 1 )
											{
												$where .= ' ' . $data[ 'where' ][ ( $m - 1 ) ] . ' ';
											}
											if ( is_numeric ( $j ) )
											{
												$where .= "(b.field_id='$j' and b.content" . $data[ 'operator' ][ $m ] . "'" . trim ( $row[ ( $data[ 'term_content' ][ $m ] ) ] ) . "')";
											} else
											{
												$where .= "(a.$j " . $data[ 'operator' ][ $m ] . "'" . trim ( $row[ ( $data[ 'term_content' ][ $m ] ) ] ) . "')";
											}
										}
									}
									$where .= ( $key_table_content_arr ? ' and (a.' . implode ( ' and a.' , $key_table_content_arr ) . ')' : '' );
									$res = $this -> query ( "
									select
										a.id,b.info_id
									from
										device_info as a
										left join device_type_field_content as b on b.info_id=a.id
									where 1 and a.quit='0'  
										$where
								" );
								

								while ( ( $rs = $this -> fetch_array ( $res ) ) != false )
			                    {
									$info_id = $rs[ 'id' ] ? $rs[ 'id' ] : $rs[ 'info_id' ];
									if ( $info_id )
									{
										if ( $field_content_arr )
										{
											foreach ( $field_content_arr as $kk => $vv )
											{
												$rt = $this -> get_one ( "select id from device_type_field_content where info_id='$info_id' and field_id='$kk'" );
												echo "select id from device_type_field_content where info_id='$info_id' and field_id='$kk'";
												if ( $rt )
												{
													$this -> query ( "
													  update device_type_field_content set content='$vv' where info_id='$info_id' and field_id='$kk'
												" );
												} else
												{
													$this -> query ( "insert into device_type_field_content set info_id='$info_id',field_id='$kk',content='$vv'" );
												}
											}
										}
										if ( $key_table_update_content_arr )
										{
											$this -> query ( "
											update device_info set " . implode ( ',' , $key_table_update_content_arr ) . " where id='$info_id'
										" );
										echo "
											update device_info set " . implode ( ',' , $key_table_update_content_arr ) . " where id='$info_id'
										" ;
										}
									}
			                    }	
								} else
								{
									//插入新数据
									if (  ! in_array ( 'date' , $data[ 'field' ] ) )
									{
										$key_table_content_arr[ ] = "date='" . time ( ) . "'";
									}
									
									if (  ! in_array ( 'amount' , $data[ 'field' ] ) )
									{
										$key_table_content_arr[ ] = "amount='1'";
									}
	                                if ($depreciationYear&&$price)
									{
										$yedept='';
										/*
										if($date){
											$dates= round((time()-strtotime($date))/3600/24) ;
											$depreciationYearDay=round((strtotime("+ $depreciationYear month",strtotime($date))-strtotime($date))/3600/24);
											if($dates>$depreciationYearDay||$dates>$depreciationYearDay){
												$yedept=0;
											}else{
												$yedept=round($price/$depreciationYearDay,2);
											}
										}else{
											$dates= round((time()-strtotime($date))/3600/24) ;
											$depreciationYearDay=round((strtotime("+ $depreciationYear month",strtotime($date))-strtotime($date))/3600/24);
											if($dates>$depreciationYearDay||$dates==$depreciationYearDay){
												$yedept=0;
											}else{
												$yedept=round($price/$depreciationYearDay,2);
											}
										}*/
										$depreciationYearDay=round((strtotime("+ $depreciationYear month",strtotime($date))-strtotime($date))/3600/24);
										$yedept=round($price/$depreciationYearDay,2);
										$key_table_content_arr[ ] = "depreciation='$yedept'";
									}
	
									if ( $typeid && $list_id )
									{
										echo "insert into device_info set " . ( $key_table_content_arr ? implode ( ',' , $key_table_content_arr ) : '' );
										$this -> query ( "insert into device_info set " . ( $key_table_content_arr ? implode ( ',' , $key_table_content_arr ) : '' ) );
										$info_id = $this -> _db -> insert_id ( );
										if ( $field_content_arr )
										{
											foreach ( $field_content_arr as $m => $c )
											{
												$this -> query ( "insert into device_type_field_content set info_id='$info_id',field_id='$m',content='$c'" );
											}
										}
										if ( $info_id &&in_array ( 'excBorrowNum' , $data[ 'field' ] )&&
										in_array ( 'excProjectNo' , $data[ 'field' ] )&&in_array ( 'excBorrower' , $data[ 'field' ] )&&
										in_array ( 'excBorrowDate' , $data[ 'field' ] )&&$excBorrowNum&&$excBorrowNum!='0')
										{
											if($excProjectNo){
												$projectI=$this->model_get_projectInfo($excProjectNo);
											}
											if($excBorrower){
												$userI=$this->model_get_userInfo($excBorrower);
											}
											$inData['infoId']=$info_id;
											$inData['areaId']=$area_id;
											$inData['typeId']=$typeid;
											$inData['listId']=$list_id;
											$inData['userId']=$userI['USER_ID'];
											$inData['deptId']=$userI['DEPT_ID'];;
											$inData['projectId']=$projectI['id'];
											$inData['pManagerId']=$projectI['manager'];
											$inData['borrower']=$excBorrower;
											$inData['amount']=$amount?$amount:1;
											$inData['borrowDate']=date('Y-m-d',strtotime($excBorrowDate));
											$inData['borrowNum']=$excBorrowNum;
											$this->model_import_borrow_device($inData);
										}
									}
								}
							}
						}
					}
					 echo "-->";
					$this -> _db -> query ( "COMMIT" );
					return $this -> query ( "
											update 
												device_list as a, 
												(select sum(amount)  as num ,sum(borrow_num)as borrow_num, avg(price) as average ,list_id  from device_info where quit=0 group by list_id   ) as b  
											set 
												a.total=b.num,
												a.average=b.average,
												a.borrow=b.borrow_num,
												a.surplus=b.num-b.borrow_num,
												a.rate=(CAST((b.borrow_num/b.num) AS DECIMAL(11,2)))  
												where a.id=b.list_id 
										" );
				} catch ( Exception $e )
				{
					$this -> _db -> query ( "ROLLBACK" );
					return false;
				}
			} else
			{
				return false;
			}
		}
	}
	/**
	 * 借出设备
	 */
	function model_import_borrow_device ($data )
	{
		if ($data&&is_array($data) )
		{
			$this -> tbl_name = 'device_borrow_order';
			$orderid = $this -> create ( array ( 
												
												'userid' => $data[ 'userId' ] , 
												'dept_id' => $data[ 'deptId' ] , 
												'project_id' => $data[ 'projectId' ] , 
												'operatorid' => $_SESSION[ 'USER_ID' ] , 
												'manager' => $data[ 'pManagerId' ] , 
												'area' => $data[ 'areaId' ] , 
												'date' => strtotime ( $data[ 'borrowDate' ] ) 
			) );
			$this -> tbl_name = 'device_borrow_order_info';
			$info = $this -> create ( array ( 
												'orderid' => $orderid , 
												'info_id' => $data[ 'infoId' ] , 
												'typeid' => $data[ 'typeId' ] , 
												'list_id' => $data[ 'listId' ] , 
												'amount' => intval ( $data[ 'borrowNum' ] ) , 
												'date' => strtotime ( $data[ 'borrowDate' ] ) 
			) );
			if ( $info )
			{
				$state='';
				$state=$data[ 'borrowNum' ]&&$data[ 'borrowNum' ]!='0'?1:'0';
				$this -> _db -> query ( "update device_info set borrow_num=borrow_num+" . $data[ 'borrowNum' ] . ",`state`='$state' where id=" . $data[ 'infoId' ] );
				$row = $this -> _db -> get_one ( '
					select sum(amount) as num,sum(amount*price) as money,SUM(borrow_num) as  borrows from 
					device_info where list_id=' . $data[ 'listId' ]);
				$average = $row[ 'money' ] / $row[ 'num' ];
				$average=$average?$average:0;
				$row[ 'num' ]=$row[ 'num' ]?$row[ 'num' ]:0;		
				
				$this -> _db -> query ( "update device_list set total=" . $row[ 'num' ] . ",borrow=borrow+" . $data[ 'borrowNum' ] . ",surplus=total-borrow,average=$average, rate=(1-CAST((borrow/total) AS DECIMAL(11,2)))  where id=" . $data[ 'listId' ] );	

				
			}
			return $this -> _db -> query ( "update device_borrow_order set amount='" . $data[ 'borrowNum' ] . "' where id=$orderid" );
		}
	}
	function model_get_projectInfo($projectNo){
		$rs=array();
		if($projectNo){
			$rs = $this -> get_one ( "SELECT * FROM `project_info` WHERE number='" . $projectNo. "'" );
		}
		return $rs;
	}
	function model_get_userInfo($userName){
		if($userName){
			$rs = $this -> get_one ( "SELECT * FROM `user` WHERE USER_NAME='" . trim($userName). "'" );
		}
		return $rs;
	}
	/**
	 * 增加新设备类别
	 * @param string $typename
	 */
	function insert_type ( $typename , $dept_id = '' )
	{
		$dept_id = $dept_id ? $dept_id : $_SESSION[ 'DEPT_ID' ];
		if ( $typename )
		{
			$rs = $this -> get_one ( "select id from device_type where dept_id='$dept_id' and typename='" . trim ( $typename ) . "'" );
			if ( $rs )
			{
				return $rs[ 'id' ];
			} else
			{
				$this -> query ( "insert into device_type set dept_id='" . $dept_id . "',typename='" . trim ( $typename ) . "'" );
				return $this -> _db -> insert_id ( );
			}
		}
	}
	/**
	 * 增加新设备
	 * @param $typeid
	 * @param $device_name
	 */
	function insert_device ( $typeid , $device_name , $dept_id = '' )
	{
		$dept_id = $dept_id ? $dept_id : $_SESSION[ 'DEPT_ID' ];
		if ( $typeid && $device_name )
		{
			$rs = $this -> get_one ( "select id from device_list where typeid='$typeid' and dept_id='$dept_id' and device_name='" . trim ( $device_name ) . "'" );
			if ( $rs )
			{
				return $rs[ 'id' ];
			} else
			{
				$this -> query ( "insert into device_list set typeid='$typeid',dept_id='" . $dept_id . "',device_name='" . trim ( $device_name ) . "',unit='套'" );
				return $this -> _db -> insert_id ( );
			}
		}
	}
	
}

?>