$(function() {
	// 单选客户
	$("#customerGrid1").yxcombogrid_customer({
		hiddenId : 'customerId1',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					alert(data.Prov);
				},
				'row_click' : function() {
//					// var
//					// g=$("#customerGrid1").yxcombogrid_customer("getGrid");
//					// g.destroy();
//					// g.empty();
//					$("#customerGrid1").yxcombogrid_customer("remove");// 移除面板
//					// 重新构建下拉
//					$("#customerGrid1").yxcombogrid_contract({
//						hiddenId : 'customerId1',
//						gridOptions : {
//							showcheckbox : false,
//							event : {
//								'row_click' : function() {
//									$("#customerGrid1")
//											.yxcombogrid_contract("remove");// 移除面板
//									$("#customerGrid1").yxcombogrid_customer({
//												hiddenId : 'customerId1'
//											});
//									// 手动显示下拉
//									$("#customerGrid1")
//											.yxcombogrid_customer("showCombo");
//								}
//							}
//						}
//					});
//					// 手动显示下拉
//					$("#customerGrid1").yxcombogrid_contract("showCombo");
				},
				'row_rclick' : function() {
					// alert(222)
				}
			}
		}
	});

	// 单选产品
	$("#combotreeName1").yxcombotree({
				hiddenId : 'combotreeId1',
				treeOptions : {
					// checkable : true,
					event : {
						"node_click" : function(e, treeId, treeNode) {
							alert(treeId)
						},
						"node_change" : function(e, treeId, treeNode) {
							alert(treeId)
						}
					},
					url : "?model=stock_productinfo_producttype&action=getChildren"
				}
			});
	// 多选客户
	$("#customerGrid2").yxcombogrid_customer({
				hiddenId : 'customerId2'
			});
	// 多选产品
	$("#combotreeName2").yxcombotree({
				hiddenId : 'combotreeId2',
				treeOptions : {
					checkable : true,
					url : "?model=stock_productinfo_producttype&action=getChildren",
					event : {
						"node_click" : function(e, treeId, treeNode) {
							alert(treeId)
						},
						"node_change" : function(e, treeId, treeNode) {
							alert(123)
						}
					}
				}
			});

	// 单选客户
	$("#name1").yxcombotext({
				hiddenId : "id1",
				nameCol : 'Name',
				url : '?model=customer_customer_customer&action=pageJson',
				event : {
					"row_click" : function(e, data) {
						alert(data.Name)
					}
				}
			});
	// 多选客户
	$("#name2").yxcombotext({
				hiddenId : "id2",
				nameCol : 'Name',
				checkable : true,
				url : '?model=customer_customer_customer&action=pageJson',
				event : {
					"row_check" : function(e, data) {
						alert(data.Name)
					}
				}
			});

});