<?php
/**
 * @description: 项目普通计划Model
 * @date 2010-9-18 上午11:39:04
 * @author oyzx
 * @version V1.0
 */
class model_engineering_plan_rdplan extends model_treeNode {

	/**
	 * @desription 构造函数
	 * @date 2010-9-18 上午11:23:53
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_project_plan";
		$this->sql_map = "engineering/plan/rdplanSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

	/**
	 *  计划资源使用透视图
	 */
	function planTable($rows){
//		print_r($rows);
		$str =<<<EOT
			<table class="main_table">
				<tr class="main_tr_header">
					<th>
						序号
					</th>
					<th>
						计划名称
					</th>
					<th>
						估计工作量
					</th>
					<th>
						已投入工作量
					</th>
					<th>
						完成率
					</th>
				</tr>
EOT;
		if($rows){
			$i = 0;
			foreach($rows as $key=> $val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$val[planName]
						</td>
						<td>
							$val[appraiseWorkload]
						</td>
						<td>
							$val[putWorkload]
						</td>
						<td>
							$val[effortRate] %
						</td>
					</tr>
EOT;
			}
		}else{
			$str.='<tr><td colspan="20">暂无相关数据</td></tr>';
		}
		$str.='</table>';
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------以下为接口方法,可以为其他模块所调用-------------------------------- *
	 **************************************************************************************************/
	/**
	 *  重写add
	 */
	public function add_d($node) {
//		$node['status'] = 'JHJXZ';
		$node['realBeginDate'] = $node['planBeginDate'];
		$purviewDao = new model_engineering_plan_rdpurview();
		try {
			$this->start_d ();
			//调用树工具产生左右节点id
			$node = $this->createNode ( $node );

			//插入计划
			$newId = $this->addBase_d ( $node, true );

			//处理附件名称
			$this->updateObjWithFile($newId);

			//处理完成率
			$this->recEff($newId);

			//添加权限
			$purviewDao->addGroup($node['projectId'],$newId);

//			$this->rollBack ();
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 新建计划--通过模板导入
	 */
	function addByImport($node){
		$node['realBeginDate'] = $node['planBeginDate'];
		$purviewDao = new model_engineering_plan_rdpurview();
		$templatePlanDao = new model_engineering_template_rdtplan();

		try {
			$this->start_d ();
			//调用树工具产生左右节点id
			$node = $this->createNode ( $node );

			//插入计划
			$newId = $this->addBase_d ( $node, true );

			//处理附件名称
			$this->updateObjWithFile($newId);

			//处理完成率
			$this->recEff($newId);

			//添加权限
			$purviewDao->addGroup($node['projectId'],$newId);

			//导入模板内容
			$templatePlanDao->templateImport($node['templateId'],$node['projectId'],$node['projectName'],$newId,$node['planName']);

//			$this->rollBack ();
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 新建计划是获取上级计划和项目信息
	 */
	function rpGetProjectInfo($pjId,$pnId){
		if(empty($pnId)){
			$returnArr['0']['parentId'] = "-1";
			$returnArr['0']['parentName'] = "里程碑计划";
		}else{
			$planArr = $this->rpArrById_d($pnId);
			$returnArr['0']['parentId'] = $planArr['0']['id'];
			$returnArr['0']['parentName'] = $planArr['0']['planName'];
		}
		$pjDao = new model_engineering_project_engineering();
		$pjArr = $pjDao->rpArrById_d($pjId);
		$returnArr['0']['projectId'] = $pjArr['0']['id'];
		$returnArr['0']['projectCode'] = $pjArr['0']['projectCode'];
		$returnArr['0']['projectName'] = $pjArr['0']['projectName'];
		return $returnArr;
	}

	/**
	 * 修改计划时获取相关计划信息
	 */
	function getPlanInfoByEdit($pnId){
		$rows = $this->get_d($pnId);
		$rows = $this->filterField('字段限制',$rows);
		if($rows['parentId'] == -1 ){
			$rows['parentName'] = "里程碑计划";
		}else{
			$trows = $this->get_d($rows['parentId']);
			$rows['parentName'] = $trows['planName'];
		}
		return $rows;
	}

	/**
	 * @desription 通过计划Id获取数据
	 * @param tags
	 * @return return_type
	 * @date 2010-9-25 下午07:02:03
	 */
	function rpArrById_d ($pnId) {
		$this->searchArr['id'] = $pnId;
		$rows = $this->listBySqlId('select_readAll');
		return $rows;
	}

	/**
	 * 重写edit_d函数
	 */
	function edit_d($object){
		$object = $this->addUpdateInfo ( $object );
		return $this->updateById ( $object );
	}

	/**
	 * 检验计划是否可删除
	 */
	function canDelete($id){
		$this->searchArr['id'] = $id;
		$rows = $this->listBySqlId('canDelete');

		if($rows['0']['leaf']){
			$taskDao = new model_engineering_task_rdtask();
			$isClean = $taskDao->isCleanPlan_d($id);
			if(!$isClean){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	/**
	 * 删除计划操作重写
	 */
	function deletes($ids) {
		try {
			$this->start_d ();
			$this->recEffDel($ids);
			parent::deletes ( $ids );
//			$this->rollBack ();
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 工作量计算
	 * 可传入4个参数
	 * 参数 1 ：计划ID
	 * 参数 2 ：改变后的的工作量
	 * 参数 3 ：改变前的工作量
	 * 参数 4 ：计算类型 分为增 add(默认)，删 del, 改 edit
	 */
	function workloadCount( $id , $newLoad , $oldLoad = 0 , $type = 'add'){
		if($type = 'add'){
			$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload + '$newLoad'  where id = '$id'";
		}elseif($type = 'del'){
			$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload - '$newLoad'  where id = '$id'";
		}elseif($type = 'edit'){
			if($newLoad > $oldLoad){
				$addLoad = $newLoad - $oldLoad;
				$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload + '$addLoad'  where id = '$id'";
			}elseif($newLoad < $oldLoad){
				$addLoad = $oldLoad - $newLoad;
				$sql = "update oa_rd_project_plan set appraiseWorkload = appraiseWorkload - '$newLoad'  where id = '$id'";
			}elseif($newLoad = $oldLoad){
				return ;
			}
		}
		try{
			$this->query($sql);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 添加已投入工作量同时计算计划的完成率
	 * 计划完成率 = 任务完成率 / 任务个数
	 */
	function addPutLoad($id,$load = 0,$taskeffect = 0){
		$taskDao = new model_engineering_task_rdtask();
		$rows = $taskDao->getGroupTKByPlanId($id);
		$sql = "update oa_rd_project_plan set putWorkload = putWorkload + '$load',effortRate = '". $rows[0]['effortRate'] ."',warpRate = '". $rows[0]['warpRate'] ."',realEndDate = '".date('Y-m-d')."'  where id = '$id'";
		try{
			$this->query($sql);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 添加/删除计划时，重新计算计划的完成率--计划中添加计划时使用
	 * 会对父节点进行重新计算
	 */
	function recEff($id){
		$planInfo = $this->find(array('id' => $id),null,'parentId');
		if(!isset($taskDao)){
			$taskDao = new model_engineering_task_rdtask();
		}
		if($planInfo['parentId'] == -1){
			return true;
		}
		else{
			$planRows = $this->getInfoByParentId($planInfo['parentId']);
			$rowsPTask = $taskDao->getInfoByPlanId($planInfo['parentId']);

			$effortRate = round(($planRows[0]['effortRate'] + $rowsPTask[0]['effortRate'])/($planRows[0]['planNum'] + $rowsPTask[0]['taskNum']),2);

			$updatesql = "update oa_rd_project_plan set effortRate = '$effortRate' where id = '".$planInfo['parentId']."'";
			try{
				$this->query($updatesql);
			}catch(exception $e){
				throw $e;
			}
			return $this->recEff($planInfo['parentId']);
		}
	}

	function recEffDel($id){
		$planInfo = $this->find(array('id' => $id),null,'parentId');
		if(!isset($taskDao)){
			$taskDao = new model_engineering_task_rdtask();
		}
		if($planInfo['parentId'] == -1){
			return true;
		}
		else{
			$planRows = $this->getInfoByParentId($planInfo['parentId']);
			$rowsPTask = $taskDao->getInfoByPlanId($planInfo['parentId']);

			$effortRate = round(($planRows[0]['effortRate'] + $rowsPTask[0]['effortRate'])/($planRows[0]['planNum'] + $rowsPTask[0]['taskNum'] - 1),2);

			$updatesql = "update oa_rd_project_plan set effortRate = '$effortRate' where id = '".$planInfo['parentId']."'";
			try{
				$this->query($updatesql);
			}catch(exception $e){
				throw $e;
			}
			return $this->recEff($planInfo['parentId']);
		}
	}

	/**
	 * 计算任务的计划的完成率，会往上计算
	 */
	function taskRecEff($id){
		$planInfo = $this->find(array('id' => $id),null,'parentId');
		if(!isset($taskDao)){
			$taskDao = new model_engineering_task_rdtask();
		}
		$planRows = $this->getInfoByParentId($id);
		$rowsPTask = $taskDao->getInfoByPlanId($id);

		$effortRate = round(($planRows[0]['effortRate'] + $rowsPTask[0]['effortRate'])/($planRows[0]['planNum'] + $rowsPTask[0]['taskNum']),2);

		$updatesql = "update oa_rd_project_plan set effortRate = '$effortRate' where id = '".$id."'";
		try{
			$this->query($updatesql);
		}catch(exception $e){
			throw $e;
		}
		if($planInfo['parentId'] == -1){
			return true;
		}
		else{
			return $this->recEff($planInfo['parentId']);
		}
	}

	/**
	 * 查询父节点为 $parentId 的计划 的总完成率 和 偏差率 个 计划个数
	 */
	function getInfoByParentId($parentId){
		$sql = "select sum(p.effortRate) as effortRate,sum(p.warpRate) as warpRate,count(p.id) as planNum from oa_rd_project_plan p where p.parentId = '$parentId'";
		return $this->findSql($sql);
	}

	/**
	 * 获取统计
	 */
	function getTheRows($projectId){
		$this->searchArr['projectId'] = $projectId;
		$this->asc = false;
		return $this->listBySqlId('easy_list');
	}

	/**
	 * 计划资源使用透视图-图表
	 */
	function planCharts($rows){
		if($rows){
			$i = 0;
			$outArr = array ();
			foreach($rows as $val){
				$outArr[$i][1] = $val['planName'];
				$outArr[$i][2] = $val['appraiseWorkload'];
				$outArr[$i][3] = $val['putWorkload'];
				$i++;
			}
		}
		$fusionCharts = new model_common_fusionCharts();
//		return $fusionCharts->mixCharts($outArr,'计划资源使用透视图',null,'h','450','300',array('估计工作量','已投入工作量'));

		$chartConf = array('exportFileName'=>"计划资源使用透视图",
				'caption'=>"计划资源使用透视图",
				'exportAtClient'=>0,                    //0: 服务器端运行， 1： 客户端运行
                'exportAction'=>"download",              //如果是服务器运行，支持浏览器下载or服务器保存
                '$numberSuffix' =>"h",                   //后缀
                'numVisiblePlot'=>5,                     //滚动分栏列数
		);
		return $fusionCharts->showBarChart($outArr,"MSColumnLine3D.swf",$chartConf );
	}

	/**
	 * 计划实际进展透视图
	 */
	function planSchedule($rows){
		if($rows){
			$i = 0;
			$outArr = array ();
			foreach($rows as $val){
				$outArr[$i][1] = $val['planName'];
				$outArr[$i][2] = $val['effortRate'];
				$i++;
			}
		}

		$fusionCharts = new model_common_fusionCharts();

		$chartConf = array('exportFileName'=>"计划实际进展透视图",
		'caption'=>"计划实际进展透视图",
		'exportAtClient'=>0,                    //0: 服务器端运行， 1： 客户端运行
        'exportAction'=>"download",              //如果是服务器运行，支持浏览器下载or服务器保存
        '$numberSuffix' =>"%",                   //后缀
		);
		return $fusionCharts->showCharts($outArr,"Column2D.swf",$chartConf );
	}

	/**
	 * 发布计划
	 */
	function issue($id){
		$object = array( 'id' => $id,'status' => 'JHJXZ','realBeginDate' => date('Y-m-d'));
		return $this->edit_d($object);
	}

	/**
	 * 关闭结算
	 */
	function closeAndBalance($id,$planEndDate){
		$WarpRate = $this->planWarpRate($planEndDate);
		$object = array( 'id' => $id,'status' => 'JHWC','realEndDate' => date('Y-m-d'),'warpRate' => $WarpRate);
		return $this->edit_d($object);
	}

	/**
	 * 计划偏差率结算
	 */
	function planWarpRate($planEndDate,$realEndDate = null){
		if(empty($realEndDate)){
			$realEndDate = date('Y-m-d');
		}
		$ri = ($realEndDate - $planEndDate) / $planEndDate  * 100 ;
	}
}
?>
