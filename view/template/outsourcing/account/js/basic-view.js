$(document).ready(function() {

	if($("#actType").val() == 'audit'){
		$("#buttonTable").hide();
	}
	//����������
	outsourType();

        });

function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			tableClass : 'form_in_table',
			url : '?model=outsourcing_account_persron&action=listJson',
			param : {
				dir : 'ASC',
				mainId :$("#id").val()
			},
			type : 'view',
		   event: {
	            'removeRow': function() {
	            	checkOrderMoney();
	            	checkDeductMoney();
	            }
	        },
			colModel : [{
				name : 'personLevel',
				display : '��Ա����',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '����',
				width : 60,
				readonly : true,
				type : "hidden"
			}, {
				name : 'pesonName',
				display : '����',
				width : 80,
				validation : {
					required : true
				}
			}, {
				name : 'beginDate',
				display : '��ʼ����',
				width : 80,
				type : 'date'
			}, {
				name : 'endDate',
				display : '��������',
				width : 80,
				type : 'date'
			}, {
				name : 'totalDay',
				display : '��ʱ(��)',
				width : 60,
				tclass:'txtshort',
				validation : {
					required : true
				}
			},{
				name : 'outBudgetPrice',
				display : '����(Ԫ/��)',
				width : 80,
				type : 'money'
			}, {
				name : 'trafficMoney',
				display : '��ͨ��(Ԫ)',
				width : 80,
				type : 'money'
			}, {
				name : 'otherMoney',
				display : '��������(Ԫ)',
				width : 80,
				type : 'money'
			},{
				name : 'customerDeduct',
				display : '�ͻ��ۿ�',
				type : 'money'
			}, {
				name : 'examinDuduct',
				display : '���˿ۿ�',
				type : 'money',
				width : 80
			}, {
				name : 'rentalPrice',
				display : '�ϼ�(Ԫ)',
				width : 80,
//				type : 'money',
				readonly : true
			},{
				name : 'remark',
				display : '��ע',
				width : 150
			}]
		});
		tableHead();
	}
}