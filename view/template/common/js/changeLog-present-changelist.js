var show_page = function(page) {
	$("#presentChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#presentChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'present',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'present'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});