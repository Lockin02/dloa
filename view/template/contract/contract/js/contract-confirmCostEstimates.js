var show_page = function () {
    var listGrid = $("#costGrid").data('yxgrid');
    listGrid.options.newp = 1;
    $("#costGrid").yxgrid("reload");
};

$(function () {
    $("#costGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'costEstimatesJson',
        param: {
            'states': '0,1,2,4,5,6',
            'isTemp': '0',
            'isEngConfirmStr': '1',
            'isSubApp': '1',
            'isNoDeal' : $("#isNoDealVal").val()
//			,'engConfirm' : '0'
        },
        title: '��ͬ��Ϣ',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '�鿴',
            icon: 'view',
            showMenuFn: function (row) {
                if (row) {
                    return true;
                }
                return false;
            },
            action: function (row) {
                showModalWin('?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
            }
        }, {
            text: 'ȷ�ϳɱ�����',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.engConfirm == '0' || row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
                    return true;
                }

                return false;
            },
            action: function (row) {
                showThickboxWin('?model=contract_contract_contract&action=confirmCostView&id='
                    + row.id
                    + "&type=Ser"
                    + "&skey="
                    + row['skey_']
                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=750&width=1000');
            }
        }, {
            text: '���',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.engConfirm == '0') {
                    return true;
                }
                return false;
            },
            action: function (row) {
                showThickboxWin("?model=contract_common_relcontract&action=toRollBack&docType=oa_contract_contract&actType=2&id="
                    + row.id
                    + "&skey="
                    + row['skey_']
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
                // if (window.confirm(("ȷ��Ҫ���?"))) {
                //     $.ajax({
                //         type: "POST",
                //         url: "?model=contract_common_relcontract&action=ajaxBack",
                //         data: {
                //             id: row.id
                //         },
                //         success: function (msg) {
                //             if (msg == 1) {
                //                 alert('��سɹ���');
                //                 $("#costGrid").yxgrid("reload");
                //             }
                //         }
                //     });
                // }
            }
        }, {
            text: '������Ŀ�³�',
            icon: 'add',
            showMenuFn: function (row) {
                return (row.ExaStatus == "���" || row.ExaStatus == "�����") && row.projectStatus != "1";
            },
            action: function (row, rows, grid) {
                if (row) {
//                    if (row.projectRate * 1 == 100) {
//                        alert('�����������Ѵﵽ100%�����ܼ����´�');
//                        return false;
//                    }
                    showOpenWin("?model=engineering_charter_esmcharter&action=toAdd&contractId="
                        + row.id);
                }
            }
        }],
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            width: 130
        }, {
            name: 'isNeedStamp',
            display: '�Ƿ����',
            sortable: true,
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
            },
            hide: true
        }, {
            name: 'contractType',
            display: '��ͬ����',
            sortable: true,
            datacode: 'HTLX',
            width: 60
        }, {
            name: 'businessBelongName',
            display: 'ǩԼ��˾',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'contractNatureName',
            display: '��ͬ����',
            sortable: true,
            width: 60,
            process: function (v) {
                if (v == '') {
                    return v;
                    // return "���ϼ�";
                } else {
                    return v;
                }
            },
            hide: true
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true,
            width: 100,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
            }
        }, {
            name: 'contractName',
            display: '��ͬ����',
            sortable: true,
            width: 180
        }, {
            name: 'contractMoney',
            display: '��ͬ���',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'moduleName',
            display: '���',
            sortable: true,
            width: 70
        }, {
            name: 'areaName',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'prinvipalName',
            display: '��ͬ������',
            sortable: true,
            width: 80
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width: 60
        }, {
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
        }, {
            name: 'engConfirmCost',
            display: 'ȷ��״̬',
            sortable: true,
            width: 60,
            process: function (v) {
                return v == "1" ? "��ȷ��" : "δȷ��";
            }
        }, {
            name: 'projectStatus',
            display: '����״̬',
            sortable: true,
            width: 60,
            process: function (v) {
                return v == "1" ? "���" : "δ���";
            }
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'customerId',
            display: '�ͻ�Id',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'customerType',
            display: '�ͻ�����',
            sortable: true,
            datacode: 'KHLX',
            width: 70,
            hide: true
        }, {
            name: 'signStatus',
            display: 'ǩ��״̬',
            sortable: true,
            width: 80,
            process: function (v, row) {
                if (v == '0') {
                    return "δǩ��";
                } else if (v == '1') {
                    return "��ǩ��";
                } else if (v == '2') {
                    return "���δǩ��";
                }
            },
            hide: true
        }, {
            name: 'areaPrincipal',
            display: '��������',
            sortable: true,
            hide: true
        }, {
            name: 'objCode',
            display: 'ҵ����',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'prinvipalDept',
            display: '�����˲���',
            sortable: true,
            hide: true
        }, {
            name: 'prinvipalDeptId',
            display: '�����˲���Id',
            sortable: true,
            hide: true
        }],
        comboEx: [{
            text: 'ȷ��״̬',
            key: 'engConfirmCost',
            data: [{
                text: 'δȷ��',
                value: '0'
            }, {
                text: '��ȷ��',
                value: '1'
            }]
        }, {
            text: '����״̬',
            key: 'projectStatus',
            data: [{
                text: '���',
                value: '1'
            }, {
                text: 'δ���',
                value: '0'
            }]
        }],
        /**
         * ��������
         */
        searchitems: [{
            display: '��ͬ���',
            name: 'contractCode'
        }, {
            display: '��ͬ����',
            name: 'contractName'
        }, {
            display: '�ͻ�����',
            name: 'customerName'
        }, {
            display: 'ҵ����',
            name: 'objCode'
        }],
        sortname: "createTime"
    });

    // ��Ӵ�����ѡ��
    var checkStr = '<div class="btnseparator"></div>' +
        '<div class="fbutton" title="" id="noDealCheckBoxWrap" style="position: relative;"><div>' +
        '<div style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;"></div>' +
        '<input id="noDealCheckBox" type="checkbox" checked disabled> ������' +
        '</div></div>';
    $("#costGrid-op").after(checkStr);

    $("#noDealCheckBoxWrap").click(function(){
        var isNoDeal = (!$("#noDealCheckBox").is(":checked"))? "1" : "0";
        $("#isNoDealVal").val(isNoDeal);
        if(isNoDeal == "1"){
            $("#noDealCheckBox").attr("checked",true);
        }else{
            $("#noDealCheckBox").removeAttr("checked");
        }

        $("#costGrid").data('yxgrid').options.param.isNoDeal = isNoDeal;
        show_page();
    });
});