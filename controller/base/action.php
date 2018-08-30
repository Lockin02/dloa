<?php
/**
 * 描述：控制层基础类，所有的控制层类都应该基础此类，用于初始化工厂类，基础的业务逻辑层类，公用控制层参数如分页参数等也应该放在此
 * 作者：chengl
 * 日期:2010-06-16
 */
class controller_base_action {
	/**
	 * 模板控制类
	 */
	protected $show;
	/**
	 * 模板路径前缀
	 */
	protected $objPath;
	/**
	 * 业务逻辑类名称
	 */
	protected $objName;
	/**
	 * 根据modelName生成的业务逻辑model类
	 */
	protected $service;

	function __construct() {
		//加入安全校验类
		$this->sconfig = new model_common_securityUtil ( $this->objName );
		$classname = "model_" . $this->objPath . "_" . $this->objName;
		$this->service = new $classname ();
		$this->show = new show ();
		if(isset($this->lang)){
			$lang=empty($_SESSION['lang'])?"chinese":$_SESSION['lang'];
			$langModel=$this->lang;
			$this->langUtil=new resources_langUtil($lang,$langModel);
			$this->service->langUtil=$this->langUtil;//让service也能使用
			$commonLangArr=$this->langUtil->commonLangArr;
			$modelLangArr=$this->langUtil->modelLangArr;
			if(is_array($commonLangArr)){
				foreach($commonLangArr as $k=>$v){
					$this->assign ( "common_".$k, $v );//加上lang前缀，用于区分其他属性
				}
			}
			if(is_array($modelLangArr)){
				foreach($modelLangArr as $k=>$v){
					$this->assign ( $langModel."_".$k, $v );//加上lang前缀，用于区分其他属性
				}
			}
		}
	}

	/*
	 * 默认的列表跳转方法
	 */
	function c_list() {
		$this->display ( 'list' );
	}

	/**
	 *默认action跳转函数
	 */
	function c_index() {
		$this->c_page ();
	}
	/**
	 * 显示对象分页列表
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$rows = $service->page_d ();
		//分页
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $service->count ) );

		$this->assign ( 'list', $service->showlist ( $rows, $showpage ) );
		$this->display ( 'list' );
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	* ajax获取业务对象信息
	*/
	function c_getByAjax(){
		$obj = $this->service->get_d ( $_REQUEST ['id'] );
		echo util_jsonUtil::encode ( $obj );
	}

