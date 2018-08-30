<?php
/**
 * @description: 项目普通计划action
 * @date 2010-9-18 上午11:37:03
 * @author oyzx
 * @version V1.0
 */
class controller_rdproject_plan_rdplan extends controller_base_action {

	/**
	 * @desription 构造函数
	 * @date 2010-9-11 下午12:51:57
	 */
	function __construct() {
		$this->objName = "rdplan";
		$this->objPath = "rdproject_plan";
		$this->operArr = array ("planName"=> "计划名称" ,"planBeginDate" => "计划开始时间", "planEndDate" => "计划结束时间","appraiseWorkload" => "计划工作量" ); //统一注册监控字段，如果不同方法有不同的监控字段，在各自方法里面更改此数组
		parent::__construct ();
	}


	/***************************************************************************************************
	 * ------------------------------以下为普通action方法-----------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 我的进度计划
	 * @date 2010-9-25 上午09:53:19
	 */
	function c_rpPageMy() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list-my' );
	}

	/**
	 * 项目中心-进度计划
	 */
	function  c_rpListAll(){
		$this->show->display( $this->objPath . '_' . $this->objName . '-list-all' ) ;
	}

	/**
	 * @desription 添加计划跳转ACTION
	 * @param tags
	 * @date 2010-9-25 下午05:09:06
	 */
	function c_toAdd() {
//		$this->service->filterFunc('新增');
		$pnId = isset ( $_GET ['pnId'] ) ? $_GET ['pnId'] : "-1";
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : "" ;
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'myPlan';
		if($type == 'myPlan'){
			$this->show->assign('loca','add');
		}else{
			$this->show->assign('loca','addInAll');
		}
		$arr = $this->service->rpGetProjectInfo ( $pjId ,$pnId);
		$this->arrToShow ( $arr );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription 添加计划-我的进度计划
	 * @param tags
	 * @date 2010-9-25 下午08:28:41
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], 'true' );
		if ($id) {
			msg ( '添加成功！','debug');
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "添加计划【" .  $_POST [$this->objName]['planName'] . "】";//操作类型
			$this->behindMethod ( $objArr );
		}else{
			msg ( '添加失败！');
		}
	}

	/**
	 * 添加计划-项目中心-进度计划
	 */
	function c_addInAll(){
		$id = $this->service->add_d ( $_POST [$this->objName],  'true'  );
		if ($id) {
			msg ( '添加成功！' );
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "添加计划【" .  $_POST [$this->objName]['planName'] . "】";//操作类型
			$this->behindMethod ( $objArr );
		}else{
			msg ( '添加失败！');
		}
	}

	/**
	 * 添加计划-- 通过模板导入
	 */
	function c_toImport(){
		if(empty($_GET['planId'])){
			$_GET['parentId'] = -1;
			$_GET['parentName'] = '里程碑计划';
		}else{
			$_GET['parentId'] = $_GET['planId'];
			$_GET['parentName'] = $_GET['planName'];
		}
		foreach($_GET as $key=> $val){
			$this->show->assign($key ,$val);
		}
		$this->show->display($this->objPath . '_' . $this->objName . '-addByImport' );
	}

	/**
	 * 添加计划-通过模板导入
	 */
	function c_addByImport(){
		$id = $this->service->addByImport( $_POST[$this->objName] ) ;
		if($id){
			$objArr ['id'] = $id;
			$objArr ['operType_'] = "导入计划【" .  $_POST [$this->objName]['planName'] . "】";//操作类型
			$this->behindMethod ( $objArr );
			msg('导入成功');
		}else{
			msg ( '导入失败！');
		}
	}

	/**
	 * @desription 维护计划
	 * @param tags
	 * @date 2010-9-26 上午10:04:52
	 */
	function c_rpMaintenanceRead () {
		$pnId = isset( $_GET['pnId'] )?$_GET['pnId']:exit;
		$arr = $this->service->rpArrById_d($pnId);
		$this->arrToShow($arr);
		$this->show->assign ( 'pnId', "&pjId=".$pnId );
		$this->show->display($this->objPath . '_' . $this->objName . '-Maintenance-Read');
	}

	/**
	 * 查看计划
	 */
	function  c_view(){
		$pnId = isset ( $_GET ['pnId'] ) ? $_GET ['pnId'] : "-1";
		$rows = $this->service->getPlanInfoByEdit($_GET ['pnId']);
		//权限控制
//		$rows = $this->filterField('字段限制',$rows);
		foreach($rows as $key => $val){
			$this->show->assign($key,$val);
		}
		$this->show->assign ( 'pnId', $pnId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

	/**
	 * 编辑计划
	 */
	function  c_toEdit(){
		$pnId = isset ( $_GET ['pnId'] ) ? $_GET ['pnId'] : "-1";
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'myPlan';
		if($type == 'myPlan'){
			$this->show->assign('loca','edit');
		}else{
			$this->show->assign('loca','editInAll');
		}
		$rows = $this->service->getPlanInfoByEdit ($pnId);
		$rows['file']=$this->service->getFilesByObjId($pnId);
		foreach($rows as $key => $val){
			$this->show->assign($key,$val);
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}

	/**
	 * 重写 c_edit - 我的进度计划的编辑
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$this->beforeMethod( $object );//操作或者变更前获取修改前业务对象信息
		if ($this->service->edit_d ( $object )) {
			$object ['operType_'] = "修改组合【" . $object ['planName'] . "】";//操作类型
			$this->behindMethod ($object);//第一个参数业务对象，第二个参数不填默认为操
			msg ( '编辑成功！');
		}else{
			msg ( '编辑失败！');
		}
	}

	/**
	 * 项目中心-进度计划 编辑
	 */
	function c_editInAll(){
		$object = $_POST [$this->objName];
		$this->beforeMethod( $object );//操作或者变更前获取修改前业务对象信息
		if ($this->service->edit_d ( $object )) {
			$object ['operType_'] = "修改组合【" . $object ['planName'] . "】";//操作类型
			$this->behindMethod ($object);//第一个参数业务对象，第二个参数不填默认为操
			msg ( '编辑成功！');
		}else{
			msg ( '编辑失败！');
		}
	}

	/**
	 * 显示删除计划页面
	 */
	function c_delectPlan(){
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'myPlan';
		if($this->service->canDelete($_GET['pnId'])){
			if($type == 'myPlan'){
				showmsg('确认删除？',"location.href='?model=rdproject_plan_rdplan&action=deleteInMy&id=" . $_GET ['pnId'] . "'", 'button' );
			}else{
				showmsg('确认删除？',"location.href='?model=rdproject_plan_rdplan&action=deleteInAll&id=" . $_GET ['pnId'] . "'", 'button' );
			}
		}else{
			if($type == 'myPlan'){
				showmsg('请先清空计划的下级内容，再进行删除','self.parent.tb_remove();','button');
			}else{
				showmsg('请先清空计划的下级内容，再进行删除','self.parent.tb_remove();','button');
			}
		}
	}

	/**
	 * 删除计划-项目中心-进度计划
	 */
	function c_deleteInAll(){
		if ($this->service->deletes ( $_GET['id'] )) {
			msg ( '删除成功！');
		}else{
			msg ( '删除失败！');
		}
	}

	/**
	 * 删除计划-我的进度计划
	 */
	function c_deleteInMy(){
		if ($this->service->deletes ( $_GET['id'] )) {
			msg ( '删除成功！');
		}else{
			msg ( '删除失败！');
		}
	}

	/**
	 * 计划资源使用透视图-表格
	 */
	function c_planTable(){
		$rows = $this->service->getTheRows($_GET['pjId']);
		$this->show->assign( 'result' ,$this->service->planTable($rows) );
		$this->show->display( $this->objPath .'_' . $this->objName .'-planChartsView');
	}

	/**
	 * 计划资源使用透视图-图表
	 */
	function c_planCharts(){
		$rows = $this->service->getTheRows($_GET['pjId']);
		$this->show->assign( 'result' ,$this->service->planCharts($rows) );
		$this->show->display( $this->objPath .'_' . $this->objName .'-planChartsView');
	}

	/**
	 * 计划实际进展透视图-图表
	 */
	function c_planSchedule(){
		$rows = $this->service->getTheRows($_GET['pjId']);
		$this->show->assign( 'result' ,$this->service->planSchedule($rows) );
		$this->show->display( $this->objPath .'_' . $this->objName .'-planChartsView');
	}

	/**
	 * 发布计划
	 */
	function c_toIssue(){
		showmsg('确认发布？',"location.href='?model=rdproject_plan_rdplan&action=issue&id=" . $_GET ['pnId'] . "'", 'button' );
	}

	/**
	 * 发布操作
	 */
	function c_issue(){
		if ($this->service->issue ( $_GET['id'] )) {
			msg ( '发布成功！');
		}else{
			msg ( '发布失败！');
		}
	}

	/**
	 * 关闭结算
	 */
	function c_toClose(){
		showmsg('确认结算？',"location.href='?model=rdproject_plan_rdplan&action=closeAndBalance&id=" . $_GET ['pnId'] . "&planEndDate=". $_GET['planEndDate']. "'", 'button' );
	}

	/**
	 * 关闭结算
	 */
	function c_closeAndBalance(){
		if ($this->service->closeAndBalance ( $_GET['id'] ,$_GET['planEndDate'])) {
			msg ( '结算完成！');
		}else{
			msg ( '结算失败！');
		}
	}

	/***************************************************************************************************
	 * ------------------------------以下为ajax返回json方法---------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 我的进度计划-计划
	 * @date 2010-9-25 上午09:53:19
	 */
	function c_rpAjaxMyPlan() {
		//如果传递了项目名称，则为从项目获取第一层项目计划
		if (!isset ( $_GET ['planName'] )) {
			$searchArrGroup = array ("parentId" => -1,"projectId" => $_GET ['parentId'] );
		} else {
			$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		}

		$searchArrGroup['loginId'] = $_SESSION['USER_ID'];

		$this->ajaxGroupAndPlan ( $searchArrGroup );
	}

	function c_rpAjaxAllPlan() {
		//如果传递了项目名称，则为从项目获取第一层项目计划
		if (!isset ( $_GET ['planName'] )) {
			$searchArrGroup = array ("parentId" => -1,"projectId" => $_GET ['parentId'] );
		} else {
			$searchArrGroup = array ("parentId" => $_GET ['parentId'] );
		}

		$this->ajaxGroupAndPlan ( $searchArrGroup );
	}

	/**
	 * @desription 根据上级id获取下级的计划列表
	 * @param tags
	 * @date 2010-9-21 上午11:19:59
	 */
	function ajaxGroupAndPlan($searchArrGroup) {
		$service = $this->service;
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = $searchArrGroup;
		$service->asc = false;
		$service->groupBy = "c.id";
		$groupRows = $service->list_d ();
		//权限控制
		$groupRows = $this->service->filterField('字段限制',$groupRows,'list');

		if (is_array ( $groupRows )) {
			//产生一个以g_或p_为前缀的id，用以区分项目或组合
			function createOIdFn($row) {
				if (  $row ['parentId'] ==-1) {
					$row ['oid'] = "a_" . $row ['id']; //以p-为前缀表明上级为项目
					$row ['oParentId'] = "p_" . $row ['projectId'];
				} else {
					$row ['oid'] = "a_" . $row ['id']; //以a-为前缀表明上级为计划
					$row ['oParentId'] = "a_" . $row ['parentId'];
				}
				return $row;
			}
			echo util_jsonUtil::encode ( array_map ( "createOIdFn", $groupRows ) );
		}else{
			echo "none";
		}
		//echo util_jsonUtil::encode ( $groupRows );
	}


	/**一键通下拉列表pageJson
	 * add by zengzx    2011年8月22日 09:26:19
	 */
	function c_pageJsonByOnekey() {
		$service = $this->service;
		$seachArr = $service->getParam ( $_GET ); //设置前台获取的参数信息
		$service->searchArr = $seachArr;
		$rows = $service->pageBySqlId ("select_plan");
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		echo util_jsonUtil::encode ( $arr );
	}

}

?>
