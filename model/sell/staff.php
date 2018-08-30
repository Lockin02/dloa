<?php
class model_sell_staff extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'sell_staff';
	}
	/**
	 * ������Ա�б�
	 */
	function model_list()
	{
		if (!$this->num)
		{
			$rs = $this->get_one("select count(0) as num from sell_staff as a $where");
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query("
									select
										a.*,b.user_name,d.user_name as username
									from
										sell_staff as a
										left join user as b on b.user_id=a.userid
										left join sell_staff as c on c.id=a.tid
										left join user as d on d.user_id=c.userid
									$where
									order by a.id desc	
								");
			while (($rs = $this->fetch_array($query))!=false)
			{
				$data = array();
				$area_name = array();
				if ($rs['area'])
				{
					$data = $this->get_area($rs['area']);
					if ($data)
					{
						foreach ($data as $row)
						{
							$area_name[] = $row['name'];
						}
					}
				}
				$edit_link = thickbox_link('�޸�','a','id='.$rs['id'].'&key='.$rs['rand_key'],'�޸� '.$rs['user_name'],null,'edit',400,400);
				$del_link = thickbox_link('ɾ��','a','id='.$rs['id'].'&key='.$rs['rand_key'],'ɾ�� '.$rs['user_name'],null,'delete',400,200);
				$str .='
						<tr>
							<td>'.$rs['user_name'].'</td>
							<td>'.($rs['username'] ? $rs['username'] : '������').'</td>
							<td>'.($rs['tid']==0 ? '������' : '��ͨ��Ա').'</td>
							<td>'.$rs['mobile'].'</td>
							<td>'.($area_name ? implode('��',$area_name) : '').'</td>
							<td>'.$edit_link.' | '.$del_link.'</td>
						</tr>
				';
			}
			$showpage = new includes_class_page ();
			$showpage->show_page ( array(
											'total'=>$this->num,
											'perpage'=>pagenum) );
			$showpage->_set_url ( 'num=' . $this->num . '&areaid=' . $_GET['areaid'] . '&status=' . $_GET['status'] . 'username=' . $_GET['username'] );
			return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * ��ȡ�ϼ��쵼
	 */
	function get_higher()
	{
		$query = $this->query("
								select
									a.*,b.user_name
								from
									sell_staff as a
									left join user as b on b.user_id=a.userid
								where
									tid=0
							");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * ��ȡ�¼�������Ա
	 * @param $tid
	 */
	function get_lower($tid)
	{
		return $this->findAll('tid='.$tid);
	}
	/**
	 * ��ȡ��������
	 */
	function get_area($areaid='')
	{
		$where = $areaid ? "where id in($areaid)" : '';
		$query = $this->query("select * from sell_area $where order by id desc");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
		
	}
	function get_info($id)
	{
		return $this->get_one("
								select
									a.*,b.user_name,d.user_name as username
								from
									sell_staff as a
									left join user as b on b.user_id=a.userid
									left join sell_staff as c on c.id=a.tid
									left join user as d on d.user_id=c.userid
								where
									a.id=$id
										
		");
	}
	/**
	 * ����������Ա
	 * @param unknown_type $userid
	 * @param unknown_type $tid
	 * @param unknown_type $mobile
	 * @param unknown_type $area
	 * @param unknown_type $type
	 * @param unknown_type $id
	 * @param unknown_type $key
	 */
	function model_save($userid,$tid,$mobile,$area='',$type='add',$id='',$key='')
	{
		$area = $area && is_array($area) ? implode(',',$area) : '';
		if ($type=='add')
		{
			return $this->create(array('userid'=>$userid,'tid'=>$tid,'mobile'=>$mobile,'area'=>$area));
		}else{
			return $this->update(array('id'=>$id,'rand_key'=>$key),array('userid'=>$userid,'tid'=>$tid,'mobile'=>$mobile,'area'=>$area));
		}
	}
	/**
	 * ɾ��������Ա
	 */
	function model_delete($id,$key)
	{
		return $this->delete(array('id'=>$id,'rand_key'=>$key));
	}
}

?>