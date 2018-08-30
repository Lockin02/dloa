var show_page = function(page) {
	$("#recommendGrid").yxgrid("reload");
};

$(function() {
	$("#recommendGrid").yxgrid({
		model : 'hr_recruitment_recommend',
		action : 'myPageJson',
		title : '�ڲ��Ƽ�',
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		bodyAlign:'center',

		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width:120,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_recommend&action=toTabPage&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700\",1)'>"
					+ v + "</a>";
			}
		},{
			name : 'formDate',
			display : '��������',
			width:80,
			sortable : true
		},{
			name : 'isRecommendName',
			display : '������',
			width:80,
			sortable : true
		},{
			name : 'positionName',
			display : '�Ƽ�ְλ',
			sortable : true
		},{
			name : 'recruitManName',
			display : '��������',
			width:80,
			sortable : true
		},{
			name : 'recommendName',
			display : '�Ƽ���',
			width:80,
			sortable : true
		},{// ״̬ת����̨����
			name : 'stateC',
			width:80,
			display : '״̬'
		},{
			name : 'hrJobName',
			display : '¼��ְλ',
			sortable : true
		},{
			name : 'isBonus',
			display : '�Ƿ񷢷��˽���',
			sortable : true,
			process : function(v) {
				if (v == 1) {
					return "��";
				} else {
					return "��";
				}
			}
		},{
			name : 'bonus',
			display : '�����',
			sortable : true
		},{
			name : 'bonusProprotion',
			display : '�ѷ�����',
			sortable : true
		},{
			name : 'recommendReason',
			display : '�Ƽ�����',
			width : 300,
			sortable : true
		},{
			name : 'closeRemark',
			display : '�������',
			width : 300,
			sortable : true
		}],

		lockCol:['formCode','formDate','isRecommendName'],//����������

		menusEx : [{
			text : '�ύ���',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == 0) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
//				location = "?model=hr_recruitment_recommend&action=change&id=" + row.id + "&state=1";
				if (window.confirm(("���ã���OA�����ߣ���ת����OA�����ύ����?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_recommend&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								g.reload();
							}
						}
					});
				}
			}
		}],

		comboEx : [{
			text : '״̬',
			key : 'state',
			data : [{
				text : 'δ���',
				value : '1'
			},{
				text : '�ѷ���',
				value : '2'
			},{
				text : '��ͨ��',
				value : '3'
			},{
				text : '������',
				value : '4'
			},{
				text : '����ְ',
				value : '8'
			},{
				text : '����ְ',
				value : '5'
			},{
				text : '������ְ',
				value : '9'
			},{
				text : '�ر�',
				value : '6'
			}]
		}],

		buttonsEx : [{
			text : "����",
			icon : 'add',
			action : function(row) {
				alert("���ã���OA�����ߣ��뵽��OA�ύ���롣лл��");
				return false;
				showThickboxWin("?model=hr_recruitment_recommend&action=addBefore"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		}],

		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.state == 0) {
					return true;
				} else {
					return false;
				}
			},
            toEditFn : function(p, g) {
				if (window.confirm(("���ã���OA�����ߣ���ת����OA�����ύ����?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
            }
		},
		toViewConfig : {
			action : 'toView'
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
			display : "�Ƽ�ְλ",
			name : 'positionName'
		},{
			display : "��������",
			name : 'recruitManName'
		},{
			display : "Э����",
			name : 'assistManName'
		},{
			display : "�Ƽ�����",
			name : 'recommendReason'
		},{
			display : "�������",
			name : 'closeRemark'
		}]
	});
});