var show_page = function () {
    $("#paylistGrid").yxgrid("reload");
};
// ��ǰ����
var countNum = 0;
// �������
var doneNum = 0;
// ����ĺ�ͬID����
var contractIdList = [];
// �����������
var contractCache = [];

// ����
function startInit() {
    // ��ť
    var btn = $("#paylistGrid-update").find("span");
    if (contractIdList.length == countNum) {
        btn.text("���²���ؿ���Ϣ");
        alert("�������");
        btn.data("init", null);
        doneNum = countNum = 0;
        contractCache = contractIdList = [];
    } else if (btn.data("init") == null) {
        alert("�Ѿ���ֹ");
        btn.text("���²���ؿ���Ϣ");
        doneNum = countNum = 0;
        contractCache = contractIdList = [];
    } else {
        // ���û���
        contractCache.push(contractIdList[countNum].contractId);
        // ����
        countNum++;

        // �����30�ı������߳����Ѿ��þ�����ʼ����
        if (countNum % 30 == 0 || contractIdList.length == countNum) {
            // ��ʼִ�д���
            $.ajax({
                url: '?model=finance_income_incomecheck&action=initData',
                data: {
                    contractIds: contractCache.toString()
                },
                type: 'GET',
                dataType: 'json',
                success: function (rs) {
                    // �����Ѵ�������
                    doneNum = doneNum + parseInt(rs.msg);
                    // д�����
                    $("#paylistGrid-update").find("span").text("������ȣ�" + doneNum + "/" + contractIdList.length);

                    // �������ˣ��ӳٸ���
                    if (doneNum == contractIdList.length) {
                        setTimeout(function () {
                            startInit();
                        }, 2000);
                    } else {
                        // ��ʼִ��
                        startInit();
                    }
                }
            });
            // �������
            contractCache = [];
        } else {
            startInit();
        }
    }
}

$(function () {
    $("#paylistGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'pageJsons',
        title: '��¼���տ������ͬ',
        param: {
            'states': '1,2,3,4,5,6', // ,7
            'isTemp': '0',
            'ExaStatus': '���'
        },
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        leftLayout: true,
        buttonsEx: [
            {
                name: 'update',
                text: "���²���ؿ���Ϣ",
                icon: 'add',
                action: function () {
                    // ��ť
                    var btn = $("#paylistGrid-update").find("span");

                    // �����ʼ��������null������Ϊ��ʼ��û��ʼ
                    if (btn.data("init") == null) {
                        if (confirm('ȷ��ִ�и����𣿸��¿�����Ҫ�ϳ���ʱ�䣬�����ĵȴ���\n\nע���ٴε����ť��������ֹ���¡�')) {
                            // ���ó�ʼ��Ϊ1����ʼִ��
                            btn.data("init", 1);
                            // ��ʼִ�д���
                            $.ajax({
                                url: '?model=finance_income_incomecheck&action=getNeedContractIdList',
                                type: 'POST',
                                dataType: 'json',
                                async: false,
                                success: function (msg) {
                                    contractIdList = msg;
                                    // д�����
                                    $("#paylistGrid-update").find("span").text("������ȣ�" + doneNum + "/" + contractIdList.length);
                                    startInit();
                                }
                            });
                        }
                    } else {
                        btn.data("init", null);
                    }
                }
            },
            {
                name: 'import',
                text: "���������ͬ��������",
                icon: 'excel',
                action: function () {
                    showThickboxWin("?model=contractTool_contractTool_contractTool&action=toImportPayInfo"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
                }
            }],
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true,
            width: 150,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            }
        }, {
            name: 'businessBelongName',
            display: 'ǩԼ��˾',
            sortable: true,
            width: 100
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true,
            width: 250
        }, {
            name: 'contractName',
            display: '��ͬ����',
            sortable: true,
            width: 150
        }, {
            name: 'contractMoney',
            display: '��ͬ���',
            sortable: true,
            width: 80
        }, {
            name: 'prinvipalName',
            display: '��ͬ������',
            sortable: true,
            width: 100
        }, {
            name: 'fcheckStatus',
            display: '¼��״̬',
            sortable: true,
            width: 80
        }, {
            name: 'fcheckDate',
            display: '¼��ʱ��',
            sortable: true,
            width: 80
        }, {
            name: 'fcheckName',
            display: '¼����',
            sortable: true,
            width: 80
        }, {
            name: 'fcheckRemarks',
            display: '��ע',
            sortable: true,
            width: 160,
            align: 'center',
            process: function (v, row) {
                var valCtn = ' - ';
                if (row.fcheckStatus == "δ¼��") {
                    valCtn = '<input type="text" id="fcheckRemarks' + row.id + '" style="width:120px" value="' + v + '"/> <input type="button" value="����" onclick = "saveRemarks(' + row.id + ');"/>';
                }
                return valCtn;
            }
        }],

        // ��չ�Ҽ��˵�
        menusEx: [{

            text: '���¸�������',
            icon: 'add',
            action: function (row) {

                showThickboxWin('?model=contract_contract_receiptplan&action=updatePlan&contractId='
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1200');

            }
        }],
        comboEx: [{
            text: "¼��״̬",
            key: 'fcheckStatus',
            value: 'δ¼��',
            data: [{
                text: 'δ¼��',
                value: 'δ¼��'
            }, {
                text: '��¼��',
                value: '��¼��'
            }]
        }, {
            text: "�ؿ�״̬",
            key: 'isIncome',
            data: [{
                text: 'δ�ؿ�',
                value: '0'
            }, {
                text: '�ѻؿ�',
                value: '1'
            }]
        }],
        searchitems: [{
            display: '��ͬ���',
            name: 'contractCode'
        }, {
            display: '��ͬ����',
            name: 'contractName'
        }, {
            display: '�ͻ�����',
            name: 'customerName'
        }],
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
                }
                ,
                {
                    name: '�ͻ�����',
                    value: 'c.customerType',
                    type: 'select',
                    datacode: 'KHLX'
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
                    name: 'ǩԼ����',
                    value: 'c.businessBelong',
                    type: 'select',
                    datacode: 'QYZT'
                }
            ]
        }
    });
});

function saveRemarks(rowId) {
    var inputObj = $("#fcheckRemarks" + rowId);
    var remarks = inputObj.val();
    $.ajax({
        type: "POST",
        url: "?model=contract_contract_contract&action=saveCheckRemarks",
        data: {
            id: rowId,
            remarks: remarks,
            type: 'fcheck'
        },
        success: function (msg) {
            if (msg == 1) {
                alert('����ɹ�');
                show_page();
            } else {
                alert('����ʧ��');
            }
        }
    });
}