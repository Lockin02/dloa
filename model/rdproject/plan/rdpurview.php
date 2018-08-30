<?php
/*
 * Created on 2010-10-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * �ƻ�Ȩ��
 */
class model_rdproject_plan_rdpurview extends model_base{

	function __construct() {
		$this->tbl_name = "oa_rd_plan_purview";
		$this->sql_map = "rdproject/plan/rdpurviewSql.php";
		parent::__construct ();
	}


	/****************************ҳ����ʾ����********************************/
	/**
	 * Ĭ��
	 */
	function showlist($rows,$isGetPurview){
		$str = "";
		if($rows){
			$i = 0;
			$checkpoint1 = $checkpoint2 = "";
			foreach($rows as $val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				if($val['purviewKey']){
					$checkpoint1 = "checked='checked'";
					$checkpoint2 = "";
					if($val['planOwner']){
						$disabled1 = "disabled='disabled'";
						$disabled2 = "disabled='disabled'";
					}else{
						if($isGetPurview){
							$disabled1 = "";
							$disabled2 = "";
						}else{
							$disabled1 = "";
							$disabled2 = "disabled='disabled'";
						}
					}
				}else{
					$checkpoint1 = "";
					$checkpoint2 = "checked='checked'";
					if($val['planOwner']){
						$disabled1 = "disabled='disabled'";
						$disabled2 = "disabled='disabled'";
					}else{
						if($isGetPurview){
							$disabled1 = "";
							$disabled2 = "";
						}else{
							$disabled1 = "disabled='disabled'";
							$disabled2 = "";
						}
					}
				}
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$val[userName]
						</td>
						<td>
							<input type="radio" name="rdpurview[$val[userId]]" value="0" $checkpoint2 $disabled2/>ֻ��
							<input type="radio" name="rdpurview[$val[userId]]" value="1" $checkpoint1 $disabled1/>������
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/******************************ҵ�񷽷������ⲿ����************************/
	/**
	 * ���Ȩ����-Ĭ�ϰ�����Ŀ�Ŷӳ�Ա����Ŀ�������Ŀ����
	 */
	function addGroup($projectId,$planId,$parentId){
		//��ȡ��Ŀ������
		$projectDao = new model_rdproject_project_rdproject();
		$projectRows = $projectDao->rpManageUserById_d($projectId);
//		print_r($projectRows);

		//��ȡ��ͨ��Ŀ��Ա
		$memberDao = new model_rdproject_team_rdmember();
		$memberRows = $memberDao->getMemberByProjectId($projectId);

		if(!empty($memberRows)){
			$newRows = array_merge($projectRows,$memberRows);
		}else{
			$newRows = $projectRows;
		}

		//��ȡ�ϼ��ƻ�Ȩ��
		$parentPurviewArr = $this->getListInPlan($parentId);

		$tempArr = array();

		if(is_array($parentPurviewArr)){
			$sql = "insert into ". $this->tbl_name . " (projectId,planId,userName,userId,purviewKey,createId,createName,planOwner) select projectId, $planId as planId,userName,userId,purviewKey,createId,createName,planOwner from " . $this->tbl_name . " where planId = ".$parentId;
		}else{
			$i = 0;

			$sql = "insert into ". $this->tbl_name." (projectId,planId,userName,userId,purviewKey,createId,createName,planOwner) values ";
			foreach($newRows as $val){
				if(!in_array($val['userId'],$tempArr)){
					if($val['type'] != 'type'){
						if( $val['userId'] == $_SESSION['USER_ID']){
							$tempArr[$i] = $val['userId'];
							$sql.= "('$projectId','$planId','" .$val['name'] . "','" .$val['userId'] . "','1','".$_SESSION['USER_ID']."','".$_SESSION['USERNAME']."','1'),";
						}else{
							$tempArr[$i] = $val['userId'];
							$sql.= "('$projectId','$planId','" .$val['name'] . "','" .$val['userId'] . "','1','".$_SESSION['USER_ID']."','".$_SESSION['USERNAME']."','0'),";
						}
					}else{
						$tempArr[$i] = $val['userId'];
						$sql.= "('$projectId','$planId','" .$val['name'] . "','" .$val['userId'] . "','0','".$_SESSION['USER_ID']."','".$_SESSION['USERNAME']."','0'),";
					}
				}
				$i++;
			}
			$sql = substr($sql,0,-1);
		}

		try{
			$this->query($sql);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * ���ݼƻ�ID��ȡ�ƻ��ڵ�Ȩ��
	 */
	function getListInPlan($planId){
		$this->searchArr['planId'] = $planId;
		return $this->listBySqlId('base_list');
	}

	/**
	 * ����ƻ���Ȩ��
	 */
	function savePlanPurview($object,$planId){
//		print_r($object);
		$arrPurview = array();
		foreach($object as $key => $val){
			$this->updateField(array('planId' => $planId ,'userId' => $key),'purviewKey',$val);
		}
		return true;
	}

	/**
	 * ����Ȩ��
	 */
	function checkPurview($planId){
		$rows = $this->find(array('planId' => $planId,'userId' => $_SESSION['USER_ID']),null,'purviewKey');
		return $rows['purviewKey'];
	}

	/**
	 * ��дadd_d
	 */
	function add_d($object){
		$newId = $this->create ( $object );
		return $newId;
	}
}
?>
