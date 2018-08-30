var show_page = function(page) {
    $("#content").yxgrid("reload");
}

$(function() {
    $("#content").yxgrid({
        model: 'flights_balance_balance',
        title: '��Ʊ����',
		showcheckbox : false,
        isAddAction: false,
        isDelAction: false,
        isOpButton : false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        },
        {
            name: 'balanceCode',
            display: '���㵥��',
            sortable: true,
            width : 120
        },
        {
            name: 'balanceDateB',
            display: '���㿪ʼ',
            sortable: true,
            width : 80
        },
        {
            name: 'balanceDateE',
            display: '�������',
            sortable: true,
            width : 80
        },
        {
            name: 'balanceSum',
            display: '���ݽ��',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'actualMoney',
            display: '������',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'rebate',
            display: '����',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 70
        },
        {
            name: 'exchange',
            display: '�һ�������',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'exchangeMoney',
            display: '���ֶһ����',
            sortable: true,
            process : function(v){
            	return moneyFormat2(v);
            },
            width : 80
        },
        {
            name: 'deptId',
            display: '����ID',
            sortable: true,
            hide: true
        },
        {
            name: 'deptName',
            display: '���㲿��',
            sortable: true,
            hide: true
        },
        {
            name: 'balanceStatus',
            display: '����״̬',
            sortable: true,
            process: function(row) {
                if (row == "0") {
                    return "<span style='color: red;' >δ֧��</span>";
                } else if(row == "1") {
                    return "<span style='color: green;' >��֧��</span>";
                } else if(row == "2") {
                    return "<span style='color: blue;' >֧��������</span>";
                }
            },
            width : 70
        },
        {
            name: 'billCode',
            display: '��Ʊ���',
            sortable: true
        },
        {
            name: 'createName',
            display: '������',
            sortable: true,
            hide: true
        },
        {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            width : 130
        }],
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
                return row.balanceStatus == '0';
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_balance_balance&action=toEdit&id=" + rowData[p.keyField] ,1,rowData.id);
			}
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_balance_balance&action=toView&id=" + rowData[p.keyField] ,1,rowData.id);
			}
		},
        // ��չ��ť
        buttonsEx: [{
            text: '���ɽ��㵥',
            icon: 'add',
            action: function(row) {
                showModalWin("?model=flights_balance_balance&action=toSubAdd");
            }
        }],
        // ��չ�Ҽ��˵�
        menusEx: [{
            name: 'delete',
            text: 'ɾ��',
            icon: 'delete',
            showMenuFn: function(row) {
                if (row.balanceStatus == 1 || row.billCode != "") {
                    return false;
                } else {
                    return true;
                }
            },
            action: function(rowDate) {
                if (confirm("ȷ��Ҫɾ����?")) {
                    $.ajax({
                        type: "POST",
                        url: "?model=flights_balance_balance&action=delete",
                        data: {
                            'id': rowDate.id,
                            'msgId' : rowDate.msgId,
                            'itemIds' : rowDate.itemIds
                        },
                        async: false,
                        success: function(data) {
                            if (data == 1) {
                                alert("ɾ���ɹ�");
                                show_page();

                            } else {
                                alert("ɾ��ʧ��");
                            }
                        }

                    });
                }
            }
        },
        {
            text: '¼�뷢Ʊ',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.billCode != "") {
                    return false;
                }
            },
            action: function(row, rows) {
                if (rows && rows.length == 1) {
                    if (row.billCode == "") {
                        showThickboxWin("?model=flights_balance_bill&action=toAdd&id="
                        	+ row.id
                        	+ "&balanceCode=" + row.balanceCode
                        	+ "&actualMoney=" + row.actualMoney
                        	+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
                    } else {
                        alert("���������з�Ʊ");
                    }
                } else {
                    alert('��ѡ��һ������');
                }
            }
        },
        {
            text: '����֧��',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.balanceStatus != '0' || row.billCode == "") {
                    return false;
                }
            },
            action: function(row) {
                if (row.balanceStatus == 1) {
                    alert('�˶�����֧��');
                } else {
                    if (row.billCode != "") {
                        showModalWin("?model=contract_other_other&action=toAddPay&projectType=QTHTXMLX-03&projectId="
                        	+row.id
                        	+"&projectCode="
                        	+row.balanceCode
                        	+"&projectName="
                        	+row.balanceCode
                        	+"&orderMoney="
                        	+row.actualMoney
                    	);
                    } else {
                        alert('������û�з�Ʊ����������֧����');
                    }
                }
            }
        },
        {
            text: '�޸ķ�Ʊ��Ϣ',
            icon: 'edit',
            showMenuFn: function(row) {
                if (row.billCode == "" || row.balanceStatus == '1') {
                    return false;
                }
            },
            action: function(row, rows, grid) {
                if (row.billCode != "") {
                    showThickboxWin("?model=flights_balance_bill&action=toEdit&mainId=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                } else {
                    alert("������û�з�Ʊ");
                }
            }
        }],
        comboEx : [ {// ͷ��Ŀ�����鿴
			text : '����״̬',
			key : 'balanceStatus',
			data : [{
				text : 'δ֧��',
				value : '0'
			}, {
				text : '��֧��',
				value : '1'
			}, {
				text : '֧��������',
				value : '2'
			}]
		} ],
        searchitems: [{
            display: "����Ʊ��",
            name: 'balanceCode'
        }],
        sortorder: "DESC"
    });
});