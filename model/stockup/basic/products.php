<?php
/**
 * @author Administrator
 * @Date 2013年11月11日 星期一 22:05:02
 * @version 1.0
 * @description:备货产品信息表 Model层
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
	 * 重写编辑方法
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//修改主表信息
			parent::edit_d($object,true);

			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}
	/**
	 * 更新状态
	 */
	function updateObjStatus($id, $statusType,$statusValue) {
		if ($id && $statusType&&$statusValue) {
			$sql = "UPDATE  oa_stockup_products SET $statusType='$statusValue' WHERE id='$id'; ";
			$flag = $this->query($sql);
		}
		return $flag;
	}

	  /**
		 * @ ajax判断项
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

	/******************************** 日志导入部分 ***********************/
    /**
     * 产品导入
     */
    function eportExcelIn_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();

		//判断导入类型是否为excel表
	if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
//				echo "<pre>";
//				print_r($excelData);
//				die();
				//行数组循环
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$val[1] = str_replace( ' ','',$val[1]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1])){
						continue;
					}else{
						$inArr = array();
						//执行名
						if(!empty($val[0])){
							$val[0] = trim($val[0]);
							if($this->ajaxObjName('productName',$val[0])=='2'){
								$inArr['productName'] = $val[0];
							}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
		                            $tempArr['result'] = '导入失败!产品名称已存在！';
		                            array_push( $resultArr,$tempArr );
		                            continue;
							}
						}else{
                            $tempArr['docCode'] = '第' . $actNum .'条数据';
                            $tempArr['result'] = '导入失败!没有填写产品名称！';
                            array_push( $resultArr,$tempArr );
                            continue;
						}
//执行名
						if(!empty($val[1])){
							$val[1] = trim($val[1]);
							if($this->ajaxObjName('productCode',$val[1])=='2'){
								$inArr['productCode'] = $val[1];
							}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
		                            $tempArr['result'] = '导入失败!产品编码已存在！';
		                            array_push( $resultArr,$tempArr );
		                            continue;
							}
						}else{
                            $tempArr['docCode'] = '第' . $actNum .'条数据';
                            $tempArr['result'] = '导入失败!没有填写产品编码！';
                            array_push( $resultArr,$tempArr );
                            continue;
						}
						//执行名
						if(!empty($val[2])){
							$val[2] = trim($val[2]);
							if($val[2]=='开启'){
								$inArr['isClose'] = 1;
							}elseif($val[2]=='关闭'){
								$inArr['isClose'] = 2;
							}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
		                            $tempArr['result'] = '导入失败!关闭状态错误！';
		                            array_push( $resultArr,$tempArr );
		                            continue;
							}
						}else{
                            $tempArr['docCode'] = '第' . $actNum .'条数据';
                            $tempArr['result'] = '导入失败!没有填写状态错误！';
                            array_push( $resultArr,$tempArr );
                            continue;
						}
						//执行名
						if(!empty($val[3])){
							$inArr['remark'] = $val[3];
						}
						//导入开始执行
						try{
								$this->start_d();
								$newId = $this->add_d($inArr,true);
								$tempArr['result'] = '新增成功';

							$this->commit_d();
						}catch(exception $e){
							$this->rollBack();
							$tempArr['result'] = '导入失败';
						}
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
		}
    }

 }
?>