var numArr = []; //物料数量关系的数组

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
					alert('没有可下达的数量');
					closeFun();
				}
			}
		},
		colModel : [{
			display : '计划单Id',
			name : 'planId',
			type : 'hidden'
		},{
			display : '计划单编号',
			name : 'planCode',
			tclass : 'readOnlyText',
			readonly : true
		},{
			display : '源单Id',
			name : 'relDocId',
			type : 'hidden'
		},{
			display : '源单编号',
			name : 'relDocCode',
			type : 'hidden'
		},{
			display : '源单名称',
			name : 'relDocName',
			type : 'hidden'
		},{
			display : '源单类型',
			name : 'relDocType',
			type : 'hidden'
		},{
			display : '源单类型编码',
			name : 'relDocTypeCode',
			type : 'hidden'
		},{
			display : '需求单Id',
			name : 'applyDocId',
			type : 'hidden'
		},{
			display : '需求明细Id',
			name : 'applyDocItemId',
			type : 'hidden'
		},{
			display : '任务单Id',
			name : 'taskId',
			type : 'hidden'
		},{
			display : '任务单编号',
			name : 'taskCode',
			type : 'hidden'
		},{
			display : '客户Id',
			name : 'customerId',
			type : 'hidden'
		},{
			display : '客户名称',
			name : 'customerName',
			type : 'hidden'
		},{
			display : '批次号',
			name : 'productionBatch',
			type : 'hidden'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编码',
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
//								//这里是先清空是为了防止后面异步获取前的数目显示出错
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"JSBC").html('');
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"KCSP").html('');
//								productObj.yxeditgrid("getCmpByRowAndCol" ,rowNum ,"SCC").html('');
//								//获取旧设备仓、库存商品仓和生产仓数量
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
			event : {
				blur : function(e){
					//验证申请数量
					checkApplyNum($(this),$(this).data('grid'),$(this).data('rowNum'));
				}
			},
			width : '5%'
		},{
			display : '最大数量',
			name : 'maxNum',
			process:function($input,row){
				$input.val(row.applyNum);
			},
			type : 'hidden'
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

//有配置项
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
				$("#productItem").yxeditgrid("removeAll" ,'true'); //清空
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
									numArr[num] = data2[j].total; //存储数量关系
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

//设置从表申请数量
function setApplyNum(num) {
	var applyNumObjs = $("#productItem").yxeditgrid('getCmpByCol' ,'applyNum');
	applyNumObjs.each(function (k ,v) {
//		$(this).val((parseInt(num) * parseInt(numArr[k])));
		$(this).val((parseInt(num)));
		return checkApplyNum($(this),$(this).data('grid'),$(this).data('rowNum'));
	});
}

//验证申请数量
function checkApplyNum(obj,grid,rownum){
	var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
	var productCode = grid.getCmpByRowAndCol(rownum, 'productCode').val();
	if(obj.val() *1 <= 0){
		alert("物料编码为【" + productCode + "】的申请数量必须大于0");
		obj.val(maxNum);
		return false;
	}
	if(obj.val() *1 > maxNum *1){
		alert("物料编码为【" + productCode + "】的申请数量不能大于" + maxNum);
		obj.val(maxNum);
		return false;
	}
	return true;
}

//直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=produce_plan_picking&action=add&actType=audit";
}

//提交时验证
function checkForm(){
	if($("#productItem").yxeditgrid('getCurShowRowNum') === 0){
		alert("申请领料不能为空");
		return false;
	}
}