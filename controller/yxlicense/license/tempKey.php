<?php

/**
 * @author Show
 * @Date 2011年5月13日 星期五 11:19:40
 * @version 1.0
 * @description:license值暂用存储表控制层
 */
class controller_yxlicense_license_tempKey extends controller_base_action
{

    function __construct() {
        $this->objName = "tempKey";
        $this->objPath = "yxlicense_license";
        parent::__construct();
    }

    /**
     * 跳转到license值暂用存储表
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * 选择license页面
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
     * 选择license页面
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
     * 变更license操作
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
     * 选择license页面 － 步骤
     */
    function c_toSelectStep() {
        $this->assignFunc($_GET);
        $this->display('toselectstep');
    }

    /**
     * license页面，直接配置
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
     * 返回fleet
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
                $str = util_jsonUtil::iconvGB2UTF('找不到文件');
            }
            fclose($file);
        } else {
            $str = util_jsonUtil::iconvGB2UTF('不存在的类型');
        }
        echo $str;
    }

    /**
     * 产品模板返回海外
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
     * 新增license申请记录
     */
    function c_addRecord() {
        $id = $this->service->addRecord_d($_POST);
        echo $id ? $id : 0;
    }

    /**
     * 获取license记录
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
     * 删除license记录
     */
    function c_delRecord() {
        echo $this->service->delRecord_d($_POST['id']) ? 1 : 0;
    }

    /**
     * 调转到查看页面
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
     * 查看license记录
     */
    function c_viewRecord() {
        if ($rs = $this->service->getRecord_d($_POST['id'])) {
            echo util_jsonUtil::encode($rs);
        } else {
            echo 0;
        }
    }

    /**
     * 查看页面 - 无其他信息
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
     * license查看
     */
    function c_compareTemplate() {
        $rs = $this->service->find(array('id' => $_GET['id']), null, 'id,templateId');
        $this->assignFunc($rs);
        $this->display('comparetemplate');
    }

    /**
     * 业务单据
     * 保存license记录
     */
    function c_saveRecord() {
        if ($id = $this->service->saveRecord_d($_POST)) {
            echo $id;
        } else {
            echo 0;
        }
    }

    /**
     * 清除缓存数据
     */
    function c_clearCache() {
        echo $this->service->clearCache_d() ? 1 : 0;
    }

    /**
     * 比较license
     */
    function c_compareLicense() {
        $this->assignFunc($_GET);
        $this->display('comparelicense');
    }

    /**
     * 查看页面 - 无其他信息
     */
    function c_toViewNew() {
        $id = $_GET['id'];
        $obj = $this->service->find(array('id' => $id), null, 'thisVal');
        //新数组
        $newArr = explode(',', $obj['thisVal']);

        $this->assign('licenseId', $id);
        //旧数据渲染
        $oldObj = $this->service->find(array('id' => $_GET['oldId']), null, 'thisVal');
        $this->assign('oldVal', $oldObj['thisVal']);
        //旧值数组
        $oldArr = explode(',', $oldObj['thisVal']);

        //差异处理
        $diffArr = array_diff($oldArr, $newArr);
        $this->assign('diffVal', implode($diffArr, ','));

        $this->display('viewnew');
    }

    //保存海外推送的产品配置HTML
    function c_saveHWProHtml($objArr) {
        return $this->service->saveHWProHtml_d($objArr);
    }
}