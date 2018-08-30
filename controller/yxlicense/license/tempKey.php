<?php

/**
 * @author Show
 * @Date 2011��5��13�� ������ 11:19:40
 * @version 1.0
 * @description:licenseֵ���ô洢����Ʋ�
 */
class controller_yxlicense_license_tempKey extends controller_base_action
{

    function __construct() {
        $this->objName = "tempKey";
        $this->objPath = "yxlicense_license";
        parent::__construct();
    }

    /**
     * ��ת��licenseֵ���ô洢��
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * ѡ��licenseҳ��
     */
    function c_toSelect() {
        $focusId = isset($_GET['focusId']) ? $_GET['focusId'] : null;
        $licenseId = isset($_GET['licenseId']) ? $_GET['licenseId'] : null;
        $licenseType = isset($_GET['licenseType']) ? $_GET['licenseType'] : null;
        $actType = isset($_GET['actType']) ? $_GET['actType'] : null;
        $this->assign('focusId', $focusId);
        $this->assign('licenseId', $licenseId);
        $this->assign('licenseType', $licenseType);
        $this->assign('actType', $actType);
        $this->display('toselect');
    }

    /**
     * ѡ��licenseҳ��
     */
    function c_toSelectWin() {
        $id = isset($_GET['licenseId']) ? $_GET['licenseId'] : null;

        $obj = $this->service->get_d($id);
        if (empty($obj)) {
            $obj = array('thisVal' => '', 'licenseType' => '', 'rowVal' => '', 'extVal' => '');
        }
        $this->assignFunc($obj);

        $arr = '';
        $objName = $this->service->getLicense_d();
        foreach ($objName as $v) {
            $arr .= "<option value='$v[oXmlFileName]'>" . $v[name] . "</option>";
        }
        $this->assign('objType', $arr);
        $this->assign('licenseId', $id);
        $this->assign('productInfoId', $_GET['productInfoId']);
        $this->display('toselectwin');
    }

    /**
     * ���license����
     */
    function c_toSelectChange() {
        $id = isset($_GET['licenseId']) ? $_GET['licenseId'] : null;
        $objName = $this->service->getLicenseName_d();
        $arr = '';
        foreach ($objName as $k => $v) {
            $this->assign($k, $v);
            $arr .= "<option value='$v[name]'>" . $v[name] . "</option>";
        }
        $this->assign('objType', $arr);
        $obj = $this->service->get_d($id);

        $this->assignFunc($obj);
        $this->assign('licenseId', $id);
        $this->display('toselectchange');
    }

    /**
     * ѡ��licenseҳ�� �� ����
     */
    function c_toSelectStep() {
        $this->assignFunc($_GET);
        $this->display('toselectstep');
    }

    /**
     * licenseҳ�棬ֱ������
     */
    function c_toSetLicense() {
        $this->assignFunc($_GET);
        $id = isset($_GET['licenseId']) && $_GET['licenseId'] != 0 ? $_GET['licenseId'] : null;
        if ($id) {
            $obj = $this->service->get_d($id);
            $this->assignFunc($obj);
        } else {
            $this->assignFunc(array('extVal' => '', 'rowVal' => ''));
        }
        $this->assign('licenseId', $id);
        $this->display('tosetlicense');
    }

    /**
     * ����fleet
     */
    function c_returnHtml() {
        $licens = $_POST['licenseType'] ? $_POST['licenseType'] : null;
        $licens = util_jsonUtil::iconvUTF2GB($licens);
        switch ($licens) {
            case 'PIO' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-pioneer.htm';
                break;
            case 'NAV' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-navigator.htm';
                break;
            case 'FL' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-fleet.htm';
                break;
            case 'SL' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-s5wlan.htm';
                break;
            case 'RCU' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-rcu.htm';
                break;
            case 'WT' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-walktour.htm';
                break;
            case 'WISER' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-wiser.htm';
                break;
            case 'FL2' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-fleet2.htm';
                break;
            case 'Walktour Pack-Ipad' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-walktourpad.htm';
                break;
            case 'Pioneer-Navigator' :
                $phpStr = WEB_TOR . 'view/template/yxlicense/license/tempKey-pioneernavigator.htm';
                break;
            case $licens :
                $phpStr = UPLOADPATH . 'oa_license_baseinfo/' . $licens . '.html';
                break;
            default :
                $phpStr = null;
                break;
        }

        if ($phpStr) {
            $fileSize = filesize($phpStr);
            if ($file = fopen($phpStr, 'r')) {
                $str = util_jsonUtil::iconvGB2UTF(stripslashes(fread($file, $fileSize)));
            } else {
                $str = util_jsonUtil::iconvGB2UTF('�Ҳ����ļ�');
            }
            fclose($file);
        } else {
            $str = util_jsonUtil::iconvGB2UTF('�����ڵ�����');
        }
        echo $str;
    }

