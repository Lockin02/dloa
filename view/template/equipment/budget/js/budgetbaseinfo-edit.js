$(document).ready(function() {
	renderingProduct();
	// 新增分类信息 选择物料类型
	$("#budgetTypeName").yxcombotree({
		hiddenId : 'budgetTypeId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
				},
				"node_change" : function(event, treeId, treeNode) {

				}
			},
			url : "?model=equipment_budget_budgetType&action=getTreeData"
		}
	});
	validate({
		"equName" : {
			required : true
		},
		"budgetTypeName" : {
			required : true
		},
		"quotedPrice" : {
			required : true
		},
		"useEndDate" : {
			required : true
		}
	});
})
//选择物料渲染
function renderingProduct() {
	//选择物料
	$("#equCode").yxcombogrid_product({// 绑定物料编号
		hiddenId : 'equId',
		nameCol : 'equCode',
		event : {
			'clear' : function() {
				clearInfo()
			}
		},
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					assignment(data)
				}
			}
		}
	});
	$("#equName").yxcombogrid_product({// 绑定物料名称
		hiddenId : 'equId',
		nameCol : 'equName',
		event : {
			'clear' : function() {
				clearInfo()
			}
		},
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					assignment(data)
				}
			}
		}
	});
}
//取消物料渲染
function removeProduct() {
	var flag = $("#equCode").yxcombogrid_product("getIsRender");
	if (flag) {
		$("#equCode").yxcombogrid_product("remove");
		$("#equCode").attr('readonly', false);
		$("#equName").yxcombogrid_product("remove");
		$("#equName").attr('readonly', false);
		clearInfo()
	} else {
		renderingProduct();
		clearInfo()
	}
}
//情况数据
function clearInfo() {
	$("#equName").val("");
	$("#equCode").val("");
//	$("#pattern").val("");
//	$("#unitName").val("");
//	$("#brand").val("");
//	$("#remark").val("");
//	$("#quotedPrice").val("");
//	$("#quotedPrice_v").val("");
//	$("#useEndDate").val("");
}
//数据赋值
function assignment(data) {
	$("#equCode").val(data.productCode);
	$("#equName").val(data.productName);
	$("#pattern").val(data.pattern);
	$("#unitName").val(data.unitName);
	$("#brand").val(data.brand);
	$("#remark").val(data.remark);
}
