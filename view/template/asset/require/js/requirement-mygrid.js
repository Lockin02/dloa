var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	$("#requirementGrid").yxsubgrid({
		model : 'asset_require_requirement',
		action : 'myPageJson',
		title : '�ҵ��ʲ�����',
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			name : 'status2',
			display : '״̬',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.isRecognize == 5) {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
//			display : 'isSubmit',
//			name : 'isSubmit',
//			hide : true
//		}, {
			name : 'requireCode',
			display : '������',
			width : 120,
			sortable : true
		}, {
			name : 'expectAmount',
			display : 'Ԥ�ƽ��',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			// name : 'recognizeAmount',
			// display : 'ȷ�Ͻ��',
			// sortable : true,
			// process : function(v) {
			// return moneyFormat2(v);
			// }
			// }, {
			name : 'isRecognize',
			display : '��������״̬',
			process : function(v) {
				if(v==0){
					return "δȷ��";
				}else if(v==1){
					return "��ȷ��";
				}else if(v==2){
					return "����";
				}else if(v==3){
					return "ȷ��������";
				}else if(v==4){
					return "���";
				}else if(v==5){
					return "�ɹ���";
				}else if(v==6){
					return "�����ʲ���Ƭ";
				}else if(v==7){
					return "������ǩ��";
				}else if(v==8){
					return "�����";
				}
			},
			sortable : true
		}, {
			name : 'userName',
			display : 'ʹ����',
			sortable : true,
			width : 80
		}, {
			name : 'userDeptName',
			display : 'ʹ�ò���',
			sortable : true,
			width : 80
		}, {
			name : 'applyName',
			display : '������',
			sortable : true,
			width : 80
		}, {
			name : 'applyDeptName',
			display : '���벿��',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 70
		}, {
//			name : 'useName',
//			display : '�ʲ���;',
//			sortable : true,
//			width : 80
//		}, {
			name : 'DeliveryStatus',
			display : '����״̬',
//			hide : true,
			sortable : true,
			process : function(v) {
				if (v == 'WFH') {
					return "δ����";
				} else if (v == 'YFH') {
					return "�ѷ���";
				} else {
					return "���ַ���";
				}
			},
			width : 80
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_require_requireitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'description',
				width : 200,
				display : '�豸����'
			}, {
				name : 'number',
				width : 80,
				display : '����'
			}, {
				name : 'executedNum',
				display : '�ѷ�������',
				width : 80
			}, {
				name : 'dateHope',
				display : '������������',
				width : 80
			}]
		},
		buttonsEx : [{
			name : 'add',
			text : "����",
			icon : 'add',
			action : function(row) {
				alert("���ã���OA�����ߣ��뵽��OA�ύ�������롣лл��");
				return false;
				window.open("?model=asset_require_requirement&action=toadd")
			}
		}],
		// buttonsEx : [{
		// name : 'add',
		// text : "����",
		// icon : 'add',
		// action : function(row) {
		// window.open("?model=asset_require_requirement&action=toadd")
		// }
		// }],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toViewTab&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isRecognize == 4||row.isSubmit == '0' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toEdit&id="
							+ row.id + "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '����',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isRecognize == 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				// alert(row.moneyAll);
				if (window.confirm(("ȷ��������"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_require_requirement&action=rollback&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('�����ѳ��أ�');
								$("#requirementGrid").yxsubgrid("reload");// ���¼���
							} else {
								alert('����ʧ�ܣ�');
							}
						}
					});
				}
			}
		}, {
			text : '���ԭ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isRecognize == 4) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_require_requireback&action=pagebyrequire&requireId="+ row.id
					+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750")
				}
			}
		}, {
			text : 'ǩ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.DeliveryStatus != 'WFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=signPage&requireId="
							+ row.id + "&skey=" + row['skey_'])
				}
			}
				// }, {
				// text : '�ύ���',
				// icon : 'add',
				// showMenuFn : function(row) {
				// if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row, rows, grid) {
				// if (row) {
				// showThickboxWin('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId='
				// + row.id
				// +
				// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				// }
				// }
		}],
		comboEx : [{
			text : '�Ƿ����',
			key : 'DeliveryStatusArr',
			data : [{
				text : 'δ���',
				value : 'WFH,BFFH'
			}, {
				text : '�����',
				value : 'YFH'
			}]
		}],
		searchitems : [{
			display : "������",
			name : 'requireCode'
		}, {
			display : "ʹ����",
			name : 'userName'
		}, {
			display : "ʹ�ò���",
			name : 'userDeptName'
		}, {
			display : "������",
			name : 'applyName'
		}, {
			display : "���벿��",
			name : 'applyDeptName'
//		}, {
//			display : "�豸����",
//			name : 'productName'
		}]
	});
});