<?php
/*
 * Created on 2011-1-8
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_rdproject_baseinfo_rddatain extends model_base {
	function __construct () {
		parent::__construct();
	}

	private $userArr = array();

	/**
	 * 获取一类项目内容
	 */
	function getItemOne_d(){
		$itemOneSql = "select ItemId,Name,Remark,PrincipalId,ApplyId,Date,Changeed,ExaStatus,ExaDT,ChangeReason,OldPriMan,CloseMan,CloseReason,CloseDT from item_one";
		return $this->_db->getArray($itemOneSql);
	}

	/**
	 * 获取二类项目内容
	 */
	function getItemTwo_d(){
		$itemTwoSql = "select ItemId,Name,Remark,PrincipalId,ApplyId,Date,Changeed,ExaStatus,ExaDT,ChangeReason,OldPriMan,CloseMan,CloseReason,CloseDT from item_two";
		return $this->_db->getArray($itemTwoSql);
	}

	/**
	 * 获取三类项目内容
	 */
	function getItemThree_d(){
		$itemThreeSql = "select ItemId,Name,Remark,PrincipalId,ApplyId,Date,Changeed,ExaStatus,ExaDT,ChangeReason,OldPriMan,CloseMan,CloseReason,CloseDT from item_three";
		return  $this->_db->getArray($itemThreeSql);
	}

	/**
	 * 返回当前项目节点的最大值
	 */
	function rtRootRightNode_d(){
		$sql = "select rgt from oa_rd_group where id = -1 ";
		$rows = $this->_db->getArray($sql);
		return $rows[0]['rgt'];
	}

	/**
	 * 根据人员帐号返回名称
	 */
	function rtUserName_d($user_id){
		if(isset($this->userArr[$user_id])){
			return $this->userArr[$user_id];
		}else{
			$sql = "select USER_NAME from user where USER_ID = '".$user_id."'";
			$rows = $this->_db->getArray($sql);
			$this->userArr[$user_id] = $rows[0]['USER_NAME'];
			return $rows[0]['USER_NAME'];
		}
	}

	/**
	 * 添加项目组合,项目
	 */
	function addGroup_d(){
		echo "<pre>";
		$rowsOne = $this->getItemOne_d();
		$rowsTwo = $this->getItemTwo_d();
		$rowsThree = $this->getItemThree_d();
		$i = $gl = $gr = $pl = $pr = 0;
		$mark = $rgt = $this->rtRootRightNode_d();
		$date = date('Y-m-d');
		$time = date('Y-m-d H:i:s');
		print_r($rgt);
		print_r($rowsOne);

		$itemOneIn = "insert into oa_rd_group (parentId,parentName,lft,rgt,groupName,groupCode,managerId,managerName,description,createId,createName,createTime,updateTime) values ";
		$itemProOne = "insert into oa_rd_project (groupSName,groupId,projectName,simpleName,projectCode,projectType,projectLevel,planDateStart,planDateClose,appraiseWorkload,managerId,managerName,description,businessCode,status,effortRate,warpRate,lft,rgt,actBeginDate,createId,createName,createTime,updateId,updateName,updateTime,ExaStatus,ExaDT) values ";
		foreach($rowsOne as $val){
			$i++;
			//确定项目组的编号
			$thisCode = 'group-00000'.$i;
			$gl = $mark;
			$gr = $mark + 3;
			$groupIn =  $itemOneIn ."( -1,'root',".$gl .",".$gr .",'".$val['Name']."','".$thisCode."'.'".$val['PrincipalId']."'.'".$this->rtUserName_d($val['PrincipalId'])."','".$val['Remark']."','".$val['PrincipalId']."','".$this->rtUserName_d($val['PrincipalId'])."','".$time."','".$time."')"  ;
			$mark = $mark + 4;
			$newId = "";
//			$this->query($itemOneIn);
			$projectCode = 'rdproject' . date ( "YmdHis" ) . rand ( 10, 99 );
			if($val['ExaStatus'] == '完成'){
				$status = 6;
			}else{
				$status = 1;
			}

			$pl = $mark + 1;
			$pr = $mark + 2;

			$projectIn = $itemProOne. "('".$val['Name']."','".$newId."','".$val['Name']."','".$val['Name']."','".$val['ItemId']."','YLXM','XMYXJZ','".$date."','".$date."','0','".$val['PrincipalId']."','".$this->rtUserName_d($val['PrincipalId'])."','".$val['Remark']."','".$projectCode."','".$status."','0','0','".$pl."','".$pr."','".$date."','".$val['PrincipalId']."','".$this->rtUserName_d($val['PrincipalId'])."','".$time."','".$val['PrincipalId']."','".$this->rtUserName_d($val['PrincipalId'])."','".$time."','".$val['ExaStatus']."','".$val['ExaDT']."' )";
			echo $projectIn;
		}
//		$this->query($itemOntIn);

//		print_r($rowsTwo);
//		print_r($rowsThree);
	}
}
?>
