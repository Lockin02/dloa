<?php
class model_base extends model_db
{
	public $page;
	public $start;
	public $num;
	function __construct()
	{
		parent::__construct ();
		$this->page = $_GET['page'] ? $_GET['page'] : 1; //获得分页
		$this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum; //分页开始数
		$this->num = $_GET['num'] ? $_GET['num'] : '';
	}
	
	/**
	 * 读取总条数
	 * @param $condition
	 */
	function GetCount($condition = null)
	{
		return $this->findCount($condition);
	}
	/**
	 * 读取数据列表
	 * @param unknown_type $condition
	 */
	function GetDataList($condition = null, $page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$this->num = $this->GetCount ( str_replace ( 'a.', '', $condition ) );
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		return $this->findAll ( $condition, $this->pk . ' desc', '*', $limit );
	}
	/**
	 * 读取单条记录
	 * @param unknown_type $condition
	 */
	function GetOneInfo($condition = null)
	{
		return $this->find ( $condition );
	}
	/**
	 * 添加
	 * @param unknown_type $data
	 */
	function Add($data)
	{
		if ($data)
		{
			return $this->create ( $data );
		} else
		{
			return false;
		}
	}
	/**
	 * 修改
	 * @param unknown_type $id
	 * @param unknown_type $key
	 * @param unknown_type $data
	 */
	function Edit($data,$id, $key = null)
	{
		if ($id && $data)
		{
			$condition = array($this->pk=>$id);
			$condition = $key ? array_merge ( $condition, array('rand_key'=>$key) ) : $condition;
			return $this->update ( $condition, $data );
		} else
		{
			return false;
		}
	}
	/**
	 * 删除
	 */
	function Del($id, $key = null)
	{
		if ($id)
		{
			$condition = array($this->pk=>$id);
			$condition = $key ? array_merge ( $condition, array('rand_key'=>$key) ) : $condition;
			return $this->delete ( $condition );
		} else
		{
			return false;
		}
	}
	/**
	 * 设置排序
	 * @param $key
	 * @param $find
	 * @param $number
	 * @param $type
	 * @param $condition
	 */
	function SetSort($key,$find, $number, $type, $condition=null)
	{
		$where = $type=='top' ? "$find > $number order by $find asc" : "$find < $number order by $find desc";
		if ($condition && is_array($condition))
		{
			$tmp = array();
			foreach ($condition as $k=>$val)
			{
				$tmp[] = "`$k`='$val'";
			}
		}else if($condition){
			$where = $condition .' and '.$where;
		}
		$where = $tmp ? implode(' and ',$tmp).' and '.$where : $where; 
		try {
			$rs = $this->get_one("select * from $this->tbl_name where $where");
			if ($rs)
			{
				$this->update(array('rand_key'=>$key),array($find=>$rs[$find]));
				$this->update(array('rand_key'=>$rs['rand_key']),array($find=>$number));
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	
	function ReQuest($name){
  	$value = $_GET[$name];
  	if($value == null){
  		$value = $_POST[$name];
  	}
  	return $value;
  }
  
  /**
     * Generates an UUID
     *
     * @author     Anis uddin Ahmad
     * @param      string an optional prefix
     * @return     string the formatted uuid
     */
    function uuid($prefix = '') {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }
    
	/**
	 * 邮件处理
	 * @p1 你调用的邮件的业务编码 str
	 * @p2 邮件接收人 str
	 * @p3 非查询脚本中的信息 array
	 * @p4 抄送人 str
	 * @p5 是否过滤公司人员 boolean
	 * @p6 公司编码 str
	 */
	function mailDeal_d($objCode,$mailUser = null,$exaInfo = null,$ccMailUser = null,$separateCompany = false,$company = null,$exaTitleInfo = null){
		//实例化一个邮件类
		$mailconfigDao = new model_system_mailconfig_mailconfig();
		$mailconfigDao->mailDeal_d($objCode,$mailUser,$exaInfo,$ccMailUser,$separateCompany,$company,$exaTitleInfo);
	}

	/**
	 * 邮件处理
	 * 获取邮件配置人
	 */
	function getMailUser_d($objCode,$separateCompany = false,$company = null){
		//实例化一个邮件类
		$mailconfigDao = new model_system_mailconfig_mailconfig();
		return $mailconfigDao->getMailUser_d($objCode,$separateCompany,$company);
	}

}
?>