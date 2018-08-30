var show_page = function(page) {
	$("#applyGrid").yxsubgrid("reload");
};
$(function() {
	$("#applyGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		title : '�ʲ��ɹ�����',
		showcheckbox : false,
		param : {
			"relDocId" : $("#requireId").val(),
			"ifShow" : "0"
		},
		isDelAction : false,
		isAddAction:false,
		toEditConfig : {
			/**
			 * �༭��Ĭ�Ͽ��
			 */
			formWidth : 1100,
			/**
			 * �༭��Ĭ�ϸ߶�
			 */
			formHeight : 600,
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true
		}, {
			name : 'applyTime',
			display : '��������',
			sortable : true
		}, {
			name : 'applicantName',
			display : '����������',
			sortable : true
		}, {
			name : 'userName',
			display : 'ʹ��������',
			sortable : true
		}, {
			name : 'useDetName',
			display : 'ʹ�ò���',
			sortable : true
		}, {
			name : 'assetUse',
			display : '�ʲ���;',
			sortable : true
		}, {
			name : 'purchaseDept',
			display : '�ɹ�����',
			sortable : true,
			width : 80,
			process : function(v) {
				if (v == '0') {
					return "������";
				} else if (v == '1') {
					return "������";
				} else {
					return "";

				}
			}
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 90
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_purchase_apply_applyItem&action=pageJson',
			param : [{
				paramId : 'applyId',
				colId : 'id'
			}],
			colModel : [{
				name : 'inputProductName',
				width : 200,
				display : '��������'
			}, {
				name : 'pattem',
				display : "���"
			}, {
				name : 'unitName',
				display : "��λ",
				width : 50
			}, {
				name : 'applyAmount',
				display : "��������",
				width : 70
			}, {
				name : 'dateHope',
				display : "ϣ����������"
			}, {
				name : 'remark',
				display : "��ע"
			}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		}, {
			display : 'ʹ�ò���',
			name : 'useDetName'
		}, {
			display : '��������',
			name : 'productName'
		}],
		toViewConfig : {
			/**
			 * �鿴��Ĭ�Ͽ��
			 */
			formWidth : 900,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight : 600
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_purchase_apply_apply&action=ajaxSubmitConfirm&actType=submit&Id=' + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '��������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���" && row.state != '�ѳ���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
//					if(row.purchaseDept == "0"){
//						alert('�����ɹ���֧�ֳ��ز���');
//						return false;
//					}
					//��ȡ�ɳ�������
					var rs;
					$.ajax({
						type : "POST",
						url : "?model=asset_purchase_apply_apply&action=canBackForm",
						data : {"id" : row.id},
					    async: false,
						success : function(msg) {
							rs = msg;
						}
					});
					//
					if(rs == "1"){
						if(confirm('����δ�´�ɹ�����,�ɽ����������أ�ѡ�� ��ȷ���� �������أ�ѡ�� ��ȡ��������ϸ����')){
							if(confirm('ȷ�Ͻ�������������')){
								$.ajax({
									type : "POST",
									url : "?model=asset_purchase_apply_apply&action=backForm",
									data : {"id" : row.id},
									success : function(msg) {
										if(msg == "1"){
											alert('�����ɹ�');
											$("#applyGrid").yxsubgrid("reload");
										}else{
											alert('����ʧ��');
											$("#applyGrid").yxsubgrid("reload");
										}
									}
								});
							}
						}else{
							showThickboxWin("?model=asset_purchase_apply_apply&action=toBackDetail&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
						}
					}else if(rs == "0"){
						alert('������ȫ���´��꣬���ܽ��д˲���');
					}else{
						showThickboxWin("?model=asset_purchase_apply_apply&action=toBackDetail&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
					}
				} else {
					alert("��ѡ��һ������");
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
						url : "?model=asset_purchase_apply_apply&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#applyGrid").yxsubgrid("reload");
						}
					});
				}
			}
		}]
	});
});