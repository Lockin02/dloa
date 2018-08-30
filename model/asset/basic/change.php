<?php

/*
 * Created on 2011-11-16
 *变动方式control层
 * @author chenzb
 */

class model_asset_basic_change extends model_base {

	public $db;
	function __construct() {
		$this->tbl_name = "oa_asset_change";
		$this->sql_map = "asset/basic/changeSql.php";
		parent :: __construct();

	}

	/**
	 * 假删除，当isDel置为1时为假删除
	 *chenzb
	 */
	function deletes_d($ids) {
		try {
			$rows = $this->get_d($ids);
			$cardDao = new model_asset_assetcard_assetcard();
			$relCardInfo = $cardDao->findAll(array (
				'changeTypeCode' => $rows['code']
			));
			if (is_array($relCardInfo) && count($relCardInfo) > 0) {
				throw new Exception();
			} else {
				$isdel = isset ($_GET['isDel']) ? $_GET['isDel'] : null;
				$changeObj = array (
					"id" => $ids,
					"isDel" => "1"
				);
				$this->updateById($changeObj);
			}
			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
	}


	/**
	 * 变动方式导入
	 */
	 function import_d($objKeyArr){
	 	try{
	 		$this->start_d();
	 		$returnFlag = true;
			$service = $this->service;
			$filename = $_FILES ["inputExcel"] ["name"];
			$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
			$fileType = $_FILES ["inputExcel"] ["type"];
			$resultArr = array();
			$objectArr = array();
			$excelData = array ();
			//判断导入类型是否为excel表
			if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
				$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
				spl_autoload_register("__autoload");
				//判断是否传入的是有效数据
//				echo "<pre>";
//				print_R($excelData);
				if ($excelData) {
					foreach ($excelData as $key=>$val){
						//格式化编码，删除多余的空格。如果编码为空，则该条数据插入无效。
						$excelData[$key][0] = str_replace( ' ','',$val[0]);
						$excelData[$key][1] = str_replace( ' ','',$val[1]);
						if( $excelData[$key][0] == '' ){
							unset( $excelData[$key] );
						}
					}
					foreach ( $excelData as $rNum => $row ) {
						foreach ( $objKeyArr as $index => $fieldName ) {
							//将值赋给对应的字段
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
					$rows = $this->list_d();
					$repeatCodeArr = array();
					foreach( $objectArr as $key=>$val ){
						foreach( $rows as $index=>$value ){
							if($val['code'] == $value['code']){
								$repeatCodeArr[$key]['docCode'] = $value['code'];
								$repeatCodeArr[$key]['result'] = '编码重复，导入失败！';
							}elseif($val['name']==''){
								$repeatCodeArr[$key]['docCode'] = $value['code'];
								$repeatCodeArr[$key]['result'] = '名称为空，导入失败！';
							}elseif($val['name']==$value['name']){
								$repeatCodeArr[$key]['docCode'] = $value['code'];
								$repeatCodeArr[$key]['result'] = '名称重复，导入失败！';
							}
						}
						if($val['type'] == '增加'){
							$objectArr[$key]['type']=0;
						}elseif($val['type'] == '减少'){
							$objectArr[$key]['type']=1;
						}
					}
					if( count($repeatCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '变动方式导入结果';
						$thead = array( '编号','结果' );
						echo "<script>alert('导入失败')</script>";
						echo util_excelUtil::showResult($repeatCodeArr,$title,$thead);
					}else{
						$this->saveDelBatch($objectArr);
						echo "<script>alert('导入成功');self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);</script>";
					}
				} else {
					msg( "文件不存在可识别数据!");
				}
			} else {
				msg( "上传文件类型不是EXCEL!");
			}
	 		$this->commit_d();
	 		return $returnFlag;
	 	}catch(Exception $e){
	 		$this->rollBack();
	 		return 0;
	 	}
	}



}
?>
