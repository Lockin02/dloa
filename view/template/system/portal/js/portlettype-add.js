$(function() {
			validate({
						"typeName" : {
							required : true
						}
					});
			/* ѡ���������� */
			$("#parentName").yxcombotree({
						nameCol : 'typeName',
						hiddenId : 'parentId',
						treeOptions : {
							url : "?model=system_portal_portlettype&action=getChildren"
						}
					});

		});
