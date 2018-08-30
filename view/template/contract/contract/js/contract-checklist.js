var show_page = function () {
    $("#checklistGrid").yxgrid("reload");
};
$(function () {
    $("#checklistGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'pageJsons',
        title: '待录入验收条款合同',
        param: {
            'states': '1,2,3,4,5,6', // 取消异常关闭（state:7）的合同
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
                name: 'import',
                text: "批量导入合同验收条款",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contractTool_contractTool_contractTool&action=toImportcheckInfo"
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
            name: 'checkStatus',
            display: '录入状态',
            sortable: true,
            width: 80
        }, {
            name: 'checkDate',
            display: '录入时间',
            sortable: true,
            width: 80
        }, {
            name: 'checkName',
            display: '录入人',
            sortable: true,
            width: 80
        },{
            name: 'checkRemarks',
            display: '备注',
            sortable: true,
            width: 160,
            align: 'center',
            process: function (v, row) {
                var valCtn = ' - ';
                if(row.checkStatus == "未录入"){
                    valCtn = '<input type="text" id="checkRemarks'+row.id+'" style="width:120px" value="'+ v +'"/> <input type="button" value="保存" onclick = "saveRemarks('+row.id+');"/>';
                }
                return valCtn;
            }
        }],

        // 扩展右键菜单
        menusEx: [{
            text: '录入验收条款',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.checkStatus == "未录入" || row.checkStatus == " ") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=contract_checkaccept_checkaccept&action=toAdd&contractCode="
                        + row.contractCode + "&contractId=" + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                }
            }
        }, {
            text: '更新验收条款',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.checkStatus == "未验收" || row.checkStatus == "部分验收") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=contract_checkaccept_checkaccept&action=toEdit&contractId="
                        + row.id + "&contractCode=" + row.contractCode
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                }
            }
        }],
        comboEx: [{
            text: "验收状态",
            key: 'checkStatusArr',
            value: "未录入",
            data: [{
                text: '未录入',
                value: '未录入'
            }, {
                text: '未验收',
                value: '未验收'
            }, {
                text: '部分验收',
                value: '部分验收'
            }, {
                text: '已验收',
                value: '已验收'
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

function saveRemarks(rowId){
    var inputObj = $("#checkRemarks"+rowId);
    var remarks = inputObj.val();
    $.ajax({
        type : "POST",
        url : "?model=contract_contract_contract&action=saveCheckRemarks",
        data : {
            id : rowId,
            remarks : remarks,
            type : 'check'
        },
        success : function(msg) {
            if (msg == 1) {
                alert('保存成功');
                show_page();
            }else{
                alert('保存失败');
            }
        }
    });
}