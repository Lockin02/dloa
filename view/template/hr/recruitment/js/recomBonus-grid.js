var show_page = function(page) {
	$("#recomBonusGrid").yxgrid("reload");
};

$(function() {
	$("#recomBonusGrid").yxgrid({
		model : 'hr_recruitment_recomBonus',
		title : '�ڲ��Ƽ�����',
		isDelAction : false,
		isAddAction : false,
//		isEditAction : false,
		showcheckbox : false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			statedNo : '0'
		},
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			width:120,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_recruitment_recomBonus&action=toView&id=" + row.id+"&skey="+row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800" +"\")'>" + v + "</a>";
			}
		},{
			name : 'formDate',
			display : '��������',
			width:70,
			sortable : true
		},{
			name : 'isRecommendName',
			display : '������',
			width:70,
			sortable : true
		// },{
		// 	name : 'positionName',
		// 	display : 'ְλ����',
		// 	width:80,
		// 	sortable : true
		},{
			name : 'developPositionName',
			display : 'ְλС��',
			width:70,
			sortable : true
		},{
			name : 'jobName',
			display : 'ְλ����',
			sortable : true
		},{
			name : 'entryDate',
			display : '��ְ����',
			width:70,
			sortable : true
		},{
			name : 'becomeDate',
			display : 'ת������',
			width:70,
			sortable : true
		},{
			name : 'beBecomDate',
			display : 'Ԥ��ת������',
			width:80,
			sortable : true
		},{
			name : 'recommendName',
			display : '�Ƽ���',
			width:70,
			sortable : true
		},{
			name : 'stateC',
			display : '״̬',
			width:70,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '����״̬',
			width:70,
			sortable : true
		},{
			name : 'firstGrantDate',
			display : '��һ�δ���ʱ��',
			sortable : true
		},{
			name : 'firstGrantBonus',
			display : '��һ�δ�������',
			sortable : true
		},{
			name : 'secondGrantDate',
			display : '�ڶ��δ���ʱ��',
			sortable : true
		},{
			name : 'secondGrantBonus',
			display : '�ڶ��δ�������',
			sortable : true
		},{
			name : 'remark',
			display : '��ע',
			sortable : true
		}],

		lockCol:['formCode','formDate','isRecommendName'],//����������

		menusEx : [{
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == "����") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				location = "?model=hr_recruitment_recomBonus&action=redirectEwf&id="+row.id;  //��ת����Ӧ��������
				//location = "controller/hr/recruitment/ewf_index.php?actTo=ewfSelect&billId="+row.id+"&examCode=oa_hr_recommend_bonus&formName=�ڲ��Ƽ�����";
			}
		}],

		toViewConfig : {
			action : 'toView'
		},
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if(row.ExaStatus == "����") {
					return true;
				} else {
					return false;
				}
			}
		},

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "��������",
			name : 'formDate'
		},{
			display : "������",
			name : 'isRecommendName'
		},{
			display : "ְλС��",
			name : 'developPositionName'
		},{
			display : "ְλ����",
			name : 'jobName'
		},{
			display : "��ְ����",
			name : 'entryDate'
		},{
			display : "ת������",
			name : 'becomeDate'
		},{
			display : "Ԥ��ת������",
			name : 'beBecomDate'
		},{
			display : "�Ƽ���",
			name : 'recommendName'
		},{
			display : "��ע",
			name : 'remark'
		}]
	});
});