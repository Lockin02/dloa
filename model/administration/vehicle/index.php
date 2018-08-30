<?php
class model_administration_vehicle_index extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'vehicle';
		$this->pk='V_ID';
	}
	/**
	 * 获取列表
	 * @param $conditions
	 * @param $page
	 * @param $rows
	 */
	function GetDataList($conditions = null, $page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$this->num = $this->GetCount ( str_replace ( 'a.', '', $conditions ) );
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		if ($this->tbl_name == 'vehicle')
		{
			return $this->findAll ( $conditions, $this->pk . ' desc', '*', $limit );
		}else if ($this->tbl_name == 'vehicle_usage'){
			
			if(is_array($conditions)){
				$join = array();
				foreach( $conditions as $key => $condition ){
					$condition = $this->__val_escape($condition);
					$join[] = "{$key} = '{$condition}'";
				}
				$where = "WHERE ( ".join(" AND ",$join). ")";
			}else{
				if(null != $conditions)$where = "WHERE ( ".$conditions. ")";
			}
			$query = $this->query("
									select 
										a.*,b.user_name as apply_user,c.user_name as use_user
									from 
										$this->tbl_name as a 
										left join user as b on b.user_id=a.VU_PROPOSER
										left join user as c on c.user_id=a.VU_USER
									$where
									order by $this->pk desc
									".($limit ? 'limit '.$limit : '')."
								");
			$data = array();
			while (($rs = $this->fetch_array($query))!=false)
			{
				$data[] = $rs;
			}
			return $data;
		}
	}
	/**
	 * 审核
	 * @param $id
	 * @param $data
	 */
	function audit($id,$data)
	{
		$info = $this->get_one("
								select 
									a.*,email 
								from 
									vehicle_usage as a 
									left join user as b on b.user_id=a.VU_PROPOSER
								where
									a.VU_ID=$id
		
		");
		if ($info)
		{
			$this->tbl_name = 'vehicle_usage';
			$this->pk = 'VU_ID';
			if ($this->Edit($data,$id))
			{
				$email = new includes_class_sendmail();
				$content = $data['VU_STATUS'] == '-1' ? "您申请的车辆使用已被打回,原因如下：<p />".$data['VU_NOTES'] : '您申请的车辆使用已通过审批！';
				return $email->send('车辆使用申请审批报告',$content.oaurlinfo,$info['email']);
			}
		}
	}
	/**
	 * 发送邮件给审批人
	 * @param $dept_id
	 */
	function send_email_audit()
	{
		$query = $this->query("select 
										d.email
								from 
									purview as a 
									left join purview_type as b on b.tid=a.id
									left join purview_info as c on c.tid=a.id and c.typeid=b.id
									left join user as d on d.user_id=c.userid
									where 
										a.models = 'administration_vehicle_index'
										and b.name='审批部门'
										and find_in_set('".$_SESSION['DEPT_ID']."',c.content)
									");
			$email = array();
			while (($rs = $this->fetch_array($query))!=false)
			{
				$email[] = $rs['email'];
			}
			if ($email)
			{
				$mail_server = new includes_class_sendmail();
				$body .= "您好，".$_SESSION['USERNAME']."提交了车辆使用申请，需要您登录OA审批处理。<p /> 登录OA -> 个人办公 -> 用车申请 -> 进行车辆申请审批操作。<br />".oaurlinfo;
				return $mail_server->send($_SESSION['USERNAME'].' 提交了车辆使用申请，需要您登录OA审批处理',$body,$email);
			}
	}
	
}

?>