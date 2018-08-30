<?php
class model_administration_overtime_record extends model_base
{
	function __construct ( )
	{
		parent :: __construct ( );
		$this -> tbl_name = 'overtime_record';
	}
	/**
	 * 列表
	 */
	function model_list ( $type = '' )
	{
		global $func_limit;
		if ( $func_limit )
		{
			$dept_id = $func_limit[ '访问部门' ];
		} else
		{
			$dept_id = $_SESSION[ 'DEPT_ID' ];
		}
		$where = $_GET[ 'dept_id' ] ? " b.dept_id=" . $_GET[ 'dept_id' ] : '';
		if ( $_GET[ 'dining' ] != '' )
		{
			$where .= $where ? " and a.dining=" . $_GET[ 'dining' ] : "a.dining=" . $_GET[ 'dining' ];
		}
		if ( $_GET[ 'station' ] != 'all' && $_GET[ 'station' ] != false )
		{
			$where .= ( $where ? ' and ' : '' ) . ( $_GET[ 'station' ] == 'notnull' ? " a.station !=''" : ( $_GET[ 'station' ] == 'isnull' ? " a.station=''" : " a.station='" . $_GET[ 'station' ] . "'" ) );
		}
		if ( $_GET[ 'work' ] )
		{
			$where .= ( $where ? ' and ' : '' ) . "work='" . $_GET[ 'work' ] . "'";
		}
		if ( trim ( $_GET[ 'start_date' ] ) && trim ( $_GET[ 'end_date' ] ) )
		{
			$where .= ( $where ? " and " : "" ) . "(a.date >='" . $_GET[ 'start_date' ] . "' and a.date <='" . $_GET[ 'end_date' ] . ' 23.59.59' . "')";
		}
		
		if ( $dept_id && $type != 'user' )
		{
			$where .= ( $where ? " and " : "" ) . " b.dept_id in($dept_id)";
		}
		if ( $type == 'user' )
		{
			$where .= ( $where ? ' and ' : '' ) . " a.userid='" . $_SESSION[ 'USER_ID' ] . "'";
		}
		$where = $where ? 'where ' . $where : "";
		if (  ! $this -> num )
		{
			$rs = $this -> get_one ( "select count(0) as num from overtime_record as a left join user as b on b.user_id=a.userid $where" );
			$this -> num = $rs[ 'num' ];
		}
		
		if ( $this -> num > 0 )
		{
			$query = $this -> query ( "
					select 
						a.*,b.user_name,c.dept_name,d.mobile1,d.mobile2,d.mobile3
					from 
						overtime_record as a
						left join user as b on b.user_id=a.userid
						left join department as c on c.dept_id=b.dept_id
						left join ecard as d on d.user_id=a.userid
					$where
					order by a.date desc, a.id desc
					" . ( $type != 'export' ? "limit $this->start," . pagenum : '' ) );
			$data = array ();
			while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
			{
				if ( $type == 'export' )
				{
					$data[ ] = $rs;
				} else
				{
					$mobile = $rs[ 'mobile1' ] ? $rs[ 'mobile1' ] : ( $rs[ 'mobile2' ] ? $rs[ 'mobile2' ] : $rs[ 'mobile3' ] );
					$str .= '<tr id="tr_' . $rs[ 'id' ] . '">';
					$str .= '<input type="hidden" id="key_' . $rs[ 'id' ] . '" value="' . $rs[ 'rand_key' ] . '" />';
					$str .= '<td>' . $rs[ 'id' ] . '</td>';
					$str .= '<td>' . $rs[ 'user_name' ] . '</td>';
					$str .= '<td>' . $rs[ 'dept_name' ] . '</td>';
					$str .= '<td>' . $mobile . '</td>';
					$str .= '<td id="dining_' . $rs[ 'id' ] . '">' . ( $rs[ 'dining' ] ? '是' : '<span>否</span>' ) . '</td>';
					//$str .= '<td id="station_' . $rs[ 'id' ] . '">' . ( $rs[ 'station' ] ? $rs[ 'station' ] : '<span>不坐车</span>' ) . '</td>';
					$str .= '<td id="remark_' . $rs[ 'id' ] . '">' . $rs[ 'remark' ] . '</td>';
					$str .= '<td id="work_' . $rs[ 'id' ] . '">' . $rs[ 'work' ] . '</td>';
					$str .= '<td id="date_' . $rs[ 'id' ] . '">' . $rs[ 'date' ] . '</td>';
					$str .= '<td>' . date ( 'Y-m-d H:i:s' , $rs[ 'post_time' ] ) . '</td>';
					$str .= '<td id="edit_' . $rs[ 'id' ] . '">' . ( $func_limit[ '增删改' ] ? '<a href="javascript:edit(' . $rs[ 'id' ] . ')">修改</a> | <a href="javascript:del(' . $rs[ 'id' ] . ')">删除</a>' : (  ! $func_limit[ '增删改' ] && time ( ) < strtotime ( $rs[ 'date' ] . ' 18:00:00' ) ? '<a href="javascript:edit(' . $rs[ 'id' ] . ')">修改</a> | <a href="javascript:del(' . $rs[ 'id' ] . ')">删除</a>' : '' ) ) . ' </td>';
					$str .= '</tr>';
				}
			}
			if ( $type == 'export' )
			{
				return $data;
			}
		}
		
		if ( $this -> num > 0 )
		{
			$showpage = new includes_class_page ( );
			$showpage -> show_page ( array ( 
											
											'total' => $this -> num , 
											'perpage' => pagenum 
			) );
			$showpage -> _set_url ( 'num=' . $this -> num . '&dept_id=' . $_GET[ 'dept_id' ] . '&dining=' . $_GET[ 'dining' ] . '&station=' . $_GET[ 'station' ] . '&start_date=' . $_GET[ 'start_date' ] . '&end_date=' . $_GET[ 'end_date' ] );
			$str .= '<tr><td colspan="10">' . $showpage -> show ( 6 ) . '</td></tr>';
		}
		return $str;
	}
	/**
	 * 添加
	 * @param $data
	 */
	function model_add ( $data )
	{
		if ( is_array ( $data ) )
		{
			foreach ( $data[ 'date' ] as $key => $row )
			{
				$week = date ( 'w' , strtotime ( $data[ 'date' ][ $key ] ) );
				$rs = $this -> get_one ( "
							select 
								id,rand_key
							from 
								overtime_record 
							where 
								userid='" . $data[ 'userid' ] . "' 
								and date = '" . $data[ 'date' ][ $key ] . "' 
								" );
				if ( $rs )
				{
					$this -> model_edit ( $rs[ 'id' ] , $rs[ 'rand_key' ] , array ( 
																					'dining' => $data[ 'dining' ][ $key ] , 
																					'station' => $data[ 'station' ][ $key ] , 
																					'work' => ( $data[ 'work' ][ $key ] ? $data[ 'work' ][ $key ] : '' ) , 
																					'date' => $data[ 'date' ][ $key ] ,
																					'remark' => $data[ 'remark' ][ $key ] , 
																					'post_time' => time ( ) 
					) );
				} else
				{
					$this -> create ( array ( 
												'userid' => $data[ 'userid' ] , 
												'dining' => $data[ 'dining' ][ $key ] , 
												'station' => $data[ 'station' ][ $key ] , 
												'work' => ( $data[ 'work' ][ $key ]? $data[ 'work' ][ $key ] : '' ),
												//'work' => ( $data[ 'work' ][ $key ] && $week != 0 && $week != 6 ? $data[ 'work' ][ $key ] : '' ) , 
												'date' => $data[ 'date' ][ $key ] , 
												'remark' => $data[ 'remark' ][ $key ] ,
												'post_time' => time ( ) 
					) );
				}
			}
			
			return true;
		} else
		{
			return false;
		}
		/*if ($data['thatday']==1)
			{
				$data['date'] = $data['date'] ? strtotime($data['date'].' '.date('H:i:s')) : time();
				$rs = $this->get_one("
							select 
								id,rand_key
							from 
								overtime_record 
							where 
								userid='".$data['userid']."' 
								and date = '".date('Y-m-d',$data['date'])."' 
								");
				if ($rs)
				{
					$this->model_edit($rs['id'],$rs['rand_key'],
															array(
															'dining'=>$data['dining'],
															'station'=>$data['station'],
															'work'=>($data['work'] ? $data['work'] : ''),
															'date'=>date('Y-m-d',$data['date']),
															'post_time'=>time()
															)
									);
				}else{
					$this->create(
									array(
											'userid'=>$data['userid'],
											'dining'=>$data['dining'],
											'station'=>$data['station'],
											'work'=>($data['work'] ? $data['work'] : ''),
											'date'=>date('Y-m-d',$data['date']),
											'post_time'=>time()
										)
								);
				}
			}
			if (date('w')==5)
			{
				//星期六
				if ($data['satur']==1)
				{
					$satur_date = strtotime('+1 day');
					$rs = $this->get_one("
										select 
											id , rand_key
										from 
											overtime_record 
										where 
											userid='".$data['userid']."' 
											and date ='".date('Y-m-d',$satur_date)."'
										");
					if ($rs)
					{
						//修改星期六
						$this->update(
							array('id'=>$rs['id'],'rand_key'=>$rs['rand_key']),
							array(
									'dining'=>$data['satur_dining'],
									'station'=>$data['satur_station'],
									'post_time'=>time()
							));
					}else{
						//添加星期六
						$this->create(
							array(
									'userid'=>$data['userid'],
									'dining'=>$data['satur_dining'],
									'station'=>$data['satur_station'],
									'date'=>date('Y-m-d',$satur_date),
									'post_time'=>strtotime('+1 day')
							)
						);
					}
					
				}
				//星期天
				if ($data['sun']==1)
				{
					$sun_date = strtotime('+2 day');
					$rs = $this->get_one("
										select 
											id , rand_key 
										from 
											overtime_record 
										where 
											userid='".$data['userid']."' 
											and date ='".date('Y-m-d',$sun_date)."'
										");
					if ($rs)
					{
						//修改星期天
						$this->update(
							array('id'=>$rs['id'],'rand_key'=>$rs['rand_key']),
							array(
									'dining'=>$data['sun_dining'],
									'station'=>$data['sun_station'],
									'post_time'=>time()
							));
					}else{
						//添加星期天
						$this->create(
							array(
									'userid'=>$data['userid'],
									'dining'=>$data['sun_dining'],
									'station'=>$data['sun_station'],
									'date'=>date('Y-m-d',$sun_date),
									'post_time'=>strtotime('+2 day')
							)
						);
					}
				}
			}
			
			if ($data['thatday'] || $data['satur'] || $data['sun'])
			{
				return true;
			}else{
				return false;
			}*/
	
	}
	/**
	 * 删除
	 * @param $id
	 * @param $key
	 */
	function model_del ( $id , $key )
	{
		return $this -> delete ( array ( 
										'id' => $id , 
										'rand_key' => $key 
		) );
	}
	/**
	 * 修改
	 * @param $id
	 * @param $key
	 * @param $data
	 */
	function model_edit ( $id , $key , $data )
	{
		return $this -> update ( array ( 
										'id' => $id , 
										'rand_key' => $key 
		) , $data );
	}
	/*
	 * 线路下拉
	 */
	function model_station_select ( $station = '' )
	{
		$this -> tbl_name = 'overtime_bus_line';
		$data = $this -> findAll ( );
		if ( $data )
		{
			foreach ( $data as $row )
			{
				$str .= '<option ' . ( $station == $row[ 'station' ] ? 'selected' : '' ) . ' value="' . $row[ 'station' ] . '">' . $row[ 'station' ] . '</option>';
			}
			return $str;
		} else
		{
			return false;
		}
	}
	/**
	 * 部门下拉
	 * @param $dept_id
	 */
	function model_dept_select ( $dept_id = '' )
	{
		global $func_limit;
		if ( $func_limit )
		{
			$dept_id = $func_limit[ '访问部门' ];
		} else
		{
			$dept_id = $_SESSION[ 'DEPT_ID' ];
		}
		$query = $this -> query ( "select dept_id,dept_name from  department where  dept_id in ($dept_id)" );
		while ( ( $rs = $this -> fetch_array ( $query ) ) != false )
		{
			$str .= '<option value="' . $rs[ 'dept_id' ] . '">' . $rs[ 'dept_name' ] . '</option>';
		}
		return $str;
	
	}
	/**
	 * 导出数据
	 */
	function export_data ( )
	{
		$data = $this -> model_list ( 'export' );
		if ( $data )
		{
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ( );
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			
			$PHPExcel -> setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel -> getActiveSheet ( );
			//=================表头名称设置======================
			$objActSheet -> setTitle ( un_iconv ( '延迟下班记录' ) );
			$objActSheet -> setCellValue ( 'A1' , un_iconv ( '序号' ) );
			$objActSheet -> setCellValue ( 'B1' , un_iconv ( '姓名' ) );
			$objActSheet -> setCellValue ( 'C1' , un_iconv ( '所属部门' ) );
			$objActSheet -> setCellValue ( 'D1' , un_iconv ( '联系电话' ) );
			$objActSheet -> setCellValue ( 'E1' , un_iconv ( '是否用餐' ) );
			$objActSheet -> setCellValue ( 'F1' , un_iconv ( '备注' ) );
			$objActSheet -> setCellValue ( 'G1' , un_iconv ( '下班时间' ) );
			$objActSheet -> setCellValue ( 'H1' , un_iconv ( '延迟下班日期' ) );
			$objActSheet -> setCellValue ( 'I1' , un_iconv ( '提交日期时间' ) );
			//================表头样式设置==============
			$objActSheet -> getStyle ( 'A1' ) -> getFill ( ) -> setFillType ( PHPExcel_Style_Fill :: FILL_SOLID );
			$objActSheet -> getStyle ( 'A1' ) -> getFill ( ) -> getStartColor ( ) -> setRGB ( 'c0c0c0' );
			$objActSheet -> getStyle ( 'A1' ) -> getFont ( ) -> setBold ( true );
			$objActSheet -> getStyle ( 'A1' ) -> getAlignment ( ) -> setVertical ( PHPExcel_Style_Alignment :: VERTICAL_CENTER );
			$objActSheet -> getStyle ( 'A1' ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
			$objActSheet -> getStyle ( 'A1' ) -> getAlignment ( ) -> setWrapText ( true );
			$A1Style = $objActSheet -> getStyle ( 'A1' );
			$objBorderA1 = $A1Style -> getBorders ( );
			$objBorderA1 -> getTop ( ) -> setBorderStyle ( PHPExcel_Style_Border :: BORDER_THIN );
			$objBorderA1 -> getTop ( ) -> getColor ( ) -> setARGB ( '00000000' ); // color      
			$objBorderA1 -> getBottom ( ) -> setBorderStyle ( PHPExcel_Style_Border :: BORDER_THIN );
			$objBorderA1 -> getLeft ( ) -> setBorderStyle ( PHPExcel_Style_Border :: BORDER_THIN );
			$objBorderA1 -> getRight ( ) -> setBorderStyle ( PHPExcel_Style_Border :: BORDER_THIN );
			$objActSheet -> duplicateStyle ( $A1Style , 'A1:I1' );
			$objActSheet -> getRowDimension ( '1' ) -> setRowHeight ( 30 );
			//============================设置单元宽度============================
			$Width_Array = array ( 
											
											'A' => 8 , 
											'B' => 18 , 
											'C' => 15 , 
											'D' => 15 , 
											'E' => 10 , 
											'F' => 15 , 
											'G' => 12 , 
											'H' => 18 , 
											'I' => 20 
			);
			foreach ( $Width_Array as $key => $val )
			{
				$objActSheet -> getColumnDimension ( $key ) -> setWidth ( $val );
			}
			//===========================输出内容==========================
			$i = 1;
			foreach ( $data as $key => $rs )
			{
				$i ++ ;
				$rs = un_iconv ( $rs );
				$mobile = $rs[ 'mobile1' ] ? $rs[ 'mobile1' ] : ( $rs[ 'mobile2' ] ? $rs[ 'mobile2' ] : $rs[ 'mobile3' ] );
				$objActSheet -> setCellValue ( 'A' . $i , $rs[ 'id' ] );
				$objActSheet -> setCellValue ( 'B' . $i , $rs[ 'user_name' ] );
				$objActSheet -> setCellValue ( 'C' . $i , $rs[ 'dept_name' ] );
				$objActSheet -> setCellValue ( 'D' . $i , $mobile );
				$objActSheet -> setCellValue ( 'E' . $i , ( $rs[ 'dining' ] ? un_iconv ( '是' ) : un_iconv ( '否' ) ) );
				$objActSheet -> setCellValue ( 'F' . $i , ( $rs[ 'station' ] ? $rs[ 'station' ] : un_iconv ( '不坐车' ) ) );
				$objActSheet -> setCellValue ( 'G' . $i , $rs[ 'work' ] );
				$objActSheet -> setCellValue ( 'H' . $i , $rs[ 'date' ] );
				$objActSheet -> setCellValue ( 'I' . $i , date ( 'Y-m-d H:i:s' , $rs[ 'post_time' ] ) );
			}
			$objActSheet -> setCellValue ( 'I' . ( $i + 3 ) , un_iconv ( '合计：' . ( $i - 1 ) . ' 人' ) );
			//=======================================
			header ( "Pragma: public" );
			header ( "Expires: 0" );
			header ( "Cache-Control:must-revalidate, post-check=0, pre-check=0" );
			header ( "Content-Type:application/force-download" );
			header ( "Content-Type: application/vnd.ms-excel;" );
			header ( "Content-Type:application/octet-stream" );
			header ( "Content-Type:application/download" );
			header ( "Content-Disposition:attachment;filename=" . time ( ) . '.xls' );
			header ( "Content-Transfer-Encoding:binary" );
			$objWriter -> save ( 'php://output' );
		}
	}
}
?>