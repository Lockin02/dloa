<?php
class model_product_bug extends model_base
{
	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'product_bug';
	}
	/**
	 * �б�
	 */
	function BugList($getdata=false)
	{
		
		$typeid = $_GET['product_id'] ? $_GET['product_id'] : $_POST['product_id'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$username = $_GET['username'] ? $_GET['username'] : mb_iconv($_POST['username']);
		$start_date = $_GET['start_date'] ? $_GET['start_date'] : $_POST['start_date'];
		$end_date = $_GET['end_date'] ? $_GET['end_date'] : $_POST['end_date'];
		$status = $_GET['status'] || $_GET['status']=='0' ? $_GET['status'] : $_POST['status'];
		if ($username)
		{
			$userid = $this->get_table_fields ( 'user', "user_name='" . $username . "'", 'user_id' );
		}
		$condition = '';
		$condition .= $typeid ? ' and a.product_id='.$typeid : '';
		$condition .= $keyword ? " and a.description like '%$keyword%'" : '';
		if($userid){
		  $condition .= " and e.user_id='$userid'";	
		}
		$condition .= $start_date ? " and a.date>='".strtotime($start_date)."'" : '';
		$condition .= $end_date ? " and a.date<='".strtotime($end_date)."'" : '';
		$condition .= $status || $status== '0' ? " and a.status=$status " : '';
		$sort = $_GET['sort'] ? $_GET['sort'] : 'a.update_time-desc';
		$sort = str_replace ( '-', ' ', $sort );
		
		$sort = $sort ? ' order by '.$sort : '';
		$where = $condition ? ' where 1 '.$condition : '';
		if (! $this->num)
		{
			$rs = $this->get_one ( "select count(0) as num from product_bug as a  left join user as e on e.user_id=a.userid $where " );
			$this->num = $rs['num'];
		}
		
		if ($this->num > 0)
		{
			$query = $this->query ( "select 
										a.*,e.user_name , b.product_name,c.user_name as manager_name,d.user_name as assistant_name
									from
										product_bug as a
										left join product as b on b.id=a.product_id
										left join user as c on c.user_id = b.manager
										left join user as d on d.user_id = b.assistant
										left join user as e on e.user_id=a.userid
									$where
									$sort
										".(!$getdata ? "limit $this->start," . pagenum : ''));
			$data = array();
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				if ($getdata)
				{
					$data[] = $rs;
				}else{
					switch ($rs['status'])
					{
						case - 1 :
							$status_str = '<a id="return_'.$rs['id'].'" href="javascript:alert($(\'#return_'.$rs['id'].'\').attr(\'title\'));" title="������ǣ�'.$rs['notes'].'">�����</a>';
							break;
						case 0 :
							$status_str = '<span>��ȷ��</aspan>';
							break;
						case 1 :
							$status_str = '��ȷ�ϣ������';
							break;
						case 2 :
							$status_str = '<span class="green">�ѽ��</span>';
							break;
					}
					$edit_link = thickbox_link ( '�޸�', 'a', 'id=' . $rs['id'] . '&key=' . $rs['rand_key'], '�޸Ĳ�ƷBug', null, 'edit', 600, 500 );
					$show_link = thickbox_link ( '�鿴', 'a', 'id=' . $rs['id'] . '&key=' . $rs['rand_key'], '�鿴��ƷBug��Ϣ', null, 'showinfo', 700, 500 );
					$file_arr = $rs['file_str'] ? explode(',',$rs['file_str']) : array();
					$str .= '<tr>';
					$str .= '<td>' . $rs['id'] . '</td>';
					$str .= '<td>' . date ( 'Y-m-d', $rs['date'] ) . '</td>';
					$str .= '<td>' . date ( 'Y-m-d H:i:s', $rs['update_time'] ) . '</td>';
					$str .= '<td>' . $rs['user_name'] . '</td>';
					$str .= '<td>' . $rs['manager_name'] . '</td>';
					$str .= '<td class="wrap">' . $rs['description'] . '</td>';
					$str .= '<td>' . ($file_arr ? '<a title="����鿴��ͼ" href="?model='.$_GET['model'].'&action=showpic&id='.$rs['id'].'&key='.$rs['rand_key'].'" target="_blank"><img src="upfile/product/bug/' . $rs['upfile_dir'] . '/'.$file_arr[0].'" border="0" width="100" height="100"/></a>' : '') . '</td>';
					$str .= '<td class="wrap">' . $rs['data_info'] . '</td>';
					$str .= '<td>' . $rs['version'] . '</td>';
					$str .= '<td>' . $status_str . '</td>';
					$str .= '<td id="feedback_'.$rs['id'].'">' . ($rs['feedback']==0 && $rs['status']==2 ? ($rs['user_name'] == $_SESSION['USER_ID'] ? '<input type="button" onclick="feedback('.$rs['id'].',\''.$rs['rand_key'].'\');" value="����Ϊ�ѷ���" />' : '<span>������</span>') : '<span class="green">�ѷ���</span>') . '</td>';
					$str .= '<td>' . $rs['unit'] . '</td>';
					$str .= '<td>' . $rs['contact'] . '</td>';
					$str .= '<td>' . $rs['mobile'] . '</td>';
					$str .= '<td>' . $rs['email'] . '</td>';
					$str .= '<td>' . ($rs['userid'] == $_SESSION['USER_ID'] && $rs['status'] <= 0 ? $edit_link : $show_link) . '</td>';
					$str .= '</tr>';
				}
			}
			if ($getdata) return $data;
			if ($this->num > pagenum)
			{
				$showpage = new includes_class_page ();
				$showpage->show_page ( array(
												
												'total'=>$this->num,
												'perpage'=>pagenum
				) );
				$showpage->_set_url ( 'num=' . $this->num . '&product_id=' . $typeid . '&status=' . $status . '&start_date=' . $start_date .'&end_date='.$end_date.'&keyword='.urlencode ($keyword) );
				return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
		}
	}
	/**
	 * ͳ������
	 */
	function CountList($getdata=false)
	{
		$typeid = $_GET['product_id'] ? $_GET['product_id'] : $_POST['product_id'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$start_date = $_GET['start_date'] ? $_GET['start_date'] : $_POST['start_date'];
		$end_date = $_GET['end_date'] ? $_GET['end_date'] : $_POST['end_date'];
		$status = $_GET['status'] || $_GET['status']=='0' ? $_GET['status'] : $_POST['status'];
		$condition = '';
		$condition .= $typeid ? ' and a.product_id='.$typeid : '';
		$condition .= $keyword ? " and a.description like '%$keyword%'" : '';
		$condition .= $start_date ? " and a.date>='".strtotime($start_date)."'" : '';
		$condition .= $end_date ? " and a.date<='".strtotime($end_date)."'" : '';
		$condition .= $status || $status== '0' ? " and a.status=$status " : '';
		$query = $this->query("SELECT 
									distinct(g.user_name),b.num as tatol,c.num as be_num ,d.num as confirm,e.num as solution,f.num as back
								 FROM 
									product_bug as a
									left join (select count(0) as num ,userid from product_bug as a where 1 $condition group by userid) as b on b.userid=a.userid
									left join (select count(0)  as num ,userid from product_bug as a where status=0 $condition group by userid) as c on c.userid=a.userid
									left join (select count(0)  as num ,userid from product_bug  as a where status=1 $condition group by userid) as d on d.userid=a.userid
									left join (select count(0)  as num ,userid from product_bug  as a where status=2 $condition group by userid) as e on e.userid=a.userid
									left join (select count(0)  as num ,userid from product_bug as a where status=-1 $condition group by userid) as f on f.userid=a.userid
									left join user as g on g.user_id=a.userid
								where 1 $condition
								order by a.userid,a.id desc
									"
		);
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			if ($getdata)
			{
				$data[] = $rs;
			}else{
				$str .='<tr>';
				$str .='<td>'.$rs['user_name'].'</td>';
				$str .='<td>'.$rs['tatol'].'</td>';
				$str .='<td width="10%">'.($rs['solution'] ? $rs['solution']:0).'</td>';
				$str .='<td>'.round($rs['solution'] * 100 /$rs['tatol'],2).'%</td>';
				$str .='<td width="10%">'.($rs['confirm'] ? $rs['confirm'] : 0).'</td>';
				$str .='<td>'.round($rs['confirm'] * 100 /$rs['tatol'],2).'%</td>';
				$str .='<td width="10%">'.($rs['be_num']?$rs['be_num']:0).'</td>';
				$str .='<td>'.round($rs['be_num'] * 100 /$rs['tatol'],2).'%</td>';
				$str .='<td width="10%">'.($rs['back']?$rs['back']:0).'</td>';
				$str .='<td>'.round($rs['back'] * 100 /$rs['tatol'],2).'%</td>';
				$str .='</tr>';
			}
		}
			if ($getdata) return $data;
			if ($this->num > pagenum)
			{
				$showpage = new includes_class_page ();
				$showpage->show_page ( array(
												
												'total'=>$this->num,
												'perpage'=>pagenum
				) );
				$showpage->_set_url ( 'num=' . $this->num . '&product_id=' . $typeid . '&status=' . $status . '&start_date=' . $start_date .'&end_date='.$end_date.'&keyword='.urlencode ($keyword) );
				return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
	}
	/**
	 * ���
	 * @param  $data
	 */
	function Add($data)
	{
		if ($data)
		{
			$data['update_time'] = time();
			if ($_FILES['file_str']['name'])
			{
				$FilePathName = md5 ( time () . rand ( 0, 99999 ) );
				$data['upfile_dir'] = $FilePathName;
				if (! is_dir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
				{
					if (! mkdir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
					{
						showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ����' );
					}
				}
				//========�����ϴ��ļ���ʼ============
				if (is_array ( $_FILES['file_str']['name'] ))
				{
					$temp_file_arr = array();
					foreach ( $_FILES['file_str']['name'] as $key => $filename )
					{
						if ($filename)
						{
							if (move_uploaded_file ( str_replace ( '\\\\', '\\', $_FILES['file_str']['tmp_name'][$key] ), WEB_TOR . 'upfile/product/bug/' . $FilePathName . '/' . $filename ))
							{
								$temp_file_arr[] = $filename;
							} else
							{
								showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ��' );
							}
						}
					}
					$data['file_str'] = $temp_file_arr ? implode ( ',', $temp_file_arr ) : '';
				} else
				{
					$tempname = $_FILES['file_str']['tmp_name'];
					$filename = $_FILES['file_str']['name'];
					if (! is_dir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
					{
						if (! mkdir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
						{
							showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ����' );
						}
					}
					if (move_uploaded_file ( str_replace ( '\\\\', '\\', $tempname ), WEB_TOR . 'upfile/product/bug/' . $FilePathName . '/' . $filename ))
					{
						$data['file_str'] = $filename;
					} else
					{
						showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ��' );
					}
				}
				//=================�����ϴ��ļ�����==========================
			

			}
			//=========���沢�����ʼ�============
			if ($this->create ( $data ))
			{
				//����Ϊ�����ʼ�����
				$row = $this->get_one ( "select product_name,manager,assistant from product where id=" . $data['product_id'] );
				$assistant = explode ( ',', $row['assistant'] );
				$userid_arr = array(
					
							$row['manager'],
							'xiaoming.geng',
							'zhen.wang',
							'ying.zhang',
							'yun.xia',
							'jinping.huang',
							'yingjian.luo',
							$_SESSION['USER_ID']
				);
				$userid = array_merge ( $userid_arr, $assistant );
				$gl = new includes_class_global ();
				$username = $gl->GetUserinfo ( $data['userid'], 'user_name' );
				$Address = $gl->get_email ( $userid );
				$Email = new includes_class_sendmail ();
				$body .='������Ʒ��'.$row['product_name'].'<br />';
				$body .='��Ʒ�汾�ţ�'.$data['version'].'<br />';
				$body .='���״̬����ȷ��<br />';
				$body .='<hr />��ƷBug������<br />';
				$body .= preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($data['description']));
				//$body .= str_replace('src="/OA/attachment/FckUpload/','src="http://127.0.0.1/web/oa/oa/OA/attachment/FckUpload/',stripslashes($data['description']));
				$body .=oaurlinfo;
				return $Email->send ( $username . '�ύ�˲�ƷBug', $body, $Address );
			}
		} else
		{
			return false;
		}
	}
	/**
	 * �޸�
	 * @param $id
	 * @param $key
	 * @param $data
	 */
	function Edit($id, $rand_key, $data)
	{
		if ($id && $rand_key)
		{
			$rs = $this->find ( array(
										'id'=>$id,
										'rand_key'=>$rand_key
			) );
			$FilePathName = $rs['upfile_dir'] ? $rs['upfile_dir'] : md5 ( time () . rand ( 0, 99999 ) );
			$data['upfile_dir'] = $FilePathName;
			$temp_file_arr = $rs['file_str'] ? explode ( ',', $rs['file_str'] ) : array();
			if (! $rs)
				return false;
			if ($_FILES && $_FILES['file_str']['name'][0])
			{
				if (! is_dir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
				{
					if (! mkdir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
					{
						showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ����' );
					}
				}
				//========�����ϴ��ļ���ʼ============
				if (is_array ( $_FILES['file_str']['name'] ))
				{
					foreach ( $_FILES['file_str']['name'] as $key => $filename )
					{
						if ($filename)
						{
							if (move_uploaded_file ( str_replace ( '\\\\', '\\', $_FILES['file_str']['tmp_name'][$key] ), WEB_TOR . 'upfile/product/bug/' . $FilePathName . '/' . $filename ))
							{
								$temp_file_arr[] = $filename;
							} else
							{
								showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ��' );
							}
						}
					}
				} else
				{
					$tempname = $_FILES['file_str']['tmp_name'];
					$filename = $_FILES['file_str']['name'];
					if (! is_dir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
					{
						if (! mkdir ( WEB_TOR . 'upfile/product/bug/' . $FilePathName ))
						{
							showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ����' );
						}
					}
					if (move_uploaded_file ( str_replace ( '\\\\', '\\', $tempname ), WEB_TOR . 'upfile/product/bug/' . $FilePathName . '/' . $filename ))
					{
						$temp_file_arr[] = $filename;
					} else
					{
						showmsg ( '�ϴ�����ʧ�ܣ��������Ա��ϵ��' );
					}
				}
				//=================�����ϴ��ļ�����==========================
			}
			$data['file_str'] = $temp_file_arr ? implode ( ',', $temp_file_arr ) : '';
			$data['update_time'] = time();
			if ($rs['status']== -1)
			{
				$data['status'] = 0;
			}
			return $this->update ( array(
											'id'=>$id,
											'rand_key'=>$rand_key
			), $data );
		} else
		{
			return false;
		}
	}
	/**
	 * ɾ��
	 * @param $id
	 * @param $key
	 */
	function Del($id, $key)
	{
		if ($id && $key)
		{
			return $this->delete ( array(
											'id'=>$id,
											'rand_key'=>$key
			) );
		} else
		{
			return false;
		}
	}
/**
	 * �����ʼ�
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
										product_bug as a 
										left join product as b on b.id=a.product_id
									where 
										a.id='$id' and a.rand_key='$key'
						" );
		$userid_arr = array(//�̶�Ҫ���͵���Ա
			
					$rs['userid'],
					$rs['manager'],
					'ying.zhang',
					'zhen.wang',
					'xiaoming.geng',
					'yun.xia',
					'jinping.huang'
		);
		$assistant = explode ( ',', $rs['assistant'] );
		$userid = array_merge ( $userid_arr, $assistant );
		$gl = new includes_class_global ();
		$username = $gl->GetUserinfo ( $rs['userid'], 'user_name' );
		$body .='������Ʒ��'.$rs['product_name'].'<br />';
		$body .='��Ʒ�汾�ţ�'.$rs['version'].'<br />';
		$body .='���״̬��'.($rs['status']==0 ? '�����' : ($rs['status']==1 ? '��ȷ����Bug' : ($rs['status']==2 ? '�ѽ��Bug' : '��ȷ����Bug,�����')));
		$body .='<hr />��ƷBug������'.preg_replace('/(<img.+src=\")(.+?attachment\/)(.+\.(jpg|gif|bmp|bnp|png)\"?.+>)/i',"\${1}".WEB_URL."/attachment/\${3}",stripslashes($rs['description']));
		$body .=($rs['status']!=0 ? '<hr />��ע˵����<br />'.$rs['notes'] : '');
		
		$body .=oaurlinfo;
		$Address = $gl->get_email ( $userid );
		$Email = new includes_class_sendmail ();
		if ($type == 'audit')
		{
			return $Email->send ( $_SESSION['USERNAME'] . ($rs['status'] == 1 ? ' ��ȷ����Bug��' : ' ��ȷ����Bug������� ��') . $username . ' �ύ�Ĳ�ƷBug', $body, $Address );
		} elseif ($type == 'upfd')
		{
			return $Email->send ( $username . ' �ύ�Ĳ�ƷBug�Ѿ��������ͻ�', $body, $Address );
		} elseif ($type == 'realize')
		{
			return $Email->send ( $username . ' �ύ�Ĳ�Bug���Ѿ�ʵ��', $body, $Address );
		}
	}
}

?>