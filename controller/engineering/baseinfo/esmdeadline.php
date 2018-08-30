<?php
/**
 * @author show
 * @Date 2014年5月13日 15:39:29
 * @version 1.0
 * @description:日志填报期限
 */
class controller_engineering_baseinfo_esmdeadline extends controller_base_action
{

    function __construct() {
        $this->objName = "esmdeadline";
        $this->objPath = "engineering_baseinfo";
        parent::__construct();
    }

    /**
     * 跳转到项目操作记录列表
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * 编辑
     */
    function c_toEdit() {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * 获取日志填报截止提示
     */
    function c_getTips() {
        // 获取当前时间
        $now = microtime(true);
        $day = date('d', $now);

        // 获取当前月数据
        $thisMonth = $this->service->find(array('month' => date('n', $now)));

        if ($thisMonth['day'] >= $day) {
            $range = $thisMonth['useRange'];
            $startMonth = date('n', strtotime("last month", $now));
            $endMonth = $thisMonth['month'];
            $deadlineDay = $thisMonth['day'];
        } else {
            // 获取下个月数据
            $nextMonth = $this->service->find(array('month' => date('n', strtotime("next month", $now))));
            $range = $nextMonth['useRange'];
            $startMonth = $thisMonth['month'];
            $endMonth = $nextMonth['month'];
            $deadlineDay = $nextMonth['day'];
        }
        if ($range) {
            // 如果当前月份是1月，则年份扣1
            $year = $endMonth == 1 ? date('Y', $now) - 1 : date('Y', $now);

            echo util_jsonUtil::iconvGB2UTF('当前最小可填日志日期是【' .
                $year . '-' . $startMonth . '-1' . '】，' . $startMonth . "月份（" . $range .
                "）日志截止填报日期是 " . $endMonth . "月" . $deadlineDay . "日");
        } else {
            echo "";
        }
    }

    /**
     * 测试获取截止日期接口
     */
    function c_getDeadlineInfo() {
        print_r($this->service->getDeadlineInfo_d());
    }

    /**
     * 测试获取截止日期接口
     */
    function c_getDeadMonth() {
        print_r($this->service->getDeadMonth_d());
    }

    /**
     * 更新工程数据
     */
    function c_updateESMData() {
        $category = isset($_GET['category']) ? $_GET['category'] : "";
        echo $this->service->updateESMData_d($category);
    }
}