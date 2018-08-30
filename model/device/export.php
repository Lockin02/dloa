<?php
class model_device_export extends model_base
{
	function __construct()
	{
		parent::__construct();
	}
	
	function model_total()
	{
		set_time_limit ( 0 );
		$sheet = "����ܱ�";
		$title = array('�豸���','�豸����','Ԥ��۸�','��λ','�ܱ�','---','---','�麣','---','---','����','---','---','����','---','---','����','---','---','��������','�������','���¶�ʧ','ƽ������','ÿ���ۼ�','ʹ����','�����̵�','��ע');
		$stock = new model_device_stock();
		$data = $stock->model_list(true);
		$tow = array('','','','','����','����','ʣ��','����','����','ʣ��','����','����','ʣ��','����','����','ʣ��','����','����','ʣ��');
		$data = $data ? array_merge(array($tow),$data) : array($tow);
		$excel = new includes_class_excel();
		$excel->SetTitle(array($sheet),array($title));
		$marrArr=array('A','B','C','D','T','U','V','W','X','Y','Z','AA');
		foreach($marrArr as $val){
			$excel->objActSheet[0]->mergeCells($val.'1:'.$val.'2');
		}
		$excel->objActSheet[0]->mergeCells('E1:G1');
		$excel->objActSheet[0]->mergeCells('H1:J1');
		$excel->objActSheet[0]->mergeCells('K1:M1');
		$excel->objActSheet[0]->mergeCells('N1:P1');
		$excel->objActSheet[0]->mergeCells('Q1:S1');
		$excel->SetContent(array($data));
		$total = (count($data) - 1);
		$excel->objActSheet[0]->setCellValue('A'.($total+5),un_iconv('�ϼƣ�'.$total));
		$excel->OutPut();
	}
	
