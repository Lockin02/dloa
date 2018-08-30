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
				"orderMoney" : {
					required : true
				}
			});
  });
   //��Ա����
function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			tableClass : 'form_in_table',
			url : '?model=outsourcing_approval_persronRental&action=listJson',
			param : {
				dir : 'ASC',
				mainId :$("#applyId").val()
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
			}, {
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
				name : 'customerDeduct',
				display : '�ͻ��ۿ�',
				type : 'money',
				staticVal:'0.00',
				width : 80,
				event : {
					blur : function() {
						countPersonOut($(this).data("rowNum"));
	            		checkOrderMoney();
	            		checkDeductMoney();
					}
				}
			}, {
				name : 'examinDuduct',
				display : '���˿ۿ�',
				type : 'money',
				staticVal:'0.00',
				width : 80,
				event : {
					blur : function() {
						countPersonOut($(this).data("rowNum"));
	            		checkOrderMoney();
	            		checkDeductMoney();
					}
				}
			}, {
				name : 'rentalPrice',
				display : '�ϼ�(Ԫ)',
				width : 80,
//				type : 'money',
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
}

function personList(approvalId,verifyIds) {
	$("#itemTable").yxeditgrid('remove');
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			tableClass : 'form_in_table',
			url : '?model=outsourcing_account_persron&action=accountListJson',
			param : {
				dir : 'ASC',
				approvalId:approvalId,
				verifyIds:verifyIds
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
			}, {
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
				name : 'customerDeduct',
				display : '�ͻ��ۿ�',
				type : 'money',
				staticVal:'0.00',
				width : 80,
				event : {
					blur : function() {
						countPersonOut($(this).data("rowNum"));
	            		checkOrderMoney();
	            		checkDeductMoney();
					}
				}
			}, {
				name : 'examinDuduct',
				display : '���˿ۿ�',
				type : 'money',
				staticVal:'0.00',
				width : 80,
				event : {
					blur : function() {
						countPersonOut($(this).data("rowNum"));
	            		checkOrderMoney();
	            		checkDeductMoney();
					}
				}
			}, {
				name : 'rentalPrice',
				display : '�ϼ�(Ԫ)',
				width : 80,
//				type : 'money',
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
}
     //ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_account_basic&action=add&actType=staff";
}