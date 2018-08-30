<?php
/**
 * @author Administrator
 * @Date 2012年6月28日 星期四 16:55:01
 * @version 1.0
 * @description:供应商评估任务控制层
 */
class controller_supplierManage_assessment_task extends controller_base_action
{

    function __construct()
    {
        $this->objName = "task";
        $this->objPath = "supplierManage_assessment";
        parent::__construct();
    }

    /**
     * 跳转到供应商评估任务列表
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * 跳转到供应商评估任务列表(已完成)
     */
    function c_toCloseList()
    {
        $this->view('close-list');
    }

    /**
     * 跳转到供应商评估任务列表(Tab)
     */
    function c_toListTab()
    {
        $this->view('tab');
    }

    /**
     * 跳转到我的供应商评估任务列表
     */
    function c_toMyTab()
    {
        $this->view('mytab');
    }

    /**
     * 跳转到我的供应商评估任务列表(未完成)
     */
    function c_toMyList()
    {
        $this->view('my-list');
    }

    /**
     * 跳转到我的供应商评估任务列表(已完成)
     */
    function c_toMyCloseList()
    {
        $this->view('myclose-list');
    }

    /**
     * 跳转到新增供应商评估任务页面
     */
    function c_toAdd()
    {
        $this->assign('formDate', date("Y-m-d"));
        $taskNumb = "T" . date("YmdHis"); //生成任务编号
        $this->assign('formCode', $taskNumb);
        $this->assign('purchManName', $_SESSION ['USERNAME']); //任务下达人
        $this->assign('purchManId', $_SESSION ['USER_ID']);
        //读取数据字典
        $this->showDatadicts(array('assessType' => 'FALX'));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('assesYear', $yearStr);
        $this->assign('quarterStr', "<option value=1>第1季度</option><option value=2>第2季度</option><option value=3>第3季度</option><option value=4>第4季度</option>");
        if (isset ($_GET ['thisYear'])) {
            $initArr = $_GET;
        } else {
            $thisQuarter = intval((date('m') + 2) / 3);

            $initArr = array(
                'thisYear' => $thisYear,
                'thisQuarter' => $thisQuarter
            );
        }
        $this->assignFunc($initArr);
        $this->view('add', true);
    }

    /**
     * 跳转到新增供应商评估任务页面
     */
    function c_toAddBySupp()
    {
        $suppId = isset ($_GET ['suppId']) ? $_GET ['suppId'] : "";
        $assesType = isset ($_GET ['assesType']) ? $_GET ['assesType'] : "";
        $flibraryDao = new model_supplierManage_formal_flibrary();
        //获取供应商信息
        $suppRow = $flibraryDao->get_d($suppId);
        $this->assign('suppId', $suppId);
        $this->assign('suppName', $suppRow['suppName']);
        $this->assign('assessType', $assesType);
        $this->assign('assessTypeName', $this->getDataNameByCode($assesType));
        $this->assign('formDate', date("Y-m-d"));
        $taskNumb = "T" . date("YmdHis"); //生成任务编号
        $this->assign('formCode', $taskNumb);
        $this->assign('purchManName', $_SESSION ['USERNAME']); //任务下达人
        $this->assign('purchManId', $_SESSION ['USER_ID']);
        //读取数据字典
//	$this->showDatadicts(array ('assessType' => 'FALX'));
        $thisYear = date('Y');
        $yearStr = "";
        for ($i = $thisYear; $i >= 2005; $i--) {
            $yearStr .= "<option value=$i>" . $i . "年</option>";
        }
        $this->assign('assesYear', $yearStr);
        $this->assign('quarterStr', "<option value=1>第1季度</option><option value=2>第2季度</option><option value=3>第3季度</option><option value=4>第4季度</option>");
        if (isset ($_GET ['thisYear'])) {
            $initArr = $_GET;
        } else {
            $thisQuarter = intval((date('m') + 2) / 3);

            $initArr = array(
                'thisYear' => $thisYear,
                'thisQuarter' => $thisQuarter
            );
        }

        $this->assignFunc($initArr);
        $this->view('supp-add', true);
    }

