var show_page = function(page) {
	$("#serviceChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#serviceChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'servicecontract',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'servicecontract'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});