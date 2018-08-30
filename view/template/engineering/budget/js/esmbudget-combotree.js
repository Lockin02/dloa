$(document).ready(function() {
	var projectId = $("#projectId").val();
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#parentCode").val(treeNode.code);
				}
			},
			url : "?model=engineering_budget_esmbudget&action=getChildren&projectId=" + projectId
		}
	});
});

/**
 * ½ð¶î¼ÆËã
 */
function amountCount() {
	var numberOne = $("#numberOne").val();
	var numberTwo = $("#numberTwo").val();
	var price = $("#price").val();
	var sum = numberOne * price;
	if (numberTwo != null && numberTwo != "" && numberTwo != 0) {
		sum = sum * numberTwo;
	}
	setMoney('amount', sum);
}
