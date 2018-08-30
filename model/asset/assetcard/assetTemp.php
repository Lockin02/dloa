<?php
/**
 * @author Administrator
 * @Date 2012年10月7日 10:36:31
 * @version 1.0
 * @description:卡片新增临时表 Model层
 */
 class model_asset_assetcard_assetTemp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_card_temp";
		$this->sql_map = "asset/assetcard/assetTempSql.php";
		parent::__construct ();
	}

	/**
	 * 添加对象
	 */
	function add_d($object, $isAddInfo = false) {
		try{
			$this->start_d();
			
			if ($isAddInfo) {
				$object = $this->addCreateInfo ( $object );
			}
			//资产名称去空格
			$object['assetName'] = trim($object['assetName']);
	        //获取邮件记录
		    $emailArr = $object['email'];
		    unset($object['email']);
		    
		    //根据资产分类id获取资产分类code
		    $direCode =new model_asset_basic_directory();
		    $code= $direCode-> getCodeById_d($object['assetTypeId']);
		    //自动生成资产编码
		    $codeDao = new model_common_codeRule ();
		    $sql = "SELECT MAX(buyDate) as buyDate from oa_asset_card";
		    $buyDateArr = $this->_db->get_one($sql);
		    $buyDate = $buyDateArr['buyDate'];
		    $maxBuyDate = date('Ym',strtotime($buyDate));
		    $thisByDate = date('Ym',strtotime($object['buyDate']));
		    if( $maxBuyDate!=$thisByDate ){
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],true );
		    }else{
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],false );
		    }
		    $object['assetCode'] = $object['companyCode'].$thisByDate.$code['code'].$mainCode;
		    $assetcardDao = new model_asset_assetcard_assetcard();//资产卡片
		    //所属二级部门
		    $rs = $assetcardDao->getParentDept_d($object['orgId']);
		    $object['parentOrgId'] = $rs[0]['parentId'];
		    $object['parentOrgName'] = $rs[0]['parentName'];
		    //使用二级部门
		    $rs = $assetcardDao->getParentDept_d($object['useOrgId']);
		    $object['parentUseOrgId'] = $rs[0]['parentId'];
		    $object['parentUseOrgName'] = $rs[0]['parentName'];
		    
		    //根据验收单id获取资产需求申请信息
		    if(isset($object['receiveItemId']) && !empty($object['receiveItemId']) && empty($object['requireinId'])){
		    	$receiveItemDao = new model_asset_purchase_receive_receiveItem();
		    	$requirementInfo = $receiveItemDao->getRequirementInfo($object['receiveItemId']);
		    	$object['requireId'] = $requirementInfo['relDocId'];
		    	$object['requireCode'] = $requirementInfo['relDocCode'];
		    }
		    //根据物料转资产id获取资产需求申请信息
		    if(isset($object['requireinId']) && !empty($object['requireinId'])){
		    	$requireinDao = new model_asset_require_requirein();
		    	$rs = $requireinDao->find(array('id'=>$object['requireinId']),null,'requireId,requireCode');
		    	$object['requireId'] = $rs['requireId'];
		    	$object['requireCode'] = $rs['requireCode'];		    	
		    }
		    //加入数据字典处理 add by chengl 2011-05-15
			$newId = $this->create ( $object );
			if( $_GET['actType']=='submit' ){
				$assetcardDao->addBeachByProperty_d($newId);
			}
			//改变资产申请状态
			if($object['isPurch']=='1'){
				$receiveItemId = $object['receiveItemId'];
				//更新验收明细卡片生成数量及状态
				$receiveItemDao->updateCardNum($receiveItemId,$object['number']);
				$receiveItemDao->changeCardStatus($receiveItemId);
				//更新资产需求申请状态
				$requirementDao = new model_asset_require_requirement();
				$requirementDao->updateRecognize($object['requireId']);
			}
			//改变物料转资产申请相关单据状态
			if(isset($object['requireinId']) && !empty($object['requireinId'])){
				//统一实例化
				$receiveItemDao = new model_asset_purchase_receive_receiveItem();
				$requireinDao = new model_asset_require_requirein();
				$requireinitemDao = new model_asset_require_requireinitem();
				
				$number = $object['number'];
				$receiveItemId = $object['receiveItemId'];
				//更新验收明细卡片生成数量及状态
				$receiveItemDao->updateCardNum($receiveItemId,$number);
				$receiveItemDao->changeCardStatus($receiveItemId);
				//更新生成卡片数量
				$requireinitemDao->updateCardNum($object['requireinItemId'],$number);
				$requireinId = $object['requireinId'];
				//更新单据状态
				$requireinDao->updateStatus($requireinId);
				//更新需求申请物料转资产状态
				$requireinDao->updateRequireInStatus($requireinId);
			}
			//发送邮件 ,当操作为提交时才发送
			if( $object['ismail'] == '1'&& !empty($emailArr['TO_ID'])){
				$this->mailDeal_d('assetCard',$emailArr['TO_ID'],array('id' =>$newId ,'mailUser' =>$emailArr['TO_NAME'],'assetName'=>$object['assetName']));
			}
			
			$this->commit_d();
			return $newId;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 根据主键获取对象
	 */
	function getAssetInfo_d($id) {
		//return $this->getObject($id);
		$condition = array ("id" => $id );
		$this->searchArr['id']=$id;
		$rows = $this->listBySqlId ('select_card');
		return $rows[0];
	}
	
	/**
	 * 根据主键修改对象
	 */
	function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		if( $_GET['actType']=='submit' ){
			$cardDao = new model_asset_assetcard_assetcard();
			$cardDao->addBeachByProperty_d($object['id']);
		}
		return $this->updateById ( $object );
	}
	
	/**
	 * 更新卡片编号
	 */
	function updateAssetCode_d (){
		$rows=$this->list_d();
		$assetCoderow=array();
		$assetcardDao = new model_asset_assetcard_assetcard();
		foreach ($rows as $key => $val){
			$assetCoderow = $assetcardDao->find(array('templeId' => $val['id']),null,'assetCode');
// 			print_r($assetCoderow);die();
			if(!empty($assetCoderow)){
				$assetCode = $assetCoderow['assetCode'];
				$sql = "update oa_asset_card_temp set assetCode = substring('{$assetCode}',1,length('{$assetCode}')-3) where id = ".$val['id'];
				$this->query($sql);
			}
		}
	}
	
	/**
	 * 匹配excel字段
	 */
	function formatArray_d($datas,$titleRow){
		// 已定义标题
		$definedTitle = array(
				'资产名称' => 'assetName', '英文名称' => 'englishName', '资产属性' => 'property', '资产类别' => 'assetTypeName',
				'手机频段' => 'mobileBand', '手机网络' => 'mobileNetwork', '资产来源' => 'assetSourceName', '资产用途' => 'useType', 
				'品牌' => 'brand', '规格型号' => 'spec', '配置' => 'deploy', '机器码' => 'machineCode', '数量' => 'number','单位' => 'unit', 
				'供应商' => 'supplierName', '物料名称' => 'productName', '存放地点' => 'place', '行政区域' => 'agencyName', '所属公司' => 'companyName', 
				'所属部门' => 'orgName', '所属人' => 'belongMan', '使用部门' => 'useOrgName', '使用人' => 'userName', '备注' => 'remark',
				'购置日期' => 'buyDate', '入账日期' => 'wirteDate', '变动方式' => 'changeTypeName', '折旧方式' => 'deprName',
				'购进原值' => 'origina', '原值本币' => 'localCurrency', '累计折旧' => 'depreciation', '净值' => 'netValue',
				'净额' => 'netAmount', '预计净残值' => 'salvage', '本期折旧额' => 'periodDepr', '开始使用日期' => 'beginTime', 
				'预计使用期间数' => 'estimateDay', '已使用期间数' => 'alreadyDay', '固定资产科目' => 'subName', '折旧费用项目' => 'expenseItems'
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
	 * 卡片记录导入
	 */
	function import_d(){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();

		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream"|| $fileType == "application/kset") {
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
				$assetcardDao = new model_asset_assetcard_assetcard();//资产卡片
				$directoryDao = new model_asset_basic_directory();//资产类别
				$agencyDao = new model_asset_basic_agency();//行政区域
				$companyDao = new model_deptuser_branch_branch();//公司信息
				$deptDao = new model_deptuser_dept_dept();//部门信息
				$userDao = new model_deptuser_user_user();//人员信息
				$supplierDao = new model_supplierManage_formal_flibrary();//供应商
				$productDao = new model_stock_productinfo_productinfo();//物料信息
				$changeDao = new model_asset_basic_change();//变动方式
				$deprDao = new model_asset_basic_deprMethod();//折旧方法
				$dataDao = new model_system_datadict_datadict();//数据字典
				//行数组循环
				foreach( $excelData as $key => $val ){
					// 格式化数组
					$val = $this->formatArray_d($val,$titleRow);
					
					$actNum = $key + 1;
					$machineCodeArr = array();
					
					//卡片默认使用状态为闲置
					$val['useStatusName'] = '闲置';
					$val['useStatusCode'] = 'SYZT-XZ';
					
					/******************************行政部填写部分******************************/
					//资产名称
					if($val['assetName'] != ''){
						//只有当资产名称为手机时，导入的手机频段，手机网络才有效
						if($val['assetName'] != "手机"){
							unset($val['mobileBand']);
							unset($val['mobileNetwork']);
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写资产名称';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//资产属性
					if($val['property'] != ''){
						if($val['property'] != '0' && $val['property'] != '1' && $val['property'] != "固定资产" && $val['property'] != "低值耐用品"){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!资产属性请填0或固定资产，1或低值耐用品';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if($val['property'] == "固定资产"){
							$val['property'] = '0';
						}elseif($val['property'] == "低值耐用品"){
							$val['property'] = '1';
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!资产属性不能为空';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//资产类别
					if($val['assetTypeName'] != ''){
						$rs = $directoryDao->find(array('name' => $val['assetTypeName']),null,'id');
						if(!empty($rs)){
							$val['assetTypeId'] = $rs['id'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该资产类别不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写资产类别';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//资产来源
					if($val['assetSourceName'] != ''){
						$rs = $dataDao->find(array('dataName' => $val['assetSourceName']),null,'dataCode');
						if(!empty($rs)){
							$val['assetSource'] = $rs['dataCode'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该资产来源不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写资产来源';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//资产用途
					if($val['useType'] != ''){
						$rs = $dataDao->find(array('dataName' => $val['useType']),null,'dataCode');
						if(empty($rs)){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该资产用途不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//机器码				
					if($val['machineCode'] == ''){
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写机器码';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//数量
					if($val['number'] != ''){
						//构造机器码数组
						$machineCodeArr = explode(",",$val['machineCode']);
						$num = count($machineCodeArr); 
						
						foreach ($machineCodeArr as $machineCode){
							if($machineCode == ""){
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!存在空的机器码';
								array_push( $resultArr,$tempArr );
								continue 2;
							}
						}
						if($num != count(array_unique($machineCodeArr))){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!存在重复的机器码';
							array_push( $resultArr,$tempArr );
							continue;
						}
						if($num != $val['number']){
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!所填机器码个数与数量不一致';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写数量';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//供应商
					if($val['supplierName'] != ''){
						$rs = $supplierDao->find(array('suppName' => $val['supplierName']),null,'id');
						if(!empty($rs)){
							$val['supplierId'] = $rs['id'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该供应商不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//物料名称
					if($val['productName'] != ''){
						$rs = $productDao->find(array('productName' => $val['productName']),null,'id,productCode');
						if(!empty($rs)){
							$val['productId'] = $rs['id'];
							$val['productCode'] = $rs['productCode'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该物料名称不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//行政区域
					$chargeId = '';//行政区域负责人id
					if($val['agencyName'] != ''){
						$rs = $agencyDao->find(array('agencyName' => $val['agencyName']),null,'agencyCode,chargeId');
						if(!empty($rs)){
							$val['agencyCode'] = $rs['agencyCode'];
							$chargeId = $rs['chargeId'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该行政区域不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写行政区域';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//所属公司
					if($val['companyName'] != ''){
						$rs = $companyDao->find(array('NameCN' => $val['companyName']),null,'NamePT');
						if(!empty($rs)){
							$val['companyCode'] = $rs['NamePT'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属公司不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写所属公司';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//所属部门
					if($val['orgName'] != ''){
						$rs = $deptDao->find(array('DEPT_NAME' => $val['orgName']),null,'DEPT_ID');
						if(!empty($rs)){
							$val['orgId'] = $rs['DEPT_ID'];
						    //所属二级部门
						    $rs = $assetcardDao->getParentDept_d($val['orgId']);
						    $val['parentOrgId'] = $rs[0]['parentId'];
						    $val['parentOrgName'] = $rs[0]['parentName'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属部门不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写所属部门';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//所属人
					if($val['belongMan'] != ''){
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
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!该所属人与所属公司不匹配';
								array_push( $resultArr,$tempArr );
								continue;
							}
							if(!$deptFlag){
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!该所属人与所属部门不匹配';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该所属人不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写所属人';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//使用部门
					if($val['useOrgName'] != ''){
						$rs = $deptDao->find(array('DEPT_NAME' => $val['useOrgName']),null,'DEPT_ID');
						if(!empty($rs)){
							$val['useOrgId'] = $rs['DEPT_ID'];
							//使用二级部门
							$rs = $assetcardDao->getParentDept_d($val['useOrgId']);
							$val['parentUseOrgId'] = $rs[0]['parentId'];
							$val['parentUseOrgName'] = $rs[0]['parentName'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该使用部门不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写使用部门';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//使用人
					if($val['userName'] != ''){
						$rs = $userDao->findAll(array('USER_NAME' => $val['userName']),null,'USER_ID,DEPT_ID');
						if(!empty($rs)){
							$deptFlag = false;
							foreach ($rs as $v){
								if($v['DEPT_ID'] == $val['useOrgId']){
									$deptFlag = true;
									$val['userId'] = $v['USER_ID'];
									//如果使用人与行政区域负责人不同，则将资产的使用状态改为使用中
									if($val['userId'] != $chargeId){
										$val['useStatusName'] = '使用中';
										$val['useStatusCode'] = 'SYZT-SYZ';
									}
									break;
								}
							}
							if(!$deptFlag){
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '导入失败!该使用人与使用部门不匹配';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该使用人不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写使用人';
						array_push( $resultArr,$tempArr );
						continue;
					}
					/******************************财务部填写部分******************************/
					//购置日期
					if($val['buyDate'] == ''){
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写购置日期';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//入账日期
					if($val['wirteDate'] == ''){
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写入账日期';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//变动方式
					if($val['changeTypeName'] != ''){
						$rs = $changeDao->find(array('name' => $val['changeTypeName']),null,'code');
						if(!empty($rs)){
							$val['changeTypeCode'] = $rs['code'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该变动方式不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '第' . $actNum .'条数据';
						$tempArr['result'] = '导入失败!没有填写变动方式';
						array_push( $resultArr,$tempArr );
						continue;
					}
					//折旧方式
					if($val['deprName'] != ''){
						$rs = $deprDao->find(array('name' => $val['deprName']),null,'code');
						if(!empty($rs)){
							$val['deprCode'] = $rs['code'];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '导入失败!该折旧方式不存在';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}
					//是否生成卡片，默认为否
					$val['isCreate'] = '0';
					//版本号默认为1
					$val['version'] = '1';

					//生成临时卡片和资产卡片
					$tempId = $this->importAdd_d($val);
					if($tempId){
						$tempArr['result'] = '新增成功';
					}else{
						$tempArr['result'] = '导入失败';
					}
					
					$tempArr['docCode'] = '第' . $actNum .'条数据';
					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
		}
	}
	
	function importAdd_d($object){
		try{
			$this->start_d();
		    
		    //自动生成资产编码
		    $codeDao = new model_common_codeRule ();
		    $sql = "SELECT MAX(buyDate) as buyDate from oa_asset_card";
		    $buyDateArr = $this->_db->get_one($sql);
		    $buyDate = $buyDateArr['buyDate'];
		    $maxBuyDate = date('Ym',strtotime($buyDate));
		    $thisByDate = date('Ym',strtotime($object['buyDate']));
		    if( $maxBuyDate!=$thisByDate ){
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],true );
		    }else{
		    	$mainCode = $codeDao->assetcardCode ( "oa_asset_assetcard",$object['property'],false );
		    }
		    $object['assetCode'] = $object['companyCode'].$thisByDate.$object['assetTypeCode'].$mainCode;
		    $newId = $this->create($object);
		    
		    //生成资产卡片
		    $assetcardDao = new model_asset_assetcard_assetcard();
		    $object['templeId'] = $newId;
		    if($assetcardDao->addBeach_d($object,true)){
		    	//更新卡片生成状态为是
		    	$this->update(array('id' => $newId), array('isCreate' => '1'));
		    }
					
			$this->commit_d();
			return $newId;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	}
	
	/**
	 * 导入卡片信息时生成卡片记录
	 */
	function addByCardImport_d($cardInfo){
		$assetCode = substr($cardInfo['assetCode'],0,strlen($cardInfo['assetCode'])-3);//卡片编号去掉后3位,作为卡片记录编号
		$rs = $this->find(array('assetCode' => $assetCode),null,'id,machineCode,number');
		if(!empty($rs)){//存在相关的卡片记录信息,进行更新
			if(isset($cardInfo['machineCode'])){//处理机器码
				if(!empty($rs['machineCode'])){//原来存在,则合并
					$rs['machineCode'] = $rs['machineCode'].','.$cardInfo['machineCode'];
				}else{
					$rs['machineCode'] = $cardInfo['machineCode'];
				}
			}
			$rs['number'] = $rs['number'] + 1;//累加卡片数量
			parent::edit_d($rs, true);
			return $rs['id'];
		}else{//新增卡片记录
			$cardInfo['assetCode'] = $assetCode;
			$cardInfo['number'] = 1;
			$cardInfo['isCreate'] = 1;//已生成卡片
			return parent::add_d($cardInfo, true);//返回id
		}
	}
 }