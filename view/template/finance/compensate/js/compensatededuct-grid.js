var show_page = function(page) {
	$("#compensateGrid").yxgrid("reload");
};
$(function() {
	$("#compensateGrid").yxgrid({
		model : 'finance_compensate_compensatededuct',
		title : '�⳥�ۿ��¼',
		isOpButton : false,
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'compensateId',
			name : '�⳥��id',
			sortable : true,
			hide : true
		}, {
			name : 'compensateCode',
			display : '�⳥�����',
			sortable : true,
			width : 110,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=finance_compensate_compensate&action=toView&id="+row.compensateId+"\",1,600,1000,"+row.compensateId+")'>"+v+"</a>";
			}
		}, {
			name : 'dutyType',
			display : '�⳥����',
			datacode : 'PCZTLX',
			sortable : true,
			width : 80
		}, {
			name : 'dutyObjId',
			display : '�⳥����id',
			sortable : true,
			hide : true
		}, {
			name : 'dutyObjName',
			display : '�⳥����',
			sortable : true,
			width : 80
		}, {
			name : 'payType',
			display : '�ۿʽ',
			datacode : 'KKFS',
			sortable : true,
			width : 80
		}, {
			name : 'deductMoney',
			display : '�ۿ���',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'operateName',
			display : '������',
			sortable : true,
			width : 100
		}, {
			name : 'operateTime',
			display : '����ʱ��',
			sortable : true,
			width : 120
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 200
		}],
		//��������
		comboEx : [{
		     text:'�⳥����',
		     key:'dutyType',
		     datacode : 'PCZTLX'
		}],
		searchitems : [{
			display : "�⳥�����",
			name : 'compensateCode'
		},{
			display : "�⳥����",
			name : 'dutyObjName'
		}]
	});
});