var show_page = function(page) {
	$("#planChangeLogGrid").yxgrid_changeLog("reload");
};
$(function() {
	$("#planChangeLogGrid").yxgrid_changeLog({
		param : {
			logObj : 'produceapply',
			objId : $('#objId').val()
		},
		colModel : [ {
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
					return "<font color='red'>" + v + "</font>";
				}
				return v;
			}
		}, {
			display : '���ʱ��',
			name : 'changeTime',
			width : 200,
			process : function(v, row) {
				if (row['tempId'] == $("#originalId").val()) {
					return "<font color='red'>" + v + "</font>";
				}
				return v;
			}
		}, {
			display : '���ԭ��',
			name : 'changeReason',
			width : 400
		}, {
			display : '����ʱ��',
			name : 'ExaDT',
			hide : true
		}, {
			display : 'tempId',
			name : 'tempId',
			hide : true
		} ],
		subGridOptions : {
			param : [ {
				logObj : 'produceapply'// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
			}, {
				paramId : 'parentId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			} ]
		}
	});

});