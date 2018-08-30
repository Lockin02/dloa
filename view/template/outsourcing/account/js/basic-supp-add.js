$(document).ready(function() {
	//变更外包类型
	outsourType();
	validate({
		"beginDate" : {
			required : true
		},
		"endDate" : {
			required : true
		},
		"projectCode" : {
			required : true
		}
	});

	$("#projectCode").yxcombogrid_esmproject({
		isDown : false,
		hiddenId : 'projectId',
		nameCol : 'projectCode',
		height : 250,
		isFocusoutCheck : true,
		openPageOptions : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'idArr' : $("#proIds").val()},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectId").val(data.id);
					$("#projectName").val(data.projectName);
					$("#projectTypeName").val(data.natureName);
					$("#projectType").val(data.nature);
					$("#projectAddress").val(data.place);
					$("#outContractCode").val(data.outContractCode);
					$("#projectManangerName").val(data.managerName);
					$("#projectManangerId").val(data.managerId);
					$("#saleManangerName").val(data.salesman);
					$("#saleManangerId").val(data.salesmanId);
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_workverify_verifyDetail&action=listJson",
						data: {
							suppVerifyId: $("#suppVerifyId").val(),
							projectId : $("#projectId").val(),
							settlementState : '0'
						},
						success: function(data) {
							if (data == 'false') {
								alert("单号中该工程项目已提交结算！");
								$("#projectCode").trigger('clear');
							}else {
								data = eval("(" + data + ")");
								$("#outsourcingName").val(data[0].outsourcingName);
								$("#outsourcing").val(data[0].outsourcing);
								$("#itemTable").empty();
								outsourType();
							}
						}
					});
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectId").val("");
				$("#projectName").val("");
				$("#projectCode").val("");
				$("#projectTypeName").val("");
				$("#projectType").val("");
				$("#projectAddress").val("");
				$("#outsourcingName").val("");
				$("#outsourcing").val("");
				$("#outContractCode").val("");
				$("#projectManangerName").val("");
				$("#projectManangerId").val("");
				$("#saleManangerName").val("");
				$("#saleManangerId").val("");
				$("#itemTable").empty();
				outsourType();
			}
		}
	});
});

//人员租赁
function itemDetail() {
	var obj = $("#itemTable");
	obj.yxeditgrid({
		objName : 'basic[personList]',
		tableClass : 'form_in_table',
		url : '?model=outsourcing_workverify_verifyDetail&action=suppVerifyListJson',
		param : {
			dir : 'ASC',
			suppVerifyId: $("#suppVerifyId").val(),
			projectId : $("#projectId").val(),
			outsourcing : 'HTWBFS-02'
		},
		realDel : true,
		event: {
            'removeRow': function() {
            	checkOrderMoney();
            	checkDeductMoney();
            }
        },
		colModel : [{
			name : 'personLevel',
			display : '人员级别',
			type : "hidden"
		}, {
			name : 'personLevelName',
			display : '级别',
			width : 60,
			readonly : true,
			type : "hidden"
		}, {
			name : 'pesonName',
			display : '姓名',
			width : 80,
			validation : {
				required : true
			}
		}, {
			name : 'beginDate',
			display : '开始日期',
			width : 80,
			event : {
				blur : function() {
					countDate('beginDate',$(this).data("rowNum"));
					countPersonOut($(this).data("rowNum"));
            		checkOrderMoney();
				}
			},
			type : 'date',
			validation : {
				required : true
			}
		}, {
			name : 'endDate',
			display : '结束日期',
			width : 80,
			event : {
				blur : function() {
					countDate('endDate',$(this).data("rowNum"));
					countPersonOut($(this).data("rowNum"));
            		checkOrderMoney();
				}
			},
			type : 'date',
			validation : {
				required : true
			}
		}, {
			name : 'totalDay',
			display : '工时(天)',
			width : 60,
            event : {
                blur : function() {
                		var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
						if (!re.test(this.value)) { //判断是否为数字
							if (isNaN(this.value)) {
								this.value ='';
							}else{
							}
						}
						countPersonOut($(this).data("rowNum"));
            			checkOrderMoney();
					}
            },
			tclass:'txtshort',
			validation : {
				required : true
			}
		},{
			name : 'outBudgetPrice',
			display : '工价(元/天)',
			width : 80,
			type : 'money',
			event : {
				blur : function() {
					countPersonOut($(this).data("rowNum"));
            		checkOrderMoney();
				}
			}
		}, {
			name : 'trafficMoney',
			display : '交通费(元)',
			width : 80,
			type : 'money',
			staticVal:'0.00',
			event : {
				blur : function() {
					countPersonOut($(this).data("rowNum"));
            		checkOrderMoney();
				}
			}
		},{
			name : 'otherMoney',
			display : '其他费用(元)',
			width : 80,
			type : 'money',
			staticVal:'0.00',
			event : {
				blur : function() {
					countPersonOut($(this).data("rowNum"));
            		checkOrderMoney();
				}
			}
		},{
			name : 'rentalPrice',
			display : '合计(元)',
			width : 80,
//			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true
		},{
			name : 'remark',
			display : '备注',
			width : 150
		}]
	});
	tableHead();

}
     //直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_account_basic&action=suppVerifyAdd&isSubmit=staff";
}