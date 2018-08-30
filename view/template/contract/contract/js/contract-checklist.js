var show_page = function () {
    $("#checklistGrid").yxgrid("reload");
};
$(function () {
    $("#checklistGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'pageJsons',
        title: '��¼�����������ͬ',
        param: {
            'states': '1,2,3,4,5,6', // ȡ���쳣�رգ�state:7���ĺ�ͬ
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
                name: 'import',
                text: "���������ͬ��������",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contractTool_contractTool_contractTool&action=toImportcheckInfo"
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
            name: 'checkStatus',
            display: '¼��״̬',
            sortable: true,
            width: 80
        }, {
            name: 'checkDate',
            display: '¼��ʱ��',
            sortable: true,
            width: 80
        }, {
            name: 'checkName',
            display: '¼����',
            sortable: true,
            width: 80
        },{
            name: 'checkRemarks',
            display: '��ע',
            sortable: true,
            width: 160,
            align: 'center',
            process: function (v, row) {
                var valCtn = ' - ';
                if(row.checkStatus == "δ¼��"){
                    valCtn = '<input type="text" id="checkRemarks'+row.id+'" style="width:120px" value="'+ v +'"/> <input type="button" value="����" onclick = "saveRemarks('+row.id+');"/>';
                }
                return valCtn;
            }
        }],

        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '¼����������',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.checkStatus == "δ¼��" || row.checkStatus == " ") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=contract_checkaccept_checkaccept&action=toAdd&contractCode="
                        + row.contractCode + "&contractId=" + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                }
            }
        }, {
            text: '������������',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.checkStatus == "δ����" || row.checkStatus == "��������") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=contract_checkaccept_checkaccept&action=toEdit&contractId="
                        + row.id + "&contractCode=" + row.contractCode
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                }
            }
        }],
        comboEx: [{
            text: "����״̬",
            key: 'checkStatusArr',
            value: "δ¼��",
            data: [{
                text: 'δ¼��',
                value: 'δ¼��'
            }, {
                text: 'δ����',
                value: 'δ����'
            }, {
                text: '��������',
                value: '��������'
            }, {
                text: '������',
                value: '������'
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

function saveRemarks(rowId){
    var inputObj = $("#checkRemarks"+rowId);
    var remarks = inputObj.val();
    $.ajax({
        type : "POST",
        url : "?model=contract_contract_contract&action=saveCheckRemarks",
        data : {
            id : rowId,
            remarks : remarks,
            type : 'check'
        },
        success : function(msg) {
            if (msg == 1) {
                alert('����ɹ�');
                show_page();
            }else{
                alert('����ʧ��');
            }
        }
    });
}