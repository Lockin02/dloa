var show_page = function(page) {
	$("#receiveGrid").yxgrid("reload");
};
$(function() {
	$("#receiveGrid").yxgrid({
		model : 'asset_purchase_receive_receive',
		title : '�ҵ����յ�',
		param : {'createId':$('#createId').val()},
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '�������',
			sortable : true,
			width : 120
		}, {
			name : 'type',
			display : '��������',
			process : function(v) {
				if(v == '0')
					return '�ɹ�';
				if(v == '1')
					return '����ת�ʲ�';
			},
			width : 80
		}, {
			name : 'code',
			display : '�ɹ������',
			sortable : true,
			width : 120
		}, {
			name : 'purchaseContractCode',
			display : '�ɹ��������',
			sortable : true,
			width : 120
		}, {
			name : 'requireinCode',
			display : '����ת�ʲ����',
			sortable : true,
			width : 130
		}, {
			name : '',
			display : 'Դ��������',
			sortable : true,
			process : function(v,row) {
				if(row.applyId != ''){
					v = row.salvage;
				}else if(row.purchaseContractId != ''){
					v = row.sendName;
				}else if(row.requireinId != ''){
					v = row.applyName;
				}
				return v;
			},
			width : 90
		}, {
			name : 'limitYears',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'salvage',
			display : '������',
			sortable : true,
			width : 90
		}, {
			name : 'deptName',
			display : '���ղ���',
			sortable : true,
			width : 80
		}, {
			name : 'amount',
			display : '���ս��',
			sortable : true,
			// �б���ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			name : 'result',
			display : '���ս��',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true,
			width : 80
		}],
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
//				text : '��������',
//				value : '��������'
//			}, {
				text : '���ύ',
				value : '���ύ'
			}, {
				text : '���',
				value : '���'
//			}, {
//				text : '���',
//				value : '���'
			}]
		},{
			text : '��������',
			key : 'type',
			data : [{
				text : '�ɹ�',
				value : '0'
			}, {
				text : '����ת�ʲ�',
				value : '1'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Դ�����',
			name : 'relDocCode'
		}, {
			display : '���ղ���',
			name : 'deptName'
		}, {
			display : '���ս��',
			name : 'result'
		}],
		toAddConfig : {
			formWidth : 900,
			/**
			 * ��������Ĭ�ϸ߶�
			 */
			formHeight : 600
		},
		toViewConfig : {
			/**
			 * �鿴����Ĭ�Ͽ���
			 */
			formWidth : 900,
			/**
			 * �鿴����Ĭ�ϸ߶�
			 */
			formHeight : 600
		},
		toEditConfig : {
			/**
			 * �༭����Ĭ�Ͽ���
			 */
			formWidth : 900,
			/**
			 * �༭����Ĭ�ϸ߶�
			 */
			formHeight : 600,
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
//			text : 'תΪ�ʲ���Ƭ',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "���")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				window.location='?model=asset_purchase_receive_receiveItem&action=page&receiveId='
//						+ row.id
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
//			}
//		},{
//			text : '�ύ����',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin('controller/asset/purchase/receive/ewf_index.php?actTo=ewfSelect&billId='
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				} else {
//					alert("��ѡ��һ������");
//				}
//			}
//
//		}, {
//			name : 'aduit',
//			text : '�������',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "���" || row.ExaStatus == "���")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_receive&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		}, {
			text : '�������յ�',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
					    type: "POST",
					    url: "?model=asset_purchase_receive_receiveItem&action=isCardJson",
					    data: {"receiveId" : row.id , "isCard" : 1},
					    async: false,
					    success: function(data){
					   		if(data == 0){
								if(confirm('ȷ��Ҫ���ظ����յ�ô��')){
									$.ajax({
										type : "POST",
										url : "?model=asset_purchase_receive_receive&action=ajaxRevocation",
					   					data: {"id" : row.id},
					   					async: false,
										success : function(msg) {
											msg=strTrim(msg);
											if(msg == "1"){
												alert('�����ɹ�!');
//												$("#receiveGrid").yxgrid("reload");
//												$.ajax({
//													type:"post",
//													url: "?model=asset_purchase_receive_receive&action=revocateSendMail",
//													data: {"id" : row.id}
//												})
												$("#receiveGrid").yxgrid("reload");
											}else{
//												alert('�����ɹ���');
												alert('����ʧ��');
												$("#receiveGrid").yxgrid("reload");
											}
										}
									});
								}
					   	    }else{
								alert('���յ��������ʲ���Ƭ,���ܽ��г��ز���');
					   	    }
						}
					});
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_receive_receive&action=ajaxdeletes",
	   					data: {"id" : row.id},
						success : function(msg) {
							if(msg == "1"){
								alert('ɾ���ɹ�');
								$("#receiveGrid").yxgrid("reload");
							}else{
								alert('ɾ��ʧ��');
								$("#receiveGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}]
	});
});