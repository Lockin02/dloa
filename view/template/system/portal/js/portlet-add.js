$(function() {
			//��֤
			validate({
						"portletName" : {
							required : true
						},
						"url" : {
							required : true
						}
					});
			/* �������� */
			$("#typeName").yxcombotree({
						nameCol : 'typeName',
						hiddenId : 'typeId',
						treeOptions : {
							url : "?model=system_portal_portlettype&action=getChildren"
						}
					});

		});
