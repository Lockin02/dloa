var pageAttr = 'add';//����ҳ�������������Ⱦ����/��Ա������Ϣ
$(document).ready(function() {


	validate({
//				"outContractCode" : {
//					required : true
//				}
	});
	//����������
	outsourType();
});



   //��Ա����
function itemDetail() {
	var obj = $("#itemTable");
	if(obj.children().length == 0){
		obj.yxeditgrid({
			objName : 'basic[personList]',
			tableClass : 'form_in_table',
			colModel : [{
				name : 'personLevel',
				display : '��Ա����',
				type : "hidden"
			}, {
				name : 'personLevelName',
				display : '����',
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
				display : '����',
				width : 60,
				validation : {
					required : true
				}
			}, {
				name : 'suppId',
				display : '���������Ӧ��Id',
				type : "hidden"
			},{
				name : 'suppName',
				display : '���������Ӧ��',
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
				display : '���޿�ʼ����',
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
				display : '���޽�������',
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
				display : '����',
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
				display : '���������ɱ�����(Ԫ/��)',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'selfPrice',
				display : '���������ɱ�',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'outBudgetPrice',
				display : '�������(Ԫ/��)',
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
				display : '����۸�',
				width : 80,
				tclass : 'readOnlyTxtShort',
				readonly : true
			}, {
				name : 'skillsRequired',
				display : '��������Ҫ��',
				width : 120
			}, {
				name : 'remark',
				display : '��ע',
				width : 120
			}]
		});
		tableHead();
	}
}

   //ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_approval_basic&action=add&actType=staff";
}