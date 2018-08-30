$(document).ready(function() {
	renderingProduct();
	// ����������Ϣ ѡ����������
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
//ѡ��������Ⱦ
function renderingProduct() {
	//ѡ������
	$("#equCode").yxcombogrid_product({// �����ϱ��
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
	$("#equName").yxcombogrid_product({// ����������
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
//ȡ��������Ⱦ
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
//�������
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
//���ݸ�ֵ
function assignment(data) {
	$("#equCode").val(data.productCode);
	$("#equName").val(data.productName);
	$("#pattern").val(data.pattern);
	$("#unitName").val(data.unitName);
	$("#brand").val(data.brand);
	$("#remark").val(data.remark);
}
