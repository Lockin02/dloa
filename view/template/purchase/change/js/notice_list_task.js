// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#noticeGrid").yxgrid('reload');
};

$(function() {
			$("#noticeGrid").yxgrid({
				// 如果传入url，则用传入的url，否则使用model及action自动组装
				model : 'purchase_change_notice',
				action : 'pageJsonTask',
				isAddAction : false,
				isEditAction : false,
				isDelAction : false,
				isViewAction:false,
				showcheckbox:false,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '变更单编号',
							name : 'changeNumb',
							width : 200
						}
						, {
							display : '采购任务ID',
							name : 'basicId',
							hide:true
						}
						, {
							display : '采购任务编号',
							name : 'basicNumb',
							width : 200
						}
//						, {
//							display : '变更主题',
//							name : 'subject',
//							width : 200
//						}
						, {
							display : '状态',
							name : 'state',
							process : function(v) {
								if (v == 0) {
									return "未接收";
								}
								return "已接收";

							}
						}, {
							display : '变更明细',
							name : 'remark',
							width : 300
						}],
				comboEx : [{
							text : "变更状态",
							key : 'state',
							data : [{
										text : '未接收',
										value : 0
									}, {
										text : '已接收',
										value : 1
									}]
						}],
				param : {
					modelCode : "task"
					},
				// 扩展右键菜单
				menusEx : [
					{
						text : '查看',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=purchase_change_notice&action=init&perm=view&id="+ row.id
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800"
							);
						}
					},{
					text : '接收',
					icon : 'add',
					showMenuFn : function(row){
								if(row.state == '0'){
									return true;
								}
								return false;
							},
					action : function(row, rows, rowIds, grid) {
						$.get(
								"?model=purchase_change_notice&action=receive&id="
										+ row.id, function(data) {
									if (data == 1) {
										alert('接收成功！');
										show_page();
									} else {
										alert('接收失败！');
									}
								});

					}
				}],
				// 快速搜索
				searchitems : [{
							display : '变更单编号',
							name : 'changeNumb'
						}, {
							display : '申请单编号',
							name : 'basicNumb',
							isdefault : true
						}],
				// title : '客户信息',
				// 业务对象名称
				boName : '变更通知',
				// 默认搜索字段名
				sortname : "updateTime",
				// 默认搜索顺序
				sortorder : "DESC"
			});

		});