    /**
     * 跳转到编辑供应商评估任务页面
     */
    function c_toEdit()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * 跳转到查看供应商评估任务页面
     */
    function c_toView()
    {
        $this->permCheck(); //安全校验
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if ($obj['assessType'] == "gysjd") {
            switch ($obj['assesQuarter']) {
                case 1:
                    $assesQuarter = "第一季度";
                    break;
                case 2:
                    $assesQuarter = "第二季度";
                    break;
                case 3:
                    $assesQuarter = "第三季度";
                    break;
                case 4:
                    $assesQuarter = "第四季度";
                    break;
                default:
                    $assesQuarter = "";
            }

        } else {
            $assesQuarter = "";

        }
        $this->assign("assesQuarter",$assesQuarter);

        $this->view('view');
    }

    /**评估任务列表的显示方法
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->asc = true;
//		$rows = $service->page_d ();
        $rows = $service->pageBySqlId();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                //转换成中文
                $rows[$key]['stateName'] = $service->statusDao->statusKtoC($rows[$key]['state']);
            }

        }
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**我的评估任务列表的显示方法
     */
    function c_myPageJson()
    {
        $service = $this->service;
        $service->getParam($_POST); //设置前台获取的参数信息
        $service->asc = true;
        $service->searchArr['assesManId'] = $_SESSION['USER_ID'];
//		$rows = $service->page_d ();
        $rows = $service->pageBySqlId();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows($rows);
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                //转换成中文
                $rows[$key]['stateName'] = $service->statusDao->statusKtoC($rows[$key]['state']);
            }

        }
        $arr = array();
        $arr ['collection'] = $rows;
        $arr ['totalSize'] = $service->count;
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * 新增方法
     *
     */
    function c_add()
    {
        $this->checkSubmit(); //验证是否重复提交
        $id = $this->service->add_d($_POST[$this->objName]);
        if ($id) {
            msg('下达成功');

        } else {
            msg('下达失败');

        }
    }

    /**
     * 变更方法
     *
     */
    function c_edit()
    {
//	 	$row=$this->service->get_d($_POST[$this->objName]['id']);
//	 	if($row['assesManId']!=$_POST[$this->objName]['assesManId']){//如果负责人不同，则状态改为待接收
//	 		$_POST[$this->objName]['state']=0;
//	 	}
        $id = $this->service->edit_d($_POST[$this->objName]);
        if ($id) {
            msg('变更成功');

        } else {
            msg('变更失败');

        }
    }

    /**接收任务
     */
    function c_accepTask()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : exit;
        $state = isset($_POST['state']) ? $_POST['state'] : exit;
        $flag = $this->service->accepTask_d($id, $state);
        //如果删除成功输出1，否则输出0
        if ($flag) {
            echo 1;
        } else {
            echo 0;
        }

    }

    /**验证该供应商是否已下达任务
     * @author suxc
     *
     */
    function c_isTask()
    {
        $suppId = $_POST['suppId'];
        $supassType = $_POST['supassType'];
        if ($suppId) {
            switch ($supassType) {
                case "gysjd":
                    $flag = $this->service->isAssesQuarter_d($suppId);
                    break; //季度考核
                case "gysnd":
                    $flag = $this->service->isAssesYear_d($suppId);
                    break; //年度考核
                case "xgyspg":
                    $flag = $this->service->isAssesNew_d($suppId);
                    break; //新供应商评估
            }
            echo $flag;
        }
    }

    /**
     * 验证供应商的季度考核任务是否已存在
     * @author suxc
     */
    function c_checkData()
    {
        $suppId = $_POST['suppId']; //供应商ID
        $assesYear = $_POST['assesYear']; //考核年份
        $assesQuarter = $_POST['assesQuarter']; //考核季度
        $assessType = $_POST['assessType']; //考核类型
        if ($assessType == "gysjd") {
            $falg = $this->service->checkData_d($suppId, $assesYear, $assesQuarter);
        } else if ($assessType == "gysnd") {
            $falg = $this->service->isAssesYear_d($suppId, $assesYear);
        }
        echo $falg;
    }
}

?>