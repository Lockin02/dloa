$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'scrap[item]',
		title : '卡片信息',
		isAddOneRow : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		colModel : [{
			display : '卡片编号',
			name : 'assetCode',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					isFocusoutCheck : false,
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0'
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
									countAmount();
								}
							})(rowNum)
						}
					}
				});
			}
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
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0'
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
									countAmount();
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '资产Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '购置日期',
			name : 'buyDate',
			// type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '资产原值',
			name : 'origina',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '残值',
			name : 'salvage',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '净值',
			name : 'netValue',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '已提折旧',
			name : 'depreciation',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
			// }, {
			// display : '出售状态',
			// name : 'sellStatus',
			// value : '未出售',
			// readonly : true,
			// type : 'hidden'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
});
//提交表单时验证
function checkForm(thisVal){
	if(checkCard()){//验证卡片信息合法性
		if(thisVal == 'audit'){
			if (confirm("确定要提交财务部确认吗?")) {
				$("#form1").attr("action","?model=asset_disposal_scrap&action=add&actType=finance");
			}else{
				return false;
			}
		}else{
			$("#form1").attr("action","?model=asset_disposal_scrap&action=add");
		}
		$("#form1").submit();
	}
}