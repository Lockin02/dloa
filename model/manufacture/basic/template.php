<?php
/**
 * @author Michael
 * @Date 2014��7��25�� 15:13:03
 * @version 1.0
 * @description:������Ϣ-���� Model��
 */
include "module/phpExcel/classes/PHPExcel.php";
include "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include "module/phpExcel/Classes/PHPExcel/Cell.php";
include "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
 class model_manufacture_basic_template  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_template";
		$this->tbl_classify = "oa_manufacture_classify";
		$this->tbl_product = "oa_manufacture_template_product";
		$this->sql_map = "manufacture/basic/templateSql.php";
		parent::__construct ();
	}

 	/**
     * ���ṹ���ݼ�
     * @param Int $parentId
     * @return Array
     */
    public function loadTree($parentId=null, $type = '') {

		if($type == 'SF'){
			$conditions = " AND `parent` = ".$parentId;
		}else if($_POST['id']){
			$conditions = " AND `parent` = '".$_POST['id']."'";
		}else{
			$conditions = " AND `parent` = '0'";
		}

		$SQL = "SELECT id,classifyName as text FROM $this->tbl_classify WHERE `classifyName` <> '' ".$conditions;
        $query = $this->_db->query($SQL);

        $treeData = array();
	    while (($rs = $this->_db->fetch_array($query)) != false) {
	        $datas = array();
	        $datas = $this->loadTree($rs['id'],'SF');
	        $state = 'open';
	        $rs['state'] = $state;
	        $rs['children'] = $datas;
	        $treeData[] = $rs;
	    }

        return $treeData;
    }
    public function getTemplateList($id) {

		$SQL = "SELECT * FROM $this->tbl_name WHERE `classifyId` = ".$id;
        $query = $this->_db->query($SQL);
		$datas = array();
	    while (($rs = $this->_db->fetch_array($query)) != false) {
	        $datas[] = $rs;
	    }
        return $datas;
    }

	function get_classify( $id = ''){
		$countsql = empty($id)?'' : ' AND `id` = '.$id;
		$data = $this->_db->getArray ("select * from " . $this->tbl_classify . " WHERE 1 AND 1 " . $countsql);

		return $data;
	}
	function add($obj){

		if(isset($obj['template'])){
			if(isset($obj['template']['id'])){
				unset($obj['template']['id']);
			}
			$id = parent::add_d($obj['template'] ,false);
		}else{
			return false;
		}

		if(isset($id) && !empty($id) && isset($obj['product']['items'])){
			$product = $obj['product']['items'];
			$SQL = "INSERT INTO $this->tbl_product (`templateId`,`productId`,`productType`,`productName`,`productCode`,`pattern`,`unitName`,`num`) VALUES ";
			$VALUES = array();
			foreach($product as $val){
				$VALUES[] ="('".$id."','".$val['productId']."','".$val['proType']."','".$val['productName']."','".$val['productCode']."','".$val['pattern']."','".$val['unitName']."','".$val['num']."')";
			}
			$this->_db->query($SQL.implode(",",$VALUES));
			return true;
		}
		return false;
	}

	function edit($obj){

		if(isset($obj['template'])){

			$obj['template']['updateId'] = $_SESSION ['USER_ID'];
			$obj['template']['updateName'] = $_SESSION['USERNAME'];
			$obj['template']['updateTime'] = date("Y-m-d");

			$mark = parent::edit_d($obj['template'] ,true);
			if($mark){
				$id = $obj['template']['id'];
			}
		}else{
			return false;
		}

		if(isset($id) && !empty($id) && isset($obj['product']['items'])){

			$SQL_P="delete from $this->tbl_product where `templateId`='".$id."'";
			$this->_db->query($SQL_P);
			$product = $obj['product']['items'];
			$SQL = "INSERT INTO $this->tbl_product (`templateId`,`productId`,`productType`,`productName`,`productCode`,`pattern`,`unitName`,`num`) VALUES ";
			foreach($product as $val){
				$VALUES[] ="('".$id."','".$val['productId']."','".$val['proType']."','".$val['productName']."','".$val['productCode']."','".$val['pattern']."','".$val['unitName']."','".$val['num']."')";
			}
			$this->_db->query($SQL.implode(",",$VALUES));
			return true;
		}
		return false;
	}

	function get_template($id){

		$data = $this->_db->getArray ("select * from " . $this->tbl_name . " WHERE `id` = ".$id);
		if(!empty($data) && isset($data[0])){
			return $data[0];
		}else{
			return false;
		}

	}
	function get_product($id){

 		$SQL = "SELECT *,productType as proType FROM $this->tbl_product WHERE `templateId` = ".$id;
        $query = $this->_db->query($SQL);
        $datas = array();
	    while (($rs = $this->_db->fetch_array($query)) != false) {
	        $datas[] = $rs;
	    }

	    return $datas;
	}

	function del($id){
		if(isset($id) && !empty($id)){
			$SQL_T="delete from $this->tbl_name where `id`='".$id."'";
			$this->_db->query($SQL_T);

			if(mysql_affected_rows() > 0){
				$SQL_P="delete from $this->tbl_product where `templateId`='".$id."'";
				$this->_db->query($SQL_P);

				return true;
			}
		}
	    return false;
	}

	public function temImport($file,$filetempname) {

		//�Լ����õ��ϴ��ļ����·��
		$filePath = 'upfile/';
		$filename = explode ( ".", $file ); //���ϴ����ļ����ԡ�.����Ϊ׼��һ�����顣
		$time = date ( "y-m-d-H-i-s" ); //ȥ��ǰ�ϴ���ʱ��
		$filename [0] = $time; //ȡ�ļ����滻
		$name = implode ( ".", $filename ); //�ϴ�����ļ���
		$uploadfile = $filePath . $name; //�ϴ�����ļ�����ַ

		//move_uploaded_file() �������ϴ����ļ��ƶ�����λ�á����ɹ����򷵻� true�����򷵻� false��
		$result = move_uploaded_file ( $filetempname, $uploadfile ); //�����ϴ�����ǰĿ¼��
		if ($result) { //����ϴ��ļ��ɹ�����ִ�е���excel����

			$objReader = PHPExcel_IOFactory::createReaderForFile($uploadfile);
//			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPHPExcel = $objReader->load($uploadfile); //   $objPHPExcel = $objReader->load($uploadfile);
			$sheet = $objPHPExcel->getSheet ( 0 );
			$highestRow = $sheet->getHighestRow (); // ȡ��������
			$highestColumn = $sheet->getHighestColumn (); // ȡ��������
			$dataArr = array (); //��ȡ���

			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString ( $highestColumn );

//			//ѭ����ȡexcel�ļ�,��ȡһ��,��ȡ������һ��
			for($j = 2; $j <= $highestRow; $j ++) {
				$str = "";
				for($k = 0; $k <= $highestColumnIndex; $k ++) {
					$str .= iconv ( 'UTF-8', 'GBK', $sheet->getCellByColumnAndRow ( $k, $j )->getValue () ); //��ȡ��Ԫ��
					if ($k != $highestColumnIndex) {
						$str .= '__';
					}
				}
				$data = array();
				if(str_replace('_', '', $str)){
					$rowData = explode ( "__", $str );
					$data['proType'] 		= $rowData[0];
					$data['productCode']	= $rowData[1];
					$data['productName']	= $rowData[2];
					$data['pattern']		= $rowData[3];
					$data['unitName']		= $rowData[4];
					$data['num']			= $rowData[5];

					array_push ( $dataArr, $data );
				}
			}

			unlink ( $uploadfile ); //ɾ���ϴ���excel�ļ�
			$msg = "��ȡ�ɹ���";
		} else {
			$msg = "��ȡʧ�ܣ�";
		}

		return $dataArr;

    }

    /*   -----------------  ���ຯ��  -----------------  */

    function get_parent( $id = ''){
		$countsql = empty($id)?'' : ' AND `id` = '.$id;
		$data = $this->_db->getArray ("select * from " . $this->tbl_classify . " WHERE 1 AND 1 " . $countsql);

		return $data;
	}

	function classify_add( $object ) {
		$this->tbl_name = "oa_manufacture_classify";
		try {
			$this->start_d();
			$id = parent::add_d($object);

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	function classify_edit( $object ) {
		$this->tbl_name = "oa_manufacture_classify";
		try {

			$this->start_d();

			$id = parent::edit_d($object ,true);

			$this->commit_d();
			return $id;

		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}
}
?>