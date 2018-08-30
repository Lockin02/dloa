var show_page = function(page) {
	$("#esmpkListGrid").yxeditgrid("reload");
};
$(function() {
	//缓存项目id
	var projectId = $("#projectId").val();
	//表格部分
	$("#esmpkListGrid").yxeditgrid({
		url: "?model=engineering_project_esmproject&action=PKInfoJson",
		param : {
			"projectId" : projectId
		},
		isAddAction : false,
		isDelAction : false,
		noCheckIdValue : 'noId',
		isOpButton : false,
		type : 'view',
		isAddOneRow : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				type : 'hidden'
			}, {
				name : 'projectCode',
				display : 'PK项目号',
				sortable : true,
				width : 140,
				process : function(v,row){
					if(row.id != 'noId'){
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ +"\",1," + row.id + ")'>" + v + "</a>";
					}else{
						return v;
					}
				}
			}, {
				name : 'projectName',
				display : '项目名称',
				width : 140
			}, {
				name : 'productLine',
				display : '产品线编号',
				type : 'hidden'
			}, {
				name : 'productLineName',
				display : '执行区域',
				width : 80
			}, {
				name : 'status',
				display : '状态编号',
				type : 'hidden'
			}, {
				name : 'statusName',
				display : '状态',
				width : 60
			}, {
				name : 'managerId',
				display : '项目经理id',
				type : 'hidden'
			}, {
				name : 'managerName',
				display : '项目经理',
				width : 80
			}, {
				name : 'planBeginDate',
				display : '预计开始日期',
				width : 80
			}, {
				name : 'planEndDate',
				display : '预计结束日期',
				width : 80
			}, {
				name : 'expectedDuration',
				display : '预计工期',
				width : 60
			}, {
				name : 'actBeginDate',
				display : '实际开始日期',
				width : 80
			}, {
				name : 'actEndDate',
				display : '实际结束日期',
				width : 80
			}, {
				name : 'actDuration',
				display : '实际工期',
				width : 60
			}, {
				name : 'budgetAll',
				display : '预算',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeAll',
				display : '决算',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}
		],
		event : {
			'reloadData' : function(e, g, data){
				if(data && data.length != 0){
                    var budgetAll = 0;
                    var feeAll = 0;
                    for(var i = 0; i < data.length; i++){
                        budgetAll = accAdd(budgetAll,data[i].budgetAll,2);
                        feeAll = accAdd(feeAll,data[i].feeAll,2);
                    }
                    $("#esmpkListGrid tbody").append("<tr class='tr_count'><td></td><td>合 计</td><td colspan='10'></td><td>"+moneyFormat2(budgetAll)+"</td><td>"+moneyFormat2(feeAll)+"</td></tr>");
				}else{
                    $("#esmpkListGrid tbody").append("<tr><td colspan='14'>-- 暂无相关记录 --</td></tr>");
                }
			}
		}
	});
});