var show_page = function(page) {
	$("#rentalChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#rentalChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'rentalcontract',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'rentalcontract'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});