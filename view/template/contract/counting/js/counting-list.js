var show_page = function () {
    $("#grid").yxgrid("reload");
};

$(function () {
    $("#grid").yxgrid({
        model: 'contract_counting_counting',
        param: {},
        title: '合同分帐表',
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        isOpButton: false,
        autoload: false,
        showcheckbox: false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'contractName',
            display: '合同名称',
            sortable: true,
            width: 120
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true,
            process: function (v, row) {
                return (v == 'NULL' || v == '')? '' : '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'contractMoney',
            display: '合同额',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'projectMoney',
            display: '项目金额',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'projectRate',
            display: '项目占合同',
            align: 'right',
            sortable: true,
            process: function (v) {
                return accMul(v, 100, 7) + " %";
            }
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true,
            width: 120
        }, {
            name: 'projectCode',
            display: '项目编号',
            sortable: true,
            process: function (v, row) {
                var skey = url = "";
                if(row.projectType == '服务'){
                    $.ajax({
                        type : "POST",
                        url : "?model=engineering_project_esmproject&action=md5RowAjax",
                        data : {
                            "id" : row.projectId
                        },
                        async : false,
                        success : function(data) {
                            skey = data;
                        }
                    });
                    skey = "&skey=" + skey;
                    url = '?model=engineering_project_esmproject&action=viewTab&id='+ row.projectId+skey;
                }else{
                    url = '?model=contract_conproject_conproject&action=viewTab&id='+ row.projectId;
                }

                return (v == 'NULL' || v == '')? '' : '<a href="javascript:void(0)" onclick="javascript:showModalWin(\''+url
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'module',
            display: '版块',
            sortable: true
        }, {
            name: 'productType',
            display: '产品类型',
            sortable: true
        }, {
            name: 'newProLine',
            display: '产品线',
            sortable: true
        }, {
            name: 'newProLineMoney',
            display: '产线金额',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'area',
            display: '执行区域',
            sortable: true
        }, {
            name: 'product',
            display: '产品',
            sortable: true
        }, {
            name: 'productMoney',
            display: '产品金额',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productRate',
            display: '产品占项目',
            align: 'right',
            sortable: true,
            process: function (v) {
                return accMul(v, 100, 7) + " %";
            }
        }, {
            name: 'projectIncome',
            display: '项目收入',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productIncome',
            display: '产品收入',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'projectFee',
            display: '项目成本',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productFee',
            display: '产品成本',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'productProfit',
            display: '产品毛利',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'createTime',
            display: '建立时间',
            sortable: true,
            width: 120
        }, {
            name: 'lastChange',
            display: '最后变更',
            sortable: true
        }, {
            name: 'countingCheck',
            display: '分账检查',
            sortable: true
        }, {
            name: 'buildCheck',
            display: '立项检查',
            sortable: true,
            process: function (v,row) {
                return (row.isTrue == 1)? "正确" : v;
            }
        }],
        comboEx: [{
            text: '检查结果',
            key: 'chkResult',
            data: [{
                text: '正常',
                value: 'correct'
            }, {
                text: '异常',
                value: 'wrong'
            }]
        }],
        buttonsEx: [{
            text: "更新项目",
            icon: 'add',
            action: function () {
                showThickboxWin("?model=contract_counting_counting&action=toUpdate"
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700")
            }
        }, {
            text: '导出数据',
            icon: 'excel',
            action: function () {
                var thisGrid = $("#grid").data('yxgrid');
                var colName = [];
                var colCode = [];
                var colModel = thisGrid.options.colModel;
                for (var i = 0; i < colModel.length; i++) {
                    if (!colModel[i].hide) {
                        colName.push(colModel[i].display);
                        colCode.push(colModel[i].name);
                    }
                }
                var url = "?model=contract_counting_counting&action=export"
                    + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                if (thisGrid.options.qtype) {
                    url += "&" + thisGrid.options.qtype + "=" + thisGrid.options.query;
                }
                window.open(url, "", "width=200,height=200,top=200,left=200");
            }
        }],
        searchitems: [{
            display: '合同名称',
            name: 'contractNameSearch'
        }, {
            display: '合同号',
            name: 'contractCodeSearch'
        }, {
            display: '项目名称',
            name: 'projectNameSearch'
        }, {
            display: '项目编号',
            name: 'projectCodeSearch'
        }],
        // 默认搜索字段名
        sortname: "c.createTime",
        // 默认搜索顺序 降序
        sortorder: "DESC",
        menusEx: [
            {
                text: '删除合同',
                icon: 'edit',
                showMenuFn: function (row) {
                    return true;
                },
                action: function (row) {
                    if(confirm("确定删除该合同【"+row.contractCode+"】下的全部数据吗?")){
                        $.ajax({
                            type : "POST",
                            url : "?model=contract_counting_counting&action=delProject",
                            data : {
                                "id" : row.contractId
                            },
                            async : false,
                            success : function(data) {
                                if(data == 'ok'){
                                    show_page();
                                    alert("删除成功!");
                                }else{
                                    alert("删除失败!");
                                }
                            }
                        });
                    }
                }
            },
            {
                text: '立项正确',
                icon: 'edit',
                showMenuFn: function (row) {
                    if(row.isTrue == 0 && row.buildCheck != '正确'){
                        return true;
                    }else{
                        return false;
                    }
                },
                action: function (row) {
                    if(confirm("确定要标记该合同【"+row.contractCode+"】下的数据立项正确吗?")){
                        $.ajax({
                            type : "POST",
                            url : "?model=contract_counting_counting&action=setProjectIsTrue",
                            data : {
                                "id" : row.contractId
                            },
                            async : false,
                            success : function(data) {
                                if(data == 'ok'){
                                    show_page();
                                    alert("标记成功!");
                                }else{
                                    alert("标记失败!");
                                }
                            }
                        });
                    }
                }
            }
        ]

    });
});