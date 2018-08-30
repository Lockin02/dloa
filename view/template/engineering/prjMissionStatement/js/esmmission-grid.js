var show_page = function(page) {
	$(".esmmissionGrid").yxgrid("reload");
};
$(function() {
			$(".esmmissionGrid").yxgrid({
						model : 'engineering_prjMissionStatement_esmmission',
						action : 'pageJson',
						title : '项目任务书',
						isToolBar : false,
						showcheckbox : false,
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'name',
									display : '任务书名称',
									sortable : true,
									width : '200'
								}, {
									name : 'startDate',
									display : '项目合同期(开始)',
									sortable : true
								}, {
									name : 'endDate',
									display : '项目合同期(结束)',
									sortable : true
								}, {
									name : 'status',
									display : '处理状态',
									sortable : true
								}, {
									name : 'executor',
									display : '处理人',
									sortable : true
								}, {
									name : 'executorTime',
//									display : '处理时间',
									display : '任务下达时间',
									width : '150',
									sortable : true
								}, {
									name : 'projectName',
									display : '关联项目名称',
									sortable : true,
									width : '300'
								}
						],
//						toAddConfig:{formWidth : 700,formHeight : 400},

						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action : function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=init"
										+ "&contractId="
										+ row.contractId
										+ "&id="
										+ row.id
										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 400 + "&width=" + 800);
//									showOpenWin("?model=engineering_prjMissionStatement_esmmission&action=init&perm=view&contractId="+row.contractId+"&id="+row.id);
								}else{
									alert("请选中一条数据");
								}
							}
						}
//						,{
//							text : '编辑',
//							icon : 'edit',
//							showMenuFn : function(row){
//								if(row.status == '待处理' || row.status == '未处理'){
//									return true;
//								}
//								return false;
//							},
//							action : function(row,rows,grid) {
//								if(row){
//									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=init"
//										+ "&id="
//										+ row.id
//										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 800);
////									showOpenWin("?model=engineering_prjMissionStatement_esmmission&action=init&id="+row.id+"&contractId="+row.contractId);
//								}else{
//									alert("请选中一条数据");
//								}
//							}
//						}
						,{
							text : '处理',
							icon : 'add',
							showMenuFn : function(row){
								if(row.status == '待处理' || row.status == '未处理'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									if(row.status == '待处理' || row.status == '未处理'){
									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=toDealIssue"
										+ "&contractId="
										+ row.contractId
//										+ "&missionId="
//										+ row.id
										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
										+ 400 + "&width=" + 800);
//										showOpenWin("?model=engineering_prjMissionStatement_esmmission&action=toDealIssue&contractId="+row.contractId+"&missionId="+row.id);
									}
								}
							}
						}
//						,
//						{
//							text : '反馈',
//							icon : 'view',
//							showMenuFn : function(row){
//								if(row.status == '待处理'){
//									return true;
//								}
//								return false;
//							},
//							action : function(row,rows,grid){
//								if(row){
//									if(row.status == '待处理'){
//									showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=dealIssue"
//										+ "&contractId="
//										+ row.id
//										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
//									}
//								}
//							}
//
//
//						}
						],
						//快速搜索
						searchitems : [
								{
									display : '任务书名称',
									name : 'name'
								},
								{
									display : '关联项目名称',
									name : 'projectName'
								}
								],
						// title : '客户信息',
						//业务对象名称
//						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "id",
						//默认搜索顺序
						sortorder : "ASC",
						//扩展按钮
						buttonsEx : [],
						isViewAction : false,
						isEditAction : false,

						isAddAction : false,
						isDelAction : false

					});
		});