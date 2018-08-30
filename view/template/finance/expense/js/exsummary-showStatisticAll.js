var show_page = function () {
    // 显示的类型
    var show_type = $('#show_type').val();
    if (show_type == 'byMan' || show_type == 'byArea') {
        $("#showStatisticGrid").yxgrid("reload");
    } else {
        $("#showStatisticGrid").yxsubgrid("reload");
    }
};

//查看费用统计明细
function showStatisticDetail(userId, areaId, feeType, year, exeDeptCode) {
    var areaIdStr = (areaId != undefined || areaId != '')? "&areaId="+areaId : "";
    var exeDeptCodeStr = (exeDeptCode != undefined)? "&exeDeptCode="+exeDeptCode : "";
    showModalWin('?model=finance_expense_exsummary&action=showStatisticDetail&userId='
        + userId
        + areaIdStr
        + '&thisYear='
        + year
        + '&view_type=view_all'
        + '&feeType='
        + feeType
        + exeDeptCodeStr
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
}

function showAreaDetail(areaId,thisYear){
    showThickboxWin("?model=finance_expense_exsummary&action=toAreaDetail&areaId="
        + areaId
        + "&thisYear=" + thisYear
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
}

$(function () {
    var periodArr = [];
    var thisYear = '';

    $.ajax({
        type: "POST",
        url: "?model=finance_expense_exsummary&action=getFinancePeriodYear",
        dataType: "Json",
        async: false,
        success: function (data) {
            thisYear = data.thisYear;
            periodArr = data.allYears;
        }
    });

    // 显示的类型
    var show_type = $('#show_type').val();
    if (show_type == 'byMan' || show_type == 'byArea') {
        initOrgGrid(show_type, periodArr, thisYear);
    } else {
        initGroupGrid(show_type, periodArr, thisYear);
    }
});

// 初始化统计表格
var initGroupGrid = function (show_type, periodArr, thisYear) {
    var buttonsEx = [];
    var excelOutBtn = {
        text: '导出',
        icon: 'excel',
        action: function () {
            var thisGrid = $("#showStatisticGrid").data('yxsubgrid');
            var url = "?model=finance_expense_exsummary&action=export"
                + '&theYear=' + filterUndefined(thisGrid.options.param.theYear);
            if (thisGrid.options.qtype) {
                url += "&" + thisGrid.options.qtype + "=" + thisGrid.options.query;
            }
            window.open(url, "", "width=200,height=200,top=200,left=200");
        }
    };

    var exportLimit = $.ajax({
        url:"?model=finance_expense_exsummary&action=chkExportLimit",
        type : "POST",
        async : false
    }).responseText;
    if(exportLimit == "1"){
        buttonsEx.push(excelOutBtn);
    }

    $("#showStatisticGrid").yxsubgrid({
        model: 'finance_expense_exsummary',
        action: 'pageStatisticAll',
        param: {
            show_type: show_type,
            isBigGroup : 1
        },

        title: '费用统计列表',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        isOpButton: false,
        noCheckIdValue: 'noId',
        buttonsEx : buttonsEx,
        // 列信息
        colModel: [
            {
                display: '板块',
                name: 'moduleName',
                width: 200,
                align: 'left',
                sortable: false
            },
            {
                display: '归属大区',
                name: 'exeDeptName',
                width: 200,
                align: 'left',
                sortable: false
            },
            {
                display: 'id',
                name: 'SalesAreaId',
                width: 200,
                align: 'left',
                hide: true,
                sortable: false
            },
            {
                display: '归属区域',
                name: 'SalesArea',
                width: 200,
                align: 'left',
                sortable: false,
                process: function (v, row) {
                    var aid = row.SalesAreaId;
                    var year = row.thisYear;
                    // return "<a href='javascript:void(0);' onclick=showAreaDetail('" + aid + "','" + year + "')>" + v + "</a>";
                    return "";
                }
            },
            {
                display: '本年销售费用累计',
                name: 'totalFee',
                width: 200,
                align: 'right',
                sortable: true,
                process: function (v, row) {
                    var uid = "";
                    var aid = row.SalesAreaId;
                    var year = row.thisYear;
                    if (v == '0' || v == '') {
                        v = '0.00';
                    } else {
                        v = moneyFormat2(v);
                    }
                    return v;
                    // return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','','fee','" + year + "','" + row.exeDeptCode + "') class='formatMoney'>" + v + "</a>";
                }
            },
            {
                display: '本年新签合同额',
                name: 'totalContract',
                width: 200,
                align: 'right',
                sortable: true,
                process: function (v, row) {
                    var uid = "";
                    var aid = row.SalesAreaId;
                    var year = row.thisYear;
                    if (v == '0' || v == '') {
                        v = '0.00';
                    } else {
                        v = moneyFormat2(v);
                    }
                    return v;
                    // return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','','contract','" + year + "','" + row.exeDeptCode + "') class='formatMoney'>" + v + "</a>";
                }
            },
            {
                display: '费用与合同同步率',
                name: 'rate',
                width: 200,
                align: 'right',
                sortable: false,
                process: function (v, row) {
                    var color = row.rate >= 100 ? 'red' : 'green';
                    color = (row.totalContract <= 0 || row.totalFee <= 0) ? 'red' : color;
                    if (v == '0' || v == '') {
                        v = '0.00';
                    }
                    return "<span style='color:" + color + "'>" + v + "%</span>";
                }
            }
        ],

        // 主从表加了个字段   规格型号   2013.7.5
        subGridOptions: {
            url: '?model=finance_expense_exsummary&action=getSubStatisticByExeDep',
            param: [{
                paramId: 'exeDepFilter',
                colId: 'exeDepFilter'
            }],
            colModel: [{
                display: '板块',
                name: 'moduleName',
                width: 200,
                align: 'left',
                sortable: false
            },{
                display: '归属大区',
                name: 'exeDeptName',
                width: 200,
                align: 'left',
                sortable: false
            },{
                display: '归属区域',
                name: 'SalesArea',
                width: 200,
                align: 'left',
                sortable: false,
                process: function (v, row) {
                    var aid = row.SalesAreaId;
                    var year = row.thisYear;
                    return "<a href='javascript:void(0);' onclick=showAreaDetail('" + aid + "','" + year + "')>" + v + "</a>";
                }
            },{
                display: '本年销售费用累计',
                name: 'totalFee',
                width: 190,
                align: 'right',
                sortable: false,
                process: function (v, row) {
                    var uid = row.feeManId;
                    uid = (uid == undefined)? '' : uid;
                    var aid = row.SalesAreaId;
                    var year = row.thisYear;
                    if (v == '0' || v == '') {
                        v = '0.00';
                    } else {
                        v = moneyFormat2(v);
                    }
                    return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','" + aid + "','fee','" + year + "') class='formatMoney'>" + v + "</a>";
                }
            }, {
                display: '本年新签合同额',
                name: 'totalContract',
                width: 190,
                align: 'right',
                sortable: false,
                process: function (v, row) {
                    var uid = row.feeManId;
                    uid = (uid == undefined)? '' : uid;
                    var aid = row.SalesAreaId;
                    var year = row.thisYear;
                    if (v == '0' || v == '') {
                        v = '0.00';
                    } else {
                        v = moneyFormat2(v);
                    }
                    return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','" + aid + "','contract','" + year + "') class='formatMoney'>" + v + "</a>";
                }
            }, {
                display: '费用与合同同步率',
                name: 'rate',
                width: 190,
                align: 'right',
                sortable: false,
                process: function (v, row) {
                    var color = row.rate >= 100 ? 'red' : 'green';
                    color = (row.totalContract <= 0 || row.totalFee <= 0) ? 'red' : color;
                    if (v == '0' || v == '') {
                        v = '0.00';
                    }
                    return "<span style='color:" + color + "'>" + v + "%</span>";
                }
            }]
        },

        /**
         * 过滤数据
         */
        comboEx: [{
            text: '年度',
            key: 'theYear',
            value: thisYear,
            data: periodArr
        }],
        /**
         * 快速搜索
         */
        searchitems: [{
            display: '归属大区',
            name: 'exeDeptName'
        }, {
            display: '归属区域',
            name: 'SalesArea'
        }
        // , {
        //     display: '人员名称',
        //     name: 'feeMan'
        // }
        ],
        setSearchAll : false
    });
};

// 初始化原来的表格
var initOrgGrid = function (show_type, periodArr, thisYear) {
    $("#showStatisticGrid").yxgrid({
        model: 'finance_expense_exsummary',
        action: 'pageStatisticAll',
        param: {
            show_type: show_type
        },

        title: '费用统计列表',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        isOpButton: false,

        // 列信息
        colModel: (show_type == 'byMan') ?
            [
                {
                    display: '人员ID',
                    name: 'feeManId',
                    width: 250,
                    align: 'center',
                    hide: true,
                    sortable: false
                },
                {
                    display: '人员名称',
                    name: 'feeMan',
                    width: 250,
                    align: 'center',
                    sortable: false
                },
                {
                    display: '区域ID',
                    name: 'SalesAreaId',
                    width: 250,
                    align: 'center',
                    hide: true,
                    sortable: false
                },
                {
                    display: '区域名称',
                    name: 'SalesArea',
                    width: 250,
                    align: 'center',
                    sortable: false
                },
                {
                    display: '本年销售费用累计',
                    name: 'totalFee',
                    width: 300,
                    align: 'center',
                    sortable: false,
                    process: function (v, row) {
                        var uid = row.feeManId;
                        var aid = row.SalesAreaId;
                        var year = row.thisYear;
                        if (v == '0' || v == '') {
                            v = '0.00';
                        } else {
                            v = moneyFormat2(v);
                        }

                        return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','" + aid + "','fee','" + year + "') class='formatMoney'>" + v + "</a>";
                    }
                },
                {
                    display: '本年新签合同额',
                    name: 'totalContract',
                    width: 300,
                    align: 'center',
                    sortable: false,
                    process: function (v, row) {
                        var uid = row.feeManId;
                        var aid = row.SalesAreaId;
                        var year = row.thisYear;
                        if (v == '0' || v == '') {
                            v = '0.00';
                        } else {
                            v = moneyFormat2(v);
                        }

                        return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','" + aid + "','contract','" + year + "') class='formatMoney'>" + v + "</a>";
                    }
                },
                {
                    display: '费用与合同同步率',
                    name: 'rate',
                    width: 300,
                    align: 'center',
                    sortable: false,
                    process: function (v, row) {
                        var color = row.rate >= 100 ? 'red' : 'green';
                        color = (row.totalContract <= 0 || row.totalFee <= 0) ? 'red' : color;
                        if (v == '0' || v == '') {
                            v = '0.00';
                        }
                        return "<span style='color:" + color + "'>" + v + "%</span>";
                    }
                }
            ]
            :
            [
                {
                    display: 'id',
                    name: 'SalesAreaId',
                    width: 250,
                    align: 'center',
                    hide: true,
                    sortable: false
                },
                {
                    display: '区域名称',
                    name: 'SalesArea',
                    width: 250,
                    align: 'center',
                    sortable: false
                },
                {
                    display: '本年销售费用累计',
                    name: 'totalFee',
                    width: 300,
                    align: 'center',
                    sortable: false,
                    process: function (v, row) {
                        var uid = "";
                        var aid = row.SalesAreaId;
                        var year = row.thisYear;
                        if (v == '0' || v == '') {
                            v = '0.00';
                        } else {
                            v = moneyFormat2(v);
                        }

                        return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','" + aid + "','fee','" + year + "') class='formatMoney'>" + v + "</a>";
                    }
                },
                {
                    display: '本年新签合同额',
                    name: 'totalContract',
                    width: 300,
                    align: 'center',
                    sortable: false,
                    process: function (v, row) {
                        var uid = "";
                        var aid = row.SalesAreaId;
                        var year = row.thisYear;
                        if (v == '0' || v == '') {
                            v = '0.00';
                        } else {
                            v = moneyFormat2(v);
                        }

                        return "<a href='javascript:void(0);' onclick=showStatisticDetail('" + uid + "','" + aid + "','contract','" + year + "') class='formatMoney'>" + v + "</a>";
                    }
                },
                {
                    display: '费用与合同同步率',
                    name: 'rate',
                    width: 300,
                    align: 'center',
                    sortable: false,
                    process: function (v, row) {
                        var color = row.rate >= 100 ? 'red' : 'green';
                        color = (row.totalContract <= 0 || row.totalFee <= 0) ? 'red' : color;
                        if (v == '0' || v == '') {
                            v = '0.00';
                        }
                        return "<span style='color:" + color + "'>" + v + "%</span>";
                    }
                }
            ],

        /**
         * 过滤数据
         */
        comboEx: [{
            text: '年度',
            key: 'theYear',
            value: thisYear,
            data: periodArr
        }],
        /**
         * 快速搜索
         */
        searchitems: (show_type == 'byMan') ?
            [{
                display: '人员名称',
                name: 'feeMan'
            }, {
                display: '区域名称',
                name: 'SalesArea'
            }] :

            [{
                display: '区域名称',
                name: 'SalesArea'
            }]
    });
};