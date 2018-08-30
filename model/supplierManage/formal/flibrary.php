<?php
/**
 * @description: 供应商正式库
 * @date 2010-11-10 下午02:07:59
 * @author oyzx
 * @version V1.0
 */
class model_supplierManage_formal_flibrary extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_lib";
		$this->sql_map = "supplierManage/formal/flibrarySql.php";
		parent::__construct ();

		$this->datadictDao = new model_system_datadict_datadict ();
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (array ("statusEName" => "common", "statusCName" => "正常", "key" => "1" ), array ("statusEName" => "stop", "statusCName" => "禁用", "key" => "2" ) );

	}

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	/**
	 * @desription 我负责的供应商获取数据方法
	 * @param tags
	 * @date 2010-11-16 下午02:22:41
	 */
	function myResSupp($manageUserId) {
		$this->searchArr['manageUserId'] = $manageUserId;
		$arr = $this->pageBySqlId ( 'myResSupp' );
		if(is_array($arr)){
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['status'] = $this->statusDao->statusKtoC ( $arr [$key] ['status'] );
			}
		}
		return $arr;
	}

    /**
     * 根据供应商ID获取信息
     * @param $id
     * @return mixed
     */
    function  getSuppInfoById($id){
        $this->searchArr['id'] = $id;
        $arr = $this->listBySqlId ( 'myResSupp' );
        return $arr[0];
   }
	/**
	 * 根据主键获取对象
	 */
	/**
	 * 获取对象分页列表数组
	 */
	function page_d() {
		$arr = parent::page_d ();
		if(is_array($arr)){
			foreach ( $arr as $key => $val ) {
				$arr [$key] ['status'] = $this->statusDao->statusKtoC ( $arr [$key] ['status'] );
			}

		}
		return $arr;
	}

	/**
	 * @desription 通过供应商ID更新供应商级别
	 * @param tags
	 * @date 2010-11-17 下午06:55:57
	 */
	function changeLevel_d( $id,$level) {
		$rows=$this->get_d($id);
		$rows['suppLevel']=$level;
		$arr=parent::edit_d($rows);
	}

	/**
	 * 在运营库直接添加供
	 */
	function addByExternal_d ($object) {
		try{
			$this->start_d();
			$bankDao = new model_supplierManage_formal_bankinfo();
			$stproductDao=new model_supplierManage_formal_sfproduct();
			$linkmanDao=new model_supplierManage_formal_sfcontact();
			$datadictDao = new model_system_datadict_datadict ();
			$object['status'] = "1";		//默认的供应商状态是正常
			$codeDao=new model_common_codeRule();
			$object['busiCode']=$codeDao->supplierCode($this->tbl_name);
			$object ['suppCategoryName'] =  $datadictDao->getDataNameByCode ( $object['suppCategory'] );
			$object['suppGrade']="E";

			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];
			$id=parent::add_d($object,true);
			//处理附件名称
			$this->updateObjWithFile($id);

			//添加银行信息
			foreach($object['Bank'] as $key => $val){
				if($val['accountNum']!=""){
					$val['suppId']=$id;
					$val['busiCode']=$object['busiCode'];
					$val['suppName']=$object['suppName'];
					$bankDao->addBankInfo_d($val);
				}
			}
			//添加联系人信息
			if(is_array($object['supplinkman'])){
				foreach($object['supplinkman'] as $linkKey=>$linkVal){
					if($linkVal['name']!=""){
						$linkVal['objCode']=generatorSerial();
						$linkVal['systemCode']=generatorSerial();
						$linkVal['parentCode']=$object['objCode'];
						$linkVal['parentId']=$id;
						$linkmanDao->add_d($linkVal,true);
					}
				}
			}
			//添加供应产品信息
			$object['stproduct']['parentCode']=$object['objCode'];
			$object['stproduct']['parentId']=$id;
			$stproductDao->add_d($object['stproduct'],true);

		/*	//添加评估任务
			$task = array (
				"suppId" => $id,
				"formCode" => "T" . date ( "YmdHis" ),//生成任务编号
				"assessType" => 'xgyspg',
				"suppName" => $object['suppName'],
				"assesManId" => $_SESSION ['USER_ID'],
				"assesManName" => $_SESSION ['USERNAME'],
				"formDate" => date("Y-m-d"),
				"purchManId" => $_SESSION ['USER_ID'],
				"purchManName" => $_SESSION ['USERNAME']
			);
			$stampDao = new model_supplierManage_assessment_task();
			$stampDao->add_d($task,true);*/
			$this->commit_d();
			return $id;
		}catch (Exception $e){
			return null;
		}

	}

	/**
	 * @desription 注册库里信息的修改方法。
	 * @param tags
	 * @date 2010-11-18 下午05:19:48
	 */
	function editinfo_d($objinfo) {
		try {
			$this->start_d();
            //记录曾用名信息
            $oldRow=$this->get_d($objinfo['id']);
            if(($oldRow['suppGrade']=='A'||$oldRow['suppGrade']=='B'||$oldRow['suppGrade']=='C')&&trim($objinfo['suppName'])!=trim($oldRow['suppName'])){//只有合格供应商修改才记录
                $usednameDao=new model_supplierManage_formal_usedname();
                $arr['suppId']=$objinfo['id'];
                $arr['busiCode']=$oldRow['busiCode'];
                $arr['suppName']=$objinfo['suppName'];
                $arr['usedName']=$oldRow['suppName'];
                $arr['newName']=$objinfo['suppName'];
                $usednameDao->add_d($arr,true);
            }
			$num = parent :: edit_d($objinfo, true);
			$bankinfo=new model_supplierManage_formal_bankinfo();
			foreach($objinfo[Bank] as $key=>$val){
				$val['busiCode']=$objinfo['busiCode'];
				$val['suppName']=$objinfo['suppName'];

				$bankinfo->addBankUpdate_d($val);
				if(empty($val['id'])){
					$val['suppId']=$objinfo['id'];
				    $bankinfo->addBankInfo_d($val);
				}
			}
			//更新附件关联关系
			$this->updateObjWithFile($objinfo['id']);
			$this->commit_d();
			return $num;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}


	/****************** 导入到处部分 **********************/
	/**
	 * 导入
	 */
	function excelImport_d(){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
//			print_r($excelData);
			if(is_array($excelData)){
				//行数组循环
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])&& empty($val[6]) && empty($val[7]) && empty($val[8]) && empty($val[9])&& empty($val[10]) && empty($val[11])){
						continue;
					}else{
						if(!empty($val[0])){
							$busiCode = $val[0];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '更新失败!没有填写供应商编号';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if(!empty($val[2])){
							$suppGrade = $val[2];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '更新失败!没有填写供应商等级';
							array_push( $resultArr,$tempArr );
							continue;
						}
						$conditionArr = array(
							'busiCode' => $busiCode
						);

						$updateRows = array(
							'suppGrade' => $suppGrade
						);
						$this->update( $conditionArr,$updateRows );
						if ($this->_db->affected_rows () == 0) {
							$tempArr['result'] = '更新成功';
						}else{
							$tempArr['result'] = '更新成功';
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
