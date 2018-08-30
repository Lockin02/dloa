// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function() {
	$(".stockinfoGrid").yxgrid("reload");
};
$(function() {
	$(".stockinfoGrid").yxgrid({
		model: 'stock_stockinfo_stockinfo',
		title: "�ֿ���Ϣ����",
		menuWidth: 140,
		isViewAction: false,
		isEditAction: false,
		isAddAction: true,
		isDelAction: false,
		showcheckbox: false, // ��ʾcheckbox
		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			display: '�ֿ����',
			name: 'stockCode',
			sortable: true,
			align: 'center'
		}, {
			display: '�ֿ�����',
			name: 'stockName',
			sortable: true,
			width: '150',
			align: 'center'
		}, {
			display: '�ֿ�����',
			name: 'stockType',
			sortable: true,
			datacode: 'CKLX',
			width: '50',
			align: 'center'
		}, {
			display: '�ֿ���;',
			name: 'stockUseCode',
			sortable: true,
			align: 'center',
			datacode: 'CKYT'
		}, {
			display: '������˾',
			name: 'businessBelongName',
			sortable: true,
			align: 'center'
		}, {
			display: '�ֿ����Ա',
			name: 'chargeUserName',
			sortable: true,
			width: '100',
			align: 'center'
		}, {
			display: '�ֿ��ַ',
			name: 'adress',
			sortable: true,
			width: '250',
			align: 'center'
		}],
		menusEx: [{
			name: 'view',
			text: "�鿴",
			icon: 'view',
			action: function(row) {
				showThickboxWin("?model=stock_stockinfo_stockinfo&action=view&id="
				+ row.id
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
			}
		}, {
			name: 'edit',
			text: "�޸Ĳֿ���Ϣ",
			icon: 'edit',
			showMenuFn: function(row) {
				return row.id != "-1";
			},
			action: function(row) {
				showThickboxWin("?model=stock_stockinfo_stockinfo&action=init&id="
				+ row.id
				+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
			}
		}, {
			text: 'ɾ��',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.id != "-1";
			},
			action: function(row) {
				if (window.confirm("ȷ��Ҫɾ��?")) {
					$.ajax({
						type: "POST",
						url: "?model=stock_stockinfo_stockinfo&action=ajaxdeleteStock",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								show_page();
								alert('ɾ���ɹ���');
							} else {
								alert('ɾ��ʧ�ܣ���ȷ�ϲֿ��Ƿ��Ѿ���ʼ��!');
							}
						}
					});
				}
			}
		}, {
			name: 'view',
			text: "�����Ϣ",
			icon: 'business',
			action: function(row) {
				location = "?model=stock_inventoryinfo_inventoryinfo&action=index&stockId="
				+ row.id
				+ "&stockName="
				+ row.stockName
				+ "&stockCode=" + row.stockCode;
			}
		}],
		// ��������
		searchitems: [{
			display: '�ֿ�����',
			name: 'stockName'
		}, {
			display: '�ֿ����',
			name: 'stockCode'
		}],
		buttonsEx: [{
			name: 'import',
			text: "����EXCEL",
			icon: 'excel',
			action: function() {
				showThickboxWin("?model=stock_stockinfo_stockinfo&action=toUploadExcel"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		}, {
			name: 'check',
			text: "���У��",
			icon: 'excel',
			action: function() {
				showModalWin("?model=stock_stockinfo_stockinfo&action=toCheckStockBalance", 1, "check");
			}
		}],
		// Ĭ������˳��
		sortname: 'id',
		sortorder: 'DSC'
	});
});