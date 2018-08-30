<?php
class model_pvurl extends model_base
{
	public $content; //������޸Ĳ�������֮ǰѡ������ݷŵ����������
	public $higher; //��һ����ѡ�������
	/**
	 * ���캯�� ���ػ��༰�ж��Ƿ�Ϊ�޸�����
	 *
	 */
	function __construct()
	{
		parent::__construct();
		if (intval($_GET['id']))
		{
			$this->content = $this->get_content($_GET['id']);
		}
		if ($_GET['action']=='get_list')
		{
			$this->get_checked_content();
		}
	}
	function get_checked_content()
	{
		if ($_GET['type']==1 && $_GET['uid'])
		{
			$this->tbl_name = 'user';
			$row = $this->find(array('user_id'=>$_GET['uid']),null,'dept_id,jobs_id');
			if ($row)
			{
				$where = " and (deptid='".$row['dept_id']."' or jobsid='".$row['jobs_id']."')";
			}
		}elseif ($_GET['type']==2){
			$this->tbl_name = 'user_jobs';
			$row = $this->find(array('id'=>$_GET['uid']),null,'dept_id');
			$where =" and deptid='".$row['dept_id']."'";
		}
		$query = $this->_db->query("select * from purview_info where typeid=".$_GET['typeid']." $where ");
		$arr = array();
		while ($rs = $this->_db->fetch_array($query))
		{
			$arr[$rs['type']] = $rs['content'];
		}
		if ($_GET['type']==1)
		{
			$this->higher = (($arr[2]=='0' || $arr[2]) ? $arr[2] : $arr[3]);
		}elseif ($_GET['type']==2)
		{
			$this->higher = $arr[3];
		}
	}
	/**
	 * ��ȡ��������
	 *
	 * @param int $id
	 * @return string
	 */
	function get_content($id)
	{
		$rs = $this->_db->get_one("select content from purview_info where id=".$id);
		return $rs['content'];
	}
	/**
	 * ����
	 *
	 * @param string $areaid
	 * @return string
	 */
	function model_area()
	{
        $str = '';
		$arr = $this->content ? explode(',',$this->content) : false;
		$higher = $this->higher ? explode(',',$this->higher) : false;
		$query = $this->_db->query("select * from area where del = 0");
		while (($rs = $this->_db->fetch_array($query))!=false)
		{
			if ($arr && in_array($rs['ID'],$arr))
			{
				$str .='<input type="checkbox" checked name="content[]" value="'.$rs['ID'].'" />'.$rs['Name'];
			}elseif ($higher && in_array($rs['ID'],$higher)){
				$str .='<input type="checkbox" checked name="content[]" value="'.$rs['ID'].'" />'.$rs['Name'];
			}else{
				$str .='<input type="checkbox" name="content[]" value="'.$rs['ID'].'" />'.$rs['Name'];
			}
		}
		return $str;
	}
	/**
	 * �豸����
	 *
	 * @param string $typeid
	 * @return string
	 */
	function model_device_type()
	{
        $str = '';
		$arr = $this->content ? explode(',',$this->content) : false;
		$higher = $this->higher ? explode(',',$this->higher) : false;
		$query = $this->_db->query("select id,typename from device_type");
		$i = 0;
		while (($rs = $this->_db->fetch_array($query))!=false)
		{
			$i++;
			if ($arr && in_array($rs['id'],$arr))
			{
				$str .='<input type="checkbox" checked name="content[]" value="'.$rs['id'].'" />'.$rs['typename'].' ';
			}elseif ($higher && in_array($rs['id'],$higher)){
				$str .='<input type="checkbox" checked name="content[]" value="'.$rs['id'].'" />'.$rs['typename'].' ';
			}else{
				$str .='<input type="checkbox" name="content[]" value="'.$rs['id'].'" />'.$rs['typename'].' ';
			}
			if ($i%6==0) $str .='<br />';
		}
		return $str;
	}

