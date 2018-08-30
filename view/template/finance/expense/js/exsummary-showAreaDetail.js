
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

$(function(){
    var show_page = function () {
        $("#pageGrid").yxgrid("reload");
    };

    var periodArr = [];
    var thisYear = '';

    $.ajax({
        type: "POST",
        url: "?model=finance_expense_exsummary&action=getFinancePeriodYear",
        dataType: "Json",
        async: false,
        success: function (data) {
            thisYear = ($("#thisYear").val() == '')? data.thisYear : $("#thisYear").val();
            periodArr = data.allYears;
        }
    });

    $("#pageGrid").yxgrid({
        model: 'finance_expense_exsummary',
        action: 'pageStatisticAll',
        param: {
            show_type   : "byMan",
            SalesAreaId : $("#SalesAreaId").val(),
            isAreaDetail : 1
        },

        title: '����ͳ���б�',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        isOpButton: false,
        noCheckIdValue: 'noId',
        // ����Ϣ
        colModel: [
            {
                display: '���',
                name: 'moduleName',
                width: 80,
                align: 'left',
                sortable: false
            },
            {
                display: '��������',
                name: 'exeDeptName',
                width: 100,
                align: 'left',
                sortable: false
            },
            {
                display: '��������',
                name: 'SalesArea',
                width: 100,
                align: 'left',
                // hide: true,
                sortable: false
            },
            {
                display: '������Ա',
                name: 'feeMan',
                width: 80,
                align: 'left',
                // hide: true,
                sortable: false
            },
            {
                display: '������ԱID',
                name: 'feeManId',
                width: 80,
                align: 'left',
                hide: true,
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
                display: '�������۷����ۼ�',
                name: 'totalFee',
                width: 120,
                align: 'right',
                sortable: true,
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
                width: 120,
                align: 'right',
                sortable: true,
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
                width: 120,
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
        }, {
            display: '��Ա����',
            name: 'feeMan'
        }],
        setSearchAll : false
    });
});