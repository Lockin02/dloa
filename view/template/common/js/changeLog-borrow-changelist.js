var show_page = function(page) {
	$("#borrowChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#borrowChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'borrow',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'borrow'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});