	function model_show_field()
	{
        $str = '';
		$arr = $this->content ? explode(',',$this->content) : false;
		$higher = $this->higher ? explode(',',$this->higher) : false;
		$rs = $this->_db->get_one("select field from purview_type where id=".$_GET['typeid']);
		if ($rs)
		{
			$field = explode(',',$rs['field']);
			foreach ($field as $v)
			{
				if ($arr && in_array($v,$arr))
				{
					$str .='<input type="checkbox" checked name="content[]" value="'.$v.'" />'.$v.'<br />';
				}elseif ($higher && in_array($v,$higher)){
					$str .='<input type="checkbox" checked name="content[]" value="'.$v.'" />'.$v.'<br />';
				}else{
					$str .='<input type="checkbox" name="content[]" value="'.$v.'" />'.$v.'<br />';
				}
			}
			return $str;
		}
	}
	function get_list()
	{
		if ($_GET['typeid'])
		{
			$rs = $this->_db->get_one("select typeid,act from purview_type where id=".$_GET['typeid']);
			if ($rs)
			{
				if ($rs['typeid']==1)
				{
					return $this->{'model_'.$rs['act']}();
				}elseif ($rs['typeid']==2){
					return $this->model_show_field();
				}else{
					return $this->show_bool();
				}
			}
		}
	}
	function show_bool()
	{
		$higher = ($this->higher=='0' || $this->higher )? explode(',',$this->higher) : false;
		if ($higher && in_array('1',$higher))
		{
			$str = '<input type="radio" checked name="content[]" value="1" />�� ';
			$str .= '<input type="radio" name="content[]" value="0" />��';
		}elseif ($higher && in_array('0',$higher))
		{
			$str = '<input type="radio" name="content[]" value="1" />�� ';
			$str .= '<input type="radio" checked name="content[]" value="0" />��';
		}else{
			$str = '<input type="radio" checked name="content[]" value="1" />�� ';
			$str .= '<input type="radio" name="content[]" value="0" />��';
		}
		return $str;
	}

	 function model_read(){
	 	$higher = ($this->higher=='0' || $this->higher )? explode(',',$this->higher) : false;
	 	if ($higher && in_array('1',$higher))
		{
			$str = '<input type="radio" checked name="content[]" value="1" />�� ';
			$str .= '<input type="radio" name="content[]" value="0" />��';
		}elseif ($higher && in_array('0',$higher))
		{
			$str = '<input type="radio" name="content[]" value="1" />�� ';
			$str .= '<input type="radio" checked name="content[]" value="0" />��';
		}else{
			$str = '<input type="radio" checked name="content[]" value="1" />�� ';
			$str .= '<input type="radio" name="content[]" value="0" />��';
		}
		return $str;
	 }

	 /********************************OA����Ȩ�޿���*****************************************/

	 function model_hasPurview(){
        $str = "";
        if( 1 == $this->content){
            $str = '<input type="checkbox" checked="checked" name="content[]" value="1" /> ��';
        }else{
            $str = '<input type="checkbox" name="content[]" value="1" />��';
        }
        return $str;
	 }

