var show_page = function(page) {
	$("#changeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#changeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'purchasecontract',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'purchasecontract'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});
					
			
		});