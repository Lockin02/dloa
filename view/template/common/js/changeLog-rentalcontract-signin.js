var show_page = function(page) {
	$("#orderChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#orderChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'rentalcontractSignin',
							objId : $('#objId').val()
						},
						subGridOptions : {
							param : [{
										logObj : 'rentalcontractSignin'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});