var show_page = function () {
    $("#paylistGrid").yxgrid("reload");
};
// 当前计数
var countNum = 0;
// 完成数量
var doneNum = 0;
// 缓存的合同ID数据
var contractIdList = [];
// 缓存的数据量
var contractCache = [];

// 测试
function startInit() {
    // 按钮
    var btn = $("#paylistGrid-update").find("span");
    if (contractIdList.length == countNum) {
        btn.text("更新财务回款信息");
        alert("更新完成");
        btn.data("init", null);
        doneNum = countNum = 0;
        contractCache = contractIdList = [];
    } else if (btn.data("init") == null) {
        alert("已经终止");
        btn.text("更新财务回款信息");
        doneNum = countNum = 0;
        contractCache = contractIdList = [];
    } else {
        // 放置缓存
        contractCache.push(contractIdList[countNum].contractId);
        // 技术
        countNum++;

        // 如果是30的倍数或者长度已经用尽，则开始更新
        if (countNum % 30 == 0 || contractIdList.length == countNum) {
            // 开始执行处理
            $.ajax({
                url: '?model=finance_income_incomecheck&action=initData',
                data: {
                    contractIds: contractCache.toString()
                },
                type: 'GET',
                dataType: 'json',
                success: function (rs) {
                    // 计算已处理数量
                    doneNum = doneNum + parseInt(rs.msg);
                    // 写入进度
                    $("#paylistGrid-update").find("span").text("处理进度：" + doneNum + "/" + contractIdList.length);

                    // 如果完成了，延迟更新
                    if (doneNum == contractIdList.length) {
                        setTimeout(function () {
                            startInit();
                        }, 2000);
                    } else {
                        // 开始执行
                        startInit();
                    }
                }
            });
            // 清除缓存
            contractCache = [];
        } else {
            startInit();
        }
    }
}

