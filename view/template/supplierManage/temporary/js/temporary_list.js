// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".supplierGrid").yxgrid("reload");
};
$(function() {
	$(".supplierGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'supplierManage_temporary_temporary',
		action : 'pageJson&parentId=' + $("#parentId").val(),
		title : '��Ӧ����Ϣ--ע���',
		showcheckbox : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},  {
				display : '��Ӧ�̱��',
				name : 'busiCode',
				sortable : true
			},{
				display : '��Ӧ������',
				name : 'suppName',
				sortable : true,
				// ���⴦���ֶκ���
				process : function(v, row) {
					return row.suppName;
				},
				width:"200"
			},  {
				display : '��ַ',
				name : 'address',
				hide : true,
				width : 200
			}, {
				display : '����',
				name : 'fax',
				sortable : true,
				hide:true
			}, {
				display : '���״̬',
				name : 'ExaStatus',
				sortable : true
			}, {
				display : '״̬',
				name : 'status',
				sortable : true,
				process : function(v) {
					if (v == 3) {
						return "�ѽ�����Ӫ��";
					} else {
						return "δ������Ӫ��";
					}
				}
			},{
				display : '��Ҫ��Ʒ',
				name : 'products',
				sortable : true
			}, {
				display : '������Ч����',
				name : 'effectDate',
				sortable : true
			}, {
				display : '����ʧЧ����',
				name : 'failureDate',
				sortable : true
			}, {
				display : '����ʱ��',
				name : 'createTime',
				sortable : true,
				width : 150
			}
		],

		// ��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action :function(row,rows,grid) {
				showThickboxWin("?model=supplierManage_temporary_temporary&action=init"
					+ "&id="
					+ row.id
					+"&skey="+row['skey_']
					+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
					+ 600 + "&width=" + 1000);
			}

		}
		,
		{
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '���' || row.ExaStatus == '��������'){
					return false;
				}
				return true;
			},
			action : function(row,rows,grid) {
				if(row){
					showThickboxWin("?model=supplierManage_temporary_temporary&action=init"
						+ "&id="
						+ row.id
						+"&skey="+row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 450 + "&width=" + 900);
				}else{
					alert("��ѡ��һ������");
				}
			}
		},
		{
			name : 'exam',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == 'δ�ύ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds) {
				location = 'controller/supplierManage/temporary/ewf_index.php?actTo=ewfSelect&formName=��Ӧ�����&examCode=oa_supp_lib_temp&billId='
						+ row.id
			}
		},
//			{
//			text : 'ɾ��',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == 'δ�ύ') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('ȷ��ɾ����')){
//					$.ajax({
//						type : "POST",
//						url : "?model=supplierManage_temporary_temporary&action=ajaxdeletes",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('ɾ���ɹ���');
//								$(".supplierGrid").yxgrid("reload");
//							}else{
//								alert('ɾ��ʧ��!');
//							}
//						}
//					});
//				}
//			}
//		},
		{
			name : 'approval',
			text : "¼����Ӫ��",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���'&& row.status!='3') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (row.ExaStatus == '���') {
						if(confirm('ȷ��¼��?')){
							$.ajax({
								type : "POST",
								url : "?model=supplierManage_temporary_temporary&action=putInFormal",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('¼��ɹ���');
										$(".supplierGrid").yxgrid("reload");
									}else{
										alert('¼��ʧ��!');
									}
								}
							});
						}
					} else {
						alert("δͨ�������Ĺ�Ӧ�̲���¼����ʽ��");
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},{
			text : 'ɾ����Ӧ��',
			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == '���') {
//					return true;
//				}
//				return false;
//			},
			action : function(row) {
				if(confirm('ȷ��ɾ����')){
					$.ajax({
						type : "POST",
						url : "?model=supplierManage_temporary_temporary&action=delSupplier",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$(".supplierGrid").yxgrid("reload");
							}else{
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		}
		],
		toAddConfig : {
			toAddFn : function(p) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ "&flag=1"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			},

			formWidth : 1200,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 450

		},
		toEditConfig : {
			formWidth : 840,
			/**
			 * �鿴��Ĭ�ϸ߶�
			 */
			formHeight :450

		},
		// ��������
		searchitems : [ {
					display : '��Ӧ�̱��',
					name : 'busiCode'
				},{
					display : '��Ӧ������',
					name : 'suppName'
				}
		],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��Ӧ��',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC"
		// �����չ��Ϣ

	});

});