$(document).ready(function() {
	//初始化预算
	initBudget();

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});

//初始化预算
function initBudget(){
	var parentId = $("#parentId").val();
	var parentName = $("#parentName").val();

	$("#importTable").yxeditgrid({
		objName : 'esmbudget[budgets]',
		colModel : [{
			display : '上级id',
			name : 'parentId',
			type : 'hidden'
		}, {
			display : '费用大类',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxt',
			type : 'hidden'
		}, {
			display : '预算项目Id',
			name : 'budgetId',
			type : 'hidden'
		}, {
			display : '级别',
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
			display : '数量',
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
			display : '加入项目',
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
			display : '离开项目',
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
			display : '天数',
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
            display : '单价',
            name : 'price',
            type : 'hidden'
        }, {
            display : '计量系数',
            name : 'coefficient',
            type : 'hidden'
        }, {
            display : '单价自定义',
            name : 'customPrice',
            type : 'hidden'
        }, {
            display : '单价',
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
                            alert('输入不正确');
                            $(this).val('');
                        }
                    }
                }
            },
            validation : {
                required : true
            }
        }, {
			display : '预算人天',
			name : 'budgetDay',
			tclass : 'readOnlyTxtShort',
			type : 'int',
			readonly : true,
			width : 80
		}, {
			display : '预算成本天数',
			name : 'budgetPeople',
			type : 'hidden'
		}, {
			display : '预算成本',
			name : 'amount',
			tclass : 'readOnlyTxtShort',
			readonly : true,
			width : 80
		}, {
			display : '备注说明',
			name : 'remark',
			tclass : 'txt'
		}]
	});
}