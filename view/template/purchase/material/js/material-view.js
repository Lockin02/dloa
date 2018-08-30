$(document).ready(function() {

	$("#itemTable").yxeditgrid({
		objName : 'material[materialequ]',
		bodyAlign:'center',
		url : '?model=purchase_material_materialequ&action=listJson',
		title : 'Э�����ϸ',
		param : {
			parentId :$("#id").val()
		},
		type : 'view',
		colModel : [{
			name : 'lowerNum',
			display : '��������',
			width : 70,
			process : function (e) {
				if(e == 0){
				   return "<span style='color:red'>-</span>";
				}else {
					return e;
				}
			}
		}, {
			name : 'ceilingNum',
			display : '��������',
			width : 70,
			process : function (e) {
				if(e == 0){
				   return "<span style='color:red'>-</span>";
				}else {
					return e;
				}
			}
		}, {
			name : 'taxPrice',
			display : '����',
			width : 70,
			process : function(v){
				return moneyFormat2(v ,6);
			}
		}, {
			name : 'startValidDate',
			display : '��ʼ��Ч��',
			type : 'date',
			width : 70
		}, {
			name : 'validDate',
			display : '������Ч��',
			width : 70
		}, {
			name : 'suppName',
			display : '��Ӧ������',
			width : 180
		}, {
			name : 'isEffective',
			display : '�Ƿ���Ч',
			width : 20,
			process : function (e) {
				if(e == "on"){
				   return "<span style='color:blue'>��</span>";
				}else{
				   return "<span style='color:red'>��</span>";
				}
			}
		}, {
			name : 'giveCondition',
			display : '��������',
			width : 150
		}, {
			name : 'remark',
			display : '��ע',
			width : 150
		}]
	});

})