var pageAttr = 'view';//����ҳ�������������Ⱦ����/��Ա������Ϣ
$(document).ready(function() {

	if($("#actType").val() == 'audit'){
		$("#buttonTable").hide();
	}
	//����������
	outsourType();
});

   //��Ա����
function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			url : '?model=outsourcing_approval_persronRental&action=listJson',
			param : {
				dir : 'ASC',
				mainId :$("#id").val()
			},
			type : 'view',
			tableClass : 'form_in_table',
			colModel : [{
				name : 'personLevel',
				display : '��Ա����',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '����',
				width : 60,
				readonly : true
			}, {
				name : 'pesonName',
				display : '����',
				width : 60
			}, {
				name : 'suppId',
				display : '���������Ӧ��Id',
				type : "hidden"
			},{
				name : 'suppName',
				display : '���������Ӧ��',
				width : 80
			}, {
				name : 'beginDate',
				display : '���޿�ʼ����',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '���޽�������',
				width : 80,
				type : 'date'
			}, {
				name : 'totalDay',
				display : '����',
				width : 60
			},{
				name : 'inBudgetPrice',
				display : '���������ɱ�����(Ԫ/��)',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'selfPrice',
				display : '���������ɱ�',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'outBudgetPrice',
				display : '�������(Ԫ/��)',
				width : 60
			},{
				name : 'rentalPrice',
				display : '����۸�',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'isAddContract',
				display : '���ɺ�ͬ',
				width : 50,
				process : function(v){
					if(v == 1){
						return '��';
					}else{
						return '��';
					}
				}
			}, {
				name : 'skillsRequired',
				display : '��������Ҫ��',
				width : 120
			}, {
				name : 'remark',
				display : '��ע',
				width : 120
			}]
		});
		tableHead();
	}
}