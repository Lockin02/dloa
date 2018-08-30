<?php

/**
 * 部门关系表
 * Class model_bi_deptFee_deptMapping
 */
class model_bi_deptFee_deptMapping extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_bi_dept_mapping";
        $this->sql_map = "bi/deptFee/deptMappingSql.php";
        parent::__construct();
    }

    /**
     * 重写新增
     * @param $object
     * @return bool|int|mixed
     */
    function add_d($object)
    {
        $object['forbidFeeType'] = is_array($object['forbidFeeType']) ? implode(',', $object['forbidFeeType']) : '';
        return parent::add_d($object);
    }

    /**
     * 重写编辑
     * @param $object
     * @return bool
     */
    function edit_d($object)
    {
        $object['forbidFeeType'] = is_array($object['forbidFeeType']) ? implode(',', $object['forbidFeeType']) : '';

        // 如果传入了原事业部字段，并且本次修改了事业部、二级部门、三级部门、四级部门，则更新部门数据
        if (isset($object['orgBusiness'])) {
            if ($object['business'] != $object['orgBusiness'] || $object['secondDept'] != $object['orgSecondDept'] ||
                $object['thirdDept'] != $object['orgThirdDept'] || $object['fourthDept'] != $object['orgFourthDept']
            ) {
                $deptFeeDao = new model_bi_deptFee_deptFee();
                $deptFeeDao->updateOldData_d(array(
                    'business' => $object['orgBusiness'], 'secondDept' => $object['orgSecondDept'],
                    'thirdDept' => $object['orgThirdDept'], 'fourthDept' => $object['orgFourthDept']
                ), array(
                    'business' => $object['business'], 'secondDept' => $object['secondDept'],
                    'thirdDept' => $object['thirdDept'], 'fourthDept' => $object['fourthDept']
                ));
            }
            unset($object['orgBusiness']);
            unset($object['orgSecondDept']);
            unset($object['orgThirdDept']);
            unset($object['orgFourthDept']);
        }

        return parent::edit_d($object);
    }

    function import_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//结果数组
        $tempArr = array();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {

                // 表头
                $titleRow = $excelData[0];
                unset($excelData[0]);

                //行数组循环
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;

                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        // 格式化数组
                        $val = $this->formatArray_d($val, $titleRow);

                        // 必填校验
                        foreach ($this->needTitle as $k => $v) {
                            if (!isset($val[$v])) {
                                $resultArr[] = array(
                                    'docCode' => '第' . $actNum . '条数据', 'result' => '没有填写' . $k
                                );
                                break;
                            }
                        }

                        try {
                            // 条件拼装
                            $condition = array(
                                'business' => $val['business'], 'secondDept' => $val['secondDept'],
                                'thirdDept' => $val['thirdDept'], 'fourthDept' => $val['fourthDept'],
                                'module' => $val['module']
                            );
                            // 匹配是否已存在
                            $deptFee = $this->find($condition, null, 'id');
                            if ($deptFee) {
                                //更新映射表
                                $val['id'] = $deptFee['id'];
                                parent::edit_d($val);
                                $tempArr['result'] = '更新成功';
                            } else {
                                parent::add_d($val);
                                $tempArr['result'] = '导入成功';
                            }
                        } catch (Exception $e) {
                            $tempArr['result'] = '更新失败' . $e->getMessage();
                        }
                    }
                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                    array_push($resultArr, $tempArr);
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    // 导入标题
    private $importTitle = array(
        '事业部' => 'business', '二级部门' => 'secondDept', '三级部门' => 'thirdDept', '四级部门' => 'fourthDept',
        '板块' => 'module', '产品线' => 'productLine', '包含部门' => 'mappingDept', '费用类别' => 'costCategory',
        '不统计项' => 'forbidFeeType', '备注说明' => 'remark', '序号' => 'sortOrder'
    );

    // 必填标题
    private $needTitle = array(
        '事业部' => 'business', '二级部门' => 'secondDept'
    );

    /**
     * 匹配excel字段
     * @param $data
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($data, $titleRow)
    {
        // 构建新的数组
        foreach ($titleRow as $k => $v) {
            // 如果存在已定义内容，则进行键值替换
            if (isset($this->importTitle[$v])) {
                // 格式化更新数组
                $data[$this->importTitle[$v]] = trim($data[$k]);
            }
            // 处理完成后，删除该项
            unset($data[$k]);
        }
        return $data;
    }

    /**
     * 获取部门关系映射
     * @param string $forbidFeeType 禁用类型
     * @return array
     */
    function getMapping_d($forbidFeeType)
    {
        $mapping = array();
        $sql = "SELECT *,FIND_IN_SET('$forbidFeeType', forbidFeeType) AS getForbidItem FROM " . $this->tbl_name;
        $data = $this->_db->getArray($sql);

        // 拼装
        if (!empty($data)) {
            foreach ($data as $v) {
                if ($v['getForbidItem']) {
                    continue;
                }
                if ($v['mappingDept']) {
                    $mappingDept = explode(',', $v['mappingDept']);

                    foreach ($mappingDept as $vi) {
                        // 如果类型是报销\支付并且映射存在板块，则使用 【部门_板块】 来作为映射key
                        $dept = $v['module'] && in_array($forbidFeeType, array('报销', '支付')) ?
                            $vi . '_' . $v['module'] : $vi;

                        // 建立映射
                        $mapping[$dept] = array(
                            'business' => $v['business'],
                            'secondDept' => $v['secondDept'],
                            'thirdDept' => $v['thirdDept'],
                            'fourthDept' => $v['fourthDept']
                        );
                    }
                }
            }
        }
        return $mapping;
    }

    /**
     * 返回项目和部门的映射关系
     * @return array
     */
    function getProjectMapping_d()
    {
        $mapping = array();
        // 获取项目的部门
        $sql = "SELECT p.id,b.feeDeptName
            FROM oa_esm_office_baseinfo b INNER JOIN oa_esm_project p ON p.officeId = b.id;";
        $projectData = $this->_db->getArray($sql);

        if (!empty($projectData)) {
            // 建立项目和部门的映射
            foreach ($projectData as $v) {
                $deptArr = explode(',', $v['feeDeptName']);
                $mapping[$v['id']] = $deptArr[0];
            }
        }
        return $mapping;
    }

    /**
     * 获取项目id和项目编码的映射
     * @return array
     */
    function getProjectCodeMapping_d()
    {
        $mapping = array();
        // 获取项目的部门
        $sql = "SELECT id,projectCode FROM oa_esm_project;";
        $projectData = $this->_db->getArray($sql);

        if (!empty($projectData)) {
            // 建立项目和部门的映射
            foreach ($projectData as $v) {
                $mapping[$v['id']] = $v['projectCode'];
            }
        }
        return $mapping;
    }

    /**
     * PK部门映射转换
     * @param $mapping
     * @return mixed
     */
    function dealPKMapping_d($mapping)
    {
        // 获取过滤掉的费用项
        $otherDatasDao = new model_common_otherdatas();
        $pkMapping = $otherDatasDao->getConfig('deptFee_pk_mapping');

        // 最终PK部门映射表 [映出部门 => 映入部门]
        $finMapping = array();

        // 开始拆解PK部门映射
        $pkMappingArr = explode(',', $pkMapping);

        // 循环转换映射部门
        foreach ($pkMappingArr as $k) {
            $deptMap = explode('|', $k);
            $finMapping[$deptMap[0]] = $deptMap[1];
        }

        // 将项目部门转为映出部门
        foreach ($mapping as $k => $v) {
            if (isset($finMapping[$v])) {
                $mapping[$k] = $finMapping[$v];
            }
        }
        return $mapping;
    }

    /**
     * 根据部门名称获取所属事业部
     *
     * @param string $Dept
     * @return array
     */
    function getBusinessByDept($Dept = "")
    {
        if ($Dept != "") {
            $row = $this->_db->getArray("select * from oa_bi_dept_mapping c where 1=1 and FIND_IN_SET('{$Dept}',c.mappingDept)>0 limit 1");
            return $row ? $row[0] : array();
        } else {
            $rows = $this->_db->getArray("select * from oa_bi_dept_mapping c where 1=1");
            return $rows ? $rows : array();
        }
    }
}