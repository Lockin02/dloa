<?php

/**
 * @author huangzf
 * @Date 2011��11��1�� 11:20:04
 * @version 1.0
 * @description:ϵͳ��־���� Model��
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
     * ��ʾ�޸�ģ��
     * @param $rows
     * @return string
     */
    function showItemAtEdit($rows)
    {
        if ($rows) {
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
            foreach ($rows as $val) {
                $seNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" >
                    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
     * ��ʾ�޸�ģ��
     * @param $rows
     * @return string
     */
    function showItemAtView($rows)
    {
        if ($rows) {
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
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
     * ��������
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
                throw new Exception ("������Ϣ����������ȷ�ϣ�");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * �޸ı���
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
                throw new Exception ("������Ϣ����������ȷ�ϣ�");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     *
     * ����һ���������־��¼
     * @param $tableName ҵ��������
     * @param $pkValue ҵ����Ϣ����ֵ
     * @param $obj ҵ����������
     * @param string $operationType ��������
     */
    function addObjLog($tableName, $pkValue, $obj, $operationType = '���')
    {
        $logsettingObj = $this->findBy("tableName", $tableName);
        if (is_array($logsettingObj)) {
            $logSetDetailDao = new model_syslog_setting_logsettingdetail ();
            $logSetDetailDao->searchArr['mainId'] = $logsettingObj['id'];
            $logsetDetailArr = $logSetDetailDao->listBySqlId();

            if (is_array($logsetDetailArr)) {
                $columnNameTextArr = array(); //ҵ����������ֶ�
                foreach ($logsetDetailArr as $logsetDetail) {
                    $columnName = $logsetDetail['columnName'];
                    $columnNameTextArr[$columnName] = $logsetDetail['columnText'];
                }

                /*start: ��¼��ע���ֶε���Ϣ */
                $operationDao = new model_syslog_operation_logoperation (); //������־
                $operationObj = array("logSettingId" => $logsettingObj['id'], "operationType" => $operationType, "pkValue" => $pkValue); //������־����
                $contentStr = '<table class="table-border"  width="100%" border="0" cellpadding="0" cellspacing="1" id="itemtable">' .
                    '<thead><tr><td colspan="5" >' .
                    '<font color="red">&nbsp;&nbsp;->' . $operationType .
                    '</font></td></tr><tr class="table-title"><th align="center">���</th><th align="center">�ֶ�</th><th align="center">����</th><th align="center">��ֵ</th><th align="center">��ֵ</th></tr></thead><tbody id="itembody">';
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
                $operationObj['logContent'] = $seNum == 1 ? '�����ݱ䶯' : $contentStr;
                $operationDao->add_d($operationObj, true);
                /*end: ��¼��ע���ֶε���Ϣ*/
            }
        }
    }

    /**
     *
     * ԭ����ʵ��͸�ֵ���ʵ��Աȣ��������µ�����д��ϵͳ������־��
     * @param $tableName ҵ��������
     * @param $oldObj ����ɼ�¼
     * @param $newObj �����¼�¼
     * @param string $operationType ��������
     */
    function compareModelObj($tableName, $oldObj, $newObj, $operationType = '����')
    {
        $logsettingObj = $this->findBy("tableName", $tableName);

        if (is_array($logsettingObj)) {
            $logSetDetailDao = new model_syslog_setting_logsettingdetail ();
            $logSetDetailDao->searchArr['mainId'] = $logsettingObj['id'];
            $logsetDetailArr = $logSetDetailDao->listBySqlId();

            if (is_array($logsetDetailArr)) {
                $columnNameTextArr = array(); //ҵ����������ֶ�
                foreach ($logsetDetailArr as $logsetDetail) {
                    $columnName = $logsetDetail['columnName'];
                    $columnNameTextArr[$columnName] = $logsetDetail['columnText'];
                }

                /*start: �ȽϾ��϶����ע���ֶ���Ϣ,����¼�仯�Ĳ�����¼*/
                $operationDao = new model_syslog_operation_logoperation (); //������־
                $operationItemDao = new model_syslog_operation_logoperationItem (); //������־
                $operationObj = array("logSettingId" => $logsettingObj['id'], "operationType" => $operationType, "pkValue" => $oldObj[$logsettingObj['pkName']]); //������־����
                $contentStr = '<table class="table-border"  width="100%" border="0" cellpadding="0" cellspacing="1" id="itemtable">' . '<thead><tr><td colspan="5" >' . '<font color="red">&nbsp;&nbsp;->����</font></td></tr><tr class="table-title"><th align="center">���</th><th align="center">�ֶ�</th><th align="center">����</th><th align="center">��ֵ</th><th align="center">��ֵ</th></tr></thead><tbody id="itembody">';
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
                $operationObj['logContent'] = $seNum == 1 ? '�����ݱ䶯' : $contentStr;
                $operationDao->add_d($operationObj, true);
                /*end: �ȽϾ��϶����ע���ֶ���Ϣ,����¼�仯�Ĳ�����¼*/
            }
        }
    }


    /**
     * ɾ��һ���������־��¼
     * @param $tableName ҵ��������
     * @param $deleteObj ҵ��ɾ������
     * @param string $operationType  ��������
     */
    function deleteObjLog($tableName, $deleteObj, $operationType = 'ɾ��')
    {
        $logsettingObj = $this->findBy("tableName", $tableName);

        if (is_array($logsettingObj)) {
            $logSetDetailDao = new model_syslog_setting_logsettingdetail ();
            $logSetDetailDao->searchArr['mainId'] = $logsettingObj['id'];
            $logsetDetailArr = $logSetDetailDao->listBySqlId();

            if (is_array($logsetDetailArr)) {
                $columnNameTextArr = array(); //ҵ����������ֶ�
                foreach ($logsetDetailArr as $logsetDetail) {
                    $columnName = $logsetDetail['columnName'];
                    $columnNameTextArr[$columnName] = $logsetDetail['columnText'];
                }

                /*start: ��¼��ע���ֶε���Ϣ */
                $operationDao = new model_syslog_operation_logoperation (); //������־
                $operationObj = array("logSettingId" => $logsettingObj['id'], "operationType" => $operationType, "pkValue" => $deleteObj[$logsettingObj['pkName']]); //������־����
                $contentStr = '<table class="table-border"  width="100%" border="0" cellpadding="0" cellspacing="1" id="itemtable">' . '<thead><tr><td colspan="5" >' . '<font color="red">&nbsp;&nbsp;->ɾ��</font></td></tr><tr class="table-title"><th align="center">���</th><th align="center">�ֶ�</th><th align="center">����</th><th align="center">��ֵ</th><th align="center">��ֵ</th></tr></thead><tbody id="itembody">';
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
                /*end: ��¼��ע���ֶε���Ϣ*/
            }
        }

    }

    /**
     * ͨ��id��ȡ��־������ϸ��Ϣ
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
     * ���ù����嵥�Ĵӱ������id��Ϣ
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