    /**
     * ��Ʒģ�巵�غ���
     */
    function c_returnHtmlHW($licenseType = null) {
        switch ($licenseType) {
            case $licenseType :
                $phpStr = UPLOADPATH . 'oa_license_baseinfo/' . $licenseType . '.html';
                break;
            default :
                $phpStr = null;
                break;
        }
        $file = addslashes(file_get_contents($phpStr, true));
        if ($file) {
            return base64_encode($file);
        } else {
            return "";
        }
    }

    /**
     * ����license�����¼
     */
    function c_addRecord() {
        $id = $this->service->addRecord_d($_POST);
        echo $id ? $id : 0;
    }

    /**
     * ��ȡlicense��¼
     */
    function c_getRecord() {
        $rs = $this->service->getRecord_d($_POST['id']);
        if (!empty($rs)) {
            echo util_jsonUtil::encode($rs);
        } else {
            echo 0;
        }
    }

    /**
     * ɾ��license��¼
     */
    function c_delRecord() {
        echo $this->service->delRecord_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ��ת���鿴ҳ��
     */
    function c_toViewRecord() {
        $id = $_GET['id'];
        $rs = $this->service->getHWTpl_d($id);
        if (empty($rs)) {
            $rs = $this->service->find(array('id' => $id), null, 'templateId');
        }
        $this->assignFunc($rs);
        $this->assign('clickTime', '0');
        $this->assign('licenseId', $id);
        $this->assign('baseinfoId', $this->service->getLicenseId_d());
        $this->view('viewrecord');
    }

    /**
     * �鿴license��¼
     */
    function c_viewRecord() {
        if ($rs = $this->service->getRecord_d($_POST['id'])) {
            echo util_jsonUtil::encode($rs);
        } else {
            echo 0;
        }
    }

    /**
     * �鿴ҳ�� - ��������Ϣ
     */
    function c_toView() {
        $id = $_GET['id'];
        $rs = $this->service->find(array('id' => $id), null, 'templateId');
        $this->assignFunc($rs);
        $this->assign('clickTime', '0');
        $this->assign('licenseId', $id);
        $this->assign('baseinfoId', $this->service->getLicenseId_d());
        $this->display('view');
    }

    /**
     * license�鿴
     */
    function c_compareTemplate() {
        $rs = $this->service->find(array('id' => $_GET['id']), null, 'id,templateId');
        $this->assignFunc($rs);
        $this->display('comparetemplate');
    }

    /**
     * ҵ�񵥾�
     * ����license��¼
     */
    function c_saveRecord() {
        if ($id = $this->service->saveRecord_d($_POST)) {
            echo $id;
        } else {
            echo 0;
        }
    }

    /**
     * �����������
     */
    function c_clearCache() {
        echo $this->service->clearCache_d() ? 1 : 0;
    }

    /**
     * �Ƚ�license
     */
    function c_compareLicense() {
        $this->assignFunc($_GET);
        $this->display('comparelicense');
    }

    /**
     * �鿴ҳ�� - ��������Ϣ
     */
    function c_toViewNew() {
        $id = $_GET['id'];
        $obj = $this->service->find(array('id' => $id), null, 'thisVal');
        //������
        $newArr = explode(',', $obj['thisVal']);

        $this->assign('licenseId', $id);
        //��������Ⱦ
        $oldObj = $this->service->find(array('id' => $_GET['oldId']), null, 'thisVal');
        $this->assign('oldVal', $oldObj['thisVal']);
        //��ֵ����
        $oldArr = explode(',', $oldObj['thisVal']);

        //���촦��
        $diffArr = array_diff($oldArr, $newArr);
        $this->assign('diffVal', implode($diffArr, ','));

        $this->display('viewnew');
    }

    //���溣�����͵Ĳ�Ʒ����HTML
    function c_saveHWProHtml($objArr) {
        return $this->service->saveHWProHtml_d($objArr);
    }
}