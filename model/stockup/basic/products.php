<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:05:02
 * @version 1.0
 * @description:������Ʒ��Ϣ�� Model��
 */
 class model_stockup_basic_products  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stockup_products";
		$this->sql_map = "stockup/basic/productsSql.php";
		parent::__construct ();
	}

	function add_d($object) {
		try {
			$this->start_d();
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];
			parent::add_d ( $object,true );
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ��д�༭����
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//�޸�������Ϣ
			parent::edit_d($object,true);

			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}
	/**
	 * ����״̬
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_products SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}

	  /**
		 * @ ajax�ж���
		 *
		 */
	    function ajaxObjName($objName,$objValue) {
	    	if($objName&&$objValue){
	    	   $sql="  SELECT id FROM oa_stockup_products
					WHERE  $objName='$objValue'
				 ";
				$rs = $this->_db->getArray($sql);
		    	if($rs[0]['id']){
	    			$flag=1;
				} else {
					$flag=2;
				}
	    	}
			return $flag;
		}

	/******************************** ��־���벿�� ***********************/
    /**
     * ��Ʒ����
     */
    function eportExcelIn_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();

		//�жϵ��������Ƿ�Ϊexcel��
	if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
//				echo "<pre>";
//				print_r($excelData);
//				die();
				//������ѭ��
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$val[1] = str_replace( ' ','',$val[1]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						$inArr = array();
						//ִ����
						if(!empty($val[0])){
							$val[0] = trim($val[0]);
							if($this->ajaxObjName('productName',$val[0])=='2'){
								$inArr['productName'] = $val[0];
							}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
		                            $tempArr['result'] = '����ʧ��!��Ʒ�����Ѵ��ڣ�';
		                            array_push( $resultArr,$tempArr );
		                            continue;
							}
						}else{
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            $tempArr['result'] = '����ʧ��!û����д��Ʒ���ƣ�';
                            array_push( $resultArr,$tempArr );
                            continue;
						}
//ִ����
						if(!empty($val[1])){
							$val[1] = trim($val[1]);
							if($this->ajaxObjName('productCode',$val[1])=='2'){
								$inArr['productCode'] = $val[1];
							}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
		                            $tempArr['result'] = '����ʧ��!��Ʒ�����Ѵ��ڣ�';
		                            array_push( $resultArr,$tempArr );
		                            continue;
							}
						}else{
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            $tempArr['result'] = '����ʧ��!û����д��Ʒ���룡';
                            array_push( $resultArr,$tempArr );
                            continue;
						}
						//ִ����
						if(!empty($val[2])){
							$val[2] = trim($val[2]);
							if($val[2]=='����'){
								$inArr['isClose'] = 1;
							}elseif($val[2]=='�ر�'){
								$inArr['isClose'] = 2;
							}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
		                            $tempArr['result'] = '����ʧ��!�ر�״̬����';
		                            array_push( $resultArr,$tempArr );
		                            continue;
							}
						}else{
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            $tempArr['result'] = '����ʧ��!û����д״̬����';
                            array_push( $resultArr,$tempArr );
                            continue;
						}
						//ִ����
						if(!empty($val[3])){
							$inArr['remark'] = $val[3];
						}
						//���뿪ʼִ��
						try{
								$this->start_d();
								$newId = $this->add_d($inArr,true);
								$tempArr['result'] = '�����ɹ�';

							$this->commit_d();
						}catch(exception $e){
							$this->rollBack();
							$tempArr['result'] = '����ʧ��';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}
    }

 }
?>