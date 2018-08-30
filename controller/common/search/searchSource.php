<?php
/*
 * Created on 2011-10-29
 * Դ����ѯ���ܣ��������ν���ϲ��²鹦��
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_common_search_searchSource extends controller_base_action {

	function __construct() {
		$this->objName = "searchSource";
		$this->objPath = "common_search";
		parent::__construct ();
	}

	/**
	 * ���鴦��
	 */
	function arrDeal($obj){
		//��ѯ�ѽ�������
		$obj['orgObj'] = isset($obj['orgObj']) ? $obj['orgObj'] : '';


		//�ϲ�id��
		$obj['ids'] = isset($obj['ids']) ? $obj['ids'] : '';



		//�²�id��
		$obj['objId'] = isset($obj['objId']) ? $obj['objId'] : '';
		//���²�����Ӧ�ֶ���
		$obj['sourceType'] = isset($obj['sourceType']) ? $obj['sourceType'] : '';


		return $obj;
	}

	/**
	 * �ϲ���֤����,��֤��ǰ�����Ƿ���Դ�����ͺ�ID
	 */
	function c_checkUp(){
		$obj = $_POST;
		$newClass = $this->service->sourceArr[$_POST['objType']]['thisClass'];
		$newAction = $this->service->sourceArr[$_POST['objType']]['thisUpAction'];
		$classDao = new $newClass();
		$rows = $classDao->$newAction($_POST['objId']);
		if(!empty($rows)){
			echo json_encode($rows);
		}else{
			echo '';
		}
		exit();

	}

	/**
	 *  �ϲ���ʾ����
	 */
	function c_upList(){
		$obj = $this->arrDeal($_GET);
		$this->assignFunc($obj);
		//�ϲ����ؼ���
		$thisUpObj = $this->service->sourceArr[$obj['objType']]['up'][$obj['orgObj']];
		if(empty($thisUpObj)){
			msgRf('δ���õĶ������Ƚ�������');
			exit();
		}
		$this->assign('sourceType',$thisUpObj);

		$this->show->display($this->objPath . '_' .$this->service->sourceArr[$thisUpObj]['pageObj'].'-sourcelist');
	}

	/**
	 * �²���֤����
	 * ����������ҵ�����Ƿ�����ɸö����������ɵ�ҵ��
	 */
	function c_checkDown(){
		$obj = $_POST;
		$rtArr = array();
		//��ȡ�²�Դ����
		$sourceArr = !empty($this->service->sourceArr[$_POST['objType']]['down']) ? $this->service->sourceArr[$_POST['objType']]['down']:'';
		if(!$sourceArr){
			echo '';
			exit();
		}
		//�������д������ݵ�Դ������
		foreach($sourceArr as $key => $val){
			$newClass = $this->service->sourceArr[$key]['thisClass'];
			$newAction = $this->service->sourceArr[$key]['thisDownAction'];
			$classDao = new $newClass();
			if($classDao->$newAction($_POST['objId'],$val)){
				array_push($rtArr,$key);
			}
		}
		if(!empty($rtArr)){
			echo implode($rtArr);
		}else{
			echo '';
		}
		exit();
	}


	/**
	 * �²���ʾ����
	 */
	function c_downList(){
		$obj = $this->arrDeal($_GET);
		$this->assignFunc($obj);
		$objsArr = explode(',',$obj['orgObj']);
		foreach($objsArr as $key => $val){
			if($key == 0){//��ʾ��һ�����ڵ�Դ���б�
				$this->assign('sourceType',$this->service->sourceArr[$obj['objType']]['down'][$val]);
				$this->show->display($this->objPath . '_' .$this->service->sourceArr[$val]['pageObj'].'-sourcelist');
			}
		}
	}
}
?>
