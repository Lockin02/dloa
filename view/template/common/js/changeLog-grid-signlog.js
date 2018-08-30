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
		title : 'ǩ�ռ�¼',
		// ��
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : 'ǩ����',
				name : 'changeManName',
				width : 200,
				process : function(v, row) {
					if (row['tempId'] == originalId) {
						return "<font color='red'>" + v + "</font>";
					}
					return v;
				}
			}, {
				display : 'ǩ��ʱ��',
				name : 'changeTime',
				width : 300,
				process : function(v, row) {
					if (row['tempId'] == originalId) {
						return "<font color='red'>" + v + "</font>";
					}
					return v;
				}
			}, {
				display : 'ǩ��˵��',
				name : 'changeReason',
				width : 350
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