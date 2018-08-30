<?php
/**
 * @author Administrator
 * @Date 2011年11月16日 14:35:53
 * @version 1.0
 * @description:固定资产卡片 Model层
 */
 class model_asset_assetcard_assetcard  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_card";
		$this->sql_map = "asset/assetcard/assetcardSql.php";
		parent::__construct ();
	}

	/**
	 * 获取基础数据（资产分类、使用状态、变动方式、折旧方式）
	 * @author zengzx
	 * @since 1.0 - 2011-11-19
	 */
	function getBaseDate_d(){
		//获取资产分类信息
		$directoryDao = new model_asset_basic_directory();
		$dirFields = "id,name";
		$dirInfo = $directoryDao->findAll(null,null,$dirFields);
		$dirOption = $this->setSelectOption($dirInfo);
		//获取变动方式信息
		$changeDao = new model_asset_basic_change();
		$directoryDao->searchArr['isSysType'] = 0;
		$chnCondition = array('isDel'=>0,'isSysType'=>0);
		$chnFields = "code as id,name";
		$chnInfo = $changeDao->findAll($chnCondition,null,$chnFields);
		$chnOption = $this->setSelectOption($chnInfo);
//		//获取使用状态信息
//		$useStatusDao = new model_asset_basic_useStatus();
//		$useFields = "code as id,name";
//		$useInfo = $useStatusDao->findAll(null,null,$useFields);
//		$useOption = $this->setSelectOption($useInfo);
		//获取折旧方式信息
		$deprMethodDao = new model_asset_basic_deprMethod();
		$deprFields = "code as id,name";
		$deprInfo = $deprMethodDao->findAll(null,null,$deprFields);
		$deprOption = $this->setSelectOption($deprInfo);
		return array(
			'dirOption'=>$dirOption,
			'chnOption'=>$chnOption,
//			'useOption'=>$useOption,
			'deprOption'=>$deprOption,
		);//返回资产类型、变动方式、折旧方式的下拉选项
	}

	/**
	 * 动态拼装select项
	 * @author zengzx
	 * @since 1.0 - 2011-11-21
	 */
	function setSelectOption($info){
		$str = '';
		foreach($info as $key=>$val){
			$str .= "<option value=".$val['id'].">".$val['name']."</option>";
		}
		return $str;
	}

	/**
	 * 资产变动
	 */
	function change_d($object){
		try{
			$this->start_d();
			
			//所属二级部门
			$rs = $this->getParentDept_d($object['orgId']);
			$object['parentOrgId'] = $rs[0]['parentId'];
			$object['parentOrgName'] = $rs[0]['parentName'];
			//使用二级部门
			$rs = $this->getParentDept_d($object['useOrgId']);
			$object['parentUseOrgId'] = $rs[0]['parentId'];
			$object['parentUseOrgName'] = $rs[0]['parentName'];
			$cardOldObj = $this->get_d($object['oldId']);
			$cardObj = $cardOldObj;
			unset($cardOldObj['id']);
			//生成旧的卡片记录
			$cardOldObj['isTemp']=1;
			$newId = parent::add_d($cardOldObj,true);
			foreach ( $cardObj as $key=>$val ){
				if( isset($object[$key]) ){
					$cardObj[$key] = $object[$key];
				}
			}
			//更新当前卡片信息
			$id = parent::edit_d($cardObj,true);
			//创建卡片变动记录
			$changeDao = new model_asset_change_assetchange();
			$changeInfo = array(
				'alterDate' => day_date,
				'assetId' => $object['oldId'],
				'oldAssetId' => $newId,
				'businessType' => $object['changeTypeCode'],
				'assetCode' => $object['assetCode'],
			);
			$changeDao->addRecord_d($changeInfo);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 资产变动 -- 业务操作导致变动
	 */
	function changeByObj_d($object,$paramArr){
		try{
			$this->start_d();
			$changeTypeDao = new model_asset_basic_change();
			//获取变动方式对应的名称
			$changeTypeObj = $changeTypeDao->get_d($paramArr['changeId']);
			$paramArr['changeName']=$changeTypeObj['name'];
			$object['changeTypeCode']=$paramArr['changeCode'];
			$object['changeTypeName']=$paramArr['changeName'];

			$cardOldObj = $this->get_d($object['oldId']);
			$cardObj = $cardOldObj;
			//生成旧的卡片记录
			$cardOldObj['isTemp']=1;
			unset($cardOldObj['id']);
			$newId = parent::add_d($cardOldObj,true);
			//将变动的数据替换原有的卡片记录数据
			foreach ( $cardObj as $key=>$val ){
				if( isset($object[$key]) ){
					if( $key == 'origina' ){
						$cardObj[$key] += $object[$key];
					}else{
						$cardObj[$key] = $object[$key];
					}
				}
			}
			$cardObj['version'] = $cardObj['version']*1+1;
			//领用签收时，更新卡片开始使用日期
			if($paramArr['changeCode'] == 'charge'){
				$cardObj['beginTime'] = day_date;
			}
			//更新当前卡片信息
			$id = $this->updateById ( $cardObj );
			//创建卡片变动记录
			$changeDao = new model_asset_change_assetchange();
			$changeInfo = array(
				'alterDate' => day_date,
				'assetId' => $object['oldId'],
				'oldAssetId' => $newId,
				'businessType' => $paramArr['changeCode'],
				'businessId' => $paramArr['businessId'],
				'businessCode' => $paramArr['businessCode'],
				'assetCode' => $object['assetCode'],
			);
			$changeDao->addRecord_d($changeInfo);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据ID和条件获取卡片信息
	 */
	function getCardsByIdArr($idArr,$condition=false){
		$idStr = implode(',',$idArr);
		if(is_array($condition)&&count($condition)>0){
			$this->searchArr=$condition;
		}
		$this->searchArr['ids'] = $idStr;
		$rows = $this->list_d();
		return $rows;
	}

	/**
	 * 添加对象
	 * @linzx
	 */
	function add_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			//新增一个卡片时自动给卡片表的isSell赋值
			//$object['isSell']="0";
			//获取到页面所有的值，存在$object数组里
			$object = $this->addCreateInfo ( $object );
			//根据资产分类id获取资产分类code
       		$direCode =new model_asset_basic_directory();
		    $code= $direCode-> getCodeById_d($object['assetTypeId']);
			//自动生成资产编码
           	$codeDao = new model_common_codeRule ();
			$object ['assetCode'] = $codeDao->assetcardCode2 ( "oa_asset_assetcard", $object['property'] ,$object['orgName'],$object['assetabbrev'],$code['code'],$object['buyDate']);

		}
		//加入数据字典处理
		$newId = $this->create ( $object );
		//echo $newId;
		return $newId;

	}

	/**
	 * 添加对象  --  导入
	 * @zengzx
	 * 2012年7月9日 11:06:18
	 */
	function importAdd_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			//新增一个卡片时自动给卡片表的isSell赋值
			//$object['isSell']="0";
			//获取到页面所有的值，存在$object数组里
			$object = $this->addCreateInfo ( $object );
			//根据资产分类id获取资产分类code
			//自动生成资产编码
		}
		//加入数据字典处理
		$newId = $this->create ( $object );
		//echo $newId;
		return $newId;

	}
	
	/**
	 * 添加对象
	 * @zengzx
	 */
	function addBeach_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$cardArr = array();
		$assetCode = $object ['assetCode'];
		if(isset($object['machineCode']) && !empty($object['machineCode'])){//存在机器码
			//构造机器码数组
			$machineCodeArr = explode(",",$object['machineCode']);
			foreach ($machineCodeArr as $key => $val){
				$object['machineCode'] = $val;
				$i = $key+1;
				switch(strlen($i)){
					case 1:$number="00".$i;break;
					case 2:$number="0".$i;break;
					case 3:$number="".$i;break;
				}
				$object ['assetCode'] = $assetCode.$number;
				$cardArr[] = $object;
			}
		}else{
			for ( $i=1;$i<=$object['number'];$i++ ){
				switch(strlen($i)){
					case 1:$number="00".$i;break;
					case 2:$number="0".$i;break;
					case 3:$number="".$i;break;
				}
				$object ['assetCode'] = $assetCode.$number;
				$cardArr[] = $object;
			}
		}
		return $this->createBatch($cardArr);
	}
	/**
	 * 批量更新卡片信息
	 */
	 function updateBeach_d($obj){
		$condition = array ("templeId" => $obj ['tempId'] );
		return $this->update ( $condition, $obj );
	 }

	//根据卡片的id获取修改前卡片的数据

	function getOldCardById_d($id){
		$OldCardObj = $this->get_d($id);
		return $OldCardObj;

	}

	/**
	 * 根据主键修改对象
	 * 编辑对象
	 * @linzx
	 */
	function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		//根据资产分类id获取资产分类code --取消根据资产类别更新资产编号 By Bingo 2015.8.31
