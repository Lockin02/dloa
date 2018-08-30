//��ʼ���������
$(function() {
    var chinseMoneyObj = $("#chinseMoney");
    if (chinseMoneyObj.length != 0) {
        chinseMoneyObj.html(toChinseMoney($("#payMoney").val() * 1));
    }

    var chinseMoneyPayedObj = $("#chinseMoneyPayed");
    if (chinseMoneyPayedObj.length != 0) {
        chinseMoneyPayedObj.html(toChinseMoney($("#payedMoney").val() * 1));
    }

    //��ǰ����ֵ��ʼ��
    var isAdvPayObj = $("#isAdvPay");
    if (isAdvPayObj.length != 0) {
        if (isAdvPayObj.val() == 1) {
            $(".isAdvPayShow").show();
            $("#payDate").attr('disabled', false);
        }
    }

    //��ʼ�����㷽ʽ
    changePayTypeFun();

    //��ʼ���ر���Ϣ
    var statusObj = $("#status");
    if (statusObj.length != 0) {
        if (statusObj.val() == 'FKSQD-04') {
            $(".closeInfo").show();
        }
    }

    //���г�ʼ��
    var bankObj = $("#bank");
    if (bankObj.val() == "") {
        bankObj.attr('readonly', false);
        bankObj.attr('class', 'txt');
    }

    //�����˺ų�ʼ��
    var accountObj = $("#account");
    if (accountObj.val() == "") {
        accountObj.attr('readonly', false);
        accountObj.attr('class', 'txt');
    }

    //���ֳ�ʼ��
//    var currencyCodeObj = $("#currencyCode");
//    if (currencyCodeObj.length > 0) {
//        // ���ұ�
//        $("#currency").yxcombogrid_currency({
//            hiddenId: 'currencyCode',
//            valueCol: 'currencyCode',
//            isFocusoutCheck: false,
//            gridOptions: {
//                showcheckbox: false,
//                event: {
//                    'row_dblclick': function(e, row, data) {
//                        $("#rate").val(data.rate);
//                    }
//                }
//            }
//        });
//    }
    $("#businessImg").click();
});

//���㷽ʽ
function changePayTypeFun() {
    var innerPayType = $("#payType").find("option:selected").attr("e1");
    if (innerPayType == 1) {
        $("#bankNeed").show();
        $("#accountNeed").show();
    } else {
        $("#bankNeed").hide();
        $("#accountNeed").hide();
    }
}


//ɾ���в�������
function mydel(obj, mytable) {
    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowNo = obj.parentNode.parentNode.rowIndex * 1;
        var mytable = document.getElementById(mytable);
        mytable.deleteRow(rowNo - 2);
        //���¶��кŸ�ֵ
        $.each($("tbody#invbody tr td:nth-child(2)"), function(i, n) {
            $(this).html(i + 1);
        });
    }
    countAll();
}

/**
 * ��Ʊ��ʷ
 * @param thisVal
 * 1.��Ʊ��ʷ
 */
function clickFun() {
    var url = '?model=supplierManage_formal_flibrary&action=supplierInfo'
            + '&id=' + $("#supplierId").val()
            + '&skey=' + $("#supplierSkey").val()
        ;
    showOpenWin(url);
}

//Դ�����
function openContract(contractId, contractType) {
    switch (contractType) {
        case '�����ͬ':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=contract_outsourcing_outsourcing&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + contractId + "&skey=" + skey, 1);
            break;

        case '������ͬ':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=contract_other_other&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=contract_other_other&action=viewTab&fundType=KXXZB&id=" + contractId + "&skey=" + skey, 1);
            break;

        case '�ɹ�����':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=purchase_contract_purchasecontract&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=purchase_contract_purchasecontract&action=toTabRead&id=" + contractId + "&skey=" + skey, 1);
            break;

        case '�⳵��ͬ':
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=outsourcing_contract_rentcar&action=md5RowAjax",
                data: {"id": contractId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=outsourcing_contract_rentcar&action=viewTab&id=" + contractId + "&skey=" + skey, 1);
            break;
        default :
            alert('�������ݲ�֧�ֲ鿴����');
    }
}

//�򿪸���������� ��Ŀ
function openObject(projectId, projectType) {
    switch (projectType) {
        case '������Ŀ' :
            var skey = "";
            $.ajax({
                type: "POST",
                url: "?model=engineering_project_esmproject&action=md5RowAjax",
                data: {"id": projectId},
                async: false,
                success: function(data) {
                    skey = data;
                }
            });
            showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId + "&skey=" + skey, 1);
            break;
        default :
            alert('�������ݲ�֧�ֲ鿴����');
    }
}


//���� - �ύ����
function audit(thisType) {
    if (thisType == 'audit') {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=add&act=audit";
    } else {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=add";
    }
}

//�༭ҳ - �ύ����
function auditEdit(thisType) {
    if (thisType == 'audit') {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=edit&act=audit";
    } else {
        document.getElementById('form1').action = "?model=finance_payablesapply_payablesapply&action=edit";
    }
}


//���� - ����
function showAndHide(btnId, tblId) {
    //���������
    var tblObj = $("table[id^='" + tblId + "']");
    //������ǰ������״̬������ʾ
    if (tblObj.is(":hidden")) {
        tblObj.show();
        $("#" + btnId).attr("src", "images/icon/info_up.gif");
    } else {
        tblObj.hide();
        $("#" + btnId).attr("src", "images/icon/info_right.gif");
    }
}

//ѡ�����д��ۺ󴥷��¼�
function entrustFun(thisVal) {
    if (thisVal == '1') {
        if (confirm('ѡ���Ѹ���󣬲����ɳ��ɽ��п���֧����ȷ��ѡ����')) {
            $("#bank").val('�Ѹ���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
            $("#account").val('�Ѹ���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
        } else {
            $("#isEntrustNo").attr('checked', true);
            $("#bank").val('').attr('class', 'txt').attr('readonly', false);
            $("#account").val('').attr('class', 'txt').attr('readonly', false);
        }
    } else {
        $("#bank").val('').attr('class', 'txt').attr('readonly', false);
        $("#account").val('').attr('class', 'txt').attr('readonly', false);
    }
}

// �޸�������������
function updateAuditDate() {
    if (confirm("ȷ��Ҫ�޸���������������?")) {
        $.ajax({
            type: "POST",
            url: "?model=finance_payablesapply_payablesapply&action=updateAuditDate",
            data: {
                id: $("#payablesapplyId").val(),
                auditDate: $("#auditDate").val()
            },
            success: function(msg) {
                if (msg) {
                    $("#tempAuditDate").val($("#auditDate").val());
                    alert('�޸ĳɹ���');
                } else {
                    alert("�޸�ʧ�ܣ�")
                }
            }
        });
    } else {
        $("#auditDate").val($("#tempAuditDate").val());
    }
}