<?php

/**
 * @author huangzf
 * @Date 2011年11月1日 11:20:04
 * @version 1.0
 * @description:系统日志设置 Model层
 */
class model_syslog_setting_logsetting extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_syslog_setting";
        $this->sql_map = "syslog/setting/logsettingSql.php";
        parent::__construct();
    }

    /**
     * 显示修改模板
     * @param $rows
     * @return string
     */
    function showItemAtEdit($rows)
    {
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach ($rows as $val) {
                $seNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" >
                    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
                    </td>
                    <td>
                        $seNum
                    </td>
                    <td>
                        <input type="text" name="logsetting[items][$i][columnName]" id="columnName$i" class="readOnlyTxtNormal" value="{$val['columnName']}" />
                        <input type="hidden" name="logsetting[items][$i][id]" id="id$i" value="{$val['id']}"  />
                    </td>
                    <td>
                        <input type="text" name="logsetting[items][$i][columnText]" id="columnText$i" class="txt" value="{$val['columnText']}" />
                    </td>
                    <td>
                        <input type="text" name="logsetting[items][$i][columnDataType]" id="columnDataType$i" class="txt" value="{$val['columnDataType']}" />
                    </td>
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 显示修改模板
     * @param $rows
     * @return string
     */
    function showItemAtView($rows)
    {
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach ($rows as $val) {
                $seNum = $i + 1;
                $str .= <<<EOT
                    <tr align="center" >
                        <td>
                            $seNum
                        </td>
                        <td>
                            $val[columnName]

                        </td>
                        <td>
                            $val[columnText]
                        </td>
                        <td>
                            $val[columnDataType]
                        </td>
                    </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 新增保存
     * @see model_base::add_d()
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            if (is_array($object['items'])) {
                $id = parent::add_d($object, true);
                $logsetDetailDao = new model_syslog_setting_logsettingdetail ();
                $itemsArr = $this->setItemMainId("mainId", $id, $object['items']);
                $logsetDetailDao->saveDelBatch($itemsArr);
                $this->commit_d();
                return $id;
            } else {
                throw new Exception ("单据信息不完整，请确认！");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 修改保存
     * @see model_base::edit_d()
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            if (is_array($object['items'])) {
                $editresult = parent::edit_d($object, true);
                $logsetDetailDao = new model_syslog_setting_logsettingdetail ();
                $itemsArr = $this->setItemMainId("mainId", $object['id'], $object['items']);
                $logsetDetailDao->saveDelBatch($itemsArr);
                $this->commit_d();
                return $editresult;
            } else {
                throw new Exception ("单据信息不完整，请确认！");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     *
     * 新增一个对象的日志记录
     * @param $tableName 业务对象表名
     * @param $pkValue 业务信息主键值
     * @param $obj 业务新增对象
     * @param string $operationType 操作类型
     */
    function addObjLog($tableName, $pkValue, $obj, $operationType = '添加')
    {
        $logsettingObj = $this->findBy("tableName", $tableName);
        if (is_array($logsettingObj)) {
            $logSetDetailDao = new model_syslog_setting_logsettingdetail ();
            $logSetDetailDao->searchArr['mainId'] = $logsettingObj['id'];
            $logsetDetailArr = $logSetDetailDao->listBySqlId();

            if (is_array($logsetDetailArr)) {
                $columnNameTextArr = array(); //业务对象设置字段
                foreach ($logsetDetailArr as $logsetDetail) {
                    $columnName = $logsetDetail['columnName'];
                    $columnNameTextArr[$columnName] = $logsetDetail['columnText'];
                }

                /*start: 记录下注册字段的信息 */
                $operationDao = new model_syslog_operation_logoperation (); //操作日志
                $operationObj = array("logSettingId" => $logsettingObj['id'], "operationType" => $operationType, "pkValue" => $pkValue); //操作日志对象
                $contentStr = '<table class="table-border"  width="100%" border="0" cellpadding="0" cellspacing="1" id="itemtable">' .
                    '<thead><tr><td colspan="5" >' .
                    '<font color="red">&nbsp;&nbsp;->' . $operationType .
                    '</font></td></tr><tr class="table-title"><th align="center">序号</th><th align="center">字段</th><th align="center">名称</th><th align="center">旧值</th><th align="center">新值</th></tr></thead><tbody id="itembody">';
                $seNum = 1;
                foreach ($columnNameTextArr as $cKey => $cText) {
                    if (isset($obj[$cKey]) && $obj[$cKey] !== "") {
                        $contentStr .= '<tr class="table-content" align="center" ><td align="center">' . $seNum .
                            '</td><td align="center">' . $cKey . '</td><td align="center">' . $cText .
                            '</td><td> </td><td>' . $obj[$cKey] . '</td></tr>';
                        $seNum++;
                    }
                }
                $contentStr .= '</tbody></table>';
                $operationObj['logContent'] = $seNum == 1 ? '无数据变动' : $contentStr;
                $operationDao->add_d($operationObj, true);
                /*end: 记录下注册字段的信息*/
            }
        }
    }

    /**
     *
     * 原来的实体和赋值后的实体对比，并将更新的内容写入系统操作日志中
     * @param $tableName 业务对象表名
     * @param $oldObj 对象旧记录
     * @param $newObj 对象新记录
     * @param string $operationType 操作类型
     */
    function compareModelObj($tableName, $oldObj, $newObj, $operationType = '更新')
    {
        $logsettingObj = $this->findBy("tableName", $tableName);

        if (is_array($logsettingObj)) {
            $logSetDetailDao = new model_syslog_setting_logsettingdetail ();
            $logSetDetailDao->searchArr['mainId'] = $logsettingObj['id'];
            $logsetDetailArr = $logSetDetailDao->listBySqlId();

            if (is_array($logsetDetailArr)) {
                $columnNameTextArr = array(); //业务对象设置字段
                foreach ($logsetDetailArr as $logsetDetail) {
                    $columnName = $logsetDetail['columnName'];
                    $columnNameTextArr[$columnName] = $logsetDetail['columnText'];
                }

                /*start: 比较旧老对象的注册字段信息,并记录变化的操作记录*/
                $operationDao = new model_syslog_operation_logoperation (); //操作日志
                $operationItemDao = new model_syslog_operation_logoperationItem (); //操作日志
                $operationObj = array("logSettingId" => $logsettingObj['id'], "operationType" => $operationType, "pkValue" => $oldObj[$logsettingObj['pkName']]); //操作日志对象
                $contentStr = '<table class="table-border"  width="100%" border="0" cellpadding="0" cellspacing="1" id="itemtable">' . '<thead><tr><td colspan="5" >' . '<font color="red">&nbsp;&nbsp;->更新</font></td></tr><tr class="table-title"><th align="center">序号</th><th align="center">字段</th><th align="center">名称</th><th align="center">旧值</th><th align="center">新值</th></tr></thead><tbody id="itembody">';
                $seNum = 1;
                foreach ($columnNameTextArr as $cKey => $cText) {
                    if ($oldObj[$cKey] != $newObj[$cKey] && isset($newObj[$cKey])) {
                        $contentStr .= '<tr class="table-content" align="center" ><td align="center">' . $seNum . '</td><td align="center">' . $cKey . '</td><td align="center">' . $cText . '</td><td>' . $oldObj[$cKey] . '</td><td>' . $newObj[$cKey] . '</td></tr>';
                        $seNum++;

                        //edit by huangzf 20130417
                        $operationItemObj = array("logSettingId" => $logsettingObj['id'],
                            "pkValue" => $oldObj[$logsettingObj['pkName']],
                            "columnCcode" => $cKey,
                            "columnCname" => $cText,
                            "oldValue" => $oldObj[$cKey],
                            "newValue" => $newObj[$cKey]);
                        $operationItemDao->add_d($operationItemObj, true);
                    }
                }
                $contentStr .= '</tbody></table>';
                $operationObj['logContent'] = $seNum == 1 ? '无数据变动' : $contentStr;
                $operationDao->add_d($operationObj, true);
                /*end: 比较旧老对象的注册字段信息,并记录变化的操作记录*/
            }
        }
    }


    /**
     * 删除一个对象的日志记录
     * @param $tableName 业务对象表名
     * @param $deleteObj 业务删除对象
     * @param string $operationType  操作类型
     */
    function deleteObjLog($tableName, $deleteObj, $operationType = '删除')
    {
        $logsettingObj = $this->findBy("tableName", $tableName);

        if (is_array($logsettingObj)) {
            $logSetDetailDao = new model_syslog_setting_logsettingdetail ();
            $logSetDetailDao->searchArr['mainId'] = $logsettingObj['id'];
            $logsetDetailArr = $logSetDetailDao->listBySqlId();

            if (is_array($logsetDetailArr)) {
                $columnNameTextArr = array(); //业务对象设置字段
                foreach ($logsetDetailArr as $logsetDetail) {
                    $columnName = $logsetDetail['columnName'];
                    $columnNameTextArr[$columnName] = $logsetDetail['columnText'];
                }

                /*start: 记录下注册字段的信息 */
                $operationDao = new model_syslog_operation_logoperation (); //操作日志
                $operationObj = array("logSettingId" => $logsettingObj['id'], "operationType" => $operationType, "pkValue" => $deleteObj[$logsettingObj['pkName']]); //操作日志对象
                $contentStr = '<table class="table-border"  width="100%" border="0" cellpadding="0" cellspacing="1" id="itemtable">' . '<thead><tr><td colspan="5" >' . '<font color="red">&nbsp;&nbsp;->删除</font></td></tr><tr class="table-title"><th align="center">序号</th><th align="center">字段</th><th align="center">名称</th><th align="center">旧值</th><th align="center">新值</th></tr></thead><tbody id="itembody">';
                $seNum = 1;
                foreach ($columnNameTextArr as $cKey => $cText) {
                    if (isset($deleteObj[$cKey]) && $deleteObj[$cKey] !== "") {
                        $contentStr .= '<tr class="table-content" align="center" ><td align="center">' . $seNum .
                            '</td><td align="center">' . $cKey . '</td><td align="center">' . $cText .
                            '</td><td>' . $deleteObj[$cKey] . ' </td><td></td></tr>';
                        $seNum++;
                    }
                }
                $contentStr .= '</tbody></table>';
                $operationObj['logContent'] = $contentStr;
                $operationDao->add_d($operationObj, true);
                /*end: 记录下注册字段的信息*/
            }
        }

    }

    /**
     * 通过id获取日志设置详细信息
     * @see model_base::get_d()
     */
    function get_d($id)
    {
        $object = parent::get_d($id);
        $logsetDetailDao = new model_syslog_setting_logsettingdetail ();
        $logsetDetailDao->searchArr['mainId'] = $id;
        $object['items'] = $logsetDetailDao->listBySqlId();
        return $object;

    }

    /**
     * 设置关联清单的从表的主表id信息
     */
    function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr)
    {
        $resultArr = array();
        foreach ($iteminfoArr as $value) {
            $value[$mainIdName] = $mainIdValue;
            array_push($resultArr, $value);
        }
        return $resultArr;
    }
}