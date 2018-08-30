<?php
/**
 * @desription 项目模板的相关操作
 * @param tags
 * @date 2010-10-22 上午10:11:25
 */
class model_rdproject_template_rdprjtemplate extends model_base{
	/**
	 * 构造函数
	 */
	function __construct(){
		$this->tbl_name = "oa_rd_milestoneplanTemplate";
		$this->sql_map = "rdproject/template/rdprjtemplateSql.php";
		parent::__construct();
	}
	/** ------------------------------以下为页面模板显示调用方法------------------------------------------*/


	/*
	 * 模板的列表显示方法
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
				$Tstatus = (($val['isrelease']) == 0)?"未发布":"已发布";
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
			$str = "<tr><td colspan='7'>暂无相关内容</td></tr>";
		}
		return $str;
	}

		 /*
	  * 查看里程碑计划模板的内容
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
						编辑
						</a>
					</td>
				</tr>
EOT;
			}
		}else{
			$str="<tr><td colspan='7'>暂无相关数据</td></tr>";
		}
		return $str;
	 }

	/**
	 * @desription TODO
	 * @param tags
	 * @date 2010-11-2 下午05:30:10
	 */
	function returnTemplateArr () {
		$getTemplateName = $this->pageBySql("select c.id,c.milestoneplanTemplateName,c.projectType from oa_rd_milestoneplantemplate c where 1=1");
		return $getTemplateName;
	}

	/*
	 * 返回里程碑计划名称
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
	 * 返回一个列表，内容是“项目类型与里程碑计划模板”的对应关系
	 * 一个项目类型只能对应一套里程碑计划模板
	 * 一套里程碑计划模板可以对应多个项目类型
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
					<option>请选择</option>
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



	/** ------------------------------以下为接口方法，可以为其他模块调用------------------------------------------*/

	/*
	 * 获取对象分页列表数组
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
	  * 里程碑计划模板的保存方法
	  */
	 function addTemplate_d($tempObj){
		try{
			$this->start_d();
//			print($tempObj);
			$addresult = parent::add_d($tempObj,true);

			//外键链接的关联关系
			$milestoneDao = new model_rdproject_baseinfo_rdmilestoneinfo();
//			$sql = "insert into oa_rd_milestone_info ('','milestoneName','','','','','','','','') values('','立项','','','','','','','','') where 1=1";
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
	 * 设置为模板的保存方法
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
