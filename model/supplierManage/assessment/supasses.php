<?php
/**
 * @author Administrator
 * @Date 2012年1月12日 15:55:18
 * @version 1.0
 * @description:供应商评估 Model层
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

 class model_supplierManage_assessment_supasses  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_suppasses";
		$this->sql_map = "supplierManage/assessment/supassesSql.php";
		parent::__construct ();
	}


	/**
	 * 新建保存评估及明细单
	 */

	function add_d($object,$actType = ""){
		try{
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
//			$object['ExaStatus']='未提交';
			$object['assesState']='1';
			$object['assessTypeName'] =  $datadictDao->getDataNameByCode ( $object['assessType'] );
			$suppCode=$this->get_table_fields('oa_supp_lib','id='.$object['suppId'],'busiCode');
			$object['formCode']=date('YmdHi').$suppCode;
			$object['formDate']=date("Y-m-d");
			$assesYear="";
			$assesQuarter="";
			if($object['assessType']=='gysnd'){
                if($object['assesYear']!=""){
                    $assesYear=$object['assesYear'];
                }else{
                    $assesYear=date("Y")-1;
                }
			}else if($object['assessType']=='gysjd'){
                if($object['assesQuarter']!=""){
                    $assesQuarter=$object['assesQuarter'];
                    $assesYear=$object['assesYear'];
                }else{
                    if(date("n")<4){
                        $assesYear=date("Y")-1;
                        $assesQuarter="4";
                    }else{
                        $assesYear=date("Y");
                        switch(date("n")){
                            case "4":$assesQuarter="1";break;
                            case "5":$assesQuarter="1";break;
                            case "6":$assesQuarter="1";break;
                            case "7":$assesQuarter="2";break;
                            case "8":$assesQuarter="2";break;
                            case "9":$assesQuarter="2";break;
                            case "10":$assesQuarter="3";break;
                            case "11":$assesQuarter="3";break;
                            case "12":$assesQuarter="3";break;
                        }
                    }
                }
			}
			$object['assesYear']=$assesYear;
			$object['assesQuarter']=$assesQuarter;

			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];
			$id=parent::add_d($object,true);

            $assesManId = array();
			//保存明细单
			$assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
			if(is_array($object['assesmentitem'])){
				foreach($object['assesmentitem'] as $key=>$val){
				    if(!in_array($val['assesManId'],$assesManId)){
                        $assesManId[] = $val['assesManId'];
                    }
					$val['parentId']=$id;
					$val['parentCode']=$object['formCode'];
					$assesmentitemDao->add_d($val);
				}
			}


			//保存评估小组人员
			$menberDao=new model_supplierManage_assessment_assessmentmenber();
			if( is_array($object['menber'])){
				$assesManIdArr=explode(",",$object['menber']['assesManId']);
				$assesManNameArr=explode(",",$object['menber']['assesManName']);
				if(is_array($assesManIdArr)){
					foreach($assesManIdArr as $key=>$val){
						$menber['formCode']=$object['formCode'];
						$menber['parentId']=$id;
						$menber['assesManId']=$val;
						$menber['assesManName']=$assesManNameArr[$key];
						$menberDao->add_d($menber);
					}
				}
			}

            if(!empty($assesManId) && $actType == "audit"){
                // 提交后,对所有评分项负责人发送一份邮件通知
                $assesManIds = implode(",",$assesManId);
                $emailDao = new model_common_mail();
                $receiveusers = $assesManIds;
                $emailInfo = $emailDao->toApplySuppasses(1, $_SESSION['USERNAME'],"",$object['formCode'],$object['suppName'],$object['assessTypeName'],$receiveusers);
            }

			//更新附件关联关系
			$this->updateObjWithFile ( $id);

			//附件处理
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			//更新评估任务状态
			if($object['taskId']>0){
				$taskDao=new model_supplierManage_assessment_task();
				$taskDao->accepTask_d($object['taskId'],'2');
			}

			$this->commit_d();
			return $id;

		}catch(Exception $e){
			$this->rollBack();
		}
	}


	/**
  * 新建保存评估及明细单
  */

     function edit_d($object,$actType = ""){
         try{
             $this->start_d();
             $id=parent::edit_d($object,true);
             $assesManId = array();
             //保存明细单
             $assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
             if(is_array($object['assesmentitem'])){
                 foreach($object['assesmentitem'] as $key=>$val){
                     if(!in_array($val['assesManId'],$assesManId)){
                         $assesManId[] = $val['assesManId'];
                     }
                     $val['parentId']=$object['id'];
//					$val['parentCode']=$object['formCode'];
                     $assesmentitemDao->edit_d($val);
                 }
             }

             if(!empty($assesManId) && $actType == "audit"){
                 // 提交后,对所有评分项负责人发送一份邮件通知
                 $assesManIds = implode(",",$assesManId);
                 $emailDao = new model_common_mail();
                 $receiveusers = $assesManIds;
                 $emailInfo = $emailDao->toApplySuppasses(1, $_SESSION['USERNAME'],"",$object['formCode'],$object['suppName'],$object['assessTypeName'],$receiveusers);
             }

             $menberDao=new model_supplierManage_assessment_assessmentmenber();
             //删除旧评估成员
             $deleteCondition=array('parentId'=>$object['id']);
             $menberDao->delete($deleteCondition);

             //保存评估小组人员
             if( is_array($object['menber'])){
                 $assesManIdArr=explode(",",$object['menber']['assesManId']);
                 $assesManNameArr=explode(",",$object['menber']['assesManName']);
                 if(is_array($assesManIdArr)){
                     foreach($assesManIdArr as $key=>$val){
                         $menber['formCode']="";
                         $menber['parentId']=$object['id'];
                         $menber['assesManId']=$val;
                         $menber['assesManName']=$assesManNameArr[$key];
                         $menberDao->add_d($menber);
                     }
                 }
             }

             //更新附件关联关系
             $this->updateObjWithFile ( $id);

             //附件处理
             if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
                 $uploadFile = new model_file_uploadfile_management ();
                 $uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $object['id']);
             }
             $this->commit_d();
             return $id;

         }catch(Exception $e){
             $this->rollBack();
         }
     }

     /**
      * 评估评分处理
      */

     function asses_d($object){
         try{
             $this->start_d();
             //保存明细单
             $assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
             if(is_array($object['assesmentitem'])){
                 foreach($object['assesmentitem'] as $key=>$val){
                     if($val['assesScore']>0){
                         $val['affstate']=1;
                     }
                     //判断是否有变动负责人
//                     if($val['assesManId']){
//
//                     }
                     $assesmentitemDao->edit_d($val);
                 }
             }
             //评估完成标志
             $isDoneFlag=false;
             //判断是否已完成评分
             if($assesmentitemDao->findCount("affstate<>'1' and parentId=".$object['id'])==0){
                //获取评分明细
                $itemRows=$assesmentitemDao->getItemByParentId_d($object['id']);
                 if(is_array($itemRows)){
                        //计算评估总分
                     $sumScore=$assesmentitemDao->getSumscoreByParentId_d($object['id']);
                     //计算评估等级
                     $suppGrade="";
                     if($object['assessType']=="gysjd"||$object['assessType']=="gysnd"){
                        if($sumScore>90){
                            $suppGrade="A";
                        }else if($sumScore==75||$sumScore>75){
                            $suppGrade="B";
                        }else if($sumScore==60||$sumScore>60){
                            $suppGrade="C";
                        }else if($sumScore<60){
                            $suppGrade="D";
                        }
                     }else if($object['assessType']=="xgyspg"){
                         if($sumScore==70||$sumScore>70){
                             $suppGrade="C";
                         }
                     }
                     //更新评估总分及等级
                     $updateArr = array(
                         'id' => $object['id'],
                         'totalNum' => $sumScore,
                         'suppGrade' =>$suppGrade
                     );
                      $this->updateById($updateArr);
                 }
                 $isDoneFlag=true;
             }
             $this->commit_d();
             return $isDoneFlag;
         }catch(Exception $e){
             $this->rollBack();
         }
     }

	/**
	 * 二次评估供应商
	 *
	 */
	function addSecondAss_d ($object) {
		try{
			$this->start_d();

			//更新关联的评估状态
			$arrivaObj=array("id"=>$object['parentId'],
								"assesState"=>"3");
			$this->updateById($arrivaObj);

			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict ();
			$object['ExaStatus']='未提交';
			$object['assesState']='1';
			$object['assessTypeName'] =  $datadictDao->getDataNameByCode ( $object['assessType'] );
			$suppCode=$this->get_table_fields('oa_supp_lib','id='.$object['suppId'],'busiCode');
			$object['formCode']=date('YmdHi').$suppCode;
			$object['formDate']=date("Y-m-d");
			$id=parent::add_d($object,true);
			//保存明细单
			$assesmentitemDao=new model_supplierManage_assessment_assesmentitem();
			if(is_array($object['assesmentitem'])){
				foreach($object['assesmentitem'] as $key=>$val){
					unset($val['id']);
					$val['parentId']=$id;
					$val['parentCode']=$object['formCode'];
					$assesmentitemDao->add_d($val);
				}
			}
			//保存评估小组人员
			$menberDao=new model_supplierManage_assessment_assessmentmenber();
			if( is_array($object['menber'])){
				$assesManIdArr=explode(",",$object['menber']['assesManId']);
				$assesManNameArr=explode(",",$object['menber']['assesManName']);
				if(is_array($assesManIdArr)){
					foreach($assesManIdArr as $key=>$val){
						$menber['formCode']=$object['formCode'];
						$menber['parentId']=$id;
						$menber['assesManId']=$val;
						$menber['assesManName']=$assesManNameArr[$key];
						$menberDao->add_d($menber);
					}
				}
			}

			//更新附件关联关系
			$this->updateObjWithFile ( $id);

			//附件处理
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			$this->commit_d();
			return $id;

		}catch(Exception $e){
			$this->rollBack();
		}

	}
	/**
	 *供应商评估审批后处理方法
	 *
	 */
	 function dealSuppass_d($object){
	 	$flibraryDao=new model_supplierManage_formal_flibrary();
	 	if($object['assessType']=="xgyspg"){//如果是为新供应商评估处理方法
			if($object['isFirst']==1&&$object['totalNum']>70||$object['totalNum']==70){//如果评估结果是合格，则更新供应商的等级为C级
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','C');
			}else if($object['isFirst']==2&&$object['totalNum']>70||$object['totalNum']==70){//二次评估
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','C');
			}else if($object['isFirst']==2&&$object['totalNum']<70){//二次评估
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','D');//如果二次评估不合格，刚将供应商的等级设为不合格
			}
	 	}
	 	if($object['assessType']=="gysnd"){//供应商年度考核
	 		$assesYear=substr($object['formDate'],0,4)-1;//评估年份
	 		//获取供应商季度考核信息
			$searchArr = array (
				"assesYear" => $assesYear,
				"assessType" => "gysnd",
				"suppId"=> $object['suppId'],
				"ExaStatus"=> "完成"
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
			$avgTotalNum=0;//季度考核平均分
			if(is_array($rows)){
				$numb=0;//评估次数
				$sumTotalNum=0;
				foreach($rows as $key=>$val){
					$numb++;
					$sumTotalNum=$sumTotalNum+$val['totalNum'];
				}
				$avgTotalNum=$sumTotalNum/$numb;
			}
			$resultNumb=$avgTotalNum*0.6+$object['totalNum']*0.4;//年度考核最终得分应用
			if($resultNumb>90){
				$yearSupGrade="A";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','A');
			}else if($resultNumb>75){
				$yearSupGrade="B";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','B');
			}else if($resultNumb>60||$resultNumb==60){
				$yearSupGrade="C";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','C');
			}else {
				$yearSupGrade="D";
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','D');
			}
			$asseseArr=array(
				"id"=>$object['id'],
				"yearSupGrade"=>$yearSupGrade,
				"yearTotal"=>$resultNumb,
			);
			$this->edit_d($asseseArr,true);
	 	}
	 	if($object['assessType']=="gysjd"){//供应商年度考核
	 		$assesYear=substr($object['formDate'],0,4);//评估年份
	 		//获取供应商季度考核信息
			$searchArr = array (
				"year" => $assesYear,
				"assessType" => "gysjd",
				"suppId"=> $object['suppId'],
				"ExaStatus"=> "完成"
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
			if(is_array($rows)){
				$numb=0;//评估次数
				foreach($rows as $key=>$val){
					if($val['totalNum']<60){
						$numb++;
					}
				}
				if($numb>2){//季度考核三次及三次以上不合格，从合格库删除
					$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','D');
				}
			}
			if($this->isGradeToB_d($object['suppId'])){//判断新供应商是否连续三次考核上90分晋级为B类
				$flibraryDao->updateField('id='.$object['suppId'],'suppGrade','B');
			}
	 	}

	 }

	 /**判断供应商该季度是否已进行季度考核
	 * @author suxc
	 *
	 */
	  function isAssesQuarter_d($suppId){
	  	$month=date("n");
	  	switch($month){
	  		case "1":$beginDate=date("Y")."-01";$endDate=date("Y")."-03";break;
	  		case "2":$beginDate=date("Y")."-01";$endDate=date("Y")."-03";break;
	  		case "3":$beginDate=date("Y")."-01";$endDate=date("Y")."-03";break;
	  		case "4":$beginDate=date("Y")."-04";$endDate=date("Y")."-06";break;
	  		case "5":$beginDate=date("Y")."-04";$endDate=date("Y")."-06";break;
	  		case "6":$beginDate=date("Y")."-04";$endDate=date("Y")."-06";break;
	  		case "7":$beginDate=date("Y")."-07";$endDate=date("Y")."-09";break;
	  		case "8":$beginDate=date("Y")."-07";$endDate=date("Y")."-09";break;
	  		case "9":$beginDate=date("Y")."-07";$endDate=date("Y")."-09";break;
	  		case "10":$beginDate=date("Y")."-10";$endDate=date("Y")."-12";break;
	  		case "11":$beginDate=date("Y")."-10";$endDate=date("Y")."-12";break;
	  		case "12":$beginDate=date("Y")."-10";$endDate=date("Y")."-12";break;
	  	}
 		//获取供应商季度考核信息
		$searchArr = array (
			"beginDate" => $beginDate,
			"endDate" => $endDate,
			"assessType" => "gysjd",
			"suppId"=>$suppId
		);
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			return 0;
		}else{
			return 1;
		}

	  }

	 /**判断供应商该季度是否已进行年度考核
	 * @author suxc
	 *
	 */
	  function isAssesYear_d($suppId){
	 		$assesYear=date("Y");//评估年份
	 		//获取供应商年度考核信息
			$searchArr = array (
				"year" => $assesYear,
				"assessType" => "gysnd",
				"suppId"=> $suppId
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			return 0;
		}else{
			return 1;
		}

	  }

	 /**判断供应商该季度是否已进行年度考核
	 * @author suxc
	 *
	 */
	  function isAssesNew_d($suppId){
	 		//获取供应商新供应商考核信息
			$searchArr = array (
				"assessType" => "xgyspg",
				"suppId"=> $suppId
			);
			$this->__SET ( 'searchArr', $searchArr );
			$rows = $this->listBySqlId ("select_assesInfo");
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			return 0;
		}else{
			return 1;
		}

	  }

	 /**判断新供应商是否连续三次考核上90分晋级为B类
	 * @author Administrator
	 *
	 */
	 function isGradeToB_d($suppId){
 		//获取供应商供应商考核信息
		$searchArr = array (
			"assessTypeArr" => "gysnd,gysjd",
			"suppId"=> $suppId
		);
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ("select_assesInfo");
		if(is_array($rows)){
			if(count($rows)==3){
				$flag=true;
				foreach($rows as $key=>$val){ //判断新供应商是否连续三次考核上90分晋级为B类
					if($val['totalNum']<90||$val['ExaStatus']!="完成"){
						$flag=false;
						break;
					}
				}
				return $flag;
			}
		}

	 }
    /**删除评估
	*/
	function deletesInfo_d($id) {
		try {
			$this->start_d();
			$row=$this->get_d($id);
			if(is_array($row)&&$row['taskId']>0){//更新评估任务状态
				$taskDao=new model_supplierManage_assessment_task();
				$taskDao->accepTask_d($row['taskId'],'1');

			}

			$this->deletes ($id);
			$this->commit_d();
			return true;
		}catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}

     /**提交评估
      */
     function sumbitAsses_d($id) {
         try {
             $this->start_d();
             $this->updateField(array(
                 "id" =>$id
             ),"ExaStatus","评分中");

             // 提交后,对所有评分项负责人发送一份邮件通知
             $getInfoSql = "select s.id,s.formCode,s.assessTypeName,s.suppName,d.assesMan,d.assesManId from oa_supp_suppasses s left join (SELECT parentId,GROUP_CONCAT(t.assesMan) as assesMan,GROUP_CONCAT(t.assesManId) as assesManId from (select parentId,assesMan,assesManId from oa_supp_suppasses_detail where affstate = 0 group by parentId,assesManId)t group by t.parentId)d on d.parentId = s.id where s.id = {$id}";
             $infoArr = $this->_db->getArray($getInfoSql);
             if($infoArr){
                 $emailDao = new model_common_mail();
                 $receiveusers = $infoArr[0]['assesManId'];
                 $emailInfo = $emailDao->toApplySuppasses(1, $_SESSION['USERNAME'],"",$infoArr[0]['formCode'],$infoArr[0]['suppName'],$infoArr[0]['assessTypeName'],$receiveusers);
             }

             $this->commit_d();
             return true;
         }catch ( Exception $e ) {
             $this->rollBack();
             return false;
         }
     }
     /**
      * 获取含有当前登录人为确认人的评估ID
      */
     function affirmUserInfo($userId){
         $sql = "select parentId from oa_supp_suppasses_detail where find_in_set('".$userId."',assesManId)  group by parentId";
         $arr = $this->_db->getArray($sql);
         $ids='';
         if(is_array($arr)){
             foreach ($arr as $v){
                 $ids .= $v['parentId'].",";
             }
             $ids = rtrim($ids, ',');
         }

         return $ids;
     }

     /**
      * 获取部门领导ID
      */
     function getDeptLeader($parentId){
         $sql = "select group_concat(m.ViceManager) as leaderIds from  oa_supp_suppasses_detail d left join department m on d.assesDeptId=m.DEPT_ID where d.parentId=".$parentId." group by d.parentId";
         $arr = $this->_db->getArray($sql);
         $leaderIds='';
         if(is_array($arr)){
             $leaderIds=$arr[0]['leaderIds'];
         }
         return $leaderIds;
     }

	/**
	 * 导入2012供应商评估信息
	 *
	 */
	 function addFistData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		$userArr = array();//用户数组
		$deptArr = array();//部门数组
		$jobsArr = array();//职位数组
		$otherDataDao = new model_common_otherdatas();//其他信息查询
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//行数组循环
				foreach($excelData as $key => $val){
					if($key <2){
						continue ;
					}
					$actNum = $key - 1;
					if(empty($val[2]) && empty($val[1])){
						continue;
					}else{
						//新增数组
						$inArr = array();


						//员工姓名
						if(!empty($val[1])){
							if(!isset($userArr[$val[0]])){
								$rs=$this->get_table_fields('oa_supp_lib', "busiCode='".$val[1]."'", 'id');
								if(!empty($rs)&&$rs>0){
									$inArr['suppId']=$rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!不存在的供应商编号</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
//							$inArr['userNo'] = $val[0];
							$inArr['formCode']=date('YmdHi').$val[1];
							$inArr['assesYear']='2012';
							$inArr['assesQuarter']='1';
							$inArr['assessType']='gysjd';
							$inArr['assessTypeName']='供应商季度考核';
							$inArr['ExaStatus']='完成';
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商编号为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//供应商名称
						if(!empty($val[2])){
							$val[2] = trim($val[2]);
							$inArr['suppName'] = $val[2];
						}

						//主营产品
						if(!empty($val[3])){
							$inArr['mainProduct'] = $val[3];
						}

						//采购负责人
						if(!empty($val[4])){
							$userIdArr=$otherDataDao->getUserID(trim($val[4]));
							$inArr['assesManId'] = $userIdArr[0]['USER_ID'];
							$inArr['assesManName'] = $val[4];
						}

						//
						if(!empty($val[5])){
						}


						//第一季度评估分数
						if(!empty($val[9])){
							$val[9] = trim($val[9]);
							$inArr['totalNum'] = $val[9];
							if($val[9]>90){
								$inArr['suppGrade'] = 'A';
							}else if($val[9]==75||$val[9]>75){
								$inArr['suppGrade'] ='B';
							}else if($val[9]>60||$val[9]==60){
								$inArr['suppGrade'] ='C';
							}else if($val[9]<60){
								$inArr['suppGrade'] = 'D';
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!第一季度评估分数为空</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}


//						print_r($inArr);
						$newId = parent::add_d($inArr,true);
						if($newId){
							$tempArr['result'] = '导入成功';
						}else{
							$tempArr['result'] = '导入失败';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}

	/**
	 * workflow callback
	 */
	 function workflowCallBack($spid){
	 	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$rows= $this->get_d ( $objId );
			if($rows['ExaStatus']=="完成"){
				//如果审批通过则更新在途数量
				$this->dealSuppass_d($rows);
			}
		}
	 }
}
?>