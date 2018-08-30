var show_page = function(page) {
	$("#equChangeLogGrid").yxgrid_changeLog("reload");
};

$(function() {
	$("#equChangeLogGrid").yxgrid_changeLog({
		param : {
			logObj : 'contractequ',
			objId : $('#objId').val()
			,objType : 'contractequ'
		},
		subGridOptions : {
			param : [{// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
				logObj : 'contractequ'
//				,parentType : 'contractequ'
			}, {
				paramId : 'parentId',
				// ���ݸ���̨�Ĳ�������
				colId : 'id' // ��ȡ���������ݵ�������
			}],
			colModel : [{
						name : 'detailTypeCn',
						display : '��������'
					},
					/**
					 * { name : 'changeField', width : 150, display : '����ֶ�' },
					 */
					{
						name : 'detailId',
						width : 30,
						display : '��־',
						process : function(v) {
							if (v != 0) {
								return v;
							}
							return "";
						}
					},  {
						name : 'objField',
						width : 150,
						display : '��������'
					},{
						name : 'changeFieldCn',
						width : 150,
						display : '�������',
						process : function(v, row) {
							if (v == '��������') {
								return v
										+ " <img src='images/icon/search.gif' title='�鿴���ò���' onclick='clickFun("
										+ row.oldValue + " , " + row.newValue
										+ ");'/>";
							} else {
								return v;
							}
						}
					}, {
						name : 'oldValue',
						width : 150,
						display : '���ǰֵ'
					}, {
						name : 'newValue',
						width : 150,
						display : '�����ֵ'
					}]
		}
	});

});