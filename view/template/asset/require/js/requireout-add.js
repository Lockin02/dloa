$(document).ready(function() {
	//主表验证如果为空，从表无法进行验证
	validate({
		"applyDate" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		objName : 'requireout[items]',
		title : '卡片信息',
		isAddOneRow : false,
		colModel : [{
			display : '资产编号',
			name : 'assetCode',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					isFocusoutCheck : false,
					hiddenId : 'itemTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'assetId','');
							g.setRowColValue(rowNum,'assetName','');
							g.setRowColValue(rowNum,'salvage','');
						}
					},
					gridOptions : {
						param : {//只显示闲置的卡片
							'useStatusCode' : 'SYZT-XZ'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("请不要选择相同的资产.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			},
			width : 120
		}, {
			display : '资产名称',
			name : 'assetName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					isFocusoutCheck : false,
					hiddenId : 'itemTable_cmp_assetId' + rowNum,
					nameCol : 'assetName',
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'assetId','');
							g.setRowColValue(rowNum,'assetCode','');
							g.setRowColValue(rowNum,'salvage','');
						}
					},
					gridOptions : {
						param : {//只显示闲置的卡片
							'useStatusCode' : 'SYZT-XZ'
						},
						searchId : '',// 按此id值进行搜索过滤
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("请不要选择相同的资产.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			},
			width : 100
		}, {
			display : '资产Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '资产残值',
			name : 'salvage',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txtmiddle',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'productId','');
							g.setRowColValue(rowNum,'productName','');
							g.setRowColValue(rowNum,'spec','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.getCmpByRowAndCol(rowNum, 'productName').val(productData.productName);
									g.getCmpByRowAndCol(rowNum, 'spec').val(productData.pattern);
								}
							})(rowNum, rowData)
						}
					}
				});
			},
			width : 120
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					nameCol : 'productName',
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'productId','');
							g.setRowColValue(rowNum,'productCode','');
							g.setRowColValue(rowNum,'spec','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.getCmpByRowAndCol(rowNum, 'productCode').val(productData.productCode);
									g.getCmpByRowAndCol(rowNum, 'spec').val(productData.pattern);
								}								
							})(rowNum, rowData)
						}
					}
				});
			},
			width : 100
		}, {
			display : '物料id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '规格型号',
			name : 'spec',
			width : 120
		}, {
			display : '数量',
			name : 'number',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 120
		}]
	});
	//加载选择卡片信息按钮
	$("#itemTable").find("tr:first td").append("<input type='button' value='选择卡片信息' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
});
//选择卡片信息
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=requireout",1,500,900);
}
//设置卡片内容
function setDatas(rows){
	var objGrid = $("#itemTable");
	for(var i = 0; i < rows.length ; i++){
		//判断卡片编码是否已存在
		var assetIdArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetIdArr.length > 0){
			assetIdArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("请不要选择相同的资产" );
					return false;
				}
			});
		}
		//如果已经重复了，就不能继续选择
		if(isExist){
			return false;
		}
		//重新获取行数
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//新增行
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage,true);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productId",rows[i].productId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productCode",rows[i].productCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productName",rows[i].productName);
		//数量设置为1，1张卡片对应1个物料
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"number",1);
	}
}
//选择卡片后自动带出物料信息
function selectAssetFn(g, rowNum, rowData) {
	g.setRowColValue(rowNum,'assetId',rowData.id);
	g.setRowColValue(rowNum,'assetCode',rowData.assetCode);
	g.setRowColValue(rowNum,'assetName',rowData.assetName);
	g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
	g.setRowColValue(rowNum,'spec',rowData.spec);
	g.setRowColValue(rowNum,'productId',rowData.productId);
	g.setRowColValue(rowNum,'productCode',rowData.productCode);
	g.setRowColValue(rowNum,'productName',rowData.productName);
	//数量设置为1，1张卡片对应1个物料
	g.setRowColValue(rowNum,'number',1);
}
//保存/审核确认
function confirmSubmit(type) {
	if(type == 'audit'){
		if (confirm("你确定要提交需求吗?")) {
			$("#form1").attr("action",
					"?model=asset_require_requireout&action=add&actType=audit");
			$("#form1").submit();
		} else {
			return false;
		}
	}else{
		$("#form1").attr("action",
		"?model=asset_require_requireout&action=add");
		$("#form1").submit();
	}
}

