<?php

/**
 * @author Show
 * @Date 2011年12月10日 星期六 15:03:50
 * @version 1.0
 * @description:项目资源计划(oa_esm_project_resources) Model层
 */
class model_engineering_resources_esmresources extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_resources";
        $this->sql_map = "engineering/resources/esmresourcesSql.php";
        parent::__construct();
    }

    /****************************业务方法***************************/

    /**
     * 获取项目信息
     */
    function getEsmprojectInfo_d($projectId)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,projectName,planBeginDate,planEndDate,expectedDuration');
    }

    /**
     * 获取活动信息
     */
    function getActivityInfo_d($activityId)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->find(array('id' => $activityId), null, 'activityName,planBeginDate,planEndDate,days,workContent,remark');
    }

    /**
     * 获取活动信息
     */
    function getActivityArr_d($projectId)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->findAll(array('projectId' => $projectId), 'lft', 'id,activityName,planBeginDate,planEndDate,lft,rgt,days,workContent,remark,parentId');
    }

    /**
     * 获取活动的下级活动id
     */
    function getUnderTreeIds_d($activityId, $lft, $rgt)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->getUnderTreeIds_d($activityId, $lft, $rgt);
    }

    /**
     * 判断是否有变更数据
     */
    function isChanging_d($projectId, $isCreateNew)
    {
        //判断是否已经存在变更申请
        $esmchangeDao = new model_engineering_change_esmchange();
        return $esmchangeDao->getChangeId_d($projectId, $isCreateNew);
    }

    /*********************** 变更部分处理 *************************/
    /**
     * 变更信息获取
     */
    function getChange_d($uid)
    {
        $esmchangeresDao = new model_engineering_change_esmchangeres();
        return $esmchangeresDao->get_d($uid);
    }

    /**
     * 变更状态还原
     */
    function revertChangeInfo_d($projectId)
    {
        return $this->update(array('projectId' => $projectId), array('changeAction' => '', 'isChanging' => '0'));
    }

    /***************************** 内部方法 *************************/
    /**
     * 重写add_d
     */
    function add_d($object)
    {
        try {
            $this->start_d();

            //判断此修改是否属于变更
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                //预算变更类
                $esmchangeresDao = new model_engineering_change_esmchangeres();
                $esmchangeresDao->add_d($object, $this);
            } else {
                //新增方法
                parent::add_d($object, true);

                //更新项目信息
                $this->updateProject_d($object);
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 复制资源计划 批量新增方法
     */
    function batchAdd_d($object)
    {
//		echo "<pre>";
//		print_r($object);
//		die();
        if (!is_array($object)) {
            throw new Exception ("传入对象不是数组！");
        }
        try {
            $this->start_d();

            $returnObjs = array();
            foreach ($object as $val) {
                $val = $this->addCreateInfo($val);
                $isDelTag = isset($val['isDelTag']) ? $val['isDelTag'] : NULL;
                if (empty ($val['id']) && $isDelTag == 1) {

                } else if (empty ($val['id'])) {
                    $id = $this->add_d($val);
                    $val['id'] = $id;
                    array_push($returnObjs, $val);
                } else if ($isDelTag == 1) {
                    $this->deletes($val['id']);
                } else {
                    $this->edit_d($val);
                    array_push($returnObjs, $val);
                }
            }

            //更新项目信息
            $this->updateProject_d($val);

            $this->commit_d();
            return $returnObjs;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重写edit_d
     */
    function edit_d($object)
    {
        try {
            $this->start_d();

            $esmchangeresDao = new model_engineering_change_esmchangeres();
            //如果存在orgId ,则定义为修改变更的预算
            if ($object['orgId'] != -1) {
                //预算变更类
                $esmchangeresDao->editOrg_d($object, $this);
            } else {
                //判断此修改是否属于变更
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                    //预算变更类
                    $esmchangeresDao->edit_d($object, $this);
                } else {
                    parent::edit_d($object, true);

                    //更新项目的现场预算
                    $this->updateProject_d($object);
                }
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * @desription 删除树节点及下属节点
     *
     */
    function deletes_d($id, $changeId, $projectId)
    {
        if (empty($id) && empty($changeId)) {
            return false;
        }
        //初始化项目
        $esmprojectDao = new model_engineering_project_esmproject();
        //预算变更类
        $esmchangeresDao = new model_engineering_change_esmchangeres();
        try {
            $this->start_d();

            if ($id) {
                //判断此修改是否属于变更
                if ($esmprojectDao->actionIsChange_d($projectId)) {
                    $esmchangeresDao->deletes_d($id, $this, $projectId);
                } else {
                    //删除预算项
                    parent::deletes($id);

                    //更新项目的现场预算
                    $this->updateProject_d(array('projectId' => $projectId));
                }
            }

            //变更部分处理
            if ($changeId) {
                $esmchangeresDao->deletesChange_d($changeId, $this, $projectId);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 批量处理 - 设备
     */
    function dealBatch_d($object)
    {
        $projectId = null;
        try {
            $this->start_d();
            //批量处理
            if (is_array($object)) {
                foreach ($object as $val) {
                    if (empty($projectId)) {
                        $projectId = $val['projectId'];
                    }
                    //附加信息
                    $val['dealManId'] = $_SESSION['USER_ID'];
                    $val['dealManName'] = $_SESSION['USERNAME'];
                    $val['dealDate'] = day_date;
                    parent::edit_d($val, true);
                }

                //修改项目处理状态
                $rs = $this->find(array('projectId' => $projectId, 'dealStatus' => 'GCZYCLZT-01'), null, 'id');
                if (!is_array($rs)) {
                    $esmprojectDao = new model_engineering_project_esmproject();
                    $esmprojectDao->edit_d(array('id' => $projectId, 'dealStatus' => 1));
                }
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 简单新增
     */
    function addOrg_d($object)
    {
        try {
            return parent::add_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 简单编辑
     */
    function editOrg_d($object)
    {
        try {
            return parent::edit_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 未知方法
     */
    function dealComplated_d($projectId)
    {
        //构建条件数组
        $conditionArr = array(
            'projectId' => $projectId,
            'dealStatus' => 'GCZYCLZT-01'
        );
        //更新数组
        $updateArr = array(
            'dealStatus' => 'GCZYCLZT-02',
            'dealManId' => $_SESSION['USER_ID'],
            'dealManName' => $_SESSION['USERNAME'],
            'dealDate' => day_date,
            'dealResult' => '处理完成'
        );

        try {
            $this->start_d();
            $this->update($conditionArr, $updateArr);

            //项目部分处理状态修改
            $esmprojectDao = new model_engineering_project_esmproject();
            $esmprojectArr = array('id' => $projectId, 'dealStatus' => 1);
            $esmprojectDao->edit_d($esmprojectArr, false);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /*************************** 逻辑处理方法 内外部均可 *************************/

    /**
     * 更新项目信息 - 统一调用方法
     * 数组需要包含 projectId
     */
    function updateProject_d($object)
    {
        try {
            $this->start_d();

            //获取项目当前人力预算
            $this->searchArr = array('projectId' => $object['projectId']);
            $rs = $this->listBySqlId('count_all');

            //实例化项目
            $esmprojectDao = new model_engineering_project_esmproject();
            $esmproject = array('budgetEqu' => $rs[0]['amount']);
            //更新项目人力预算
            $esmprojectDao->updateProject_d($object['projectId'], $esmproject);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 获取项目设备预算
     */
    function getProjectBudget_d($projectId)
    {
        //获取项目当前人力预算
        $this->searchArr = array('projectId' => $projectId);
        $rs = $this->listBySqlId('count_all');
        if ($rs[0]['amount']) {
            return $rs[0]['amount'];
        } else {
            return 0;
        }
    }

    /**
     * 更新未形成申请单的设备
     */
    function updateNoApplyResource_d($applyInfo, $projectId)
    {
        try {
            $sql = "update " . $this->tbl_name . " set applyId = " . $applyInfo['applyId'] . ",applyNo = '" .
                $applyInfo['applyNo'] . "' where projectId = '" . $projectId . "' and (applyNo is null or applyNo = '')";
            return $this->query($sql);
        } catch (exception $e) {
            throw $e;
        }
    }

    /**
     * 获取项目的设备预算
     */
    function getProjectResources_d($projectId)
    {
        return $this->findAll(array('projectId' => $projectId));
    }

    //变更 编辑任务时设置状态
    //操作有三类，add ,edit ,delete
    function changeInfoSet_d($id, $thisAction = 'add')
    {
        return $this->update(array('id' => $id), array('changeAction' => $thisAction, 'isChanging' => 1));
    }

    /**
     * 返回设备预算
     * @param $projectId
     * @param int $isChanging
     * @return array|bool
     */
    function getResourcesCount_d($projectId, $isChanging = 0)
    {
        $this->searchArr = array('projectId' => $projectId);
        if ($isChanging) {
            $objArr = $this->listBySqlId('count_change');
        } else {
            $objArr = $this->listBySqlId('count_all');
        }
        return !empty($objArr) ? $objArr[0] : array(
            'number' => 0, 'useDays' => 0, 'amount' => 0
        );
    }

    /************************************* 页面渲染部分 ****************************/
    /**
     * 渲染审批页面
     */
    function initViewPage_d($activityArr)
    {
        if ($activityArr) {
//			print_r($activityArr);
            $str = null;
            //项目费用合计
            $projectCount = array('amount' => 0, 'number' => 0, 'useDays' => 0);
            //标识位
            $mark = null;
            //级别
            $level = 0;
            //缓存任务数组
            $activityCache = array();

//			echo "<pre>";
//			print_r($activityArr);
            foreach ($activityArr as $val) {
                $activityCache[$val['id']] = $val;

                $appendStr = $this->rtAppendStr_v($level);
                if ($val['parentId'] == PARENT_ID) {
                    //重置标志位
                    $mark = $val['id'];
                    //重置级数
                    $level = 0;
                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">$val[activityName]</td>
							<td colspan="8"></td>
						</tr>
EOT;
                } else if ($mark == $val['parentId']) {
                    $level++;
                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">└ $val[activityName]</td>
							<td colspan="8"></td>
						</tr>
EOT;
                } else {
                    $showStr = $appendStr . '└ ' . $val['activityName'];

                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">$showStr</td>
							<td colspan="8"></td>
						</tr>
EOT;
                }
                //费用数组
                $rs = $this->findAll(array('activityId' => $val['id']));
                //任务费用合计数组
                $activityCount = array('amount' => 0, 'number' => 0, 'useDays' => 0);

                if ($rs) {
                    foreach ($rs as $v) {
                        $str .= <<<EOT
							<tr class="tr_even">
								<td></td>
								<td>$v[resourceTypeName]</td>
								<td><a href="javascript:void(0)" onclick="showThickboxWin('?model=engineering_resources_esmresources&action=init&perm=view&id=$v[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900')">$v[resourceName]</a></td>
								<td><span class="formatMoney">$v[number]</span></td>
								<td>$v[planBeginDate]</td>
								<td>$v[planEndDate]</td>
								<td>$v[useDays]</td>
								<td><span class="formatMoney">$v[price]</span></td>
								<td><span class="formatMoney">$v[amount]</span></td>
							</tr>
EOT;
                        //计算合计金额
                        $projectCount['amount'] = bcadd($projectCount['amount'], $v['amount'], 2);
                        $projectCount['number'] = bcadd($projectCount['number'], $v['number'], 2);
                        $projectCount['useDays'] = bcadd($projectCount['useDays'], $v['useDays'], 2);
                        $activityCount['amount'] = bcadd($activityCount['amount'], $v['amount'], 2);
                        $activityCount['number'] = bcadd($activityCount['number'], $v['number'], 2);
                        $activityCount['useDays'] = bcadd($activityCount['useDays'], $v['useDays'], 2);
                    }
                    $str .= <<<EOT
						<tr class="tr_count">
							<td></td>
							<td>任务合计：</td>
							<td></td>
							<td><span class="formatMoney">$activityCount[number]</span></td>
							<td></td>
							<td></td>
							<td><span class="formatMoney">$activityCount[useDays]</span></td>
							<td></td>
							<td><span class="formatMoney">$activityCount[amount]</span></td>
						</tr>
EOT;
                }
            }
            $str .= <<<EOT
				<tr class="tr_count">
					<td></td>
					<td>项目合计：</td>
					<td></td>
					<td><span class="formatMoney">$projectCount[number]</span></td>
					<td></td>
					<td></td>
					<td><span class="formatMoney">$projectCount[useDays]</span></td>
					<td></td>
					<td><span class="formatMoney">$projectCount[amount]</span></td>
				</tr>
EOT;
            return $str;
        } else {
            return "<tr><td colspan='8'>项目没有对应预算信息</td></tr>";
        }
    }

    /**
     * 返回前置空格
     */
    function rtAppendStr_v($level)
    {
        if ($level == 0) {
            return "";
        }
        $str = "";
        for ($i = 0; $i < $level; $i++) {
            $str .= "&nbsp;&nbsp;&nbsp;";
        }
        return $str;
    }

    /**
     * 项目导入功能
     */
    function addExecelData_d($projectId, $activityId)
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); // 结果数组
        $tempArr = array();
        // 判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            // echo "<pre>";
            //print_r($excelData);
            // die();
            if (is_array($excelData)) {
                // 行数组循环
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    // 1.导入设备类型
                    $esmresourcesType = trim($val[0]);
                    // 2.导入资源名称（资源名称一定要存在）
                    $esmresourcesName = trim($val[1]);
                    // 3.设备数量
                    $esmresourcesNum = trim($val[2]);
                    // 4.设备领用日期
                    $val[3] = trim($val[3]);
                    if (!is_numeric($val[3])) {
                        $esmresourceBorrowDate = $val[3];
                    } else {
                        $esmresourceBorrowDate = util_excelUtil::exceltimtetophp($val[3]);
                    }
                    // 5.设备归还日期
                    $val[4] = trim($val[4]);
                    if (!is_numeric($val[4])) {
                        $esmresourcesReturnDate = $val[4];
                    } else {
                        $esmresourcesReturnDate = util_excelUtil::exceltimtetophp($val[4]);
                    }

                    //采用条件查询语句，在设备表查找到匹配的设备名称和类型，就把该设备信息取出
                    if (!empty ($esmresourcesName)) {
                        $result = util_curlUtil::getDataFromAWS('asset', 'ClassifiDetailAslp', array(
                            'pname' => util_jsonUtil::iconvGB2UTF($esmresourcesType), 'page' => 1,
                            'pageSize' => 1, 'nameSearch' => util_jsonUtil::iconvGB2UTF($esmresourcesName)
                        ), array(), false);
                        $data = util_jsonUtil::decode($result['data'], true);

                        if (empty($data['data']['array'])) {
                            $tempArr['docCode'] = '第' . $actNum . '行数据';
                            $tempArr['result'] = '导入失败!匹配不到对应设备！';
                            array_push($resultArr, $tempArr);
                            continue;
                        } else {
                            $resourceId = 9999;
                            $budgetPrice = $data['data']['array'][0]['INPRICE'];
                            $unit = $data['data']['array'][0]['MEASUREMENTUNIT'];
                            $className = $data['data']['array'][0]['ASSETSCLASS'];
                        }

                        $sql2 = "SELECT projectCode,projectName FROM oa_esm_project WHERE id ='$projectId'";
                        $sql_c2 = $this->_db->getArray($sql2); //搜索出projectId对应的项目编号和项目名，并存放于一个数组

                        //计算设备租用金额=单设备折旧 * 使用日期
                        $usedays = (strtotime($esmresourcesReturnDate) -
                                strtotime($esmresourceBorrowDate)) / 86400 + 1;
                        $amount = bcmul(bcmul($budgetPrice, ($usedays), 2), $esmresourcesNum, 2);

                        if ($resourceId) {
                            if (is_numeric($val[3]) && is_numeric($val[4])) {
                                //结果数组拼装
                                $inArr = array(
                                    'resourceId' => $resourceId,
                                    'resourceTypeName' => $className,
                                    'resourceName' => $esmresourcesName,
                                    'number' => $esmresourcesNum,
                                    'planBeginDate' => $esmresourceBorrowDate,
                                    'planEndDate' => $esmresourcesReturnDate,
                                    'useDays' => $usedays,
                                    'price' => $budgetPrice,
                                    'amount' => $amount,
                                    'resourceTypeId' => 0, //设备类型号
                                    'unit' => $unit, //单位
                                    'projectCode' => $sql_c2[0]['projectCode'],
                                    'projectName' => $sql_c2[0]['projectName'],
                                    'projectId' => $projectId,
                                    'activityId' => $activityId
                                );
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '行数据';
                                $tempArr['result'] = '导入失败!请检查填写时间格式！';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '行数据';
                            $tempArr['result'] = '导入失败!设备名称或类型错误！';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                    } else {
                        $tempArr['docCode'] = '第' . $actNum . '行数据';
                        $tempArr['result'] = '导入失败!设备名称不能为空!';
                        array_push($resultArr, $tempArr);
                        continue;
                    }

                    try {
                        $this->start_d();
                        // 新增设备数据
                        $this->add_d($inArr);

                        // 更新项目信息
                        $this->updateProject_d($inArr);

                        $this->commit_d();

                        $tempArr['docCode'] = '第' . $actNum . '行数据';
                        $tempArr['result'] = '导入成功!';
                        array_push($resultArr, $tempArr);
                    } catch (Exception $e) {
                        $this->rollBack();

                        $tempArr['docCode'] = '第' . $actNum . '行数据';
                        $tempArr['result'] = '导入失败!';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }
}