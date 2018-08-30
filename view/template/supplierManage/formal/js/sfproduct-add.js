$(document).ready(function() {
	if (perm != 'view') {
//		$("#productNames").yxcombotree({
//			// hiddenName : {
//			// 'id' : 'productIds'
//			// },
//			hiddenId : 'productIds',
//			treeOptions : {
//				checkable : true,
//				url : "?model=stock_productinfo_producttype&action=getChildren",
//				param : ["name", "id"]
//				// 获取节点数据时，必须的数据名称，例如：id、name
//				// checkType : {
//				// "Y" : "ps",//
//				// "N" : "ps"
//				// }
//			}
//		});
		$("#productNames").yxcombogrid_product({
				hiddenId : 'productIds',
				gridOptions : {
					showcheckbox : true,
					event : {
						'row_dblclick' : function(e, row, data) {
							//alert(data.Prov);
						},
						'row_click' : function(e,row,data) {
							// alert(123)
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});
	}
});