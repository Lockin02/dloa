<?php

/**
 * @author Show
 * @Date 2011��12��10�� ������ 15:03:50
 * @version 1.0
 * @description:��Ŀ��Դ�ƻ�(oa_esm_project_resources) Model��
 */
class model_engineering_resources_esmresources extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_resources";
        $this->sql_map = "engineering/resources/esmresourcesSql.php";
        parent::__construct();
    }

    /****************************ҵ�񷽷�***************************/

    /**
     * ��ȡ��Ŀ��Ϣ
     */
    function getEsmprojectInfo_d($projectId)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,projectName,planBeginDate,planEndDate,expectedDuration');
    }

    /**
     * ��ȡ���Ϣ
     */
    function getActivityInfo_d($activityId)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->find(array('id' => $activityId), null, 'activityName,planBeginDate,planEndDate,days,workContent,remark');
    }

    /**
     * ��ȡ���Ϣ
     */
    function getActivityArr_d($projectId)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->findAll(array('projectId' => $projectId), 'lft', 'id,activityName,planBeginDate,planEndDate,lft,rgt,days,workContent,remark,parentId');
    }

    /**
     * ��ȡ����¼��id
     */
    function getUnderTreeIds_d($activityId, $lft, $rgt)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->getUnderTreeIds_d($activityId, $lft, $rgt);
    }

    /**
     * �ж��Ƿ��б������
     */
    function isChanging_d($projectId, $isCreateNew)
    {
        //�ж��Ƿ��Ѿ����ڱ������
        $esmchangeDao = new model_engineering_change_esmchange();
        return $esmchangeDao->getChangeId_d($projectId, $isCreateNew);
    }

    /*********************** ������ִ��� *************************/
    /**
     * �����Ϣ��ȡ
     */
    function getChange_d($uid)
    {
        $esmchangeresDao = new model_engineering_change_esmchangeres();
        return $esmchangeresDao->get_d($uid);
    }

    /**
     * ���״̬��ԭ
     */
    function revertChangeInfo_d($projectId)
    {
        return $this->update(array('projectId' => $projectId), array('changeAction' => '', 'isChanging' => '0'));
    }

    /***************************** �ڲ����� *************************/
    /**
     * ��дadd_d
     */
    function add_d($object)
    {
        try {
            $this->start_d();

            //�жϴ��޸��Ƿ����ڱ��
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                //Ԥ������
                $esmchangeresDao = new model_engineering_change_esmchangeres();
                $esmchangeresDao->add_d($object, $this);
            } else {
                //��������
                parent::add_d($object, true);

                //������Ŀ��Ϣ
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
     * ������Դ�ƻ� ������������
     */
    function batchAdd_d($object)
    {
//		echo "<pre>";
//		print_r($object);
//		die();
        if (!is_array($object)) {
            throw new Exception ("������������飡");
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

            //������Ŀ��Ϣ
            $this->updateProject_d($val);

            $this->commit_d();
            return $returnObjs;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��дedit_d
     */
    function edit_d($object)
    {
        try {
            $this->start_d();

            $esmchangeresDao = new model_engineering_change_esmchangeres();
            //�������orgId ,����Ϊ�޸ı����Ԥ��
            if ($object['orgId'] != -1) {
                //Ԥ������
                $esmchangeresDao->editOrg_d($object, $this);
            } else {
                //�жϴ��޸��Ƿ����ڱ��
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                    //Ԥ������
                    $esmchangeresDao->edit_d($object, $this);
                } else {
                    parent::edit_d($object, true);

                    //������Ŀ���ֳ�Ԥ��
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
     * @desription ɾ�����ڵ㼰�����ڵ�
     *
     */
    function deletes_d($id, $changeId, $projectId)
    {
        if (empty($id) && empty($changeId)) {
            return false;
        }
        //��ʼ����Ŀ
        $esmprojectDao = new model_engineering_project_esmproject();
        //Ԥ������
        $esmchangeresDao = new model_engineering_change_esmchangeres();
        try {
            $this->start_d();

            if ($id) {
                //�жϴ��޸��Ƿ����ڱ��
                if ($esmprojectDao->actionIsChange_d($projectId)) {
                    $esmchangeresDao->deletes_d($id, $this, $projectId);
                } else {
                    //ɾ��Ԥ����
                    parent::deletes($id);

                    //������Ŀ���ֳ�Ԥ��
                    $this->updateProject_d(array('projectId' => $projectId));
                }
            }

            //������ִ���
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
     * �������� - �豸
     */
    function dealBatch_d($object)
    {
        $projectId = null;
        try {
            $this->start_d();
            //��������
            if (is_array($object)) {
                foreach ($object as $val) {
                    if (empty($projectId)) {
                        $projectId = $val['projectId'];
                    }
                    //������Ϣ
                    $val['dealManId'] = $_SESSION['USER_ID'];
                    $val['dealManName'] = $_SESSION['USERNAME'];
                    $val['dealDate'] = day_date;
                    parent::edit_d($val, true);
                }

                //�޸���Ŀ����״̬
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
     * ������
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
     * �򵥱༭
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
     * δ֪����
     */
    function dealComplated_d($projectId)
    {
        //������������
        $conditionArr = array(
            'projectId' => $projectId,
            'dealStatus' => 'GCZYCLZT-01'
        );
        //��������
        $updateArr = array(
            'dealStatus' => 'GCZYCLZT-02',
            'dealManId' => $_SESSION['USER_ID'],
            'dealManName' => $_SESSION['USERNAME'],
            'dealDate' => day_date,
            'dealResult' => '�������'
        );

        try {
            $this->start_d();
            $this->update($conditionArr, $updateArr);

            //��Ŀ���ִ���״̬�޸�
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

    /*************************** �߼������� ���ⲿ���� *************************/

    /**
     * ������Ŀ��Ϣ - ͳһ���÷���
     * ������Ҫ���� projectId
     */
    function updateProject_d($object)
    {
        try {
            $this->start_d();

            //��ȡ��Ŀ��ǰ����Ԥ��
            $this->searchArr = array('projectId' => $object['projectId']);
            $rs = $this->listBySqlId('count_all');

            //ʵ������Ŀ
            $esmprojectDao = new model_engineering_project_esmproject();
            $esmproject = array('budgetEqu' => $rs[0]['amount']);
            //������Ŀ����Ԥ��
            $esmprojectDao->updateProject_d($object['projectId'], $esmproject);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ��ȡ��Ŀ�豸Ԥ��
     */
    function getProjectBudget_d($projectId)
    {
        //��ȡ��Ŀ��ǰ����Ԥ��
        $this->searchArr = array('projectId' => $projectId);
        $rs = $this->listBySqlId('count_all');
        if ($rs[0]['amount']) {
            return $rs[0]['amount'];
        } else {
            return 0;
        }
    }

    /**
     * ����δ�γ����뵥���豸
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
     * ��ȡ��Ŀ���豸Ԥ��
     */
    function getProjectResources_d($projectId)
    {
        return $this->findAll(array('projectId' => $projectId));
    }

    //��� �༭����ʱ����״̬
    //���������࣬add ,edit ,delete
    function changeInfoSet_d($id, $thisAction = 'add')
    {
        return $this->update(array('id' => $id), array('changeAction' => $thisAction, 'isChanging' => 1));
    }

    /**
     * �����豸Ԥ��
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

    /************************************* ҳ����Ⱦ���� ****************************/
    /**
     * ��Ⱦ����ҳ��
     */
    function initViewPage_d($activityArr)
    {
        if ($activityArr) {
//			print_r($activityArr);
            $str = null;
            //��Ŀ���úϼ�
            $projectCount = array('amount' => 0, 'number' => 0, 'useDays' => 0);
            //��ʶλ
            $mark = null;
            //����
            $level = 0;
            //������������
            $activityCache = array();

//			echo "<pre>";
//			print_r($activityArr);
            foreach ($activityArr as $val) {
                $activityCache[$val['id']] = $val;

                $appendStr = $this->rtAppendStr_v($level);
                if ($val['parentId'] == PARENT_ID) {
                    //���ñ�־λ
                    $mark = $val['id'];
                    //���ü���
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
							<td align="left">�� $val[activityName]</td>
							<td colspan="8"></td>
						</tr>
EOT;
                } else {
                    $showStr = $appendStr . '�� ' . $val['activityName'];

                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">$showStr</td>
							<td colspan="8"></td>
						</tr>
EOT;
                }
                //��������
                $rs = $this->findAll(array('activityId' => $val['id']));
                //������úϼ�����
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
                        //����ϼƽ��
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
							<td>����ϼƣ�</td>
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
					<td>��Ŀ�ϼƣ�</td>
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
            return "<tr><td colspan='8'>��Ŀû�ж�ӦԤ����Ϣ</td></tr>";
        }
    }

    /**
     * ����ǰ�ÿո�
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
     * ��Ŀ���빦��
     */
    function addExecelData_d($projectId, $activityId)
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); // �������
        $tempArr = array();
        // �жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            // echo "<pre>";
            //print_r($excelData);
            // die();
            if (is_array($excelData)) {
                // ������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    // 1.�����豸����
                    $esmresourcesType = trim($val[0]);
                    // 2.������Դ���ƣ���Դ����һ��Ҫ���ڣ�
                    $esmresourcesName = trim($val[1]);
                    // 3.�豸����
                    $esmresourcesNum = trim($val[2]);
                    // 4.�豸��������
                    $val[3] = trim($val[3]);
                    if (!is_numeric($val[3])) {
                        $esmresourceBorrowDate = $val[3];
                    } else {
                        $esmresourceBorrowDate = util_excelUtil::exceltimtetophp($val[3]);
                    }
                    // 5.�豸�黹����
                    $val[4] = trim($val[4]);
                    if (!is_numeric($val[4])) {
                        $esmresourcesReturnDate = $val[4];
                    } else {
                        $esmresourcesReturnDate = util_excelUtil::exceltimtetophp($val[4]);
                    }

                    //����������ѯ��䣬���豸����ҵ�ƥ����豸���ƺ����ͣ��ͰѸ��豸��Ϣȡ��
                    if (!empty ($esmresourcesName)) {
                        $result = util_curlUtil::getDataFromAWS('asset', 'ClassifiDetailAslp', array(
                            'pname' => util_jsonUtil::iconvGB2UTF($esmresourcesType), 'page' => 1,
                            'pageSize' => 1, 'nameSearch' => util_jsonUtil::iconvGB2UTF($esmresourcesName)
                        ), array(), false);
                        $data = util_jsonUtil::decode($result['data'], true);

                        if (empty($data['data']['array'])) {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!ƥ�䲻����Ӧ�豸��';
                            array_push($resultArr, $tempArr);
                            continue;
                        } else {
                            $resourceId = 9999;
                            $budgetPrice = $data['data']['array'][0]['INPRICE'];
                            $unit = $data['data']['array'][0]['MEASUREMENTUNIT'];
                            $className = $data['data']['array'][0]['ASSETSCLASS'];
                        }

                        $sql2 = "SELECT projectCode,projectName FROM oa_esm_project WHERE id ='$projectId'";
                        $sql_c2 = $this->_db->getArray($sql2); //������projectId��Ӧ����Ŀ��ź���Ŀ�����������һ������

                        //�����豸���ý��=���豸�۾� * ʹ������
                        $usedays = (strtotime($esmresourcesReturnDate) -
                                strtotime($esmresourceBorrowDate)) / 86400 + 1;
                        $amount = bcmul(bcmul($budgetPrice, ($usedays), 2), $esmresourcesNum, 2);

                        if ($resourceId) {
                            if (is_numeric($val[3]) && is_numeric($val[4])) {
                                //�������ƴװ
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
                                    'resourceTypeId' => 0, //�豸���ͺ�
                                    'unit' => $unit, //��λ
                                    'projectCode' => $sql_c2[0]['projectCode'],
                                    'projectName' => $sql_c2[0]['projectName'],
                                    'projectId' => $projectId,
                                    'activityId' => $activityId
                                );
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!������дʱ���ʽ��';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!�豸���ƻ����ʹ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                    } else {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!�豸���Ʋ���Ϊ��!';
                        array_push($resultArr, $tempArr);
                        continue;
                    }

                    try {
                        $this->start_d();
                        // �����豸����
                        $this->add_d($inArr);

                        // ������Ŀ��Ϣ
                        $this->updateProject_d($inArr);

                        $this->commit_d();

                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ɹ�!';
                        array_push($resultArr, $tempArr);
                    } catch (Exception $e) {
                        $this->rollBack();

                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }
}