<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @description: ��Ŀ��̱�-�����Ϣ
 * @date 2010-9-26 ����03:09:08
 */
class model_rdproject_baseinfo_rdmilestoneinfo extends model_base {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-9-26 ����03:10:13
	 */
	function __construct () {
		$this->tbl_name = "oa_rd_milestone_info";
		$this->sql_map = "rdproject/baseinfo/rdmilestoneinfoSql.php";
		parent::__construct();
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*
	 **************************************************************************************************/
	/**
	 * @desription ��ʾ��̱������Ϣ
	 * @param tags
	 * @date 2010-9-26 ����04:06:19
	 */
	function showprojectlist ($rows) {
		//���´����Ƕ�Ӧ������-��̱��㡱��2010��10��26���޸ġ�27�ոĻء�
		if($rows){
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();
			foreach($rows as $key => $val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$typeOne = $datadictDao->getDataNameByCode($val['projectType']);
				if($val['frontNumb']==-1){
					$frontNumb="";
				}else{
					$frontRows=$this->findAll(array('numb'=>$val['frontNumb']));
					$frontNumb=$frontRows[0]['milestoneName'];
				}
				$str .=<<<EOT
				<tr class="$classCss" id="tr_$val[id]">
					<td><input type="checkbox" name="datacb" value="$val[id]" onClick="checkOne();"></td>
					<td>$val[milestoneName]</td>
					<td>$frontNumb</td>
					<td>$typeOne</td>
					<td>$val[createName]</td>
					<td>$val[createTime]</td>
					<td class="remarkClass"><div>$val[milestoneDescription]</div></td>
					<td>
						<a href="?model=rdproject_baseinfo_rdmilestoneinfo&action=toEdit&id=$val[id]&projectType=$val[projectType]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=700" class="thickbox">�޸�</a>
					</td>
				</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='8'>���������Ϣ</td></tr>";
		}
		return $str;
	}


//��ȡ���ҳ���С�ǰ����̱�������Ҫ��ҳ������
	function showExMilestone($rows){
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$projectType = isset( $_GET['projectType'] )?$_GET['projectType']:null;
		$getexmilestoneStr="";
		if( is_array($rows) ){
			foreach($rows as $key=>$val){
				//�ų������ѡ��
				if($parentId != $val['parentId']){
					if($projectType == $val['projectType']){
						$getexmilestoneStr .=<<<EOT
						<option value='$val[numb]'>$val[milestoneName]</option>
EOT;
					}
				}
			}
		}else{
			$getexmilestoneStr ="";
		}
		return $getexmilestoneStr;
	}

	//��ȡ���ҳ���С�ǰ����̱�������Ҫ��ҳ������--�����ҳ��ѡ����Ŀ����ʱ��
	function showExMilestoneList($rows){
		$getexmilestoneStr="";
		$getexmilestoneStr.="<option value='-1'>��ǰ����̱���</option>";
		if( is_array($rows) ){
			foreach($rows as $key=>$val){
						$getexmilestoneStr .=<<<EOT
						<option value='$val[numb]'>$val[milestoneName]</option>
EOT;
			}
		}else{
			$getexmilestoneStr ="<option value='-1'>��ǰ����̱���</option>";
		}
		return $getexmilestoneStr;
	}



	/*
	 * ��ȡ��̱���select
	 */
	function milestoneSelect_d($id){
		$this->searchArr = array('projectType'=>$_GET['projectType']);
		$arr = $this->listBySqlId("select_default");
		$rows=$this->get_d($id);
		$str = "";
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		if($rows['frontNumb']=="-1"){
			$str.=" <option value='-1'>��ǰ����̱���</option>";
		}else{
			$frontRows=$this->findAll(array('numb'=>$rows[frontNumb]));
			$frontNumb=$frontRows[0][milestoneName];
			$str.="<option value='$rows[frontNumb]'>$frontNumb</option> <option value='-1'>��ǰ����̱���</option>";
		}
		if( is_array($arr) ){
			foreach($arr as $key=>$val){
				//�ų������ѡ��
				if($id != $val['id']&&$rows['frontNumb']!=$val['numb']){
					$str .="<option value='$val[numb]'>$val[milestoneName]</option>";
				}
			}
		}
		else{
			$str =" <option value=''>�����������</option>";
		}

		return $str;
	}


	/**
	 * @desription ǰ��ģ��
	 * @param tags
	 * @date 2010-10-21 ����02:30:06
	 */
	function showExTemplate ($rows) {
		$getExTemplateStr = "";
		if( is_array($rows) ){
			foreach($rows as $key=>$val){
				$getExTemplateStr .=<<<EOT
					<option value='$val[numb]'>$val[templateName]</option>
EOT;
			}
		}else{
			return "<option value='-1'>��ǰ��ģ��</option>";
		}
		return $getExTemplateStr;
	}


	/***************************************************************************************************
	 * ------------------------------����Ϊ�ӿڷ���,����Ϊ����ģ��������--------------------------*
	 **************************************************************************************************/

	/*
	 * ������Excel����Ҫ������
	 */
	 function getExportData_d(){
		try{
			$this->start_d();

			if(!isset($this->sql_arr)){
				return $this->pageBySql("select c.milestoneName,c.projectType,c.createName,c.createTime,c.numb from " . $this->tbl_name . " c" . " where 1=1");
			}else{
				return $this->pageBySqlId();
			}
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}
	 }

	/*
	 * �����̱���Ϣ�����ݿ�
	 */
	 function addmilestone_d($objmilestone){
		try{
			$this->start_d();
			$frontRows=$this->findAll(array('numb'=>$objmilestone['frontNumb']));
			$objmilestone['exMilestoneName']=$frontRows['0'][milestoneName];

			$newId = parent::add_d($objmilestone,true);

			//�������д������������дadd_d()������ֱ��Ϊtrue,����Ҫ������ֻ�ǽ�add_d()��������ӵĺ��Ĵ����ȡ����ʵ�֡�
//			$objmilestone = $this->addCreateInfo($objmilestone);
//			$newId = $this->create( $objmilestone );

			$this->commit_d();
			return $newId;
		}catch(Exception $e){
			echo $e;
			$this->rollBack();
			return null;
		}
	 }

	 /*
	  * �鿴ģ������ݣ�ģ��������̱���
	  */
	 function templateView_d($templateId){
	 	$this->asc = FALSE;
	 	if(!isset( $this->sql_arr )){
	 		return $this->pageBySql("select c.id,c.parentId,c.milestoneName,c.numb,c.exMilestoneName,c.frontNumb,c.createName,c.createId,c.createTime,c.projectType,c.milestoneDescription from oa_rd_milestone_info c where c.parentId = " . "'" . $templateId . "'");
	 	}else{
	 		return $this->pageBySqlId();
	 	}
	 }


	 /*
	  * ���ڻ�ȡǰ����̱�������б�����
	  */
		function pageExMile_d($getId) {
		//�˴����ƶ�ȡ���ݿ�����鰴IDֵ��������
		$this->asc = FALSE;
		if (! isset ( $this->sql_arr )) {
			return $this->pageBySql ( "select c.milestoneName,c.exMilestoneName,c.createName,c.createTime,c.numb,c.frontNumb,c.parentId,c.projectType from " . $this->tbl_name . " c" . " where c.parentId = " . "'" . $getId . "' " );
		} else {
			//var_dump($this->pageBySqlId ());
			return $this->pageBySqlId ();
		}

	}

	/*
	 * �༭��̱���ʱ����ȡ��̱��������Ϣ
	 */
	function getEditMilestoneInfo_d($id){
		//���ҳ���̱������Ϣ
		$miledetail = $this->get_d($id);
		return $miledetail;

	}

	/*
	 * �༭��̱��ı��淽��
	 */
	function editMilestone_d($objinfo){
		try{
			$this->start_d();

			if(isset($objinfo['id'])){
				$id = parent::edit_d($objinfo,true);

				$this->commit_d();
				return true;
			}else{
				return false;
			}
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
		}
	}

	/*
	 * ͨ�������ȡǰ����̱�����
	 */
	function getexMileName($projectType){
		$projectType = isset($projectType)?$projectType:null;
		$sql = "select c.id,c.parentId,c.projectType,c.milestoneName from " . "oa_rd_milestone_info c " . "where c.projectType = " . "'" . $projectType . "'";
		return $this->pageBySql($sql);
	}

	/*
	 * �ṩһ���ӿڷ�����������̱��µ������Ϣ
	 */
	function returnMilestoneInfo_d($pjType){
		$condiction = array('projectType' => $pjType);
		$mileName = $this->findAll($condiction);
		return $mileName;
	}

	/**������Ŀ���ͣ���ȡ��̱���Ϣ
	*author can
	*2011-4-9
	*/
	function getMilestoneByProjectType_d($projectType){
		$condiction = array('projectType' => $projectType);
		$mileName = $this->findAll($condiction);
		return $mileName;

	}

}
?>
