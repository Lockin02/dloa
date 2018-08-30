<?php
/**
 * @desription ��Ŀģ�����ز���
 * @param tags
 * @date 2010-10-22 ����10:11:25
 */
class model_rdproject_template_rdprjtemplate extends model_base{
	/**
	 * ���캯��
	 */
	function __construct(){
		$this->tbl_name = "oa_rd_milestoneplanTemplate";
		$this->sql_map = "rdproject/template/rdprjtemplateSql.php";
		parent::__construct();
	}
	/** ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------------------*/


	/*
	 * ģ����б���ʾ����
	 */
	function showTemplateList($rows){
		$str = "";
		$i = 0;
//		print($rows);
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$i++;
				$templateId = $val['id'];
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$Tstatus = (($val['isrelease']) == 0)?"δ����":"�ѷ���";
				$str .=<<<EOT
					<tr class="$classCss" name="tr_$val[id]">
						<td><input type="checkbox" name="datacb" value="$val[id]" onClick="checkOne()"></td>
						<td>$i</td>
						<td>
							<a href="?model=rdproject_template_rdprjtemplate&action=toviewtemplate&id=$templateId">
							$val[milestoneplanTemplateName]
							</a>
						</td>
						<td>$Tstatus</td>
						<td>$val[createName]</td>
						<td>$val[createTime]</td>
						<td class="main_td_align_left">$val[plantemplateDescription]</td>
					</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='7'>�����������</td></tr>";
		}
		return $str;
	}

		 /*
	  * �鿴��̱��ƻ�ģ�������
	  */
	 function viewtemplate_d($rows){
		$str = "";
		$i = 0;
//		echo "<pre>";
//		print_r($rows);
		$parentId = $_GET['id'];
//		echo $parentId;
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$i++;
				$classCss = (($i%2)==0)?"tr_even":"tr_odd";
				$str.=<<<EOT
				<tr class="$classCss" id="tr_$val[id]">
					<td><input type="checkbox" name="datacb" value="$val[id]" onClick="checkOne();"></td>
					<td>$i</td>
					<td>$val[milestoneName]</td>
					<td>$val[exMilestoneName]</td>
					<td>$val[parentId]</td>
					<td class="main_td_align_left">$val[milestoneDescription]</td>
					<td>
						<a href="?model=rdproject_baseinfo_rdmilestoneinfo&action=toEdit&id=$val[id]&parentId=$val[parentId]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=750" class="thickbox">
						�༭
						</a>
					</td>
				</tr>
EOT;
			}
		}else{
			$str="<tr><td colspan='7'>�����������</td></tr>";
		}
		return $str;
	 }

	/**
	 * @desription TODO
	 * @param tags
	 * @date 2010-11-2 ����05:30:10
	 */
	function returnTemplateArr () {
		$getTemplateName = $this->pageBySql("select c.id,c.milestoneplanTemplateName,c.projectType from oa_rd_milestoneplantemplate c where 1=1");
		return $getTemplateName;
	}

	/*
	 * ������̱��ƻ�����
	 */
	function returnTemplate( $arr,$code ){
		if( isset($arr) && is_array($arr) ){
			$getTemplateName = $arr;
		}else
			$getTemplateName = $this->returnTemplateArr();
		$str = "";
//		echo "<pre>";
//		print_r($arr);
		foreach($getTemplateName as $key=>$val){
			if( isset( $val['projectType'] ) && $val['projectType']==$code ){
				$str.=<<<EOT
				<option value='$val[id]' selected>$val[milestoneplanTemplateName]</option>
EOT;
			}else{
				$str.=<<<EOT
				<option value='$val[id]' >$val[milestoneplanTemplateName]</option>
EOT;
			}
		}
		return $str;
	}

	/*
	 * ����һ���б������ǡ���Ŀ��������̱��ƻ�ģ�塱�Ķ�Ӧ��ϵ
	 * һ����Ŀ����ֻ�ܶ�Ӧһ����̱��ƻ�ģ��
	 * һ����̱��ƻ�ģ����Զ�Ӧ�����Ŀ����
	 */
	function showTypeAndTemplate( $typeArr ){
		$arr = $this->returnTemplateArr(  );
//		print_R($typeArr);
		$str = "";
		$i = 0;
		foreach($typeArr as $key => $val){
			$i++;
			$str.=<<<EOT
			<tr>
				<td>$i</td>
				<td>$val[text]</td>
				<td>
				<select>
					<option>��ѡ��</option>
EOT;
			$str.= $this->returnTemplate($arr,$val['dataCode']);
			$str.=<<<EOT
				</select>
				</td>
			</tr>
EOT;
		}
		return $str;
	}



	/** ------------------------------����Ϊ�ӿڷ���������Ϊ����ģ�����------------------------------------------*/

	/*
	 * ��ȡ�����ҳ�б�����
	 */
	 function templatePage_d(){
//	 	$this->echoSelect();
		$this->asc = FALSE;
		if(!isset( $this->sql_arr )){
	 		return $this->pageBySql("select c.id,c.milestoneNumb,c.milestoneplanTemplateName,c.isrelease,c.plantemplateDescription,c.createId,c.createTime,c.createName from oa_rd_milestoneplantemplate c where 1=1");
	 	}else{
	 		return $this->pageBySqlId();
	 	}
	 }



	 /*
	  * ��̱��ƻ�ģ��ı��淽��
	  */
	 function addTemplate_d($tempObj){
		try{
			$this->start_d();
//			print($tempObj);
			$addresult = parent::add_d($tempObj,true);

			//������ӵĹ�����ϵ
			$milestoneDao = new model_rdproject_baseinfo_rdmilestoneinfo();
//			$sql = "insert into oa_rd_milestone_info ('','milestoneName','','','','','','','','') values('','����','','','','','','','','') where 1=1";
//			$milestoneDao->query($sql);
			$this->commit_d();
			return $addresult;
		}catch(Exception $e){
			throw $e;
			$this->rollBack();
			return null;
		}
	 }


	/*
	 * ����Ϊģ��ı��淽��
	 */
	 function setAsTemplate_d($objTemplate){
	 	try{
			$this->start_d();
			$setTemp = parent::add_d($objTemplate,true);

			$this->commit_d();
			return $setTemp;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		throw null;
	 	}
	 }
}
?>
