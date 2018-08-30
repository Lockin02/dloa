var show_page = function(page) {
	$("#inventoryGrid").yxgrid("reload");
};

$(function() {

	buttonsArr = [{
		name : 'view',
		text : "��ѯ",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_invent_inventory&action=toSearch"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	},{
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_invent_inventory&action=toImport"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	},{
		name : 'exportOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_invent_inventory&action=toExcelOut"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	}];

	$("#inventoryGrid").yxgrid({
		model : 'hr_invent_inventory',
		title : '��Ա�̵���Ϣ',
		bodyAlign:'center',
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			width:70,
			sortable : true
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width:60,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_invent_inventory&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		},{
			name : 'deptNameS',
			display : '����',
			sortable : true
		},{
			name : 'position',
			display : 'ְλ',
			sortable : true
		},{
			name : 'entryDate',
			display : '��ְ����',
			width:80,
			sortable : true
		},{
			name : 'inventoryDate',
			display : '�̵�����',
			width:80,
			sortable : true
		},{
			name : 'alternative',
			display : '��ְλ���г��������',
			sortable : true
		},{
			name : 'matching',
			display : '�ֹ�������������ְλ��ƥ���',
			sortable : true
		},{
			name : 'isCritical',
			display : '�Ƿ�ؼ�Ա��',
			sortable : true
		},{
			name : 'critical',
			display : 'Ա���ؼ���',
			sortable : true
		},{
			name : 'isCore',
			display : '���ı����˲�',
			sortable : true
		},{
			name : 'recruitment',
			display : '�г���Ƹ�Ѷ�',
			sortable : true
		},{
			name : 'recruitment',
			display : '�Լ�Ч������������',
			sortable : true
		},{
			name : 'examine',
			display : '��һ���ȿ����Ƿ��ź�5%',
			sortable : true
		},{
			name : 'preEliminated',
			display : '�Ƿ�ΪԤ��̭��Ա',
			sortable : true
		},{
			name : 'remark',
			display : '�Ƿ������ʧ',
			sortable : true
		},{
			name : 'adjust',
			display : '�Դ�Ա���ĺ�����������',
			sortable : true
		},{
			name : 'workQuality',
			display : '��������',
			sortable : true
		},{
			name : 'workEfficiency',
			display : '����Ч��',
			sortable : true
		},{
			name : 'workZeal',
			display : '��������',
			sortable : true
		}],

		lockCol:['userNo','userName','deptNameS'],//����������

		buttonsEx : buttonsArr,

		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_invent_inventory&action=toView&id="
						+ row.id
						+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Ա�����',
			name : 'userNoM'
		},{
			display : 'Ա������',
			name : 'userNameM'
		},{
			display : '����',
			name : 'deptName'
		}],

		sortorder : "DESC",
		sortname : "id",
		title : 'Ա���̵���Ϣ'
	});
});