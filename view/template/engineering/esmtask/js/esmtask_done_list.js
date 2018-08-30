// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".esmtaskdoneGrid").yxgrid("reload");
};
$(function() {
			$(".esmtaskdoneGrid").yxgrid({

						model : 'engineering_esmtask_esmtask',
						action : 'donePageJson',
						title:'项目任务--已完成',
						showcheckbox : true,	//显示checkbox
						isToolBar : false,		//显示列表上方的工具栏

						//列信息
						colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '任务名称',
								name : 'name',
								sortable : true
							},{
								display : '所属项目',
								name : 'projectName',
								sortable : true
							},{
								display : '优先级',
								name : 'priority',
								sortable : true
							}, {
								display : '状态',
								name : 'status',
								datacode : 'XMRWZT',
								sortable : true
							},{
								display : '完成率',
								name : 'effortRate',
								sortable : true
							},{
								display : '偏差率',
								name : 'warpRate',
								sortable : true
							}, {
								display : '责任人',
								name : 'chargeName',
								sortable : true
							},{
								display : '最近更新时间',
								name : 'updateTime',
								sortable : true,
								width : 150
							}, {
								display : '计划完成时间',
								name : 'planEndDate',
								sortable : true
							},{
								display : '任务类型',
								name : 'taskType',
								sortable : true
							}
						],
						//扩展按钮
						buttonsEx : [],
						 //扩展右键
						 menusEx : [{
								text : '查看',
								icon : 'view',
								action : function(row) {

									showThickboxWin('?model=engineering_task_protask&action=taskTab&id='
											+ row.id
											+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
								}

							},{
								text : '填写日志',
								icon : 'add',
								action : function(row) {

									showThickboxWin('?model=.../.../...&action=....&id='
											+ row.id
											+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
								}

							  }],
						//快速搜索
						searchitems : [{
									display : '等级',
									name : 'name'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '等级',
						//默认搜索字段名
						sortname : "name",
						//默认搜索顺序
						sortorder : "ASC",
						//显示查看按钮
						isViewAction : false,
						//隐藏添加按钮
						isAddAction : false,
						//隐藏删除按钮
						isDelAction : false,
						//查看扩展信息
						toViewConfig : { action : 'toRead' },

						//修改扩展信息
						toEditConfig : { action : 'toEdit' }
					});

		});