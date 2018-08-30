$(function() {
			//验证
			validate({
						"portletName" : {
							required : true
						},
						"url" : {
							required : true
						}
					});
			/* 下拉类型 */
			$("#typeName").yxcombotree({
						nameCol : 'typeName',
						hiddenId : 'typeId',
						treeOptions : {
							url : "?model=system_portal_portlettype&action=getChildren"
						}
					});

		});
