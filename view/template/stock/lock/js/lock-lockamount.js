var show_page = function(page) {
	$("#lockamount").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;

	$("#lockamount").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装


        param : {"objCode" : $('#objCode').val()},

		model : 'stock_lock_lock',

//		action : 'lockPageJson&objCode=' +$('#objCode').val()
//		          +'&equId' + $('#equId').val()
//		          +'&stockId' + $('#stockId').val() ,


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
             //是否显示添加按钮
            isAddAction : false,
            //是否显示工具栏
            isToolBar : true,
            //是否显示checkbox
	        showcheckbox : false,


	        //扩展按钮
		buttonsEx : [{
			name : 'return',
			text : '返回',
			icon : 'delete',
			action : function(row, rows, grid) {
				location = "?model=contract_sales_sales&action=toLockStockByContract&id="+  $('#id').val();

			}
		}],

		// 表单
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '产品编号',
				name : 'productNo',
				sortable : true,
				width : 150

			}, {
				display : '产品名称',
				name : 'productName',
				sortable : true,
                width : 150
			}, {
				display : '锁定仓库',
				name : 'stockName',
				sortable : true,
				width : 150
			}, {
				display : '锁定数量',
				name : 'lockNum',
				sortable : true,
				width : 150
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
			display : '产品名称',
			name : 'productName'
		}],
		sortorder : "DESC",
		title : '查看合同锁定数量'
	});
});