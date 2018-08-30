var pageAttr = 'add';//配置页面操作，用于渲染整包/人员租赁信息
$(document).ready(function() {


	validate({
//				"outContractCode" : {
//					required : true
//				}
	});
	//变更外包类型
	outsourType();
});



   //人员租赁
function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			tableClass : 'form_in_table',
			colModel : [{
				name : 'personLevel',
				display : '人员级别',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '级别',
				width : 60,
				readonly : true,
				process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_eperson({
						hiddenId : 'itemTable_cmp_personLevel' + rowNum,
						width : 600,
						height : 300,
						gridOptions : {
							showcheckbox : false,
							event : {
								row_dblclick : (function(rowNum) {
									return function(e, row, rowData) {
										g.getCmpByRowAndCol(rowNum,'personLevel').val(rowData.id);
										g.getCmpByRowAndCol(rowNum,'inBudgetPrice').val(rowData.price);
										$.ajax({
											type: "POST",
											url: "?model=outsourcing_outsourcing_person&action=selectPersonnel",
											async: false,
											data: {"applyId" : $("#applyId").val(),
													"riskCode":rowData.personLevel},
											success: function(data){
													if(data){
														var dataObj = eval("(" + data +")");
														g.getCmpByRowAndCol(rowNum,'skillsRequired').val(dataObj.skill);
													}
												}
										});
										countPerson(rowNum);
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
				name : 'pesonName',
				display : '姓名',
				width : 60,
				validation : {
					required : true
				}
			}, {
				name : 'suppId',
				display : '归属外包供应商Id',
				type : "hidden"
			},{
				name : 'suppName',
				display : '归属外包供应商',
				width : 80,
				process : function($input, rowData) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_outsupplier({
						hiddenId : 'itemTable_cmp_suppId' + rowNum,
						width : 600,
						height : 300,
						isFocusoutCheck : false,
						gridOptions : {
							showcheckbox : false
						}
					});
				},
				validation : {
					required : true
				}
			}, {
				name : 'beginDate',
				display : '租赁开始日期',
				width : 80,
				event : {
					blur : function() {
						countDate('beginDate',$(this).data("rowNum"));
						countPerson($(this).data("rowNum"));
						countPersonOut($(this).data("rowNum"));
					}
				},
				type : 'date',
				validation : {
					required : true
				}
			}, {
				name : 'endDate',
				display : '租赁结束日期',
				width : 80,
				event : {
					blur : function() {
						countDate('endDate',$(this).data("rowNum"));
						countPerson($(this).data("rowNum"));
						countPersonOut($(this).data("rowNum"));
					}
				},
				type : 'date',
				validation : {
					required : true
				}
			}, {
				name : 'totalDay',
				display : '天数',
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
							countPerson($(this).data("rowNum"));
							countPersonOut($(this).data("rowNum"));
						}
                },
				tclass:'txtshort',
				validation : {
					required : true
				}
			},{
				name : 'inBudgetPrice',
				display : '服务人力成本单价(元/天)',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'selfPrice',
				display : '服务人力成本',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'outBudgetPrice',
				display : '外包单价(元/天)',
				width : 60,
				event : {
					blur : function() {
						countPersonOut($(this).data("rowNum"));
					}
				},
				type : 'money',
				validation : {
					required : true
				}
			},{
				name : 'rentalPrice',
				display : '外包价格',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'skillsRequired',
				display : '工作技能要求',
				width : 120
			}, {
				name : 'remark',
				display : '备注',
				width : 120
			}]
		});
		tableHead();
	}
}

   //直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_approval_basic&action=add&actType=staff";
}