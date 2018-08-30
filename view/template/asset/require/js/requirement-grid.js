var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	//��ȡ��������״̬��ֵ
	var isRecognize = $("#isRecognize").val();
	$("#requirementGrid").yxsubgrid({
		model : 'asset_require_requirement',
		param : {
			// 'ExaStatusArr' : '���,���ȷ������'
			'isSubmit' : '1',
			'isRecognizeFlag' : '2,4'
		},
		title : '�ʲ���������',
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
		// comboEx : [{
		// text : 'ȷ��״̬',
		// key : 'isRecognize',
		// data : [{
		// text : 'δȷ��',
		// value : '0'
		// }, {
		// text : '��ȷ��',
		// value : '1'
		// }]
		// }],
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
			name : 'isRecognize',
			display : '��������״̬',
			process : function(v) {
				if(v==0){
					return "δȷ��";
				}else if(v==1){
					return "��ȷ��";
				}else if(v==3){
					return "ȷ��������";
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
			name : 'DeliveryStatus',
			display : '����״̬',
			process : function(v) {
				if (v == 'WFH') {
					return "δ����";
				} else if (v == 'YFH') {
					return "�ѷ���";
				} else {
					return "���ַ���";
				}
			},
			sortable : true,
			width : 70
		}, {
			name : 'requireInStatus',
			display : '����ת�ʲ�״̬',
			process : function(v) {
				if (v == '1') {
					return "������";
				} else if (v == '2') {
					return "������";
				} else if (v == '3') {
					return "�������ʲ���Ƭ";
				} else if (v == '4') {
					return "�����";
				} else {
					return "----";
				}
			},
			sortable : true,
			width : 100
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
			width : 80
		}, {
			// name : 'useName',
			// display : '�ʲ���;',
			// sortable : true
			// }, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
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
			}, {
				name : 'purchDept',
				display : '�ɹ�����',
				process : function(v) {
					if (v == '0') {
						return "������";
					}else if(v == '1'){
						return "������";
					}else{
						return "";
					}
				},
				width : 80
			}, {
				name : 'purchAmount',
				display : '�ɹ�����',
				width : 80
			}]
		},
		comboEx : [{
			text : '����״̬',
			key : 'isRecognize',
			data : [{
				text : 'δȷ��',
				value : '0'
			}, {
				text : '��ȷ��',
				value : '1'
			}, {
				text : 'ȷ��������',
				value : '3'
			}, {
				text : '�ɹ���',
				value : '5'
			}, {
				text : '�����ʲ���Ƭ',
				value : '6'
			}, {
				text : '������ǩ��',
				value : '7'
			}, {
				text : '�����',
				value : '8'
			}],
			value : isRecognize
		},{
			text : '����ת�ʲ�״̬',
			key : 'requireInStatus',
			data : [{
				text : '������',
				value : '1'
			}, {
				text : '������',
				value : '2'
			}, {
				text : '�������ʲ���Ƭ',
				value : '3'
			}, {
				text : '�����',
				value : '4'
			}, {
				text : '��������',
				value : '0'
			}]
		},{
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
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toViewTab&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'],1,700,1900);

				}
			}
		}, {
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isRecognize == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_require_requirement&action=toBackDetail&id="
							+ row.id + "&skey=" + row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=650')
				}
			}
		}, {
//			text : '���',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.isRecognize == 0) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				// alert(row.moneyAll);
//				if (window.confirm(("ȷ�������"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=asset_require_requirement&action=reback&id="
//								+ row.id,
//						success : function(msg) {
//							if (msg == 1) {
//								alert('�����Ѵ�أ�');
//								$("#requirementGrid").yxsubgrid("reload");// ���¼���
//							} else {
//								alert('���ʧ�ܣ�');
//							}
//						}
//					});
//					$.ajax({
//						type : "POST",
//						url : "?model=asset_require_requirement&action=rebackMail&id="
//								+ row.id
//					});
//				}
//			}
//		}, {
			text : 'ȷ�Ͻ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSubmit == '1' && row.ExaStatus == ''
						&& row.isRecognize == '0'
						&& row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirement&action=toRecognize&id="
							+ row.id + "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_daily_borrow&action=toAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_daily_charge&action=toAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '�ɹ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.DeliveryStatus != 'YFH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_purchase_apply_apply&action=toRequireAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			text : '����ת�ʲ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.DeliveryStatus != 'YFH' && row.requireInStatus != '4') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toAdd&requireId="
							+ row.id
							+ "&requireCode="
							+ row.requireCode
							+ "&skey=" + row['skey_'])
				}
			}
		}, {
			name : 'cancel',
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isRecognize == 3) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_requirement'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
						    	show_page();
								return false;
							}else{
								if(confirm('ȷ��Ҫ����������')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/require/ewf_index_require.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
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
		}]
	});
});