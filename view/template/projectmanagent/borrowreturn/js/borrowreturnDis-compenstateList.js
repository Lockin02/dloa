var show_page = function(page) {
	$("#compenstateGrid").yxgrid("reload");
};
$(function() {
	$("#compenstateGrid").yxgrid({
		model : 'projectmanagent_borrowreturn_borrowreturnDis',
		title : '�⳥��',
		param : {'states' : '3,4'},
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_borrowreturn_borrowreturnDis&action=toView&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		},{
			text : 'ȷ���⳥���',
			icon : 'add',
            showMenuFn : function(row) {
				if (row.state = '3' && (row.ExaStatus == 'δ����' || row.ExaStatus == '���')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrowreturn_borrowreturnDis&action=comfirmComMoney&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=800&width=1000");
				}
			}
		},{
			text : '������',
			icon : 'add',
            showMenuFn : function(row) {
				if (row.compensateState = '0' && row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ����������?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrowreturn_borrowreturnDis&action=ajaxDisposeCom",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								$("#compenstateGrid").yxgrid("reload");
							}else{
							    alert('����ʧ�ܣ�');
								$("#compenstateGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text : '������',
			key : 'compensateState',
			value : '0',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '�Ѵ���',
				value : '1'
			}]
		}],
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'borrowId',
			display : '���õ�ID',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '�������',
			sortable : true,
			width : 150
		}, {
			name : 'borrowCode',
			display : '���õ����',
			sortable : true
		}, {
			name : 'borrowLimit',
			display : '��������',
			sortable : true
		}, {
			name : 'disposeType',
			display : '����ʽ',
			sortable : true
		}, {
			name : 'state',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "���黹";
				} else if (v == '1') {
					return "���ֹ黹";
				} else if (v == '2') {
					return "�ѹ黹";
				} else if (v == '3') {
					return "�⳥��ȷ��";
				} else if (v == '4') {
					return "ȷ�����";
				} else{
				    return "--";
				}
			},
			width : 60
		}, {
			name : 'money',
			display : '�⳥���',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'affirmMoney',
			display : 'ȷ���⳥���',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'compensateState',
			display : '������״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				} else{
				    return "--";
				}
			},
			width : 80
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'disposeIdea',
			display : '�������',
			sortable : true,
			width : 300
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�������',
			name : 'Code'
		}, {
			display : '���õ����',
			name : 'borrowCode'
		}]
	});
});