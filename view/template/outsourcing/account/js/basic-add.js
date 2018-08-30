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
				"orderMoney" : {
					required : true
				}
			});
  });
   //人员租赁
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
			}, {
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
				name : 'customerDeduct',
				display : '客户扣款',
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
				display : '考核扣款',
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
				display : '合计(元)',
				width : 80,
//				type : 'money',
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
			}, {
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
				name : 'customerDeduct',
				display : '客户扣款',
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
				display : '考核扣款',
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
				display : '合计(元)',
				width : 80,
//				type : 'money',
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
}
     //直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_account_basic&action=add&actType=staff";
}