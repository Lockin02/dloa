<?php
/**
 * @description: 里程碑点Model
 * @date 2010-9-18 上午11:23:53
 * @author oyzx
 * @version V1.0
 */
class model_engineering_milestone_esmmilespoint extends model_base {

	public $statusDao;

	/**
	 * @desription 构造函数
	 * @date 2010-9-18 上午11:23:53
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_milestone_point";
		$this->sql_map = "engineering/milestone/esmmilespointSql.php";
		parent::__construct ();

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			array(
				"statusEName" => "wite",
				"statusCName" => "待执行",
				"key" => "1"
			),
			array(
				"statusEName" => "execute",
				"statusCName" => "执行中",
				"key" => "2"
			),
			array(
				"statusEName" => "end",
				"statusCName" => "完成",
				"key" => "3"
			),
			array(
				"statusEName" => "locking",
				"statusCName" => "锁定",
				"key" => "4"
			),
			array(
				"statusEName" => "undo",
				"statusCName" => "不可用",
				"key" => "5"
			)
		);
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

		/**
		 * @desription 里程碑修改显示模板
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmPointU_s ( $arr,$pjId ) {
			$str ='';
			$i = 0;
			foreach( $arr as $key =>$val ){
				++$i;
				$optionStr = $this->rmMilespointSelect_d ( $pjId,$val['frontId'],$val['id'] );
				$str.=<<<EOT
				<tr class="tr_even">
				<td>
					$val[pointName]
				</td>
				<td>
					 <input type="text" class="txt" onfocus="WdatePicker()" id="planBeginDate[$key]" name="rdmilespoint[$key][planBeginDate]" value="$val[planBeginDate]" readonly />
				</td>
				<td>
					<input type="text" class="txt" onfocus="WdatePicker()" id="planEndDate[$key]" name="rdmilespoint[$key][planEndDate]" value="$val[planEndDate]" readonly />
				</td>
				<td>
					<select name="rdmilespoint[$key][frontCode]">
						$optionStr
					</select>
					<input type="hidden" name="rdmilespoint[$key][id]" value="$val[id]" >
					<input type="hidden" id="mytype$key" class="mytype" name="rdmilespoint[$key][mytype]" value="0" >
				</td>
			</tr>
EOT;
			}
			return $str;
		}

		/**
		 * @desription 里程碑修改显示模板
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmPointU2_s ( $arr,$pjId ) {
			$str ='';
			$i = 0;
			$pjDao = new model_engineering_project_engineering();
			$pjArr = $pjDao->rpArrById_d($pjId);
			$milDao = new model_engineering_milestone_rdmilestone();
			$milArr = $milDao->rmArrBypjId_d($pjId);
			$pjId = $pjArr['0']['id'];
			$pjCode = $pjArr['0']['projectCode'];
			$pjName = $pjArr['0']['projectName'];
			$milId = $milArr['0']['id'];
			$milCode = $milArr['0']['milestoneCode'];
			foreach( $arr as $key =>$val ){
				++$i;
				$keyA = $key ."a";
				$optionStr = $this->rmMilespointSelect_d ( $pjId );
				$str.=<<<EOT
				<tr class="tr_even">
				<td>
					$val[milestoneName]
				</td>
				<td>
					 <input type="text" class="txt" onfocus="WdatePicker()" id="planBeginDate[$keyA]" name="rdmilespoint[$keyA][planBeginDate]" value="" readonly />
				</td>
				<td>
					<input type="text" class="txt" onfocus="WdatePicker()" id="planEndDate[$keyA]" name="rdmilespoint[$keyA][planEndDate]" value="" readonly />
				</td>
				<td>
					<select name="rdmilespoint[$keyA][frontCode]">
						$optionStr
					</select>
					<input type="hidden" name="rdmilespoint[$keyA][id]" value="" >
					<input type="hidden" name="rdmilespoint[$keyA][pointName]" value="$val[milestoneName]" >
					<input type="hidden" name="rdmilespoint[$keyA][code]" value="$val[numb]" >
					<input type="hidden" name="rdmilespoint[$keyA][projectId]" value="$pjId" >
					<input type="hidden" name="rdmilespoint[$keyA][projectCode]" value="$pjCode" >
					<input type="hidden" name="rdmilespoint[$keyA][projectName]" value="$pjName" >
					<input type="hidden" name="rdmilespoint[$keyA][milestoneId]" value="$milId" >
					<input type="hidden" name="rdmilespoint[$keyA][milestoneCode]" value="$milCode" >
					<input type="hidden" id="mytype$keyA" class="mytype" name="rdmilespoint[$keyA][mytype]" value="1" >
				</td>
			</tr>
EOT;
			}
			return $str;
		}

		/**
		 * @desription 里程碑显示模板
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmListRead_s ( $arr ) {
			$str ='';
			$i = 0;
			foreach( $arr as $key =>$val ){
				++$i;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str.=<<<EOT
				<tr class="$classCss">
				<td>$i</td>
				<td>
					$val[pointName]
				</td>
				<td>
					$val[planBeginDate]
				</td>
				<td>
					$val[planEndDate]
				</td>
				<td>
					$val[realBeginDate]
				</td>
				<td>
					$val[realEndDate]
				</td>
				<td>
					$val[frontName]
				</td>
				<td>
					$val[effortRate]
				</td>
				<td>
					$val[warpRate]
				</td>
				<td>
					$val[statusCN]
				</td>
			</tr>
EOT;
			}
			return $str;
		}

		/**
		 * @desription 里程碑列表（点）
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmList_s ( $arr ) {
			$str ='';
			$i = 0;
			foreach( $arr as $key =>$val ){
				++$i;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str.=<<<EOT
				<tr class="$classCss">
				<td>$i</td>
				<td>
					$val[pointName]
				</td>
				<td>
					$val[planBeginDate]
				</td>
				<td>
					$val[planEndDate]
				</td>
				<td>
					$val[frontName]
				</td>
				<td>
					<a href="?model=engineering_milestone_rdmilespoint&action=toEdit&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=550" class="thickbox">修改</a>
				</td>
			</tr>
EOT;
			}
			return $str;
		}

		/**
		 * @desription 里程碑中心（点）
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmListCenter_s ( $arr ) {
			$str ='';
			$i = 0;
			foreach( $arr as $key =>$val ){
				++$i;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str.=<<<EOT
				<tr class="$classCss">
				<td>$i</td>
				<td>
					$val[pointName]
				</td>
				<td>
					$val[planBeginDate]
				</td>
				<td>
					$val[planEndDate]
				</td>
				<td>
					$val[effortRate]
				</td>
				<td>
					$val[warpRate]
				</td>
				<td>
					$val[statusCN]
				</td>
			</tr>
EOT;
			}
			return $str;
		}


	/***************************************************************************************************
	 * ------------------------------以下为数据字典接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 获取里程碑点select
	 * @param tags $pjId 项目Id
	 * @param tags $pointId 默认选中
	 * @param tags $tid 不可以选中自己
	 * @param tags $isFirst 是否有 （首个里程碑点）
	 * @date 2010-10-5 上午09:53:26
	 */
	function rmMilespointSelect_d ( $pjId,$pointId='',$tid='',$isFirst=true ) {
//		echo $isFirst;
		$sDao = $this->statusDao;
		$this->searchArr = array(
			"pjId" => $pjId,
			"statusArr" => $sDao->statusEtoK ( "wite" ).",".$sDao->statusEtoK ( "execute" )
		);
		$this->asc = false;
		$arr = $this->listBySqlId("select_readSelect");
		if($isFirst){
			$str =" <option value='-1'>首个里程碑点</option>";
		}else{
			$str =" ";
		}
		if( is_array($arr) ){
//			echo "<pre>";
//			print_r($arr);
			foreach($arr as $key=>$val){
				if($tid!=$val['id']){
					if( $pointId==$val['id'] ){
						$str.=" <option value='$val[code]' selected>$val[pointName]</option>";
					}else{
						$str.=" <option value='$val[code]'>$val[pointName]</option>";
					}
				}
			}
		}else{
			$str =" <option value=''>没有数据</option>";
		}
		return $str;
	}

