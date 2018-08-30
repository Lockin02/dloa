$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		param : {
			mainId : $("#id").val()
		},
		isAddOneRow : true,
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'name',
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '�豸����',
			name : 'description',
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : 'Ԥ�ƽ��',
			name : 'expectAmount',
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
			type:'date',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txtmiddle',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
//						closeCheck : true,
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
			process : function($input, rowData) {
				// ���ҳ�治�ܸ�������
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
//						closeCheck : true,
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
			width : 100
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
				"?model=asset_require_requirement&action=edit&actType=submit");
		$("#form1").submit();
	} else {
		return false;
	}
}