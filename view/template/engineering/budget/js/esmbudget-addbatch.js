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
					initTemplate(data.modelType);
				}
			}
		}
	});

	$("#importTable").yxeditgrid({
		objName : 'esmbudget[budgets]',
		url : '?model=engineering_budget_esmbudget&action=listJson',
		param : {
			'activityId' : $("#activityId").val(),
			'projectId' : $("#projectId").val()
		},
		event : {
			'reloadData' : function(e){
				//���������
				var thisGrid = $("#importTable");
				var colObj = thisGrid.yxeditgrid("getCmpByCol", "budgetName");
				if(colObj.length == 0){
//					alert(1);
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'Ԥ����ĿId',
			name : 'budgetId',
			type : 'hidden'
		}, {
			display : '�ϼ�id',
			name : 'parentId',
			type : 'hidden'
		}, {
			display : '���÷���',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxt'
		}, {
			display : 'Ԥ����Ŀ',
			name : 'budgetName',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_budgetdl({
					hiddenId : 'importTable_cmp_budgetId' + rowNum,
					nameCol : 'budgetName',
					width : 600,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'parentId').val(rowData.parentId);
									g.getCmpByRowAndCol(rowNum,'parentName').val(rowData.parentName);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			validation : {
				required : true
			},
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
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
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
			type : 'money',
			validation : {
				required : true
			}
		}, {
			display : '��ע˵��',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			type : 'hidden',
			value : $("#projectId").val()
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden',
			value : $("#projectCode").val()
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden',
			value : $("#projectName").val()
		}, {
			display : '����id',
			name : 'activityId',
			type : 'hidden',
			value : $("#activityId").val()
		}, {
			display : '��������',
			name : 'activityName',
			type : 'hidden',
			value : $("#activityName").val()
		}]
	})

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});
});