	/**
	 * @desription 获取里程碑点select(id)
	 * @param tags $pjId 项目Id
	 * @param tags $pointId 默认选中
	 * @param tags $tid 不可以选中自己
	 * @param tags $isFirst 是否有 （首个里程碑点）
	 * @date 2010-10-5 上午09:53:26
	 */
	function rmMilespointSelectId_d ( $pjId,$pointId='',$tid='',$isFirst=true ) {
//		echo $isFirst;
		$sDao = $this->statusDao;
		$this->searchArr = array(
			"pjId" => $pjId,
			"statusArr" => $sDao->statusEtoK ( "wite" ).",".$sDao->statusEtoK ( "execute" )
		);
		$this->asc = false;
		$arr = $this->listBySqlId("select_readSelect");
		if($isFirst){
			$str =" <option value='-1'>首个里程碑点</option>";
		}else{
			$str =" ";
		}
		if( is_array($arr) ){
//			echo "<pre>";
//			print_r($arr);
			foreach($arr as $key=>$val){
				if($tid!=$val['id']){
					if( $pointId==$val['id'] ){
						$str.=" <option value='$val[id]' selected>$val[pointName]</option>";
					}else{
						$str.=" <option value='$val[id]'>$val[pointName]</option>";
					}
				}
			}
		}else{
			$str =" <option value=''>没有数据</option>";
		}
		return $str;
	}

