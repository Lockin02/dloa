var show_page = function(page) {
	$("#balanceGrid").yxgrid("reload");
};
$(function() {
	$("#balanceGrid").yxgrid({
		model : 'asset_balance_balance',
		action : 'pageJson',
		title : '资产折旧',
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		// showcheckbox : false,
		param:{"assetId":$("#assetId").val()},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetId',
			display : '固定资产id',
			sortable : true,
			hide : true
		},
				// 获取的是资产卡片的数据
				{
					name : 'useOrgName',
					display : '使用部门',
					sortable : true,
					hide : true
				}, {
					name : 'assetCode',
					display : '卡片编号',
					sortable : true,
					width : 250
				}, {
					name : 'assetName',
					display : '资产名称',
					sortable : true
				}, {
					name : 'localNetValue',
					display : '目前净值',
					sortable : true,
					process : function(v) {
						return moneyFormat2(v);
					}
				},
				// 获取的是余额表的数据
				{
					name : 'origina',
					display : '资产原值',
					sortable : true,
					// 列表格式化千分位
					process : function(v) {
						return moneyFormat2(v);
					}
				},{
					name : 'deprTime',
					display : '折旧日期',
					sortable : true
				}, {
					name : 'initDepr',
					display : '期初累计折旧',
					sortable : true,
					// 列表格式化千分位
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'depr',
					display : '本期计提折旧额',
					sortable : true,
					// 列表格式化千分位
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
//					name : 'deprRate',
//					display : '当期折旧率',
//					sortable : true,
//					width : 80
//				}, {
					name : 'deprRemain',
					display : '剩余折旧额',
					sortable : true,
					// 列表格式化千分位
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'deprShould',
					display : '本期应提折旧额',
					sortable : true,
					// 列表格式化千分位
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'salvage',
					display : '预计净残值',
					sortable : true
				}, {
					name : 'estimateDay',
					display : '预计使用期间',
					sortable : true,
					width : 80
				}, {
					name : 'workLoad',
					display : '工作量',
					sortable : true,
					hide : true
				}, {
					name : 'period',
					display : '期间',
					sortable : true,
					hide : true,
					width : 80
				}, {
					name : 'years',
					display : '年度',
					sortable : true
				}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '资产名称',
			name : 'assetName'
		}, {
			display : '折旧日期',
			name : 'deprTime'
		}],
		toAddConfig : {
			formWidth : 900,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 600
		},
		buttonsEx : [{
			name : 'Review',
			text : "返回",
			icon : 'view',
			action : function() {
				history.back();
			}
		}],
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin('?model=asset_balance_balance&action=init&id='
							+ row.id
							+ "&assetCode="
							+ row.assetCode
							+ "&assetName="
							+ row.assetName
//							+ "&origina="
//							+ row.origina
//							+ "&netValue="
//							+ row.netValue
							+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				//本期年月
						var date = new Date(); //日期对象
						var year = date.getFullYear();
						var month = date.getMonth()+1; //取月的时候取的是当前月-1如果想取当前月+1就可以了
				//折旧日期的年月
						arr=row.deprTime.split("-");
				//判断折旧日期不是本月时，不能编辑
				if (arr[0] == year && arr[1] == month) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_balance_balance&action=init&id='
							+ row.id
							+ "&assetCode="
							+ row.assetCode
							+ "&assetName="
							+ row.assetName
//							+ "&origina="
//							+ row.origina
//							+ "&netValue="
//							+ row.netValue
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("请选中一条数据");
				}
			}
		}]

	});
});