$(document).ready(function() {
	$("#planItem").yxeditgrid({
		url : '?model=produce_plan_picking&action=listJsonProduct',
		param : {
			planId : $("#planId").val(),
			productId : $("#productId").val()
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type: 'hidden'
		},{
			name : 'docCode',
			display: '�������ϵ���',
			width : 120,
			process : function (v ,row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name : 'docStatus',
			display: '����״̬',
			process : function (v) {
				switch (v) {
					case '0' : return "δ�ύ";break;
					case '1' : return "������";break;
					case '2' : return "���";break;
					case '3' : return "���";break;
					default : return "--";
				}
			}
		},{
			name : 'docDate',
			display: '��������'
		},{
			name : 'relDocCode',
			display: 'Դ�����'
		},{
			name : 'relDocName',
			display: 'Դ������',
			width : 200
		},{
			name : 'relDocType',
			display: 'Դ������'
		},{
			name : 'createName',
			display: '������'
		},{
			name : 'remark',
			display: '��ע',
			width : 250
		}]
	});
});