	/**
	 * @desription 根据里程碑点Id完成里程碑
	 * @param tags
	 * @date 2010-10-6 下午05:47:17
	 */
	function rmMilespointEnd_d ($projectId,$stoneId,$date) {
		try {
			$this->start_d ();
			$this->pk="id";
			if( $this->findCount(" id='$stoneId' and status!='3' ") ){
				$sDao = $this->statusDao;
				$updateArr = array(
					"id"=>$stoneId,
					"status"=>$sDao->statusEtoK ( "end" ),
					"realEndDate"=>$date

				);
				$this->edit_d($updateArr,true);
				$arr = $this->findSql(" select code from oa_rd_milestone_point where id='$stoneId' ");
				$object = array(
					"status"=>$sDao->statusEtoK ( "execute" ),
					"realBeginDate"=>date("Y-m-d"),
				);
				$this->update ( " frontCode='".$arr['0']['code']."' ", $object );
			}

			$pointSql = "select (a.aa/b.bb*100) wanchenglv from " .
					"( select count(*) as aa from oa_rd_milestone_point where projectId='".$projectId."' and  status='3'  ) a," .
					"( select count(*) as bb from oa_rd_milestone_point where projectId='".$projectId."' ) b";
			$countPoint = $this->findSql($pointSql);
			//TODO: 更新项目的完成率
			$pjDao = new model_engineering_project_engineering();
			$updateSql = " update oa_rd_project set effortRate='".$countPoint['0']['wanchenglv']."' where id='".$projectId."' ";
			$pjDao->query($updateSql);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			echo "异常*********************";
			$this->rollBack ();
			throw $e;
			return null;
		}
	}

	/**
	 * @desription 根据项目的数据获取历程的关系
	 * @param tags
	 * @date 2010-10-7 下午03:41:10
	 */
	function rmPjMiles_d ($rows) {
		if( is_array($rows) ){
			$sDao = $this->statusDao;
			foreach( $rows as $key => $val ){
				$rows[$key]['newPointName']="无";
				$rows[$key]['newPointRealBeginDate']="无";
				$rows[$key]['nextPointName']="无";
				$rows[$key]['nextPointPlanBeginDate']="无";
				$this->searchArr = array(
					"pjId" => $val['id']
				);
				$arr = $this->listBySqlId("select_readSelect");
				$thisCode = "无";
				if( is_array($arr) ){
					foreach($arr as $arrKey => $arrVal ){
						if( $arrVal['status'] == $sDao->statusEtoK ( "execute" ) ){
							$rows[$key]['newPointName']=$arrVal['pointName'];
							$rows[$key]['newPointRealBeginDate']=$arrVal['realBeginDate'];
							$thisCode = $arrVal['code'];
						}
					}

					$this->searchArr = array(
						"frontCode" => $thisCode,
						"pjId" => $val['id']
					);
					$arrNext = $this->listBySqlId("select_readSelect");
					if( is_array($arrNext) ){
						foreach($arrNext as $arrNextKey => $arrNextVal ){
								$rows[$key]['nextPointName']=$arrNextVal['pointName'];
								$rows[$key]['nextPointPlanBeginDate']=$arrNextVal['realBeginDate'];
						}
					}
				}
			}
		}
			return $rows;
	}


