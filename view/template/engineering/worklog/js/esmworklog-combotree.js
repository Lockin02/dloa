$(document).ready(function(){
	// 项目编号下拉
	$("#projectName").yxcombogrid_project({
		hiddenId : 'projectId',
		nameCol : 'projectName',
		width : 600,
		isFocusoutCheck : false,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			action : 'myProjectListPageJson',
			colModel : [{
					display : '项目名称',
					name : 'projectName',
					process : function(v,row){
						if(row.isCanEdit == 1){
							return "<span style='color:blue'>" + v + "</span>";
						}else{
							return v;
						}
					}
				}, {
					display : '项目编号',
					name : 'projectCode',
					process : function(v,row){
						if(row.isCanEdit == 1){
							return "<span style='color:blue'>" + v + "</span>";
						}else{
							return v;
						}
					}
				}, {
					display : '所属办事处',
					name : 'officeName'
				}, {
					display : '项目经理',
					name : 'managerName'
				}, {
					display : '预计结束日期',
					name : 'planEndDate',
					width : 80
				}
			],
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#projectCode").val(data.projectCode);
					$("#planEndDate").val(data.planEndDate);

					//设置项目默认值
					$("#worklogItem").yxeditgrid('setColValue','projectCode',data.projectCode);
					$("#worklogItem").yxeditgrid('setColValue','projectName',data.projectName);
					$("#worklogItem").yxeditgrid('setColValue','projectId',data.id);

					//设置用车记录项目默认
					$("#importTable").yxeditgrid('setColValue','projectCode',data.projectCode);
					$("#importTable").yxeditgrid('setColValue','projectName',data.projectName);
					$("#importTable").yxeditgrid('setColValue','projectId',data.id);


					//设置测试卡使用记录项目默认
					$("#importCardTable").yxeditgrid('setColValue','projectCode',data.projectCode);
					$("#importCardTable").yxeditgrid('setColValue','projectName',data.projectName);
					$("#importCardTable").yxeditgrid('setColValue','projectId',data.id);
				}
			}
		}
	});

});