$(function() {
    countAll();

    $("#salesman").yxselect_user({
        hiddenId: 'salesmanId'
    });
    $("#deptName").yxselect_dept({
        hiddenId: 'deptId'
    });

    $("#feeDeptName").yxselect_dept({
        hiddenId: 'feeDeptId',
        unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
    });

    //�Ƿ�ί�и���
    var isEntrust = $("#isEntrust").val();
    if (isEntrust == 1) {
        $("#isEntrustYes").attr('checked', true);
        $("#bank").val('�Ѹ���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
        $("#account").val('�Ѹ���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
    } else {
        $("#isEntrustNo").attr('checked', true);
    }

    //�Ƿ񿪾ݷ�Ʊ
    if ($("#isInvoice").val() == 1) {
        $("#isInvoiceYes").attr('checked', true);
    }
    else {
        $("#isInvoiceNo").attr('checked', true);
    }
    //�Ƿ����ึ��
    if ($("#isSalary").val() == 1) {
        $("#isSalaryYes").attr('checked', true);
    } else {
        $("#isSalaryNo").attr('checked', true);
    }

    //������Ϣ
    if ($("#payFor").val() != 'FKLX-03' && $("#sourceType").val() == 'YFRK-02')
        initPayDetailGrid();
});

//�����ܽ��
function countAll() {
    var invnumber = $('#coutNumb').val();
    var allAmount = 0;
    for (var i = 1; i <= invnumber; i++) {
        var thisMoney = $("#money" + i).val() * 1;
        if (thisMoney != 0 || thisMoney != "") {
            allAmount = accAdd(allAmount, thisMoney, 2);
        }
    }

    $("#payMoney").val(allAmount);
    $("#payMoney_v").val(moneyFormat2(allAmount));
}