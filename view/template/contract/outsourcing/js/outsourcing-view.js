var pageAttr = 'view';//����ҳ�������������Ⱦ����/��Ա������Ϣ
$(function() {
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
	}
	//outsourType();
	//�ж��Ƿ���ڹر�ԭ�򣬲�����������
	if($("#closeReason").val() == ''){
		$("#closeReason").parents("tr:first").hide();
	}
});

function itemDetail(){
	$("#itemTable").yxeditgrid( {
		objName : 'outsourcing[items]',
		url : '?model=contract_personrental_personrental&action=listJson',
		type : 'view',
		param : {
			mainId : $("#pid").val()
		},
		colModel : [{
			name : 'personLevel',
			display : '��Ա����',
			type : "hidden"
		}, {
			name : 'personLevelName',
			display : '��Ա��������'
		}, {
			name : 'pesonName',
			display : '����'
		}, {
			name : 'beginDate',
			display : '���޿�ʼ����'
		}, {
			name : 'endDate',
			display : '���޽�������'
		}, {
			name : 'selfPrice',
			display : '�����������ɱ�',
			process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            }
		}, {
			name : 'rentalPrice',
			display : '����۸�',
			process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            }
		}, {
			name : 'skillsRequired',
			display : '��������Ҫ��'
		}, {
			name : 'interviewResults',
			display : '�������Խ��'
		}, {
			name : 'interviewName',
			display : '������Ա'
		}, {
			name : 'interviewId',
			display : '������Աid',
			type : "hidden"
		}, {
			name : 'remark',
			display : '��ע'
		}]
	});
	tableHead();
}
