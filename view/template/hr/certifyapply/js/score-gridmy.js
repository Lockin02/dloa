var show_page = function(page) {
	$("#scoreGrid").yxgrid("reload");
};
$(function() {
	$("#scoreGrid").yxgrid({
		model : 'hr_certifyapply_score',
		action : 'myPageJson',
		title : '�ҵ���ְ�ʸ���ί���',
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		isEditAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '����Ա��',
				sortable : true
			}, {
				name : 'userAccount',
				display : '����Ա���ʺ�',
				sortable : true,
				hide : true
			}, {
				name : 'managerName',
				display : '������',
				sortable : true
			}, {
				name : 'managerId',
				display : '������Աid',
				sortable : true,
				hide : true
			}, {
				name : 'scoreId',
				display : '���ֱ�id',
				sortable : true,
				hide : true
			}, {
				name : 'assessDate',
				display : '��������',
				sortable : true
			}, {
				name : 'status',
				display : '���۱�״̬',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return '����';break;
						case '1' : return '��֤׼����';break;
						case '2' : return '������';break;
						case '3' : return '��ɴ�����';break;
						case '4' : return '���������';break;
						case '5' : return 'ȷ�������';break;
						case '6' : return '��������';break;
						default : return v;
					}
				}
			}, {
				name : 'scoreStatus',
				display : '����״̬',
				sortable : true,
				process : function(v){
					if(v == '0'){
						return 'δ����';
					}else{
						return '������';
					}
				}
			}, {
				name : 'score',
				display : '�÷�'
			}],
		menusEx : [{
			name : 'view',
			text : "�鿴��֤����",
			icon : 'view',
			action : function(row) {
				//�ж�
				showModalWin("?model=hr_certifyapply_cassess&action=toView&id=" + row.id + "&skey=" + row.skey);
			}
		},{
			name : 'add',
			text : "�½�����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.scoreStatus == '0' && row.status == '3') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_certifyapply_score&action=toScore&cassessId=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'view',
			text : "�鿴����",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.scoreStatus != '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_certifyapply_score&action=toView&id=" + row.scoreId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'edit',
			text : "�޸�����",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.scoreStatus != '0' && (row.status == '3' ||row.status == '4') ) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_certifyapply_score&action=toEdit&id=" + row.scoreId
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'edit',
			text : "��������",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '3' || row.status == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.managerId == row.scoreManagerId){
					alert('��ѡ��������ί�Ͳ�����ί');
					return false;
				}
				//�ж�
				showModalWin("?model=hr_certifyapply_cassess&action=toInScore&id=" + row.id + "&skey=" + row.skey);
			}
		}],
		searchitems : [{
			display : "����Ա��",
			name : 'suserNameSearch'
		}]
	});
});