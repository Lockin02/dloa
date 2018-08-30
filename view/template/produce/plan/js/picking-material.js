$(document).ready(function() {
	var data = eval("(" + $("#productJson").text() + ")");
	var productObj = $("#productItem");
	productObj.yxeditgrid({
		objName : 'picking[item]',
		data : data,
		colModel : [{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编码',
			name : 'productCode',
			process : function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_product({
					hiddenId : 'productItem_cmp_productId' + rowNum,
					gridOptions : {
						showcheckbox : false,
						event : {
							row_dblclick : function(e ,row ,data) {
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productCode").val(data.productCode);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"productName").val(data.productName);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"pattern").val(data.pattern);
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"unitName").val(data.unitName);

								//这里是先清空是为了防止后面异步获取前的数目显示出错
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"JSBC").html('');
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"KCSP").html('');
								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"SCC").html('');
								//获取旧设备仓、库存商品仓和生产仓数量
								$.ajax({
									type : 'POST',
									url : "?model=produce_plan_picking&action=getProductNum",
									data : {
										productCode : data.productCode
									},
									success : function (result) {
										var obj = eval("(" + result + ")");
										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"JSBC").html(obj.JSBC);
										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"KCSP").html(obj.KCSP);
										productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"SCC").html(obj.SCC);
									}
								});
							}
						}
					}
				});
			}
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyText',
			width : '25%',
			readonly : true
		},{
			display : '规格型号',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			width : '10%',
			readonly : true
		},{
			display : '单位',
			name : 'unitName',
			tclass : 'readOnlyTxtShort',
			width : '8%',
			readonly : true
		},{
			display : '旧设备仓',
			name : 'JSBC',
			type : 'statictext',
			width : '5%'
		},{
			display : '库存商品',
			name : 'KCSP',
			type : 'statictext',
			width : '5%'
		},{
			display : '生产仓',
			name : 'SCC',
			type : 'statictext',
			width : '5%'
		},{
			display : '申请数量',
			name : 'applyNum',
			validation : {
				custom : ['onlyNumber']
			},
			width : '5%'
		},{
			display : '计划领料日期',
			name : 'planDate',
			width : '10%',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '备注',
			name : 'remark',
			type : 'textarea',
			rows : 2,
			width : '20%'
		}]
	});
});

//设置从表计划领料日期
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

//直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=produce_plan_picking&action=add&actType=audit";
}