<?php
class model_product_demand extends model_base
{
	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'product';
	}
	
	/**
	 * 列表
	 */
	function model_showlist()
	{
		global $func_limit;
		$id = intval($_GET[ 'id' ]);
		$start_date = $_GET[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ];
		$where .= ($_GET['status'] || $_GET['status'] == '0') ? " and a.status='" . $_GET['status'] . "'" : '';
		$where .= $where && $_GET['product_id'] ? " and a.product_id='" . $_GET['product_id'] . "'" : ($_GET['product_id'] ? " and  a.product_id='" . $_GET['product_id'] . "'" : '');
		if ($_GET['username'])
		{
			$userid = $this->get_table_fields ( 'user', "user_name='" . $_GET['username'] . "'", 'user_id' );
			if ($userid)
			{
				$where .=  " and userid='$userid'";
			}
		}
		if ( $id )
		{
			$where .= " and a.id like '%$id%'";
		}
		if($start_date){
			$where .= "  and a.date>='".strtotime($start_date)."'" ;
		}
		if($end_date){
			$where .=  "  and a.date<='".strtotime($end_date)."'" ;
		}
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(0) as num from product_demand_info as a where 1 $where" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query ( "
									select
										a.*,b.product_name,b.manager,b.assistant,c.user_name,d.user_name as manager_name,e.user_name as assistant_name
									from 
										product_demand_info as a
										left join product as b on b.id=a.product_id
										left join user as c on c.user_id=a.userid
										left join user as d on d.user_id=b.manager
										left join user as e on e.user_id=b.assistant
									where 1  $where
									order by a.id desc
									limit $this->start," . pagenum . "
								" );				
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				switch ($rs['status'])
				{
					case - 1 :
						$status = '<span class="purple"><a href="javascript:show_notse('.$rs['id'].')" title="查看详细说明">被打回</a></span>';
						break;
					case 1 :
						$status = '<span class="green">审核通过</span>';
						break;
					case 2 :
						$status = '已实现';
						break;
					default :
						$status = '<span>待审核</span>';
				}
				$fd = ($rs['feedback'] == 1 ? '是' : '否');
				$fdlink = '<a href="?model=product_demand&action=upfd&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&feedback=' . $rs['feedback'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=300" title="设置序号为  ' . $rs['id'] . ' 的客户反馈状态" class="thickbox" />' . $fd . '</a>';
				$editlink = '<a href="?model=product_demand&action=edit&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500" title="修改序号为 ' . $rs['id'] . ' 的需求" class="thickbox"">修改</a>';
				$del_link = ' | '.thickbox_link('删除','a','id=' . $rs['id'] . '&key=' . $rs['rand_key'],'删除需求',null,'del',300,200);
				$infolink = thickbox_link ( '查看', 'a', 'id=' . $rs['id'] . '&key=' . $rs['rand_key'], '查看序号为 ' . $rs['id'] . ' 的详细信息', null, 'full_info', null, 600 );
				$str .= '
						<tr>
							<div id="notse_'.$rs['id'].'" style="display:none">'.$rs['notse'].'</div>
							<td>' . $rs['id'] . '</td>
							<td>' . date ( 'y-m-d', $rs['date'] ) . '</td>
							<td>' . $rs['product_name'] . '</td>
							<td>' . $rs['manager_name'] . '</td>
							<td>' . $rs['user_name'] . '</td>
							<td>' . ($rs['degree'] == 2 ? '紧急' : '普通') . '</td>
							<td>' . $status . '</td>
							<td>' . ($rs['userid'] == $_SESSION['USER_ID'] ? $fdlink : $fd) . '</td>
							<td nowrap width="15%">' . strip_tags($rs['description']) . '</td>
							<td nowrap width="15%">' . ($rs['upfile'] ? '<a href="upfile/product/demand/'.$rs['FilePathName'].'/'.$rs['upfile'].'" target="_blank"><img src="images/w_fActionSave.gif" border="0"/></a>' : '').$rs['step'] . '</td>
							<td>' . $rs['unit'] . '</td>
							<td>' . $rs['contact'] . '</td>
							<td>' . $rs['mobile'] . '</td>
							<td width="8%">' . ($rs['status'] <= 0 && $rs['userid'] == $_SESSION['USER_ID'] ? $editlink : $infolink) . ($func_limit['删除权限']==1 ? $del_link : '').'</td>
						</tr>
				';
			}
			$showpage = new includes_class_page ();
			$showpage->show_page ( array(
				
						'total'=>$this->num,
						'perpage'=>pagenum
			) );
			$showpage->_set_url ( 'num=' . $this->num . '&product_id=' . $_GET['product_id'] . '&status=' . $_GET['status'] . '&username=' . urlencode($_GET['username']) );
			return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
		}
	
	}
	/**
	 * 统计记录
	 */
	function model_count_list()  
	{
		$start_date = $_GET[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ];
		
		$where .= ($_GET['status'] || $_GET['status'] == '0') ? " and  a.status='" . $_GET['status'] . "'" : '';
		$where .= $where && $_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : ($_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : '');
		if ($_GET['username'])
		{
			$userid = $this->get_table_fields ( 'user', "user_name='" . $_GET['username'] . "'", 'user_id' );
			if ($userid)
			{
				$where .= " and userid='$userid'";
			}
		}
		if($start_date){
			$where .= "  and a.date>='".strtotime($start_date)."'" ;
		}
		if($end_date){
			$where .=  "  and a.date<='".strtotime($end_date)."'" ;
		}
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(distinct(a.userid)) as num from product_demand_info as a where 1 $where" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query ( "
									select 
										distinct(a.userid),user_name
									from 
										product_demand_info as a 
										left join user as b on b.user_id=a.userid
									where 1 $where
									limit $this->start," . pagenum . "	
										" );
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				$sql = $this->query ( "select count(0) as num,a.userid,a.status from product_demand_info as a where 1 $where and a.userid='" . $rs['userid'] . "' group by a.userid,a.status" );
				$data = array();
				while ( ($row = $this->fetch_array ( $sql )) != false )
				{
					$data[$row['status']] = $row['num'] ? $row['num'] : 0;
				}
				$str .= '
						<tr>
							<td>' . $rs['user_name'] . '</td>
							<td>' . array_sum ( $data ) . '</td>
							<td width="10%">' . ($data['0'] ? $data['0'] : 0) . '</td>
							<td>' . round ( ($data['0'] * 100) / array_sum ( $data ), 2 ) . '%</td>
							<td width="10%">' . ($data['-1'] ? $data['-1'] : 0) . '</td>
							<td>' . round ( ($data['-1'] * 100) / array_sum ( $data ), 2 ) . '%</td>
							<td width="10%">' . ($data['1'] ? $data['1'] : 0) . '</td>
							<td>' . round ( ($data['1'] * 100) / array_sum ( $data ), 2 ) . '%</td>
							<td width="10%">' . ($data['2'] ? $data['2'] : 0) . '</td>
							<td>' . round ( ($data['2'] * 100) / array_sum ( $data ), 2 ) . '%</td>
						</tr>
				';
			}
			$showpage = new includes_class_page ();
			$showpage->show_page ( array(
				
						'total'=>$this->num,
						'perpage'=>pagenum
			) );
			$showpage->_set_url ( 'num=' . $this->num . '&product_id=' . $_GET['product_id'] . '&status=' . $_GET['status'] . '&username=' . $_GET['username'] );
			return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
		}
	}
	
	/**
	 * 获取单条记录
	 * @param $id
	 */
	function get_info($id, $key)
	{
		if (intval ( $id ))
		{
			$this->tbl_name = 'product_demand_info';
			return $this->find ( array(
				
						'id'=>$id,
						'rand_key'=>$key
			) );
		}
	}
	/**
	 * 新增
	 */
	function model_save_add($data)
	{
		if (is_array ( $data ))
		{
			if ($_FILES['upfile']['name'])
			{
				$tempname = $_FILES['upfile']['tmp_name'];
				$filename = $_FILES['upfile']['name'];
				$FilePathName = md5(time().rand(0,99999));
				$data['FilePathName'] = $FilePathName;
				if (!is_dir(WEB_TOR.'upfile/product/demand/'.$FilePathName))
				{
					if (! mkdir ( WEB_TOR . 'upfile/product/demand/'. $FilePathName ))
					{
						showmsg ( '上传附件失败，请与管理员联系！！' );
					}
				}
				if (move_uploaded_file ( str_replace('\\\\','\\',$tempname), WEB_TOR . 'upfile/product/demand/'. $FilePathName . '/' . $filename ))
				{
					$data['upfile'] .= $filename;
				} else
				{
					showmsg ( '上传附件失败，请与管理员联系！' );
				}
			}
			$this->tbl_name = 'product_demand_info';
			if ($this->create ( $data ))
			{
				//以下为发送邮件内容
				$row = $this->get_one ( "select product_name,manager,assistant from product where id=" . $data['product_id'] );
				$assistant = explode ( ',', $row['assistant'] );
				$userid_arr = array(
					
							$row['manager'],
							'bill.tsao',
							'chunxiong.chen',
							'xiaoming.geng',
							'yun.xia',
							'ying.zhang',
							'zhen.wang',
							'yingjian.luo',
							$_SESSION['USER_ID']
				);
				$userid = array_merge ( $userid_arr, $assistant );
				$gl = new includes_class_global ();
				$username = $gl->GetUserinfo ( $data['userid'], 'user_name' );
				$Address = $gl->get_email ( $userid );
				$Email = new includes_class_sendmail ();
				$body .='所属产品：'.$row['product_name'].'<br />';
				$body .='紧急程度：'.($data['degree']==1 ? '普通' : '紧急').'<br />';
				$body .='需求状态：待审核<br />';
				$body .='<hr />产品需求描述：<br />';
				$body .= preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($data['description']));
				//$body .= str_replace('src="/OA/attachment/FckUpload/','src="http://127.0.0.1/web/oa/oa/OA/attachment/FckUpload/',stripslashes($data['description']));
				$body .=oaurlinfo;
				return $Email->send ( $username . '提交了产品需求', $body, $Address );
			}
		}
	}
	/**
	 * 修改
	 */
	function model_save_edit($data, $id, $key)
	{
		if (is_array ( $data ))
		{
			$this->tbl_name = 'product_demand_info';
			$rs = $this->find(array('id'=>$id,'rand_key'=>$key));
			//var_dump($rs);
			//exit();
			if ($rs)
			{
				if ($data['delfile']=='1' && $rs['upfile'])
				{
					unlink(WEB_TOR . 'upfile/product/demand/'. $rs['FilePathName'].'/'.$rs['upfile']);
					$data['upfile'] = '';
				}
				unset($data['delfile']);
				if ($_FILES['upfile']['name'])
				{
					$tempname = $_FILES['upfile']['tmp_name'];
					$filename = $_FILES['upfile']['name'];
					$FilePathName = $rs['FilePathName'] ? $rs['FilePathName'] : md5(time().rand(0,9999));
					$data['FilePathName'] = $FilePathName;
					if (!is_dir(WEB_TOR.'upfile/product/demand/'.$FilePathName))
					{
						if (! mkdir ( WEB_TOR . 'upfile/product/demand/'. $FilePathName ))
						{
							showmsg ( '上传附件失败，请与管理员联系！！' );
						}
					}
					if (move_uploaded_file ( str_replace('\\\\','\\',$tempname), WEB_TOR . 'upfile/product/demand/'. $FilePathName . '/' . $filename ))
					{
						$data['upfile'] .= $filename;
						if ($rs['upfile'])
						{
							unlink(WEB_TOR . 'upfile/product/demand/'. $rs['FilePathName'].'/'.$rs['upfile']);
						}
					} else
					{
						showmsg ( '上传附件失败，请与管理员联系！' );
					}
				}
				$data['status'] = 0;
				return $this->update ( array(
					
							'id'=>$id,
							'rand_key'=>$key
				), $data );
			}
		}
	}
	/**
	 * 删除
	 */
	function model_delete($id,$key)
	{
		$this->tbl_name = 'product_demand_info';
		return $this->delete(array('id'=>$id,'rand_key'=>$key));
	}
	/**
	 * 审核
	 * @param $status
	 * @param $id
	 * @param $key
	 */
	function model_audit($status, $id, $key, $notse = '')
	{
		if ($status && $id && $key)
		{
			$this->tbl_name = 'product_demand_info';
			$this->update ( array(
				
						'id'=>$id,
						'rand_key'=>$key
			), array(
				
						'status'=>$status,
						'notse'=>$notse
			) );
			return $this->send_email ( $id, $key );
		} else
		{
			return false;
		}
	}
	/**
	 * 显示实现
	 * @param $id
	 * @param $key
	 * @param $date
	 */
	function model_realize($id, $key, $date, $version)
	{
		if ($id && $key && $date)
		{
			$this->tbl_name = 'product_demand_info';
			$this->update ( array(
				
						'id'=>$id,
						'rand_key'=>$key
			), array(
				
						'status'=>2,
						'realize_date'=>$date,
						'version'=>$version
			) );
			return $this->send_email ( $id, $key, 'realize' );
		}
	}
	
	/**
	 * 设置反馈状态
	 * @param $id
	 * @param $key
	 * @param $feedback
	 */
	function model_upfd($id, $key, $feedback)
	{
		if ($id && $key)
		{
			$this->tbl_name = 'product_demand_info';
			$this->update ( array(
				
						'id'=>$id,
						'rand_key'=>$key
			), array(
				
						'feedback'=>$feedback
			) );
			return $feedback==1 ? $this->send_email ( $id, $key, 'upfd' ) : true;
		} else
		{
			return false;
		}
	}
	/**
	 * 发送邮件
	 * @param $id
	 * @param $key
	 * @param $type
	 */
	function send_email($id, $key, $type = 'audit')
	{
		$rs = $this->get_one ( "
									select 
										a.*,b.product_name,b.manager,b.assistant 
									from 
										product_demand_info as a 
										left join product as b on b.id=a.product_id
									where 
										a.id='$id' and a.rand_key='$key'
						" );
		$userid_arr = array(//固定要发送的人员
			
					$rs['userid'],
					$rs['manager'],
					'bill.tsao',
					'chunxiong.chen',
					'xiaoming.geng',
					'yun.xia',
					'ying.zhang',
					'zhen.wang'
		);
		$assistant = explode ( ',', $rs['assistant'] );
		$userid = array_merge ( $userid_arr, $assistant );
		$gl = new includes_class_global ();
		$username = $gl->GetUserinfo ( $rs['userid'], 'user_name' );
		$body .='所属产品：'.$rs['product_name'].'<br />';
		$body .='紧急程度：'.($rs['degree']==1 ? '普通' : '紧急').'<br />';
		$body .='需求状态：'.($rs['status']==0 ? '待审核' : ($rs['status']==1 ? '通过审核' : ($rs['status']==2 ? '已实现' : '被打回')));
		$body .= ($type=='realize' ? '<br />产品版本：'.$rs['version'] : '');
		$body .='<hr />产品描述：'.preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($rs['description']));
		$body .=($rs['status']!=0 ? '<hr />备注说明：<br />'.$rs['notse'] : '');
		
		$body .=oaurlinfo;
		$Address = $gl->get_email ( $userid );
		$Email = new includes_class_sendmail ();
		if ($type == 'audit')
		{
			return $Email->send ( $_SESSION['USERNAME'] . ($rs['status'] == 1 ? ' 通过审核了 ' : ' 打回了 ') . $username . ' 提交的产品需求', $body, $Address );
		} elseif ($type == 'upfd')
		{
			return $Email->send ( $username . ' 提交的产品需求已经反馈给客户', $body, $Address );
		} elseif ($type == 'realize')
		{
			return $Email->send ( $username . ' 提交的产品需求已经实现', $body, $Address );
		}
	}
	/**
	 * 导出(列表)EXCEL表
	 */
	function model_list_export()
	{
		
		$start_date = $_GET[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ];
		$where .= ($_GET['status'] || $_GET['status'] == '0') ? " and  a.status='" . $_GET['status'] . "'" : '';
		$where .= $where && $_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : ($_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : '');
		if ($_GET['username'])
		{
			$userid = $this->get_table_fields ( 'user', "user_name='" . $_GET['username'] . "'", 'user_id' );
			if ($userid)
			{
				$where .= " and userid='$userid'" ;
			}
		}
		if($start_date){
			$where .= "  and a.date>='".strtotime($start_date)."'" ;
		}
		if($end_date){
			$where .=  "  and a.date<=".strtotime($end_date) ;
		}
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(0) as num from product_demand_info as a where 1 $where" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query ( "
									select
										a.*,b.product_name,b.manager,b.assistant,c.user_name,d.user_name as manager_name,e.user_name as assistant_name
									from 
										product_demand_info as a
										left join product as b on b.id=a.product_id
										left join user as c on c.user_id=a.userid
										left join user as d on d.user_id=b.manager
										left join user as e on e.user_id=b.assistant
									where 1 $where
									order by a.id desc
								" );
			
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ();
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			
			$PHPExcel->setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel->getActiveSheet ();
			//=================表头名称设置======================
			$objActSheet->setTitle ( un_iconv ( '产品需求列表' ) );
			$objActSheet->setCellValue ( 'A1', un_iconv ( '序号' ) );
			$objActSheet->setCellValue ( 'B1', un_iconv ( '提出日期' ) );
			$objActSheet->setCellValue ( 'C1', un_iconv ( '提出人' ) );
			$objActSheet->setCellValue ( 'D1', un_iconv ( '所属产品' ) );
			$objActSheet->setCellValue ( 'E1', un_iconv ( '产品经理' ) );
			$objActSheet->setCellValue ( 'F1', un_iconv ( '产品助理' ) );
			$objActSheet->setCellValue ( 'G1', un_iconv ( '需求紧急程度' ) );
			$objActSheet->setCellValue ( 'H1', un_iconv ( '需求状态' ) );
			$objActSheet->setCellValue ( 'I1', un_iconv ( '反馈给客户' ) );
			$objActSheet->setCellValue ( 'J1', un_iconv ( "产品需求描述\r\n（含应用目的）" ) );
			$objActSheet->setCellValue ( 'K1', un_iconv ( '客户建议的实现方式' ) );
			$objActSheet->setCellValue ( 'L1', un_iconv ( '需求实现步骤' ) );
			$objActSheet->setCellValue ( 'M1', un_iconv ( '需求实现算法' ) );
			$objActSheet->setCellValue ( 'N1', un_iconv ( '提出单位' ) );
			$objActSheet->setCellValue ( 'O1', un_iconv ( '提出人' ) );
			$objActSheet->setCellValue ( 'P1', un_iconv ( '提出人手机' ) );
			$objActSheet->setCellValue ( 'Q1', un_iconv ( '提出人Email' ) );
			$objActSheet->setCellValue ( 'R1', un_iconv ( '产品实现日期' ) );
			$objActSheet->setCellValue ( 'S1', un_iconv ( '产品版本号' ) );
			$objActSheet->setCellValue ( 'T1', un_iconv ( '被打回理由' ) );
			
			//================表头样式设置==============
			$objActSheet->getStyle ( 'A1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objActSheet->getStyle ( 'A1' )->getFill ()->getStartColor ()->setRGB ( 'c0c0c0' );
			$objActSheet->getStyle ( 'A1' )->getFont ()->setBold ( true );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setWrapText ( true );
			$A1Style = $objActSheet->getStyle ( 'A1' );
			$objBorderA1 = $A1Style->getBorders ();
			$objBorderA1->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
			$objBorderA1->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objActSheet->duplicateStyle ( $A1Style, 'A1:T1' );
			$objActSheet->getRowDimension ( '1' )->setRowHeight ( 30 );
			//============================设置单元宽度============================
			$Width_Array = array(
				
						'A'=>8,
						'B'=>18,
						'C'=>15,
						'D'=>15,
						'E'=>10,
						'F'=>10,
						'G'=>8,
						'H'=>10,
						'I'=>6,
						'J'=>25,
						'K'=>30,
						'L'=>25,
						'M'=>25,
						'N'=>28,
						'O'=>15,
						'P'=>15,
						'Q'=>20,
						'R'=>25,
						'S'=>20,
						'T'=>30
			);
			foreach ( $Width_Array as $key => $val )
			{
				$objActSheet->getColumnDimension ( $key )->setWidth ( $val );
			}
			//===========================输出内容==========================
			$i = 1;
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				switch ($rs['status'])
				{
					case - 1 :
						$status = '被打回';
						break;
					case 1 :
						$status = '审核通过';
						break;
					case 2 :
						$status = '已实现';
						break;
					default :
						$status = '待审核';
				}
				$i ++;
				$rs = un_iconv ( $rs );
				$objActSheet->setCellValue ( 'A' . $i, $rs['id'] );
				$objActSheet->setCellValue ( 'B' . $i, date ( 'Y-m-d', $rs['date'] ) );
				$objActSheet->setCellValue ( 'C' . $i, $rs['user_name'] );
				$objActSheet->setCellValue ( 'D' . $i, $rs['product_name'] );
				$objActSheet->setCellValue ( 'E' . $i, $rs['manager_name'] );
				$objActSheet->setCellValue ( 'F' . $i, $rs['assistant_name'] );
				$objActSheet->setCellValue ( 'G' . $i, ($rs['degree'] == 2 ? un_iconv ( '紧急' ) : un_iconv ( '普通' )) );
				$objActSheet->setCellValue ( 'H' . $i, un_iconv ( $status ) );
				$objActSheet->setCellValue ( 'I' . $i, ($rs['feedback'] == 1 ? un_iconv ( '是' ) : un_iconv ( '否' )) );
				$objActSheet->setCellValue ( 'J' . $i, str_replace('&nbsp;','',strip_tags(htmlspecialchars_decode($rs['description']))));
				$objActSheet->setCellValue ( 'K' . $i, $rs['realize'] );
				$objActSheet->setCellValue ( 'L' . $i, $rs['step'] );
				$objActSheet->setCellValue ( 'M' . $i, $rs['algorithm'] );
				$objActSheet->setCellValue ( 'N' . $i, $rs['unit'] );
				$objActSheet->setCellValue ( 'O' . $i, $rs['contact'] );
				$objActSheet->setCellValue ( 'P' . $i, $rs['mobile'] );
				$objActSheet->setCellValue ( 'Q' . $i, $rs['email'] );
				$objActSheet->setCellValue ( 'R' . $i, $rs['realize_date'] );
				$objActSheet->setCellValue ( 'S' . $i, $rs['version'] );
				$objActSheet->setCellValue ( 'T' . $i, $rs['notse'] );
			}
			if ($i > 1)
			{
				$A2Style = $objActSheet->getStyle ( 'A2' );
				$objBorderA2 = $A2Style->getBorders ();
				$objBorderA2->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
				$objBorderA2->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setWrapText ( true );
				
				$objActSheet->duplicateStyle ( $A2Style, 'A2:T' . $i );
			}
			
			//=======================================
			header ( "Pragma: public" );
			header ( "Expires: 0" );
			header ( "Cache-Control:must-revalidate, post-check=0, pre-check=0" );
			header ( "Content-Type:application/force-download" );
			header ( "Content-Type: application/vnd.ms-excel;" );
			header ( "Content-Type:application/octet-stream" );
			header ( "Content-Type:application/download" );
			header ( "Content-Disposition:attachment;filename=" . time () . '.xls' );
			header ( "Content-Transfer-Encoding:binary" );
			$objWriter->save ( 'php://output' );
			//=========
		

		}
	}
	/**
	 * 导出统计EXCEL表
	 */
	function model_count_export()
	{
		$start_date = $_GET[ 'start_date' ];
		$end_date = $_GET[ 'end_date' ];
		
		$where .= ($_GET['status'] || $_GET['status'] == '0') ? " and a.status='" . $_GET['status'] . "'" : '';
		$where .= $where && $_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : ($_GET['product_id'] ? " and product_id='" . $_GET['product_id'] . "'" : '');
		if ($_GET['username'])
		{
			$userid = $this->get_table_fields ( 'user', "user_name='" . $_GET['username'] . "'", 'user_id' );
			if ($userid)
			{
				$where .= " and userid='$userid'";
			}
		}
		if($start_date){
			$where .= "  and a.date>='".strtotime($start_date)."'" ;
		}
		if($end_date){
			$where .=  "  and a.date<=".strtotime($end_date) ;
		}
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(distinct(a.userid)) as num from product_demand_info as a where 1 $where" );
			$this->num = $rs['num'];
		}
		if ($this->num)
		{
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			$PHPExcel = new PHPExcel ();
			$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			$objWriter = new PHPExcel_Writer_Excel5 ( $PHPExcel );
			$PHPExcel->setActiveSheetIndex ( 0 );
			$objActSheet = $PHPExcel->getActiveSheet ();
			//=====================设置表头==========================
			$objActSheet->setTitle ( un_iconv ( '产品需求统计表' ) );
			
			$objActSheet->setCellValue ( 'A1', un_iconv ( '提交人' ) );
			$objActSheet->getColumnDimension ( 'A' )->setWidth ( 15 );
			$objActSheet->setCellValue ( 'B1', un_iconv ( '总提交次数' ) );
			$objActSheet->getColumnDimension ( 'B' )->setWidth ( 15 );
			$objActSheet->mergeCells ( 'C1:D1' );
			$objActSheet->setCellValue ( 'C1', un_iconv ( '待审核数' ) );
			$objActSheet->mergeCells ( 'E1:F1' );
			$objActSheet->setCellValue ( 'E1', un_iconv ( '被打回数' ) );
			$objActSheet->mergeCells ( 'G1:H1' );
			$objActSheet->setCellValue ( 'G1', un_iconv ( '已确认数' ) );
			$objActSheet->mergeCells ( 'I1:J1' );
			$objActSheet->setCellValue ( 'I1', un_iconv ( '已实现数' ) );
			//================表头样式设置==============
			$objActSheet->getStyle ( 'A1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
			$objActSheet->getStyle ( 'A1' )->getFill ()->getStartColor ()->setRGB ( 'c0c0c0' );
			$objActSheet->getStyle ( 'A1' )->getFont ()->setBold ( true );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
			$objActSheet->getStyle ( 'A1' )->getAlignment ()->setWrapText ( true );
			$A1Style = $objActSheet->getStyle ( 'A1' );
			$objBorderA1 = $A1Style->getBorders ();
			$objBorderA1->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
			$objBorderA1->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objBorderA1->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
			$objActSheet->duplicateStyle ( $A1Style, 'A1:J1' );
			$objActSheet->getRowDimension ( '1' )->setRowHeight ( 25 );
			//===========================================================
			$query = $this->query ( "
									select 
										distinct(a.userid),user_name
									from 
										product_demand_info as a 
										left join user as b on b.user_id=a.userid
									     where 1
									     		$where
										" );
			$i = 1;
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				$i ++;
				$rs = un_iconv ( $rs );
				$objActSheet->setCellValue ( 'A' . $i, $rs['user_name'] );
				$sql = $this->query ( "select count(0) as num,a.userid,a.status from product_demand_info as a where 1 and  a.userid='" . $rs['userid'] . "' group by a.userid,a.status" );
				$data = array();
				while ( ($row = $this->fetch_array ( $sql )) != false )
				{
					$data[$row['status']] = $row['num'] ? $row['num'] : 0;
				}
				$objActSheet->setCellValue ( 'B' . $i, array_sum ( $data ) );
				$objActSheet->setCellValue ( 'C' . $i, ($data['0'] ? $data['0'] : 0) );
				$objActSheet->setCellValue ( 'D' . $i, round ( ($data['0'] * 100) / array_sum ( $data ), 2 ) . '%' );
				$objActSheet->setCellValue ( 'E' . $i, ($data['-1'] ? $data['-1'] : 0) );
				$objActSheet->setCellValue ( 'F' . $i, round ( ($data['-1'] * 100) / array_sum ( $data ), 2 ) . '%' );
				$objActSheet->setCellValue ( 'G' . $i, ($data['1'] ? $data['1'] : 0) );
				$objActSheet->setCellValue ( 'H' . $i, round ( ($data['1'] * 100) / array_sum ( $data ), 2 ) . '%' );
				$objActSheet->setCellValue ( 'I' . $i, ($data['2'] ? $data['2'] : 0) );
				$objActSheet->setCellValue ( 'J' . $i, round ( ($data['2'] * 100) / array_sum ( $data ), 2 ) . '%' );
			}
			//========================================
			if ($i > 1)
			{
				$A2Style = $objActSheet->getStyle ( 'A2' );
				$objBorderA2 = $A2Style->getBorders ();
				$objBorderA2->getTop ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getTop ()->getColor ()->setARGB ( '00000000' ); // color      
				$objBorderA2->getBottom ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getLeft ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objBorderA2->getRight ()->setBorderStyle ( PHPExcel_Style_Border::BORDER_THIN );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setVertical ( PHPExcel_Style_Alignment::VERTICAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objActSheet->getStyle ( 'A2' )->getAlignment ()->setWrapText ( true );
				
				$objActSheet->duplicateStyle ( $A2Style, 'A2:J' . $i );
			}
			//=======================================
			header ( "Pragma: public" );
			header ( "Expires: 0" );
			header ( "Cache-Control:must-revalidate, post-check=0, pre-check=0" );
			header ( "Content-Type:application/force-download" );
			header ( "Content-Type: application/vnd.ms-excel;" );
			header ( "Content-Type:application/octet-stream" );
			header ( "Content-Type:application/download" );
			header ( "Content-Disposition:attachment;filename=" . time () . '.xls' );
			header ( "Content-Transfer-Encoding:binary" );
			$objWriter->save ( 'php://output' );
			//=========
		}
	}
	//====================================================
	/**
	 * 产品列表
	 */
	function model_type_list()
	{
		$product_manager = false;
		$product_audit = false;
		$group = new model_system_usergroup_list();
		$group_id = $group->GetId('product_manager'); // 获取产品管理员
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$product_manager = true;
		}
		$group_id = $group->GetId('product_audit'); // 获取产品审批人员
		if ($group_id && $group->CheckUser($group_id, $_SESSION['USER_ID']))
		{
			$product_audit = true;
		}
		
		
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(0) as num from product" );
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query ( "
									select 
										a.*,b.user_name,d.typename as tname
										
									from
										product as a
										left join user as b on b.user_id=a.manager
										left join product_type as d on d.id=a.typeid
									order by a.id desc
										" );
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				$user = $this->get_username ( $rs['assistant'] );
				$str .= '
						<tr id="tr_' . $rs['id'] . '">
							<td><a href="javascript:show_project(' . $rs['id'] . ')"><img id="a_' . $rs['id'] . '" src="images/work/plus.png" border="0"/></a></td>
							<td>'.$rs['tname'].'</td>
							<td>' . $rs['product_name'] . '</td>
							<td>' . $rs['en_product_name'] . '</td>
							<td>' . $rs['hasp'] . '</td>
							<td>' . $rs['user_name'] . '</td>
							<td>' . ($user ? implode ( ' 、', $user ) : '') . '</td>
							<td style="width:250px;word-break:break-all;">' . $rs['description'] . '</td>
							<td>'.($rs['status']==1 ? '审批通过' :($rs['status']==-1 ? '<a href="?model='.$_GET['model'].'&action=show_remark&id='.$rs['id'].'&key='.$rs['rand_key'].'&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=300" class="thickbox" title="查看被打回理由">被打回</a>' : '<span>待审批</span>')).'</td>';
				$str .='<td>';
				//$str .='<a href="javascript:show_project(' . $rs['id'] . ')" id="a_' . $rs['id'] . '">查看项目</a>';
				if ($product_manager || $product_audit)
				{
					$str .='<a href="?model=product_demand&action=edit_type&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=500" class="thickbox" title="修改 ' .$rs['product_name'] . '">修改</a>';
				}
				if (($product_manager || $product_audit) && $rs['status'] == 1)
				{
					$str .='| <a href="?model=product_demand&action=delete_type&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=400" class="thickbox" title="删除 ' . $rs['product_name'] . '">删除</a>';
				}
				
				if ($rs['status'] == 0 && $product_audit)
				{
					$str .='| <a href="?model=product_demand&action=audit_type&id=' . $rs['id'] . '&key=' . $rs['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=400" class="thickbox" title="审批 ' . $rs['product_name'] . ' 产品">审批产品</a>';
				}
				$str .='</td></tr>';
			}
			return $str;
		} else
		{
			return false;
		}
	}
	/**
	 * 关联项目
	 * 
	 * @param unknown_type $product_id
	 * @param unknown_type $html
	 */
	function model_project($product_id,$html=false)
	{
		$query = $this->query("
								select
									a.*,c.user_name
								from
									project_info as a
									left join user as c on c.user_id=a.manager
								where
									find_in_set($product_id,a.product_id_str)
									order by a.id desc
		");
		$data =array();
		$str ='';
		while (($rs = $this->fetch_array($query))!=false)
		{
			if (!$html)
			{
				$data[] = $rs;
			}else{
				//$str .=....
			}
		}
		return $html ? $str : $data;
	}
	/**
	 * 获取所有产品
	 */
	function get_typelist()
	{
		$this->tbl_name = 'product';
		return $this->findAll (array('status'=>1));
	}
	/**
	 * 获取单条需求数据
	 * @param unknown_type $id
	 * @param unknown_type $key
	 */
	function get_typeinfo($id, $key)
	{
		if ($id && $key)
		{
			return $this->get_one ( "
									select 
										a.*,b.user_name
										
									from
										product as a
										left join user as b on b.user_id=a.manager
									where 
										a.id=$id and a.rand_key='$key'
								" );
		}
	}
	/**
	 * 获取用户姓名
	 * @param unknown_type $userid_str
	 */
	function get_username($userid_str)
	{
		if ($userid_str)
		{
			$arr = explode ( ',', $userid_str );
			$user = array();
			foreach ( $arr as $val )
			{
				$user[] = "'$val'";
			}
			$query = $this->query ( "select user_id,user_name from user where user_id in (" . implode ( ',', $user ) . ")" );
			$data = array();
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				$data[$rs['user_id']] = $rs['user_name'];
			}
			return $data;
		} else
		{
			return false;
		}
	}
	/**
	 * 保存类型
	 */
	function model_save_type($data)
	{
		if (is_array ( $data ))
		{
			
			if ($data['assistant_name'])
			{
				$assistant_name = explode(',', $data['assistant_name']);
				$in = array();
				$user_id_arr = array();
				foreach ($assistant_name as $val)
				{
					if (trim($val))
					{
						$in [] = "'" . trim($val) . "'";
					}
				}
				if ($in)
				{
					$query = $this->query ( "select user_id from user where user_name in(" . implode ( ',', $in ) . ")" );
					while ( ($rs = $this->fetch_array ( $query )) != false )
					{
						$user_id_arr [] = $rs ['user_id'];
					}
				}
			}
			$data['assistant'] = $user_id_arr ? implode(',', $user_id_arr) : '';
			$data['project_id'] = $data['project_id'] ? implode(',',$data['project_id']) : '';
			$this->tbl_name = 'product';
			if ($_GET['type'] == 'add')
			{
				return $this->create ( $data );
			} else if ($_GET['type'] == 'edit' && intval ( $_GET['id'] ))
			{
				$rs = $this->GetOneInfo(array('id'=>$_GET['id'],'rand_key'=>$_GET['key']));
				if ($rs)
				{
					if ($rs['status'] == -1)
					{
						$data['status'] = 0;
						$group = new model_system_usergroup_list();
						$group_id = $group->GetId('product_audit'); // 获取产品审批人员
						$address = $group->GetGroupUserEmail($group_id);
						if ($address)
						{
							$email = new includes_class_sendmail();
							$email->send($_SESSION['USERNAME'].' 从新提交了产品添加申请,有劳您登录OA审批', $_SESSION['USERNAME'].' 从新提交了产品添加申请，产品名称：'.$data['product_name'].',详情请登录OA查看！'.oaurlinfo, $address);
						}
					}else{
						$product = array();
						$product['company'] = 1;
						$product['tid'] = $rs['id'];
						$product['name'] = $data['product_name'];
						$product['code'] = $rs['id'];
						$product['PO'] = $data['manager'];
						$product['desc'] = $data['description'];
						$product['status'] = 'normal';
						$pms = new api_pms();
						$result = $pms->GetModule('product', 'edit&params=tid='.$rs['id'],un_iconv($product),'post'); 
					}
					return $this->update ( array('id'=>$_GET['id'],'rand_key'=>$_GET['key']), $data );
				}
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 删除产品
	 */
	function model_delete_type($id, $key)
	{
		if (intval ( $id ) && $key)
		{
			$rs = $this->get_one("select * from product_demand_info where product_id=$id");
			if (!$rs)
			{
				$this->tbl_name = 'product';
				return $this->delete ( array(
					
							'id'=>$id,
							'rand_key'=>$key
				) );
			}else{
				showmsg('对不起，该产品还有需求信息存在暂不能删除！');
			}
		}
	}
	
}

?>