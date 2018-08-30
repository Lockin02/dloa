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
			display : '��ͬ���(Դ�����)'
		},{
			name : 'docCode',
			display : '�������񵥺�',
			sortable : true,
			width : 130,
			process : function ($input ,rowData) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_task_producetask&action=toViewTab&id=" + rowData.id + "\",1)'>" + rowData.docCode + "</a>";
			}
		},{
			name : 'relDocName',
			display : '��ͬ����(Դ������)'
		},{
			name : 'relDocType',
			display : '��ͬ����(Դ������)'
		},{
			name : 'taskNum',
			display : '����'
		},{
			name : 'planNum',
			display : '���´�ƻ�����'
		}]
	});
});