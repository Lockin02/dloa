$(function() {
	// ��ѡ�ͻ�
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
//					$("#customerGrid1").yxcombogrid_customer("remove");// �Ƴ����
//					// ���¹�������
//					$("#customerGrid1").yxcombogrid_contract({
//						hiddenId : 'customerId1',
//						gridOptions : {
//							showcheckbox : false,
//							event : {
//								'row_click' : function() {
//									$("#customerGrid1")
//											.yxcombogrid_contract("remove");// �Ƴ����
//									$("#customerGrid1").yxcombogrid_customer({
//												hiddenId : 'customerId1'
//											});
//									// �ֶ���ʾ����
//									$("#customerGrid1")
//											.yxcombogrid_customer("showCombo");
//								}
//							}
//						}
//					});
//					// �ֶ���ʾ����
//					$("#customerGrid1").yxcombogrid_contract("showCombo");
				},
				'row_rclick' : function() {
					// alert(222)
				}
			}
		}
	});

	// ��ѡ��Ʒ
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
	// ��ѡ�ͻ�
	$("#customerGrid2").yxcombogrid_customer({
				hiddenId : 'customerId2'
			});
	// ��ѡ��Ʒ
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

	// ��ѡ�ͻ�
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
	// ��ѡ�ͻ�
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