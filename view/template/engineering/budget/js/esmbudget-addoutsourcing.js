$(document).ready(function() {

	//��ʼ��Ԥ��
	initBudget();

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});
});

//��ʼ��Ԥ��
function initBudget(){
	var parentId = $("#parentId").val();
	var parentName = $("#parentName").val();

	$("#importTable").yxeditgrid({
		objName : 'esmbudget[budgets]',
		event : {
			'reloadData' : function(e){

			}
		},
		colModel : [{
			display : '�ϼ�id',
			name : 'parentId',
			type : 'hidden',
			value : parentId
		}, {
			display : '���ô���',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxt',
			value : parentName
		}, {
			display : 'Ԥ����ĿId',
			name : 'budgetId',
			type : 'hidden'
		}, {
			display : 'Ԥ��С��',
			name : 'budgetName',
			validation : {
				required : true
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			},
			width : 80
		}, {
			display : '����1',
			name : 'numberOne',
			tclass : 'txtshort',
			type : 'int',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			},
			width : 80
		}, {
			display : '����2',
			name : 'numberTwo',
			tclass : 'txtshort',
			type : 'int',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			},
			width : 80
		}, {
			display : '���',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			width : 80
		}, {
			display : '��ע˵��',
			name : 'remark',
			tclass : 'txt'
		}]
	})
}