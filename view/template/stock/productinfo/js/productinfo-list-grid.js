/** ������Ϣ�б�* */

var show_page = function() {
	$("#proTypeTree").yxtree("reload");
	$("#productinfoGrid").yxgrid("reload");
};

$(function() {

	$("#tree").yxtree({
		url: '?model=stock_productinfo_producttype&action=getTreeDataByParentId',
		event: {
			"node_click": function(event, treeId, treeNode) {
				var productinfoGrid = $("#productinfoGrid").data('yxgrid');

				productinfoGrid.options.extParam['proTypeId'] = treeNode.id;
				$("#proTypeId").val(treeNode.id);
				$("#proType").val(treeNode.name);
				$("#arrivalPeriod").val(treeNode.submitDay);
				productinfoGrid.reload();
			}
		}
	});

	$("#productinfoGrid").yxgrid({
		customCode: 'productinfolistgrid',
		model: 'stock_productinfo_productinfo',
		action: 'pageProInfoJson',
		title: '������Ϣ����',
		isToolBar: true,
		isViewAction: false,
		showcheckbox: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton: false,
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			display: '��������',
			name: 'proType',
			sortable: true
		}, {
			display: '���ϱ���',
			name: 'productCode',
			width: 80,
			sortable: true
		}, {
			display: 'k3����',
			name: 'ext2',
			width: 80,
			sortable: true
		}, {
			display: '��������',
			name: 'productName',
			sortable: true,
			width: 200
		}, {
			display: '�������ϱ���',
			name: 'relProductCode',
			width: 80,
			sortable: true,
			hide: true
		}, {
			display: '������������',
			name: 'relProductName',
			sortable: true,
			width: 200,
			hide: true
		}, {
			name: 'pattern',
			display: '����ͺ�',
			sortable: true
		}, {
			name: 'priCost',
			display: '����',
			sortable: true,
			hide: true
		}, {
			name: 'unitName',
			display: '��λ',
			width: 50,
			sortable: true
		}, {
			name: 'businessBelongName',
			display: '������˾',
			width: 70,
			sortable: true
		}, {
			display: '״̬',
			name: 'ext1',
			process: function(v) {
				if (v == "WLSTATUSKF") {
					return "����";
				} else {
					return "�ر�";
				}
			},
			sortable: true,
			width: 50
		}, {
			name: 'aidUnit',
			display: '������λ',
			sortable: true,
			hide: true
		}, {
			name: 'converRate',
			display: '������',
			sortable: true,
			hide: true
		}, {
			name: 'warranty',
			display: '������(��)',
			hide: true,
			sortable: true
		}, {
			name: 'arrivalPeriod',
			display: '��������(��)',
			hide: true,
			sortable: true
		}, {
			name: 'purchPeriod',
			display: '�ɹ�����(��)',
			sortable: true,
			hide: true
		}, {
			name: 'accountingCode',
			display: '��ƿ�Ŀ����',
			sortable: true,
			datacode: 'KJKM',
			hide: true
		}, {
			name: 'changeProductCode',
			display: '������ϱ���',
			sortable: true,
			hide: true
		}, {
			name: 'changeProductName',
			display: '�����������',
			sortable: true,
			hide: true
		}, {
			name: 'closeReson',
			display: '�ر�ԭ��',
			sortable: true,
			hide: true
		}, {
			name: 'leastPackNum',
			display: '��С��װ��',
			sortable: true,
			hide: true
		}, {
			name: 'leastOrderNum',
			display: '��С������',
			sortable: true,
			hide: true
		}, {
			name: 'material',
			display: '����',
			sortable: true,
			hide: true
		}, {
			name: 'brand',
			display: 'Ʒ��',
			sortable: true,
			hide: true
		}, {
			name: 'color',
			display: '��ɫ',
			sortable: true,
			hide: true
		}, {
			name: 'purchUserName',
			display: '�ɹ�������',
			sortable: true,
			hide: true
		}, {
			display: '��������',
			name: 'esmCanUse',
			process: function(v) {
				if (v == "1") {
					return "��";
				} else {
					return "��";
				}
			},
			sortable: true,
			width: 50,
			hide: true
		}, {
			name: 'createName',
			display: '������',
			sortable: true,
			hide: true
		}, {
			name: 'updateName',
			display: '�޸���',
			sortable: true,
			hide: true
		}],
		toAddConfig: {
			toAddFn: function(p) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toAdd&proType="
				+ $("#proType").val()
				+ "&proTypeId="
				+ $("#proTypeId").val()
				+ "&arrivalPeriod="
				+ $("#arrivalPeriod").val()
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=590&width=900");
			}
		},
		menusEx: [{
			name: 'edit',
			text: "�޸�",
			icon: 'edit',
			action: function(row) {
				var editTypeResult = false;
				$.ajax({
					type: "POST",
					async: false,
					url: "?model=stock_productinfo_productinfo&action=checkProType",
					data: {
						typeId: row.proTypeId
					},
					success: function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				});
				if (editTypeResult) {// ��������Ȩ��
					if ($("#productUpdate").val() != '1') {// ����Ȩ��
						var editReresult = false;
						$.ajax({
							type: "POST",
							async: false,
							url: "?model=stock_productinfo_productinfo&action=checkExistBusiness",
							data: {
								id: row.id
							},
							success: function(result) {
								if (result == 1)
									editReresult = true;
							}
						});
						if (editReresult) {// �޸�Ȩ��
							showThickboxWin("?model=stock_productinfo_productinfo&action=init&id="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

						} else {
							alert("�������Ѿ����ڹ���ҵ����Ϣ,�������޸�,����ϵ����Ա!");
						}
					} else {
						showThickboxWin("?model=stock_productinfo_productinfo&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

					}
				} else {
					alert("������������û�й���Ȩ��!");
				}

			}
		}, {
			name: 'view',
			text: "�鿴",
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
				+ row.id
				+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			name: 'view',
			text: "������־",
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
				+ row.id
				+ "&tableName=oa_stock_product_info"
				+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			text: '����',
			icon: 'edit',
			showMenuFn: function() {
				return $("#productUpdate").val() == '1';
			},
			action: function(row) {
				var editTypeResult = false;
				$.ajax({
					type: "POST",
					async: false,
					url: "?model=stock_productinfo_productinfo&action=checkProType",
					data: {
						typeId: row.proTypeId
					},
					success: function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				});
				if (editTypeResult) {// ��������Ȩ��
					showThickboxWin('?model=stock_productinfo_productinfo&action=toUpdate&id='
					+ row.id
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("��û�д��������͵Ĺ���Ȩ��!")
				}
			}
		}, {
			text: '������',
			icon: 'view',
			action: function(row) {
				showThickboxWin('?model=stock_productinfo_productinfo&action=toViewRelation&id='
				+ row.id
				+ "&skey="
				+ row['skey_']
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
			}
		}, {
			text: 'BOM�嵥',
			icon: 'edit',
			action: function(row) {
				showModalWin('?model=stock_material_material&action=toEditList&productId='
				+ row.id + '&productCode=' + row.productCode + '&productName=' + row.productName
				+ "&skey="
				+ row['skey_']);
			}
		}],
		buttonsEx: [{
			text: "����",
			icon: 'excel',
			items: [{
				text: "��������",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}, {
				text: "��������",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadUpdateExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}, {
				text: "���³ɱ�",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUpdatePriceExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}, {
				text: "����K3",
				icon: 'excel',
				action: function() {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadK3Excel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
				}
			}]
		}, {
			text: "����",
			icon: 'excel',
			items: [{
				name: 'expport',
				text: "��������",
				icon: 'excel',
				action: function() {
					window.open("?model=stock_productinfo_productinfo&action=toExportExcel", "",
						"width=200,height=200,top=200,left=200");
				}
			}, {
				name: 'expport',
				text: "�����������",
				icon: 'excel',
				action: function() {
					var proTypeId = $("#proTypeId").val();
					window.open("?model=stock_productinfo_productinfo&action=toArmatureExcel&proTypeId=" + proTypeId, "",
						"width=800,height=800,top=200,left=200");
				}
			}]
		}, {
			name: 'editaccess',
			text: "�������",
			icon: 'edit',
			action: function() {
				if ($("#proTypeId").val() != "") {
					showThickboxWin("?model=stock_productinfo_productinfo&action=toEditProAccess&proTypeId=" + $("#proTypeId").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				} else {
					alert("��ѡ�����Ϸ��࣡");
				}
			}
		}],
		// �߼�����
		advSearchOptions: {
			modelName: 'productinfolist',
			searchConfig: [{
				name: '���ϱ��',
				value: 'c.productCode'
			}, {
				name: '��������',
				value: 'c.productName'
			}, {
				name: '����ͺ�',
				value: 'c.pattern'
			}, {
				name: '��������',
				value: 'c.proType'
			}, {
				name: '����״̬',
				value: 'c.ext1',
				type: 'select',
				datacode: 'WLSTATUS'
			}, {
				name: 'K3����',
				value: 'c.ext2'
			}, {
				name: '������˾',
				value: 'c.businessBelongName'
			}]
		},
		searchitems: [{
			display: '���ϱ���',
			name: 'productCode'
		}, {
			display: '��������',
			name: 'productName'

		}, {
			display: '��������',
			name: 'ext3'
		}, {
			display: 'Ʒ��',
			name: 'brand'
		}, {
			display: '����ͺ�',
			name: 'pattern'
		}, {
			name: 'ext2',
			display: 'K3����'
		}, {
			name: 'businessBelongName',
			display: '������˾'
		}],
		sortname: "ext1 desc,id",
		// Ĭ������˳��
		sortorder: "asc"
	});
});