var show_page = function(page) {
	$("#taskChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#taskChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'purchasetask',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'purchasetask'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});