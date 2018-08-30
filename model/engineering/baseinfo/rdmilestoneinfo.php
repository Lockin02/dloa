<?php
/**
 * @description: 项目里程碑-相关信息
 * @date 2010-9-26 下午03:09:08
 */
class model_engineering_baseinfo_rdmilestoneinfo extends model_base {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-9-26 下午03:10:13
	 */
	function __construct () {
		$this->tbl_name = "oa_rd_milestone_info";
		$this->sql_map = "engineering/baseinfo/rdmilestoneinfoSql.php";
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
						<a href="?model=engineering_baseinfo_rdmilestoneinfo&action=toEdit&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=750" class="thickbox">修改</a>
					</td>
				</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='6'>暂无相关信息</td></tr>";
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
//					<a href="?model=engineering_template_rdprjtemplate&action=toEdit&id=$val[id]&projectType=$val[projectType]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=750" class="thickbox">修改</a>
//				</td>
//EOT;
//		}
//	}else{
//			$str = "<tr><td colspan='12'>暂无相关信息</td></tr>";
//		}
//		return $str;
	}


//获取添加页面中“前置里程碑”所需要的页面内容
	function showExMilestone($rows){
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		$getexmilestoneStr="";
//		echo "<pre>";
//		print_r($rows);
		if( is_array($rows) ){
			foreach($rows as $key=>$val){
				//排除自身的选项
				if($parentId != $val['parentId']){
					$getexmilestoneStr .=<<<EOT
					<option value='$val[numb]'>$val[milestoneName]</option>
EOT;
				}
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
		$arr = $this->listBySqlId("select_default");
//		echo "<pre>";
//		print_r($arr);
		$str = "";
		$parentId = isset($_GET['parentId'])?$_GET['parentId']:null;
		if( is_array($arr) ){
			$str =" <option value='-1'>无前置里程碑点</option>";
			foreach($arr as $key=>$val){
				//排除自身的选项
				if($id != $val['id']){
//					if($parentId == $val['parentId']){
//						$str .="<option value='$val[numb]' selected>$val[milestoneName]</option>";
//					}

					$str .="<option value='$val[numb]' selected>$val[milestoneName]</option>";
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
	 		return $this->pageBySql("select c.id,c.parentId,c.milestoneName,c.numb,c.exMilestoneName,c.frontNumb,c.createName,c.createId,c.createTime,c.milestoneDescription from oa_rd_milestone_info c where c.parentId = " . "'" . $templateId . "'");
	 	}else{
	 		return $this->pageBySqlId();
	 	}
	 }


	 /*
	  * 用于获取前置里程碑所需的列表内容
	  */
		function pageExMile_d($getId) {
		//$this->echoSelect();
		//此处控制读取数据库的数组按ID值升序排列
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
	 * 编辑里程碑点时，获取里程碑点相关信息
	 */
	function getEditMilestoneInfo_d($id){
		//先找出里程碑点的信息
		$miledetail = $this->get_d($id);
//		echo "<pre>";
//		print_r($miledetail);
//		echo "************";
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
		$projectType = isset($parentId)?$parentId:null;
		$sql = "select c.id,c.parentId,c.projectType,c.milestoneName from " . "oa_rd_milestone_info c " . "where c.projectType = " . "'" . $projectType . "'";
		return $this->pageBySql($sql);
	}

	/*
	 * 提供一个接口方法，返回里程碑下的相关信息
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
//			//判断传入的<option>下拉值是否为空
//			if( $pjType != ""){
//				//$i用于数组遍历,$x用于数组里面'numb'值的变换，主要是关联前置里程碑点。
//				$i = 0;
//				$x = 0;
//				foreach($pjType as $val){
//
//					//定义一个数组，用于存储返回的数组内容
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
//								'name' => $val['milestoneName'],//里程碑点
//	//							'numb' => $_GET['exMilestoneName'],
//								'numb' => $val['numb'],
//								'frontNumb' => $val['frontNumb'],
//							),
//
//
//						)
//					);
//
//					//如果里程碑是第一个添加进去的，则标记其为-1，此标号区别于后续的标号。
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
