$(document).ready(function() {
	//����������
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
								alert("�����иù�����Ŀ���ύ���㣡");
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

//��Ա����
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
			display : '��������',
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
			display : '��ʱ(��)',
			width : 60,
            event : {
                blur : function() {
                		var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
						if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
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
			display : '����(Ԫ/��)',
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
			display : '��ͨ��(Ԫ)',
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
			display : '��������(Ԫ)',
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
			display : '�ϼ�(Ԫ)',
			width : 80,
//			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true
		},{
			name : 'remark',
			display : '��ע',
			width : 150
		}]
	});
	tableHead();

}
     //ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_account_basic&action=suppVerifyAdd&isSubmit=staff";
}