	//���
	function model_type_list($typeid,$list_id=null)
	{
		$rs = $this->get_one("
						select
							a.device_name,b.typename
						from
							device_list as a
							left join device_type as b on b.id=a.typeid
						where
							a.typeid=$typeid
							".($list_id ? " and a.id=$list_id" : "")."
						
								
		");
		$sheet= $list_id ? $rs['device_name'] : $rs['typename'];
		$arr = array('�������','״̬','���','�������','�������','ʹ����','ʹ����Ŀ','������','��������');
		$title = $this->fixed_format($typeid);
		$title = array_merge($title,$arr);
		$stock = new model_device_stock();
		if($list_id){
			$data = $stock->model_device_info_list ( true ,true);
		}else{
		 $data = $stock->model_deviceInfoTypelist ( true ,true);
		}
		$rs = $stock->get_fixed_field_name ( $_GET['typeid'], false );
		if ($data)
		{
			$excel_data = array();
			foreach ( $data as $key => $row )
			{
				$arr = array();
				$arr[] = $row['id'];
				$arr[] = $row['device_name'];
				foreach ( $rs as $k => $v )
				{
					if (! $v)
					{
						switch ($k)
						{
							case '_coding' :
								$arr[] = $row['coding'] ? $row['coding'] : '--';
								break;
							case '_dpcoding' :
								$arr[] = $row['dpcoding'] ? $row['dpcoding'] : '--';
								break;
							case '_fitting' :
								$arr[] = $row['fitting'] ? $row['fitting'] : '--';
								break;
							case '_price' :
								$arr[] = $row['price'] ? $row['price'] : '--';
								break;
							case '_notes' :
								$arr[] = $row['notes'] ? $row['notes'] : '--';
								break;
						}
					}
				}
				if ($row['field'])
				{
					$field_data = $stock->get_field_content ( $row['id'] );
					foreach ( $row['field'] as $av )
					{
						
						if ($field_data[$av])
						{
							$arr[] = $field_data[$av];
						} else
						{
							$arr[] = '--';
						}
					}
					unset ( $field_data );
				}
				$arr[] = $row['date'] ? date ( 'Y-m-d', $row['date'] ) : '--';
				switch ($row['state'])
				{
					case 0 :
						$arr[] = '����';
						break;
					case 1 :
						$arr[] = '���';
						break;
					case 2 :
						$arr[] = '�ȴ�ȷ��';
						break;
					case 3 :
						$arr[] = 'ά��';
						break;
					case 4 :
						$arr[] = '�˿�';
						break;
					default :
						$arr[] = '������';
				}
				$arr[] = $row['areaname'];
				$arr[] = $row['amount'];
				$arr[] = $row['borrow_num'];
				$arr[] = ($row['borrow_date'] ? $stock->rate ( $row['id'], $row['date'] ) . '%' : '');
				$arr[] = ($row['returndate'] ? '' : $row['projectname']);
				$arr[] = ($row['returndate'] ? '' : $row['user_name']);
				$arr[] = ($row['borrow_date'] && ! $row['returndate'] ? date ( 'Y-m-d', $row['borrow_date'] ) : '');
				$excel_data[] = $arr;
			}
			
			$title = explode(',',implode(',',$title));
			$excel = new includes_class_excel();
			$excel->SetTitle(array($sheet),array($title));
			$excel->SetContent(array($excel_data));
			$excel->OutPut();
		}
	}
	
	//**********************************�軹**********************************
	/**
	 * ���豸���͵���
	 * @param unknown_type $typeid
	 */
	function model_borrow_type_info($typeid)
	{
		$arr = array('����','������','ʹ����Ŀ','ȷ��״̬','��������','Ԥ�ƹ黹����','ʵ�ʹ黹����');
		$title = $this->fixed_format($typeid);
		$title = array_merge($title,$arr);
		$borrow = new model_device_borrow();
		$excel = new includes_class_excel();
		$excel->SetTitle(array($_GET['typename']),array($title));
		$excel->SetContent(array($borrow->model_borrow_type_info($typeid,false,false,true)));
		$excel->OutPut();
	}	
	/**
	 * ��������豸�б�
	 */
	function model_borrow_info_list()
	{
		$borrow = new model_device_borrow();
		$data = $borrow->model_borrow_info_list(false,true);
		$excel = new includes_class_excel();
		$excel->SetTitle($data['sheet'],$data['column']);
		$excel->SetContent($data['row']);
		$excel->OutPut();
	}
	
	function model_project_info_list()
	{
		$borrow = new model_device_borrow();
		$data = $borrow->model_show_project_list($_GET['project_id'],false,true);
		$excel = new includes_class_excel();
		$excel->SetTitle($data['sheet'],$data['column']);
		$excel->SetContent($data['row']);
		$excel->OutPut();
	}
	/**
	 * ��ʽ��ͷ
	 * @param unknown_type $typeid
	 */
	function fixed_format($typeid)
	{
		if($typeid){
		$stock = new model_device_stock();
		$fixed_arr = $stock->get_fixed_field_name($typeid,false);
		$field_arr = $stock->model_show_field_name($typeid,false);
		$title = array('id'=>'���','device_name'=>'�豸����');
		$data = array();
		if (is_array($fixed_arr))
		{
			foreach ( $fixed_arr as $key => $val )
			{
				if (! $val)
				{
					switch ($key)
					{
						case '_coding' :
							$data['coding']='������ ';
							break;
						case '_dpcoding' :
							$data['dpcoding']='���ű���';
							break;
						case '_fitting' :
							$data['fitting']='���';
							break;
						case '_price' :
							$data['price']='����';
							break;
						case '_notes' :
							$data['notes']='��ע';
							break;
					}
				}
			}
		}
		$title = array_merge($title,$data);
		if ($field_arr)
		{
			foreach ($field_arr as $row)
			{
				$title[$row['id']]=$row['fname'];
			}
		}
		}
		return $title;
	}
	/**
	 * ���ű���
	 * @param $typeid
	 * @param $list_id
	 * @param $start_date
	 * @param $end_datew
	 */
	function report($report_type='1',$typeid,$list_id,$start_date,$end_date,$field,$count_field)
	{
		$start_date_num = strtotime($start_date);
		$end_date_num = strtotime($end_date.' 23:59:59');
		
		if (!is_array($field))
		{
			return false;
		}
		$left_join = '';
		$field_arr = array();
		$field_name_arr = array('user_name'=>'����','days'=>'��������');
		$number_field = '';
		$i = 1;
		foreach ($field as $val)
		{
			if ($val == 'id')
			{
				$field_arr[$val] = 'info.id';
				$field_name_arr[$val] = '���';
			}else if ($val == 'device_name')
			{
				$field_arr[$val] = 'dd.device_name';
				$field_name_arr[$val] = '�豸����';
			}else if (!is_numeric($val)){
				$field_arr[$val] = 'info.'.$val;
				$field_name_arr[$val] = $this->fixed_field($val);
			}else{
				$field_name_arr['n'.$i.'_content'] = $this->Custom_Fields($typeid,$val);
				$field_arr[$val] = 'n'.$i.'.content as n'.$i.'_content';
				$left_join .=' left join device_type_field_content as n'.$i.' on n'.$i.'.field_id='.$val.' and n'.$i.'.info_id=a.info_id ';
				$i++;
			}
		}
		$sql = "
				select 
					a.id,d.user_name,e.dept_name,dd.device_name,xm.name as project_name,b.days ".($field_arr ? ','.implode(',',$field_arr) : '')."
				from 
					device_borrow_order_info as a 
					INNER JOIN(
								 select 
									id,info_id ,max(round(( ( if ( returndate is null,".$end_date_num.",returndate ) ) - ( if ( date < ".$start_date_num." , ".$start_date_num.",date ) ) ) / ( 3600*24 ) )) as days 
								from
									device_borrow_order_info 
								where 
									typeid=$typeid 
									and list_id = $list_id 
									and date <= '".$end_date_num."' 
									and ( 
											(
												 ( returndate >='".$start_date_num."' and returndate <='".$end_date_num."' )
												 or 
												returndate is null 
											) 
										) 
									group by info_id 
								) as b ON a.id=b.id 
					LEFT JOIN device_borrow_order AS c ON c.id=a.orderid
					LEFT JOIN user as d on d.user_id=c.userid
					left join department as e on e.dept_id=d.dept_id
					left join device_list as dd on dd.id=a.list_id
					left join device_info as info on info.id=a.info_id
					left join project_info as xm on xm.id=c.project_id
					$left_join
				where
					".($report_type == '1' ? 'c.project_id=0' : 'c.project_id > 0')."
				order by e.dept_name ,d.user_name,c.project_id desc
				";
		
		$row_data = array();
		$project_data = array();
		$device_name = '';
		$query = $this->query($sql);
		while (($rs = $this->fetch_array($query))!=false)
		{
			$device_name = $rs['device_name'];
			if ($report_type =='1')
			{
				$row_data[$rs['dept_name']][] = $rs;
			}else{
				$row_data[$rs['project_name']][] = $rs;
			}
		}
		if ($report_type == '1')
		{
			$title = array($start_date.' �� '.$end_date.' ['.$device_name.'] ���Ž��ñ���');
			$sheet = $device_name.' ���Ž��ñ���';
			
		}else{
			$title = array($start_date.' �� '.$end_date.' ['.$device_name.'] ��Ŀ���ñ���');
			$sheet = $device_name.' ��Ŀ���ñ���';
		}
		$excel = new includes_class_excel($sheet.'.xls');
		$excel->SetTitle(array($sheet),array($title));
		$line = 1;
		$field_num = count($field_name_arr)-1;
		$data = array();
		$field_title_arr = explode(',',implode(',',$field_name_arr));

		$count = array();
		foreach ($row_data as $key=>$row)
		{
			if ($line == 1)
			{
				$excel->objActSheet[0]->mergeCells('A'.$line.':'.$excel->abc[$field_num].$line);
			}
			$line++;
			$excel->objActSheet[0]->mergeCells('A'.$line.':'.$excel->abc[$field_num].$line);
			$data[] = array('');
			$line++;
			$excel->objActSheet[0]->mergeCells('A'.$line.':'.$excel->abc[$field_num].$line);
			$excel->objActSheet[0]->getStyle('A'.$line)->getFont()->setBold(true);
			$excel->objActSheet[0]->getStyle('A'.$line)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			$excel->objActSheet[0]->getStyle('A'.$line)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$excel->objActSheet[0]->getStyle('A'.$line)->getFill()->getStartColor()->setARGB('FF808080');
			$data[] = array($key); //��������
			$line++;
			$data[] = $field_title_arr; //����
			if ($row)
			{
				foreach ($row as $rs)
				{
					$temp = array();
					$n = 0;
					foreach ($field_name_arr as $k=>$val)
					{
						$temp[] = $rs[$k];
						if ($n > 1 && $count_field)
						{
							$field_key = $field[($n-2)];
							if (in_array($field_key,$count_field))
							{
								$count[$key][$field_key] += (is_numeric(trim($rs[$k])) ? trim($rs[$k]) : 0);
							}
						}
						$n++;
					}

					$line++;
					$data[] = $temp;
				}
				
				
			}
			$line++;
			$excel->objActSheet[0]->mergeCells('A'.$line.':B'.$line);
			$count_line = array('С�ƣ�','');
			foreach ($field as $val)
			{
				if (in_array($val,$count_field))
				{
					$count_line[] = $count[$key][$val];
				}else{
					$count_line[] = '';
				}
			}
			$data[] = $count_line;
		}
		$line++;
		$excel->objActSheet[0]->mergeCells('A'.$line.':'.$excel->abc[$field_num].$line);
		$data[] = array('');
		$line++;
		$excel->objActSheet[0]->mergeCells('A'.$line.':'.$excel->abc[$field_num].$line);
		$data[] = array(($report_type == '1' ? '����ͳ��' : '��Ŀͳ��'));
		$excel->objActSheet[0]->getStyle('A'.$line)->getFont()->setBold(true);
		$excel->objActSheet[0]->getStyle('A'.$line)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		$excel->objActSheet[0]->getStyle('A'.$line)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$excel->objActSheet[0]->getStyle('A'.$line)->getFill()->getStartColor()->setARGB('FF808080');
		$line++;
		$count_field_num = $field_num - count($count_field);
		$excel->objActSheet[0]->mergeCells('A'.$line.':'.$excel->abc[$count_field_num].$line);
		$excel->objActSheet[0]->getStyle('A'.$line)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		$count_title = array(($report_type == '1' ? '��������' : '��Ŀ����'));
		$Filling = array();
		for($s = 0;$s<$count_field_num;$s++)
		{
			$count_title[] = '';
			$Filling[] = '';
		}
		foreach ($count_field as $k=>$v)
		{
			if ($v == 'device_name')
			{
				$count_title[] = '�豸����';
			}else if (!is_numeric($v)){
				$count_title[] = $this->fixed_field($v);
			}else{
				$count_title[] = $this->Custom_Fields($typeid,$v);
			}
		}
		$data[] = $count_title;
		foreach ($count as $kk=>$val)
		{
			$line++;
			$excel->objActSheet[0]->mergeCells('A'.$line.':'.$excel->abc[$count_field_num].$line);
			$excel->objActSheet[0]->getStyle('A'.$line)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			$tmp = array($kk);
			$tmp = array_merge($tmp,$Filling);
			foreach ($val as $v)
			{
				$tmp[] = $v;
			}
			$data[] = $tmp;
		}
		for($f=0;$f<$field_num;$f++)
		{
			//$excel->objActSheet[0]->getColumnDimension($excel->abc[$i])->setAutoSize(true);
			$excel->objActSheet[0]->getColumnDimension($excel->abc[$f])->setWidth('15');
		}
		$excel->SetContent(array($data));
		$total = (count($data) - 1);
		$excel->objActSheet[0]->setCellValue('A'.($total+5),'');
		$excel->OutPut();
		
	}
	/**
	 * �̶��ֶ�
	 * @param $key
	 */
	function fixed_field($key)
	{
		switch ($key)
		{
			case 'coding' :
				return '������ ';
				break;
			case 'dpcoding' :
				return '���ű���';
				break;
			case 'fitting' :
				return '���';
				break;
			case 'price' :
				return '����';
				break;
			case 'notes' :
				return '��ע';
				break;
		}
	}
	/**
	 * ��̬�ֶ�
	 * @param unknown_type $typeid
	 * @param unknown_type $key
	 */
	function Custom_Fields($typeid,$key)
	{
		static $field_arr = array();
		if (!$field_arr)
		{
			$stock = new model_device_stock();
			$field_arr = $stock->model_show_field_name($typeid,false);
		}
		if ($field_arr)
		{
			foreach ($field_arr as $rs)
			{
				if ($rs['id'] == $key)
				{
					return $rs['fname'];
				}
			}
		}
	}
	
	
	
	function model_typeListInfo($arrI=array())
	{
		set_time_limit ( 0 );
		if($arrI&&is_array($arrI)){
			
			$arrStr=implode(',',$arrI);
			
			$sql="select a.typename,a.id
				  from  device_type as a
					    
				  where
							a.id in ($arrStr)
		";
			$query = $this->query($sql);
			
			$i=0;
			$excel_data = array();
			$titles = array();
			$sheet = array();
			while (($rss = $this->fetch_array($query))!=false)
			{
					$title='';
					$rs='';
					$arr = array('�������','״̬','���','�������','�������','ʹ����','ʹ����Ŀ','������','��������');
					$sheet[$i]=$rss['typename'];
					if($rss['id']){
						$title = $this->fixed_format($rss['id']);
					}
					$title= array_merge($title,$arr);
					$stock = new model_device_stock();
					$data = $stock->model_deviceInfoTypelists ($rss['id']);
					$rs = $stock->get_fixed_field_name ( $rss['id'], false );
					if ($data)
					{
						
						foreach ( $data as $key => $row )
						{
							$arr = array();
							$arr[] = $row['id'];
							$arr[] = $row['device_name'];
							foreach ( $rs as $k => $v )
							{
								if (! $v)
								{
									switch ($k)
									{
										case '_coding' :
											$arr[] = $row['coding'] ? $row['coding'] : '--';
											break;
										case '_dpcoding' :
											$arr[] = $row['dpcoding'] ? $row['dpcoding'] : '--';
											break;
										case '_fitting' :
											$arr[] = $row['fitting'] ? $row['fitting'] : '--';
											break;
										case '_price' :
											$arr[] = $row['price'] ? $row['price'] : '--';
											break;
										case '_notes' :
											$arr[] = $row['notes'] ? $row['notes'] : '--';
											break;
									}
								}
							}
							if ($row['field'])
							{
								$field_data = $stock->get_field_content ( $row['id'] );
								foreach ( $row['field'] as $av )
								{
									
									if ($field_data[$av])
									{
										$arr[] = $field_data[$av];
									} else
									{
										$arr[] = '--';
									}
								}
								unset ( $field_data );
							}
							$arr[] = $row['date'] ? date ( 'Y-m-d', $row['date'] ) : '--';
							switch ($row['state'])
							{
								case 0 :
									$arr[] = '����';
									break;
								case 1 :
									$arr[] = '���';
									break;
								case 2 :
									$arr[] = '�ȴ�ȷ��';
									break;
								case 3 :
									$arr[] = 'ά��';
									break;
								case 4 :
									$arr[] = '�˿�';
									break;
								default :
									$arr[] = '������';
							}
							$arr[] = $row['areaname'];
							$arr[] = $row['amount'];
							$arr[] = $row['borrow_num'];
							$arr[] = ($row['borrow_date'] ? $stock->rate ( $row['id'], $row['date'] ) . '%' : '');
							$arr[] = ($row['returndate'] ? '' : $row['projectname']);
							$arr[] = ($row['returndate'] ? '' : $row['user_name']);
							$arr[] = ($row['borrow_date'] && ! $row['returndate'] ? date ( 'Y-m-d', $row['borrow_date'] ) : '');
							
							$excel_data[$i][] = $arr;
						}
                      $titles[$i] = explode(',',implode(',',$title));
					}
				$i++;
			}
			
			
			$excel = new includes_class_excel();
			$excel->SetTitle($sheet,$titles);
			$excel->SetContent($excel_data);
			$excel->OutPut();
			
		}

		
	}
	
}
?>