//2012-12-27����
$(function () {

    // if( $("#paperContract").val()=='��'){
    //     $("#paperContractView").html("*�˺�ͬ��ֽ�ʺ�ͬ");
    // }else{
    //     $("#paperContractView").hide("");
    // }
    //
    // if( $("#checkFile").val()=='��'){
    //     $("#checkFileView").show();
    // }else{
    //     $("#checkFileView").hide();
    // }

    //��ϸ���ϳɱ�Ȩ�޿���
    var equCoseLimit = $("#equCoseLimit").val();
    if (equCoseLimit == '1') {
        $("#equCost").show();
    } else {
        $("#equCost").hide();
    }

    //�ж�Ԥ��ë����ɫ
    var exgross = $("#exgrossDiv").html();
    var exgrossVal = $("#exgrossVal").val();
    if (exgross < exgrossVal) {
        $("#exgrossDiv").attr('style', "color:red");
    } else {
        $("#exgrossDiv").attr('style', "color:black");
    }
    // ��ͬ���
    var winRate = $("#winRate").html();
    if (winRate == '100%') {
        $("#contractMoney").html("ǩԼ���");
        $("#contractMoneyA").html("ǩԼ���");
        document.getElementById("signDateNone").style.display = "";
    } else {
        $("#contractMoney").html("Ԥ�ƽ��");
        $("#contractMoneyA").html("Ԥ�ƽ��");
        document.getElementById("signDateNone").style.display = "none";
    }
    //��Ʊ����
    var invoiceLimitR = $("#invoiceLimitR").val();
    if (invoiceLimitR == '1') {

    } else {
        $("#invoiceLimit").html("******");
    }

    //�¿�Ʊ���Ϳ���
    var dataCode = $("#dataCode").val();
    if (dataCode != '' && typeof(dataCode) != 'undefined') {
        var itemArr = dataCode.split(',');
        var itemLength = itemArr.length;
        for (i = 0; i < itemLength; i++) {
            if ($("#" + itemArr[i] + "V").val() == 1) {
                $("#" + itemArr[i]).before("��");
                $("#" + itemArr[i] + "Hide").show();
            } else {
                $("#" + itemArr[i]).before("��");
                $("#" + itemArr[i]).css("color", "#969696");
            }
        }
    }

    //��ͬ��������
    var advance = $("#advance").html();
    if (advance != "") {
        $("#advance").html("<span style='color:#0080FF'>Ԥ����</span> : "
            + advance + "%&nbsp&nbsp|&nbsp");
    }
    var delivery = $("#delivery").html();
    if (delivery != "") {
        $("#delivery").html("<span style='color:#0080FF'>��������</span> : "
            + delivery + "%&nbsp&nbsp|&nbsp");
    }
    var initialpayment = $("#initialpayment").html();
    if (initialpayment != "") {
        $("#initialpayment")
            .html("<span style='color:#0080FF'>����ͨ������</span> : "
                + initialpayment + "%&nbsp&nbsp|&nbsp");
    }
    var finalpayment = $("#finalpayment").html();
    if (finalpayment != "") {
        $("#finalpayment").html("<span style='color:#0080FF'>����ͨ������</span> : "
            + finalpayment + "%&nbsp&nbsp|&nbsp");
    }
    //�����ȸ���
    var progresspayment = $("#progresspayment").html();
    if (progresspayment != "") {
        $("#progresspayment")
            .html("<span style='color:#0080FF'>�����ȸ���</span> :");
        var progressArr = progresspayment.split(',');
        $.each(progressArr, function (i, n) {
            var str = '<tr>' + '<td id="progresspaymentterm' + i + '"></td>'
                + '<td>' + n + '%</td>' + '</tr>';
            $("#progresspayment").append(str);
        });
        var progresspaymentterm = $("#progresspaymentterm").val();
        var progresspaymenttermArr = progresspaymentterm.split(',');
        $.each(progresspaymenttermArr, function (i, n) {
            $("#progresspaymentterm" + i).html(n);
        });
    }
    //��������
    var otherpayment = $("#otherpayment").html();
    if (otherpayment != "") {
        $("#otherpayment").html("<span style='color:#0080FF'>������������</span> :");
        var otherpaymentArr = otherpayment.split(',');
        $.each(otherpaymentArr, function (i, n) {
            var str = '<tr>' + '<td id="otherpaymentterm' + i + '"></td>'
                + '<td>' + n + '%</td>' + '</tr>';
            $("#otherpayment").append(str);
        });
        var otherpaymentterm = $("#otherpaymentterm").val();
        var otherpaymenttermArr = otherpaymentterm.split(',');
        $.each(otherpaymenttermArr, function (i, n) {
            $("#otherpaymentterm" + i).html(n);
        });
    }
    //��ǩ��Ϣ
    var isRenewed = strTrim($("#isRenewed").html());
    if(isRenewed == "��ǩ��ͬ"){
        $("#renewedCode").show();
        $("#renewedCodeV").show();
    }else{
        $("#renewedCode").hide();
        $("#renewedCodeV").hide();
    }
})

