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
	 * �б�ҳ��
	 */
	function c_page(){
		$this->display('list');
	}

	/**
	 * tabҳ
	 */
	function c_auditTab(){
		$this->display('audittab');
	}

	/**
	 * δ����б�
	 */
	function c_auditingList(){
		//��ȡѡ��Ĭ��ֵ
		$this->assign('selectedCode',$this->service->getPersonSelectedSetting_d());
		$this->display('auditinglist');
	}

	/**
	 * δ���pagejson
	 */
	function c_auditingPageJson(){
		$service = $this->service;

		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//�������˴���
		if(isset($_POST['formName'])){
			$formName = util_jsonUtil :: iconvUTF2GB($_POST['formName']);
			//���ڴ��ڱ�����͹������Ĵ���
			if(isset($service->changeFunArr[$formName])){
				$service->searchArr[$service->changeFunArr[$formName]['seachCode']]= 1;

			//�����Ǳ���������Ĵ���
			}else if(isset($service->urlArr[$formName]['isChange'])){
				$service->searchArr[$service->urlArr[$formName]['seachCode']]= 1;
				$service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
			}
		}else{
			$service->searchArr['inNames'] = $service->rtWorkflowStr_d();
		}

		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('select_auditing');
		//��������������
		$rows = $service->rowsDeal_d($rows);
		//���������������
		$rows = $service->auditInfo_d($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ������б�
	 */
	function c_auditedList(){
		//��ȡѡ��Ĭ��ֵ
		$this->assign('selectedCode',$this->service->getPersonSelectedSetting_d('audited'));
		$this->display('auditedlist');
	}

	/**
	 * �����pagejson
	 */
	function c_auditedPageJson(){
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//�������˴���
		if(isset($_POST['formName'])){
			$formName = util_jsonUtil :: iconvUTF2GB($_POST['formName']);
			//���ڴ��ڱ�����͹������Ĵ���
			if(isset($service->changeFunArr[$formName])){
				$service->searchArr[$service->changeFunArr[$formName]['seachCode']]= 1;

			//�����Ǳ���������Ĵ���
			}else if(isset($service->urlArr[$formName]['isChange'])){
				$service->searchArr[$service->urlArr[$formName]['seachCode']]= 1;
				$service->searchArr['formName'] = $service->urlArr[$formName]['orgCode'];
			}
		}else{
			$service->searchArr['inNames'] = $service->rtWorkflowStr_d();
		}

		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$rows = $service->pageBySqlId('select_audited');
		//��������������
		$rows = $service->rowsDeal_d($rows);
		//���������������
		$rows = $service->auditInfo_d($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ����ʱ�鿴�ĵ���
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
			echo 'δ������ɵ�������������ϵ����Ա��ɶ�Ӧ����';
		}
	}

	/**
	 * �б�鿴����
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
			if(isset($this->service->urlArr[$obj['formName']]['isChange'])&&$_GET['audited']){//�ж��Ƿ�Ϊ�������
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
			echo 'δ������ɵ�������������ϵ����Ա��ɶ�Ӧ����';
		}
	}

	/**
	 * ������ɺ���תҳ��
	 * edit on 2012-03-01
	 * edit by kuangzw
	 */
	function c_toLoca(){
		//��ȡ�����������Ϣ
		$thisFormName = $this->service->getWfInfo_d($_GET['spid']);

		$allStep = isset($this->service->urlArr[$thisFormName['formName']]['allStep']) ? $this->service->urlArr[$thisFormName['formName']]['allStep'] : null;
		if(empty($allStep) && empty($thisFormName['examines'])){
			//����������һ����������û���������в�����õĹ�����,ֱ�ӷ�������ҳ��
			succ_show('?model=common_workflow_workflow&action=auditingList');
		}

		//�����Ӧ���̴��ڱ��������ñ������·��
		if($url = $this->service->inChange_d($_GET['spid'])){
			succ_show($url);
		}else if(isset($this->service->urlArr[$thisFormName['formName']]['rtUrl'])){
			//ֱ�ӵ��������������̷���·��
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
			//��������ڷ���·��,��Ĭ����ת����������ҳ��
			succ_show('?model=common_workflow_workflow&action=auditingList');
		}
	}


	/**
	 * ��ȡ���������� - ������������
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
