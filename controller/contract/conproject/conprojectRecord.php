<?php
/**
 * @author hoajin
 * @Date 2017年7月17日 17:05:09
 * @version 1.0
 * @description:合同项目表 Controller层
 */
class controller_contract_conproject_conprojectRecord  extends controller_base_action {
    function __construct() {
        $this->objName = "conprojectRecord";
        $this->objPath = "contract_conproject";
        parent::__construct();
    }

    /**
     * 获取新版本信息
     */
    function c_getVersionInfo(){
        $versionInfo = $this->service->getVersionInfo_d();
        echo util_jsonUtil::encode ( $versionInfo );
    }

    /**
     * 检查版本是否已有
     */
    function c_checkIsUse() {
        echo $this->service->checkIsUsing_d($_POST['storeYearMonth']);
    }

    /**
     * 更新数据 - 将最新的项目数据更新到当前版本
     */
    function c_updateRecord() {
        set_time_limit(0);

        //清空并关闭输出缓存
        ob_end_clean();

        // 首先输出页面
        echo file_get_contents(TPL_DIR . '/engineering/records/esmrecord-update.htm');

        flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。

        // 这里加载数据
        $data = $this->service->getConProjectInfo_d();
        $dataLength = count($data);
        $dataKeyLength = $dataLength - 1;

        // 版本数据获取
        $versionInfo = $this->service->getVersionInfo_d();

        foreach ($data as $k => $v) {
            // 条件判断 - 100个进行一次输出
            $isRealSave = ($k != 0 && $k % 50 == 0) || $k == $dataKeyLength;

            // 补齐并且将数据插入表
            $this->service->saveRecord_d($v, $versionInfo, $isRealSave);

            if ($isRealSave) {

                $i = $k + 1;
                $length = round($i / $dataLength * 500);
                echo <<<E
                    <script type="text/javascript">
                        updateProgress("($i/$dataLength)操作完成！", $length);
                    </script>
E;
                flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
            }
        }
    }

    /**
     * 根据年份获取相应的版本号
     */
    function c_getVersionByYear(){
        $year = isset($_POST['storeYear'])? $_POST['storeYear'] : '';
        if($year != ''){
            $sql = "select version from oa_contract_project_record where storeYear = '{$year}' group by version order by version desc;";
            $result = $this->service->_db->getArray($sql);
            echo util_jsonUtil::encode ( $result );
        }else{
            echo "false";
        }
    }

    /**
     * 跳转到保存界面
     */
    function c_toSetUsing() {
        $yearsOpts = "<option value=''>请选择</option>";
        $thisYear = 2010;
        $currentYear = date("Y");
        while($thisYear <= $currentYear){
            $yearsOpts .= "<option value='{$thisYear}'>{$thisYear}</option>";
            $thisYear += 1;
        }
        $nowVersion = $this->service->getVersionInfo_d();
        $nowVersionCode = $nowVersion['maxVersion'];
        $this->assign('nowVersion', $nowVersionCode);
        $this->assign('yearsOpts', $yearsOpts);
        $this->view('setusing');
    }

    /**
     * 将当前的数据保存一份作为最新发布的数据
     */
    function c_setUsing() {
        echo $this->service->setUsing_d($_POST['version'], $_POST['storeYearMonth']);
    }

}
?>