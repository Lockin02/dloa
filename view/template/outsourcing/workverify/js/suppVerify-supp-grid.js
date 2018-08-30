var show_page = function(page) {
	$("#suppVerifyGrid").yxgrid("reload");
};
$(function() {
	$("#suppVerifyGrid").yxgrid({
		model : 'outsourcing_workverify_suppVerify',
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		param:{'createId':$("#createId").val()},
		bodyAlign:'center',
		title : '������ȷ�ϵ�',
		//����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'formCode',
					display : '���ݱ��',
					width : 150,
					sortable : true,
					process : function(v, row) {
						return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_suppVerify&action=toView&id="
								+ row.id + "\",1)'>" + v + "</a>";
					}
				},{
					name : 'status',
					display : '״̬',
					width : 70,
					sortable : true,
					process : function(v) {
						if (v == "1") {
							return "�ύ����";
						} else if (v == "3") {
							return "��ȷ��";
						} else if (v == "4") {
							return "�ر�";
						} else if (v == "5") {
							return "�������";
						} else {
							return "δ�ύ";
						}
					}
				},{
					name : 'formDate',
					display : '����ʱ��',
					sortable : true
				},{
					name : 'workMonth',
					display : '�������·�',
					width:80,
					sortable : true
				},{
					name : 'endDate',
					display : '���ڽ�������',
					sortable : true
				},{
					name : 'remark',
					display : '��ע',
					width : 450,
					align:'left',
					sortable : true
				}],

          //��������
			comboEx : [{
				text : '״̬',
				key : 'status',
				data : [{
						text : 'δ�ύ',
						value : '0'
					},{
						text : '�ύ����',
						value : '1'
					},{
						text : '�������',
						value : '5'
					},{
						text : '�ر�',
						value : '4'
					}]
				}
			],

          toAddConfig : {
			formWidth : 1000,
			formHeight : 500,
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_workverify_suppVerify&action=toAdd");
			}
		},


		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			toEditFn : function(p, g) {
					var get = g.getSelectedRow().data('data');
				showModalWin("?model=outsourcing_workverify_suppVerify&action=toEdit&id=" + get[p.keyField]);
			}
		},
		toViewConfig : {
//			action : 'toView',
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=outsourcing_workverify_suppVerify&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
					// ��չ�Ҽ��˵�

		menusEx : [{
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					showModalWin("?model=outsourcing_workverify_suppVerify&action=toEdit&id=" +row.id);

				}

			},{
				text: '�������',
				icon: 'add',
				showMenuFn: function(row) {
					if (row.status == '5') {
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin("?model=outsourcing_account_basic&action=toSuppVerifyAdd&suppVerifyId=" + row.id);

				}
			},{
				text : '�ύ����',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("ȷ��Ҫ�ύ?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_suppVerify&action=changeStatus",
							data : {
								id : row.id,
								status:1
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�ύ�ɹ���');
									$("#suppVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.status == '0') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("ȷ��Ҫɾ��?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_workverify_suppVerify&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ���');
									$("#suppVerifyGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			}]
	});
});