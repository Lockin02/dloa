var show_page = function(page) {
	$("#statusreportGrid").yxgrid("reload");
};

$(function() {
	$("#statusreportGrid").yxgrid({
		model : 'engineering_project_statusreport',
		action : 'jsonForProject',
		title : '项目周报',
		param : { "projectId" : $("#projectId").val() },
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		usepager : false,
		sortname : 'weekNo',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				hide : true
			}, {
				name : 'projectId',
				display : '项目id',
				hide : true
			}, {
				name : '',
				display : '',
				sortable : true,
				align :'center',
				width : 40,
				process : function(v,row){
					if(row.ExaStatus == '完成'){
						return "<img src='images/icon/cicle_green.png'/>";
					}else if(row.ExaStatus == '部门审批'){
						return "<img src='images/icon/cicle_blue.png'/>";
					}else{
						return "<img src='images/icon/cicle_grey.png'/>";
					}
				}
			}, {
				name : 'weekNo',
				display : '周次',
				sortable : true,
				width : 80
			}, {
				name : 'handupDate',
				display : '汇报日期',
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_statusreport&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\",1)'>" + v + "</a>";
				}
			}, {
				name : 'beginDate',
				display : '开始日期'
			}, {
				name : 'endDate',
				display : '结束日期'
			}, {
				name : 'projectProcess',
				display : '项目进度',
				process : function(v){
					if(v != ""){
						return v + " %";
					}
				}
			}, {
				name : 'createName',
				display : '提交人',
				hide : true
			}, {
				name : 'status',
				display : '报告状态',
				datacode : 'XMZTBG',
				hide : true,
				width : 80
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				width : 80
			}, {
				name : 'confirmName',
				display : '审批人'
			}, {
				name : 'ExaDT',
				display : '审批日期',
				hide : true
			}, {
				name : 'confirmDate',
				display : '审批日期'
			}, {
				name : 'createTime',
				display : '创建时间',
				width : 140
			}
		],
		toViewConfig : {
			showMenuFn : function(row) {
				if (row.id *1 == row.id) {
					return true;
				}
				return false;
			},
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				if(rowData.id *1 == rowData.id){
					showModalWin("?model=engineering_project_statusreport&action=toView&id=" + rowData[p.keyField] ,1);
				}
			}
		}
	});
});