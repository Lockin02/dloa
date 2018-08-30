var show_page = function (page) {
    $("#pageGrid").yxgrid("reload");
};
$(function () {

    var act = $("#act").val();
    var areaId = $("#areaId").val();
    var userId = $("#userId").val();
    var year = $("#year").val();
    var excelInLimit = $("#excelInLimit").val();
    var exeDeptCode = $("#exeDeptCode").val();
    var param = {};
    var periodArr = [];
    var thisYear = '';

    $.ajax({
        type: "POST",
        url: "?model=finance_expense_exsummary&action=getFinancePeriodYear",
        dataType: "Json",
        async: false,
        success: function (data) {
            data = eval("("+data+")");
            thisYear = (year == "")? data.thisYear : year;
            periodArr = data.allYears;
        }
    });

    if(areaId != ''){param.paramAreaId = areaId;}
    if(year != ''){param.paramYear = year;}
    if(exeDeptCode != ''){param.exeDeptCode = exeDeptCode;}
    if(userId != ''){param.costManId = userId;}

    var buttonsArr = [];
    if(act != 'list' && excelInLimit == 1){
        buttonsArr.push({
            name: 'import',
            text: "导入",
            icon: 'excel',
            action : function(row) {
                showThickboxWin("?model=finance_salescost_salescostimport&action=toImportExcel"
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
            }
        });
    }

    $("#pageGrid").yxgrid({
        model: 'finance_salescost_salescostimport',
        action: 'pageJson',
        title: '销售费用导入',
        param : param,
        showcheckbox : false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'belongMonth',
                display: "月份",
                sortable: true,
            },
            {
                name: 'exeDeptName',
                display: "归属大区",
                sortable: true,
            },
            {
                name: 'salesArea',
                display: "归属区域",
                sortable: true,

            },
            {
                name: 'costManId',
                display: '费用承担人ID',
                sortable: true,
                width: 80,
                hide: true
            },
            {
                name: 'costMan',
                display: '费用承担人',
                sortable: true
            },
            {
                name: 'costAmount',
                display: '其他费用',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
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
        searchitems: [
            {
                display: '归属大区',
                name: 'exeDeptNameSearch'
            },
            {
                display: '归属区域',
                name: 'salesAreaSearch'
            },
            {
                display: '月份',
                name: 'belongMonthSearch'
            },
            {
                display: '费用承担人',
                name: 'costManSearch'
            }
        ],
        // sortname: "createTime",
        buttonsEx: buttonsArr
    });
});