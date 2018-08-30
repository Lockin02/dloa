var show_page = function(page) {
	$("#lockRecord").yxgrid("reload");
};
$(function() {
			var skey_ = $('#skey').val();
			// var proIdValue = parent.document.getElementById("proId").value;
			var param = {
				"objId" : $('#id').val(),
				"stockId" : $('#stockId').val(),
				//"objCode" : $('#objCode').val(),//同个合同有临时跟正式号，所以不能根据编号去过滤
				"objType" : $('#objType').val()
			};
			if ($("#equId").val() != '') {
				param.objEquId = $('#equId').val();
			}
			$("#lockRecord").yxgrid({

				// 如果传入url，则用传入的url，否则使用model及action自动组装

				param : param,

				model : 'stock_lock_lock',
				action : 'lockPageJson',

				// action : 'lockPageJson&objCode=' +$('#objCode').val()
				// +'&equId' + $('#equId').val()
				// +'&stockId' + $('#stockId').val() ,

				/**
				 * 是否显示查看按钮/菜单
				 */
				isViewAction : false,
				/**
				 * 是否显示修改按钮/菜单
				 */
				isEditAction : false,
				/**
				 * 是否显示删除按钮/菜单
				 */
				isDelAction : false,
				/**
				 * 是否显示右键菜单
				 */
				isRightMenu : false,
				// 是否显示添加按钮
				isAddAction : false,
				// 是否显示工具栏
				isToolBar : true,
				// 是否显示checkbox
				showcheckbox : false,

				// 扩展按钮
				buttonsEx : [{
					name : 'return',
					text : '返回',
					icon : 'delete',
					action : function(row, rows, grid) {
						location = "?model=stock_lock_lock&action=toLokStock&id="
								+ $('#id').val()
								+ "&objCode="
								+ $('#objCode').val()
								+ "&objType="
								+ $('#objType').val() + "&skey=" + skey_;

					}
				}],

				// 表单
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '产品编号',
							name : 'productNo',
							sortable : true,
							width : 100

						}, {
							display : '产品名称',
							name : 'productName',
							sortable : true,
							width : 150
						}, {
							display : '锁定仓库',
							name : 'stockName',
							sortable : true,
							width : 100
						}, {
							display : '锁定类型',
							name : 'lockType',
							process : function(v) {
								if (v == 'stockLock') {
									return "仓库锁定";
								} else if (v == 'purchaseLock') {
									return "采购锁定";
								} else if (v == 'productionLock') {
									return "生产锁定";
								} else if (v == 'instockLock') {
									return "入库锁定";
								}else if (v == 'outstockLock') {
									return "出库锁定";
								}else if (v == 'allocationLock') {
									return "调拨锁定";
								}else if (v == 'otherLock') {
									return "其他锁定";
								}
							},
							sortable : true,
							width : 80
						}, {
							display : '锁定数量',
							name : 'lockNum',
							sortable : true,
							width : 100
						}, {
							display : '锁定人',
							name : 'updateName',
							sortable : true,
							width : 150
						}, {
							display : '锁定时间',
							name : 'createTime',
							sortable : true,
							width : 150
						}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
							display : '产品编号',
							name : 'productNo'
						}, {
							display : '产品名称',
							name : 'productName'
						}],
				sortname : 'id',
				sortorder : 'DESC',
				title : '锁定记录'
			});
		});