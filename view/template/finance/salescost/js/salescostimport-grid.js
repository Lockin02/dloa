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
            text: "����",
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
        title: '���۷��õ���',
        param : param,
        showcheckbox : false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'belongMonth',
                display: "�·�",
                sortable: true,
            },
            {
                name: 'exeDeptName',
                display: "��������",
                sortable: true,
            },
            {
                name: 'salesArea',
                display: "��������",
                sortable: true,

            },
            {
                name: 'costManId',
                display: '���óе���ID',
                sortable: true,
                width: 80,
                hide: true
            },
            {
                name: 'costMan',
                display: '���óе���',
                sortable: true
            },
            {
                name: 'costAmount',
                display: '��������',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
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
        searchitems: [
            {
                display: '��������',
                name: 'exeDeptNameSearch'
            },
            {
                display: '��������',
                name: 'salesAreaSearch'
            },
            {
                display: '�·�',
                name: 'belongMonthSearch'
            },
            {
                display: '���óе���',
                name: 'costManSearch'
            }
        ],
        // sortname: "createTime",
        buttonsEx: buttonsArr
    });
});