$(function () {
    $("#paylistGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'pageJsons',
        title: '待录入收款条款合同',
        param: {
            'states': '1,2,3,4,5,6', // ,7
            'isTemp': '0',
            'ExaStatus': '完成'
        },
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        leftLayout: true,
        buttonsEx: [
            {
                name: 'update',
                text: "更新财务回款信息",
                icon: 'add',
                action: function () {
                    // 按钮
                    var btn = $("#paylistGrid-update").find("span");

                    // 如果初始化属性是null，则认为初始化没开始
                    if (btn.data("init") == null) {
                        if (confirm('确认执行更新吗？更新可能需要较长的时间，请耐心等待。\n\n注：再次点击按钮，可以终止更新。')) {
                            // 设置初始化为1，开始执行
                            btn.data("init", 1);
                            // 开始执行处理
                            $.ajax({
                                url: '?model=finance_income_incomecheck&action=getNeedContractIdList',
                                type: 'POST',
                                dataType: 'json',
                                async: false,
                                success: function (msg) {
                                    contractIdList = msg;
                                    // 写入进度
                                    $("#paylistGrid-update").find("span").text("处理进度：" + doneNum + "/" + contractIdList.length);
                                    startInit();
                                }
                            });
                        }
                    } else {
                        btn.data("init", null);
                    }
                }
            },
            {
                name: 'import',
                text: "批量导入合同付款条款",
                icon: 'excel',
                action: function () {
                    showThickboxWin("?model=contractTool_contractTool_contractTool&action=toImportPayInfo"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
                }
            }],
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true,
            width: 150,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'businessBelongName',
            display: '签约公司',
            sortable: true,
            width: 100
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true,
            width: 250
        }, {
            name: 'contractName',
            display: '合同名称',
            sortable: true,
            width: 150
        }, {
            name: 'contractMoney',
            display: '合同金额',
            sortable: true,
            width: 80
        }, {
            name: 'prinvipalName',
            display: '合同负责人',
            sortable: true,
            width: 100
        }, {
            name: 'fcheckStatus',
            display: '录入状态',
            sortable: true,
            width: 80
        }, {
            name: 'fcheckDate',
            display: '录入时间',
            sortable: true,
            width: 80
        }, {
            name: 'fcheckName',
            display: '录入人',
            sortable: true,
            width: 80
        }, {
            name: 'fcheckRemarks',
            display: '备注',
            sortable: true,
            width: 160,
            align: 'center',
            process: function (v, row) {
                var valCtn = ' - ';
                if (row.fcheckStatus == "未录入") {
                    valCtn = '<input type="text" id="fcheckRemarks' + row.id + '" style="width:120px" value="' + v + '"/> <input type="button" value="保存" onclick = "saveRemarks(' + row.id + ');"/>';
                }
                return valCtn;
            }
        }],

        // 扩展右键菜单
        menusEx: [{

            text: '更新付款条件',
            icon: 'add',
            action: function (row) {

                showThickboxWin('?model=contract_contract_receiptplan&action=updatePlan&contractId='
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1200');

            }
        }],
        comboEx: [{
            text: "录入状态",
            key: 'fcheckStatus',
            value: '未录入',
            data: [{
                text: '未录入',
                value: '未录入'
            }, {
                text: '已录入',
                value: '已录入'
            }]
        }, {
            text: "回款状态",
            key: 'isIncome',
            data: [{
                text: '未回款',
                value: '0'
            }, {
                text: '已回款',
                value: '1'
            }]
        }],
        searchitems: [{
            display: '合同编号',
            name: 'contractCode'
        }, {
            display: '合同名称',
            name: 'contractName'
        }, {
            display: '客户名称',
            name: 'customerName'
        }],
        // 高级搜索
        advSearchOptions: {
            modelName: 'contractInfo',
            // 选择字段后进行重置值操作
            selectFn: function ($valInput) {
                $valInput.yxcombogrid_area("remove");
                $valInput.yxselect_user("remove");
            },
            searchConfig: [
                {
                    name: '建立日期',
                    value: 'c.ExaDTOne',
                    changeFn: function ($t, $valInput) {
                        $valInput.click(function () {
                            WdatePicker({
                                dateFmt: 'yyyy-MM-dd'
                            });
                        });
                    }
                },
                {
                    name: '年份（直接输入数字，如2013）',
                    value: 'date_format(c.ExaDTOne,"%Y")'
                },
                {
                    name: '月份（直接输入数字，如 04、11）',
                    value: 'date_format(c.ExaDTOne,"%m")'
                },
                {
                    name: '季度（直接输入数字，如 1、2、3、4）',
                    value: 'quarter(c.ExaDTOne)'
                },
                {
                    name: '合同类型',
                    value: 'c.contractType',
                    type: 'select',
                    datacode: 'HTLX'
                }
                ,
                {
                    name: '客户类型',
                    value: 'c.customerType',
                    type: 'select',
                    datacode: 'KHLX'
                },
                {
                    name: '区域负责人',
                    value: 'c.areaPrincipal',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaPrincipalId' + rowNum,
                            nameCol: 'areaPrincipal',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '归属区域',
                    value: 'c.areaName',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaCode' + rowNum,
                            nameCol: 'areaName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '合同负责人',
                    value: 'c.prinvipalName',
                    changeFn: function ($t, $valInput, rowNum) {

                        $valInput.yxselect_user({
                            hiddenId: 'prinvipalId' + rowNum,
                            nameCol: 'prinvipalName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '省份',
                    value: 'c.contractProvince'
                },
                {
                    name: '城市',
                    value: 'c.contractCity'
                },
                {
                    name: '合同状态',
                    value: 'c.state',
                    type: 'select',
                    options: [
                        {
                            'dataName': '未提交',
                            'dataCode': '0'
                        },
                        {
                            'dataName': '审批中',
                            'dataCode': '1'
                        },
                        {
                            'dataName': '执行中',
                            'dataCode': '2'
                        },
                        {
                            'dataName': '已完成',
                            'dataCode': '4'
                        },
                        {
                            'dataName': '已关闭',
                            'dataCode': '3'
                        },
                        {
                            'dataName': '异常关闭',
                            'dataCode': '7'
                        }
                    ]
                },
                {
                    name: '签约主体',
                    value: 'c.businessBelong',
                    type: 'select',
                    datacode: 'QYZT'
                }
            ]
        }
    });
});

function saveRemarks(rowId) {
    var inputObj = $("#fcheckRemarks" + rowId);
    var remarks = inputObj.val();
    $.ajax({
        type: "POST",
        url: "?model=contract_contract_contract&action=saveCheckRemarks",
        data: {
            id: rowId,
            remarks: remarks,
            type: 'fcheck'
        },
        success: function (msg) {
            if (msg == 1) {
                alert('保存成功');
                show_page();
            } else {
                alert('保存失败');
            }
        }
    });
}