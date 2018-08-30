<?php
/**
 * @description: �Ŷӽ�ɫModel
 * @author chengl
 * @version V1.0
 */
class model_rdproject_role_rdpermission extends model_base {

	/**
	 * @desription ���캯��
	 * @date 2010-9-11 ����12:46:46
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_project_permission";
		$this->sql_map = "rdproject/role/rdpermissionSql.php";
		parent::__construct ();
	}


	function showuserlist($rows)	{
		//���´����Ƕ�Ӧ������-��̱��㡱��2010��10��26���޸ġ�27�ոĻء�
		if($rows){
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();
			foreach($rows as $key => $val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str .=<<<EOT
				<tr class="$classCss" id="tr_$val[id]">
					<td>$i</td>
					<td>$val[USER_ID]</td>
					<td>$val[USER_NAME]</td>
					<td>$val[DEPT_NAME]</td>
					<td class="remarkClass">$val[deptName]</td>
					<td>
						<a href="?model=rdproject_role_rdpermission&action=toAdd&userId=$val[USER_ID]&userName=$val[USER_NAME]&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=700" class="thickbox">����</a>
					</td>
				</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='8'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	function page_d($deptId,$user){
		//��ҳ
		if( $deptId && $deptId != 'ALL_DEPT' ){
			$deptId=substr($deptId,0, -1);
			$userSql = "select u.USER_ID,u.USER_NAME,u.DEPT_ID,p.id,p.userId,p.userName,p.deptId,p.deptName from user u left join oa_rd_project_permission p on u.USER_ID=p.userId where u.DEPT_ID in (".$deptId.")";
		}elseif($user){
			$userSql = "select u.USER_ID,u.USER_NAME,u.DEPT_ID,p.id,p.userId,p.userName,p.deptId,p.deptName from user u left join oa_rd_project_permission p on u.USER_ID=p.userId where u.USER_ID='".$user."'";
		}else{
			$userSql = "select u.USER_ID,u.USER_NAME,u.DEPT_ID,p.id,p.userId,p.userName,p.deptId,p.deptName from user u left join oa_rd_project_permission p on u.USER_ID=p.userId";
		}
		$this->sort="u.DEPT_ID";
		$userRow = $this->pageBySql($userSql);
		$deptSql = "select d.DEPT_ID,d.DEPT_NAME from department d";
		$this->sort="d.DEPT_ID";
		$deptRow = $this->listBySql($deptSql);
		foreach ( $userRow as $key=>$val ){
			foreach ( $deptRow as $index => $row ){
				if( $val['DEPT_ID']==$row['DEPT_ID'] ){
					$userRow[$key]['DEPT_NAME'] = $row['DEPT_NAME'];
				}
			}
		}
		return $userRow;
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		if( !isset($object['id']) ){
			if ($isAddInfo) {
				$object = $this->addCreateInfo ( $object );
			}
			//���������ֵ䴦�� add by chengl 2011-05-15
			$newId = $this->create ( $object );
			return $newId;
		}else{
			if ($isAddInfo) {
				$object = $this->addUpdateInfo ( $object );
			}
			return $this->updateById ( $object );
		}
	}


}

?>
