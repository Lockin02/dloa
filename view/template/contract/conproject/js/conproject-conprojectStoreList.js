var show_page = function (page) {
    $("#conprojectListGrid").yxsubgrid("reload");
};
$(function () {

});
$(function () {
    buttonsArr = [],
        SJGX = {
            name: 'edit',
            text: "数据操作",
            icon: 'copy',
            items: [
                {
                    text: '数据更新',
                    icon: 'copy',
                    action: function (row) {
                        showThickboxWin('?model=contract_conproject_conproject&action=progressView'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
                    }
                },
                {
                    name: 'edit',
                    text: "保存版本",
                    icon: 'save',
                    action: function (row) {
                        var version = $("#version").val();
                        showThickboxWin('?model=contract_conproject_conproject&action=saveVersionView'
                            + '&versionNum=' + version
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');
                    }
                }
            ]
        };
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '数据操作'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(SJGX);
            }
        }
    });
    LBDC = {
        name: 'export',
        text: "列表数据导出",
        icon: 'excel',
        action: function (row) {
            var searchConditionKey = "";
            var searchConditionVal = "";
            for (var t in $("#conprojectListGrid").data('yxsubgrid').options.searchParam) {
                if (t != "") {
                    searchConditionKey += t;
                    searchConditionVal += $("#conprojectListGrid")
                        .data('yxsubgrid').options.searchParam[t];
                }
            }
            var i = 1;
            var colId = "";
            var colName = "";
            var version = $("#nowVersion").val();
            $("#conprojectListGrid_hTable").children("thead").children("tr")
                .children("th").each(function () {
                    if ($(this).css("display") != "none"
                        && $(this).attr("colId") != undefined && $(this).attr("axis") != 'col1') {
                        colName += $(this).children("div").html() + ",";
                        colId += $(this).attr("colId") + ",";
                        i++;
                    }
                })
            var searchSql = $("#conprojectListGrid").data('yxsubgrid').getAdvSql();
            var searchArr = [];
            searchArr[0] = searchSql;
            searchArr[1] = searchConditionKey;
            searchArr[2] = searchConditionVal;
            window
                .open("?model=contract_conproject_conproject&action=exportExcel&colId="
                    + colId
                    + "&colName="
                    + colName
                    + "&searchConditionKey="
                    + searchConditionKey
                    + "&searchConditionVal=" + searchConditionVal
                    + "&version="
                    + version)
        }
    }
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '列表导出'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(LBDC);
            }
        }
    });
    DR = {
        name: 'excel',
        text: "导入操作",
        icon: 'excel',
        items: [
            {
                text: "项目决算导入",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toLeadfinanceMoney"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            },
            {
                text: "合同项目导入",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toExcel"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            },
            {
                text: "收入确认方式导入",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toExcelExtend"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            }
        ]
    };
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '数据导入'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(DR);
            }
        }
    });

    SX = {
        text: "重置",
        icon: 'delete',
        action: function (row) {
            history.go(0)
        }
    }
    buttonsArr.push(SX);


    $("#conprojectListGrid").yxsubgrid({
        model: 'contract_conproject_conproject',
        action: 'conprojectStoreJson',
        param: {'version': $("#nowVersion").val()},
        customCode: 'conprojectStoreListNewList',
        title: '合同项目表',
        isOpButton: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        showcheckbox: false,
        leftLayout: true,
//		lockCol : ['conflag','checkTip'],// 锁定的列名
        //列信息
        colModel: [
            {
                display: '项目类型',
                name: 'proType',
                sortable: true,
                width: 50,
                align: 'center',
                process: function (v, row) {
//				return "<img src='images/icon/kong.gif' style='width:15px;height:15px;'>";
                    if (row.esmProjectId != '' && row.esmProjectId != '0') {
                        return "<img src='images/icon/service.jpg' ></span>";
                    } else {
                        return "<img src='images/icon/devi.jpg' ></span>";
                    }
                }
            },
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                display: 'pid',
                name: 'pid',
                sortable: true,
                hide: true
            },
            {
                name: 'contractId',
                display: '合同id',
                sortable: true,
                hide: true
            },
            {
                name: 'contractCode',
                display: '合同编号',
                sortable: true,
                width: 100,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.contractId
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + row.contractCode
                        + "</font>" + '</a>';
                }
            },
            {
                display: 'esmProjectId',
                name: 'esmProjectId',
                sortable: true,
                hide: true
            },
            {
                name: 'projectCode',
                display: '项目编号',
                sortable: true,
                width: 130,
                process: function (v, row) {
                    if (row.esmProjectId != '' && row.esmProjectId != '0') {
                        var skey = "";
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_project_esmproject&action=md5RowAjax",
                            data: { "id": row.esmProjectId },
                            async: false,
                            success: function (data) {
                                skey = data;
                            }
                        });
                        return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=engineering_project_esmproject&action=viewTab&id='
                            + row.esmProjectId
                            + '&skey='
                            + skey
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>"
                            + v
                            + "</font>" + '</a>';
                    } else {
                        return v;

                    }
                }
            },
            {
                name: 'projectName',
                display: '项目名称',
                sortable: true,
                hide: true
            },
            {
                name: 'proLineName',
                display: '产品线',
                sortable: true
            },
            {
                name: 'proLineCode',
                display: '产品线编码',
                sortable: true,
                hide: true
            },
            {
                name: 'proportionTrue',
                display: '产线占比',
                sortable: true,
                align: 'right',
                width: 50,
                hide: true,
                process: function (v) {
                    return v + "%";
                }
            },
            {
                name: 'officeName',
                display: '执行区域',
                sortable: true
            },
            {
                name: 'officeId',
                display: '执行区域id',
                sortable: true,
                hide: true
            },
            {
                name: 'proMoney',
                display: '项目合同额',
                sortable: true,
                width: 60,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'rateMoney',
                display: '不含税金额',
                sortable: true,
                align: 'right',
                width: 80,
                hide: true,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'proportion',
                display: '项目占比',
                sortable: true,
                align: 'right',
                width: 50,
                process: function (v) {
                    return v + "%";
                }
            },
            {
                name: 'contractMoney',
                display: '合同金额',
                sortable: true,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'status',
                display: '项目状态',
                sortable: true,
                align: 'center',
                width: 50,
                datacode: 'GCXMZT'
            },
            {
                name: 'schedule',
                display: '项目进度',
                sortable: true,
                width: 80,
                process: function (v) {
                    var v = formatProgress(v);
                    return v;
                }
            },
            {
                name: 'exgross',
                display: '预计毛利率',
                sortable: true,
                align: 'right',
                width: 70,
                process: function (v) {
                    if (v){
                        if (v < 0){
                            return "<span class='red'>" + v + "%</span>";
                        }else if(v==0){
                            return "-";
                        }else{
                            return v + "%";
                        }
                    }else{
                        return "-";
                    }
                }
            },
            {
                name: 'exgrossTrue',
                display: '毛利率',
                width: 50,
                sortable: true,
                align: 'right',
                process: function (v, row) {
                    if (v && (row.cost != '0.00' || row.costAct != '0.00') && row.schedule != '0.00')
                        if (v < 0 || ((v - row.exgross) < 0 && v != 0))
                            return "<span class='red'>" + v + "%</span>";
                        else
                            return v + "%";
                    else
                        return "-";
                }
            },
            {
                name: 'estimates',
                display: '概算',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'budget',
                display: '预算',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.estimates) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'cost',
                display: '决算',
                sortable: true,
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.budget) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'costAct',
                display: '核算决算',
                sortable: true,
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.budget) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'earnings',
                display: '收入',
                sortable: true,
                width: 70,
                process: function (v) {
                    if (v == '0.00' || v == '0') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'grossTrue',
                display: '毛利',
                sortable: true,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'gross',
                display: '预计毛利',
                sortable: true,
                align: 'right',
                hide: true,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'earningsTypeName',
                display: '收入确认方式',
                width: 80,
                sortable: true
            },
            {
                name: 'earningsTypeCode',
                display: '收入确认方式编码',
                sortable: true,
                hide: true
            },
            {
                name: 'reserveEarnings',
                display: '预留营收',
                sortable: true,
                width: 70,
                process: function (v) {
                    if (v == '0.00' || v == '0') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right',
                hide: true
            },
            {
                name: 'txaRate',
                display: '税点',
                sortable: true,
                align: 'right',
                width: 50,
                hide: true,
                process: function (v) {
                    if (v)
                        return v + "%";
                    else
                        return "-";
                }
            },
            {
                name: 'moduleName',
                display: '板块',
                sortable: true,
                hide: true
            },
            {
                name: 'module',
                display: '板块编码',
                sortable: true,
                hide: true
            },
            {
                name: 'planBeginDate',
                display: '预计开始日期',
                sortable: true,
                hide: true
            },
            {
                name: 'planEndDate',
                display: '预计结束日期',
                sortable: true,
                hide: true
            },
            {
                name: 'actBeginDate',
                display: '实际开始日期',
                sortable: true,
                hide: true
            },
            {
                name: 'actEndDate',
                display: '实际结束日期',
                sortable: true,
                hide: true
            }
            ,
            {
                display: '考核标识',
                name: 'checkTip',
                sortable: true,
                width: 50,
                align: 'center',
                hide: true,
                process: function (v, row) {
//				return "<img src='images/icon/kong.gif' style='width:15px;height:15px;'>";
                    if (v == '0') {
                        return "<span  onclick='checkTip(\"" + row.id + "\",\"" + row.pid + "\",1)'><img src='images/icon/shi.gif' style='width:20px;height:20px;'></span>";
                    } else if (v == '1') {
                        return "<span  onclick='checkTip(\"" + row.id + "\",\"" + row.pid + "\",0)'><img src='images/icon/kong.gif' style='width:20px;height:20px;'></span>";
                    }
                }
            },
            {
                name: 'deductMoney',
                display: '扣款',
                align: 'right',
                width: 60,
                process: function (v,row) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'badMoney',
                display: '坏账',
                align: 'right',
                width: 60,
                process: function (v,row) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            }
        ],
        sortname: "contractId",
        // 默认搜索顺序
        sortorder: "desc",
        searchitems: [
            {
                display: "合同编号",
                name: 'contractCode'
            },
            {
                display: "项目编号",
                name: 'projectCode'
            },
            {
                display: "执行部门",
                name: 'proLineName'
            }
        ],
        menusEx: [
//            {
//                text: '确认收入核算方式',
//                icon: 'add',
//                action: function (row) {
//                    showThickboxWin('?model=contract_conproject_conproject&action=incomeAcc&id='
//                        + row.id
//                        + '&pid='
//                        + row.pid
//                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600');
//                }
//            }
        ],
        comboEx: [
            {
                text: '产品线',
                key: 'proLineCode',
                datacode: 'HTCPX'
            },
            {
                text: '执行区域',
                key: 'officeId',
                datacode: 'GCSCX'
            },
            {
                text: '项目状态',
                key: 'status',
                datacode: 'GCXMZT'
            },
            {
                text: '预警过滤',
                key: 'warningStr',
                data: [
                    {
                        text: '预警数据',
                        value: '1'
                    }
                ]
            }
        ],
        // 主从表格设置
        subGridOptions: {
            url: '?model=contract_conproject_conproject&action=conProsubJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
                {
                    paramId: 'pid',// 传递给后台的参数名称
                    colId: 'pid'// 获取主表行数据的列名称

                }
            ],
            // 显示的列
            colModel: [
                {
                    name: 'indexName',
                    display: '指标',
                    sortable: true,
                    width: 60
                },
                {
                    name: 'January',
                    display: '一月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'February',
                    display: '二月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'March',
                    display: '三月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'April',
                    display: '四月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'May',
                    display: '五月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'June',
                    display: '六月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'July',
                    display: '七月份',
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'August',
                    display: '八月份',
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'September',
                    display: '九月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'October',
                    display: '十月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'November',
                    display: '十一月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'December',
                    display: '十二月份',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                }
            ]
        },
        buttonsEx: buttonsArr
    });

