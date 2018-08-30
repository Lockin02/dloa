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
			display : '设备名称',
			name : 'name',
			validation : {
				required : true
			},
			width : 200,
			tclass : 'txt'
		}, {
			display : '设备描述',
			name : 'description',
			validation : {
				required : true
			},
			width : 200,
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			validation : {
				required : true,
				custom : ['onlyNumber']
			},
			width : 60,
			tclass : 'txtshort'
		}, {
			display : '预计金额',
			name : 'expectAmount',
			width : 80,
			tclass : 'txtshort',
			type : 'money',
			// blur 失焦触发计算金额和数量的方法
			event : {
				blur : function() {
					countAmount();
				}
			}
		}, {
			display : '预计交货日期',
			name : 'dateHope',
			type : 'date',
			validation : {
				required : true
			},
			width : 80,
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '物料编号',
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
												alert("请不要选择相同的物料" );
												return false;
											}
										});
									}
									//如果已经重复了，就不能继续选择
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
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
//			validation : {
//				required : true
//			},
			width : 200,
			process : function($input, rowData) {
				// 变更页面不能更改物料
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
												alert("请不要选择相同的物料" );
												return false;
											}
										});
									}
									//如果已经重复了，就不能继续选择
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
			display : '物料ID',
			name : 'productId',
//			validation : {
//				required : true
//			}
			type : 'hidden'
		}, {
			display : '物料金额',
			name : 'productPrice',
			type : 'money',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 80
		}, {
			display : '物料品牌',
			name : 'brand',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 80
		}, {
			display : '规格型号',
			name : 'spec',
			readonly : 'readonly',
			tclass : 'readOnlyTxtItem',
			width : 200
		}]
	})
})

//提交确认
function confirmAudit() {
	if (confirm("你确定要提交需求吗?")) {
		$("#form1").attr("action",
				"?model=asset_require_requirement&action=add&actType=submit");
		$("#form1").submit();
	} else {
		return false;
	}
}

//label 控制
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
    if(target.value!='请输入收货地址。若不填收货地址，则默认珠海市地址。'&&target.value!=''){
    	$('#addressFlag').val('1');
    }
    target.parentNode.className = target.parentNode.className.replace(/\s+focus/, "");
}
