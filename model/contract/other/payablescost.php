<?php

/**
 * @author chenrf
 * @description:付款申请费用分摊明细表 Model层(临时表，用于其他合同未提交前存放分摊费用)
 */
class model_contract_other_payablescost extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_payablesapply_cost_temp";
		$this->sql_map = "contract/other/payablescostSql.php";
		parent :: __construct();
	}

	/********************* S 策略部分 ***********************/
	//启用策略的费用分摊类型
	private $initStrategy = array('CWFYFT-03');

	//策略实例对象
	private $relatedCode = array(
		'CWFYFT-01' => '', //人员
		'CWFYFT-02' => '', //部门
		'CWFYFT-03' => 'esmproject', //工程项目
		'CWFYFT-04' => ''  //研发项目
	);

	/**
	 * 根据类型返回业务名称
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	//不同分摊类型调用的策略文件
	private $relatedStrategyArr = array (
		'CWFYFT-01' => '', //人员
		'CWFYFT-02' => '', //部门
		'CWFYFT-03' => 'esmproject', //工程项目
		'CWFYFT-04' => ''  //研发项目
	);

	/**
	 * 根据数据类型返回类
	 */
	public function getClass($objType){
		$rs = isset($this->relatedStrategyArr[$objType]) ? $this->relatedStrategyArr[$objType] : null;
		return $rs;
	}

	/********************* E 策略部分 ***********************/

    //数据字典字段处理
    public $datadictFieldArr = array(
    	'shareType'
    );

    //费用分摊记录状态
    private $statusArr = array(
    	'未启用','启用'
    );

    //返回分摊记录状态
    function rtStatus($val){
		if(isset($this->statusArr[$val])){
			return $this->statusArr[$val];
		}else{
			return $val;
		}
    }

	/**
	 * 费用分摊方法
	 * TODO 各类对象策略调用未完全实现，目前只实现工程部分
	 */
	function share_d($object){
		if(empty($object)){
			return '没有录入分摊数据';
		}else{
			//缓存的付款申请id字段
			$payapplyId = $object['payapplyId'];
			//缓存的付款申请编号
			$payapplyCode = $object['payapplyCode'];
			//获取付款申请信息
			$payablesapplyDao = new model_finance_payablesapply_payablesapply();
			$isEffective = $payablesapplyDao->isEffective_d($payapplyId);
			//id数组
			$idArr = array();

			//工程项目缓存
			$esmprojectArr = array();

			try{
				$this->start_d();

				foreach($object['detail'] as $key => $val){
					//加载信息
					$object['detail'][$key] = $this->valueDeal_d($object['detail'][$key]);
					$object['detail'][$key] = $this->processDatadict($object['detail'][$key]);
					$object['detail'][$key]['payapplyId'] = $payapplyId;
					$object['detail'][$key]['payapplyCode'] = $payapplyCode;
					$object['detail'][$key]['status'] = $isEffective ? 1 : 0;

					//如果存在工程项目，加项目缓存数组，等待处理
					if($val['shareType'] == 'CWFYFT-03' && !in_array($val['shareObjCode'],$esmprojectArr)){
						array_push($esmprojectArr,$val['shareObjCode']);
					}

					//如果存在原项目编号，加入项目缓存数组，等待处理
					if(!empty($val['orgProjectCode'])  && !in_array($val['orgProjectCode'],$esmprojectArr)){
						array_push($esmprojectArr,$val['orgProjectCode']);
					}
				}
				//批量处理数据
				$this->saveDelBatch($object['detail']);

				//获取付款申请分摊的所有费用
				$allShareMoney = $this->getAllShareMoney_d($payapplyId);

				//付款申请单部分处理
				$payablesapplyDao->updateShareInfo_d($payapplyId,$allShareMoney);

				//如果工程项目数组不为空,更新各项目的分摊费用
				if(!empty($esmprojectArr)){
					$esmprojectRows = $this->getEffectShareMoney_d($esmprojectArr);
					//实例化工程项目类
					$esmprojectDao = new model_engineering_project_esmproject();
					foreach($esmprojectRows as $key => $val){
						$esmprojectDao->updateFeePayables_d($val['shareObjCode'],$val['shareMoney']);

						$k = array_search( $val['shareObjCode'] ,$esmprojectArr);
						unset($esmprojectArr[$k]);
					}
					//如果项目缓存数组不为空，则将项目的分摊费用更新为0
					if(!empty($esmprojectArr)){
						foreach($esmprojectArr as $key => $val){
							$esmprojectDao->updateFeePayables_d($val,0);
						}
					}
				}

				$this->commit_d();
				return true;
			}catch(exception $e){
				$this->rollBack();
				throw $e;
				return false;
			}
		}
	}

	/**
	 * 对应类型设值处理
	 */
	function valueDeal_d($object){
		if(isset($object['shareType'])){

			switch ($object['shareType']) {
				case 'CWFYFT-01'://类型为人员
					$object['userName'] = $object['shareObjName'];
					$object['userId'] = $object['shareObjCode'];
					$object['deptName'] = '';
					$object['deptId'] = '';
					$object['projectId'] = '';
					$object['projectName'] = '';
					$object['projectCode'] = '';
					break;
				case 'CWFYFT-02'://类型为部门
					$object['deptId'] = $object['shareObjId'];
					$object['deptName'] = $object['shareObjName'];
					$object['userName'] = '';
					$object['userId'] = '';
					$object['projectId'] = '';
					$object['projectName'] = '';
					$object['projectCode'] = '';
					break;
				case 'CWFYFT-03'://工程项目
					$object['projectId'] = $object['shareObjId'];
					$object['projectName'] = $object['shareObjName'];
					$object['projectCode'] = $object['shareObjCode'];
					$object['userName'] = '';
					$object['userId'] = '';
					$object['deptId'] = '';
					$object['deptName'] = '';
					break;
				case 'CWFYFT-04'://研发项目
					$object['projectId'] = $object['shareObjId'];
					$object['projectName'] = $object['shareObjName'];
					$object['projectCode'] = $object['shareObjCode'];
					$object['userName'] = '';
					$object['userId'] = '';
					$object['deptId'] = '';
					$object['deptName'] = '';
					break;

				default:break;
			}

			return $object;
		}else{
			return $object;
		}
	}

	/**
	 * 获取付款申请的所有费用分摊金额
	 */
	function getAllShareMoney_d($payapplyId){
		$this->searchArr = array('payapplyId' => $payapplyId);
		$rs = $this->list_d('count_all');
		if(is_array($rs)){
			return $rs[0]['shareMoney'];
		}else{
			return 0;
		}
	}

	/**
	 * 获取有效的费用分摊金额 - 工程项目暂用 (根据工程项目编号数组)
	 * param array('AA','BB')
	 */
	function getEffectShareMoney_d($projectCodes,$isStatus = true){
		if(is_array($projectCodes)){
			$projectCodes = implode($projectCodes,',');
		}
		$this->searchArr = array(
			'projectCodeArr' => $projectCodes ,
			'shareType' => 'CWFYFT-03'
		);
		if($isStatus){
			$this->searchArr['status'] = 1;
		}

		$this->groupBy = 'projectCode';
		return $this->listBySqlId('count_shareObj');
	}

	/**
	 * 付款申请关闭时，使费用分摊信息失效
	 */
	function closeShare_d($payapplyId){
		try{
			$this->start_d();

			//获取付款申请内的项目串
			$esmprojectCodeStr = $this->get_table_fields($this->tbl_name,'shareType ="CWFYFT-03" and payapplyId = '. $payapplyId ,'group_concat(projectCode)');
			$esmprojectArr = explode(',',$esmprojectCodeStr);

			//禁用费用分摊明细
			$this->update(array('payapplyId' => $payapplyId),array('status' => 0));

			if($esmprojectCodeStr){
				$rs = $this->getEffectShareMoney_d($esmprojectCodeStr);
//				echo "<pre>";
//				print_r($rs);
//				print_r($esmprojectArr);
				if(!empty($rs)){
					//实例化工程项目类
					$esmprojectDao = new model_engineering_project_esmproject();
					foreach($rs as $key => $val){
						$esmprojectDao->updateFeePayables_d($val['shareObjCode'],$val['shareMoney']);

						$k = array_search( $val['shareObjCode'] ,$esmprojectArr);
						unset($esmprojectArr[$k]);
					}
					//如果项目缓存数组不为空，则将项目的分摊费用更新为0
					if(!empty($esmprojectArr)){
						foreach($esmprojectArr as $key => $val){
							$esmprojectDao->updateFeePayables_d($val,0);
						}
					}
				}
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * 根据付款申请id获取费用分摊信息
	 */
	function getShareInfo_d($payapplyId){
		$this->searchArr = array('payapplyId' => $payapplyId);
		$this->asc = false;
		return $this->list_d();
	}

	/**************************** 页面显示构造 ******************************/
	/**
	 * 编辑费用分摊
	 */
	function initShareEdit_v($rows){
		if(!empty($rows)){
			$i = 0;
			//返回的字符串
			$str = null;
			$datadictArr = $this->getDatadicts ( 'CWFYFT' );

			//获取费用类型
			$otherdatasDao = new model_common_otherdatas();

			foreach($rows as $key => $val){
				$i ++;
//				print_r($val);
				//分摊类型
				$productTypeStr = $this->getDatadictsStr ( $datadictArr ['CWFYFT'],$val['shareType']);

				$feeTypeHidden = 'feeType' . $i . 'Hidden';
				$str.=<<<EOT
					<tr align="center">
						<td>$i</td>
						<td>
					    	<select name="payablescost[detail][$i][shareType]" id="shareType$i" class="txtmiddle" onchange="changeShareType($i,this.value)">$productTypeStr</select>
						</td>
						<td>
							<input type="text" class="txt" name="payablescost[detail][$i][shareObjName]" id="shareObjName$i" value="{$val['shareObjName']}" readonly="readonly"/>
							<input type="text" class="txt" name="payablescost[detail][$i][shareObjCode]" id="shareObjCode$i" value="{$val['shareObjCode']}" readonly="readonly" style="display:none"/>
							<input type="hidden" name="payablescost[detail][$i][shareObjId]" id="shareObjId$i" value="{$val['shareObjId']}"/>
							<input type="hidden" name="payablescost[detail][$i][orgProjectCode]" value="{$val['projectCode']}"/>
						</td>
						<td>
							<select name="payablescost[detail][$i][feeType]" id="feeType$i" class="txtmiddle"><option></option></select>
							<input type="hidden" id="$feeTypeHidden" value="{$val['feeType']}"/>
						</td>
						<td>
							<input type="text" class="txtmiddle formatMoney" name="payablescost[detail][$i][shareMoney]" id="shareMoney$i" value="{$val['shareMoney']}" onblur="reCalMoney()"/>
							<input type="hidden" name="payablescost[detail][$i][id]" id="id$i" value="{$val['id']}"/>
						</td>
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行"/>
						</td>
					</tr>
EOT;
			}
			return $str;
		}else{
			return false;
		}
	}

	/**
	 * 查看费用分摊
	 */
	function initShareView_v($rows){
		if(!empty($rows)){
			$i = 0;
			//返回的字符串
			$str = null;
			$shareMoneyCount = 0;
			foreach($rows as $key => $val){
				$i ++;
				$shareMoneyCount = bcadd($val['shareMoney'],$shareMoneyCount,2);
				//update chenrf 加超链接
				if(trim($val['shareType'])=='售后费用')
					$val['shareObjName']='<a href="?model=contract_contract_contract&action=toViewTab&id='.$val['shareObjId'].'" target="_blank">'.$val['shareObjName'].'</a>';
				$str.=<<<EOT
					<tr align="center">
						<td>$i</td>
						<td>{$val['shareTypeName']}</td>
						<td>
							{$val['shareObjName']}
						</td>
						<td>
							{$val['feeType']}
						</td>
						<td>
							<span class="formatMoney">{$val['shareMoney']}</span>
						</td>
					</tr>
EOT;
			}
			$str.=<<<EOT
					<tr class="tr_count" align="center">
						<td></td>
						<td>合计</td>
						<td>
						</td>
						<td>
						</td>
						<td>
							<span class="formatMoney">$shareMoneyCount</span>
						</td>
					</tr>
EOT;

			return $str;
		}else{
			return "<tr align='center'><td colspan='5'>暂无分摊明细信息</td></tr>";
		}
	}

	/**************************** S 导入导出部分 ***********************/
	/**
	 * 导入
	 * @param 1是 id ，2是编号
	 */
	function excelIn_d($checkType){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$payablesapplyDao = new model_finance_payablesapply_payablesapply();//付款申请对象
		$payablesapplyArr = array();//付款申请缓存数组
		$payablesapplyIndexArr = array();//付款申请索引数组
		$datadictArr = array();//数据字典数组
		$datadictDao = new model_system_datadict_datadict();//数据字典对象
		$otherdatasDao = new model_common_otherdatas();//其他数据对象
		$deptArr = array();//部门缓存
		$userArr = array();//人员缓存
		$rdprojectArr = array();//研发项目缓存
		$feeTypeArr = array();//费用类型缓存
		$esmprojectDao = new model_engineering_project_esmproject();//工程项目对象
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				//行数组循环
				foreach($excelData as $key => $val){
					$val[0] = trim($val[0]);
					$val[1] = trim($val[1]);
					$val[2] = trim($val[2]);
					$val[3] = trim($val[3]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3])){
						continue;
					}else{
						echo "<pre>";
//						print_r($val);
						//重置插入数组
						$inArr = array();

						//付款申请索引字段
						if($val[0]){
							if(!isset($payablesapplyArr[$val[0]])){
								if($checkType == 1){
									$rs = $payablesapplyDao->find(array('id' => $val[0]));
								}else{
									$rs = $payablesapplyDao->find(array('formNo' => $val[0]));
								}
								if($rs){
									$payablesapplyArr[$val[0]] = $rs;
									array_push($payablesapplyIndexArr,$val[0]);
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!不存在的付款申请';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							if($payablesapplyArr[$val[0]]['ExaStatus'] != AUDITED || $payablesapplyArr[$val[0]]['status'] == 'FKSQD-04'){
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '插入失败!付款申请单状态不满足费用分摊条件';
								array_push( $resultArr,$tempArr );
								continue;
							}
							if($payablesapplyArr[$val[0]]['isRed'] == 1){
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '插入失败!退款申请不能进行费用分摊';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['payapplyId'] = $payablesapplyArr[$val[0]]['id'];
							$inArr['payapplyCode'] = $payablesapplyArr[$val[0]]['formNo'];
							$inArr['status'] = 1;
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!付款申请索引字段必填';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//分摊类型
						if($val[1]){
							if(!isset($datadictArr[$val[1]])){
								$rs = $datadictDao->getCodeByName('CWFYFT',$val[1]);
								if(!empty($rs)){
									$shareType = $datadictArr[$val[1]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '<span class="red">导入失败!不存在的分摊类型:'.$val[1].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$shareType = $datadictArr[$val[1]]['code'];
							}
							$inArr['shareType'] = $shareType;
							$inArr['shareTypeName'] = $val[1];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有填入分摊类型';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//分摊对象
						if($val[2]){
							switch ($inArr['shareType']) {
								case 'CWFYFT-01':
									if(!isset($userArr[$val[2]])){
										$rs = $otherdatasDao->getUserInfo($val[2]);
										if(!empty($rs)){
											$userArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '第' . $actNum .'行数据';
											$tempArr['result'] = '更新失败!不存在的用户名称';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['userName'] = $val[2];
									$inArr['shareObjCode'] = $inArr['userId'] = $userArr[$val[2]]['USER_ID'];
									break;
								case 'CWFYFT-02' :
									if(!isset($deptArr[$val[2]])){
										$rs = $otherdatasDao->getDeptInfo_d($val[2]);
										if(!empty($rs)){
											$deptArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '第' . $actNum .'行数据';
											$tempArr['result'] = '更新失败!不存在的部门名称';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['deptName'] = $val[2];
									$inArr['shareObjId'] = $inArr['deptId'] = $deptArr[$val[2]]['DEPT_ID'];
									break;
								case 'CWFYFT-03' :
									if(!isset($esmprojectArr[$val[2]])){
										$rs = $esmprojectDao->find(array('projectCode'=> $val[2]),null,'projectName,id,projectCode');
										if(!empty($rs)){
											$esmprojectArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '第' . $actNum .'行数据';
											$tempArr['result'] = '更新失败!不存在的工程项目';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['projectName'] = $esmprojectArr[$val[2]]['projectName'];
									$inArr['shareObjCode'] = $inArr['projectCode'] = $val[2];
									$inArr['shareObjId'] = $inArr['projectId'] = $esmprojectArr[$val[2]]['id'];

									break;
								case 'CWFYFT-04' :
									if(!isset($rdprojectArr[$val[2]])){
										$rs = $otherdatasDao->getRdproject($val[2]);
										if(!empty($rs)){
											$rdprojectArr[$val[2]] = $rs;
										}else{
											$tempArr['docCode'] = '第' . $actNum .'行数据';
											$tempArr['result'] = '更新失败!不存在的研发项目';
											array_push( $resultArr,$tempArr );
											continue;
										}
									}
									$inArr['shareObjName'] = $inArr['projectName'] = $rdprojectArr[$val[2]]['projectName'];
									$inArr['shareObjCode'] = $inArr['projectCode'] = $val[2];
									$inArr['shareObjId'] = $inArr['projectId'] = $rdprojectArr[$val[2]]['projectId'];

									break;
								default:break;
							}
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有填入分摊对象';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//费用类型
						if($val[3]){
							if(!isset($feeTypeArr[$val[3]])){
								$rs = $otherdatasDao->issetFeeType($val[3]);
								if($rs){
									$feeTypeArr[$val[3]] = $val[3];
								}else{
									$tempArr['docCode'] = '第' . $actNum .'条数据';
									$tempArr['result'] = '插入失败!请使用报销系统中的费用类型';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
							$inArr['feeType'] = $val[3];
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!没有填入费用类型';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//分摊费用
						$shareMoney = $val[4] === '' ? 'NONE' : sprintf("%f",abs(trim($val[4])));
						if($shareMoney != 'NONE'&& $shareMoney != 0){
							$inArr['shareMoney'] = $shareMoney;
						}else{
							$tempArr['docCode'] = '第' . $actNum .'条数据';
							$tempArr['result'] = '插入失败!分摊费用不能为空或0';
							array_push( $resultArr,$tempArr );
							continue;
						}

						print_r($inArr);

						try{
							$this->start_d();
							$thisShareMoney = bcadd($payablesapplyArr[$val[0]]['shareMoney'],$shareMoney,2);

							if($payablesapplyArr[$val[0]]['payMoney'] < $thisShareMoney){
								$tempArr['docCode'] = '第' . $actNum .'条数据';
								$tempArr['result'] = '插入失败!分摊费用已超出付款申请金额';
								array_push( $resultArr,$tempArr );
								continue;
							}

							//插入数据
							$this->add_d($inArr,true);
							//更新付款申请的分摊状态和金额
							if($checkType == 1){
								$payablesapplyDao->updateShareInfo_d($val[0],$thisShareMoney);
							}else{
								$payablesapplyDao->updateShareInfo_d($val[0],$thisShareMoney,'formNo');
							}
							//将分摊金额加入缓存
							$payablesapplyArr[$val[0]]['shareMoney'] = $thisShareMoney;

							if($inArr['shareType'] == 'CWFYFT-03'){
								$rs = $this->getEffectShareMoney_d($val[2]);
								$esmprojectDao->updateFeePayables_d($val[2],$val['shareMoney']);

								$esmprojectRows = $this->getEffectShareMoney_d($val[2]);
								//实例化工程项目类
								foreach($esmprojectRows as $key => $val){
									$esmprojectDao->updateFeePayables_d($val['shareObjCode'],$val['shareMoney']);
								}
							}

							$this->commit_d();

							$tempArr['result'] = '插入成功';
							$tempArr['docCode'] = '第' . $actNum .'条数据';
						}catch(exception $e){
							$this->rollBack();
							$tempArr['result'] = '插入失败';
							$tempArr['docCode'] = '第' . $actNum .'条数据';
						}
						array_push( $resultArr,$tempArr );

					}
				}
//				print_r($payablesapplyIndexArr);
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
		}
	}
	/**************************** E 导入导出部分 ***********************/
	/**
	 *
	 * 分摊明细（包含临时导入信息）
	 */
	function listCost($payapplyId,$createId){
		$sql=' payapplyId="'.$payapplyId.'" or payapplyId is null and createId= "'.$createId.'"';
		return $this->findAll($sql);
	}
}
?>