$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapplyitem&action=editItemJson',
		type : 'view',
		title :'�ʼ�������ϸ',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
			name : 'productCode',
			display : '���ϱ��',
			width : 90
		}, {
			name : 'productName',
			display : '��������',
			width : 150
		}, {
			name : 'pattern',
			display : '�ͺ�',
			width : 100
		}, {
			name : 'unitName',
			display : '��λ',
			width : 50
		}, {
			name : 'checkTypeName',
			display : '�ʼ췽ʽ',
			width : 80
		}, {
			name : 'qualityNum',
			display : '��������',
			width : 80
		}, {
			name : 'assignNum',
			display : '���´�����',
			width : 80
		}, {
			name : 'standardNum',
			display : '�ϸ�����',
			width : 80
		},{
			name : 'status',
			display : '������',
			width : 80,
			process : function(v){
				switch(v){
					case "0" : return "�ʼ����";
					case "1" : return "���ִ���";
					case "2" : return "������";
					case "3" : return "�ʼ����";
					case "4" : return "δ����";
					default : return "";
				}
			}
		},{
			name : 'dealUserName',
			display : '������',
			width : 80
		},{
			name : 'dealTime',
			display : '����ʱ��',
			width : 140
		},{
			name : 'passReason',
			display : '����ԭ��',
			width : 140,
			align : 'left'
		}, {
			display : '���κ�',
			name : 'batchNum',
			width : 80
		}, {
			display : '���к�',
			name : 'serialName',
			process : function(v){
				if(v!=""){
					return "<a href='javascript:void(0);' onclick='showOpenWin(\"?model=stock_serialno_serialno&action=toViewFormat"+
						"&nos=" + v
						+"\",1,400,600)'>����鿴</a>";
				}else{
					return '��';
				}
			},
			width : 80
		}]
	});
})