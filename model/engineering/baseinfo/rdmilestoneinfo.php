<?php
/**
 * @description: ��Ŀ��̱�-�����Ϣ
 * @date 2010-9-26 ����03:09:08
 */
class model_engineering_baseinfo_rdmilestoneinfo extends model_base {
	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-9-26 ����03:10:13
	 */
	function __construct () {
		$this->tbl_name = "oa_rd_milestone_info";
		$this->sql_map = "engineering/baseinfo/rdmilestoneinfoSql.php";
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
//			$datadictDao = new model_system_datadict_datadict();
			foreach($rows as $key => $val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
//				$typeOne = $datadictDao->getDataNameByCode($val['projectType']);
				$str .=<<<EOT
				<tr class="$classCss" id="tr_$val[id]">
					<td><input type="checkbox" name="datacb" value="$val[id]" onClick="checkOne();"></td>
					<td>$i</td>
					<td>$val[id]</td>
					<td>$val[milestoneName]</td>
					<td>$val[createName]</td>
					<td>$val[createTime]</td>
					<td class="main_td_align_left">$val[milestoneDescription]</td>
					<td>
						<a href="?model=engineering_baseinfo_rdmilestoneinfo&action=toEdit&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=750" class="thickbox">�޸�</a>
					</td>
				</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='6'>���������Ϣ</td></tr>";
		}
		return $str;

//		if($rows){
//		$i = 0;
//		$str = "";
//		foreach($rows as $key=>$val){
//			$i++;
//			$classCss = (($i%2)==0)?"tr_even":"tr_odd";
//			$str.=<<<EOT
//				<tr class="$classCss" id="tr_$val[id]">
//				<td><input type="checkbox" name="datacb" value="$val[id]" onClick="checkOne();"></td>
//				<td>$i</td>
//				<td>$val[id]</td>
//				<td><a href="#">
//					$val[templateName]
//					</a>
//				</td>
//				<td class="main_td_align_left">$val[templateDescription]</td>
//				<td>
//					<a href="?model=engineering_template_rdprjtemplate&action=toEdit&id=$val[id]&projectType=$val[projectType]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=750" class="thickbox">�޸�</a>
//				</td>
//EOT;
//		}
//	}else{
//			$str = "<tr><td colspan='12'>���������Ϣ</td></tr>";
//		}
//		return $str;
	}


//��ȡ���ҳ���С�ǰ����̱�������Ҫ��ҳ������
	function showExMilestone($rows){
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$getexmilestoneStr="";
//		echo "<pre>";
//		print_r($rows);
		if( is_array($rows) ){
			foreach($rows as $key=>$val){
				//�ų������ѡ��
				if($parentId != $val['parentId']){
					$getexmilestoneStr .=<<<EOT
					<option value='$val[numb]'>$val[milestoneName]</option>
EOT;
				}
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
		$arr = $this->listBySqlId("select_default");
//		echo "<pre>";
//		print_r($arr);
		$str = "";
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		if( is_array($arr) ){
			$str =" <option value='-1'>��ǰ����̱���</option>";
			foreach($arr as $key=>$val){
				//�ų������ѡ��
				if($id != $val['id']){
//					if($parentId == $val['parentId']){
//						$str .="<option value='$val[numb]' selected>$val[milestoneName]</option>";
//					}

					$str .="<option value='$val[numb]' selected>$val[milestoneName]</option>";
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
	 		return $this->pageBySql("select c.id,c.parentId,c.milestoneName,c.numb,c.exMilestoneName,c.frontNumb,c.createName,c.createId,c.createTime,c.milestoneDescription from oa_rd_milestone_info c where c.parentId = " . "'" . $templateId . "'");
	 	}else{
	 		return $this->pageBySqlId();
	 	}
	 }


	 /*
	  * ���ڻ�ȡǰ����̱�������б�����
	  */
		function pageExMile_d($getId) {
		//$this->echoSelect();
		//�˴����ƶ�ȡ���ݿ�����鰴IDֵ��������
		$this->asc = FALSE;
//		echo $getId;
		if (! isset ( $this->sql_arr )) {
			return $this->pageBySql ( "select c.milestoneName,c.exMilestoneName,c.createName,c.createTime,c.numb,c.frontNumb,c.parentId from " . $this->tbl_name . " c" . " where c.parentId = " . "'" . $getId . "' " );
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
//		echo "<pre>";
//		print_r($miledetail);
//		echo "************";
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
		$projectType = isset($parentId)?$parentId:null;
		$sql = "select c.id,c.parentId,c.projectType,c.milestoneName from " . "oa_rd_milestone_info c " . "where c.projectType = " . "'" . $projectType . "'";
		return $this->pageBySql($sql);
	}

	/*
	 * �ṩһ���ӿڷ�����������̱��µ������Ϣ
	 */
	function returnMilestoneInfo_d($pjType){
		$this->asc = FALSE;
		$templateDao = new model_engineering_template_rdprjtemplate();
		$getTemplate = $templateDao->pageBySql("select c.id from oa_rd_milestoneplantemplate c where c.projectType = ". "'" .$pjType . "'");
//		echo "<pre>";
//		print_r($getTemplate);
		foreach($getTemplate as $key=>$val){
			$getMilestone = $this->pageBySql("select c.id,c.milestoneName,c.numb,c.frontNumb,c.parentId,c.milestoneDescription from " . $this->tbl_name . " c" . " where c.parentid = " . "'" . $val['id'] . "' " );
		}
//		echo "<pre>";
//		print_r($getMilestone);
		return $getMilestone;

//		try{
//			//�жϴ����<option>����ֵ�Ƿ�Ϊ��
//			if( $pjType != ""){
//				//$i�����������,$x������������'numb'ֵ�ı任����Ҫ�ǹ���ǰ����̱��㡣
//				$i = 0;
//				$x = 0;
//				foreach($pjType as $val){
//
//					//����һ�����飬���ڴ洢���ص���������
//					$x = $val['frontNumb'];
////					echo $x;
////					echo "-------------------";
//					$mileInfo = array(
//						"exMilestoneName"=>$val['exMilestoneName'],
//						"projectType" => $_GET['projectType'],
//						"createName" => $val['createName'],
//						"createTime" => $val['createTime'],
//						"milestoneName" => array(
//							$i => array(
//								'name' => $val['milestoneName'],//��̱���
//	//							'numb' => $_GET['exMilestoneName'],
//								'numb' => $val['numb'],
//								'frontNumb' => $val['frontNumb'],
//							),
//
//
//						)
//					);
//
//					//�����̱��ǵ�һ����ӽ�ȥ�ģ�������Ϊ-1���˱�������ں����ı�š�
//					if( $i == 0 ){
//						$mileInfo['milestoneName'][0] = array( $val['milestoneName'],"-1" );
//					}
//				$i++;
//				echo "<pre>";
//				print_r($mileInfo);
//				}
//return $mileInfo;
//			}else{
//				return null;
//			}
//		}catch(Exception $e){
//			return $e;
//		}


	}

}
?>