$(function () {
    proInfoList();

    // �տ�ƻ�
    $("#paymentListInfo").yxeditgrid({
        objName: 'contract[payment]',
        tableClass: 'form_in_table',
        url: '?model=contract_contract_receiptplan&action=listJson',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'isDel': 0,
            'isfinance' : 0
        },
        isAddOneRow: false,
        isAdd: false,
        colModel: [
            {
                display: '��������ID',
                name: 'paymenttermId',
                tclass: 'txt',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'paymentterm'

            },
            {
                display: '����ٷֱȣ�%��',
                name: 'paymentPer',
                tclass: 'txtshort'

            },
            {
                display: '�ƻ�������',
                name: 'money',
                tclass: 'txtshort',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '�ƻ���������',
                name: 'payDT',
                type: 'date'
            },
            {
                display: '��ע',
                name: 'remark',
                tclass: 'txtlong'
            }
        ]
    });
    $("#linkmanListInfo").yxeditgrid({
        objName: 'contract[linkman]',
        url: '?model=contract_contract_linkman&action=listJsonLimit',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'prinvipalId': $("#prinvipalId").val(),
            'createId': $("#createId").val(),
            'areaPrincipalId': $("#areaPrincipalId").val(),
            'isTemp': '0',
            'isDel': '0'
        },
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '�ͻ���ϵ��',
                name: 'linkmanName',
                tclass: 'txt'
            },
            {
                display: '��ϵ��ID',
                name: 'linkmanId',
                type: 'hidden'
            },
            {
                display: '�绰',
                name: 'telephone',
                tclass: 'txt'
            },
            {
                display: 'ְλ',
                name: 'position',
                tclass: 'txt'
            },
            {
                display: '����',
                name: 'Email',
                tclass: 'txt'
            },
            {
                display: '��ע',
                name: 'remark',
                tclass: 'txt',
                width: 500
            }
        ]
    });


    if ($("#isSubAppChange").val() == '1' || $("#isTemp").val() == '1') {//��ʱ��ͬ�鿴ҳ�棬Ҫ��ʾ��ʱ����
        var param = {
            'contractId': $("#contractId").val(),
            'isDel': '0',
            'isBorrowToorder': '0'
        };
    } else {
        var param = {
            'contractId': $("#contractId").val(),
            'isTemp': '0',
            'isDel': '0',
            'isBorrowToorder': '0'
        };
    }
    //�����嵥
    $("#equInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJsonGroup',
        type: 'view',
        tableClass: 'form_in_table',
        param: param,
        colModel: [
            {
                display: '������Ʒ',
                name: 'conProductName',
                tclass: 'txt'
            },
            {
                display: '���ϱ��',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '��������',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '����Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '��ִ������',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '���˿�����',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: 'ʵ��ִ������',
                name: 'actNum'
            },
            {
                display: '����',
                name: 'price',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v, 6);
                }
            },
            {
                display: '���',
                name: 'money',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '��������Id',
                name: 'license',
                type: 'hidden'
            },
            {
                name: 'licenseButton',
                display: '��������',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\"" + row.license
                            + "\")'>�鿴</a>";
                    }
                }
            }
        ]
    });
    //�տ��ƻ�
    $("#financialplanInfo").yxeditgrid({
        objName: 'contract[financialplan]',
        url: '?model=contract_contract_financialplan&action=listJsonLimit',
        type: 'view',
        tableClass: 'form_in_table',
        param: {
            'contractId': $("#contractId").val()
        },
        colModel: [
            {
                display: '����',
                name: 'planDate',
                type: 'date'
            },
            {
                display: '��Ʊ���',
                name: 'invoiceMoney',
                tclass: 'txtshort',
                type: 'money'
            },
            {
                display: '�տ���',
                name: 'incomeMoney',
                tclass: 'txtshort',
                type: 'money'
            },
            {
                display: '��ע',
                name: 'remark',
                tclass: 'txtlong'
            }
        ]
    });
    //������ת����
    $("#borrowConEquInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJson',
        type: 'view',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': '0',
            'isDel': '0',
            'isBorrowToorder': '1'
        },
        isAddOneRow: false,
        isAdd: false,
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '������Ʒ',
                name: 'conProduct',
                tclass: 'txt'
            },
            {
                display: '���ϱ��',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '��������',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '����Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '��ִ������',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '���˿�����',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: 'ʵ��ִ������',
                name: 'actNum'
            },
            {
                display: '����',
                name: 'price',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '���',
                name: 'money',
                type: 'hidden',
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '��������Id',
                name: 'license',
                type: 'hidden'
            },
            {
                name: 'licenseButton',
                display: '��������',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\"" + row.license
                            + "\")'>�鿴</a>";
                    }
                }
            }
        ]

    });

});
//������
function hideList(listId) {
    var temp = document.getElementById(listId);
    var tempH = document.getElementById(listId + "H");
    if (temp.style.display == '') {
        temp.style.display = "none";
        if (tempH != null) {
            tempH.style.display = "";
        }
    } else if (temp.style.display == "none") {
        temp.style.display = '';
        if (tempH != null) {
            tempH.style.display = 'none';
        }
    }
}
$(function () {
    var currency = $("#currency").html();
    if (currency != '�����' && currency != '') {
        document.getElementById("currencyRate").style.display = "";
    }
});
// ��ϸ���ϳɱ�
function equCoseView() {
    showThickboxWin('?model=contract_contract_contract&action=equCoseView&contractId='
        + $("#pid").val()
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
}


//��Ʒ�嵥
function proInfoList() {
    var currency = $("#currency").html();
    if (currency != '�����') {
        var rate = $("#rate").html();
        //��Ʒ�嵥
        $("#productInfo").yxeditgrid({
            objName: 'contract[product]',
            url: '?model=contract_contract_product&action=listJsonLimit',
            type: 'view',
            tableClass: 'form_in_table',
            param: {
                'contractId': $("#contractId").val(),
                'dir': 'ASC',
                'prinvipalId': $("#prinvipalId").val(),
                'createId': $("#createId").val(),
                'areaPrincipalId': $("#areaPrincipalId").val(),
                //			'isTemp' : '0',
                'isDel': '0'
            },
            colModel: [
                {
                    name: 'newProLineName',
                    display: '��Ʒ��',
                    width: 100
                },
                {
                    name: 'exeDeptName',
                    display: 'ִ������',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '��Ʒ����',
                    width: 80
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + $("#contractId").val()
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '��ƷId',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '����',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '����(' + currency + ')',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '����',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v * rate);
                    }
                },
                {
                    display: '���(' + currency + ')',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '���',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v * rate);
                    }
                },
                {
                    display: '��������Id',
                    name: 'license',
                    type: 'hidden'
                },
                //{
                //    name: 'licenseButton',
                //    display: '��������',
                //    process: function (v, row) {
                //        if (row.license != "") {
                //            return "<a href='javascript:void(0)' onclick='showLicense(\""
                //                + row.license + "\")'>��������</a>";
                //        }
                //    }
                //},
                {
                    display: '��Ʒ����Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    name: 'deployButton',
                    display: '��Ʒ����',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>��Ʒ����</a>";
                        }
                    }
                }
            ],
            event: {
                'reloadData': function (e) {
                    initCacheInfo();
                }
            }
        });
    } else {
        //��Ʒ�嵥
        $("#productInfo").yxeditgrid({
            objName: 'contract[product]',
            url: '?model=contract_contract_product&action=listJsonLimit',
            type: 'view',
            tableClass: 'form_in_table',
            param: {
                'contractId': $("#contractId").val(),
                'dir': 'ASC',
                'prinvipalId': $("#prinvipalId").val(),
                'createId': $("#createId").val(),
                'areaPrincipalId': $("#areaPrincipalId").val(),
                //			'isTemp' : '0',
                'isDel': '0'
            },
            colModel: [
                {
                    name: 'newProLineName',
                    display: '��Ʒ��',
                    width: 100
                },
                {
                    name: 'exeDeptName',
                    display: 'ִ������',
                    width: 100
                },
                {
                    name: 'proType',
                    display: '��Ʒ����',
                    width: 80
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductName',
                    tclass: 'txt',
                    process: function (v, row) {
                        return '<a title=����鿴�����嵥 href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=toViewEqu&id='
                            + row.id
                            + '&contractId='
                            + $("#contractId").val()
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
                    }
                },
                {
                    display: '��ƷId',
                    name: 'conProductId',
                    tclass: 'txt',
                    type: 'hidden'
                },
                {
                    display: '��Ʒ����',
                    name: 'conProductDes',
                    tclass: 'txt'
                },
                {
                    display: '����',
                    name: 'number',
                    tclass: 'txtshort'
                },
                {
                    display: '����',
                    name: 'price',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    display: '���',
                    name: 'money',
                    tclass: 'txtshort',
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                    //		}, {
                    //			display : '������',
                    //			name : 'warrantyPeriod',
                    //			tclass : 'txtshort'
                },
                {
                    display: '��������Id',
                    name: 'license',
                    type: 'hidden'
                },
                {
                    display: '��Ʒ����Id',
                    name: 'deploy',
                    type: 'hidden'
                },
                {
                    name: 'deployButton',
                    display: '��Ʒ����',
                    process: function (v, row) {
                        if (row.deploy != "") {
                            return "<a href='javascript:void(0)' onclick='showGoods(\""
                                + row.deploy
                                + "\",\""
                                + row.conProductName
                                + "\")'>��Ʒ����</a>";
                        }
                    }
                }
            ],
            event: {
                'reloadData': function (e) {
                    initCacheInfo();
                }
            }
        });
    }
}


function parentView(pid) {
    showThickboxWin('?model=contract_contract_contract&action=parentView&contractId='
        + pid
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900');
}