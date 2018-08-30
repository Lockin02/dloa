var show_page = function(page) {
	$("#bomGrid").yxsubgrid("reload");
};
$(function() {
	$("#bomGrid").yxsubgrid({
		model : 'produce_bom_bom',
		title : 'BOM��',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : 'BOM����',
					sortable : true
				}, {
					name : 'productCode',
					display : '���ϱ���',
					sortable : true
				}, {
					name : 'productName',
					display : '��������',
					sortable : true,
					width : 200
				}, {
					name : 'pattern',
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true,
					width : 50
				}, {
					name : 'properties',
					display : '��������',
					sortable : true,
					datacode : 'WLSX'
				}, {
					name : 'proNum',
					display : '����',
					sortable : true,
					width : 50
				}, {
					name : 'useStatus',
					display : 'ʹ��״̬',
					sortable : true,
					process : function(v) {
						if (v == "0") {
							return "ʹ����";
						} else {
							return "δʹ��";
						}
					}
				}, {
					name : 'version',
					display : '�汾',
					sortable : true,
					width : 50
				}, {
					name : 'auditerName',
					display : '�����',
					sortable : true,
					hide : true
				}, {
					name : 'docStatus',
					display : '���״̬',
					sortable : true,
					process : function(v) {
						if (v == "WSH") {
							return "δ���";
						} else {
							return "�����";
						}
					}
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					hide : true
				}, {
					name : 'createName',
					display : '������',
					sortable : true,
					hide : true

				}, {
					name : 'createTime',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸���',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�����',
					sortable : true,
					hide : true
				}], // ���ӱ������
		subGridOptions : {
			url : '?model=produce_bom_bomitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '���ϱ���'
					}, {
						name : 'productName',
						display : '��������',
						width : 200
					}, {
						name : 'pattern',
						display : '����ͺ�'
					}, {
						name : 'properties',
						display : '��������',
						datacode : 'WLSX'
					}, {
						name : 'unitName',
						display : '��λ'
					}, {
						name : 'useNum',
						display : '����'
					}]
		},
		menusEx : [{
			text : "�鿴",
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=produce_bom_bom&action=toView&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus == 'WSH') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=produce_bom_bom&action=toEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
				}
			}
		}, {
			text : '����',
			icon : 'business',
			showMenuFn : function(row) {
				if (row.useStatus == '1' && row.docStatus == 'YSH') {
					return true;
				} else
					return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��������"))) {
					$.ajax({
								type : "GET",
								url : "?model=produce_bom_bom&action=actUseStatus&id="
										+ row.id
										+ "&productId="
										+ row.productId,
								success : function(result) {
									alert(result)
									if (result == 0) {
										alert("���óɹ�!")
										show_page();
									} else {
										alert("����ʧ��");
									}

								}
							});
				}
			}
		}, {
			text : '���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus == 'WSH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ�������"))) {
					$.ajax({
								type : "POST",
								url : "?model=produce_bom_bom&action=audit&id="
										+ row.id,
								success : function(result) {
									if (result == 0) {
										alert("��˳ɹ�!")
										show_page();
									} else {
										alert("���ʧ��");
									}

								}
							});
				}
			}
		}, {
			text : '�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus == 'YSH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ���������"))) {
					$.ajax({
								type : "GET",
								url : "?model=produce_bom_bom&action=cancelAudit&id="
										+ row.id,
								success : function(result) {
									if (result == 0) {
										alert("����˳ɹ�!")
										show_page();
									} else {
										alert("�����ʧ��");
									}

								}
							});
				}
			}
		}, {
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == 'WSH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
								type : "POST",
								url : "?model=produce_bom_bom&action=ajaxdeletes&id="
										+ row.id,
								success : function(msg) {
									if (msg == 1) {
										alert('ɾ���ɹ�')
										$("#bomGrid").yxsubgrid("reload");
									} else {
										alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!')
									}
								}
							});
				}
			}
		}],
		comboEx : [{
					text : "���״̬",
					key : 'docStatus',
					data : [{
								text : '�����',
								value : 'YSH'
							}, {
								text : 'δ���',
								value : 'WSH'
							}]
				}],
		toAddConfig : {
			formWidth : '1100px',
			formHeight : 600
		},
		toEditConfig : {
			formWidth : '1100px',
			formHeight : 600,
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "BOM����",
					name : 'docCode'
				}, {
					display : "���ϱ���",
					name : 'productCode'
				}, {
					display : "��������",
					name : 'productName'
				}]
	});
});