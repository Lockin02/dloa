// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#operantionGrid").yxgrid("reload");
};



$(function() {
	var objTable = $('#objTable').val();
	var objId = $('#objId').val();
	$("#operantionGrid").yxgrid({
		model : 'log_operation_operation',
		action : 'pageJson&objTable='+objTable + '&objId=' + objId,
		title : '������¼',
		isToolBar : false,
		showcheckbox : false,
		isRightMenu : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '������',
					name : 'operateManName',
					sortable : true
				}, {
					display : '����ʱ��',
					name : 'operateTime',
					sortable : true,
					width : '150'
				}, {
					display : '��������',
					name : 'operateType',
					sortable : true,
					width : '250'
				}, {
					display : '������¼',
					name : 'operateLog',
					sortable : true,
					width : '400',
					process:function(v){
						if(v == 'NULL'){
							return '';
						}else{
							return v;
						}
					}
				}]
	});

});