<?php

/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 20:39:05
 * @version 1.0
 * @description:Ӧ��������Ʊ Model�� ���״̬ ExaStatus
 * 0.δ���
 * 1.�����
 */
class model_finance_invother_invother extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invother";
        $this->sql_map = "finance/invother/invotherSql.php";
        parent::__construct();
    }

    /********************�²��Բ���ʹ��************************/

    // ��ͬ����������������,������Ҫ���������׷��
    private $relatedStrategyArr = array(
        'YFQTYD02' => 'model_finance_invother_strategy_other', // ������ͬ
        'YFQTYD01' => 'model_finance_invother_strategy_outsourcing', // �����ͬ
        'YFQTYD03' => 'model_finance_invother_strategy_rentcar'// �⳵��ͬ
    );

    /**
     * ��ȡ�ύ״̬��Ӧ����ʾ
     * @param $v
     * @return string
     */
    function getMsg_d($v) {
        switch ($v) {
            case 'audit' :
                $msg = '��˳ɹ�';
                break;
            case 'sub' :
                $msg = '�ύ�ɹ�';
                break;
            case 'back' :
                $msg = '��سɹ�';
                break;
            default :
                $msg = '����ɹ�';
        }
        return $msg;
    }

    /**
     * �����������ͷ�����
     * @param $objType
     * @return mixed
     */
    public function getClass($objType) {
        return $this->relatedStrategyArr[$objType];
    }

    // �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
    protected $_isSetCompany = 1;

    // ��Ӧҵ�����
    private $relatedCode = array(
        'YFQTYD02' => 'other',
        'YFQTYD01' => 'outsourcing',
        'YFQTYD03' => 'rentcar'
    );

    /**
     * �������ͷ���ҵ������
     * @param $objType
     * @return mixed
     */
    public function getBusinessCode($objType) {
        return $this->relatedCode[$objType];
    }

    /**
     * ��ȡ������Ϣ - �鿴�޸�ʱʹ��
     * @param $obj
     * @param iinvother $strategy
     * @param formCode ��Ϊnullʱ����ͨ��Դ����Ż�ȡ����
     * @return mixed
     */
    public function getObjInfo_d($obj, iinvother $strategy, $formCode = null) {
        return $strategy->getObjInfo_d($obj, $formCode);
    }

    /**
     * ��ȡ�ʼ���չ����
     * @param $objCode
     * @param iinvother $strategy
     */
    public function getMailExpend_d($objCode, iinvother $strategy) {
        return $strategy->getMailExpend_d($objCode);
    }

    /**
     * �ʼ����û�ȡ
     */
    function getMailInfo_d() {
        include(WEB_TOR . "model/common/mailConfig.php");
        $mailArr = isset($mailUser['invother']) ? $mailUser['invother'] : array('sendUserId' => '',
            'sendName' => '');
        return $mailArr;
    }

    /********************�²��Բ���ʹ��************************/

    /**
     * ��������
     * @param $object
     * @param null $actType
     * @return bool|null
     */
    function add_d($object, $actType = null) {
        $codeRuleDao = new model_common_codeRule();

        if (isset($object['mail'])) {
            $emailArr = $object['mail'];
            unset($object['mail']);
        }

        // created by huanghaojin 16-10-27 pms2138
        if (isset($object['isShare'])) {
            // Ϊ���ú���Ĳ������жϳ�����������޷�̯¼��ķ�Ʊ
            $object['isShareCost'] = ($object['isShare'] == 0)? "no" : "yes";
        }

        try {
            $this->start_d();

            if (is_array($object['items'])) {

                // ��ȡ�ӱ�����
                $itemsArr = $object['items'];
                unset($object['items']);

                // ȡ���÷�̯��Ϣ
                if (isset($object['costshare'])) {
                    $shareObj['costshare'] = $object['costshare'];
                    unset($object['costshare']);
                }

                // ��������
                $object['invoiceCode'] = $codeRuleDao->invotherCode($this->tbl_name, $object['salesmanId']);

                // ������ύ���������״̬�����ύ״̬��Ϊ1
                if ($actType == 'sub' || $actType == 'audit') {
                    $object['status'] = 1;
                }

                $id = parent::add_d($object, true);

                // ���Ӵӱ���Ϣ
                $invotherdetailDao = new model_finance_invother_invotherdetail();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $id, $itemsArr);
                $invotherdetailDao->saveDelBatch($itemsArr);

                // ��̯��ϸ����
                if (isset($shareObj)) {
                    $costHookDao = new model_finance_cost_costHook();
                    $shareObj['costshare'] = util_arrayUtil::setItemMainId("invotherId", $id, $shareObj['costshare']);
                    $shareObj['hookObj'] = array(
                        'hookId' => $id, 'hookCode' => $object['invoiceCode'], 'hookDate' => $object['formDate']
                    );
                    $hookObj = $costHookDao->dealHook_d($shareObj, $actType == 'audit' ? true : false);

                    // ���·�̯���
                    $this->update(array('id' => $id), array('hookMoney' => $hookObj['hookMoney']));
                }

                // ���¸���������ϵ
                $this->updateObjWithFile($id, $object['invoiceNo']);

                $this->commit_d();

                // �ʼ�����
                if (isset($emailArr) && $actType == 'sub' && $emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                    $this->mailDeal_d('invotherAdd', $emailArr['TO_ID'], $object);
                }

                return $id;
            } else {
                throw new Exception ("������Ϣ����������ȷ�ϣ�");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �༭�����ʼ��ʼ�����
     * @param $object
     */
    function thisMailSend_d($object) {
        if (isset($object['mail'])) {
            $emailArr = $object['mail'];
            if (!empty($emailArr['TO_ID'])) {
                $addMsg = '�ʼ����ݣ�' . $emailArr['mailContent'];

                $emailDao = new model_common_mail();
                $emailDao->mailClear('OA-֪ͨ :������Ʊ', $emailArr['TO_ID'], $addMsg);
            }
        }
    }

    /**
     * �޸ı���
     * @param $object
     * @param null $actType
     * @return bool|null
     */
    function edit_d($object, $actType = null) {

        if (isset($object['mail'])) {
            $emailArr = $object['mail'];
            unset($object['mail']);
        }

        try {
            $this->start_d();
            if (is_array($object ['items'])) {
                //��ȡ�ӱ�����
                $itemsArr = $object ['items'];
                unset($object ['items']);

                //ȡ���÷�̯��Ϣ
                if (isset($object['costshare'])) {
                    $shareObj['costshare'] = $object['costshare'];
                    unset($object['costshare']);
                }

                // ������ύ�����޸��ύ״̬
                if ($actType == 'sub') {
                    $object['status'] = 1;
                } elseif ($actType == 'back') {
                    $object['status'] = 2;
                } elseif ($actType == 'audit') {
                    $object = $this->setAuditInfo_d($object);
                }

                parent::edit_d($object, true);

                $invotherdetailDao = new model_finance_invother_invotherdetail();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $object ['id'], $itemsArr);
                $invotherdetailDao->saveDelBatch($itemsArr);

                //��̯��ϸ����
                if (isset($shareObj)) {
                    // ��������ѡ��
                    $hookDate = isset($object['ExaDT']) && !empty($object['ExaDT']) && $object['ExaDT'] != '0000-00-00' ?
                        $object['ExaDT'] : $object['formDate'];

                    $costHookDao = new model_finance_cost_costHook();
                    $shareObj['costshare'] = util_arrayUtil::setItemMainId("invotherId", $object['id'], $shareObj['costshare']);
                    $shareObj['hookObj'] = array(
                        'hookId' => $object['id'], 'hookCode' => $object['invoiceCode'], 'hookDate' => $hookDate
                    );
                    $hookObj = $costHookDao->dealHook_d($shareObj, $actType == 'audit' ? true : false);

                    // ���·�̯���
                    $this->update(array('id' => $object['id']), array('hookMoney' => $hookObj['hookMoney']));
                }

                // ��˴���
                if ($actType == 'audit') {
                    // ���̾��㴦�� - ֻҪ�⳵��ͬ�ĲŽ��д���
                    if ($object['sourceType'] == 'YFQTYD03') {
                        $periodArr = explode('.', $object['period']);
                        $esmfieldrecordDao = new model_engineering_records_esmfieldrecord();
                        $esmfieldrecordDao->businessFeeUpdate_d('rentCar', $periodArr[0],
                            $periodArr[1], array(
                                'id' => $object['id'], 'menuNo' => $object['menuNo']
                            ));
                    }

                    // ��˵�ʱ�����ʼ������д���ǷƱ��Ϣ��
                    $newClass = $this->getClass($object['sourceType']);
                    if ($newClass) {
                        $initObj = new $newClass();
                        //��ȡ��Ӧҵ����Ϣ
                        $object['mailExpend'] = $this->getMailExpend_d($object['menuNo'], $initObj);
                    }
                }

                $this->commit_d();

                // �޸��ʼ� -- �����ǰ�û����Ǵ����ˣ���֪ͨ������
                if (isset($emailArr) && $emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                    if ($actType == 'save') {
                        $this->mailDeal_d('invotherEdit', $emailArr['TO_ID'], $object);
                    } elseif ($actType == 'audit') {
                        $this->mailDeal_d('invotherAudit', $emailArr['TO_ID'], $object);
                    } elseif ($actType == 'back') {
                        $this->mailDeal_d('invotherBackMail', $emailArr['TO_ID'], $object);
                    }
                }

                return true;
            } else {
                throw new Exception ("������Ϣ����������ȷ�ϣ�");
            }
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * Ҫɾ����id
     * @param $ids
     * @return true
     * @throws $e
     */
    function deletes_d($ids) {
        $costHookDao = new model_finance_cost_costHook();
        try {
            $this->start_d();

            // delete deal
            $this->deletes($ids);

            // delete hook info
            $costHookDao->deleteHook_d($ids, 2);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ�ѿ�Ʊ���
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getInvotherMoney_d($objId, $objType = 'YFQTYD02') {
        $this->setCompany(0);
        $this->searchArr = array(
            'dObjId' => $objId,
            'dObjType' => $objType
        );
        $this->sort = '';
        $rs = $this->list_d("select_sum");
        $this->groupBy = 'd.objId';
        if ($rs[0]['formCount']) {
            return $rs[0]['formCount'];
        } else {
            return 0;
        }
    }

    /**
     * ��������Ϣ
     * @param $object
     * @return array
     */
    function setAuditInfo_d($object) {
        return array_merge($object, array('ExaStatus' => 1, 'exaMan' => $_SESSION['USERNAME'],
            'exaManId' => $_SESSION['USER_ID'], 'ExaDT' => day_date
        ));
    }

    /**
     * �����
     * @param $id
     * @return mixed
     */
    function unaudit_d($id) {
        // ��ѯ��Ʊ����
        $object = $this->find(array('id' => $id), null, 'formDate,sourceType,menuNo,period');

        try {
            $this->start_d();

            // �������״̬
            parent::edit_d(array('id' => $id, 'ExaStatus' => 0, 'exaMan' => null, 'exaManId' => null,
                'ExaDT' => null, 'hookMoney' => 0
            ), true);

            // ���̾��㴦�� - ֻҪ�⳵��ͬ�ĲŽ��д���
            if ($object['sourceType'] == 'YFQTYD03') {
                $periodArr = explode('.', $object['period']);
                $esmfieldrecordDao = new model_engineering_records_esmfieldrecord();
                $esmfieldrecordDao->businessFeeUpdate_d('rentCar', $periodArr[0],
                    $periodArr[1], array(
                        'id' => $object['id'], 'menuNo' => $object['menuNo']
                    ));
            }

            // ȡ����������
            $costHookDao = new model_finance_cost_costHook();
            $costHookDao->unAudit_d($id, 2);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
}