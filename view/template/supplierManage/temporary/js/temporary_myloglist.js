// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".contactGrid").yxgrid("reload");
};
$(function() {
	$(".contactGrid").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'supplierManage_temporary_temporary',
		action : 'mylogpageJson',
		title : "��ע��Ĺ�Ӧ��",
		isAddAction:true,
		isEditAction:false,
		isViewAction:false,
		isDelAction:false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [
				{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '��Ӧ������',
					name : 'suppName',
					sortable : true,
					// ���⴦���ֶκ���
					process : function(v, row) {
						return row.suppName;
					}
				}, {
					display : '��Ӧ�̱��',
					name : 'busiCode',
					sortable : true
				}, {
					display : '��Ҫ��Ʒ',
					name : 'products',
					sortable : true,
					width : 200
				}, {
					display : '��ַ',
					name : 'address',
					hide : true,
					width : 200
				}, {
					display : '����',
					name : 'fax',
					sortable : true
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
				}],
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
			formHeight : 500

		},
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
					+ 600 + "&width=" + 840);
			}
		},
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
						+ 600 + "&width=" + 900);
				}else{
					alert("��ѡ��һ������");
				}
			}
		},
//		{
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
			name : 'exam',
			text : "�ύ����",
			icon : 'edit',
			showMenuFn : function(row) {
					if (row.ExaStatus=='���'||row.ExaStatus=='δ�ύ') {
						return true;
					}
					return false;
			},
			action : function(row, rows, rowIds) {
				location = 'controller/supplierManage/temporary/ewf_index.php?actTo=ewfSelect&formName=��Ӧ�����&examCode=oa_supp_lib_temp&billId='
						+ row.id
			}
		},

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
						if (confirm("ȷ��Ҫ����Ӧ�̡�" + row.suppName + "��¼����ʽ����")) {
							showThickboxWin("?model=supplierManage_temporary_temporary&action=putInFormal&id="
											+ row.id
											+ "&objCode="
											+ row.objCode
											+ "&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=400");
//							$.ajax({
//									type:"POST",
//									url:"?model=supplierManage_temporary_temporary&action=putInFormal",
//									data:{id:row.id},
//							});
						}
					} else {
						alert("δͨ�������Ĺ�Ӧ�̲���¼����ʽ��");
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		}
		,{
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
								$(".contactGrid").yxgrid("reload");
							}else{
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		}],
		// ��������
		searchitems : [{
					display : '��Ӧ������',
					name : 'suppName'
				}, {
					display : '��Ӫ��Ʒ',
					name : 'productName'
				}, {
					display : '״̬',
					name : 'status'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		boName : '��Ӧ������',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC"
//		// ��ʾ�鿴��ť
//		isViewAction : true,
//		// ������Ӱ�ť
//		isAddAction : false,
//		// ������Ӱ�ť
//		isEditAction : true,
//		// ����ɾ����ť
//		isDelAction : false,
		// �鿴��չ��Ϣ
//		toViewConfig : {
//			text : '�鿴',
//			/**
//			 * Ĭ�ϵ���鿴��ť�����¼�
//			 */
//			toViewFn : function(p, g) {
//				var c = p.toViewConfig;
//				var w = c.formWidth ? c.formWidth : p.formWidth;
//				var h = c.formHeight ? c.formHeight : p.formHeight;
//				var rowObj = g.getSelectedRow();
//				if (rowObj) {
//					showThickboxWin("?model="
//							+ p.model
//							+ "&action="
//							+ p.toViewConfig.action
//							+ c.plusUrl
//							+ "&id="
//							+ rowObj.data('data').id
//							+ "&objCode="
//							+ rowObj.data('data').objCode
//							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//							+ 600 + "&width=" + 800);
//				} else {
//					alert('��ѡ��һ�м�¼��');
//				}
//			},
//			/**
//			 * ���ر�Ĭ�ϵ��õĺ�̨����
//			 */
//			action : 'init'
//		},

		// �޸���չ��Ϣ
//		toEditConfig : {
//			text : '�༭',
//			/**
//			 * Ĭ�ϵ���༭��ť�����¼�
//			 */
//			toEditFn : function(p, g) {
//				var c = p.toEditConfig;
//				var w = c.formWidth ? c.formWidth : p.formWidth;
//				var h = c.formHeight ? c.formHeight : p.formHeight;
//				var rowObj = g.getSelectedRow();
//				if (rowObj) {
//					showThickboxWin("?model="
//							+ p.model
//							+ "&action="
//							+ c.action
//							+ c.plusUrl
//							+ "&id="
//							+ rowObj.data('data').id
//							+ "&objCode="
//							+ rowObj.data('data').objCode
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//							+ 600 + "&width=" + 800);
//				} else {
//					alert('��ѡ��һ�м�¼��');
//				}
//			},
//			/**
//			 * ���ر�Ĭ�ϵ��õĺ�̨����
//			 */
//			action : 'init'
//
//		}
	});

});