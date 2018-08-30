<?php
class model_module_release_list extends model_base
{
	public $ftp_host;
	public $ftp_port;
	public $ftp_user;
	public $ftp_pwd;
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'base_module';
		//FTP连接配置
		$this->ftp_host = '172.16.1.102';
		$this->ftp_port = '21';
		$this->ftp_user = 'oa_upfile';
		$this->ftp_pwd  = 'dinglicom_oa'; 
	}
	/**
	 * 分页列表数据
	 * @param $condition
	 * @param $page
	 * @param $rows
	 * @param $group
	 */
	function GetDataList($condition=null,$page=null,$rows=null,$group=false)
	{
		if ($page && $rows && !$this->num)
		{
			$rs = $this->get_one("
									select 
										count(".($group ? "distinct(a.identification)" : 0).") as num 
									from 
										$this->tbl_name as a 
										left join user as b on b.user_id=a.user_id
										left join user as c on c.user_id=a.owner
										left join user as d on d.user_id=a.reviewer
									" . ($condition ? " where " . $condition : '')."
									");
			$this->num = $rs['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page - 1) * $pagenum) : $this->start;
			$limit = $page && $rows ? $start . ',' . $pagenum : '';
		}
		$query = $this->query("
										select 
											a.*,b.user_name,c.user_name as owner_name,d.user_name as audit_name
										from
											(SELECT module_name FROM base_module group by module_name  order by max(id) desc) as t
											left join (select ".($group ? "max(id) as" : '')." id, module_name from base_module ".($group ? "group by identification" : "")." order by date desc) as at on at.module_name=t.module_name
											left join base_module as a on a.id=at.id 
											left join user as b on b.user_id=a.user_id
											left join user as c on c.user_id=a.owner
											left join user as d on d.user_id=a.reviewer
										" . ($condition ? " having " . $condition : '')."
										" . ($limit ? "limit " . $limit : '') . "
		");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * 更新下载记录
	 * @param $module_id
	 * @param $userid
	 */
	function update_download_info($module_id,$userid)
	{
		$rs = $this->get_one("select * from base_module_download_info where module_id=$module_id and user_id='$userid'");
		if ($rs)
		{
			$this->query("update base_module_download_info set number=number+1,date='".date('Y-m-d H:i:s')."' where id=".$rs['id']);
		}else{
			$this->query("insert into base_module_download_info(module_id,user_id,number,date)values($module_id,'$userid',1,'".date('Y-m-d H:i:s')."')");
		}
		
		return $this->query("update base_module set down_num=down_num+1 where id=$module_id");
	}
	/*
	 * 审核模块
	 */
	function audit($id,$status,$audit_userid,$remark='')
	{
		$rs = $this->get_one("select a.id,a.module_name,a.audit_remark,a.version,b.email from $this->tbl_name as a left join user as b on b.user_id=a.user_id where a.id=".$id);
		if ($rs)
		{
			$email = new includes_class_sendmail();
			if ($status == 1)
			{
				if ($this->Edit(array('status'=>1,'reviewer'=>$audit_userid),$id))
				{
					$Group = new model_system_usergroup_list();
					$GroupId = $Group->GetId('module_receive_user'); //通过唯一标识获取用户组
					if ($GroupId)
					{
						$Emial_List = $Group->GetGroupUserEmail($GroupId);
						if ($Emial_List)
						{
							$this->EmailTask($rs['module_name'].' Ver'.$rs['version'].' 正式发布了','详情请登录OA查看！'.oaurlinfo,$Emial_List);
						}
					}
					return $email->send('您发布的 '.$rs['module_name'].' '.$rs['version'] .'已经通过审批了','详情请登录OA查看'.oaurlinfo,$rs['email']);
				}
			}else if ($status == -1){
				if ($this->Edit(array('status'=>-1,'reviewer'=>$audit_userid,'audit_remark'=>$remark),$id))
				{
					return $email->send('您发布的 '.$rs['module_name'].' '.$rs['version'] .'已被打回','打回理由:<p />'.$rs['audit_remark'].'</p>详情请登录OA查看'.oaurlinfo,$rs['email']);
				}
			}
		}else{
			return false;
		}
	}
}

?>