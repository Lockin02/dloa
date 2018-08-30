// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#noticeGrid").yxgrid('reload');
};

$(function() {
			$("#noticeGrid").yxgrid({
				// 如果传入url，则用传入的url，否则使用model及action自动组装
				model : 'purchase_change_notice',
				action : "pageJsonPlan",
				isAddAction : false,
				isEditAction : false,
				isDelAction : false,
				isViewAction:false,
				showcheckbox:false,
				menuWidth:150,
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
							display : '采购计划Id',
							name : 'basicId',
							hide:true
						}
						, {
							display : '采购计划编号',
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
				param : { subject : '采购计划变更'},
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
					showMenuFn:function(row){
						if(row.state==0){
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
				},
				{
					text : '查看相关采购任务',
					icon : 'view',
					action:function(row,rows,grid){
						if(row){
							 	parent.location ="index1.php?model=purchase_task_basic&action=readTaskByPlanId&basicId="+ row.basicId
							 						+"&basicNum="+row.basicNumb;  //根据采购计划ID查看相关的任务
							}
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
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			});

		});