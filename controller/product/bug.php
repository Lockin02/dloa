<?php
class controller_product_bug extends model_product_bug
{
	private $show;
	
	function __construct()
	{
		parent::__construct();
		$this->show = new show();
		$this->show->path = 'product/bug/';
	}
	/**
	 * 首页
	 */
	function c_index()
	{
		$this->c_list();
	}
	/**
	 * 列表
	 */
	function c_list()
	{

		$product_id = $_GET['product_id'] ? $_GET['product_id'] : $_POST['product_id'];
		$start_date = $_GET['start_date'] ? $_GET['start_date'] : $_POST['start_date'];
		$end_date = $_GET['end_date'] ? $_GET['end_date'] : $_POST['end_date'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$username = $_GET['username'] ? $_GET['username'] : $_POST['username'];
		$status = $_GET['status'] || $_GET['status']=='0' ? $_GET['status'] : $_POST['status'];
		
		$product = new model_product_demand();
		$typeinfo = $product->get_typelist();
		$options= '';
		if ($typeinfo)
		{
			foreach ($typeinfo as $row)
			{
				if($row['status']==1)
				{
					$options .= '<option '.($product_id == $row['id'] ? 'selected' : '').' value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
				}
			}
		}
		$arr = $_GET['sort'] ? explode ( '-', $_GET['sort'] ) : '';
		$find = $arr[0];
		$sotr = $arr[1];
		$this->show->assign('update_time_sort',sort_img('a.update_time',$find=='a.update_time' ? $sotr : ''));
		$this->show->assign('options',$options);
		$this->show->assign('username',$username?$username:'');
		$this->show->assign('start_date',$start_date);
		$this->show->assign('end_date',$end_date);
		$this->show->assign('keyword',$keyword);
		$this->show->assign('status',$status);
		$this->show->assign('list',$this->BugList());
		$this->show->display('list');
	}
	/**
	 * 统计列表
	 */
	function c_countlist()
	{
		$product = new model_product_demand();
		$typeinfo = $product->get_typelist();
		foreach ( $typeinfo as $row )
		{
			if ($row['status']==1)
			{
				$str .= '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
			}
		}
		$this->show->assign ( 'typelist', $str );

		$this->show->assign ( 'username', $_GET['username'] );
			$this->show->assign ( 'start_date', $_GET['start_date']?$_GET['start_date']:'' );
		$this->show->assign ( 'end_date', $_GET['end_date']?$_GET['end_date']:'' );
		$this->show->assign('list',$this->CountList());
		$this->show->display('count');
	}
	/**
	 * 导出EXCEL表
	 */
	function c_export()
	{
		if ($_GET['type'] == 'list')
		{
			$data = $this->BugList(true);
			$excel_data = array();
			if ($data)
			{
				foreach ( $data as $key=>$rs)
				{
					$excel_data[$key][] = $rs['id'];
					$excel_data[$key][] = date('Y-m-d',$rs['date']);
					$excel_data[$key][] = date('Y-m-d H:i:s',$rs['update_time']);
					$excel_data[$key][] = $rs['user_name'];
					$excel_data[$key][] = $rs['manager_name'];
					$excel_data[$key][] = $rs['description'];
					$excel_data[$key][] = $rs['data_info'];
					$excel_data[$key][] = $rs['version'];
					$excel_data[$key][] = ($rs['status']==0 ? '待确认' : ($rs['status']==1 ? '已确认是Bug' : ($rs['status']==2 ?'已解决' : '被打回')));
					$excel_data[$key][] = $rs['feedback']==0 ? '未反馈' : '已反馈' ;
					$excel_data[$key][] = $rs['unit'];
					$excel_data[$key][] = $rs['contact'];
					$excel_data[$key][] = $rs['mobile'];
					$excel_data[$key][] = $rs['email'];
				}
			}
			$Title = array('序号','提交日期','最后更新时间','提交人','接 收 人','Bug详细描述(Bug重现条件)','Bug数据路径及数据名称','产品版本号(含日期)','解决情况','是否已反馈给客户','提出单位','提出人','提出人手机','提出人Email');
			$excel = new includes_class_excel();
			$excel->SetTitle(array('Bug列表'),array($Title));
			$excel->SetContent(array($excel_data));
			$total = (count($data) - 1);
			$excel->objActSheet[0]->setCellValue('A'.($total+5),un_iconv('合计：'.$total));
			$excel->OutPut();
		} else if ($_GET['type'] == 'count')
		{
			$data = $this->CountList(true);
			$excel_data[] = array('','','已解决Bug','','已确认Bug','','待确认Bug','','被打回','');
			if ($data)
			{
				foreach ($data as $key=>$rs)
				{
					$key++;
					$excel_data[$key][] = $rs['user_name'];
					$excel_data[$key][] = $rs['tatol'];
					$excel_data[$key][] = ($rs['be_num'] ? $rs['be_num']:0);
					$excel_data[$key][] = round($rs['be_num'] * 100 /$rs['tatol'],2).'%';
					$excel_data[$key][] = ($rs['confirm'] ? $rs['confirm'] : 0);
					$excel_data[$key][] = round($rs['confirm'] * 100 /$rs['tatol'],2).'%';
					$excel_data[$key][] = ($rs['solution']?$rs['solution']:0);
					$excel_data[$key][] = round($rs['solution'] * 100 /$rs['tatol'],2).'%';
					$excel_data[$key][] = ($rs['back']?$rs['back']:0);
					$excel_data[$key][] = round($rs['back'] * 100 /$rs['tatol'],2).'%';
				}
			}
			$Title = array('提交人','提交总数','Bug状态');
			$excel = new includes_class_excel();
			$excel->SetTitle(array('Bug统计表'),array($Title));
			$excel->objActSheet[0]->mergeCells('A1:A2');
			$excel->objActSheet[0]->mergeCells('B1:B2');
			$excel->objActSheet[0]->mergeCells('C1:J1');
			$excel->objActSheet[0]->mergeCells('C2:D2');
			$excel->objActSheet[0]->mergeCells('E2:F2');
			$excel->objActSheet[0]->mergeCells('G2:H2');
			$excel->objActSheet[0]->mergeCells('I2:J2');
			$excel->objActSheet[0]->getStyle('C2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$excel->objActSheet[0]->getStyle('C2:I2')->getFont()->setBold(true);
			$excel->SetContent(array($excel_data));
			$total = (count($data));
			$excel->objActSheet[0]->setCellValue('A'.($total+5),un_iconv('合计：'.$total));
			$excel->OutPut();
		}
	}
	/**
	 * 添加
	 */
	function c_add()
	{
		if ($_POST)
		{
			$data = array();
			$data = $_POST;
			$data['userid'] = $_SESSION['USER_ID'];
			$data['date'] = time();
			if ($this->Add($data))
			{
				showmsg('操作成功！', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败！');
			}
			
		}else{
			$product = new model_product_demand();
			$typeinfo = $product->get_typelist();
			$options= '';
			if ($typeinfo)
			{
				foreach ($typeinfo as $row)
				{
					if ($row['status']==1)
					{
						$options .= '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
					}
				}
			}
			$this->show->assign('options',$options);
			$this->show->display('add');
		}
	}
	/**
	 * 修改
	 */
	function c_edit()
	{
		if ($_POST)
		{
			if ($this->Edit($_GET['id'],$_GET['key'],$_POST))
			{
				showmsg('操作成功！','self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			$data = $this->find(array('id'=>$_GET['id']));
			if ($data)
			{
				foreach ($data as $key=>$val)
				{
					if ($key=='file_str' && $val)
					{
						$arr = explode(',',$val);
						$temp = '';
						foreach ($arr as $k=>$v)
						{
							$temp .= '<div id="pic_'.$k.'" style="text-align:center;border:1px solid #cccccc;width:115px;float:left;margin-left:5px;margin-top:5px;">';
							$temp .= '<a href="upfile/product/bug/'.$data['upfile_dir'].'/'.$v.'" target="_blank"><img src="upfile/product/bug/'.$data['upfile_dir'].'/'.$v.'" border="0" width="100" height="100"/></a><br />';
							$temp .= '<a href="javascript:del_file('.$k.','.$data['id'].',\''.$data['rand_key'].'\',\''.$v.'\')">删除</a></div>';
						}
						$val = $temp ? $temp : '';
					}
					$this->show->assign($key,$val);
				}
			}
			$product = new model_product_demand();
			$typeinfo = $product->get_typelist();
			$options= '';
			if ($typeinfo)
			{
				foreach ($typeinfo as $row)
				{
					if ($row['status']==1)
					{
						$options .= '<option '.($row['id']==$data['product_id'] ? 'selected' : '').' value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
					}
				}
			}
			$this->show->assign('options',$options);
			$this->show->display('edit');
		}
	}
	/**
	 * 删除
	 */
	function c_del()
	{
		if ($_GET['id'] && $_GET['key'])
		{
			if ($this->Del($_GET['id'],$_GET['key']))
			{
				showmsg('操作成功！');
			}else{
				showmsg('操作失败！');
			}
		}else{
			
		}
	}
	/**
	 * 显示信息
	 */
	function c_showinfo()
	{
		if ($_GET['id'] && $_GET['key'])
		{
			$data = $this->find(array('id'=>$_GET['id'],'rand_key'=>$_GET['key']));
			if ($data)
			{
				foreach ($data as $key =>$val)
				{
					if ($key=='file_str' && $val)
					{
						$arr = explode(',',$val);
						$temp = '';
						foreach ($arr as $k=>$v)
						{
							$temp .= '<div id="pic_'.$k.'" style="text-align:center;border:1px solid #cccccc;width:115px;float:left;margin-left:5px;margin-top:5px;">';
							$temp .= '<a href="upfile/product/bug/'.$data['upfile_dir'].'/'.$v.'" target="_blank"><img src="upfile/product/bug/'.$data['upfile_dir'].'/'.$v.'" border="0" width="100" height="100"/></a></div>';
						}
						$val = $temp ? $temp.'<br />' : '';
					}else if ($key == 'status'){
						switch ($val)
						{
							case -1:
								$status = '<span class="blue">被打回，理由是:</span> '.$data['notes'];
								break;
							case 0:
								$status = '<span>待确认</span>';
								break;
							case 1:
								$status = '<span class="green">已确认，待解决</span> 备注说明：'.$data['notes'];
								break;
							case 2:
								$status = '已解决';
								break;
						}
						$val = $status;
					}
					$this->show->assign($key,$val);
				}
			}
			$product = new model_product_demand();
			$typeinfo = $product->get_typelist();
			$typename = '';
			$manager = '';
			$assistant = '';
			if ($typeinfo)
			{
				foreach ($typeinfo as $rs)
				{
					if ($data['product_id']==$rs['id'])
					{
						$typename = $rs['product_name'];
						$manager = $rs['manager'];
						$assistant = $rs['assistant'];
					}
				}
			}
		}
		$audit_button = '';
		$status_button = '';
		if ((in_array($_SESSION['USER_ID'],explode(',',$manager)) || in_array($_SESSION['USER_ID'],explode(',',$assistant))))
		{
			if ($data['status']==0 )
			{
				$audit_button = '<input type="button" class="thickbox" title="审核Bug" alt="?model='.$_GET['model'].'&action=audit&id='.$_GET['id'].'&key='.$_GET['key'].'&TB_iframe=true&height=250&width=400" value=" 审核操作 " />';
			}else if ($data['status']==1){
				$status_button = $data['status']==1 ? '<tr id="status_button"><th>设置状态：</th><td align="left"><input type="button" onclick="set_status('.$_GET['id'].',\''.$_GET['key'].'\')" value=" 设置为已解决 " /></td></tr>' : '';
			}
		}
		$this->show->assign('status_button',$status_button);
		$this->show->assign('audit_button',$audit_button);
		$this->show->assign('typename',$typename);
		$this->show->display('info');
	}
	/**
	 * 显示所有图片
	 */
	function c_showpic()
	{
		if ($_GET['id'] && $_GET['key'])
		{
			$data = $this->find(array('id'=>$_GET['id'],'rand_key'=>$_GET['key']));
			if ($data && $data['file_str'])
			{
				$pic_arr = explode(',',$data['file_str']);
				foreach ($pic_arr as $val)
				{
					echo '<img src="upfile/product/bug/' . $data['upfile_dir'] . '/'.$val.'" border="0"/></a><p />';
				}
			}
		}
	}
	/**
	 * 审核
	 */
	function c_audit()
	{
		if ($_POST)
		{
			if ($this->Edit($_GET['id'],$_GET['key'],$_POST))
			{
				showmsg('操作成功！','self.parent.location.reload();', 'button');
			}else{
				showmsg('操作失败，请与管理员联系！');
			}
		}else{
			$this->show->display('audit');
		}
	}
}

?>