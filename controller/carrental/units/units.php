<?php
/**
 * @author Show
 * @Date 2011��12��25�� ������ 14:36:05
 * @version 1.0
 * @description:�⳵��λ(oa_carrental_units)���Ʋ�
 */
class controller_carrental_units_units extends controller_base_action {

	function __construct() {
		$this->objName = "units";
		$this->objPath = "carrental_units";
		parent::__construct ();
	 }

	/*
	 * ��ת���⳵��λ(oa_carrental_units)�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������⳵��λ(oa_carrental_units)ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('unitNature' => 'DWXZ' ));//ʹ��״̬ -- �����ֵ�
     	$this->view ( 'add' );
   }

   /**
	 * ��ת���༭�⳵��λ(oa_carrental_units)ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
			$this->showDatadicts ( array ('unitNature' => 'DWXZ' ), $obj ['unitNature'] );
		$this->view ( 'edit');
   }

   /**
	 * ��ת���鿴�⳵��λ(oa_carrental_units)ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'unitNature', $this->getDataNameByCode ( $obj ['unitNature'] ));
			$this->view ( 'view' );
   }

   	/**
	 * �鿴ҳ��Tab
	 */
	function c_viewTab() {
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}


	/**
	 *
	 * �ϴ��⳵��λ��ϢEXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	 /**
	 * ����EXCEL���ϴ����鿴�⳵��λ(oa_carrental_units)ҳ��
	 */
	function c_importUnits() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_carrental_units_importUnitsUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importUnitsInfo ( $excelData );

			if (is_array ( $resultArr )){
					$title = '���ⵥλ�������б�';
					$thead = array( '������Ϣ','������' );
					echo util_excelUtil::showResult ( $resultArr,$title,$thead );
				}else{
					echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
				}

		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
 }
?>