var show_page = function(page) {
	$("#rdprojectChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#rdprojectChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'rdproject',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'rdproject'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});