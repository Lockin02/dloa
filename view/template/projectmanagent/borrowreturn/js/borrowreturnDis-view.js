$(function() {
	// ��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		url:'?model=projectmanagent_borrowreturn_borrowreturnDisequ&action=listJson',
		tableClass : 'form_in_table',
		type:'view',
		title : '�����嵥',
		param:{
        	'disposeId' : $("#id").val()
        },
		colModel : [{
			display : '���ϱ��',
			name : 'productNo'
		},{
			display : '��������',
			name : 'productName'
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '���黹����',
			name : 'disposeNum',
			width : 80
		}, {
			display : '�ѹ黹����',
			name : 'backNum',
			width : 80
		}, {
			display : '����������',
			name : 'outNum',
			width : 80
		}, {
			display : '�ѳ�������',
			name : 'executedNum',
			width : 80
		}, {
			name : 'serialName',
			display : '���к�'
		}]
	});

	//��ʾ�ʼ����
	$("#showQualityReport").showQualityDetail({
		param : {
			"objId" : $("#qualityObjId").val(),
			"objType" : 'ZJSQYDGH'
		}
	});
});