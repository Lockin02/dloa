<?php
header("Content-type: text/html; charset=gb2312");
/**
 * @description: 项目里程碑-相关信息
 * @date 2010-9-26 下午03:09:08
 */
class model_rdproject_baseinfo_rdmilestoneinfo extends model_base {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-9-26 下午03:10:13
	 */
	function __construct () {
		$this->tbl_name = "oa_rd_milestone_info";
		$this->sql_map = "rdproject/baseinfo/rdmilestoneinfoSql.php";
		parent::__construct();
	}


	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/
	/**
	 * @desription 显示里程碑相关信息
	 * @param tags
	 * @date 2010-9-26 下午04:06:19
	 */
	function showprojectlist ($rows) {
		//以下代码是对应“类型-里程碑点”。2010年10月26日修改。27日改回。
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
						<a href="?model=rdproject_baseinfo_rdmilestoneinfo&action=toEdit&id=$val[id]&projectType=$val[projectType]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=700" class="thickbox">修改</a>
					</td>
				</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='8'>暂无相关信息</td></tr>";
		}
		return $str;
	}


//获取添加页面中“前置里程碑”所需要的页面内容
	function showExMilestone($rows){
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$projectType = isset( $_GET['projectType'] )?$_GET['projectType']:null;
		$getexmilestoneStr="";
		if( is_array($rows) ){
			foreach($rows as $key=>$val){
				//排除自身的选项
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

	//获取添加页面中“前置里程碑”所需要的页面内容--在添加页面选择项目类型时。
	function showExMilestoneList($rows){
		$getexmilestoneStr="";
		$getexmilestoneStr.="<option value='-1'>无前置里程碑点</option>";
		if( is_array($rows) ){
			foreach($rows as $key=>$val){
						$getexmilestoneStr .=<<<EOT
						<option value='$val[numb]'>$val[milestoneName]</option>
EOT;
			}
		}else{
			$getexmilestoneStr ="<option value='-1'>无前置里程碑点</option>";
		}
		return $getexmilestoneStr;
	}



	/*
	 * 获取里程碑点select
	 */
	function milestoneSelect_d($id){
		$this->searchArr = array('projectType'=>$_GET['projectType']);
		$arr = $this->listBySqlId("select_default");
		$rows=$this->get_d($id);
		$str = "";
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		if($rows['frontNumb']=="-1"){
			$str.=" <option value='-1'>无前置里程碑点</option>";
		}else{
			$frontRows=$this->findAll(array('numb'=>$rows[frontNumb]));
			$frontNumb=$frontRows[0][milestoneName];
			$str.="<option value='$rows[frontNumb]'>$frontNumb</option> <option value='-1'>无前置里程碑点</option>";
		}
		if( is_array($arr) ){
			foreach($arr as $key=>$val){
				//排除自身的选项
				if($id != $val['id']&&$rows['frontNumb']!=$val['numb']){
					$str .="<option value='$val[numb]'>$val[milestoneName]</option>";
				}
			}
		}
		else{
			$str =" <option value=''>暂无相关数据</option>";
		}

		return $str;
	}


	/**
	 * @desription 前置模板
	 * @param tags
	 * @date 2010-10-21 下午02:30:06
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
			return "<option value='-1'>无前置模板</option>";
		}
		return $getExTemplateStr;
	}


	/***************************************************************************************************
	 * ------------------------------以下为接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/

	/*
	 * 导出到Excel所需要的数据
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
	 * 添加里程碑信息到数据库
	 */
	 function addmilestone_d($objmilestone){
		try{
			$this->start_d();
			$frontRows=$this->findAll(array('numb'=>$objmilestone['frontNumb']));
			$objmilestone['exMilestoneName']=$frontRows['0'][milestoneName];

			$newId = parent::add_d($objmilestone,true);

			//以下两行代码的作用是重写add_d()方法，直接为true,不需要附件，只是将add_d()里面的增加的核心代码抽取出来实现。
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
	  * 查看模板的内容，模板下有里程碑点
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
	  * 用于获取前置里程碑所需的列表内容
	  */
		function pageExMile_d($getId) {
		//此处控制读取数据库的数组按ID值升序排列
		$this->asc = FALSE;
		if (! isset ( $this->sql_arr )) {
			return $this->pageBySql ( "select c.milestoneName,c.exMilestoneName,c.createName,c.createTime,c.numb,c.frontNumb,c.parentId,c.projectType from " . $this->tbl_name . " c" . " where c.parentId = " . "'" . $getId . "' " );
		} else {
			//var_dump($this->pageBySqlId ());
			return $this->pageBySqlId ();
		}

	}

	/*
	 * 编辑里程碑点时，获取里程碑点相关信息
	 */
	function getEditMilestoneInfo_d($id){
		//先找出里程碑点的信息
		$miledetail = $this->get_d($id);
		return $miledetail;

	}

	/*
	 * 编辑里程碑的保存方法
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
	 * 通过编码获取前置里程碑名称
	 */
	function getexMileName($projectType){
		$projectType = isset($projectType)?$projectType:null;
		$sql = "select c.id,c.parentId,c.projectType,c.milestoneName from " . "oa_rd_milestone_info c " . "where c.projectType = " . "'" . $projectType . "'";
		return $this->pageBySql($sql);
	}

	/*
	 * 提供一个接口方法，返回里程碑下的相关信息
	 */
	function returnMilestoneInfo_d($pjType){
		$condiction = array('projectType' => $pjType);
		$mileName = $this->findAll($condiction);
		return $mileName;
	}

	/**根据项目类型，获取里程碑信息
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