//	 document.getElementById("storeYear").options.remove(0);
//	 document.getElementById("storeMon").options.remove(0);

    $("#view").append("<br/><div id='versionNum' class='red'>最新版本号: V<span>" + $("#maxVersion").val() + "</span></div>");
    var M = new Date()
    var Year = M.getFullYear();
    var Year2 = Year - 2;
    var Year1 = Year - 1;
    var month = M.getMonth() + 1;
    $("#view").append("<tr><td class='form_text_left'>版本年份</td>" +
        "<td class='form_view_right'>" +
        "<select class='selectauto' id='storeYear' style='width: 100%;' onchange='createVersionNum()'>" +
        "<option value='0'>" + "...选择..." + "</option>" +
        "<option value='" + Year + "'>" + Year + "年</option>" +
        "<option value='" + Year1 + "'>" + Year1 + "年</option>" +
        "<option value='" + Year2 + "'>" + Year2 + "年</option>" +
        "</select></td></tr>  ");
    $("#view").append("<tr><td class='form_text_left'>版本月份</td>" +
        "<td class='form_view_right'>" +
        "<select class='selectauto' id='storeMon' style='width: 100%;' onchange='createVersionNum()'>" +
        "<option value='0'>" + "...选择..." + "</option>" +
        "<option value='1'>1月</option><option value='2'>2月</option><option value='3'>3月</option><option value='4'>4月</option>" +
        "<option value='5'>5月</option><option value='6'>6月</option><option value='7'>7月</option><option value='8'>8月</option>" +
        "<option value='9'>9月</option><option value='10'>10月</option><option value='11'>11月</option><option value='12'>12月</option>" +
        "</select></td></tr>  ");

});
//构建查看版本号
function createVersionNum() {
    var year = $("#storeYear").val();
    var mon = $("#storeMon").val();

    varsionArr = [];
    $.ajax({
        type: "POST",
        url: "?model=contract_conproject_conproject&action=getVarsionArr",
        data: {"year": year, "mon": mon},
        async: false,
        success: function (data) {
            $("#view").append("<div id='verSelect'></div>");
            if (data != 0) {
                $("#verSelect").html("<tr><td class='form_text_left'>版本号</td>" +
                    "<td class='form_view_right'>" +
                    "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
                    data +
                    "</select></td></tr>  ");
            } else {
                $("#verSelect").html("<tr><td class='form_text_left'>版本号</td>" +
                    "<td class='form_view_right'>" +
                    "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
                    "<option>暂无数据</option>" +
                    "</select></td></tr>  ");
            }

        }
    });

}
//重置查询版本数据
function setVersion() {
    var version = $("#version").val();
    if (version != '0') {
        $("#versionNum").html("<div id='versionNum' class='red'>最新版本号: V<span>" + $("#maxVersion").val() + "</span></div>" +
            "<div id='versionNum' class='blue'>当前版本号: V<span>" + version + "</span></div>");
    }

    $("#nowVersion").val(version);
    var listGrid = $("#conprojectListGrid").data('yxsubgrid');
    listGrid.options.extParam['version'] = version;
    listGrid.reload();

}

//用于列表进度显示
function formatProgress(value) {
    if (value) {
        var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
            + '<div style="width:'
            + value
            + '%;background:#66FF66;white-space:nowrap;padding: 0px;">'
            + value + '%' + '</div>'
        '</div>';
        return s;
    } else {
        return '';
    }
}

//考核标识
function checkTip(id, pid, val) {
    $.ajax({
        type: "POST",
        url: "?model=contract_conproject_conproject&action=ajaxCheckTip",
        data: { "id": id, "pid": pid, "val": val},
        async: false,
        success: function (data) {
            skey = data;
            $("#conprojectListGrid").yxsubgrid("reload");
        }
    });
}
//保存当前版本数据
function saveVersion() {
    $.ajax({
        type: "POST",
        url: "?model=contract_conproject_conproject&action=saveVersion",
        data: {},
        async: false,
        success: function (data) {
            alert("保存成功!");
            $("#conprojectListGrid").yxsubgrid("reload");
        }
    });
}