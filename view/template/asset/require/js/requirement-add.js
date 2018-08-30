$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		objName : 'requirement[items]',
		isAddOneRow : true,
		colModel : [ {
			display : '�豸����',
			name : 'name',
			validation : {
				required : true
			},
			width : 200,
			tclass : 'txt'
		}, {
			display : '�豸����',
			name : 'description',
			validation : {
				required : true
			},
			width : 200,
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			width : 60,
			tclass : 'txtshort'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'expectAmount',
			width : 80,
			tclass : 'txtshort',
			type : 'money',
			// blur ʧ������������������ķ���
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display : 'Ԥ�ƽ�������',
			name : 'dateHope',
			type : 'date',
			validation : {
				required : true
			},
			width : 80,
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txtmiddle',
			width : 120,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
//						closeCheck : true,
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId'
							+ rowNum,
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum, 'productName','');
							g.setRowColValue(rowNum, 'productPrice','');
							g.setRowColValue(rowNum, 'spec','');
							g.setRowColValue(rowNum, 'brand','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									var isExist = false;
									var productCodeArr = $("#itemTable").yxeditgrid("getCmpByCol","productCode");
									if(productCodeArr.length > 0){
										productCodeArr.each(function(){
											if(this.value == productData.productCode){
												isExist = true;
												alert("�벻Ҫѡ����ͬ������" );
												return false;
											}
										});
									}
									//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
									if(isExist){
										exit;
									}else{
										g.setRowColValue(rowNum, 'productName',productData.productName);
										g.setRowColValue(rowNum, 'productPrice',productData.priCost,true);
										g.setRowColValue(rowNum, 'spec',productData.pattern);
										g.setRowColValue(rowNum, 'brand',productData.brand);
									}
								}
							})(rowNum, rowData)
						}
					}
				});
			}
		}, {
			display : '��������',
			name : 'productName',
			tclass : 'txt',
//			validation : {
//				required : true
//			},
			width : 200,
			process : function($input, rowData) {
				// ���ҳ�治�ܸ�������
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
//						closeCheck : true,
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId'
							+ rowNum,
					nameCol : 'productName',
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum, 'productCode','');
							g.setRowColValue(rowNum, 'productPrice','');
							g.setRowColValue(rowNum, 'spec','');
							g.setRowColValue(rowNum, 'brand','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									var isExist = false;
									var productCodeArr = $("#itemTable").yxeditgrid("getCmpByCol","productCode");
									if(productCodeArr.length > 0){
										productCodeArr.each(function(){
											if(this.value == productData.productCode){
												isExist = true;
												alert("�벻Ҫѡ����ͬ������" );
												return false;
											}
										});
									}
									//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
									if(isExist){
										exit;
									}else{
										g.setRowColValue(rowNum, 'productCode',productData.productCode);
										g.setRowColValue(rowNum, 'productPrice',productData.priCost,true);
										g.setRowColValue(rowNum, 'spec',productData.pattern);
										g.setRowColValue(rowNum, 'brand',productData.brand);
									}
								}								
							})(rowNum, rowData)
						}
					}
				});
			}
		}, {
			display : '����ID',
			name : 'productId',
//			validation : {
//				required : true
//			}
			type : 'hidden'
		}, {
			display : '���Ͻ��',
			name : 'productPrice',
			type : 'money',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 80
		}, {
			display : '����Ʒ��',
			name : 'brand',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 80
		}, {
			display : '����ͺ�',
			name : 'spec',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 200
		}]
	})
})

//�ύȷ��
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ������?")) {
		$("#form1").attr("action",
				"?model=asset_require_requirement&action=add&actType=submit");
		$("#form1").submit();
	} else {
		return false;
	}
}

//label ����
function focusin(obj) {
    var target = document.getElementById(obj);
    var innerValueTemp = target.parentNode.children[0].innerHTML;
    var innerValue =  innerValueTemp.replace(/<br>/g,"");

    target.parentNode.children[0].style.display = "none";
    target.parentNode.className += " focus";
}
function focusout(obj) {
	var target = document.getElementById(obj);
    if(target.value === "") {
        target.parentNode.children[0].style.display = "inline";
    }
    if(target.value!='�������ջ���ַ���������ջ���ַ����Ĭ���麣�е�ַ��'&&target.value!=''){
    	$('#addressFlag').val('1');
    }
    target.parentNode.className = target.parentNode.className.replace(/\s+focus/, "");
}
