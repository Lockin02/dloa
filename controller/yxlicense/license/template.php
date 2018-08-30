<?php

/**
 * @author Show
 * @Date 2011年10月9日 星期日 14:39:27
 * @version 1.0
 * @description:(new)license模 板控制层
 */
class controller_yxlicense_license_template extends controller_base_action
{

    function __construct() {
        $this->objName = "template";
        $this->objPath = "yxlicense_license";
        parent::__construct();
    }

    /**
     * 跳转到(new)license模 板
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * 跳转到add页面
     */
    function c_toAdd() {
        $licenseInfo = $this->service->getLicense_d();
        $arr = '';
        foreach ($licenseInfo as $k => $v) {
            $arr .= "<option id='$v[id]' value='$v[oXmlFileName]'>" . $v[name] . "</option>";
        }
        $this->assign('objType', $arr);
        $this->view('add');
    }

    /**
     * 重写添加方法
     */
    function c_add() {
        if ($this->service->add_d($_POST[$this->objName])) {
            msgRf('添加成功');
        } else {
            msgRf('添加失败');
        }
    }

    /**
     * 重写编辑方法
     */
    function c_edit() {
        if ($this->service->edit_d($_POST[$this->objName])) {
            msgRf('保存成功');
        } else {
            msgRf('保存失败');
        }
    }

    /**
     * 初始化对象
     */
    function c_init() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        $obj['extVal'] = stripslashes($obj['extVal']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $licenseInfo = $this->service->getLicenseAll_d();
        $arr = '';
        foreach ($licenseInfo as $k => $v) {
            $arr .= "<option id='$v[id]' value='$v[oXmlFileName]'>" . $v[name] . "</option>";
        }
        $this->assign('objType', $arr);
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $this->display('view');
        } else {
            $this->display('edit');
        }
    }

    /**
     * 初始化对象
     */
    function c_toViewOnly() {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        $obj['extVal'] = stripslashes($obj['extVal']);
        $this->assignFunc($obj);
        $this->display('viewonly');
    }

    /**
     * 根据license类型获取license模板
     */
    function c_getTemplateByType() {
        $this->service->getParam($_POST);
        $rows = $this->service->list_d();
        if (!is_array($rows)) return '';
        foreach ($rows as $key => $val) {
            if ($val['extVal']) {
                $rows[$key]['extVal'] = stripslashes($rows[$key]['extVal']);
            }
            if ($val['rowVal']) {
                $rows[$key]['rowVal'] = stripslashes($rows[$key]['rowVal']);
            }
        }
        echo util_jsonUtil::encode($rows);
    }

    /**
     * 根据模板id获取模板值
     */
    function c_getTemplate() {
        $rs = $this->service->find(array('id' => $_POST['id']));
        if (is_array($rs)) {
            echo util_jsonUtil::encode($rs);
        } else {
            echo 0;
        }
    }
}