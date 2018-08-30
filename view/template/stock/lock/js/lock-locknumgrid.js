var show_page = function(page) {
	$("#locknumGrid").yxgrid("reload");
};
$(function() {
	$("#locknumGrid").yxgrid({
		model : 'stock_lock_lock',
		action : 'locknumJson',
		param : {
			"productId" : $('#productId').val(),
			"stockId" : $('#stockId').val()
		},
		title : '�ֿ�������¼��',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		isRightMenu : true,
		searchitems : [{
					display : 'Դ����',
					name : 'objCode'
				}],
		// ��չ��ť
		buttonsEx : [{
					name : 'return',
					text : '������Ϊ0',
					icon : 'back',
					action : function(row, rows, rowIds, g) {
						delete g.options.param.showAll;
						$("#locknumGrid").yxgrid('reload');
					}
				}, {
					name : 'return',
					text : '����',
					icon : 'back',
					action : function(row, rows, rowIds, g) {
						g.options.param.showAll = true;
						$("#locknumGrid").yxgrid('reload');
					}
				}, {
					name : 'return',
					text : '����',
					icon : 'back',
					action : function(row, rows, grid) {
						location = "?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList";
					}
				}],
		menusEx : [{
			name : 'relock',
			text : '����',
			icon : 'relock',
			action : function(row) {
				try {
					var r = prompt("�������������", row.lockNum);
					if (r) {
						var num = parseInt(r);
						if (num == 0 || num < 0) {
							alert("����ʧ��,������������Ϊ0���߸���.");
							return false;
						} else if (num > parseInt(row.lockNum)) {
							alert("����ʧ��,�����������ܴ�������������.");
							return false;
						} else {
							$.ajax({
										type : 'post',
										url : '?model=stock_lock_lock&action=lockAjax',
										data : {
											'lock[stockName]' : row.stockName,
											'lock[stockId]' : row.stockId,
											'lock[objCode]' : row.objCode,
											'lock[objType]' : row.objType,
											'lock[objId]' : row.objId,
											'lock[objEquId]' : row.objEquId,
											'lock[productId]' : row.productId,
											'lock[productNo]' : row.productNo,
											'lock[productName]' : row.productName,
											'lock[inventoryId]' : row.inventoryId,
											'lock[lockNum]' : num * -1
										},
										success : function(msg) {
											if (msg == 1) {
												alert("�����ɹ�.");
												$("#locknumGrid")
														.yxgrid('reload');
											} else {
												alert("û�н���Ȩ��.����ϵ����Ա.");
											}

										}
									});
						}
					}
				} catch (e) {
					alert("��������ȷ�Ľ�������.")
				}

			}
		}],
		// ����Ϣ
		colModel : [{
					display : 'productId',
					name : 'productId',
					sortable : true,
					// process : function(v , row){
					// alert(row.objTypTest);
					// },
					hide : true
				}, {
					name : 'productName',
					display : '��������',
					sortable : true
				}, {
					name : 'objId',
					display : 'ҵ��Id',
					sortable : true,
					hide : true
				}, {
					name : 'objCode',
					display : '����Դ�����',
					width : 180,
					sortable : true
				}, {
					name : 'lockNum',
					display : '��������',
					sortable : true,
					width : 60
				}, {
					name : 'objTypeTest',
					display : 'Դ������',
					sortable : true
				}, {
					name : 'stockName',
					display : '�ֿ�����',
					sortable : true
				}]
	});
});