	/*
	 * 编辑里程碑点时，获取里程碑点相关信息
	 */
	function getEditMileInfo_d($id){
		//先找出里程碑点的信息
		return $miledetail = $this->get_d($id);

	}

	/*
	 * 编辑里程碑点的保存方法
	 */
	function editpoint_d($objectinfo){
		try{
			$this->start_d();

			if( isset($objectinfo['id']) ){
//				print_r($objectinfo);
				$id = parent::edit_d($objectinfo,true);

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

	/**
	 * @desription 变更方法
	 * @param tags
	 * @date 2010-10-13 下午03:57:49
	 */
	function rmChange_d ($arr) {
		try {
			$this->start_d ();
			foreach( $arr as $key => $val ){
				$idEq = (!isset($val['id'])||$val['id']=="")?false:true;//是否是在里程碑里（是/否）
				$mytype = ($val['mytype']=="0")?true:false; //是否是新里程碑（是/否）
				if( $mytype ){
					if($idEq){
						$this->editpoint_d($val);
					}else{
						$pointArr = array();
						$pointArr['pointName'] = $val['pointName'];
						$pointArr['projectId'] = $val['projectId'];
						$pointArr['projectCode'] = $val['projectCode'];
						$pointArr['projectName'] = $val['projectName'];
						$pointArr['milestoneId'] = $val['milestoneId'];
						$pointArr['milestoneCode'] = $val['milestoneCode'];
						$pointArr['code'] = $val['code'];
						$pointArr['frontCode'] = $val['frontCode'];
						$pointArr['planBeginDate'] = $val['planBeginDate'];
						$pointArr['planEndDate'] = $val['planEndDate'];
						if( $val['frontCode']=="-1" ){
							$pointArr['status'] = $milespointDao->statusDao->statusEtoK ( "execute" );
							$pointArr['realBeginDate'] = date("Y-m-d");
						}
						$this->add_d($pointArr,true);
					}
				}
				else{
					if($idEq){
						$arrDel = array(
							"id"=>$val['id']
						);
						$this->delete($arrDel);
					}
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return false;
		}
	}

	/*
	 * @desription 操作记录
	 * @param $object 操作对象
	 */
	private function operationLog($object) {
		$operation = array ();
		$operation ['objTable'] = $this->tbl_name;
		$operation ['objId'] = $object ['id'];
		$operation ['operateType'] = $object ['operType_']; //操作类型作为对象的属性传过来，加上下划线避免与对象业务属性冲突
		if (isset ( $this->operLog )) {
			$operation ['operateLog'] = $this->operLog;
		}
		$operationDao = new model_log_operation_operation ();
		$operationDao->add_d ( $operation );
	}

	/*
	 * @desription 变更记录
	 * @param $object 操作对象
	 */
	private function changeLog($object) {
		$change = array ();
		$change ['objTable'] = $this->service->tbl_name;
		$change ['objId'] = $object ['id'];
		if (isset ( $object ['changeReason'] )) {
			$change ['changeReason'] = $object ['changeReason']; //对象变更原因属性
		}
		if (isset ( $this->operLog )) {
			$change ['changeLog'] = $this->operLog;
		}
		$changeDao = new model_log_change_change ();
		$changeDao->add_d ( $change );
	}

	/**
	 * @desription 根据里程碑 获取数据
	 * @param tags
	 * @date 2010-10-12 下午08:31:46
	 */
	function rmArrList_d ($seachArr) {
		$this->searchArr = $seachArr;
		$this->asc = false;
		$pointArr = $this->listBySqlId('select_readCenter');
		foreach($pointArr as $key => $val){
			if($val['frontCode']== "-1" ){
				$pointArr[$key]['frontName']="无";
				$pointArr[$key]['frontId']="";
			}
			else{
				foreach($pointArr as $keyA => $valA){
					if( $val['frontCode'] == $valA['code'] ){
						$pointArr[$key]['frontName']=$valA['pointName'];
						$pointArr[$key]['frontId']=$valA['id'];
					}

					if( !isset($pointArr[$key]['frontName']) ||$pointArr[$key]['frontName']=="" ){
						$pointArr[$key]['frontName']="";
						$pointArr[$key]['frontId']="";
					}
				}
			}

			if( isset( $pointArr[$key]['planBeginDate']) &&$pointArr[$key]['planBeginDate']!="" &&
				isset( $pointArr[$key]['planEndDate']) && $pointArr[$key]['planEndDate']!="" &&
				isset( $pointArr[$key]['realBeginDate']) &&$pointArr[$key]['realBeginDate']!="" &&
				isset( $pointArr[$key]['realEndDate']) &&$pointArr[$key]['realEndDate']!="" ){

				$warpRate= (strtotime($pointArr[$key]['realEndDate']) -strtotime($pointArr[$key]['realBeginDate']))/(strtotime($pointArr[$key]['planEndDate']) -strtotime($pointArr[$key]['planBeginDate']));
				if($warpRate>1){
					$warpRate = ($warpRate-1)*100 ."%";
				}else{
					$warpRate = "0.00%";
				}
				$pointArr[$key]['warpRate'] = $warpRate;
			}else{
				$pointArr[$key]['warpRate']= "0.00%";
			}

			if( isset($pointArr[$key]['effortRate'])  ){
				$pointArr[$key]['effortRate'] = $pointArr[$key]['effortRate'] ."%";
			}else{
				$pointArr[$key]['effortRate'] = "0.00%";
			}

			$pointArr[$key]['statusCN'] = $this->statusDao->statusKtoC( $pointArr[$key]['status'] );

//			$sql = "select (case b.bb where 0 then '0,00%' else CONCAT( a.aa/b.bb*100 ,'%') ) wanchenglv from " .
//					"( select count(*) as aa from oa_rd_project_plan where pointId='".$pointArr[$key]['id']."' and  status='JHWC'  ) a," .
//					"( select count(*) as bb from oa_rd_project_plan where pointId='".$pointArr[$key]['id']."' ) b";
//					echo $sql."<br>";
			//$this->findSql($sql);
		}
		return $pointArr;
	}

	/**
	 * @desription 里程碑点完成率修改
	 * @param tags
	 * @date 2010-10-13 上午11:03:54
	 */
	function rmUpdateEffort_d ($val,$id) {
		$objectinfo = array(
			"id"=>$id,
			"effortRate"=>$val
		);
		if( parent::edit_d($objectinfo,true) ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * @desription 根据已有的里程碑点获取数据
	 * @param tags
	 * @date 2010-10-14 下午04:24:53
	 */
	function rmBasicArr_d ($arr,$pjId ) {
		$pjDao = new model_engineering_project_engineering();
		$pjArr = $pjDao->rpArrById_d($pjId);
		$milestoneinfoDao = new model_engineering_baseinfo_rdmilestoneinfo();
		$milArr = $milestoneinfoDao->returnMilestoneInfo_d( $pjArr['0']['projectType'] );
		$rArr = array();
		$i = 0;
		foreach( $milArr as $key => $val ){
			$a=0;
			foreach($arr as $keyM => $valM){
				if($valM['code']==$val['numb']  ){
					++$a;
				}
			}
			if(!$a||$a==0){
				$rArr[$i]=$val;
				++$i;
			}
		}
		return $rArr;
	}

}
?>
