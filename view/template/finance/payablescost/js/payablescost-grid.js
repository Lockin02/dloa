var show_page = function(page) {
	$("#payablescostGrid").yxgrid("reload");
};
$(function() {
	$("#payablescostGrid").yxgrid({
		model : 'finance_payablescost_payablescost',
		title : '����������÷�̯��ϸ',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'payapplyId',
				display : '���뵥Id',
				sortable : true,
				width : 50
			}, {
				name : 'payapplyCode',
				display : '���뵥���',
				sortable : true,
				width : 140
			}, {
				name : 'shareTypeName',
				display : '��̯����',
				width : 80,
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='#' onclick='showThickboxWin(\"?model=finance_payablescost_payablescost&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true
			}, {
				name : 'deptId',
				display : '����Id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '��Ա����',
				sortable : true
			}, {
				name : 'userId',
				display : '��ԱId',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 130
			}, {
				name : 'projectId',
				display : '��Ŀid',
				sortable : true,
				hide : true
			}, {
				name : 'shareMoney',
				display : '��̯����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				width : 80,
				process : function (v){
					if(v == '1'){
						return '����';
					}else if(v== '0'){
						return 'δ����';
					}
				}
			}, {
				name : 'createName',
				display : '������',
				sortable : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 130
			}
		],

		toEditConfig : {
			showMenuFn : function(row) {
				if(row.id == 'noId'){
					return false;
				}
			},
			action : 'toEdit'
		},
		toViewConfig : {
			showMenuFn : function(row) {
				if(row.id == 'noId'){
					return false;
				}
			},
			action : 'toView'
		},
        //��������
		comboEx:[
			{
				text:'��̯����',
			    key:'shareType',
			    datacode : 'CWFYFT'
			},{
				text : '״̬',
				key : 'status',
				value : 1,
				data : [
					{
						text : '����',
						value : '1'
					}, {
						text : 'δ����',
						value : '0'
					}
				]
			}
		],
		searchitems : [
			{
				display : "���뵥id",
				name : 'payapplyId'
			},{
				display : "���뵥���",
				name : 'payapplyCodeSearch'
			},{
				display : "��������",
				name : 'deptNameSearch'
			},{
				display : "��Ա����",
				name : 'userNameSearch'
			},{
				display : "��Ŀ����",
				name : 'projectNameSearch'
			},{
				display : "��Ŀ���",
				name : 'projectCodeSearch'
			}
		],
		sortname : 'c.payapplyId'
	});
});