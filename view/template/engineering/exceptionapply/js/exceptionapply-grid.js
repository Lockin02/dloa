var show_page = function(page) {
	$("#exceptionapplyGrid").yxgrid("reload");
};
$(function() {
	$("#exceptionapplyGrid").yxgrid({
		model : 'engineering_exceptionapply_exceptionapply',
		title : '���̳�Ȩ������',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formNo',
				display : '���뵥��',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_exceptionapply_exceptionapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=480&width=800\")'>" + v + "</a>";
				},
				width : 80
			}, {
				name : 'applyTypeName',
				display : '��������',
				sortable : true,
				width : 70
			}, {
				name : 'applyUserName',
				display : '������',
				sortable : true,
				width : 90
			}, {
				name : 'applyDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'applyMoney',
				display : '������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'useRangeName',
				display : '���÷�Χ',
				sortable : true,
				width : 80
			}, {
				name : 'projectCode',
				display : '������Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '������Ŀ',
				sortable : true,
				width : 130
			}, {
				name : 'applyReson',
				display : '����ԭ��',
				sortable : true,
				width : 120
			}, {
				name : 'products',
				display : '���',
				sortable : true,
				hide : true
			}, {
				name : 'rentalType',
				display : '�⳵����',
				sortable : true,
				hide : true
			}, {
				name : 'rentalTypeName',
				display : '�⳵��������',
				sortable : true,
				hide : true
			}, {
				name : 'area',
				display : 'ʹ������',
				sortable : true,
				hide : true
			}, {
				name : 'carNumber',
				display : '������',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '��ע��Ϣ',
				sortable : true,
				width : 120
			}, {
				name : 'ExaStatus',
				display : '���״̬',
				sortable : true,
				width : 70
			}, {
				name : 'ExaDT',
				display : '�������',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		toViewConfig : {
			action : 'toView',
			formHeight : 480
		},
        //��������
		comboEx:[{
		     text:'���״̬',
		     key:'ExaStatus',
		     value : '���',
		     data : [{
		     	'text' : '���',
		     	'value' : '���'
		     },{
		     	'text' : '�����',
		     	'value' : '�����'
		     },{
		     	'text' : '���ύ',
		     	'value' : '���ύ'
		     },{
		     	'text' : '���',
		     	'value' : '���'
		     }]
		   },{
		     text:'��������',
		     key:'applyType',
		     datacode : 'GCYCSQ'
		   }],
		searchitems : [{
			display : "���뵥��",
			name : 'formNoSearch'
		},{
			display : "��������",
			name : 'applyDateSearch'
		},{
			display : "������Ŀ",
			name : 'projectNameSearch'
		},{
			display : "������",
			name : 'applyMoney'
		}],
		sortname : 'c.createTime',
		sortorder : 'DESC'
	});
});