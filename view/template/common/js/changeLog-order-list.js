var show_page = function(page) {
	$("#orderChangeLogGrid").yxgrid_changeLog("reload");
};

function clickFun(oldVal, newVal) {
	alert('���ܽ�����');
}

$(function() {
	var objId = $("#objId").val();
	var logObj = ($("#logObj").val() != '')? $("#logObj").val() : 'contract';
	/*
	 * ��ȡ�������
	 */
	function getData() {
		var responseText = $.ajax({
			url : 'index1.php?model=common_changeLog&action=getChangeFieldCn',
			type : "POST",
			data : {
				objId : objId,
				logObj : logObj
			},
			async : false
		}).responseText;
		var d = eval("(" + responseText + ")");
		var obj = [];
		for (var i = 0; i < d.length; i++) {
			obj.push({'text':d[i].changeFieldCn,'value':d[i].changeFieldCn});
		}
		return obj;
	}
	var selectArr = getData();
	
	$("#orderChangeLogGrid").yxgrid_changeLog({
		param : {
			logObj : logObj,
			objType : logObj,
			objId : $('#objId').val()
		},
		subGridOptions : {
			param : [{// �̶�ֵ��һ�𣬶���Ҫ��ǰ��
				logObj : logObj,
				parentType : logObj
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
					}, {
						name : 'objField',
						width : 150,
						display : '��������'
					}, {
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
		},
		searchitems : [{
			display : '�����',
			name : 'changeManNameSearch'
		}],
		comboEx : [{
			text: "�������",
			key: 'changeFieldCn',
			data : selectArr
		}]
	});

});