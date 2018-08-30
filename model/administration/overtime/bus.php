<?php
class model_administration_overtime_bus extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'overtime_bus_line';
	}
	
	function model_list()
	{
		$query = $this->query("select * from overtime_bus_line");
		while (($rs = $this->fetch_array($query))!=false)
		{
			$str .='<tr id="tr_'.$rs['id'].'">';
			$str .='<input type="hidden" id="key_'.$rs['id'].'" value="'.$rs['rand_key'].'" />';
			$str .='<td>'.$rs['id'].'</td>';
			$str .='<td id="td_'.$rs['id'].'">'.$rs['station'].'</td>';
			$str .='<td id="ac_'.$rs['id'].'"><a href="javascript:edit('.$rs['id'].')">ĞŞ¸Ä</a> | <a href="javascript:del('.$rs['id'].')">É¾³ı</a></td>';
			$str .='</tr>';
		}
		
		return $str;
	}
	/**
	 * Ìí¼Ó
	 * @param unknown_type $data
	 */
	function model_add($data)
	{
		return $this->create($data);
	}
	/**
	 * ĞŞ¸Ä
	 * @param $id
	 * @param $key
	 * @param $data
	 */
	function model_edit($id,$key,$data)
	{
		return $this->update(array('id'=>$id,'rand_key'=>$key),$data);
	}
	/**
	 * É¾³ı
	 * @param $id
	 * @param $key
	 */
	function model_del($id,$key)
	{
		return $this->delete(array('id'=>$id,'rand_key'=>$key));
	}
}

?>