	/**
	* 根据条件获取业务对象数量
	*/
	function c_getCountByName(){
		$nameCol=$_REQUEST ['nameCol'];
		$nameVal=$_REQUEST ['nameVal'];
		$idVal=$_REQUEST ['idVal'];
		$idCol=$_REQUEST ['idCol'];
		$nameVal=util_jsonUtil::iconvUTF2GB($nameVal);
		$checkParam=$_REQUEST ['checkParam'];
		if(!empty($idVal)){
			$checkParam[$idCol]=$idVal;
			$checkParam[$nameCol]=$nameVal;
			$objs=$this->service->find($checkParam);
			if(is_array($objs)&&count($objs)>0){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			$num = $this->service->findCount ( array($nameCol=>$nameVal));
			echo $num;
		}
	}

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->display ('add',true);
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ('edit',true);
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 批量删除对象
	 */
	function c_deletes() {
		//$this->permDelCheck ();
		$message = "";
		try {
			$this->service->deletes_d ( $_GET ['id'] );
			$message = '<div style="color:red" align="center">删除成功!</div>';

		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">删除失败，该对象可能已经被引用!</div>';
		}
		if (isset ( $_GET ['url'] )) {
			$event = "document.location='" . iconv ( 'utf-8', 'gb2312', $_GET ['url'] ) . "'";
			showmsg ( $message, $event, 'button' );
		} else if (isset ( $_SERVER [HTTP_REFERER] )) {
			$event = "document.location='" . $_SERVER [HTTP_REFERER] . "'";
			showmsg ( $message, $event, 'button' );
		} else {
			$this->c_page ();
		}
	}

	/*
	 * ajax方式批量删除对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxdeletes() {
		//$this->permDelCheck ();
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

	/**
	 * 批量保存对象操作，根据批量对象id属性是否有值自动进行新增或者修改操作
	 */
	function c_saveBatch($isAddInfo = false) {
		$objs = $_POST [$this->objName];
		$objs = util_jsonUtil::iconvUTF2GBArr ( $objs ); //需要对前台提交ajax进行转码
		$addObjs = array ();
		$editObjs = array ();
		foreach ( $objs as $key => $value ) {
			if (empty ( $value ['id'] )) {
				$addObjs [] = $value;
			} else {
				$editObjs [] = $value;
			}
		}
		$this->service->saveBatch ( $addObjs, $editObjs );
	}

	/*****************************以上为公用的增删改方法**************************************************/

	/**
	 * 检查对象是否重复
	 */
	function c_checkRepeat() {
		$checkId = "";
		$service = $this->service;
		if (isset ( $_REQUEST ['id'] )) {
			$checkId = $_REQUEST ['id'];
			unset ( $_REQUEST ['id'] );
		}
		if(!isset($_POST['validateError'])){
			$service->getParam ( $_REQUEST );
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			echo $isRepeat;
		}else{
			//新验证组件
			$validateId=$_POST['validateId'];
			$validateValue=$_POST['validateValue'];
			$service->searchArr=array(
				$validateId."Eq"=>$validateValue
			);
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			$result=array(
				'jsonValidateReturn'=>array($_POST['validateId'],$_POST['validateError'])
			);
			if($isRepeat){
				$result['jsonValidateReturn'][2]="false";
			}else{
				$result['jsonValidateReturn'][2]="true";
			}
			echo util_jsonUtil::encode ( $result );
		}
	}

	/*
	 * 根据code数组获取数据字典项
	 * 传入的数组key为页面上设置的模板字符串，value为后台设置的父亲code
	 */
	function getDatadicts($parentCodeArr,$conditionArr = null) {
		if (is_array ( $parentCodeArr )) {
			$parentCodes = implode ( ",", $parentCodeArr );
			//根据上级编码获取数据字典信息
			$datadictDao = new model_system_datadict_datadict ();
			$datadictArr = $datadictDao->getDatadictsByParentCodes ( $parentCodes ,$conditionArr);
			return $datadictArr;
		}
	}

	/*
	 * 设置页面数据字典项
	 */
	function showDatadicts($parentCodeArr, $keyCode = null, $emptyOption = null ,$conditionArr = null,$returnStr = false) {
		if (is_array ( $parentCodeArr )) {
			$datadictArr = $this->getDatadicts ( $parentCodeArr ,$conditionArr);
			foreach ( $datadictArr as $key => $valueArr ) {
				$str = "";
				if ($emptyOption) {
				    if(is_array($emptyOption)){
				        foreach ($emptyOption as $eok => $eov){
                            $str .= "<option value='".$eov."'>".$eok."</option>";
                        }
                    }else{
                        $str .= "<option value=''></option>";
                    }
				}
				if (is_array ( $valueArr )) {
					foreach ( $valueArr as $k => $v ) {
						$eStr = 'e1="' . $v ['expand1'] . '" e2="' . $v ['expand2'] . '" e3="' . $v ['expand3'] . '" e4="' . $v ['expand4'] . '" e5="' . $v ['expand5'] . '"';
						if ($v ['dataCode'] == $keyCode)
							$str .= '<option '.$eStr.' value="' . $v ['dataCode'] . '" title="'.$v ['remark'].'" selected>';
						else
							$str .= '<option '.$eStr.' value="' . $v ['dataCode'] . '" title="'.$v ['remark'].'">';
						$str .= $v ['dataName'];
						$str .= '</option>';
					}
				}
				$k = array_search ( $key, $parentCodeArr );
				if($returnStr){
                    return $str;
                }else{
                    $this->show->assign ( $k == false ? $key : $k, $str );
                }
			}
			return $str;
		}
	}

	/*
	 * 设置页面数据字典项(option的 value 与text 都是数字字典的name)
	 */
	function showDatadictsByName($parentCodeArr, $keyCode = null, $emptyOption = false ,$conditionArr = null) {
		if (is_array ( $parentCodeArr )) {
			$datadictArr = $this->getDatadicts ( $parentCodeArr ,$conditionArr);
			//print_r($datadictArr);
			foreach ( $datadictArr as $key => $valueArr ) {
				$str = "";
				if ($emptyOption) {
					$str .= "<option value=''></option>";
				}
				if (is_array ( $valueArr )) {
					foreach ( $valueArr as $k => $v ) {
						$eStr = 'e1="' . $v ['expand1'] . '" e2="' . $v ['expand2'] . '" e3="' . $v ['expand3'] . '" e4="' . $v ['expand4'] . '" e5="' . $v ['expand5'] . '"';
						if ($v ['dataName'] == $keyCode)
							$str .= '<option '.$eStr.' value="' . $v ['dataName'] . '" title="'.$v ['remark'].'" selected>';
						else
							$str .= '<option '.$eStr.' value="' . $v ['dataName'] . '" title="'.$v ['remark'].'">';
						$str .= $v ['dataName'];
						$str .= '</option>';
					}
				}
				$k = array_search ( $key, $parentCodeArr );
				$this->show->assign ( $k == false ? $key : $k, $str );
			}
		}
	}

	/*
	 * 根据数据字典编码获取数据字典名称
	 */
	function getDataNameByCode($code) {
		if (! isset ( $this->datadictDao )) {
			$this->datadictDao = new model_system_datadict_datadict ();
		}
		return $this->datadictDao->getDataNameByCode ( $code );
	}

	/*
	 * 设置自定义下拉选项
	 */
	function showSelectOption($parentCode, $keyCode = null, $emptyOption = false ,$dataArr = null) {
		if ( $parentCode) {
			$str = "";
			if ($emptyOption) {
				$str .= "<option value=''></option>";
			}

			if (is_array ( $dataArr )) {
				foreach ( $dataArr as $k => $v ) {
					$eStr = 'e1="' . $v ['expand1'] . '" e2="' . $v ['expand2'] . '" e3="' . $v ['expand3'] . '" e4="' . $v ['expand4'] . '" e5="' . $v ['expand5'] . '"';
					if ($v ['code'] == $keyCode)
						$str .= '<option '.$eStr.' value="' . $v ['code'] . '" selected>';
					else
						$str .= '<option '.$eStr.' value="' . $v ['code'] . '">';
					$str .= $v ['name'];
					$str .= '</option>';
				}
			}
			$this->show->assign ( $parentCode , $str );
		}
	}

	/**
	 * 左导航栏
	 * numb从1开始计算
	 * 注：$arrayPanel = array("topName"=>"左导航","numb"=>0,"name1"=>"xx","url1"=>"xx","name2"=>"xxx","url2"="xxx")
	 * TODO:扩展
	 */
	function leftPlan($arrayPanel = array("numb"=>0)) {
		if ($arrayPanel ["numb"] > 0) {
			$str = "";
			for($i = 1; $i <= $arrayPanel ["numb"]; $i ++) {
				$url = $arrayPanel ["url" . $i];
				$name = $arrayPanel ["name" . $i];
				if ($i == 1)
					$styleThis = "cursor:hand;background-color:#80E8FC;";
				else
					$styleThis = "cursor:hand;";
				$str .= <<<EOT
					<tr class="TableHeader" onClick="setTrColor(this);parent.main.location='$url';" style="$styleThis">
				        <td style="padding-left:25">
				            <a href="#">$name</a>
				        </td>
				    </tr>
EOT;
			}
			$this->show->assign ( 'topName', $arrayPanel ["topName"] );
			$this->show->assign ( 'list', $str );
			$this->show->display ( 'common_left-panel' );
			unset ( $this->show );
		} else {
			//例子：
			$arrayPanel = array ("topName" => "这是导航条", "numb" => 2, "name1" => "导航1", "url1" => "#", "name2" => "导航2", "url2" => "#" );
			$this->leftPlan ( $arrayPanel );

		}
	}

	/**
	 * 头导航栏
	 * numb从1开始计算
	 * clickNumb 默认选中哪个
	 * TODO:扩展
	 */
	function topPlan($arrayPanel = array("numb"=>0)) {
		if ($arrayPanel ["numb"] > 0) {
			$str = <<<EOT
				<script>
					function clickTab(thisObj){
						document.getElementById('selLi').id='';
						thisObj.id='selLi';
					}
				</script>
				<div id='tabsJ'>
					<ul>
EOT;
			for($i = 1; $i <= $arrayPanel ["numb"]; $i ++) {
				if (isset ( $arrayPanel ["clickNumb"] ) && $i == $arrayPanel ["clickNumb"])
					$selLi = "id='selLi'";
				else
					$selLi = "";

				if (! isset ( $arrayPanel ["title" . $i] ))
					$title = $arrayPanel ["name" . $i];
				else
					$title = $arrayPanel ["title" . $i];
				$str .= "<li " . $selLi . " onclick='clickTab(this)'><a href='" . $arrayPanel ["url" . $i] . "' title='" . $title . "'><span>" . $arrayPanel ["name" . $i] . "</span></a></li>";
			}
			$str .= "</ul></div>";
			return $str;
		} else {
			//例子：
			$arrayPanel = array ("numb" => 2, "clickNumb" => 2, "name1" => "头部1", "title1" => "头部11", "url1" => "#", "name2" => "头部2", "title2" => "头部22", "url2" => "#" );
			$this->topPlan ( $arrayPanel );
		}
	}

	/**
	 * 数组直接转换action中assign
	 */
	function arrToShow($arr) {
		$assignName = $this->objName;
		foreach ( $arr ["0"] as $key => $val ) {
			if (! is_array ( $val )) {
				$this->show->assign ( $assignName . "[$key]", $val );
			}
		}
	}

	/**
	 * @exclude 显示审批查看列表
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 下午07:33:45
	 */
	function showExa($arrExa) {
		if ($arrExa) {
			$str = <<<EOT
	<tr align="center" >
      <td colspan="6" style="padding-left:0px;padding-right:0px;" >
        <table border="0" cellspacing="0" cellpadding="0" class="table" width="100%"  align="center">
                <tr align="center" class="tablecontrol" >
                    <td width="100%" align="center" colspan="6" style="font-size:14px;" height="35"><B>审批情况</B></td>
                </tr>
                    <tr align="center" class="TableLine2" style="color:blue;">
                        <td width="20%">步骤名</td>
                        <td width="10%">审批人</td>
                        <td width="20%">审批日期</td>
                        <td width="9%">审批结果</td>
                        <td width="27%">审批意见</td>
                    </tr>
EOT;
			foreach ( $arrExa as $key => $val ) {
				if ($val ["childArr"]) {
					$x = 0;
					foreach ( $val ["childArr"] as $childKey => $childVal ) {
						$x ++;
						$str .= "<tr class='extr TableLine2' >";
						if ($x == 1) {
							$str .= "<td rowspan=" . count ( $val ["childArr"] );
							if ($childVal ["Flag"] == 0) {
								$str .= " style='color:red;' ";
							}
							$str .= ">&nbsp;" . $val ["Item"] . "</td>";
						}
						//trim(get_username_list( $childVal['User'] ),",")
						$str .= " <td align='center'>&nbsp " . $childVal ['User'] . "</td>";
						$str .= " <td align='center' style='color:green;'>&nbsp;" . $childVal ['Endtime'] . "</td> ";
						$str .= "<td align='center'>&nbsp;";

						if (isset ( $childVal ['Result'] ) && $childVal ['Result'] == 'ok') {
							$str .= "<font color='green'>同意</font>";
						} elseif (isset ( $childVal ['Result'] ) && $childVal ['Result'] == 'no') {
							$str .= "<font color='red'>不同意</font>";
						} else {
							$str .= '未审批';
						}
						$str .= "</td><td>&nbsp;";
						if (isset ( $childVal ['Content'] ))
							$str .= $childVal ['Content'];

						$str .= "</td></tr>";
					}
				} else {
					$str .= "<tr class='extr TableLine2' >";
					$str .= "<td >&nbsp;" . $val ['Item'] . "</td>";
					$str .= " <td align='center'>&nbsp " . $val ['User'] . "</td>";
					$str .= " <td align='center'>&nbsp;" . $val ['Endtime'] . "</td>";
					$str .= "<td align='center'>&nbsp;未审批</td><td>&nbsp;</td></tr>";
				}

			}
			$str .= "</table></td></tr>";
		} else {
			$str = "";
		}
		return $str;
	}

	/**
	 * @desription 分页显示方法
	 * @param $div 表单替换名称
	 * @date 2010-9-17 上午09:41:48
	 */
	function pageShowAssign($isPost = false, $div = 'pageDiv') {
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $this->service->count ) );
		$this->show->assign ( $div, $showpage->pageDiv ( $isPost ) );
	}

	/**
	 * @desription 生成唯一的项目业务编号
	 * @return return_type
	 * @date 2010-9-26 下午02:57:14
	 */
	function businessCode() {
		return $this->objName . date ( "YmdHis" ) . rand ( 10, 99 );
	}

	/**
	 * @desription 操作前统一调用方法
	 * @param $object 操作对象
	 */
	function beforeMethod($object) {
		$this->beforeTag = true;
		if (! isset ( $this->operLog ))
			$this->operLog = "";

		//拿到对象在数据库的原记录
		$oldObj = $this->service->get_d ( $object ['id'] );
		$result = array_diff_assoc ( $oldObj, $object ); //拿出差值
		$datadictDao = new model_system_datadict_datadict ();
		if (is_array ( $this->operArr )) {
			foreach ( $this->operArr as $key => $value ) {
				if (isset ( $result [$key] )) { //判断是否是监控字段(包括操作及变更需要监控的字段)
					$oldValue = $oldObj [$key];
					$newValue = $object [$key];
					if (is_array ( $this->service->datadictFieldArr )) { //数据字典字段处理
						if (in_array ( $key, $this->service->datadictFieldArr )) {
							$oldValue = $datadictDao->getDataNameByCode ( $oldObj [$key] );
							$newValue = $datadictDao->getDataNameByCode ( $object [$key] );
						}
					}
					$this->operLog .= $value . ":" . $oldValue . "==>" . $newValue . "<br>";
				}
			}
		}
	}

	/**
	 * @desription 操作成功后统一调用方法
	 * @param $object 操作对象
	 * @param $type 操作类型,默认oper为操作，change为变更
	 */
	function behindMethod($object, $type = "oper") {
		if (is_array ( $type )) { //支持多种类型操作，以后扩展


		} else {
			//操作记录
			if ($type == "oper") {
				$this->operationLog ( $object );
			}
			//变更记录
			if ($type == "change") {
				$this->changeLog ( $object );
			}
		}
		//其他操作扩展
	}

	/*
	 * @desription 操作记录
	 * @param $object 操作对象
	 */
	private function operationLog($object) {
		$operation = array ();
		$operation ['objTable'] = $this->service->tbl_name;
		$operation ['objId'] = $object ['id'];
		$operation ['operateType'] = $object ['operType_']; //操作类型作为对象的属性传过来，加上下划线避免与对象业务属性冲突
		//如果没有before标志或者operLog不为空，则需要插入操作记录（这里判断主要解决修改的时候没有修改字段也插入操作记录问题，在beforeMenthod方法里面会进行beforeTag标志）
		if (! isset ( $this->beforeTag ) || ! empty ( $this->operLog )) {
			if (isset ( $object ['operateLog_'] )) {
				$operation ['operateLog'] = $object ['operateLog_'];
			} else {
				$operation ['operateLog'] = $this->operLog;
			}
			$operationDao = new model_log_operation_operation ();
			$operationDao->add_d ( $operation );
		}
	}

	/*
	 * @desription 变更记录
	 * @param $object 操作对象
	 */
	private function changeLog($object) {
		$change = array ();
		$change ['objTable'] = $this->service->tbl_name;
		$change ['objId'] = $object ['id'];
		if (! empty ( $object ['changeReason'] )) {
			$change ['changeReason'] = $object ['changeReason']; //对象变更原因属性
		}
		if (! empty ( $this->operLog )) {
			$change ['changeLog'] = $this->operLog;
		}
		$changeDao = new model_log_change_change ();
		$changeDao->add_d ( $change );
	}

	/*
	 * 判断是否有执行
	 */
	function isCurUserPerm($projectId, $permCode) {
		//判断是否是项目负责人或者项目助理，是不进行权限判断
		$projectDao = new model_rdproject_project_rdproject ();
		$project = $projectDao->get_d ( $projectId );
		$curUserId = $_SESSION ['USER_ID'];
		$pos = strpos ( $project ['assistantId'], $curUserId . "," );
		if ($pos != - 1 && $project ['managerId'] != $curUserId && $project ['createId'] != $curUserId) {
			$roleDao = new model_rdproject_role_rdrole ();
			if (! $roleDao->isCurUserPerm ( $projectId, $permCode )) {
				msg ( "没有执行此操作权限，请联系管理员！" );
				exit ();
			}
		}
	}

	/**
	 * 测试Model方法
	 */
	function c_model() {
		$this->service->rmMilespointEnd_d ( "94", "220", "2010-09-08" );
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_addExecelData($objNameArr) {
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelData ( $filename, $temp_name );
			if ($excelData) {
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objNameArr as $index => $fieldName ) {
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				if ($this->service->addBatch_d ( $objectArr ))
					echo "<b>导入成功!</b>";
				else
					echo "导入失败,请重新上传!";
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}
	}

	/**
	 * @desription 添加搜索
	 * @param tags
	 * @date 2010-11-18 下午04:20:44
	 */
	function searchVal($key, $head = "POST") {
		if ($head == "POST") {
			$searchVal = isset ( $_POST [$key] ) ? $_POST [$key] : "";
		} else if ($head == "Get") {
			$searchVal = isset ( $_GET [$key] ) ? $_GET [$key] : "";
		}
		if ($searchVal != "") {
			$this->service->searchArr [$key . "Seach"] = $searchVal;
		}
	}

	/**
	 * 设置模板变量
	 */
	function assign($key, $value) {
		if($value === '0000-00-00' || $value === 'NULL'|| $value === '0000-00-00 00:00:00'){
			$value = '';
		}
		$this->show->assign ( $key, $value );
	}

	/**
	 * 跳转页面封装
	 */
	function display($result,$needSubmit = false) {
		if (! empty ( $_GET ['skey'] )) {
			$this->show->assign ( "skey_", $_GET ['skey'] );
		}
		$needSubmit ? $this->createSubmitTag() : $this->assign('submitTag_','');//创建重复提交校验
		$this->show->display ( $this->objPath . '_' . $this->objName . '-' . $result );
	}

	/**
	 * 跳转页面封装
	 */
	function displayPT($result,$datas,$needSubmit = false) {
		if (! empty ( $_GET ['skey'] )) {
			$this->show->assign ( "skey_", $_GET ['skey'] );
		}
		$needSubmit ? $this->createSubmitTag() : $this->assign('submitTag_','');//创建重复提交校验
		$this->show->displayPT ( $this->objPath . '_' . $this->objName . '-' . $result,$datas);
	}

	/*
	 * 新模板显示方法，加入公用的js及css
	 */
	function view($result,$needSubmit = false) {
		$c = file_get_contents ( 'includes/commonInclude.htm' );
		$this->show->assign ( "#commonInclude#", $c );
		$this->show->assign ( "#comboGrid#", "js/jquery/combo/business" );
		$this->show->assign ( "#jsPath#", "view/template/" . str_replace ( '_', '/', $this->objPath ) . '/js' );
		if (! empty ( $_GET ['skey'] )) {
			$this->show->assign ( "skey_", $_GET ['skey'] );
		}
		$this->assignLang();
		$needSubmit ? $this->createSubmitTag() : $this->assign('submitTag_','');//创建重复提交校验
		$this->show->display ( $this->objPath . '_' . $this->objName . '-' . $result );
	}

	/**
	 * 语言包模板替换
	 */
	function assignLang(){
		//语言包处理
		if(isset($this->lang)){
			$commonLangArr=$this->langUtil->commonLangArr;
			$modelLangArr=$this->langUtil->modelLangArr;
			$str="<script>var langUtil={get:function(key){return langUtil.modelLangArr[key]?langUtil.modelLangArr[key]:langUtil.commonLangArr[key];}};</script>";
			if(is_array($commonLangArr)){
				$str.="<script>langUtil.commonLangArr=".util_jsonUtil::encode ( $commonLangArr )."</script>";
			}
			if(is_array($modelLangArr)){
				$str.="<script>langUtil.modelLangArr=".util_jsonUtil::encode ( $modelLangArr )."</script>";
			}
			$this->show->assign ( "#langInclude#",$str);
		}
	}

	/**
	 * foreach循环...
	 */
	function assignFunc($object) {
		foreach ( $object as $key => $val ) {
			$this->assign ( $key, $val );
		}
	}

	/**
	 * 私有方法 获取业务模块记录
	 * @param  $id
	 * @param  $code
	 */
	private function getModelRow($id, $code) {
		if (empty ( $id )) {
			$id = $_GET ['id'];
		}
		if (! empty ( $code )) {
			$className = "model_" . $code;
			$service = new $className ();
			$className = "controller_" . $code;
			$controller = new $className ();
			$this->sconfig = new model_common_securityUtil ( $controller->objName );
		} else {
			$service = $this->service;
		}
		//这里为了防止get_d被重写导致的效率问题，直接调用底层find方法
		$condition = array ("id" => $id );
		return $service->find ( $condition );
	}

	/**
	 * url安全加密,加密一条业务记录并返回加密后密匙
	 * @param  $id 业务对象id
	 * @param  $code 业务对象编码 不传则用action业务编码
	 * @param  $keyField 编码字段 不传则用配置文件
	 */
	function md5Row($id, $code, $keyField) {
		$row = $this->getModelRow ( $id, $code );
		$key = $this->sconfig->md5Row ( $row, $keyField, $code );
		return $key;
	}

	/**
	 * ajax url安全加密,加密一条业务记录并返回加密后密匙
	 * @param  $id 业务对象id
	 * @param  $code 业务对象编码 不传则用action业务编码
	 * @param  $keyField 编码字段 不传则用配置文件
	 */
	function c_md5RowAjax() {
		$key = $this->md5Row ( $_POST['id'], $_POST['code'], $_POST['keyField'] );
		echo $key;
	}

	/**
	 * url安全校验
	 * @param  $id 业务对象id
	 * @param  $code 业务对象编码
	 */
	function permCheck($id = false, $code = false) {
		//return true;//先放开权限
		$row = $this->getModelRow ( $id, $code );
		$hasPerm = $this->sconfig->permCheck ( $row, $_GET ['skey'] );
		if (! $hasPerm) {
			msg ( "非法访问." );
			//echo ( "没有访问权限." );
			exit ();
		}
	}
	/**
	 * 删除权限校验
	 * @param  $ids
	 */
	function permDelCheck($ids) {
		if (empty ( $ids )) {
			$ids = $_REQUEST ['id'];
		}
		$hasPerm = false;
		$idArr = explode ( ",", $ids );
		if (is_array ( $idArr ) && count ( $idArr ) > 0) {
			$row = $this->getModelRow ( $idArr [0], $code );
			$hasPerm = $this->sconfig->permCheck ( $row, $_REQUEST ['skey'] );
		//return $hasPerm;
		}
		if (! $hasPerm) {
			echo 2;
			exit ();
		}
	}

	/**
	 * 添加一个根节点
	 */
	function addRoot($rows,$textkey='text',$textName='全选'){
		if($_GET['addRoot']==1){
			return array(array(id=>"root",$textkey=>$textName,isParent=>1,nodes=>$rows));
		}
		return $rows;
	}

	/**
	 * 根据key获取语言包
	 */
	function getLangByKey($key){
		return $this->langUtil->getLangByKey($key);
	}

	/**
	 * 创建提交唯一标识
	 */
	function createSubmitTag(){
		$_SESSION['submitTag'] = $this->service->uuid();
		$this->assign ( "submitTag_", $_SESSION['submitTag'] );
	}

	/**
	 * 检验是否重复提交
	 */
	function checkSubmit(){
		$tag=$_POST['submitTag_'];
		if(!empty($tag)){
			if($_SESSION['submitTag']!=$tag){
				msg("请不要重复提交表单.");
				throw new Exception("请不要重复提交表单.");
			}else{
				$_SESSION['submitTag']=$this->service->uuid();
			}
		}
	}
}