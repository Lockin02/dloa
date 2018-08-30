<?php
/**
 * ���������Ʋ�����࣬���еĿ��Ʋ��඼Ӧ�û������࣬���ڳ�ʼ�������࣬������ҵ���߼����࣬���ÿ��Ʋ�������ҳ������ҲӦ�÷��ڴ�
 * ���ߣ�chengl
 * ����:2010-06-16
 */
class controller_base_action {
	/**
	 * ģ�������
	 */
	protected $show;
	/**
	 * ģ��·��ǰ׺
	 */
	protected $objPath;
	/**
	 * ҵ���߼�������
	 */
	protected $objName;
	/**
	 * ����modelName���ɵ�ҵ���߼�model��
	 */
	protected $service;

	function __construct() {
		//���밲ȫУ����
		$this->sconfig = new model_common_securityUtil ( $this->objName );
		$classname = "model_" . $this->objPath . "_" . $this->objName;
		$this->service = new $classname ();
		$this->show = new show ();
		if(isset($this->lang)){
			$lang=empty($_SESSION['lang'])?"chinese":$_SESSION['lang'];
			$langModel=$this->lang;
			$this->langUtil=new resources_langUtil($lang,$langModel);
			$this->service->langUtil=$this->langUtil;//��serviceҲ��ʹ��
			$commonLangArr=$this->langUtil->commonLangArr;
			$modelLangArr=$this->langUtil->modelLangArr;
			if(is_array($commonLangArr)){
				foreach($commonLangArr as $k=>$v){
					$this->assign ( "common_".$k, $v );//����langǰ׺������������������
				}
			}
			if(is_array($modelLangArr)){
				foreach($modelLangArr as $k=>$v){
					$this->assign ( $langModel."_".$k, $v );//����langǰ׺������������������
				}
			}
		}
	}

	/*
	 * Ĭ�ϵ��б���ת����
	 */
	function c_list() {
		$this->display ( 'list' );
	}