// 		$direCode =new model_asset_basic_directory();
// 		$code= $direCode-> getCodeById_d($object['assetTypeId']);

		$OldCardObj=$this->getOldCardById_d($object['id']);

//      $thisByDate = date('Ym',strtotime($object['buyDate']));
// 		$afterNum = substr( $object ['assetCode'],-6 );
// 		$object ['assetCode'] = $object['companyCode'].$thisByDate.$code['code'].$afterNum;
		//所属二级部门
		$rs = $this->getParentDept_d($object['orgId']);
		$object['parentOrgId'] = $rs[0]['parentId'];
		$object['parentOrgName'] = $rs[0]['parentName'];
		//使用二级部门
		$rs = $this->getParentDept_d($object['useOrgId']);
		$object['parentUseOrgId'] = $rs[0]['parentId'];
		$object['parentUseOrgName'] = $rs[0]['parentName'];

		return $this->updateById ( $object );
	}

	function addCard_d($object){
		$this->addBeach_d($object, true);
		$receiveItem = new model_asset_purchase_receive_receiveItem();
		$receiveItem->changeIsCard($object['receiveItemId']);

	}

	function isRelated_d($assetId){
		$alterDao = new model_asset_change_assetchange();
		$alterDao->pageSize=1;
		$alterDao->asc=true;
		$alterDao->searchArr['assetId']=$assetId;
		$rows = $alterDao->page_d();
		if(is_array($rows)&&$rows[0]['businessId']!=''){
			echo 0;
		}else{
			echo 1;
		}
	}

	/**
	 * 新增折旧数据时，更新资产累计折旧值和资产净值
	 */
	function updateDepreciation($equId,$origina,$localNetValue,$period){
			$sql = " update ".$this->tbl_name." set depreciation=($origina - $localNetValue),netValue=$localNetValue,alreadyDay=$period where id=$equId ";
			//echo $sql;
			return $this->query($sql);
	}

	/**
	 * 删除折旧数据时，更新资产累计折旧值和资产净值
	 */
	function updateDepreciationReturn($equId,$depreciation,$lastDepreciation=false){
		if(isset($lastDepreciation)&&$depreciation==$lastDepreciation){
			return true;
		}else{
			if($lastDepreciation){
				$sql = " update ".$this->tbl_name." set depreciation=(ifnull(depreciation,0) - $depreciation - $lastDepreciation),netValue=(ifnull(netValue,0) + $depreciation - $lastDepreciation) where id=$equId ";
			}else{
				$sql = " update ".$this->tbl_name." set depreciation=(ifnull(depreciation,0) - $depreciation),netValue=(ifnull(netValue,0) + $depreciation) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}
	
	/**
	 * @author chenzb
	 *@param $isTemp 变动
	 *@param $isDel 是否清除
	 *根据 $isTemp $isDel 来获取卡片信息
	 */
	function getChildren_d($isTemp,$isDel){
		$this->searchArr['isTemp'] = $isTemp;
		$this->searchArr['isDel'] =$isDel;
//			$this->asc = false;
		$rows=$this->listBySqlId('select_default');
		return $rows;
	}

	/**
	 * 获取卡片信息
	 */
	function getCards(){


		//获取指定日期所在月的第一天和获取指定日期下个月的第一天
		$date=date('Y-m-d');
		$arr = getdate();
		if($arr['mon'] == 12){
			$year = $arr['year'] +1;
			$month = $arr['mon'] -11;
	  		$day = $arr['mday'];
	   		if($day < 10){
	    		$mday = '0'.$day;
	   		}else {
	   			 $mday = $day;
	   		}
	   		$nextfirstday = $year.'-0'.$month.'-01';
	  	}else{
	   		$time=strtotime($date);
	   		$nextfirstday=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)+1).'-01'));
	  	}
	  	$firstday = date("Y-m-01",strtotime($date));

	  	// 控制资产入账时间的当月，不能折旧；清理的资产不能折旧，折旧完的资产不能折旧;过滤变动记录
		$sql = "select * from oa_asset_card c where c.wirteDate>='$nextfirstday' or c.wirteDate<'$firstday' and c.isDel=0 and c.isTemp=0 and c.netValue>c.salvage";
        $rows = $this->_db->getArray($sql);
		return $rows;
	}
	
	/**
	 * 匹配excel字段
	 */
	function formatArray_d($datas,$titleRow){
		// 已定义标题
		$definedTitle = array(
				'序号' => 'serial', '卡片编号' => 'assetCode', '资产名称' => 'assetName', '资产属性' => 'property',
				'品牌' => 'brand', '资产类别' => 'assetTypeName', '手机频段' => 'mobileBand', '手机网络' => 'mobileNetwork',
				'规格型号' => 'spec', '配置' => 'deploy', '机器码' => 'machineCode','单位' => 'unit', '数量' => 'number', 
				'行政区域' => 'agencyName', '供应商' => 'supplierName', '所属公司' => 'companyName', '所属部门' => 'orgName', '所属人' => 'belongMan',
				'使用部门' => 'useOrgName', '使用人' => 'userName', '开始使用日期' => 'beginTime', '备注' => 'remark', 
				'购置日期' => 'buyDate', '入账日期' => 'wirteDate','变动方式' => 'changeTypeName', '折旧方式' => 'deprName', 
				'购进原值' => 'origina', '原值本币' => 'localCurrency', '累计折旧' => 'depreciation','净值' => 'netValue', 
				'净额' => 'netAmount', '预计净残值' => 'salvage', '本期折旧额' => 'periodDepr', '预计使用期间数' => 'estimateDay',
				'已使用期间数' => 'alreadyDay', '固定资产科目' => 'subName', '折旧费用项目' => 'expenseItems', '使用状态' => 'useStatusName'
		);
		// 日期验证的标题
		$dateTitle = array(
				'开始使用日期' => 'beginTime', '购置日期' => 'buyDate', '入账日期' => 'wirteDate'
		);
	
		// 构建新的数组
		foreach($titleRow as $k => $v){
			// 如果数据为空，则删除
			if(trim($datas[$k]) === ''){
				unset($datas[$k]);
				continue;
			}
			// 如果存在已定义内容，则进行键值替换
			if(isset($definedTitle[$v])){
				// 时间数据处理
				if(isset($dateTitle[$v]) && is_numeric(trim($datas[$k]))){
					$datas[$k] = date('Y-m-d',(mktime(0,0,0,1, $datas[$k] - 1 , 1900)));
				}
	
				// 格式化更新数组
				$datas[$definedTitle[$v]] = trim($datas[$k]);
			}
			// 处理完成后，删除该项
			unset($datas[$k]);
		}
		return $datas;
	}
	
	/**
	 * 卡片信息导入
	 */
	 function import_d(){
	 	try{
	 		$this->start_d();

	 		set_time_limit(0);
			$filename = $_FILES ["inputExcel"] ["name"];
			$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
			$fileType = $_FILES ["inputExcel"] ["type"];
			$resultArr = array();//结果数组
			$excelData = array ();//excel数据数组
			$tempArr = array();
			
			//判断导入类型是否为excel表
			if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
				$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name, 1);
				spl_autoload_register("__autoload");
				
				if($excelData[0][0] == "行政部填写"){//第2行为标题的情况
					$titleRow = $excelData[1];
					unset($excelData[0]);
					unset($excelData[1]);
				}else{//第1行为标题的情况
					$titleRow = $excelData[0];
					unset($excelData[0]);
				}
				
				//删除多余的空格以及空白数据
				foreach ($excelData as $key => $val){
					$delete = true;
					foreach( $val as $index => $value ){
						$excelData[$key][$index] = trim($value);
						if($value != ''){
							$delete = false;
						}
					}
					if($delete){
						unset( $excelData[$key] );
					}
				}

				//判断传入的是否为有效数据
				if (count($excelData)>0) {
					$deprDao = new model_asset_basic_deprMethod();//折旧方法
					$directoryDao = new model_asset_basic_directory();//资产类别
					$changeDao = new model_asset_basic_change();//变动方式
					$agencyDao = new model_asset_basic_agency();//行政区域
					$companyDao = new model_deptuser_branch_branch();//公司信息
					$deptDao = new model_deptuser_dept_dept();//部门信息
					$userDao = new model_deptuser_user_user();//人员信息
					$supplierDao = new model_supplierManage_formal_flibrary();//供应商
					$dataDao = new model_system_datadict_datadict();//数据字典
					$cardArr = $this->list_d();//卡片信息

					$inArr = array();
					foreach($excelData as $key => $val){
						// 格式化数组
						$val = $this->formatArray_d($val,$titleRow);
						$actNum = $key + 1;
						
						//如果使用状态为空，则默认为闲置
						if($val['useStatusName'] == ""){
							$val['useStatusName'] = '闲置';
							$val['useStatusCode'] = 'SYZT-XZ';
						}else{
							$rs = $dataDao->find(array('dataName' => $val['useStatusName']),null,'dataCode');
							if(!empty($rs)){
								$val['useStatusCode'] = $rs['dataCode'];
							}
						}
						
						//卡片编号
						if($val['assetCode'] == ''){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '卡片编号为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$assetCode = $val['assetCode'];
							foreach($cardArr as $cardVal){
								if(strtoupper($assetCode) == strtoupper($cardVal['assetCode'])){
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = '卡片编号已存在，导入失败！';
									array_push( $resultArr,$tempArr );
									continue 2;
								}
							}
						}
						//资产名称
						if($val['assetName'] != ''){
							//只有当资产名称为手机时，导入的手机频段，手机网络才有效
							if($val['assetName'] != "手机"){
								unset($val['mobileBand']);
								unset($val['mobileNetwork']);
							}
						}else{
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '资产名称为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//资产属性
						if($val['property'] != ''){
							if($val['property'] != '0' && $val['property'] != '1' && $val['property'] != "固定资产" && $val['property'] != "低值耐用品"){
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '资产属性非法，导入失败！请填0或固定资产，1或低值耐用品';
								array_push( $resultArr,$tempArr );
								continue;
							}elseif($val['property'] == "固定资产"){
								$val['property'] = '0';
							}elseif($val['property'] == "低值耐用品"){
								$val['property'] = '1';
							}
						}else{
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '资产属性为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//资产类别
						if($val['assetTypeName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '资产类别为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $directoryDao->find(array('name' => $val['assetTypeName']),null,'id');
							if(!empty($rs)){
								$val['assetTypeId'] = $rs['id'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '资产类别不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//行政区域
						$chargeId = '';//行政区域负责人id
						if($val['agencyName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '行政区域为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $agencyDao->find(array('agencyName' => $val['agencyName']),null,'agencyCode,chargeId');
							if(!empty($rs)){
								$val['agencyCode'] = $rs['agencyCode'];
								$chargeId = $rs['chargeId'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '行政区域不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//供应商
						if($val['supplierName'] != ''){
							$rs = $supplierDao->find(array('suppName' => $val['supplierName']),null,'id');
							if(!empty($rs)){
								$val['supplierId'] = $rs['id'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '供应商不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//所属公司
						if($val['companyName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '所属公司为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $companyDao->find(array('NameCN' => $val['companyName']),null,'NamePT');
							if(!empty($rs)){
								$val['companyCode'] = $rs['NamePT'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '所属公司不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//所属部门
						if($val['orgName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '所属部门为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $deptDao->find(array('DEPT_NAME' => $val['orgName'],'DelFlag' =>0),null,'DEPT_ID');
							if(!empty($rs)){
								$val['orgId'] = $rs['DEPT_ID'];
								//所属二级部门
								$rs = $this->getParentDept_d($val['orgId']);
								$val['parentOrgId'] = $rs[0]['parentId'];
								$val['parentOrgName'] = $rs[0]['parentName'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '所属部门不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//所属人
						if($val['belongMan'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '所属人为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							//姓名可能出现重复，所以用findAll
							$rs = $userDao->findAll(array('USER_NAME' => $val['belongMan']),null,'USER_ID,DEPT_ID,Company');
							if(!empty($rs)){
								$companyFlag = false;
								$deptFlag = false;
								foreach ($rs as $v){
									if($v['Company'] == $val['companyCode']){
										$companyFlag = true;
									}else{
										$companyFlag = false;
									}
									if($v['DEPT_ID'] == $val['orgId']){
										$deptFlag = true;
									}else{
										$deptFlag = false;
									}
									if($companyFlag && $deptFlag){
										$val['belongManId'] = $v['USER_ID'];
										break;
									}
								}
								if(!$companyFlag){
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = '所属人与所属公司不对应，导入失败！';
									array_push( $resultArr,$tempArr );
									continue;
								}
								if(!$deptFlag){
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = '所属人与所属部门不对应，导入失败！';
									array_push( $resultArr,$tempArr );
									continue;								
								}
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '所属人不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//使用部门
						if($val['useOrgName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '使用部门为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $deptDao->find(array('DEPT_NAME' => $val['useOrgName'],'DelFlag' =>0),null,'DEPT_ID');
							if(!empty($rs)){
								$val['useOrgId'] = $rs['DEPT_ID'];
								//使用二级部门
								$rs = $this->getParentDept_d($val['useOrgId']);
								$val['parentUseOrgId'] = $rs[0]['parentId'];
								$val['parentUseOrgName'] = $rs[0]['parentName'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '使用部门不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//使用人
						if($val['userName'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '使用人为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}else{
							$rs = $userDao->findAll(array('USER_NAME' => $val['userName']),null,'USER_ID,DEPT_ID');
							if(!empty($rs)){
								$deptFlag = false;
								foreach ($rs as $v){
									if($v['DEPT_ID'] == $val['useOrgId']){
										$deptFlag = true;
										$val['userId'] = $v['USER_ID'];
										//如果使用人与行政区域负责人不同，则将资产的使用状态改为使用中
										if($val['useStatusName'] == "闲置" && ($val['userId'] != $chargeId)){
											$val['useStatusName'] = '使用中';
											$val['useStatusCode'] = 'SYZT-SYZ';
										}
										break;
									}
								}
								if(!$deptFlag){
									$tempArr['docCode'] = $assetCode;
									$tempArr['result'] = '使用人与使用部门不对应，导入失败！';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '使用人不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						//购置日期
						if($val['buyDate'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '购置日期为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//入账日期
						if($val['wirteDate'] == ''){
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '入账日期为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//变动方式
						if($val['changeTypeName'] != ''){
							$rs = $changeDao->find(array('name' => $val['changeTypeName']),null,'code');
							if(!empty($rs)){
								$val['changeTypeCode'] = $rs['code'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '变动方式不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = $assetCode;
							$tempArr['result'] = '变动方式为空，导入失败！';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//折旧方式
						if($val['deprName'] != ''){
							$rs = $deprDao->find(array('name' => $val['deprName']),null,'code');
							if(!empty($rs)){
								$val['deprCode'] = $rs['code'];
							}else{
								$tempArr['docCode'] = $assetCode;
								$tempArr['result'] = '折旧方式不存在，导入失败！';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						array_push($inArr, $val);
					}

					if( count($resultArr)>0 ){
						$title = '资产卡片信息导入结果';
						$thead = array( '资产编号','结果' );
						echo "<script>alert('导入失败')</script>";
						echo util_excelUtil::showResult($resultArr,$title,$thead);
					}else{
						foreach ( $inArr as $key => $val ){
							//添加卡片记录信息
							$assetTempDao = new model_asset_assetcard_assetTemp();
							$tempId = $assetTempDao->addByCardImport_d($val);
							$val['templeId'] = $tempId;
							$val['version'] = 1;
							parent::add_d($val,true);
						}
						echo "<script>alert('导入成功');self.parent.tb_remove();if(self.parent.show_page)self.parent.show_page(1);</script>";
					}
				} else {
					msg( "文件不存在可识别数据!");
				}
			} else {
				msg( "上传文件类型不是EXCEL!");
			}
	 		$this->commit_d();
	 	}catch(Exception $e){
	 		$this->rollBack();
	 	}
	}

	/**
	 * 卡片信息导入
	 */
	 function oldImport_d($objKeyArr){
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
				unset($excelData[0]);
				//判断是否传入的是有效数据
				if ($excelData) {
					$branchDao = new model_deptuser_branch_branch();
					$deprDao = new model_asset_basic_deprMethod();
					$directoryDao = new model_asset_basic_directory();
					$changeDao = new model_asset_basic_change();
					$agencyDao = new model_asset_basic_agency();
					$dataDao = new model_system_datadict_datadict();
					$userDao = new model_deptuser_user_user();
					$deptDao = new model_deptuser_dept_dept();
//					$projectDao = new model_engineering_project_esmproject();
//					$projectDao->searchArr['ExaStatus'] = '完成';
					$dataArr = $dataDao->getDatadictsByParentCodes('SYZT');
					$branchArr = $branchDao->list_d();
					$direArr = $directoryDao->list_d();
					$agencyArr = $agencyDao->list_d();
					$changeArr = $changeDao->list_d();
					$deprArr = $deprDao->list_d();
					$deptArr = $deptDao->list_d();
					$userArr = $userDao->list_d();
					$cardArr = $this->list_d();
//					$projArr = $projectDao->list_d();
//					echo "<pre>";
//					print_R($projArr);
					foreach ($excelData as $key=>$val){
						//格式化编码，删除多余的空格。如果编码为空，则该条数据插入无效。
						foreach( $val as $index => $value ){
							$excelData[$key][$index] = trim($value);
						}
						$excelData[$key][0] = str_replace( ' ','',$val[0]);
						$excelData[$key][2] = trim($val[2]);
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
						$repeatCodeArr[$key]['docCode'] = $val['assetCode'];
						$repeatCodeArr[$key]['result'] = '';
						if($val['assetCode'] == ''){
							$repeatCodeArr[$key]['result'] .= '资产编码为空，导入失败！';
						}else{
							foreach( $cardArr as $cardKey => $cardVal ){
								if( $val['assetCode'] == $cardVal['assetCode'] ){
									$repeatCodeArr[$key]['result'] .= '该资产编码已存在，导入失败！';
									break;
								}
							}
						}
						if($val['assetName'] == ''){
							$repeatCodeArr[$key]['result'] .= '资产名称为空，导入失败！';
						}
						if($val['assetTypeName']==''){
							$repeatCodeArr[$key]['result'] .= '资产类别为空，导入失败！';
						}else{
							$isAssetType = false;
							foreach ( $direArr as $direKey=>$direVal ){
								if($val['assetTypeName'] == $direVal['name']){
									$objectArr[$key]['assetTypeId'] = $direVal['id'];
									$isAssetType = true;
								}
							}
							if(!$isAssetType){
								$repeatCodeArr[$key]['result'] .= '资产类别非法，请初始化后再导入！';
							}
						}
						$objectArr[$key]['isDeprf']='1';
						$objectArr[$key]['useStatusName']='闲置';
						$isUseStatus = false;
						foreach ( $dataArr['SYZT'] as $dataKey=>$dataVal ){
							if($objectArr[$key]['useStatusName'] == $dataVal['dataName']){
								$objectArr[$key]['useStatusCode'] = $dataVal['dataCode'];
								$isUseStatus = true;
							}
						}
						if(!$isUseStatus){
							$repeatCodeArr[$key]['result'] .= '使用状态非法，请初始化后再导入！';
						}
						$objectArr[$key]['changeTypeName']='购入';
//						$isChangeType = false;
//						foreach ( $changeArr as $changeKey=>$changeVal ){
//							if($objectArr[$key]['changeTypeName'] == $changeVal['name']){
//								$objectArr[$key]['changeTypeCode'] = $changeVal['code'];
//								$isChangeType = true;
//							}
//						}
//						if(!$isChangeType){
//							$repeatCodeArr[$key]['result'] .= '变动方式非法，请初始化后再导入！';
//						}
						$objectArr[$key]['deprName']='平均年限法';
						$isdeprType = false;
						foreach ( $deprArr as $deprKey=>$deprVal ){
							if($objectArr[$key]['deprName'] == $deprVal['name']){
								$objectArr[$key]['deprCode'] = $deprVal['code'];
								$isdeprType = true;
							}
						}
						if(!$isdeprType){
							$repeatCodeArr[$key]['result'] .= '折旧方式非法！';
						}
						if($val['buyDate']==''){
							$repeatCodeArr[$key]['result'] .= '购置日期为空，导入失败！';
						}else{
							$buyDate = mktime(0, 0, 0, 1, $objectArr[$key]['buyDate'] - 1, 1900);
							$objectArr[$key]['buyDate'] = date("Y-m-d", $buyDate);
							$objectArr[$key]['wirteDate'] = $objectArr[$key]['buyDate'];
						}
						if($val['orgName']!=''){
							$isDept = false;
							foreach ( $deptArr as $deptKey=>$deptVal ){
								if($val['orgName'] == $deptVal['name']){
									$objectArr[$key]['orgId'] = $deptVal['id'];
									$isDept = true;
								}
							}
							if(!$isDept){
								$repeatCodeArr[$key]['result'] .= '所属部门不存在！';
							}
						}else{
							$repeatCodeArr[$key]['result'] .= '所属部门为空，导入失败！';
						}

						if($val['agencyName']==''){
							$repeatCodeArr[$key]['result'] .= '所属区域为空，导入失败！';
						}else{
							$isAgency = false;
							foreach ( $agencyArr as $agencyKey=>$agencyVal ){
								if(strstr($agencyVal['agencyName'],$val['agencyName'])){
									$objectArr[$key]['agencyCode'] = $agencyVal['agencyCode'];
									$isAgency = true;
								}
							}
							if(!$isAgency){
								$repeatCodeArr[$key]['result'] .= '所属区域非法，请初始化后再导入！';
							}
						}
						if($val['origina']==''){
							$objectArr[$key]['origina'] = 0;
						}
						if($val['buyDepr']==''){
							$objectArr[$key]['buyDepr'] = 0;
						}
						if($val['beginTime']==''){
							$objectArr[$key]['beginTime'] = $objectArr[$key]['buyDate'];
						}
						if($val['estimateDay']==''){
							$objectArr[$key]['estimateDay'] = 0;
						}
						if($val['alreadyDay']==''){
							$objectArr[$key]['alreadyDay'] = 0;
						}
						if($val['depreciation']==''){
							$objectArr[$key]['depreciation'] = 0;
						}
						if($val['salvage']==''){
							$objectArr[$key]['salvage'] = 0;
						}
						if($val['netValue']==''){
							$objectArr[$key]['netValue'] = 0;
						}
//						if( $val['useOrgName']!='' ){
//							$isDept = false;
//							foreach ( $deptArr as $deptKey=>$deptVal ){
//								if($val['useOrgName'] == $deptVal['name']){
//									$objectArr[$key]['useOrgId'] = $deptVal['id'];
//									$isDept = true;
//								}
//							}
//							if(!$isDept){
//								$repeatCodeArr[$key]['result'] .= '使用部门不存在！';
//							}
//						}
						if( $val['belongMan']!='' ){
							$isUser = false;
							foreach ( $userArr as $userKey=>$userVal ){
								if($val['belongMan'] == $userVal['USER_NAME'] && $userVal['DEPT_ID']==$objectArr[$key]['orgId']){
									$objectArr[$key]['belongManId'] = $userVal['USER_ID'];
									$objectArr[$key]['companyCode'] = $userVal['Company'];
									foreach( $branchArr as $branchKey=>$branchval ){
										if( $branchval['NamePT'] == $userVal['Company'] ){
											$objectArr[$key]['companyName'] = $branchval['NameCN'];
										}
									}
									$isUser = true;
								}
							}
							if(!$isUser){
								$repeatCodeArr[$key]['result'] .= '所属人不存在,或所属人与所属部门不对应！';
							}
						}
					}
					foreach( $repeatCodeArr as $key=>$val ){
						if($val['result']==''){
							unset($repeatCodeArr[$key]);
						}
					}
					if( count($repeatCodeArr)>0 ){
				 		$returnFlag = false;
						$title = '资产卡片信息导入结果';
						$thead = array( '资产名称','结果' );
						echo "<script>alert('导入失败')</script>";
						echo util_excelUtil::showResult($repeatCodeArr,$title,$thead);
					}else{
						foreach ( $objectArr as $key => $val ){
							$val['version']=1;
							$this->importAdd_d($val,true);
						}
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
	
	/**
	 * 检测卡片是否被借用/领用单据关联
	 */
	function checkAsset_d($code){
		$sql = "SELECT count(*) as number from (
			SELECT bi.id,bi.borrowId AS mainId,bi.assetCode,bi.isReturn from oa_asset_borrowitem bi WHERE bi.isReturn=0
			UNION ALL SELECT ci.id,ci.allocateId AS mainId,ci.assetCode,ci.isReturn FROM oa_asset_chargeitem ci WHERE ci.isReturn=0
			)c WHERE c.assetCode='".$code."'";
		$number = $this->_db->getArray( $sql );
	 	return $number[0]['number'];
	}

    /**
     * 邮件发送
     * 2012年12月26日 17:07:59
     * zengzx
     */
    function toMail_d($emailArr,$object){
        $addMsg = '财务人员已确认了（资产名称：'.$object['assetName'].')的卡片记录，并已生成了资产卡片。请确认。';
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->mailClear('卡片确认',$emailArr,$addMsg);
    }

    function addBeachByProperty_d($id){
		$tempDao = new model_asset_assetcard_assetTemp();
		$tempInfo = $tempDao->getAssetInfo_d($id);
		$row['id']=$id;
		$tempInfo['templeId']=$id;
		//默认使用状态为闲置
		$tempInfo['useStatusCode']='SYZT-XZ';
		$tempInfo['useStatusName']='闲置';
		$tempInfo['version']=1;

		//获取行政区域负责人信息
		$agencyDao = new model_asset_basic_agency();
		$rs = $agencyDao->find(array('agencyCode' => $tempInfo['agencyCode']),null,'chargeId');
		//当行政区域负责人与使用人不同时使用状态改为使用中
		if($rs['chargeId'] != $tempInfo['userId']){
			$tempInfo['useStatusCode']='SYZT-SYZ';
			$tempInfo['useStatusName']='使用中';
		}
		$id = $this->addBeach_d($tempInfo,true);
		if($row['id']){
		 	$statusInfo = array(
		 		'id' => $row['id'],
		 		'isCreate' => 1
		 	);
		 	$tempDao->updateById( $statusInfo );
		}
    }
    
    /**
     * 资产卡片更新所属人
     */
    function updateBelongMan_d() {
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream"|| $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//判断是否传入的是有效数据
			if (is_array($excelData)) {
				$userDao = new model_deptuser_user_user();
				$deptDao = new model_deptuser_dept_dept();
				$userArr = $userDao->list_d();//所属人
				$deptArr = $deptDao->list_d();//所属部门
				$sql = "select NameCN,NamePT from branch_info";
				$compArr = $this->listBySql($sql);//所属公司
				//格式化编码，删除多余的空格
				foreach ($excelData as $key=>$val){
					foreach( $val as $index => $value ){
						$excelData[$key][$index] = trim($value);
					}
				}
				//行数组循环
				foreach( $excelData as $key=>$val ){
					$actNum = $key + 2;
					$inArr = array();
					//卡片编号
					if($val[0] != ''){
						$inArr['assetCode'] = $val[0];
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写卡片编号';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//所属公司
					if($val[1]!=''){
						$inArr['companyName'] = $val[1];
						$isComp = false;
						foreach ($compArr as $compKey => $compVal){
							if($val[1] == trim($compVal['NameCN'])){
								$inArr['companyCode'] = trim($compVal['NamePT']);
								$isComp = true;
							}
						}
						if(!$isComp){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属公司不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//所属部门
					if($val[2]!=''){
						$inArr['orgName'] = $val[2];
						$isDept = false;
						foreach ($deptArr as $deptKey => $deptVal){
							if($val[2] == trim($deptVal['DEPT_NAME'])){
								$inArr['orgId'] = trim($deptVal['id']);
								//所属二级部门
								$rs = $this->getParentDept_d($inArr['orgId']);
								$inArr['parentOrgId'] = $rs[0]['parentId'];
								$inArr['parentOrgName'] = $rs[0]['parentName'];
								$isDept = true;
							}
						}
						if(!$isDept){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属部门不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//所属人
					if($val[3]!=''){
						$inArr['belongMan'] = $val[3];
						$isUser = false;
						$isInDept = true;
						$isInComp = true;
						if(isset($inArr['orgId'])){
							$isInDept = false;
						}elseif (isset($inArr['companyCode'])){
							$isInComp = false;
						}
						foreach ($userArr as $userKey=>$userVal){
							if($val[3] == trim($userVal['USER_NAME'])){
								$inArr['belongManId'] = $userVal['USER_ID'];
								if(trim($userVal['DEPT_ID']) == $inArr['orgId']){
									$isInDept = true;
								}else{
									$inArr['orgId'] = trim($userVal['DEPT_ID']);
									foreach ($deptArr as $deptKey => $deptVal){
										if($inArr['orgId'] == trim($deptVal['id'])){
											$inArr['orgName'] = trim($deptVal['DEPT_NAME']);
										}
									}
								}
								if(trim($userVal['Company']) == $inArr['companyCode']){
									$isInComp = true;
								}else{
									$inArr['companyCode'] = trim($userVal['Company']);
									foreach ($compArr as $compKey => $compVal){
										if($inArr['companyCode'] == trim($compVal['NamePT'])){
											$inArr['companyName'] = trim($compVal['NameCN']);
										}
									}
								}
								$isUser = true;
							}
						}
						if(!$isUser){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属人不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if(!$isInDept){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属人与所属部门不匹配';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if(!$isInComp){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属人与所属公司不匹配';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写所属人';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//导入开始执行
					try{
						$this->start_d();
						//更新卡片所属人信息
						$id = $this->update(array("assetCode" => $inArr['assetCode']),$inArr);
						if($id){
							$tempArr['result'] = '更新成功';
						}
							
						$this->commit_d();
					}catch(Exception $e){
						$this->rollBack();
						$tempArr['result'] = '导入失败';
					}
					$tempArr['docCode'] = '第' . $actNum .'条数据';
					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			}else {
				msg( "文件不存在可识别数据!");
			}
		}else {
			msg( "上传文件类型不是EXCEL!");
		}
    }
    
    /**
     * 财务确认报废申请，更新资产卡片残值、净值信息
     */
    function updateScrapcard($cardInfo){
    	$condition = array('id' => $cardInfo['assetId']);
    	$row = array('salvage' => $cardInfo['salvage'],'netValue' => $cardInfo['netValue']);
    	return $this->update($condition,$row);
    }
    
    /**
     * 获取需要批量更新的卡片信息
     */
    function getUpdateData_d($obj){
    	$rowLength = 6;
    	$rowWidth = 100/$rowLength;
    	$rs = $this->getData_d($obj);
    	if($rs){
    		$rsLength = count($rs);
    		$str = '<table class="main_table" style="width:100%;" ><td colspan="99" class="form_header"><input type="checkbox" id="checkboxAll" onclick="checkAll();"/>'.
    		' （共<span id="allNum">'.$rsLength.'</span>条记录,选中<span id="num">0</span>条）'.
    		'</td>';
    		$i = 0;
    		foreach($rs as $v){
    			if($i == 0) $str .= "<tr>";
    			$str .= '<td style="width:'.$rowWidth.'%;"><input type="checkbox" id="check-'.$v['id'].'" name="assetcard[idArr][]" value="'.$v['id'].'" onclick="checkThis(\''.$v['id'].'\');"/>'.
    					'<a href="javascript:void(0);" onclick="viewForm(\''.$v['id'].'\');">' .$v['assetCode'].'</a></td>';
    			$i++;
    			if($i == $rowLength){
    				$str .= '</tr>';
    				$i = 0;
    			}
    		}
    		$leastNum = $rowLength - $i;
    		if($i != $rowLength) $str .= '<td colspan="'. $leastNum .'"></td></tr>';
    		$str .= '</table>';
    		return $str;
    	}else{
    		return '<table class="form_table" style="width:100%;"><tr><td colspan="5"> - 没有找到相关的卡片信息 - </td></tr></table>';
    	}
    }
    
    /**
     * 获取数组
     */
    function getData_d($obj){
    	$condition='';
    	$userId = $obj['userId'];
    	$belongManId = $obj['belongManId'];
    	$agencyCode = $obj['agencyCode'];
    	
    	if($userId) $condition.=" and userId = '".$userId."'";
    	if($belongManId) $condition.=" and belongManId = '".$belongManId."'";
    	if($agencyCode) $condition.=" and agencyCode = '".$agencyCode."'";
    	
		$sql = "SELECT id,assetCode FROM oa_asset_card WHERE isDel=0 and isTemp = 0".$condition;
    	return $this->_db->getArray($sql);
    }
    
    /**
     * 更新卡片信息方法
     */
    function updateCard_d($object){ 		
    	$updateSql = "UPDATE ". $this->tbl_name . " SET ";
    	$updateArr = array();
    		
    	//转入使用人信息
    	if(!empty($object['inUserId']))array_push($updateArr, "userId = '" . $object['inUserId'] . "'");
    	if(!empty($object['inUser']))array_push($updateArr, "userName = '" . $object['inUser'] . "'");
    	if(!empty($object['inUseOrgId'])){
    		$useOrgId = $object['inUseOrgId'];
    		//使用二级部门
    		$rs = $this->getParentDept_d($useOrgId);
    		$parentUseOrgId = $rs[0]['parentId'];
    		$parentUseOrgName = $rs[0]['parentName'];
    		array_push($updateArr, "useOrgId = '" . $useOrgId . "'");
    		array_push($updateArr, "useOrgName = '" . $object['inUseOrg'] . "'");
    		array_push($updateArr, "parentUseOrgId = '" . $parentUseOrgId . "'");
    		array_push($updateArr, "parentUseOrgName = '" . $parentUseOrgName . "'");
    	}
    	//转入所属人信息
    	if(!empty($object['inBelongManId']))array_push($updateArr, "belongManId = '" . $object['inBelongManId'] . "'");
    	if(!empty($object['inBelongMan']))array_push($updateArr, "belongMan = '" . $object['inBelongMan'] . "'");
    	if(!empty($object['inOrgId'])){
    		$orgId = $object['inOrgId'];
    		//所属二级部门
    		$rs = $this->getParentDept_d($orgId);
    		$parentOrgId = $rs[0]['parentId'];
    		$parentOrgName = $rs[0]['parentName'];
    		array_push($updateArr, "orgId = '" . $orgId . "'");
    		array_push($updateArr, "orgName = '" . $object['inOrg'] . "'");
    		array_push($updateArr, "parentOrgId = '" . $parentOrgId . "'");
    		array_push($updateArr, "parentOrgName = '" . $parentOrgName . "'");
    	}
    	//行政区域信息
    	if(!empty($object['inAgencyCode']))array_push($updateArr, "agencyCode = '" . $object['inAgencyCode'] . "'");
    	if(!empty($object['inAgencyName']))array_push($updateArr, "agencyName = '" . $object['inAgencyName'] . "'");
    	//id信息
    	$idStr = implode(',',$object['idArr']);
    		
    	//执行更新
    	if(!empty($updateArr)){
    		$updateSql .= implode(',',$updateArr) . 'WHERE id IN ('.$idStr.')';
    		return $this->_db->query($updateSql);
    	}else{
    		return false;
    	}
    }
    
    /**
     *获取区域权限字符串
     */
    function getAgencyStr_d() {
    	$agencyDao = new model_asset_basic_agency();
    
    	//行政区域负责人默认有查看该区域下卡片的权限
    	$rsArr = $agencyDao->findAll(array('chargeId'=>$_SESSION ['USER_ID']),null,'agencyCode');
    	//获取区域权限
    	$otherDataDao = new model_common_otherdatas();
    	$sysLimit = $otherDataDao->getUserPriv('asset_assetcard_assetcard', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
    	$agency = $sysLimit['区域权限'];
    	//如果该用户是行政区域负责人或存在区域权限
    	if((!empty($rsArr) || !empty($agency))){
    		//区域权限为全部
    		if(strstr($agency,';;') !== false){
    			return ';;';
    		}else{	//如果区域权限不为全部，则进行区域过滤
    			$agencyCodeArr = array();
    			if(!empty($agency)){
    				$agencyIdArr = explode(',',$agency);
    				foreach ($agencyIdArr as $key => $val){
    					$rs = $agencyDao->find(array('id'=>$val),null,agencyCode);
    					array_push($agencyCodeArr, "'".$rs['agencyCode']."'");
    				}
    			}
    			if(!empty($rsArr)){
    				foreach ($rsArr as $key => $val){
    					array_push($agencyCodeArr, "'".$val['agencyCode']."'");
    				}
    			}
    			//数组去重
    			$agencyCodeArr = array_unique($agencyCodeArr);
    			//构造区域字符串
    			$agencyStr = implode(',', $agencyCodeArr);
    			return $agencyStr;
    		}
    	}
    	return null;
    }
    
    /****************************************更新卡片部门信息****************************************/
    /**
     * 通过部门id获取上级部门信息
     */
    function getParentDept_d($deptId){
    	//如果该部门不存在上级部门，则将上级部门置为该部门本身
    	$sql = "
	    	SELECT
	    	IF (
		    	d.PARENT_ID = 0,
		    	d.DEPT_ID,
		    	d1.DEPT_ID
	    	) AS parentId,
	    	IF (
		    	d.PARENT_ID = 0,
		    	d.DEPT_NAME,
		    	d1.DEPT_NAME
	    	) AS parentName
	    	FROM
	    		department d
	    	LEFT JOIN department d1 ON d.PARENT_ID = d1.DEPT_ID
	    	WHERE
	    		d.DEPT_ID = $deptId";
    	return $this->findSql($sql);
    }

    /**
     * 通过人员id获取部门信息
     */
    function getDeptInfo_d($userId){
    	$sql = "
				SELECT
    				u.USER_NAME AS userName,
					u.DEPT_ID AS deptId,
					d.DEPT_NAME AS deptName,
				IF (
					d.PARENT_ID = 0,
					d.DEPT_ID,
					d1.DEPT_ID
				) AS parentId,				
				IF (
					d.PARENT_ID = 0,
					d.DEPT_NAME,
					d1.DEPT_NAME
				) AS parentName
				FROM
					USER u
				LEFT JOIN department d ON u.DEPT_ID = d.DEPT_ID
				LEFT JOIN department d1 ON d.PARENT_ID = d1.DEPT_ID
				WHERE
					u.USER_ID = '".$userId."'";
    	return $this->findSql($sql);
    }
    
    /**
     * 人员变动时，更新卡片使用人或所属人的部门信息
     */
    function updateDeptInfo($userId){
    	//获取部门信息
    	$deptInfo = $this->getDeptInfo_d($userId);

    	$deptId = $deptInfo[0]['deptId']; //部门id
    	$deptName = $deptInfo[0]['deptName']; //部门名称
    	$parentDeptId = $deptInfo[0]['parentId']; //二级部门id
    	$parentDeptName = $deptInfo[0]['parentName']; //二级部门名称
    	
    	try {
    		$this->start_d();
    		 
    		//更新资产卡片使用部门信息
    		$this->update(array('userId' => $userId), array(
    				'useOrgId' => $deptId,'useOrgName' => $deptName,
    				'parentUseOrgId' => $parentDeptId,'parentUseOrgName' => $parentDeptName));
    		//更新资产卡片所属部门信息
    		$this->update(array('belongManId' => $userId), array(
    				'orgId' => $deptId,'orgName' => $deptName,
    				'parentOrgId' => $parentDeptId,'parentOrgName' => $parentDeptName));
    		 
    		$this->commit_d();
    		return true;
    	}catch ( Exception $e ) {
    		$this->rollBack();
    		return false;
    	}
    }
    
    /**
     * 工程设备借用、转借时，更新卡片使用人及使用部门信息，并将卡片状态改为使用中
     */
    function updateByEsmDevice($userId,$ids){
    	//获取部门信息
    	$deptInfo = $this->getDeptInfo_d($userId);

    	$userName = $deptInfo[0]['userName']; //人员姓名
    	$deptId = $deptInfo[0]['deptId']; //部门id
    	$deptName = $deptInfo[0]['deptName']; //部门名称
    	$parentDeptId = $deptInfo[0]['parentId']; //二级部门id
    	$parentDeptName = $deptInfo[0]['parentName']; //二级部门名称
    	
    	$sql = " UPDATE $this->tbl_name SET userId = '".$userId."' ,userName = '".$userName."' ,useOrgId = '".$deptId."' ,
				 	useOrgName = '".$deptName."' ,parentUseOrgId = '".$parentDeptId."' ,parentUseOrgName = '".$parentDeptName."' ,
				 	useStatusCode = 'SYZT-SYZ' ,useStatusName = '使用中'
				 WHERE id IN ($ids)";
    	$this->_db->query($sql);
    }
    
    /**
     * 工程设备归还时，将使用人及使用部门修改为行政区域负责人信息，并将卡片状态改为闲置
     */
    function emptyByEsmDevice($ids){
    	$idArr = explode(',', $ids);
    	$agencyDao = new model_asset_basic_agency();//行政区域
    	foreach ($idArr as $id){
    		$agencyCode = $this->get_table_fields($this->tbl_name, 'id='.$id, 'agencyCode');
    		$rs = $agencyDao->find(array('agencyCode' => $agencyCode),null,'chargeId');//获取行政区域负责人信息
    		$userId = $rs['chargeId'];
    		//获取部门信息
    		$deptInfo = $this->getDeptInfo_d($userId);
    		
    		$userName = $deptInfo[0]['userName']; //人员姓名
    		$deptId = $deptInfo[0]['deptId']; //部门id
    		$deptName = $deptInfo[0]['deptName']; //部门名称
    		$parentDeptId = $deptInfo[0]['parentId']; //二级部门id
    		$parentDeptName = $deptInfo[0]['parentName']; //二级部门名称
    		
	    	$sql = " UPDATE $this->tbl_name SET userId = '".$userId."' ,userName = '".$userName."' ,useOrgId = '".$deptId."' ,
					 	useOrgName = '".$deptName."' ,parentUseOrgId = '".$parentDeptId."' ,parentUseOrgName = '".$parentDeptName."' ,
					 	useStatusCode = 'SYZT-XZ' ,useStatusName = '闲置'
					 WHERE id =".$id;
    		$this->_db->query($sql);
    	} 	 
    }
    
    /****************************************清理低值耐用品业务部分****************************************/
    /**
     * 获取低值耐用品
     */
    function searchCleanLowValueGoods_d($object) {
    	$assetCode = isset($object['assetCode']) ? $object['assetCode'] : "";  //资产编号
    	$condition = '';
    	if($assetCode){//若填写了资产编号，则不处理其它搜索条件
    		$condition .= " and c.assetCode = '".$assetCode."'";
    	}else{
    		$assetName = isset($object['assetName']) ? $object['assetName'] : "";  //资产名称
    		$year = isset($object['year']) ? $object['year'] : "";  //使用周期(年)
    		$month = isset($object['month']) ? $object['month'] : "";  //使用周期(月)
    		$day = isset($object['day']) ? $object['day'] : "";  //使用周期(天)
    		if($assetName){
    			$condition .= " and c.assetName = '".util_jsonUtil::iconvUTF2GB($assetName)."'";
    		}
    		if ($year || $month || $day) {//使用周期
    			$countDays = 0;
    			if($year){
    				$countDays += bcmul($year,365);
    			}
    			if($month){
    				$countDays += bcmul($month,30);
    			}
    			if($day){
    				$countDays += $day;
    			}
    			if($countDays != 0){
    				$condition .= " and DATEDIFF(CURDATE(),c.".$object['dateType'].") >= ".$countDays;
    			}
    		}
    	}
    	$sql = "
			SELECT
				c.id,c.assetCode,c.assetName,c.buyDate,c.wirteDate,FORMAT(c.salvage,2) as salvage,c.agencyName,c.useOrgName,c.userName,c.orgName,c.belongMan,c.remark
			FROM
				" . $this->tbl_name . " c
			WHERE
				c.isDel = 0 AND	c.isTemp = 0 AND c.useStatusCode = 'SYZT-XZ' AND c.property = 1" .$condition."
    		ORDER BY 
				c.assetName,c.".$object['dateType'];

    	return $this->_db->getArray($sql);
    }
    
    /**
     * 输出html
     */
    function serachHtml_d($rows) {
    	if ($rows) {
    		$html = "<table class='main_table'><thead><tr class='main_tr_header'><th><input type='checkbox' id='checkAll' onclick='checkAll();' /></td><th>序号</th>" .
    				"<th>资产编号</th><th>资产名称</th><th>购置日期</th><th>入账日期</th><th>残值</th><th>所属区域</th><th>所属部门</th><th>所属人</th><th>备注</th></tr></thead><tbody>";
    		$i = 0;
    		foreach ($rows as $v) {
    			$i++;
    			$html .= "<tr class='tr_even'>";
    			$html .= "<td><input type='checkbox' value='$v[id]' /></td><td>$i</td><td><a href='javascript:void(0);' onclick='searchDetail(\"$v[id]\")'>$v[assetCode]</a></td>" .
    			"<td>$v[assetName]</td><td>$v[buyDate]</td><td>$v[wirteDate]</td><td>$v[salvage]</td><td>$v[agencyName]</td><td>$v[orgName]</td>" .
    			"<td>$v[belongMan]</td><td>$v[remark]</td></tr>";
    		}
    		return $html . '</tbody></table>';
    	} else {
    		return '没有查询到数据';
    	}
    }
    
    /**
     * 批量清理低值耐用品
     */
    function cleanLowValueGoods_d($ids) {
    	$idStr = util_jsonUtil::strBuild($ids);
    	$updateSql = "UPDATE " . $this->tbl_name . " SET useStatusCode = 'SYZT-YQL',useStatusName = '已清理',isDel = 1 WHERE id IN(" . $idStr . ")";
    	return $this->query($updateSql);
    }
    /****************************************更新卡片状态****************************************/
    /**
     * 根据Id修改资产清理状态
     * @linzx
     */
    function setCleanStatus($id){
    	$rows = array(
    			'id'=>$id,
    			'isDel'=>'1',
    			'useStatusCode'=>'SYZT-YQL',
    			'useStatusName'=>'已清理'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 根据资产ID修改资产出售状态
     * @linzx
     */
    function setIsSell($id){
    	$rows = array(
    			'id'=>$id,
    			'isSell'=>'1',
    			'useStatusCode'=>'SYZT-WCS',
    			'useStatusName'=>'未出售'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 根据资产ID修改资产卡片为闲置状态
     * @linzx
     */
    function setNoScrap($id){
    	$rows = array(
    			'id'=>$id,
    			'isScrap'=>'0',
    			'useStatusCode'=>'SYZT-XZ',
    			'useStatusName'=>'闲置'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 根据资产ID修改资产卡片为待报废状态
     * @linzx
     */
    function setToScrap($id){
    	$rows = array(
    			'id'=>$id,
    			'isScrap'=>'1',
    			'useStatusCode'=>'SYZT-DBF',
    			'useStatusName'=>'待报废'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 根据资产ID修改资产卡片为已报废状态
     */
    function setIsScrap($id){
    	$rows = array(
    			'id'=>$id,
    			'isScrap'=>'1',
    			'useStatusCode'=>'SYZT-YBF',
    			'useStatusName'=>'已报废'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 根据资产ID修改资产折旧完状态
     * @fengxw
     */
    function setIsDepr($id){
    	$rows = array(
    			'id'=>$id,
    			'isDeprf'=>'1'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 根据资产ID修改资产卡片为待出库状态
     */
    function setToStock($id){
    	$rows = array(
    			'id'=>$id,
    			'useStatusCode'=>'SYZT-DTK',
    			'useStatusName'=>'待退库'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 根据资产ID修改资产卡片为已退库状态
     */
    function setIsStock($id){
    	$rows = array(
    			'id'=>$id,
    			'useStatusCode'=>'SYZT-YTK',
    			'useStatusName'=>'已退库'
    	);
    	return parent::edit_d($rows,true);
    }
    
    /**
     * 更新卡片的【是否空闲】状态
     */
    function setIdleStatus($idArr,$value){
    	$ids = implode(',',$idArr);
    	$sql = "update oa_asset_card set idle=".$value." where id in(".$ids.")";
    	$this->_db->query($sql);
    }
    
    /**
     * 归还资产时,验证卡片是否已提交过归还
     */
    function isReturning_d($assetId,$userId){
		$sql = "SELECT r.id FROM oa_asset_return r LEFT JOIN oa_asset_returnitem ri
				ON (r.id = ri.allocateID) WHERE r.isSign = 0 AND r.returnManId = '".$userId."' AND ri.assetId=".$assetId;
		$rs = $this->_db->getArray( $sql );
    	if(empty($rs)){//不存在归还记录
    		return false;
    	}else{
    		return true;
    	}
    }
 }