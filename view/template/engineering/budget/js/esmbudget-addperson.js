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
		colModel : [{
			display : '�ϼ�id',
			name : 'parentId',
			type : 'hidden'
		}, {
			display : '���ô���',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxt',
			type : 'hidden'
		}, {
			display : 'Ԥ����ĿId',
			name : 'budgetId',
			type : 'hidden'
		}, {
			display : '����',
			name : 'budgetName',
			readonly : true,
			process : function($input) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_eperson({
					hiddenId : 'importTable_cmp_budgetId' + rowNum,
					width : 600,
					height : 300,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum,'parentId').val(parentId);
									g.getCmpByRowAndCol(rowNum,'parentName').val(parentName);
									g.getCmpByRowAndCol(rowNum,'price').val(rowData.price);
									g.getCmpByRowAndCol(rowNum,'coefficient').val(rowData.coefficient);
                                    g.getCmpByRowAndCol(rowNum,'customPrice').val(rowData.customPrice);
                                    if(rowData.customPrice == "1"){
                                        g.getCmpByRowAndCol(rowNum,'priceShow').val('').attr('readonly',false).
                                            removeClass('readOnlyTxtShort').addClass('txtshort');
                                    }else{
                                        g.getCmpByRowAndCol(rowNum,'priceShow').val('******').attr('readonly',true).
                                            removeClass('txtshort').addClass('readOnlyTxtShort');
                                    }
								}
							})(rowNum)
						}
					}
				});
			},
			validation : {
				required : true
			}
		}, {
			display : '����',
			name : 'numberOne',
            width : 70,
			type : 'int',
			event : {
				blur : function() {
					countPerson($(this).data("rowNum"));
				}
			},
			validation : {
				required : true
			}
		}, {
			display : '������Ŀ',
			name : 'planBeginDate',
			tclass : 'txtshort',
			type : 'date',
			event : {
				blur : function() {
					countDate('planBeginDate',$(this).data("rowNum"));
					countPerson($(this).data("rowNum"));
				}
			},
			width : 80,
			validation : {
				required : true
			}
		}, {
			display : '�뿪��Ŀ',
			name : 'planEndDate',
			tclass : 'txtshort',
			type : 'date',
			event : {
				blur : function() {
					countDate('planEndDate',$(this).data("rowNum"));
					countPerson($(this).data("rowNum"));
				}
			},
			width : 80,
			validation : {
				required : true
			}
		}, {
			display : '����',
			name : 'numberTwo',
            width : 70,
			type : 'int',
			event : {
				blur : function() {
					countPerson($(this).data("rowNum"));
				}
			},
			validation : {
				required : true ,
				custom : ['onlyNumber']
			}
		}, {
            display : '����',
            name : 'price',
            type : 'hidden'
        }, {
            display : '����ϵ��',
            name : 'coefficient',
            type : 'hidden'
        }, {
            display : '�����Զ���',
            name : 'customPrice',
            type : 'hidden'
        }, {
            display : '����',
            name : 'priceShow',
            tclass : 'readOnlyTxtShort',
            readonly : true,
            width : 80,
            event : {
                blur : function() {
                    var rowNum = $(this).data("rowNum");
                    var g = $(this).data("grid");
                    if(g.getCmpByRowAndCol(rowNum,'customPrice').val() == "1"){
                        if(checkMoney($(this).val()) == true){
                            g.getCmpByRowAndCol(rowNum,'price').val($(this).val());
                            countPerson($(this).data("rowNum"));
                        }else{
                            alert('���벻��ȷ');
                            $(this).val('');
                        }
                    }
                }
            },
            validation : {
                required : true
            }
        }, {
			display : 'Ԥ������',
			name : 'budgetDay',
			tclass : 'readOnlyTxtShort',
			type : 'int',
			readonly : true,
			width : 80
		}, {
			display : 'Ԥ��ɱ�����',
			name : 'budgetPeople',
			type : 'hidden'
		}, {
			display : 'Ԥ��ɱ�',
			name : 'amount',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 80
		}, {
			display : '��ע˵��',
			name : 'remark',
			tclass : 'txt'
		}]
	});
}