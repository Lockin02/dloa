var show_page = function(page) {
	$("#inventoryGrid").yxgrid("reload");
};

$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
	$("#inventoryGrid").yxgrid({
		model : 'hr_invent_inventory',
		title : '��Ա�̵���Ϣ',
		bodyAlign:'center',
		isOpButton:false,
       	showcheckbox:false,
       	param:{"userNo":userNo},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
					width:70,
				sortable : true
			}, {
//				name : 'userAccount',
//				display : 'Ա���˺�',
//				sortable : true
//			}, {
				name : 'userName',
				display : 'Ա������',
				sortable : true,
					width:60,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_invent_inventory&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
//				name : 'companyType',
//				display : '��˾����',
//				sortable : true
//			}, {
//				name : 'companyName',
//				display : '��˾����',
//				sortable : true
//			}, {
				name : 'deptNameS',
				display : '����',
				sortable : true
			}, {
				name : 'position',
				display : 'ְλ',
				sortable : true
			}, {
				name : 'entryDate',
				display : '��ְ����',
					width:80,
				sortable : true
			}, {
				name : 'inventoryDate',
				display : '�̵�����',
					width:80,
				sortable : true
			}, {
				name : 'alternative',
				display : '��ְλ���г��������',
				sortable : true
			}, {
				name : 'matching',
				display : '�ֹ�������������ְλ��ƥ���',
				sortable : true
			}, {
				name : 'critical',
				display : 'Ա���ؼ���',
					width:80,
				sortable : true
			}, {
				name : 'isCore',
				display : '���ı����˲�',
					width:80,
				sortable : true
			}, {
				name : 'recruitment',
				display : '�г���Ƹ�Ѷ�',
					width:80,
				sortable : true
			}, {
				name : 'recruitment',
				display : '�Լ�Ч������������',
				sortable : true
			}, {
				name : 'examine',
				display : '��һ���ȿ����Ƿ��ź�5%',
				sortable : true
			}, {
				name : 'preEliminated',
				display : '�Ƿ�ΪԤ��̭��Ա',
					width:90,
				sortable : true
			}, {
				name : 'remark',
				display : '�Ƿ������ʧ',
					width:80,
				sortable : true
			}, {
				name : 'adjust',
				display : '�Դ�Ա���ĺ�����������',
				sortable : true
			}],
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_invent_inventory&action=toView&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		sortorder : "DESC",
		sortname : "id",
		title : 'Ա���̵���Ϣ'
	});
});