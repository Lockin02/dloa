var show_page = function(page) {
	$("#outplanChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
			$("#outplanChangeLogGrid").yxgrid_changeLog({
						param : {
							logObj : 'outplan',
							objId : $('#objId').val()
						},
					// ��
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '�����',
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
								display : '���ʱ��',
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
								width : 300
							}, {
								display : '����״̬',
								name : 'ExaStatus',
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
										logObj : 'outplan'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
									}, {
										paramId : 'parentId',// ���ݸ���̨�Ĳ�������
										colId : 'id'// ��ȡ���������ݵ�������
									}]
						}
					});


		});