//选择产品类型
$(function(){
     $("#parentName").yxcombotree({
     	hiddenId:'parentId',
 		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#parentCode").val(treeNode.code);
				}
			},
			url : "?model=engineering_baseinfo_resource&action=getChildren"
		}
	});

	// 动态添加预算项目下拉
	$("#budgetName").yxcombogrid_budget({
		hiddenId : 'budgetId',
		width : 600,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param  : {'status' : 0 ,'isLeaf' : 1},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#budgetCode").val(data.budgetCode);
				}
			}
		}
	});
});

$(function() {
	/**
	 * 验证信息
	 */
	validate({
		"resourceCode" : {
			required : true
		},
		"resourceName" : {
			required : true
		},
		"parentName" : {
			required : true

		}
	});
});
