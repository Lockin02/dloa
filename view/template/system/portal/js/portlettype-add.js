$(function() {
			validate({
						"typeName" : {
							required : true
						}
					});
			/* 选择区域负责人 */
			$("#parentName").yxcombotree({
						nameCol : 'typeName',
						hiddenId : 'parentId',
						treeOptions : {
							url : "?model=system_portal_portlettype&action=getChildren"
						}
					});

		});
