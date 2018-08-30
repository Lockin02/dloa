var show_page = function(page) {
	$("#changeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
	var logObj = $('#logObj').val();
	var originalId = $("#originalId").val();

	$("#changeLogGrid").yxgrid_changeLog({
		param : {
			logObj : logObj,
			objId : $('#objId').val()
		},
		title : '�����Ϣ (ע����ɫ����ǰ����汾��¼)',
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
					if (row['tempId'] == originalId) {
						return "<font color='red'>" + v + "</font>";
					}
					return v;
				}
			}, {
				display : '���ʱ��',
				name : 'changeTime',
				width : 200,
				process : function(v, row) {
					if (row['tempId'] == originalId) {
						return "<font color='red'>" + v + "</font>";
					}
					return v;
				}
			}, {
				display : '���ԭ��',
				name : 'changeReason',
				width : 300
			}, {
				display : '����״̬',
				name : 'ExaStatus'
			}, {
				display : '����ʱ��',
				name : 'ExaDT'
			}, {
				display : 'tempId',
				name : 'tempId',
				hide : true
			}
		],
		subGridOptions : {
			param : [{
				logObj : logObj// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
			}, {
				paramId : 'parentId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}]
		}
	});
});