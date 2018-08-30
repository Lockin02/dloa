<?php 
/**
 * ��Ŀ������ȹ���������
 * @author chengchao.huang
 *
 */
class model_administration_appraisal_evaluate_manager extends model_base
{
	public $last_quarter; //�ϼ���
	public $this_quarter; //������
	public $manager = array(); // ��Ŀ����
	public $last_quarter_year;//�ϼ������
	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'appraisal_project_manager';
		
		$config = new model_administration_appraisal_performance_config();
		$year = $config->getYearInDB();
		$season = $config->getSeasonInDB();
		$this->this_quarter = $season;
		$this->last_quarter = $season - 1;
		$this->last_quarter_year = $year;
		/*
		$this->last_quarter = ((ceil((date('n'))/3)-1) == 0 ? 4 : ceil((date('n'))/3)-1);
		//$this->this_quarter = ceil((date('n'))/3);
		$this->this_quarter = $this->last_quarter;
		$this->last_quarter_year = (ceil((date('n'))/3)-1) == 0 ? (date('Y')-1) : date('Y');
		*/
		
		$this->manager = $config->getProjectManagerList(true);
		
		//�̶�����Ŀ����
		/*
		$this->manager = array(
								"'pinlin.zhang'",
								"'dechi.wang'",
								"'liwan.zhao'",
								"'min.zheng'",
								"'xiangxiong.jian'",
								"'sheng.fan'",
								"'yong.zhang'",
								"'zegong.ning'",
		//edit at 2012-07-19
								"'02001993'",//mujiang.yang ��ľ��
								"'yanhui.chen'",//���ӻ�
								"'minghao.zhang'",//������
								"'01001888'",//le.an ����
								"'01001893'",//yuanshui.zheng ֣Դˮ
								"'01001884'",//tianming.tang ������
								"'01001911'",//donggang.li ���
								"'02001987'",//you.wang ����
								"'jianping.luo'",//�޽�ƽ
								"'02001985'",//zhongrong.ye Ҷ����
								"'chuanjiao.xiao'",//Ф����
								"'heng.yin'",//����
								"'xinbao.zhang'",//���±�
								"'jinbo.qiu'"//�����
							);
		*/
	}
	/**
	 * �б�
	 * @param $condition
	 * @param $page
	 * @param $row
	 */
	function GetDataList($condition=null,$page=null,$rows=null)
	{
		if ($page && $rows && ! $this->num)
		{
			$rs = $this->get_one ( "
									select 
										count(0) as num 
									from 
										$this->tbl_name as a 
										left join user as b on b.user_id=a.manager_userid
										left join user as c on c.user_id=a.assess_userid
									where
										a.manager_userid in (".implode(',',$this->manager).")
										".($condition ? " and ".$condition : '')."
										");
			$this->num = $rs ['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page - 1) * $pagenum) : $this->start;
			$limit = $page && $rows ? $start . ',' . $pagenum : '';
		}
		
		$query = $this->query("
								select 
									a.*,b.user_name as manager_name,c.user_name as assess_name,d.dept_name
								from
									$this->tbl_name as a
									left join user as b on b.user_id=a.manager_userid
									left join user as c on c.user_id=a.assess_userid
									left join department as d on d.dept_id=b.dept_id
								where
									a.manager_userid in (".implode(',',$this->manager).")
									".($condition ? " and ".$condition : '')."
									order by a.id desc
									".($limit ? "limit ".$limit : '')."
		");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * ��ȡԱ��������Ŀ�����Ŀ����
	 * @param $userid
	 */
	function GetMyManager($userid)
	{
		/*
		$SQL = "
			SELECT 
				 p.manager as manager, u.user_name as manager_name
			FROM 
				project_rd AS p, project_rd_action AS a, user AS u 
			WHERE
				u.user_id = p.manager AND
				p.id = a.project AND
				a.account = '{$userid}';
				
		";
		$query = $this->query($SQL);
		*/
		$query = $this->query("
								select 
									a.*,b.user_name as manager_name
								from 
									project_info as a
									left join user as b on b.user_id=a.manager
								where 
									find_in_set('$userid',a.developer)
									or find_in_set('$userid',a.teamleader)
							");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[]=$rs;
		}
		return $data;
	}
	/**
	 * ��Ŀ������ȹ���ƽ�����˷�
	 * @param $manager_userid
	 * @param $years
	 * @param $quarter
	 */
	function GetManagerScheduleFraction($manager_userid,$years,$quarter)
	{
		$rs = $this->get_one("select avg(fraction) as num from $this->tbl_name where manager_userid='$manager_userid' and years='$years' and quarter='$quarter'");
		if ($rs)
		{
			return $rs['num'] ? round($rs['num'],3) : 0;
		}else{
			return 0;
		}
	}
	
	function getMemberByManager($manager_userid,$years,$quarter){
		$memberList = array();
		$query = $this->query("select `assess_userid` from $this->tbl_name t1, user t2  where t1.assess_userid = t2.user_id and manager_userid='$manager_userid' and years='$years' and quarter='$quarter'");
		$in = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$in[]=$rs['assess_userid'];
		}
		$memberList['in'] = $in;
		
		$allMembers = array();
		$query = $this->query("select developer from project_info where manager = '$manager_userid' ");
		while (($rs = $this->fetch_array($query))!=false)
		{
			$memberList['all'] = $rs;
		}
		
		return $memberList;
	}
	
	function getManagerRealName($userId){
		$SQL = "SELECT `user_name` FROM user WHERE `user_id` = '".$userId."'";
		$result = $this->_db->getArray($SQL);
		return isset($result[0]['user_name'])?$result[0]['user_name']:"";
	}
}
?>