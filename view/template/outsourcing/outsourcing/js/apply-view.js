$(document).ready(function () {
    if ($("#actType").val() == 'audit') {
        $("#buttonTable").hide();
    }
    // 初始化外包类型
    var outType = $(".otypename:checked").val();
    changeType(outType);

    // 初始化项目的一些信息
    $.ajax({
        url: "?model=engineering_project_esmproject&action=ajaxGetProject",
        data: {id: $("#projectId").val()},
        type: 'POST',
        dataType: 'json',
        success: function(msg) {
            if (msg.id) {
                $("#projectExpectedDuration").text(msg.expectedDuration);
                $("#projectAttribute").text(msg.attributeName);
                $("#projectCategory").text(msg.categoryName);
                $("#projectStatus").text(msg.statusName);
                $("#projectMoneyWithTax").text(moneyFormat2(msg.projectMoneyWithTax));
                $("#estimates").text(moneyFormat2(msg.estimates));
                $("#budgetAll").text(moneyFormat2(msg.budgetAll));
            }
        }
    });
});

function initPerson() {
    $("#wapdiv").yxeditgrid({
        url: '?model=outsourcing_outsourcing_person&action=listJson',
        type: 'view',
        tableClass: 'form_in_table',
        width: 1080,
        param: {"applyId": $("#aid").val()},
        objName: 'person[items]',
        title: '租借人员',
        colModel: [{
            display: '设备厂家/制式',
            name: 'content',
            tclass: 'txtshort'
        }, {
            display: '级别/职位编码',
            name: 'riskCode',
            tclass: 'txtshort'
        }, {
            display: '数量',
            name: 'peopleCount',
            tclass: 'txtshort person_sl'
        }, {
            display: '使用起始时间',
            name: 'startTime',
            tclass: 'txtshort',
            process: function (v, row) {
                return row.startTime.substring(0, 10);
            }
        }, {
            display: '使用结束时间',
            name: 'endTime',
            tclass: 'txtshort',
            process: function (v, row) {
                return row.endTime.substring(0, 10);
            }
        }, {
            display: '使用周期',
            name: 'totalDay',
            tclass: 'txtshort'
        }, {
            display: '外包最高限价<br/>(元/月/人）',
            name: 'outBudgetPrice',
            process: function (v, row) {
                return moneyFormat2(v);
            }
        }, {
            display: '价格描述',
            name: 'priceContent',
            align: 'left',
            tclass: 'txt'
        }, {
            display: '人员技能及工作内容描述',
            name: 'skill',
            align: 'left',
            tclass: 'txt'
        }]
    });
}