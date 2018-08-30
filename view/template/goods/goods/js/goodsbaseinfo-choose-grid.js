var show_page = function() {
	$("#goodsbaseinfoGrid").yxgrid("reload");
};

$(function() {
	$("#goodsTypeTree").yxtree({
		url: '?model=goods_goods_goodstype&action=getTreeData&isSale=' + $("#isSale").val()+'&typeId='+$("#typeId").val(),
		event: {
			node_click: function(event, treeId, treeNode) {
				var goodsbaseinfoGrid = $("#goodsbaseinfoGrid")
					.data('yxgrid');
				goodsbaseinfoGrid.options.param['goodsTypeId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				goodsbaseinfoGrid.reload();
			}
		}
	});

	$("#goodsbaseinfoGrid").yxgrid({
		model: 'goods_goods_goodsbaseinfo',
		param: {'useStatus': 'WLSTATUSKF'},
		title: '����1��ѡ���Ʒ',
		showcheckbox: false,
		isAddAction: false,
		isDelAction: false,
		isEditAction: false,
		isOpButton: false,
		autoload: false,
		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'goodsTypeName',
			display: '������������',
			sortable: true,
			width: 80
		}, {
			name: 'goodsName',
			display: '��Ʒ����',
			sortable: true,
			width: 200
		}, {
			name: 'exeDeptName',
			display: '��Ʒ��',
			sortable: true,
			width: 80
		}
//		, {
//			name: 'auditDeptName',
//			display: 'ִ������',
//			sortable: true
//		}
		, {
			name: 'unitName',
			display: '��λ',
			sortable: true,
			width: 50
		}, {
			name: 'useStatus',
			display: '״̬',
			sortable: true,
			datacode: 'WLSTATUS',
			width: 50
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 250
		}, {
			name: 'createName',
			display: '������',
			sortable: true,
			hide: true
		}, {
			name: 'createTime',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'updateName',
			display: '�޸���',
			sortable: true,
			hide: true
		}, {
			name: 'updateTime',
			display: '�޸�����',
			sortable: true,
			hide: true
		}],
		buttonsEx: [{
			name: 'Add',
			text: "ѡ��",
			icon: 'add',
			action: function(rowData) {
				if (rowData) {
					var thisId = rowData.id;
					window.location = '?model=contract_contract_product&action=toSetProductInfo'
					+ "&isMoney="
					+ $("#isMoney").val()
					+ "&isSale="
					+ $("#isSale").val()
					+ "&rowNum="
					+ $("#rowNum").val()
					+ "&componentId="
					+ $("#componentId").val()
					+ "&isFrame="
					+ $("#isFrame").val()
                    + "&typeId="
                    + $("#typeId").val()
			        + "&notEquSlt="
                    + $("#notEquSlt").val()
					+ '&goodsId='
					+ thisId
					+ "&goodsName="
					+ rowData.goodsName
					+ "&isEncrypt="
					+ rowData.isEncrypt
					+ "&isCon="
					+ $("#isCon").val()
					+ "&exeDeptName="
					+ rowData.exeDeptName
					+ "&exeDeptCode="
					+ rowData.exeDeptCode
					+ "&auditDeptName="
					+ rowData.auditDeptName
					+ "&auditDeptCode="
					+ rowData.auditDeptCode;
				} else {
					alert('����ѡ���¼');
				}
			}
		}],
		event: {
			row_dblclick: function(e, row, rowData) {
				if (rowData) {
					if (rowData.useStatus == "WLSTATUSGB") {
						alert("�ò�Ʒ�ѹرգ�������ѡ��!");
					} else {
						var thisId = rowData.id;
						window.location = '?model=contract_contract_product&action=toSetProductInfo'
						+ "&isMoney="
						+ $("#isMoney").val()
						+ "&isSale="
						+ $("#isSale").val()
						+ "&rowNum="
						+ $("#rowNum").val()
						+ "&componentId="
						+ $("#componentId").val()
						+ "&isFrame="
						+ $("#isFrame").val()
                        + "&typeId="
                        + $("#typeId").val()
							+ "&notEquSlt="
							+ $("#notEquSlt").val()
						+ '&goodsId='
						+ thisId
						+ "&goodsName="
						+ rowData.goodsName
						+ "&isEncrypt="
						+ rowData.isEncrypt
						+ "&isCon="
						+ $("#isCon").val()
						+ "&exeDeptName="
						+ rowData.exeDeptName
						+ "&exeDeptCode="
						+ rowData.exeDeptCode
						+ "&auditDeptName="
						+ rowData.auditDeptName
						+ "&auditDeptCode="
						+ rowData.auditDeptCode;
					}

				} else {
					alert('����ѡ���¼');
				}
			}
		},
		menusEx: [{
			text: '����Ԥ��',
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=goods_goods_properties&action=toPreView&goodsId="
				+ row.id
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}],
		toViewConfig: {
			action: 'toView'
		},
		// ����״̬���ݹ���
		comboEx: [{
			text: "״̬",
			key: 'useStatus',
			value: 'WLSTATUSKF',
			datacode: 'WLSTATUS'
		}, {
			text: "��Ʒ��",
			key: 'exeDeptCode',
			datacode: 'HTCPX'
		}],
		searchitems: [{
			display: "��Ʒ����",
			name: 'goodsName'
		}, {
			display: "���ϱ��",
			name: 'productCode'
		}, {
			display: "��������",
			name: 'productName'
		}]
	});
});