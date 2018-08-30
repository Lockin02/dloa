var numArr = []; //����������ϵ������

$(document).ready(function() {
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName : 'picking[item]',
		url:'?model=produce_plan_produceplan&action=classifyByPickingMulti',
		param : {
			planId : $("#planId").val(),
			productId : $("#productId").val(),
			dir : 'ASC'
		},
		isAdd : false,
		event : {
			reloadData : function(event,g,data) {
				if(!data || data.length == 0){
					alert('û�п��´������');
					closeFun();
				}
			}
		},
		colModel : [{
			display : '�ƻ���Id',
			name : 'planId',
			type : 'hidden'
		},{
			display : '�ƻ������',
			name : 'planCode',
			tclass : 'readOnlyText',
			readonly : true
		},{
			display : 'Դ��Id',
			name : 'relDocId',
			type : 'hidden'
		},{
			display : 'Դ�����',
			name : 'relDocCode',
			type : 'hidden'
		},{
			display : 'Դ������',
			name : 'relDocName',
			type : 'hidden'
		},{
			display : 'Դ������',
			name : 'relDocType',
			type : 'hidden'
		},{
			display : 'Դ�����ͱ���',
			name : 'relDocTypeCode',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'applyDocId',
			type : 'hidden'
		},{
			display : '������ϸId',
			name : 'applyDocItemId',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'taskId',
			type : 'hidden'
		},{
			display : '���񵥱��',
			name : 'taskCode',
			type : 'hidden'
		},{
			display : '�ͻ�Id',
			name : 'customerId',
			type : 'hidden'
		},{
			display : '�ͻ�����',
			name : 'customerName',
			type : 'hidden'
		},{
			display : '���κ�',
			name : 'productionBatch',
			type : 'hidden'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ���',
			name : 'productCode',
			tclass : 'readOnlyText',
			width : '10%',
			readonly : true
//			process : function ($input) {
//				var rowNum = $input.data("rowNum");
//				$input.yxcombogrid_product({
//					hiddenId : 'productItem_cmp_productId' + rowNum,
//					gridOptions : {
//						showcheckbox : false,
//						event : {
//							row_dblclick : function(e ,row ,data) {
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);
//
//								//�������������Ϊ�˷�ֹ�����첽��ȡǰ����Ŀ��ʾ����
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"JSBC").html('');
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"KCSP").html('');
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"SCC").html('');
//								//��ȡ���豸�֡������Ʒ�ֺ�����������
//								$.ajax({
//									type : 'POST',
//									url : "?model=produce_plan_picking&action=getProductNum",
//									data : {
//										productCode : data.productCode
//									},
//									success : function (result) {
//										var obj = eval("(" + result + ")");
//										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"JSBC").html(obj.JSBC);
//										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"KCSP").html(obj.KCSP);
//										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"SCC").html(obj.SCC);
//									}
//								});
//							}
//						}
//					}
//				});
//			}
		},{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyText',
			width : '25%',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			width : '10%',
			readonly : true
		},{
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			width : '8%',
			readonly : true
		},{
			display : '���豸��',
			name : 'JSBC',
			type : 'statictext',
			width : '5%'
		},{
			display : '�����Ʒ',
			name : 'KCSP',
			type : 'statictext',
			width : '5%'
		},{
			display : '������',
			name : 'SCC',
			type : 'statictext',
			width : '5%'
		},{
			display : '��������',
			name : 'applyNum',
			validation : {
				custom : ['onlyNumber']
			},
			event : {
				blur : function(e){
					//��֤��������
					checkApplyNum($(this),$(this).data('grid'),$(this).data('rowNum'));
				}
			},
			width : '5%'
		},{
			display : '�������',
			name : 'maxNum',
			process:function($input,row){
				$input.val(row.applyNum);
			},
			type : 'hidden'
		},{
			display : '�ƻ���������',
			name : 'planDate',
			width : '10%',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '��ע',
			name : 'remark',
			type : 'textarea',
			rows : 2,
			width : '20%'
		}]
	});

//	if ($('#typeId').val() > 0) {
//		havaConfig();
		$('#num').change(function () {
			setApplyNum($(this).val());
		}).parent().show().prev().show();
//	}

	validate({
		"docTypeCode" : {
			required : true
		},
		"module" : {
			required : true
		}
	});
});

//��������
function havaConfig() {
	$.ajax({
		type : "POST",
		url : '?model=produce_apply_produceapply&action=statisticsListJson',
		data : {
			typeId : $('#typeId').val()
		},
		success : function(data) {
			if (data != 'false' && data) {
				data = eval("(" + data + ")");
				var num = 0;
				$("#productItem").yxeditgrid("removeAll" ,'true'); //���
				for (var i = 0 ;i < data.length ;i++) {
					$.ajax({
						type : "POST",
						url : '?model=produce_apply_produceapply&action=childrenListJson',
						data : {
							parentId : data[i].id,
							showNum : true
						},
						success : function(data2) {
							if (data2 != 'false' && data2) {
								data2 = eval("(" + data2 + ")");
								for (var j = 0 ;j < data2.length ;j++) {
									$("#productItem").yxeditgrid("addRow" ,num ,data2[j]);
									$("#productItem").yxeditgrid('getCmpByRowAndCol' ,num ,'applyNum').val(data2[j].total);
									numArr[num] = data2[j].total; //�洢������ϵ
									num++;
								}
							}
						}
					});
				}
			}
		}
	});
}

//���ôӱ�ƻ���������
function setPlanDate(e) {
	if (e.value != '') {
		var planDateObjs = $("#productItem").yxeditgrid('getCmpByCol' ,'planDate');
		planDateObjs.each(function (k ,v) {
			if (this.value == '') {
				$(this).val(e.value);
			}
		});
	}
}

//���ôӱ���������
function setApplyNum(num) {
	var applyNumObjs = $("#productItem").yxeditgrid('getCmpByCol' ,'applyNum');
	applyNumObjs.each(function (k ,v) {
//		$(this).val((parseInt(num) * parseInt(numArr[k])));
		$(this).val((parseInt(num)));
		return checkApplyNum($(this),$(this).data('grid'),$(this).data('rowNum'));
	});
}

//��֤��������
function checkApplyNum(obj,grid,rownum){
	var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
	var productCode = grid.getCmpByRowAndCol(rownum, 'productCode').val();
	if(obj.val() *1 <= 0){
		alert("���ϱ���Ϊ��" + productCode + "�������������������0");
		obj.val(maxNum);
		return false;
	}
	if(obj.val() *1 > maxNum *1){
		alert("���ϱ���Ϊ��" + productCode + "���������������ܴ���" + maxNum);
		obj.val(maxNum);
		return false;
	}
	return true;
}

//ֱ���ύ
function toSubmit(){
	document.getElementById('form1').action="?model=produce_plan_picking&action=add&actType=audit";
}

//�ύʱ��֤
function checkForm(){
	if($("#productItem").yxeditgrid('getCurShowRowNum') === 0){
		alert("�������ϲ���Ϊ��");
		return false;
	}
}