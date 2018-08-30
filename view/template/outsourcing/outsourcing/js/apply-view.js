$(document).ready(function () {
    if ($("#actType").val() == 'audit') {
        $("#buttonTable").hide();
    }
    // ��ʼ���������
    var outType = $(".otypename:checked").val();
    changeType(outType);

    // ��ʼ����Ŀ��һЩ��Ϣ
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
        title: '�����Ա',
        colModel: [{
            display: '�豸����/��ʽ',
            name: 'content',
            tclass: 'txtshort'
        }, {
            display: '����/ְλ����',
            name: 'riskCode',
            tclass: 'txtshort'
        }, {
            display: '����',
            name: 'peopleCount',
            tclass: 'txtshort person_sl'
        }, {
            display: 'ʹ����ʼʱ��',
            name: 'startTime',
            tclass: 'txtshort',
            process: function (v, row) {
                return row.startTime.substring(0, 10);
            }
        }, {
            display: 'ʹ�ý���ʱ��',
            name: 'endTime',
            tclass: 'txtshort',
            process: function (v, row) {
                return row.endTime.substring(0, 10);
            }
        }, {
            display: 'ʹ������',
            name: 'totalDay',
            tclass: 'txtshort'
        }, {
            display: '�������޼�<br/>(Ԫ/��/�ˣ�',
            name: 'outBudgetPrice',
            process: function (v, row) {
                return moneyFormat2(v);
            }
        }, {
            display: '�۸�����',
            name: 'priceContent',
            align: 'left',
            tclass: 'txt'
        }, {
            display: '��Ա���ܼ�������������',
            name: 'skill',
            align: 'left',
            tclass: 'txt'
        }]
    });
}