<?php
/**
 * @author Show
 * @Date 2012年5月28日 星期一 13:38:56
 * @version 1.0
 * @description:人员调用记录 Model层
 */

include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";


 class model_hr_transfer_transfer  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_transfer";
		$this->sql_map = "hr/transfer/transferSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'notsub',
				'statusCName' => '未提交',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'notview',
				'statusCName' => '未审核',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'notcommit',
				'statusCName' => '员工待确认',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'commited',
				'statusCName' => '员工已确认',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'finis',
				'statusCName' => '档案待更新',
				'key' => '4'
			),
			5 => array (
				'statusEName' => 'wait',
				'statusCName' => '档案已更新',
				'key' => '5'
			),
			6 => array (
				'statusEName' => 'done',
				'statusCName' => '完成',
				'key' => '6'
			),
			7 => array (
				'statusEName' => 'yesview',
				'statusCName' => '已审核',
				'key' => '7'
			)
		);
		parent::__construct ();
	}

	//需要数据字典处理的字段
    public $datadictFieldArr = array(
		'transferType'
	);

	/**
	 * 重写add_d
	 */
	function add_d($object){
	 	try{
			$this->start_d();
			$object = $this->processDatadict($object);
			$object['formCode'] = date('YmdHis');
			$object['ExaStatus'] = "未提交";
			$object['employeeOpinion'] = 2;

			$id = parent::add_d($object ,true);

			//如果为员工提交申请，则发邮件通知HR
			if ($object['status'] == '1') {
				$this->mailToHr_d($id);
			}
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 发邮件通知HR
	 */
	function mailToHr_d($id) {
		$object = $this->get_d($id);
		$emailDao = new model_common_mail ();
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = $mailUser['oa_hr_transfer'];
		$mailId = $mailArr['TO_ID'];
		$mailContent = '<span style="color:blue">'.$object['preBelongDeptName'].'</span>部门的<span style="color:blue">'.$object['userName'].'</span>提交了调动申请，单据编号为：<span style="color:blue">'.$object['formCode'].'</span>';
		$emailDao->mailClear("调动申请" ,$mailId ,$mailContent);
	}

	/**
	 * 编辑增员申请信息
	 */
	 function edit_d($object){
	 	try{
			$this->start_d();
			$id = parent::edit_d($object ,true);
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
 	}

	/**
	 * 员工填写意见
	 */
	 function opinionEdit_d($object){
	 	try{
			$this->start_d();
			$object['status']=3;
			$id=parent::edit_d($object,true);
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
 	}

	/**
	 *更新员工档案信息
	 */
	function updatePersonInfo_d($ids){
		try{
			$this->start_d();
			$personDao = new model_hr_personnel_personnel();
			$branchDao = new  model_deptuser_branch_branch();
			if(is_array($ids)){
				$idArr = $ids;
			} else {
				$idArr = explode(',' ,$ids);
			}
			$flag = false;
			if(!empty($idArr)) {
				foreach($idArr as $key => $val) {
					$info = $this->get_d($val);
					$object['id'] = $val;
					$object['status'] = 6;
					$id = parent::edit_d($object ,true);
					$aboutinfo = $branchDao->find(array('ID'=>$info['afterUnitId']));
					if($aboutinfo['type'] == 0){
						$typeinfo = '子公司';
					}else if($aboutinfo['type'] == 1){
						$typeinfo = '集团';
					}
					$flag = $personDao->update(
						array(
							'userNo'=>$info['userNo']
							,'userAccount'=>$info['userAccount']
						),
						array(
							'companyType' => $typeinfo
							,'companyTypeCode' => $aboutinfo['type']
							,'companyName' => $info['afterUnitName']
							,'companyId' => $info['afterUnitId']
							,'deptNameS' => $info['afterDeptNameS']
							,'deptIdS' => $info['afterDeptIdS']
							,'deptCodeS' => $info['afterDeptCodeS']
							,'deptNameT' => $info['afterDeptNameT']
							,'deptIdT' => $info['afterDeptIdT']
							,'deptCodeT' => $info['afterDeptCodeT']
							,'deptNameF' => $info['afterDeptNameF']
							,'deptIdF' => $info['afterDeptIdF']
							,'deptCodeF' => $info['afterDeptCodeF']
							,'deptName' => $info['afterDeptName']
							,'deptId' => $info['afterDeptId']
							,'deptCode' => $info['afterDeptCode']
							,'jobId' => $info['afterJobId']
							,'regionName' => $info['afterUseAreaName']
							,'jobName' => $info['afterJobName']
							,'regionId' => $info['afterUseAreaId']
							,'belongDeptCode' => $info['afterBelongDeptCode']
							,'belongDeptName' => $info['afterBelongDeptName']
							,'belongDeptId' => $info['afterBelongDeptId']
							,'personnelClassName' => $info['afterPersonClass']
							,'personnelClass' => $info['afterPersonClassCode']
						)
					);

					$personnelID = $this->get_table_fields('oa_hr_personnel', "userNo='".$info['userNo']."'", 'id');
					$personDao->updateOldInfo_d($personnelID,'edit');

					//更新固定资产归属信息
					$assetcardDao = new model_asset_assetcard_assetcard();
					$assetcardDao->updateDeptInfo($info['userAccount']);
				}
			}
			$this->commit_d();
			return $flag;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/******************* S 导入导出系列 ************************/
	function addExecelData_d(){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		$userConutArr = array();//用户数组
		$userArr = array();//用户数组
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		$deptDao = new model_deptuser_dept_dept();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");

			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					if($key === 0){
						continue ;
					}
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4])&& empty($val[5])){
						continue;
					} else {
						//新增数组
						$inArr = array();

						//员工编号
						if(!empty($val[0])){
							if(!isset($userArr[$val[0]])) {
								$rs = $otherDataDao->getUserInfoByUserNo($val[0]);
								if(!empty($rs)) {
									$userConutArr[$val[0]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的员工编号</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

							$personDao = new model_hr_personnel_personnel();
							$personObj = $personDao->getPersonnelInfo_d($userConutArr[$val[0]]['USER_ID']);
							$inArr['entryDate'] = $personObj['entryDate'];

							$inArr['userAccount'] = $userConutArr[$val[0]]['USER_ID'];
							$inArr['deptId'] = $userConutArr[$val[0]]['DEPT_ID'];
							$inArr['deptName'] = $userConutArr[$val[0]]['DEPT_NAME'];
							$inArr['userNo'] = $val[0];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工编号</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//员工姓名
						if(!empty($val[1])){
							$inArr['userName'] = $val[1];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有员工姓名</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//调动日期
						if(!empty($val[2])&& $val[2] != '0000-00-00'){
							$val[2] = trim($val[2]);

							if(!is_numeric($val[2])){
								$inArr['applyDate'] = $val[2];
							} else {
								$transferDate = date('Y-m-d',(mktime(0,0,0,1, $val[2] - 1 , 1900)));
								$inArr['applyDate'] = $transferDate;
							}
						}

						//调动类别已弃用

						//公司类型
						if(!empty($val[4])){
							$inArr['preUnitTypeName'] = $val[4];
						}

						//公司名称
						if(!empty($val[5])){
							$inArr['preUnitName'] = $val[5];
						}

						//二级部门
						if(!empty($val[6])){
							if(!isset($deptArr[$val[6]])){
								$rs = $deptDao->getDeptId_d($val[6]);
								if($rs){
									$deptArr[$val[6]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动前二级部门</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preDeptNameS'] = $val[6];
							$inArr['preDeptIdS'] = $deptArr[$val[6]];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有调动前二级部门</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//三级部门
						if(!empty($val[7])){
							if(!isset($deptArr[$val[7]])){
								$rs = $deptDao->getDeptId_d($val[7]);
								if($rs){
									$deptArr[$val[7]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动前三级部门</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preDeptNameT'] = $val[7];
							$inArr['preDeptIdT'] = $deptArr[$val[7]];
							$inArr['preBelongDeptName'] = $val[7];
							$inArr['preBelongDeptId'] = $deptArr[$val[7]];
						} else {
							$inArr['preBelongDeptName'] = $val[6];
							$inArr['preBelongDeptId'] = $deptArr[$val[6]];
						}

						//四级部门
						if(!empty($val[8])){
							if(!isset($deptArr[$val[8]])){
								$rs = $deptDao->getDeptId_d($val[8]);
								if($rs){
									$deptArr[$val[8]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动前四级部门</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preDeptNameF'] = $val[8];
							$inArr['preDeptIdF'] = $deptArr[$val[8]];
							$inArr['preBelongDeptName'] = $val[8];
							$inArr['preBelongDeptId'] = $deptArr[$val[8]];
						}

						//公司类型
						if(!empty($val[9])){
							$inArr['afterUnitTypeName'] = $val[9];
						}

						//公司名称
						if(!empty($val[10])){
							$inArr['afterUnitName'] = $val[10];
							if($val[10] == $val[5]) {
								$inArr['isCompanyChange'] = '0';
							} else {
								$inArr['isCompanyChange'] = '1';
							}
						}

						//二级部门
						if(!empty($val[11])){
							if(!isset($deptArr[$val[11]])){
								$rs = $deptDao->getDeptId_d($val[11]);
								if($rs){
									$deptArr[$val[11]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动后二级部门</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterDeptNameS'] = $val[11];
							$inArr['afterDeptIdS'] = $deptArr[$val[11]];
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<span class="red">导入失败!没有调动后二级部门</span>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//三级部门
						if(!empty($val[12])){
							if(!isset($deptArr[$val[12]])){
								$rs = $deptDao->getDeptId_d($val[12]);
								if($rs){
									$deptArr[$val[12]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动后三级部门</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterDeptNameT'] = $val[12];
							$inArr['afterDeptIdT'] = $deptArr[$val[12]];
							$inArr['afterBelongDeptName'] = $val[12];
							$inArr['afterBelongDeptId'] = $deptArr[$val[12]];
						} else {
							$inArr['afterBelongDeptName'] = $val[11];
							$inArr['afterBelongDeptId'] = $deptArr[$val[11]];
						}

						//四级部门
						if(!empty($val[13])){
							if(!isset($deptArr[$val[13]])){
								$rs = $deptDao->getDeptId_d($val[13]);
								if($rs){
									$deptArr[$val[13]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动后四级部门</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterDeptNameF'] = $val[13];
							$inArr['afterDeptIdF'] = $deptArr[$val[13]];
							$inArr['afterBelongDeptName'] = $val[13];
							$inArr['afterBelongDeptId'] = $deptArr[$val[13]];
						}

						if($inArr['afterBelongDeptName'] == $inArr['preBelongDeptName']){
							$inArr['isDeptChange'] = '0';
						} else {
							$inArr['isDeptChange'] = '1';
						}

						//调动前职位
						if(!empty($val[14])){
							if(!isset($jobsArr[$val[14]])){
								$rs = $otherDataDao->getJobId_d($val[14]);
								if(1){
									$jobsArr[$val[14]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动前员工职位</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['preJobName'] = $val[14];
							$inArr['preJobId'] = $jobsArr[$val[14]];
						}

						//调动后职位
						if(!empty($val[15])){
							if(!isset($jobsArr[$val[15]])){
								$rs = $otherDataDao->getJobId_d($val[15]);
								if(1){
									$jobsArr[$val[15]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的调动后员工职位</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}
							$inArr['afterJobName'] = $val[15];
							$inArr['afterJobId'] = $jobsArr[$val[15]];
							if($val[15] == $val[14]){
								$inArr['isJobChange']='0';
							} else {
								$inArr['isJobChange']='1';
							}
						}

						//调动前区域
						if(!empty($val[16])){
							$branchDao = new model_deptuser_branch_branch();
							$rs = $otherDataDao->getAreaByName($val[16]);
							if(!empty($rs)){
								$branchId = $rs['ID'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的调动前区域</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['preUseAreaName'] = $val[16];
							$inArr['preUseAreaId'] = $branchId;
						}

						//调动后区域
						if(!empty($val[17])){
							$branchDao = new model_deptuser_branch_branch();
							$rs = $otherDataDao->getAreaByName($val[17]);
							if(!empty($rs)){
								$branchId=$rs['ID'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的调动后区域</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['afterUseAreaName'] = $val[17];
							$inArr['afterUseAreaId'] = $branchId;
						}

						if($val[17] == $val[16]) {
							$inArr['isAreaChange'] = '0';
						} else {
							$inArr['isAreaChange'] = '1';
						}

						//调动前人员分类
						if(!empty($val[18])) {
							$rs = $datadictDao->getCodeByName('HRRYFL' ,$val[18]);
							if(!empty($rs)) {
								$inArr['prePersonClassCode'] = $rs;
								$inArr['prePersonClass'] = $val[18];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的调动前人员分类</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//调动后人员分类
						if(!empty($val[19])) {
							$rs = $datadictDao->getCodeByName('HRRYFL' ,$val[19]);
							if(!empty($rs)) {
								$inArr['afterPersonClassCode'] = $rs;
								$inArr['afterPersonClass'] = $val[19];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<span class="red">导入失败!不存在的调动后人员分类</span>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						if($val[19] == $val[18]){
							$inArr['isClassChange'] = '0';
						} else {
							$inArr['isClassChange'] = '1';
						}

						//经办人
						if(!empty($val[20])){
							if(!isset($userArr[$val[20]])){
								$rs = $otherDataDao->getUserInfo($val[20]);
								if(!empty($rs)){
									$userArr[$val[20]] = $rs;
								} else {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的经办人名称</span>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							}

							$inArr['managerId'] = $userArr[$val[20]]['USER_ID'];
							$inArr['managerName'] = $val[20];
						}

						//调动原因
						if(!empty($val[22])){
							$inArr['reason'] = $val[22];
						}

						//备注
						if(!empty($val[23])){
							$inArr['remark'] = $val[23];
						}

						$inArr['ExaStatus'] = AUDITED;
						$inArr['ExaDT'] = date('Y-m-d');
						$inArr['status'] = 6;
						$inArr['formCode'] = date('YmdHis').$inArr['userNo'];
						$inArr['applyDate'] = date('Y-m-d');
						$inArr['employeeOpinion'] = '1';

						if($inArr['isCompanyChange'] == 1)
							$inArr['transferTypeName'].="公司变动  ";
						if($inArr['isAreaChange'] == 1)
							$inArr['transferTypeName'].="区域变动  ";
						if($inArr['isDeptChange'] == 1)
							$inArr['transferTypeName'].="部门变动 ";
						if($inArr['isJobChange'] == 1)
							$inArr['transferTypeName'].="职位变动 ";
						if($inArr['isClassChange'] == 1)
							$inArr['transferTypeName'].="人员分类变动 ";

						$newId = parent::add_d($inArr ,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						} else {
							$tempArr['result'] = '<span class="red">导入失败</span>';
						}
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}


	//导出excel
	function export($rowdatas){
		PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
		//		//创建一个Excel工作流
		//		$objPhpExcelFile = new PHPExcel();


		$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
		$objPhpExcelFile = $objReader->load ( "upfile/人资-人员调动导出模版.xls" ); //读取模板
		//Excel2003及以前的格式
		$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

		//设置保存的文件名称及路径
		$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
		$path = "D:/EXCELDEMO";
		$fileSave = $path . "/" . $fileName;

		//以下是对工作表基本参数的设置
		//设置当前工作表的名称
		$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '信息列表' ) );
		//设置表头及样式 设置
		$i = 3;
		if (! count ( array_filter ( $rowdatas ) ) == 0) {
			$row = $i;
			for($n = 0; $n < count ( $rowdatas ); $n ++) {
				$m = 0;
				foreach ( $rowdatas [$n] as $field => $value ) {
					$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
					$m ++;
				}
				$i ++;
			}
		} else {
			$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'T' . $i );
			for($m = 0; $m < 10; $m ++) {
				$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
				$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '暂无相关信息' ) );
			}
		}

		//到浏览器
		ob_end_clean (); //解决输出到浏览器出现乱码的问题
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		header ( 'Content-Disposition:inline;filename="' . "人员调动导出.xls" . '"' );
		header ( "Content-Transfer-Encoding: binary" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Pragma: no-cache" );
		$objWriter->save ( 'php://output' );
	}
	/******************* E 导入导出系列 ************************/
}
?>