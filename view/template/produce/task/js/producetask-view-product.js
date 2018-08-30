$(document).ready(function() {

	var applyObj = $("#applyInfo");
	applyObj.yxeditgrid({
		url : '?model=produce_task_producetask&action=listJsonProduct',
		param : {
			productId : $("#productId").val()
		},
		type : 'view',
		colModel : [{
			name : 'relDocCode',
			display : '合同编号(源单编号)'
		},{
			name : 'docCode',
			display : '生产任务单号',
			sortable : true,
			width : 130,
			process : function ($input ,rowData) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_task_producetask&action=toViewTab&id=" + rowData.id + "\",1)'>" + rowData.docCode + "</a>";
			}
		},{
			name : 'relDocName',
			display : '合同名称(源单名称)'
		},{
			name : 'relDocType',
			display : '合同类型(源单类型)'
		},{
			name : 'taskNum',
			display : '数量'
		},{
			name : 'planNum',
			display : '已下达计划数量'
		}]
	});
});