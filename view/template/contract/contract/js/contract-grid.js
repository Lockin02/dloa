var show_page = function (page) {
    $("#contractGrid").yxgrid("reload");
};
$(function () {
    var buttonsArr = [
        {
            text: "����",
            icon: 'delete',
            action: function (row) {
                var listGrid = $("#contractGrid").data('yxgrid');
                listGrid.options.extParam = {};
                $("#caseListWrap tr").attr('style',
                    "background-color: rgb(255, 255, 255)");
                listGrid.reload();
            }
        }, {
            text: "���ݸ���",
            icon: 'add',
            action: function (row) {
                showThickboxWin("?model=contract_contract_contract&action=toUpdateConRedundancy"
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600")
            }
        }
    ];

    //add chenrf 20130417 ����
    /*****************��������Ȩ�޿���****************************/
    var attachment = {
        text: "��������",
        icon: 'add',
        action: function () {
            var listGrid = $("#contractGrid").data('yxgrid');
            var ids = listGrid.getAllCheckedRowIds();
            if (ids.toString().substring(ids.toString().length - 1) == ",") {
                ids = ids.toString().substring(0, ids.toString().length - 1);
            }
            showThickboxWin('?model=contract_contract_contract&action=toDownAllFile&ids='
                + ids
                + "&type=oa_contract_contract"
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
        }
    };
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '��������'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(attachment);
            }
        }
    });
    /*****************��������Ȩ�޿��� end****************************/

    var HTDC = {
        name: 'export',
        text: "����",
        icon: 'excel',
        items: [{
            name: 'export',
            text: "��ͬ����",
            icon: 'excel',
            action: function (row) {
                // var getAdvSearchArr =
                // $("#contractGrid").yxgrid("getAdvSearchArr")
                // alert(getAdvSearchArr)
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#contractGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#contractGrid").data('yxgrid').options.searchParam[t];
                    }
                }
                var contractType = $('#contractType').val();
                var ExaStatusArr = $('#ExaStatusArr').val();
                var businessBelong = $('#businessBelong').val();
                var isTemp = $("#contractGrid").data('yxgrid').options.param.isTemp;
                var states = $("#contractGrid").data('yxgrid').options.param.states;
                if (states == undefined) {
                    states = '';
                }
                var i = 1;
                var colId = "";
                var colName = "";
                $("#contractGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    });
                var searchSql = $("#contractGrid").data('yxgrid').getAdvSql();
                var searchArr = [];
                searchArr[0] = searchSql;
                searchArr[1] = searchConditionKey;
                searchArr[2] = searchConditionVal;
                var msg = $.ajax({
                    url: '?model=contract_contract_contract&action=setColInfoToSession',
                    data: 'ColId=' + colId + '&ColName=' + colName,
                    dataType: 'html',
                    type: 'post',
                    async: false
                }).responseText;

                if (msg == 1) {
                    window
                        .open("?model=contract_contract_contract&action=exportExcel&"
                        + "&isTemp="
                        + isTemp
                        + "&states="
                        + states
                        + "&contractType="
                        + contractType
                        + "&ExaStatusArr="
                        + ExaStatusArr
                        + "&businessBelong="
                        + businessBelong
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal)
                }
            }
        }, {
            name: 'export',
            text: "��ͬ����(CSV)",
            icon: 'excel',
            action: function (row) {
                // var getAdvSearchArr =
                // $("#contractGrid").yxgrid("getAdvSearchArr")
                // alert(getAdvSearchArr)
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#contractGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#contractGrid").data('yxgrid').options.searchParam[t];
                    }
                }
                var contractType = $('#contractType').val();
                var ExaStatusArr = $('#ExaStatusArr').val();
                var businessBelong = $('#businessBelong').val();
                var isTemp = $("#contractGrid").data('yxgrid').options.param.isTemp;
                var states = $("#contractGrid").data('yxgrid').options.param.states;
                if (states == undefined) {
                    states = '';
                }
                var i = 1;
                var colId = "";
                var colName = "";
                $("#contractGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    });
                var searchSql = $("#contractGrid").data('yxgrid').getAdvSql();
                var searchArr = [];
                searchArr[0] = searchSql;
                searchArr[1] = searchConditionKey;
                searchArr[2] = searchConditionVal;

                var msg = $.ajax({
                    url: '?model=contract_contract_contract&action=setColInfoToSession',
                    data: 'ColId=' + colId + '&ColName=' + colName,
                    dataType: 'html',
                    type: 'post',
                    async: false
                }).responseText;

                if (msg == 1) {
                    window
                        .open("?model=contract_contract_contract&action=exportExcel"
                        + "&isTemp="
                        + isTemp
                        + "&states="
                        + states
                        + "&contractType="
                        + contractType
                        + "&ExaStatusArr="
                        + ExaStatusArr
                        + "&businessBelong="
                        + businessBelong
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal="
                        + searchConditionVal
                        + "&CSV")
                }
            }
        }]
    }, mergeArr = {
        text: "�������",
        icon: 'excel',
        action: function (row) {
            showThickboxWin("?model=contract_contract_contract&action=FinancialImportexcel"
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
        }
    };
    var importExcel = {
        text: "��ͬ����",
        icon: 'add',
        action: function (row) {
            showThickboxWin("?model=contract_contract_contract&action=toExcel"
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
        }
    };
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '��ͬ����Ȩ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(importExcel);
            }
        }
    });
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '�������'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(mergeArr);
            }
        }
    });
    $.ajax({
        type: 'POST',
        url: '?model=contract_contract_contract&action=getLimits',
        data: {
            'limitName': '��ͬ��Ϣ�б���'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(HTDC);
            }
        }
    });
    var param = {
        'states': '0,1,2,3,4,5,6,7',
        'isTemp': '0'
    };
    if ($("#lastAdd").val()) {
        param.lastAdd = $("#lastAdd").val();
    }
    if ($("#lastChange").val()) {
        param.lastChange = $("#lastChange").val();
    }

    //����Ȩ��
    var assLimit = $("#assLimit").val();
    var autoloadVal = $("#autoload").val();
    if (autoloadVal == "") {
        autoloadVal = false;
    }
    $("#contractGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'conPageJson',
        param: param,
        leftLayout: true,
        title: '��ͬ��Ϣ',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        autoload: autoloadVal,
        customCode: 'contractInfoNew',//2014-10-23������ͬ��Ϣ�б�Ĭ����ʾ�ֶΣ������Զ����ͷ contractInfo
        // ��չ�Ҽ��˵�
        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                showMenuFn: function (row) {
                    return row ? true : false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                }
            },
            {
                text: '����鿴',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row && row.becomeNum != '0' && row.becomeNum != '') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=showViewTab&id='
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                }
            },
            {
                text: '��Ʊ����',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=toAdd&invoiceapply[objId]='
                        + row.id
                        + '&invoiceapply[objCode]='
                        + row.contractCode
                        + '&invoiceapply[objType]=KPRK-12');
                }
            },
            {
                text: '¼�벻��Ʊ���',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_uninvoice_uninvoice&action=toAdd&objId='
                        + row.id
                        + '&objCode='
                        + row.contractCode
                        + '&objType=KPRK-12'
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
                }
            },
            {
                text: '�ۿ�����',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_deduct_deduct&action=toAdd&contractId='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');
                }
            },
            {
                name: 'stamp',
                text: '�������',
                icon: 'add',
                showMenuFn: function (row) {
                    if (assLimit != "1") {
                        return false;
                    }
                },
                action: function (row, rows, grid) {
                    if (row) {
                        if (row.isNeedStamp == '1') {
                            alert('�˺�ͬ���������,�����ظ�����');
                            return false;
                        }
                        //alert(row.contractType);
                        //add chenrf 20130524
                        //����Ƿ��Ѵ��ڸ��²���δ������
                        var msg = $.ajax({
                            url: '?model=contract_stamp_stamp&action=checkStamp',
                            data: 'contractId=' + row.id + '&contractType=HTGZYD-04',
                            dataType: 'html',
                            type: 'post',
                            async: false
                        }).responseText;
                        if (msg == 1) {
                            alert('�˺�ͬ���������,�����ظ�����');
                            return false;
                        }
                        showThickboxWin("?model=contract_contract_contract&action=toStamp&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                    } else {
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                text: '�����ͬ',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row && row.financial == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=toChange&noApp=1&id='
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400');
                }
            },
            {
                text: '�޸�',
                icon: 'edit',
                showMenuFn: function (row) {
                    var canEdit = $.ajax({
                        url: '?model=contract_contract_contract&action=chkHwEditLimit',
                        type: 'get',
                        async: false
                    }).responseText;
                    if (row && (row.ExaStatus == 'δ����' || row.ExaStatus == '���' || row.parentName != '')
                        && row.isSubApp == '0' && canEdit == '1') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin('?model=contract_contract_contract&action=init&id='
                        + row.id
                        + '&perm=hwedit'
                        + "&skey="
                        + row['skey_']
                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                }
            },
            {
                text: '�����ϴ�',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_contract_contract&action=toUploadFile&id='
                        + row.id
                        + '&type=oa_contract_contract'
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
                }
            },
            {
                text: '��������',
                icon: 'add', // downloadLimit
                showMenuFn: function (row) {
                    if (row && row.downloadLimit == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_contract_contract&action=toDownFile&id='
                        + row.id
                        + '&type=oa_contract_contract'
                        + '&contractName='
                        + row.contractName
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
                }
            },
            {
                text: '�����������',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && row.financialDate == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=contract_contract_contract&action=financialRelatedDate&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
                }
            },{
                text : '�鵵��Ϣ�޸�',
                icon : 'add',
                showMenuFn : function(row) {
                    if($("#archivedInfoModifyLimit").val() == '1'){
                        return true;
                    }else{
                        return false;
                    }
                },
                action : function(row) {
                    showThickboxWin("?model=contract_contract_contract&action=toUpdateArchivedInfo&id="
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=380&width=650");
                }
            }
        ],
        lockCol: ['conflag', 'exeStatus'],// ����������
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'conflag',
                display: '��ͨ��',
                sortable: true,
                width: 40,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "�ϼ�";
                    }
                    if (v == '') {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
                            + row.id
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
                            + "<img src='images/icon/icon139.gif' />"
                            + '</a>';
                    } else {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=listremark&id='
                            + row.id
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
                            + "<img src='images/icon/icon095.gif' />"
                            + '</a>';
                    }

                }
            },
            {
                name : 'exeStatus',
                display : '��ͬ״̬',
                sortable : true,
                width : 50,
                process : function(v, row) {
                    return "<p onclick='exeStatusView(" + row.id
                        + ");' style='cursor:pointer;color:blue;' >"
                        + v + "</p>";
                }
            },
            {
                name: 'contractTypeName',
                display: "��ͬ����",
                sortable: true,
                width: 60
            },
            {
                name: 'customerTypeName',
                display: '�ͻ�����',
                sortable: true,
                width: 70
            },
            {
                name: 'customerName',
                display: '�ͻ�����',
                sortable: true,
                width: 180,
                hide: true
            },
            {
                name: 'contractCode',
                display: '��ͬ���',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + v
                        + "</font>"
                        + '</a>';
                }
            },
            {
                name: 'objCode',
                display: 'ҵ����',
                hide: true,
                sortable: true,
                width: 120
            },
            {
                name: 'contractName',
                display: '��ͬ����',
                sortable: true,
                width: 150
            },
            {
                name: 'contractMoney',
                display: '��ͬ���',
                sortable: true,
                width: 80,
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'costEstimates',
                display: '�ɱ�����(����˰)',
                sortable: true,
                width: 80,
                process: function (v) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'exgross',
                display: '����ë����',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    }

                    if (isNaN(v) || !v) {
                        return v;
                    } else {
                        return v + "%";
                    }

                }
            },
            {
                name: 'budgetAll',
                display: '��Ԥ��',
                sortable: false,
                width: 80,
                hide: true,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'conProgress',
                display: '����',
                width: 80,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00%";
                    } else {
                        return v+ "%";
                    }
                }
            },
            {
                name: 'curIncome',
                display: '����',
                sortable: false,
                width: 80,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'feeAll',
                display: '�ɱ�',
                sortable: false,
                width: 80,
                process: function (v, row) {
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'gross',
                display: 'ë��',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v != '******') {
                        return moneyFormat2(v);
                    } else {
                        return v;
                    }
                }
            },
            {
                name: 'rateOfGross',
                display: 'ë����',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    }
                    if (isNaN(v) || !v) {
                        return v;
                    } else {
                        return v + "%";
                    }
                }
            },
            {
                name: 'serviceconfirmMoneyAll',
                display: '����ȷ��������',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v != '******') {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
                            + row.id
                            + '&tablename='
                            + row.contractType
                            + '&moneyType=serviceconfirmMoney'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                            + "<font color = '#4169E1'>"
                            + moneyFormat2(v) + "</font>" + '</a>';
                    } else {
                        return v;
                    }

                }
            },
            {
                name: 'financeconfirmMoneyAll',
                display: '����ȷ���ܳɱ�',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v != '******') {
                        return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
                            + row.id
                            + '&tablename='
                            + row.contractType
                            + '&moneyType=financeconfirmMoney'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                            + "<font color = '#4169E1'>"
                            + moneyFormat2(v) + "</font>" + '</a>';
                    } else {
                        return v;
                    }
                }
            },
            {
                name: 'KPLXSD',
                display: '��Ʊ����˰��',
                hide: true
            },
            {
                name: 'comPoint',
                display: '�ۺ�˰��',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'invoiceApplyMoney',
                display: '��Ʊ�����ܽ��',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'lastInvoiceDate',
                display: '���һ�ο�Ʊ����',
                hide: true,
                sortable: true
            },
            {
                name: 'invoiceMoney',
                display: '��Ʊ���',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'invoiceProgress',
                display: '��Ʊ����',
                width: 80,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00%";
                    } else {
                        return v * 100 + "%";
                    }
                },
                hide: true
            },
            {
                name: 'softMoney',
                display: '������',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'hardMoney',
                display: 'Ӳ�����',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'serviceMoney',
                display: '������',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'repairMoney',
                display: 'ά�޽��',
                width: 80,
                hide: true,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'equRentalMoney',
                display: '�豸���޽��',
                hide: true,
                sortable: true
            },
            {
                name: 'spaceRentalMoney',
                display: '�������޽��',
                hide: true,
                sortable: true
            },
            {
                name: 'dsEnergyCharge',
                display: '���յ���ܽ��',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'dsWaterRateMoney',
                display: '����ˮ���ܽ��',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'houseRentalFee',
                display: '���ݳ����ܽ��',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'installationCost',
                display: '��װ�����ܽ��',
                hide: true,
                sortable: true,
                process : function(v){
                    v = (v == '')? 0 : v;
                    return moneyFormat2(v);
                }
            },
            {
                name: 'deductMoney',
                display: '�ۿ���',
                sortable: true,
                width: 80,
                hide: true,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == '******') {
                        return "******";
                    } else {
                        if (v == '') {
                            return "0.00";
                        } else {
                            return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_contract_contract&action=financialDetailTab&id='
                                + row.id
                                + '&tablename='
                                + row.contractType
                                + '&moneyType=deductMoney'
                                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
                                + "<font color = '#4169E1'>"
                                + moneyFormat2(v) + "</font>" + '</a>';
                        }
                    }
                }
            },
            {
                name: 'uninvoiceMoney',
                display: '����Ʊ���',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == '******') {
                        return "******";
                    } else {
                        if (v == '') {
                            return "0.00";
                        } else {
                            return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=contract_uninvoice_uninvoice&action=toObjList&objId='
                                + row.id
                                + '&objType=KPRK-12'
                                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\')">'
                                + moneyFormat2(v) + '</a>';
                        }
                    }
                }
            },
            {
                name: 'surplusInvoiceMoney',
                display: 'ʣ�࿪Ʊ���',
                sortable: true,
                process: function (v, row) {
                    return "<span color = 'blue'>" + moneyFormat2(v) + "</span>";
                }
            },
            {
                name: 'incomeMoney',
                display: '���ս��',
                width: 60,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00";
                    } else {
                        return moneyFormat2(v);
                    }
                }
            },
            {
                name: 'surOrderMoney',
                display: 'ǩԼ��ͬӦ���˿����',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return "<span class='blue'>" + moneyFormat2(v) + "</span>";
                },
                hide: true
            },
            {
                name: 'incomeProgress',
                display: '�տ����',
                width: 80,
                sortable: true,
                process: function (v, row) {
                    if (v == '') {
                        return "0.00%";
                    } else {
                        return v * 100 + "%";
                    }
                },
                hide: true
            },
            {
                name: 'badMoney',
                display: '����',
                sortable: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return moneyFormat2(v);
                    }
                    if (v == "") {
                        return "0.00";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'surincomeMoney',
                display: '����Ӧ���˿����',
                sortable: true,
                process: function (v, row) {
                    return "<span color = 'blue'>" + moneyFormat2(v) + "</span>";
                }
            },
            {
                name: 'contractSigner',
                display: '��ͬǩ����',
                sortable: true,
                hide: true,
                width: 80
            },
            {
                name: 'prinvipalName',
                display: '��ͬ������',
                sortable: true,
                width: 80
            },
            {
                name: 'areaName',
                display: '��������',
                sortable: true,
                width: 60
            },
            {
                name: 'areaPrincipal',
                display: '��������',
                hide: true,
                sortable: true
            },
            {
                name: 'contractProvince',
                display: 'ʡ��',
                sortable: true,
                hide: true,
                width: 60
            },
            {
                name: 'contractCity',
                display: '����',
                hide: true,
                sortable: true,
                width: 60
            },
            {
                name: 'ExaDTOne',
                display: '����ʱ��',
                sortable: true,
                width: 80
            },
            {
                name: 'isRenewed',
                display: '�Ƿ���ǩ',
                hide: true,
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "��ǩ��ͬ";
                    } else if (v == '1') {
                        return "��ǩ��ͬ";
                    } else {
                        return "";
                    }
                },
                width: 60
            },
            {
                name: 'businessBelongName',
                display: 'ǩԼ��˾',
                sortable: true,
                width: 60,
                hide: true
            },
            {
                name: 'state',
                display: '��ͬ״̬',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "δ�ύ";
                    } else if (v == '1') {
                        return "������";
                    } else if (v == '2') {
                        return "ִ����";
                    } else if (v == '3') {
                        return "�ѹر�";
                    } else if (v == '4') {
                        return "�����";
                    } else if (v == '5') {
                        return "�Ѻϲ�";
                    } else if (v == '6') {
                        return "�Ѳ��";
                    } else if (v == '7') {
                        return "�쳣�ر�";
                    }
                },
                width: 60
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true,
                hide: true,
                width: 60
            },
            {
                name: 'signStatus',
                display: 'ǩ��״̬',
                sortable: true,
                width: 80,
                hide: true,
                process: function (v, row) {
                    if (v == '0') {
                        return "δǩ��";
                    } else if (v == '1') {
                        return "��ǩ��";
                    } else if (v == '2') {
                        return "���δǩ��";
                    }
                }
            },
            {
                name: 'signContractTypeName',
                display: "ǩ�պ�ͬ����",
                sortable: true,
                hide: true,
                width: 60
            },
            {
                name: 'isAcquiring',
                display: '�յ�״̬',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v) {
                    if (v == '0') {
                        return "δ�յ�";
                    } else if (v == '1') {
                        return "���յ�";
                    }
                }
            },
            {
                name: 'paperContract',
                display: 'ֽ�ʺ�ͬ',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'isNeedStamp',
                display: '�Ƿ���Ҫ����',
                sortable: true,
                hide: true,
                width: 80,
                process: function (v, row) {
                    if (row.id == "allMoney" || row.id == undefined) {
                        return "";
                    } else {
                        if (v == '0') {
                            return "��";
                        } else {
                            return "��";
                        }
                    }
                }
            },
            {
                name: 'checkFile',
                display: '�����ļ�',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'trialprojectCode',
                display: '������Ŀ���',
                hide: true,
                sortable: true
            },
            //{
            //    name: 'chanceCode',
            //    display: '�̻����',
            //    sortable: true,
            //    hide: true,
            //    width: 180,
            //    process: function (v, row) {
            //        if (row.chanceId != '')
            //            return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
            //                + row.chanceId
            //                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
            //                + "<span color='#4169E1'>"
            //                + v
            //                + "</span>"
            //                + '</a>';
            //    }
            //},
            {
                name: 'beginDate',
                display: '��ͬ��ʼ��ʼ��',
                width: 80,
                sortable: true
            },
            {
                name: 'endDate',
                display: '��ͬ��ʼ���գ�',
                width: 80,
                sortable: true
            },
            {
                name: 'signSubjectName',
                display: '��ͬǩԼ����',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanName',
                display: '�׷���ϵ��',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanTel',
                display: '�׷���ϵ��ʽ',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'partAContractCode',
                display: '�׷���ͬ���',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'esmManagerName',
                display: '��Ŀ����',
                width: 80,
                sortable: true,
                hide: true
            },
            {
                name: 'paperSignTime',
                display: 'ֽ�ʰ�ǩ��ʱ��',
                width: 80,
                sortable: true,
                process: function(v, row){
                    if(v == 'NULL'){
                        return '';
                    }else{
                        return v;
                    }
                }
            },
            // PMS 559 Ҫ��ȥ����Щ�ֶε���ʾ
            // {
            //     name: 'productCheck',
            //     display: '��Ʒ�����',
            //     width: 80,
            //     sortable: true,
            //     hide: true
            // },
            // {
            //     name: 'projectCheck',
            //     display: '��Ŀ�����',
            //     width: 80,
            //     sortable: true,
            //     hide: true
            // },
            // {
            //     name: 'invoiceCheck',
            //     display: 'Ԥ�ƿ�Ʊ���',
            //     width: 80,
            //     sortable: true,
            //     hide: true,
            //     process: function(v, row){
            //         if(row.isNoInvoiceCont == '1'){
            //             return '-';
            //         }else{
            //             return v;
            //         }
            //     }
            // },
            // {
            //     name: 'invoiceTrueCheck',
            //     display: 'ʵ�ʿ�Ʊ���',
            //     width: 80,
            //     sortable: true,
            //     hide: true,
            //     process: function(v, row){
            //         if(row.isNoInvoiceCont == '1'){
            //             return '-';
            //         }else{
            //             return v;
            //         }
            //     }
            // },
            {
                name: 'icomeMoney',
                display: 'Ӫ��Ӧ�տ�',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return "<span class='blue'>" + moneyFormat2(v) + "</span>";
                },
                hide: true
            },
            {
                name: 'contractNatureName',
                display: '��ͬ����',
                width: 80,
                sortable: true,
                hide: true
            }
        ],
        comboEx: [
            {
                text: '����',
                key: 'contractType',
                data: [
                    {
                        text: '���ۺ�ͬ',
                        value: 'HTLX-XSHT'
                    },
                    {
                        text: '�����ͬ',
                        value: 'HTLX-FWHT'
                    },
                    {
                        text: '���޺�ͬ',
                        value: 'HTLX-ZLHT'
                    },
                    {
                        text: '�з���ͬ',
                        value: 'HTLX-YFHT'
                    }
                ]
            },
            {
                text: '��ͬ״̬',
                key: 'states',
                value: '0,1,2,3,4,5,6',
                data: [
                    {
                        text: '����(�����쳣��ͬ)',
                        value: '0,1,2,3,4,5,6'
                    },
                    {
                        text: '������',
                        value: '1'
                    },
                    {
                        text: 'ִ����',
                        value: '2'
                    },
                    {
                        text: '�����',
                        value: '4'
                    },
                    {
                        text: '�ѹر�',
                        value: '3'
                    }
                    ,
                    {
                        text: '�쳣�ر�',
                        value: '7'
                    },
                    {
                        text: '��Ч��ͬ(ִ�У���ɣ��ر�)',
                        value: '2,3,4'
                    }
                ]
            },
            {
                text: '����״̬',
                key: 'ExaStatusArr',
                data: [
                    {
                        text: 'δ����',
                        value: 'δ����'
                    },
                    {
                        text: '��������',
                        value: '��������'
                    },
                    {
                        text: '���������',
                        value: '���������'
                    },
                    {
                        text: '���',
                        value: '���'
                    },
                    {
                        text: '���',
                        value: '���'
                    },
                    {
                        text: '��ɺͱ��������',
                        value: '���,���������'
                    }
                ]
            },
            {
                text: 'ǩԼ����',
                key: 'businessBelong',
                datacode: 'QYZT'
            }
        ],
        /**
         * ��������
         */
        searchitems: [
            {
                display: '��ͬ���',
                name: 'contractCode'
            },
            {
                display: '��ͬ����',
                name: 'contractName'
            },
            {
                display: '�ͻ�����',
                name: 'customerName'
            },
            {
                display: 'ҵ����',
                name: 'objCode'
            },
            {
                display: '��Ʒ����',
                name: 'conProductName'
            },
            {
                display: '������Ŀ',
                name: 'trialprojectCode'
            }
        ],
        sortname: "createTime",
        buttonsEx: buttonsArr,

        // �߼�����
        advSearchOptions: {
            modelName: 'contractInfo',
            // ѡ���ֶκ��������ֵ����
            selectFn: function ($valInput) {
                $valInput.yxcombogrid_area("remove");
                $valInput.yxselect_user("remove");
            },
            searchConfig: [
                {
                    name: '��������',
                    value: 'c.ExaDTOne',
                    changeFn: function ($t, $valInput) {
                        $valInput.click(function () {
                            WdatePicker({
                                dateFmt: 'yyyy-MM-dd'
                            });
                        });
                    }
                },
                {
                    name: '��ݣ�ֱ���������֣���2013��',
                    value: 'date_format(c.ExaDTOne,"%Y")'
                },
                {
                    name: '�·ݣ�ֱ���������֣��� 04��11��',
                    value: 'date_format(c.ExaDTOne,"%m")'
                },
                {
                    name: '���ȣ�ֱ���������֣��� 1��2��3��4��',
                    value: 'quarter(c.ExaDTOne)'
                },
                {
                    name: '��ͬ����',
                    value: 'c.contractType',
                    type: 'select',
                    datacode: 'HTLX'
                },
                {
                    name: '���ۺ�ͬ����',
                    value: 'c.contractNature*XS',
                    type: 'select',
                    datacode: 'HTLX-XSHT'
                }
                ,
                {
                    name: '�����ͬ����',
                    value: 'c.contractNature*FW',
                    type: 'select',
                    datacode: 'HTLX-FWHT'
                },
                {
                    name: '���޺�ͬ����',
                    value: 'c.contractNature*ZL',
                    type: 'select',
                    datacode: 'HTLX-ZLHT'
                },
                {
                    name: '�з���ͬ����',
                    value: 'c.contractNature*YF',
                    type: 'select',
                    datacode: 'HTLX-YFHT'
                }
                ,
                {
                    name: '�ͻ�����',
                    value: 'c.customerType',
                    type: 'select',
                    datacode: 'KHLX'
                }
                ,
                {
                    name: 'ǩԼ��ͬӦ���˿����',
                    value: 'c.surOrderMoney'
                },
                {
                    name: '����Ӧ���˿����',
                    value: 'c.surincomeMoney'
                },
                {
                    name: '��������',
                    value: 'c.areaPrincipal',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaPrincipalId' + rowNum,
                            nameCol: 'areaPrincipal',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '��������',
                    value: 'c.areaName',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaCode' + rowNum,
                            nameCol: 'areaName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '��ͬ������',
                    value: 'c.prinvipalName',
                    changeFn: function ($t, $valInput, rowNum) {

                        $valInput.yxselect_user({
                            hiddenId: 'prinvipalId' + rowNum,
                            nameCol: 'prinvipalName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '��ͬǩ����',
                    value: 'c.contractSigner',
                    changeFn: function ($t, $valInput, rowNum) {

                        $valInput.yxselect_user({
                            hiddenId: 'contractSignerId' + rowNum,
                            nameCol: 'contractSigner',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: 'ʡ��',
                    value: 'c.contractProvince'
                },
                {
                    name: '����',
                    value: 'c.contractCity'
                },
                {
                    name: '��ͬ״̬',
                    value: 'c.state',
                    type: 'select',
                    options: [
                        {
                            'dataName': 'δ�ύ',
                            'dataCode': '0'
                        },
                        {
                            'dataName': '������',
                            'dataCode': '1'
                        },
                        {
                            'dataName': 'ִ����',
                            'dataCode': '2'
                        },
                        {
                            'dataName': '�����',
                            'dataCode': '4'
                        },
                        {
                            'dataName': '�ѹر�',
                            'dataCode': '3'
                        },
                        {
                            'dataName': '�쳣�ر�',
                            'dataCode': '7'
                        }
                    ]

                },
                {
                    name: '����״̬',
                    value: 'c.ExaStatus',
                    type: 'select',
                    options: [
                        {
                            'dataName': 'δ����',
                            'dataCode': 'δ����'
                        },
                        {
                            'dataName': '��������',
                            'dataCode': '��������'
                        },
                        {
                            'dataName': '���������',
                            'dataCode': '���������'
                        },
                        {
                            'dataName': '���',
                            'dataCode': '���'
                        },
                        {
                            'dataName': '���',
                            'dataCode': '���'
                        }
                    ]

                },
                {
                    name: 'ǩԼ����',
                    value: 'c.businessBelong',
                    type: 'select',
                    datacode: 'QYZT'
                }
            ]
        }
    });

});

// ִ�н�����ʾ
function exeStatusView(cid) {
    showModalWin("?model=contract_contract_contract&action=exeStatusView&cid=" + cid);
}