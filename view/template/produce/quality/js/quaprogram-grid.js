var show_page = function(page) {
	$("#quaprogramGrid").yxgrid("reload");
};
$(function() {
	$("#quaprogramGrid").yxgrid({
		model : 'produce_quality_quaprogram',
		title : '�ʼ췽��',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'programName',
				display : '��������',
				sortable : true,
				width : 150,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_quaprogram&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'standardName',
				display : '������׼',
				sortable : true,
				width : 150
			}, {
				name : 'isClose',
				display : '�Ƿ�ر�',
				sortable : true,
				process : function(v){
					if(v == "1"){
						return '��';
					}else{
						return '��';
					}
				},
				width : 80
			}, {
				name : 'remark',
				display : '��ע',
				sortable : true,
				width : 200
			}, {
				name : 'createName',
				display : '������',
				sortable : true
			}, {
				name : 'createTime',
				display : '��������',
				sortable : true,
				width : 130
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�����',
				sortable : true,
				width : 130,
				hide : true
			}], // ���ӱ������

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
			display : "ģ������",
			name : 'programNameSearch'
		}
	});
});