	/**
	 *Ĭ��action��ת����
	 */
	function c_index() {
		$this->c_page ();
	}
	/**
	 * ��ʾ�����ҳ�б�
	 */
	function c_page() {
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		//��ҳ
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $service->count ) );

		$this->assign ( 'list', $service->showlist ( $rows, $showpage ) );
		$this->display ( 'list' );
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	* ajax��ȡҵ�������Ϣ
	*/
	function c_getByAjax(){
		$obj = $this->service->get_d ( $_REQUEST ['id'] );
		echo util_jsonUtil::encode ( $obj );
	}

	/**
	* ����������ȡҵ���������
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
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->display ('add',true);
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
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
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //��ȫУ��
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '�༭�ɹ���' );
		}
	}

	/**
	 * ����ɾ������
	 */
	function c_deletes() {
		//$this->permDelCheck ();
		$message = "";
		try {
			$this->service->deletes_d ( $_GET ['id'] );
			$message = '<div style="color:red" align="center">ɾ���ɹ�!</div>';

		} catch ( Exception $e ) {
			$message = '<div style="color:red" align="center">ɾ��ʧ�ܣ��ö�������Ѿ�������!</div>';
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
	 * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
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
	 * ����������������������������id�����Ƿ���ֵ�Զ��������������޸Ĳ���
	 */
	function c_saveBatch($isAddInfo = false) {
		$objs = $_POST [$this->objName];
		$objs = util_jsonUtil::iconvUTF2GBArr ( $objs ); //��Ҫ��ǰ̨�ύajax����ת��
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

	/*****************************����Ϊ���õ���ɾ�ķ���**************************************************/

	/**
	 * �������Ƿ��ظ�
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
			//����֤���
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
	 * ����code�����ȡ�����ֵ���
	 * ���������keyΪҳ�������õ�ģ���ַ�����valueΪ��̨���õĸ���code
	 */
	function getDatadicts($parentCodeArr,$conditionArr = null) {
		if (is_array ( $parentCodeArr )) {
			$parentCodes = implode ( ",", $parentCodeArr );
			//�����ϼ������ȡ�����ֵ���Ϣ
			$datadictDao = new model_system_datadict_datadict ();
			$datadictArr = $datadictDao->getDatadictsByParentCodes ( $parentCodes ,$conditionArr);
			return $datadictArr;
		}
	}

	/*
	 * ����ҳ�������ֵ���
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
	 * ����ҳ�������ֵ���(option�� value ��text ���������ֵ��name)
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
	 * ���������ֵ�����ȡ�����ֵ�����
	 */
	function getDataNameByCode($code) {
		if (! isset ( $this->datadictDao )) {
			$this->datadictDao = new model_system_datadict_datadict ();
		}
		return $this->datadictDao->getDataNameByCode ( $code );
	}

	/*
	 * �����Զ�������ѡ��
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
	 * �󵼺���
	 * numb��1��ʼ����
	 * ע��$arrayPanel = array("topName"=>"�󵼺�","numb"=>0,"name1"=>"xx","url1"=>"xx","name2"=>"xxx","url2"="xxx")
	 * TODO:��չ
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
			//���ӣ�
			$arrayPanel = array ("topName" => "���ǵ�����", "numb" => 2, "name1" => "����1", "url1" => "#", "name2" => "����2", "url2" => "#" );
			$this->leftPlan ( $arrayPanel );

		}
	}

	/**
	 * ͷ������
	 * numb��1��ʼ����
	 * clickNumb Ĭ��ѡ���ĸ�
	 * TODO:��չ
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
			//���ӣ�
			$arrayPanel = array ("numb" => 2, "clickNumb" => 2, "name1" => "ͷ��1", "title1" => "ͷ��11", "url1" => "#", "name2" => "ͷ��2", "title2" => "ͷ��22", "url2" => "#" );
			$this->topPlan ( $arrayPanel );
		}
	}

	/**
	 * ����ֱ��ת��action��assign
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
	 * @exclude ��ʾ�����鿴�б�
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-12 ����07:33:45
	 */
	function showExa($arrExa) {
		if ($arrExa) {
			$str = <<<EOT
	<tr align="center" >
      <td colspan="6" style="padding-left:0px;padding-right:0px;" >
        <table border="0" cellspacing="0" cellpadding="0" class="table" width="100%"  align="center">
                <tr align="center" class="tablecontrol" >
                    <td width="100%" align="center" colspan="6" style="font-size:14px;" height="35"><B>�������</B></td>
                </tr>
                    <tr align="center" class="TableLine2" style="color:blue;">
                        <td width="20%">������</td>
                        <td width="10%">������</td>
                        <td width="20%">��������</td>
                        <td width="9%">�������</td>
                        <td width="27%">�������</td>
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
							$str .= "<font color='green'>ͬ��</font>";
						} elseif (isset ( $childVal ['Result'] ) && $childVal ['Result'] == 'no') {
							$str .= "<font color='red'>��ͬ��</font>";
						} else {
							$str .= 'δ����';
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
					$str .= "<td align='center'>&nbsp;δ����</td><td>&nbsp;</td></tr>";
				}

			}
			$str .= "</table></td></tr>";
		} else {
			$str = "";
		}
		return $str;
	}

	/**
	 * @desription ��ҳ��ʾ����
	 * @param $div ���滻����
	 * @date 2010-9-17 ����09:41:48
	 */
	function pageShowAssign($isPost = false, $div = 'pageDiv') {
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => $this->service->count ) );
		$this->show->assign ( $div, $showpage->pageDiv ( $isPost ) );
	}

	/**
	 * @desription ����Ψһ����Ŀҵ����
	 * @return return_type
	 * @date 2010-9-26 ����02:57:14
	 */
	function businessCode() {
		return $this->objName . date ( "YmdHis" ) . rand ( 10, 99 );
	}

	/**
	 * @desription ����ǰͳһ���÷���
	 * @param $object ��������
	 */
	function beforeMethod($object) {
		$this->beforeTag = true;
		if (! isset ( $this->operLog ))
			$this->operLog = "";

		//�õ����������ݿ��ԭ��¼
		$oldObj = $this->service->get_d ( $object ['id'] );
		$result = array_diff_assoc ( $oldObj, $object ); //�ó���ֵ
		$datadictDao = new model_system_datadict_datadict ();
		if (is_array ( $this->operArr )) {
			foreach ( $this->operArr as $key => $value ) {
				if (isset ( $result [$key] )) { //�ж��Ƿ��Ǽ���ֶ�(���������������Ҫ��ص��ֶ�)
					$oldValue = $oldObj [$key];
					$newValue = $object [$key];
					if (is_array ( $this->service->datadictFieldArr )) { //�����ֵ��ֶδ���
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
	 * @desription �����ɹ���ͳһ���÷���
	 * @param $object ��������
	 * @param $type ��������,Ĭ��operΪ������changeΪ���
	 */
	function behindMethod($object, $type = "oper") {
		if (is_array ( $type )) { //֧�ֶ������Ͳ������Ժ���չ


		} else {
			//������¼
			if ($type == "oper") {
				$this->operationLog ( $object );
			}
			//�����¼
			if ($type == "change") {
				$this->changeLog ( $object );
			}
		}
		//����������չ
	}

	/*
	 * @desription ������¼
	 * @param $object ��������
	 */
	private function operationLog($object) {
		$operation = array ();
		$operation ['objTable'] = $this->service->tbl_name;
		$operation ['objId'] = $object ['id'];
		$operation ['operateType'] = $object ['operType_']; //����������Ϊ��������Դ������������»��߱��������ҵ�����Գ�ͻ
		//���û��before��־����operLog��Ϊ�գ�����Ҫ���������¼�������ж���Ҫ����޸ĵ�ʱ��û���޸��ֶ�Ҳ���������¼���⣬��beforeMenthod������������beforeTag��־��
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
	 * @desription �����¼
	 * @param $object ��������
	 */
	private function changeLog($object) {
		$change = array ();
		$change ['objTable'] = $this->service->tbl_name;
		$change ['objId'] = $object ['id'];
		if (! empty ( $object ['changeReason'] )) {
			$change ['changeReason'] = $object ['changeReason']; //������ԭ������
		}
		if (! empty ( $this->operLog )) {
			$change ['changeLog'] = $this->operLog;
		}
		$changeDao = new model_log_change_change ();
		$changeDao->add_d ( $change );
	}

	/*
	 * �ж��Ƿ���ִ��
	 */
	function isCurUserPerm($projectId, $permCode) {
		//�ж��Ƿ�����Ŀ�����˻�����Ŀ�����ǲ�����Ȩ���ж�
		$projectDao = new model_rdproject_project_rdproject ();
		$project = $projectDao->get_d ( $projectId );
		$curUserId = $_SESSION ['USER_ID'];
		$pos = strpos ( $project ['assistantId'], $curUserId . "," );
		if ($pos != - 1 && $project ['managerId'] != $curUserId && $project ['createId'] != $curUserId) {
			$roleDao = new model_rdproject_role_rdrole ();
			if (! $roleDao->isCurUserPerm ( $projectId, $permCode )) {
				msg ( "û��ִ�д˲���Ȩ�ޣ�����ϵ����Ա��" );
				exit ();
			}
		}
	}

	/**
	 * ����Model����
	 */
	function c_model() {
		$this->service->rmMilespointEnd_d ( "94", "220", "2010-09-08" );
	}

	/**
	 * �ϴ�EXCEl������������
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
					echo "<b>����ɹ�!</b>";
				else
					echo "����ʧ��,�������ϴ�!";
			} else {
				echo "�ļ������ڿ�ʶ������!";
			}
		} else {
			echo "�ϴ��ļ����Ͳ���EXCEL!";
		}
	}

	/**
	 * @desription �������
	 * @param tags
	 * @date 2010-11-18 ����04:20:44
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
	 * ����ģ�����
	 */
	function assign($key, $value) {
		if($value === '0000-00-00' || $value === 'NULL'|| $value === '0000-00-00 00:00:00'){
			$value = '';
		}
		$this->show->assign ( $key, $value );
	}

	/**
	 * ��תҳ���װ
	 */
	function display($result,$needSubmit = false) {
		if (! empty ( $_GET ['skey'] )) {
			$this->show->assign ( "skey_", $_GET ['skey'] );
		}
		$needSubmit ? $this->createSubmitTag() : $this->assign('submitTag_','');//�����ظ��ύУ��
		$this->show->display ( $this->objPath . '_' . $this->objName . '-' . $result );
	}

	/**
	 * ��תҳ���װ
	 */
	function displayPT($result,$datas,$needSubmit = false) {
		if (! empty ( $_GET ['skey'] )) {
			$this->show->assign ( "skey_", $_GET ['skey'] );
		}
		$needSubmit ? $this->createSubmitTag() : $this->assign('submitTag_','');//�����ظ��ύУ��
		$this->show->displayPT ( $this->objPath . '_' . $this->objName . '-' . $result,$datas);
	}

	/*
	 * ��ģ����ʾ���������빫�õ�js��css
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
		$needSubmit ? $this->createSubmitTag() : $this->assign('submitTag_','');//�����ظ��ύУ��
		$this->show->display ( $this->objPath . '_' . $this->objName . '-' . $result );
	}

	/**
	 * ���԰�ģ���滻
	 */
	function assignLang(){
		//���԰�����
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
	 * foreachѭ��...
	 */
	function assignFunc($object) {
		foreach ( $object as $key => $val ) {
			$this->assign ( $key, $val );
		}
	}

	/**
	 * ˽�з��� ��ȡҵ��ģ���¼
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
		//����Ϊ�˷�ֹget_d����д���µ�Ч�����⣬ֱ�ӵ��õײ�find����
		$condition = array ("id" => $id );
		return $service->find ( $condition );
	}

	/**
	 * url��ȫ����,����һ��ҵ���¼�����ؼ��ܺ��ܳ�
	 * @param  $id ҵ�����id
	 * @param  $code ҵ�������� ��������actionҵ�����
	 * @param  $keyField �����ֶ� �������������ļ�
	 */
	function md5Row($id, $code, $keyField) {
		$row = $this->getModelRow ( $id, $code );
		$key = $this->sconfig->md5Row ( $row, $keyField, $code );
		return $key;
	}

	/**
	 * ajax url��ȫ����,����һ��ҵ���¼�����ؼ��ܺ��ܳ�
	 * @param  $id ҵ�����id
	 * @param  $code ҵ�������� ��������actionҵ�����
	 * @param  $keyField �����ֶ� �������������ļ�
	 */
	function c_md5RowAjax() {
		$key = $this->md5Row ( $_POST['id'], $_POST['code'], $_POST['keyField'] );
		echo $key;
	}

	/**
	 * url��ȫУ��
	 * @param  $id ҵ�����id
	 * @param  $code ҵ��������
	 */
	function permCheck($id = false, $code = false) {
		//return true;//�ȷſ�Ȩ��
		$row = $this->getModelRow ( $id, $code );
		$hasPerm = $this->sconfig->permCheck ( $row, $_GET ['skey'] );
		if (! $hasPerm) {
			msg ( "�Ƿ�����." );
			//echo ( "û�з���Ȩ��." );
			exit ();
		}
	}
	/**
	 * ɾ��Ȩ��У��
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
	 * ���һ�����ڵ�
	 */
	function addRoot($rows,$textkey='text',$textName='ȫѡ'){
		if($_GET['addRoot']==1){
			return array(array(id=>"root",$textkey=>$textName,isParent=>1,nodes=>$rows));
		}
		return $rows;
	}

	/**
	 * ����key��ȡ���԰�
	 */
	function getLangByKey($key){
		return $this->langUtil->getLangByKey($key);
	}

	/**
	 * �����ύΨһ��ʶ
	 */
	function createSubmitTag(){
		$_SESSION['submitTag'] = $this->service->uuid();
		$this->assign ( "submitTag_", $_SESSION['submitTag'] );
	}

	/**
	 * �����Ƿ��ظ��ύ
	 */
	function checkSubmit(){
		$tag=$_POST['submitTag_'];
		if(!empty($tag)){
			if($_SESSION['submitTag']!=$tag){
				msg("�벻Ҫ�ظ��ύ��.");
				throw new Exception("�벻Ҫ�ظ��ύ��.");
			}else{
				$_SESSION['submitTag']=$this->service->uuid();
			}
		}
	}
}