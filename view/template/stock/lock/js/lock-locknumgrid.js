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
		title : '仓库锁定记录表',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		isRightMenu : true,
		searchitems : [{
					display : '源单号',
					name : 'objCode'
				}],
		// 扩展按钮
		buttonsEx : [{
					name : 'return',
					text : '锁数不为0',
					icon : 'back',
					action : function(row, rows, rowIds, g) {
						delete g.options.param.showAll;
						$("#locknumGrid").yxgrid('reload');
					}
				}, {
					name : 'return',
					text : '所有',
					icon : 'back',
					action : function(row, rows, rowIds, g) {
						g.options.param.showAll = true;
						$("#locknumGrid").yxgrid('reload');
					}
				}, {
					name : 'return',
					text : '返回',
					icon : 'back',
					action : function(row, rows, grid) {
						location = "?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList";
					}
				}],
		menusEx : [{
			name : 'relock',
			text : '解锁',
			icon : 'relock',
			action : function(row) {
				try {
					var r = prompt("请输入解锁数量", row.lockNum);
					if (r) {
						var num = parseInt(r);
						if (num == 0 || num < 0) {
							alert("解锁失败,解锁数量不能为0或者负数.");
							return false;
						} else if (num > parseInt(row.lockNum)) {
							alert("解锁失败,解锁数量不能大于已锁定数量.");
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
												alert("解锁成功.");
												$("#locknumGrid")
														.yxgrid('reload');
											} else {
												alert("没有解锁权限.请联系管理员.");
											}

										}
									});
						}
					}
				} catch (e) {
					alert("请输入正确的解锁数量.")
				}

			}
		}],
		// 列信息
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
					display : '物料名称',
					sortable : true
				}, {
					name : 'objId',
					display : '业务Id',
					sortable : true,
					hide : true
				}, {
					name : 'objCode',
					display : '锁定源单编号',
					width : 180,
					sortable : true
				}, {
					name : 'lockNum',
					display : '锁定数量',
					sortable : true,
					width : 60
				}, {
					name : 'objTypeTest',
					display : '源单类型',
					sortable : true
				}, {
					name : 'stockName',
					display : '仓库名称',
					sortable : true
				}]
	});
});