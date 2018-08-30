<?php

/*责任范围控制层类
 * Created on 2010-12-1
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_engineering_officeinfo_range extends controller_base_action
{
    function __construct()
    {
        $this->objName = "range";
        $this->objPath = "engineering_officeinfo";
        parent:: __construct();
    }

    //根据省份编码返回办事处信息
    function c_getOfficeInfo()
    {
        echo util_jsonUtil::encode($this->service->find(array('proCode' => $_POST['proCode'])));
    }

    //根据省份id返回办事处信息
    //此方法已经停用,需要使用从esmproject getRangeId进入
    function c_getOfficeInfoForId()
    {
        echo $this->service->getRangeId_d($_POST['provinceId'], $_POST['productLine'], $_POST['officeId']);
    }

    //根据省份ID和部门ID查询区域信息
    function c_getRangeByProvinceAndDept()
    {
        echo $this->service->getRangeByProvinceAndDept_d($_POST['provinceId'], $_POST['deptId']);
    }

    /**
     * 责任范围查询
     */
    function c_showRange()
    {
        // 权限获取
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        // 办事处权限部分
        $sysLimit = $sysLimit['办事处'];
        // 省份权限
        $proLimit = $sysLimit['省份权限'];
        // 扩展的查询条件
        $whereSql = "";

        // 如果没有全部权限，那么将当前拥有的权限进行整合
        if (strpos($sysLimit, ';;') !== false || strpos($proLimit, ';;') !== false) {

        } else {
            // 没有全部权限的时候，先匹配配置表中的数据
            $whereSql = "AND FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.mainManagerId)
                OR FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.managerId)
                OR FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.headId)";

            // 如果办事处权限不为空，则加载这部分的数据
            if (!empty($sysLimit)) {
                $whereSql .= " OR c.officeId IN(" . util_jsonUtil::strBuild($sysLimit) . ")";
            }

            // 如果省份权限不为空，则加载这部分的数据
            if (!empty($proLimit)) {
                $whereSql .= " OR c.proName IN(" . util_jsonUtil::strBuild($proLimit) . ")";
            }
        }

        // 查询数据
        $sql = "SELECT b.moduleName, b.feeDeptId, c.officeId, c.officeName, c.proName, c.productLine, c.productLineName
            FROM oa_esm_office_range c LEFT JOIN oa_esm_office_baseinfo b ON c.officeId = b.id
            WHERE c.state = 0
            $whereSql
            ORDER BY c.officeId, c.proName";
        $rows = $this->service->_db->getArray($sql);

        $rst = array(); // 返回数组

        if (!empty($rows) && isset($_REQUEST['t']) && $_REQUEST['t'] == 2) {
            $officeCache = array(); // 上个缓存
            foreach ($rows as $v) {
                if ($v['proName'] && !in_array($v['officeId'], $officeCache)) {
                    $rst[] = array(
                        'officeId' => $v['officeId'],
                        'officeName' => $v['officeName'],
                        'feeDeptId' => $v['feeDeptId'],
                        'moduleName' => $v['moduleName'],
                        'productLineName' => $v['productLineName'],
                        'productLine' => $v['productLine'],
                        'ext' => 1
                    );
                    $officeCache[] = $v['officeId'];
                }
                $rst[] = $v;
            }
        } else {
            $rst = $rows;
        }

        if (isset($_REQUEST['needCountRow']) && $_REQUEST['needCountRow'] && !empty($rst)) {
            $rst[] = array(
                'officeName' => '合计',
                'officeId' => 'countRow'
            );
        }

        echo util_jsonUtil::encode($rst);
    }
}