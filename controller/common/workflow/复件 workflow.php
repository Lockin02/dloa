<?php
/*
 * Created on 2011-8-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_common_workflow_workflow extends controller_base_action {

	function __construct() {
		$this->objName = "workflow";
		$this->objPath = "common_workflow";
		parent :: __construct();
	}

	/**
	 * 列表页面
	 */
	function c_page(){
		$this->display('list');
	}

	/**
	 * tab页
	 */
	function c_auditTab(){
		$this->display('audittab');
	}

	/**
	 * 未审核列表
	 */
	function c_auditingList(){
		//获取选择默认值
		$this->assign('selectedCode',$this->service->getPersonSelectedSetting_d());
		$this->display('auditinglist');
	}

	/**
	 * 未审核pagejson
	 */
	function c_auditingPageJson(){
		$service = $this->service;

		$service->getParam($_POST); //设置前台获取的参数信息

		//下拉过滤处理
		if(isset($_POST['formName'])){
			$formName = util_jsonUtil :: iconvUTF2GB($_POST['formName']);
			//对于存在变更类型工作流的处理
			if(isset($service->changeFunArr[$formName])){
				$service->searchArr[$service->changeFunArr[$formName]['seachCode']]= 1;

			//对于是变更工作流的处理
			}else if(isset($service->urlArr[$formName]['isChange'])){
				$service->searchArr[$service->urlArr[$formName]['seachCode']]= 1;
				$service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
			}
		}else{
			$service->searchArr['inNames'] = $service->rtWorkflowStr_d();
		}

		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('select_auditing');
		//处理变更部分数据
		$rows = $service->rowsDeal_d($rows);
		//处理审批意见部分
		$rows = $service->auditInfo_d($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 已审核列表
	 */
	function c_auditedList(){
		//获取选择默认值
		$this->assign('selectedCode',$this->service->getPersonSelectedSetting_d('audited'));
		$this->display('auditedlist');
	}

	/**
	 * 已审核pagejson
	 */
	function c_auditedPageJson(){
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息

		//下拉过滤处理
		if(isset($_POST['formName'])){
			$formName = util_jsonUtil :: iconvUTF2GB($_POST['formName']);
			//对于存在变更类型工作流的处理
			if(isset($service->changeFunArr[$formName])){
				$service->searchArr[$service->changeFunArr[$formName]['seachCode']]= 1;

			//对于是变更工作流的处理
			}else if(isset($service->urlArr[$formName]['isChange'])){
				$service->searchArr[$service->urlArr[$formName]['seachCode']]= 1;
				$service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
			}
		}else{
			$service->searchArr['inNames'] = $service->rtWorkflowStr_d();
		}

		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('select_audited');
		//处理变更部分数据
		$rows = $service->rowsDeal_d($rows);
		//处理审批意见部分
		$rows = $service->auditInfo_d($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 审批时查看的单据
	 */
	function c_toObjInfo(){
		$obj = $_GET;
		unset($obj['model']);
		unset($obj['action']);
    $tempdb=$this->service->getWfInfo_d($obj['spid']);
    if(!empty($tempdb['DBTable'])){
      $dburlstr='&gdbtable='.$tempdb['DBTable'];
    }else{
      $dburlstr='';
    }
		if(!empty($this->service->urlArr[$obj['formName']]['url'])){
			$objId = $obj['billId'];
//			if(isset($this->service->urlArr[$obj['formName']]['isChange'])){
//				$objId = $this->service->getObjIdByTempId_d($objId,$this->service->urlArr[$obj['formName']]['changeCode']);
//			}

			$url = $this->service->urlArr[$obj['formName']]['url'] .$objId;

			if($this->service->urlArr[$obj['formName']]['isSkey']){
				$url .= '&skey='.$this->md5Row($objId,$this->service->urlArr[$obj['formName']]['keyObj']);
			}
      $url.=$dburlstr;
			succ_show($url);
		}else{
			echo '未配置完成的审批对象，请联系管理员完成对应配置';
		}
	}

	/**
	 * 列表查看单据
	 */
	function c_toViweObjInfo(){
		$obj = $_GET;
		unset($obj['model']);
		unset($obj['action']);
    $tempdb=$this->service->getWfInfo_d($obj['spid']);
    if(!empty($tempdb['DBTable'])){
      $dburlstr='&gdbtable='.$tempdb['DBTable'];
    }else{
      $dburlstr='';
    }
		if(!empty($this->service->urlArr[$obj['formName']]['viewUrl'])){
			$objId = $obj['billId'];
			if(isset($this->service->urlArr[$obj['formName']]['isChange'])&&$_GET['audited']){//判断是否为变更审批
				$objId = $this->service->getObjIdByTempId_d($objId,$this->service->urlArr[$obj['formName']]['changeCode']);
				$url = $this->service->urlArr[$obj['formName']]['auditedViewUrl'] .$objId;
			}else{
				$url = $this->service->urlArr[$obj['formName']]['viewUrl'] .$objId;
			}

			if($this->service->urlArr[$obj['formName']]['isSkey']){
				$url .= '&skey='.$this->md5Row($objId,$this->service->urlArr[$obj['formName']]['keyObj']);
			}
      $url.=$dburlstr;
			succ_show($url);
		}else{
			echo '未配置完成的审批对象，请联系管理员完成对应配置';
		}
	}

	/**
	 * 审批完成后跳转页面
	 * edit on 2012-03-01
	 * edit by kuangzw
	 */
	function c_toLoca(){
		//获取工作流相关信息
		$thisFormName = $this->service->getWfInfo_d($_GET['spid']);

		$allStep = isset($this->service->urlArr[$thisFormName['formName']]['allStep']) ? $this->service->urlArr[$thisFormName['formName']]['allStep'] : null;
		if(empty($allStep) && empty($thisFormName['examines'])){
			//如果不是最后一步审批，且没有配置所有步骤调用的工作流,直接返回审批页面
			succ_show('?model=common_workflow_workflow&action=auditingList');
		}

		//如果对应流程存在变更，则调用变更返回路径
		if($url = $this->service->inChange_d($_GET['spid'])){
			succ_show($url);
		}else if(isset($this->service->urlArr[$thisFormName['formName']]['rtUrl'])){
			//直接调用正常审批流程返回路径
			$addStr = null;
			if(isset($_GET['row'])){
				$rows = $_GET['row'];
				if(is_array($rows)){
					foreach($rows as $key => $val){
						$addStr .= '&rows['.$key.']=' . $val;
					}
				}
			}
			if(!empty($_GET['gdbtable'])){
          $addStr .='&gdbtable='.$_GET['gdbtable'];
      }
			$url = $this->service->urlArr[$thisFormName['formName']]['rtUrl'].$_GET['spid'].$addStr;
			succ_show($url);
		}else{
			//如果不存在返回路径,则默认跳转到所有审批页面
			succ_show('?model=common_workflow_workflow&action=auditingList');
		}
	}


	/**
	 * 获取工作流类型 - 用于下拉过滤
	 */
	function c_getFormType(){
		$orgArr = $this->service->rtWorkflowArr_d();
		$newArr = array();
		foreach($orgArr as $key => $val){
			$newArr[$key]['text'] = $val;
			$newArr[$key]['value'] = $val;
		}
		echo util_jsonUtil::encode ( $newArr);
	}
}
?>
