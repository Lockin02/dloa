<?php


class controller_rdproject_light_rdlight extends controller_base_action {

	 /**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-10-24 ����11:04:34
	 */
	function __construct () {
		$this->objName = "rdlight";
		$this->objPath = "rdproject_light";
		parent::__construct();
	}
/**
 * @desription ��ת���б�
 * @param tags
 * @date 2010-10-19 ����10:14:02
 */
	function c_tolightlist () {
			$service = $this->service; //����ǰ̨��ȡ�Ĳ�����Ϣ
			$service->getParam ( $_GET );


			$rows = $service->page_d();
			//print_r($rows);
			//��ҳ��ʾ��Ĭ����Ҫ��Htm ��{pageDiv}
			$this->pageShowAssign ();

			$this->show->assign ( 'list', $service->showlist ( $rows ) );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
/**
 * @desription ��ת���޸�ҳ��
 * @param tags
 * @date 2010-10-19 ����08:45:31
 */
	function c_toEdit () {
		$rdlight = $this->service->get_d($_GET['id']);
		if($rdlight[lightcol]=="��ɫ"){
			$yellowlRows=$this->service->find(array('lightcol'=>'��ɫ'));
			$this->assign('max',$yellowlRows['Min']);
			$this->assign('min',"");
		}else if($rdlight[lightcol]=="��ɫ"){
			$greenRows=$this->service->find(array('lightcol'=>'��ɫ'));
			$redRows=$this->service->find(array('lightcol'=>'��ɫ'));
			$this->assign('max',$redRows['Min']);
			$this->assign('min',$greenRows['Max']);

		}else{
			$yellowlRows=$this->service->find(array('lightcol'=>'��ɫ'));
			$this->assign('min',$yellowlRows['Max']);
			$this->assign('max',"99999999999999");
		}
		foreach( $rdlight as $key => $val){
			$this->show->assign( $key, $val);
		}
		$this->show->display($this->objPath.'_'.$this->objName.'-edit');

	}


/**
 * @desription �����޸ĺ����Ϣ
 * @param tags
 * @date 2010-10-19 ����08:50:55
 */
	function c_editlightinfo () {
		$rdlight = $_POST[$this->objName];
		//print_r($rdlight);
		$id = $this->service->edit_d($rdlight,true);
		if ($id){
			msg ( '�޸ĳɹ�' );
		}
	}

	/**
	 * �����Ƿ��ڷ�Χ��
	 */

	function c_ajaxRange(){
		$this->service->searchArr['rangeKey'] = isset($_GET['Max']) ? $_GET['Max'] : $_GET['Min'] ;
		$this->service->searchArr['notId'] = $_GET['notId'];
		$rs = $this->service->listBySqlId();
		if(empty($rs)){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>