	/**
	 * �з���Ŀ����
	 */
	 function model_rdprojectType(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select dataCode,dataName from oa_system_datadict where parentCode = 'YFXMGL'";
	 	$rows = $this->findSql($sql);
	 	$str = "";
	 	foreach($rows as $val){
	 		if( in_array($val['dataCode'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}

	 	}
		return $str;
	 }

	/**
	 * �з���Ŀ�ȼ�
	 */
	 function model_rdprojectProjectLevel(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select dataCode,dataName from oa_system_datadict where parentCode = 'XMYXJ'";
	 	$rows = $this->findSql($sql);
	 	$str = "";
	 	foreach($rows as $val){
	 		if( in_array($val['dataCode'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}
	 	}
		return $str;
	 }

	 /**
	  * ��������
	  */
	 function model_sellRegion(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,areaName,areaCode from oa_system_region";
	 	$rows = $this->findSql($sql);
	 	$str = "";
	 	foreach($rows as $val){
	 		if( in_array($val['id'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['id'].'" />'.$val['areaName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['id'].'" />'.$val['areaName'];
	 		}
	 	}
		return $str;
	 }
      /**
       * ����
       */
     function model_department(){
        $arr = $this->content ? explode(',',$this->content) : false;
        $sql = "select DEPT_ID,DEPT_NAME from department";
        $rows = $this->findSql($sql);
        $str = "";
        foreach($rows as $val){
             if( in_array($val['DEPT_ID'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['DEPT_ID'].'" />'.$val['DEPT_NAME'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['DEPT_ID'].'" />'.$val['DEPT_NAME'];
	 		}
        }
        return $str;
     }

      /**
	 * ����
	 */
	function model_departmentAll() {
		$arr = $this->content ? explode(',', $this->content) : false;
		$sql = "select DEPT_ID AS id,DEPT_NAME AS name,PARENT_ID  AS pid from department ORDER BY PARENT_ID";
		$rows = $this->findSql($sql);
		$str = '';

		$nameCache = array();
		$idCache = array();

		if ($rows) {
			// ѭ������
			foreach ($rows as $v) {
				if (isset($this->_dataCache[$v['pid']])) {
					$this->_dataCache[$v['pid']][] = $v;
				} else {
					$this->_dataCache[$v['pid']] = array($v);
				}

				if (in_array($v['id'], $arr)) {
					$nameCache[] = $v['name'];
					$idCache[] = $v['id'];
				}
			}
		}

		$name = implode(',', $nameCache);
		$id = implode(',', $idCache);

		$str .= <<<E
			��ѡ������
			<input type="button" onclick="allCheck();" value="ȫѡ">
			<input type="button" onclick="allClear();" value="���">
			<input type="hidden" id="showId" value="{$id}" style="width:500;"><br/>
			<textarea id="show" style="width:400;height:100px;" readonly="readonly">{$name}</textarea><br/>
			<script type="text/javascript">
				$(function() {
					$("input:checkbox").bind('click', function() {
						showDept();
					});
				});

				var allCheck = function() {
					$('input:checkbox').attr('checked', true);
					showDept();
				}

				var allClear = function() {
					$('input:checkbox').attr('checked', false);
					showDept();
				}

				var showDept = function() {
					var ids = [];
					var names = [];
					$("input:checkbox").each(function() {
						if ($(this).attr('checked')) {
							ids.push($(this).val());
							names.push($(this).attr('title'));
						}
					});
					$("#showId").val(ids.toString());
					$("#show").val(names.toString());
				}
			</script>
E;

		// ���Ź���
		if (in_array(';;', $arr)) {
			$checked = 'checked="checked"';
		} else {
			$checked = '';
		}
		$str .= '<input type="checkbox" ' . $checked . ' id="all" name="content[]" title="ȫ��" value=";;"/>ȫ��<br/>';

		// �ַ�������
		$this->buildDept(0, $arr);

		$str .= $this->_dataStr;

		return $str;
	}

// ���ݻ���
	private $_dataCache = array();
	private $_dataStr = '';

	// ���ι���
	function buildDept($parentId, $arr, $m = 1) {
		// ������ڻ��棬�Ž��д�
		if (isset($this->_dataCache[$parentId])) {
			// ��������
			$innerDeptCache = $this->_dataCache[$parentId];
			unset($this->_dataCache[$parentId]);

			if (isset($innerDeptCache)) {
				foreach ($innerDeptCache as $v) {
					if (in_array($v['id'], $arr)) {
						$checked = 'checked="checked"';
					} else {
						$checked = '';
					}
					$this->_dataStr .= $this->buildImg($m) . '<input type="checkbox" ' . $checked .
						' name="content[]" id="' . $v['id'] . '" title="' . $v['name'] . '" value="' . $v['id'] .
						'" pid="' . $v['pid'] . '"/>' . $v['name'] . '<br/>';

					if (isset($this->_dataCache[$v['id']])) {
						$this->buildDept($v['id'], $arr, $m + 1);
					}
				}
			}
		}
	}

	// ռλ������
	function buildImg($m) {
		$imgLine = '<img src="images/menu/tree_transp.gif"/>'; //ֱ��
		$str = '';
		while ($m > 0) {
			$str .= $imgLine;
			$m--;
		}
		return $str;
	}

	 /**
	  * ��ͬ����
	  */
	 function model_contractType(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$rows = array(
			array( "oa_sale_order" , "���ۺ�ͬ" ),
			array( "oa_sale_lease" , "�����ͬ" ),
			array( "oa_sale_service" , "���޺�ͬ" ),
			array( "oa_sale_rdproject" , "�з���ͬ" )
	 	);
	 	$str = "";
	 	foreach($rows as $val){
	 		if( in_array($val['0'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val[0].'" />'.$val[1];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val[0].'" />'.$val[1];
	 		}
	 	}
		return $str;
	 }

	 /**
	  * ����Ȩ��
	  */
	 function model_dept(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select DEPT_ID,DEPT_NAME from department";
	 	$rows = $this->findSql($sql);
	 	$str = "";
	 	foreach($rows as $val){
	 		if( in_array($val['DEPT_ID'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['DEPT_ID'].'" />'.$val['DEPT_NAME'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['DEPT_ID'].'" />'.$val['DEPT_NAME'];
	 		}
	 	}
		return $str;
	 }

	 /**
	  * ���´�Ȩ��
	  */
	 function model_esmOffice(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,officeName from oa_esm_office_baseinfo";
	 	$rows = $this->findSql($sql);

	 	if( in_array(';;',$arr) ){
	 		$str = '<input type="checkbox" name="content[]" value=";;" checked="checked"/>ȫ��';
	 	}else{
	 		$str = '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}
	 	foreach($rows as $val){
	 		if( in_array($val['id'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['id'].'" />'.$val['officeName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['id'].'" />'.$val['officeName'];
	 		}
	 	}
		return $str;
	 }

	 /**
	  * ʡ��Ȩ��
	  */
	 function model_provinceNames(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select provinceName from oa_system_province_info";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if( in_array(';;',$arr) ){
	 		$str = '<input type="checkbox" name="content[]" value=";;" checked="checked"/>ȫ��';
	 	}else{
	 		$str = '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}
	 	foreach($rows as $val){
	 		if( in_array($val['provinceName'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['provinceName'].'" />'.$val['provinceName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['provinceName'].'" />'.$val['provinceName'];
	 		}
	 	}
		return $str;
	 }

	 /**
	  *
	  * ��������Ȩ��
	  */
	 function model_proType(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select p.id,p.`proType` from oa_stock_product_type p where parentId=-1";
	 	$rows = $this->findSql($sql);
	 	$str = "";
	 	foreach($rows as $val){
	 		if( in_array($val['id'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['id'].'" />'.$val['proType'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['id'].'" />'.$val['proType'];
	 		}
	 	}
		return $str;
	 }

	/**
	 * �ͻ�����
	 */
	 function model_customerType(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select dataCode,dataName from oa_system_datadict where parentCode = 'KHLX'";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if( in_array(';;',$arr) ){
	 		$str = '<input type="checkbox" name="content[]" value=";;" checked="checked"/>ȫ��';
	 	}else{
	 		$str = '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}
	 	foreach($rows as $val){
	 		if( in_array($val['dataCode'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}
	 	}
		return $str;
	 }

	 /**
	  * ����������Ȩ�� - ����ȫ��ѡ��
	  */
	 function model_sellRegionAll(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,areaName,areaCode from oa_system_region";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
			$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
			$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['id'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['id'].'" />'.$val['areaName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['id'].'" />'.$val['areaName'];
	 		}
	 	}
		return $str;
	 }
    /**
     * ����Ȩ��- ��������
     */
    function model_sellRegionAllName(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,areaName,areaCode from oa_system_region";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
			$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
			$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['areaName'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['areaName'].'" />'.$val['areaName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['areaName'].'" />'.$val['areaName'];
	 		}
	 	}
		return $str;
	 }
	 /**
	  * ʡ��
	  */
	 function model_sellprovinceAllName(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,provinceName,provinceCode from oa_system_province_info";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
			$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
			$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['provinceName'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['provinceName'].'" />'.$val['provinceName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['provinceName'].'" />'.$val['provinceName'];
	 		}
	 	}
	 	$pvurlArr = array("ϵͳ��","�ƶ��ܲ�","��ͨ�ܲ�","�����ܲ�");
	 	foreach($pvurlArr as $key => $val){
	 		if( in_array($val,$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val.'" />'.$val;
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val.'" />'.$val;
	 		}
	 	}
		return $str;
	 }
	  /**
	  * ��ȫ������͹���Ȩ��
	  */
	 function model_SafeStockType(){
	 	$checkedArr = $this->content ? explode(',',$this->content) : false;
	 	$checked0="";$checked1="";$checked2="";
	 	if(in_array(0, $checkedArr)){
	 		$checked0="checked";
	 	}
	 	if(in_array(1, $checkedArr)){
	 		$checked1="checked";
	 	}
	 	 if(in_array(2, $checkedArr)){
	 		$checked2="checked";
	 	}

		$str='<input type="checkbox" name="content[]" '.$checked0.' value="0" />ԭ����'.
		'<input type="checkbox" name="content[]" '.$checked1.' value="1" />�⹺��Ʒ'.
		'<input type="checkbox" name="content[]" '.$checked2.' value="2" />���/��װ��';
		return $str;
	 }

	 /**
	  * �º�ͬ ��Ʒ����Ȩ��
	  */
	 function model_goodsAll(){
		$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,goodsType from oa_goods_type where parentId=-1";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
			$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
			$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['id'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['id'].'" />'.$val['goodsType'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['id'].'" />'.$val['goodsType'];
	 		}
	 	}
		return $str;
	 }


	 /**
	  * ��������
	  */
	 function model_borrowType(){
		$arr = $this->content!==null ? explode(',',$this->content) : false;
	 	$rows = array(
			array( "1" , "�ͻ�" ),
			array( "2" , "Ա��" ),
			array( "3" , "����" )
	 	);
	 	$str = "";
	 	foreach($rows as $val){
	 		if( in_array($val['0'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val[0].'" />'.$val[1];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val[0].'" />'.$val[1];
	 		}
	 	}
		return $str;
	 }

	 /**
	  * �ִ�ģ��
	  */
	 function model_allStock(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,stockName,stockCode from oa_stock_baseinfo";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
	 		$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
	 		$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['id'],$arr) ){
	 			$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['id'].'" />'.$val['stockName'];
	 		}else{
	 			$str .= '<input type="checkbox" name="content[]" value="'.$val['id'].'" />'.$val['stockName'];
	 		}
	 	}
	 	return $str;
	 }

	/**
	 * ���� - ����ȫ��ѡ��
	 *
	 * @param string $areaid
	 * @return string
	 */
	function model_areaAll(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select ID,Name from area where del = 0";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
	 		$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
	 		$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['ID'],$arr) ){
	 			$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['ID'].'" />'.$val['Name'];
	 		}else{
	 			$str .= '<input type="checkbox" name="content[]" value="'.$val['ID'].'" />'.$val['Name'];
	 		}
	 	}
		return $str;
	}

	/**
	 * ��˾Ȩ��
	 *
	 * @param string $areaid
	 * @return string
	 */
	function model_companyAll(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select namePT,nameCN from branch_info where type = 1";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
	 		$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
	 		$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['namePT'],$arr) ){
	 			$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['namePT'].'" />'.$val['nameCN'];
	 		}else{
	 			$str .= '<input type="checkbox" name="content[]" value="'.$val['namePT'].'" />'.$val['nameCN'];
	 		}
	 	}
		return $str;
	}

    /**
     * ��ҵ��Ȩ��
     * @return string
     */
    function model_businessAll(){
        $deptMappingDao = new model_bi_deptFee_deptMapping();
        $arr = $this->content ? explode(',',$this->content) : false;
        $rows = $deptMappingDao->getBusinessByDept();
        $str = "";

        if(in_array(';;',$arr)){
            $str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
        }else{
            $str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
        }

        foreach($rows as $val){
            if( in_array($val['business'],$arr) ){
                $str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['business'].'" />'.$val['business'];
            }else{
                $str .= '<input type="checkbox" name="content[]" value="'.$val['business'].'" />'.$val['business'];
            }
        }
        return $str;
    }

    /**
     * �������Ȩ��
     *
     * @return string
     */
    function model_loanTypeArr(){
        if($this->content === '0'){
            $arr = array('0');
        }else{
            $arr = $this->content ? explode(',',$this->content) : false;
        }

        $otherDataDao = new model_common_otherdatas();
        $loanTypesStr = $otherDataDao->getConfig('loanTypes');
        $rows = array();
        if($loanTypesStr != ''){
            $arr1 = explode(",",$loanTypesStr);
            foreach ($arr1 as $v){
                $arr2 = explode(":",$v);
                $rows[$arr2[0]] = $arr2[1];
            }
        }
        $str = "";

        if(in_array(';;',$arr)){
            $str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
        }else{
            $str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
        }

        foreach($rows as $key => $val){
            if( in_array($key,$arr) ){
                $str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$key.'" />'.$val;
            }else{
                $str .= '<input type="checkbox" name="content[]" value="'.$key.'" />'.$val;
            }
        }
        return $str;
    }

	/**
	 * ��Ʒ��Ȩ��
	 */
	 function model_productLine(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select dataCode,dataName from oa_system_datadict where parentCode = 'HTCPX'";
	 	$rows = $this->findSql($sql);
	 	$str = "";
	 	if(in_array(';;',$arr)){
	 		$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
	 		$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}
	 	foreach($rows as $val){
	 		if( in_array($val['dataCode'],$arr) ){
				$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}else{
 				$str .= '<input type="checkbox" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}

	 	}
		return $str;
	 }

    /**
     * ��Ʒ��Ȩ�ޣ��£�-���� LiuB by 2015-01-01 �����һ�д�������
     */
    function model_productLineDept(){
        $arr = $this->content ? explode(',',$this->content) : false;
        $sql = "select DEPT_ID,DEPT_NAME from department";
        $rows = $this->findSql($sql);
        $str = "";

        foreach($rows as $val){
            if( in_array($val['DEPT_ID'],$arr) ){
                $str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['DEPT_ID'].'" />'.$val['DEPT_NAME'];
            }else{
                $str .= '<input type="checkbox" name="content[]" value="'.$val['DEPT_ID'].'" />'.$val['DEPT_NAME'];
            }

        }
        return $str;
    }
    /**
	  * �������� - ����ȫ��ѡ��
	  *
	  * @param string $areaid
	  * @return string
	  */
	 function model_agencyAll(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select id,agencyName from oa_asset_agency";
	 	$rows = $this->findSql($sql);
	 	$str = "";

	 	if(in_array(';;',$arr)){
	 		$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
	 		$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}

	 	foreach($rows as $val){
	 		if( in_array($val['id'],$arr) ){
	 			$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['id'].'" />'.$val['agencyName'];
	 		}else{
	 			$str .= '<input type="checkbox" name="content[]" value="'.$val['id'].'" />'.$val['agencyName'];
	 		}
	 	}
	 	return $str;
	 }
	 
	 /**
	 * ִ������Ȩ��
	 */
	function model_exeDept() {
		$arr = $this->content ? explode(',', $this->content) : false;
		$sql = "select dataCode,dataName from oa_system_datadict where parentCode = 'GCSCX'";
		$rows = $this->findSql($sql);
		$str = "";
		// ���Ź���
		if (in_array(';;', $arr)) {
			$checked = 'checked="checked"';
		} else {
			$checked = '';
		}
		$str .= '<input type="checkbox" ' . $checked . ' id="all" name="content[]" title="ȫ��" value=";;"/>ȫ��<br/>';

		foreach ($rows as $val) {
			if (in_array($val['dataCode'], $arr)) {
				$checked = 'checked="checked"';
			} else {
				$checked = '';
			}
			$str .= '<input type="checkbox" ' . $checked . ' name="content[]" value="' .
				$val['dataCode'] . '" />' . $val['dataName'] . '<br/>';

		}
		return $str;
	}
	 
	 /**
	  * ���Ȩ��
	  */
	 function model_module(){
	 	$arr = $this->content ? explode(',',$this->content) : false;
	 	$sql = "select dataCode,dataName from oa_system_datadict where parentCode = 'HTBK'";
	 	$rows = $this->findSql($sql);
	 	$str = "";
	 	if(in_array(';;',$arr)){
	 		$str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
	 	}else{
	 		$str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
	 	}
	 	foreach($rows as $val){
	 		if( in_array($val['dataCode'],$arr) ){
	 			$str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}else{
	 			$str .= '<input type="checkbox" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
	 		}
	 
	 	}
	 	return $str;
	 }

	 /**
      * ��Ŀ����Ȩ��
      */
	 function model_attributeAll(){
         $arr = $this->content ? explode(',',$this->content) : false;
         $sql = "select dataCode,dataName from oa_system_datadict where parentCode = 'GCXMSS'";
         $rows = $this->findSql($sql);
         $str = "";
         if(in_array(';;',$arr)){
             $str .= '<input type="checkbox" name="content[]" value=";;" checked="checked" />ȫ��';
         }else{
             $str .= '<input type="checkbox" name="content[]" value=";;" />ȫ��';
         }
         foreach($rows as $val){
             if( in_array($val['dataCode'],$arr) ){
                 $str .= '<input type="checkbox" checked="checked" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
             }else{
                 $str .= '<input type="checkbox" name="content[]" value="'.$val['dataCode'].'" />'.$val['dataName'];
             }

         }
         return $str;
     }
}