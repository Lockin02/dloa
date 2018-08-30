var show_page = function(page) {
	$("#applyGrid").yxsubgrid("reload");
};
$(function() {
	$("#applyGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		title : '�ҵ��ʲ��ɹ�����',
		showcheckbox : false,
		param : {
			"createId" : $("#createId").val(),
			"isSetMyList" : 'true'
		},
		isDelAction : false,
		isAddAction:false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'applyTime',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width : 110
		}, {
			name : 'purchaseDept',
			display : '�ɹ�����',
			sortable : true,
			process : function(v){
				if(v == "1"){
					return '������';
				}else if(v == "2"){
					return '��Ϥ������';
				}else{
					return '������';
				}
			},
			width : 80
		}, {
			name : 'applicantName',
			display : '����������',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : 'ʹ��������',
			sortable : true
		}, {
			name : 'useDetName',
			display : 'ʹ�ò���',
			sortable : true,
			width : 80
		}, {
//			name : 'purchCategory',
//			display : '�ɹ�����',
//			sortable : true,
//			datacode : 'CGZL'
//		}, {
			name : 'assetUse',
			display : '�ʲ���;',
			sortable : true
		}, {
			name : 'state',
			display : '����״̬',
			sortable : true,
			width : 80
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
				name : 'issuedAmount',
				display : "�´�����",
				width : 70,
				process : function(v){
					if(v == ""){
						return 0;
					}else{
						return v;
					}

				}
			}, {
				name : 'dateHope',
				display : "ϣ����������"
			}, {
				name : 'remark',
				display : "��ע"
			}]
		},
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '��������',
				value : '��������'
			}, {
				text : '���ύ',
				value : '���ύ'
			}, {
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
			}]
		}],
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
		toAddConfig : {
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 600
		},
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
				if ((row.state == "δ�ύ" || row.state == "���") && (row.ExaStatus == "���ύ" || row.ExaStatus == "���")) {
					return true;
				}
				return false;
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == "��ȷ��" && (row.ExaStatus == "���ύ" || row.ExaStatus == "���")) {
					if(row.purchaseDept == "1"){
						return false;
					}
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				// ��ȡ���ݲ����˵Ĺ�������
				var responseText = $.ajax({
					url:'index1.php?model=deptuser_user_user&action=ajaxGetUserInfo',
					data : {'userId':row.createId},
					type : "POST",
					async : false
				}).responseText;
				var billDept = '';
				if(responseText != '' && responseText != 'false'){
					var resultObj = eval("("+responseText+")");
					billDept = resultObj.DEPT_ID;
				}

				if (row) {
					showThickboxWin('controller/asset/purchase/apply/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&billDept='+billDept
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'cancel',
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
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
							examCode : 'oa_asset_purchase_apply'
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
									    url: "controller/asset/purchase/apply/ewf_index.php?actTo=delWork&billId=",
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
//					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_purchase_apply&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.state == "δ�ύ" || row.state == "���") && (row.ExaStatus == '���ύ' || row.ExaStatus == '���')) {
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