$(document).ready(function() {
	// ģ��ѡ����Ⱦ
	$("#templateName").yxcombogrid_expensemodel({
		hiddenId :  'templateId',
		isFocusoutCheck : false,
		height : 300,
		isShowButton : true,
		isClear : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					initBudget(data.modelType);
				}
			}
		}
	});

	//��ʼ��Ԥ��
	initBudget($("#templateId").val());

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});
});

//��ʼ��Ԥ��
function initBudget(templateId){
	var objGrid = $("#importTable");
	if(objGrid.children().length == 0){
		objGrid.yxeditgrid({
			objName : 'esmbudget[budgets]',
			url : '?model=finance_expense_customtemplate&action=getTemplateCostType',
			param : {
				'id' : templateId
			},
			event : {
				'reloadData' : function(e){
					initBudgetIds();
				},
				'removeRow' : function(e,rowNum){
					cancelCheck(rowNum);
				}
			},
			isAdd : false,
			colModel : [{
				display : '����',
				type : 'statictext',
				width : 30,
				html : '<img src="images/icon/icon105.gif"/>',
				event : {
					'click' : function(e) {
						var rowNum = $(this).data("rowNum");
						//���ø��Ʒ���
						copyBudget(rowNum);
					}
				}
			}, {
				display : 'Ԥ����ĿId',
				name : 'budgetId',
				type : 'hidden'
			}, {
				display : '�ϼ�id',
				name : 'parentId',
				type : 'hidden'
			}, {
				display : '���ô���',
				name : 'parentName',
				readonly : true,
				tclass : 'readOnlyTxt'
			}, {
				display : 'Ԥ��С��',
				name : 'budgetName',
				tclass : 'readOnlyTxt',
				readonly : true
			}, {
				display : '����',
				name : 'price',
				tclass : 'txtshort',
				type : 'money',
				event : {
					blur : function() {
						countAll($(this).data("rowNum"));
					}
				}
			}, {
				display : '����1',
				name : 'numberOne',
				tclass : 'txtshort',
				type : 'int',
				event : {
					blur : function() {
						countAll($(this).data("rowNum"));
					}
				}
			}, {
				display : '����2',
				name : 'numberTwo',
				tclass : 'txtshort',
				type : 'int',
				event : {
					blur : function() {
						countAll($(this).data("rowNum"));
					}
				}
			}, {
				display : '���',
				name : 'amount',
				tclass : 'txtshort',
				type : 'money'
			}, {
				display : '��ע˵��',
				name : 'remark',
				tclass : 'txt'
			}]
		})
	}else{
		//����Ѿ����ڿɱ༭���ֱ����Ⱦ
		objGrid.yxeditgrid("setParam",{
			'id' : templateId
		}).yxeditgrid("processData");
	}
}