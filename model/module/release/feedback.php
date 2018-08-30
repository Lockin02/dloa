<?php
class model_module_release_feedback extends model_base
{ 
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'base_module_feedback';
	}
	/**
	 * �����ύ�������ʼ�
	 * @param $data
	 */
	function Add($data)
	{
		if ($data['module_id'])
		{
			$rs = $this->get_one("
									select 
										a.module_name,b.email as owner_email,c.email 
									from 
										base_module as a 
										left join user as b on b.user_id=a.owner
										left join user as c on c.user_id=a.user_id
									where
										a.id=".$data['module_id']."
								");
			if ($rs)
			{
				if (parent::Add($data))
				{
					$email = new includes_class_sendmail();
					$title = $_SESSION['USERNAME'].'�� '.$rs['module_name'].' ģ���ύ�˷������';
					return $email->send($title,'����������£�<p />'.$data['content'].oaurlinfo,array($rs['owner_email'],$rs['email']));
				}else{
					return false;
				}
				
			}
		}
	}
}
?>