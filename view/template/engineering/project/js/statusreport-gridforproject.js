var show_page = function(page) {
	$("#statusreportGrid").yxgrid("reload");
};

$(function() {
	$("#statusreportGrid").yxgrid({
		model : 'engineering_project_statusreport',
		action : 'jsonForProject',
		title : '��Ŀ�ܱ�',
		param : { "projectId" : $("#projectId").val() },
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		usepager : false,
		sortname : 'weekNo',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				hide : true
			}, {
				name : 'projectId',
				display : '��Ŀid',
				hide : true
			}, {
				name : '',
				display : '',
				sortable : true,
				align :'center',
				width : 40,
				process : function(v,row){
					if(row.ExaStatus == '���'){
						return "<img src='images/icon/cicle_green.png'/>";
					}else if(row.ExaStatus == '��������'){
						return "<img src='images/icon/cicle_blue.png'/>";
					}else{
						return "<img src='images/icon/cicle_grey.png'/>";
					}
				}
			}, {
				name : 'weekNo',
				display : '�ܴ�',
				sortable : true,
				width : 80
			}, {
				name : 'handupDate',
				display : '�㱨����',
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_statusreport&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\",1)'>" + v + "</a>";
				}
			}, {
				name : 'beginDate',
				display : '��ʼ����'
			}, {
				name : 'endDate',
				display : '��������'
			}, {
				name : 'projectProcess',
				display : '��Ŀ����',
				process : function(v){
					if(v != ""){
						return v + " %";
					}
				}
			}, {
				name : 'createName',
				display : '�ύ��',
				hide : true
			}, {
				name : 'status',
				display : '����״̬',
				datacode : 'XMZTBG',
				hide : true,
				width : 80
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				width : 80
			}, {
				name : 'confirmName',
				display : '������'
			}, {
				name : 'ExaDT',
				display : '��������',
				hide : true
			}, {
				name : 'confirmDate',
				display : '��������'
			}, {
				name : 'createTime',
				display : '����ʱ��',
				width : 140
			}
		],
		toViewConfig : {
			showMenuFn : function(row) {
				if (row.id *1 == row.id) {
					return true;
				}
				return false;
			},
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				if(rowData.id *1 == rowData.id){
					showModalWin("?model=engineering_project_statusreport&action=toView&id=" + rowData[p.keyField] ,1);
				}
			}
		}
	});
});