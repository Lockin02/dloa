var show_page = function () {
    // ��ʾ������
    var show_type = $('#show_type').val();
    if (show_type == 'byMan' || show_type == 'byArea') {
        $("#showStatisticGrid").yxgrid("reload");
    } else {
        $("#showStatisticGrid").yxsubgrid("reload");
    }
};

//�鿴����ͳ����ϸ
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

    // ��ʾ������
    var show_type = $('#show_type').val();
    if (show_type == 'byMan' || show_type == 'byArea') {
        initOrgGrid(show_type, periodArr, thisYear);
    } else {
        initGroupGrid(show_type, periodArr, thisYear);
    }
});

// ��ʼ��ͳ�Ʊ��
var initGroupGrid = function (show_type, periodArr, thisYear) {
    var buttonsEx = [];
    var excelOutBtn = {
        text: '����',
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

        title: '����ͳ���б�',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        isOpButton: false,
        noCheckIdValue: 'noId',
        buttonsEx : buttonsEx,
        // ����Ϣ
        colModel: [
            {
                display: '���',
                name: 'moduleName',
                width: 200,
                align: 'left',
                sortable: false
            },
            {
                display: '��������',
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
                display: '��������',
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
                display: '�������۷����ۼ�',
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
                display: '������ǩ��ͬ��',
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
                display: '�������ͬͬ����',
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

        // ���ӱ���˸��ֶ�   ����ͺ�   2013.7.5
        subGridOptions: {
            url: '?model=finance_expense_exsummary&action=getSubStatisticByExeDep',
            param: [{
                paramId: 'exeDepFilter',
                colId: 'exeDepFilter'
            }],
            colModel: [{
                display: '���',
                name: 'moduleName',
                width: 200,
                align: 'left',
                sortable: false
            },{
                display: '��������',
                name: 'exeDeptName',
                width: 200,
                align: 'left',
                sortable: false
            },{
                display: '��������',
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
                display: '�������۷����ۼ�',
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
                display: '������ǩ��ͬ��',
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
                display: '�������ͬͬ����',
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
         * ��������
         */
        comboEx: [{
            text: '���',
            key: 'theYear',
            value: thisYear,
            data: periodArr
        }],
        /**
         * ��������
         */
        searchitems: [{
            display: '��������',
            name: 'exeDeptName'
        }, {
            display: '��������',
            name: 'SalesArea'
        }
        // , {
        //     display: '��Ա����',
        //     name: 'feeMan'
        // }
        ],
        setSearchAll : false
    });
};

// ��ʼ��ԭ���ı��
var initOrgGrid = function (show_type, periodArr, thisYear) {
    $("#showStatisticGrid").yxgrid({
        model: 'finance_expense_exsummary',
        action: 'pageStatisticAll',
        param: {
            show_type: show_type
        },

        title: '����ͳ���б�',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        isOpButton: false,

        // ����Ϣ
        colModel: (show_type == 'byMan') ?
            [
                {
                    display: '��ԱID',
                    name: 'feeManId',
                    width: 250,
                    align: 'center',
                    hide: true,
                    sortable: false
                },
                {
                    display: '��Ա����',
                    name: 'feeMan',
                    width: 250,
                    align: 'center',
                    sortable: false
                },
                {
                    display: '����ID',
                    name: 'SalesAreaId',
                    width: 250,
                    align: 'center',
                    hide: true,
                    sortable: false
                },
                {
                    display: '��������',
                    name: 'SalesArea',
                    width: 250,
                    align: 'center',
                    sortable: false
                },
                {
                    display: '�������۷����ۼ�',
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
                    display: '������ǩ��ͬ��',
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
                    display: '�������ͬͬ����',
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
                    display: '��������',
                    name: 'SalesArea',
                    width: 250,
                    align: 'center',
                    sortable: false
                },
                {
                    display: '�������۷����ۼ�',
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
                    display: '������ǩ��ͬ��',
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
                    display: '�������ͬͬ����',
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
         * ��������
         */
        comboEx: [{
            text: '���',
            key: 'theYear',
            value: thisYear,
            data: periodArr
        }],
        /**
         * ��������
         */
        searchitems: (show_type == 'byMan') ?
            [{
                display: '��Ա����',
                name: 'feeMan'
            }, {
                display: '��������',
                name: 'SalesArea'
            }] :

            [{
                display: '��������',
                name: 'SalesArea'
            }]
    });
};