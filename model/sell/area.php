<?php
class model_sell_area extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'sell_area';
	}
	
	function model_list()
	{
		if (!$this->num)
		{
			$rs = $this->get_one("select count(0) as num from sell_area");
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query("select * from sell_area order by id desc limit $this->start,".pagenum);
			while (($rs = $this->fetch_array($query))!=false)
			{
				$str .='
					<tr>
						<td>'.$rs['id'].'</td>
						<td>'.$rs['name'].'</td>
						<td><a href="?model=sell_area&action=edit&id='.$rs['id'].'&key='.$rs['rand_key'].'&name='.$rs['name'].'&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=250" class="thickbox" title="修改区域">修改</a> | 
						<a href="?model=sell_area&action=delete&id='.$rs['id'].'&key='.$rs['rand_key'].'&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=250" class="thickbox" title="删除区域">删除</a></td>
					</tr>
				';
			}
			
			$showpage = new includes_class_page ();
			$showpage->show_page ( array(
											'total'=>$this->num,
											'perpage'=>pagenum) );
			$showpage->_set_url ( 'num=' . $this->num );
			return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
		}
	}
	/**
	 * 保存数据
	 * @param unknown_type $name
	 */
	function model_save($name,$type='add',$id='',$key='')
	{
		if ($type=='add')
		{
			return $this->create(array('name'=>$name));
		}elseif ($type=='edit'){
			return $this->update(array('id'=>$id,'rand_key'=>$key),array('name'=>$name));
		}
	}
	
	function model_delete($id,$key)
	{
		return $this->delete(array('id'=>$id,'rand_key'=>$key));
	}
}

?>