var show_page = function(page) {
	$("#purchaseSignLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#purchaseSignLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'purchasesign',
							objId : $('#objId').val()
						},
						isRightMenu:false,
					    title : 'ǩ�ռ�¼',
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : 'ǩ����',
								name : 'changeManName',
								width : 200,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : 'ǩ��ʱ��',
								name : 'changeTime',
								width : 200,
								process : function(v, row) {
									if (row['tempId'] == $("#originalId").val()) {
										return "<font color='red'>" + v
												+ "</font>";
									}
									return v;
								}
							}, {
								display : '���ԭ��',
								name : 'changeReason',
								hide : true
							}, {
								display : '����ʱ��',
								name : 'ExaDT',
								hide : true
							}, {
								display : 'tempId',
								name : 'tempId',
								hide : true
							}],
						subGridOptions : {
							param : [{
										logObj : 'purchasesign'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});