$(document).ready(function() {

			// 新增分类信息 选择物料类型
			$("#parentName").yxcombotree({
						hiddenId : 'parentId',
						treeOptions : {
							event : {
								"node_click" : function(event, treeId, treeNode) {
								},
								"node_change" : function(event, treeId,
										treeNode) {

								}
							},
							url : "?model=goods_goods_goodstype&action=getTreeData"
						}
					});
			validate({
						"goodsType" : {
							required : true

						},
						"parentName" : {
							required : true

						}
					})
		})