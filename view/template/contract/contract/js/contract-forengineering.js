var show_page = function () {
    $("#engGrid").yxsubgrid("reload");
};

$(function () {
	//��ʼ���Ҽ���ť����
	menusArr = [
			{
			    text: '�鿴',
			    icon: 'view',
			    action: function (row) {
			        showModalWin('?model=contract_contract_contract&action=toViewTab&id='
			        + row.id
			        + "&skey="
			        + row.skey_);
			    }
			}],
	FBXMZC = {
            text: '������Ŀ�³�',
            icon: 'add',
//            showMenuFn: function (row) {
//                return !(row.projectRate * 1 == 100 || row.state == "3");
//            },
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
        };
	//��ȡ����Ȩ��
	$.ajax({
		type : "POST",
		url : "?model=engineering_project_esmproject&action=getLimits",
		data : {
			limitName : '����Ȩ��-�����ͬ'
		},
		async: false,
		success : function(data) {
			if(data==1){
				menusArr.push(FBXMZC);
			}
		}
	});
    $("#engGrid").yxsubgrid({
        model: 'contract_contract_contract',
        action: 'esmContractJson',
        title: '��Ŀ��ͬ�б�',
        param: {
            'states': '2,3,4',
            'isTemp': '0'
        },
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        showcheckbox: false,
        customCode: 'engGrid',
        // ��չ�Ҽ��˵�
        menusEx: menusArr,
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'createDate',
            display: '��������',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'contractType',
            display: '��ͬ����',
            sortable: true,
            datacode: 'HTLX',
            width: 60,
            hide: true
        }, {
            name: 'contractNatureName',
            display: '��ͬ����',
            sortable: true,
            width: 60
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true,
            width: 180,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.id
                    + '\')">' + v + '</a>';
            }
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true,
            width: 100
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
            width: 70
        }, {
            name: 'contractName',
            display: '��ͬ����',
            sortable: true,
            width: 150,
            hide: true
        }, {
            name: 'contractProvince',
            display: '��ͬʡ��',
            sortable: true,
            width: 70
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
            name: 'contractTempMoney',
            display: 'Ԥ�ƺ�ͬ���',
            sortable: true,
            width: 80,
            process: function (v, row) {
                if (row.contractMoney == '' || row.contractMoney == 0.00
                    || row.id == 'allMoney') {
                    return moneyFormat2(v);
                } else {
                    return "<font color = '#B2AB9B'>" + moneyFormat2(v)
                        + "</font>";
                }

            }
        }, {
            name: 'contractMoney',
            display: 'ǩԼ��ͬ���',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width: 60
        }, {
            name: 'sign',
            display: '�Ƿ�ǩԼ',
            sortable: true,
            width: 70,
            process: function (v, row) {
                if (row.id == '' || row.id == undefined) {
                    return "";
                } else if (v == 0 && row.id != '') {
                    return "δǩԼ";
                } else if (v == 1) {
                    return "��ǩԼ";
                }
            },
            hide: true
        }, {
            name: 'areaName',
            display: '��������',
            sortable: true,
            width: 60
        }, {
            name: 'areaPrincipal',
            display: '��������',
            sortable: true
        }, {
            name: 'prinvipalName',
            display: '��ͬ������',
            sortable: true,
            width: 80
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
            name: 'objCode',
            display: 'ҵ����',
            sortable: true,
            width: 120
        }, {
            name: 'projectRate',
            display: '����������',
            sortable: true,
            process: function (v) {
                return v + " %";
            },
            width: 80
        }],
        comboEx: [{
            text: '��ͬ״̬',
            key: 'state',
            data: [{
                text: '������',
                value: '1'
            }, {
                text: 'ִ����',
                value: '2'
            }, {
                text: '�����',
                value: '4'
            }, {
                text: '�ѹر�',
                value: '3'
            }, {
                text: '�Ѻϲ�',
                value: '5'
            }, {
                text: '�Ѳ��',
                value: '6'
            }, {
                text: '�쳣�ر�',
                value: '7'
            }]
        }],
        // ���ӱ������
        subGridOptions: {
            url: '?model=contract_contract_product&action=pageJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [{
                paramId: 'contractId',// ���ݸ���̨�Ĳ�������
                colId: 'id'// ��ȡ���������ݵ�������

            }],
            // ��ʾ����
            colModel: [{
                name: 'conProductName',
                width: 200,
                display: '��Ʒ����'
            }, {
                name: 'conProductDes',
                display: '��Ʒ����',
                width: 80
            }, {
                name: 'number',
                display: '����',
                width: 80
            }, {
                name: 'price',
                display: '����',
                width: 80
            }, {
                name: 'money',
                display: '���',
                width: 80
            }, {
                name: 'licenseButton',
                display: '��������',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='#' onclick='showLicense(\'" + row.license + "\')'>�鿴</a>";
                    } else {
                        return "";
                    }
                }
            }, {
                name: 'deployButton',
                display: '��Ʒ����',
                process: function (v, row) {
                    if (row.deploy != "") {
                        return "<a href='#' onclick='showGoods(\"" + row.deploy + "\",\"" + row.conProductName + "\")'>�鿴</a>";
                    } else {
                        return "";
                    }
                }
            }]
        },
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
        sortname: "createTime",
        // �߼�����
        advSearchOptions: {
            modelName: 'contractInfo',
            // ѡ���ֶκ��������ֵ����
            selectFn: function ($valInput) {
                $valInput.yxcombogrid_area("remove");
            },
            searchConfig: [{
                name: '��������',
                value: 'c.createTime',
                changeFn: function ($t, $valInput) {
                    $valInput.click(function () {
                        WdatePicker({
                            dateFmt: 'yyyy-MM-dd'
                        });
                    });
                }
            }, {
                name: '��������',
                value: 'c.areaPrincipal',
                changeFn: function ($t, $valInput, rowNum) {
                    if (!$("#areaPrincipalId" + rowNum)[0]) {
                        $hiddenCmp = $("<input type='hidden' id='areaPrincipalId" + rowNum + "' value=''>");
                        $valInput.after($hiddenCmp);
                    }
                    $valInput.yxcombogrid_area({
                        hiddenId: 'areaPrincipalId' + rowNum,
                        height: 200,
                        width: 550,
                        gridOptions: {
                            showcheckbox: true
                        }
                    });
                }
            }]
        }
    });
});