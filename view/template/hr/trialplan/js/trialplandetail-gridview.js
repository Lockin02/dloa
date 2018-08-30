var show_page = function(page) {
	$("#trialplandetailGrid").yxgrid("reload");
};

//��ȡ��������������
var planArr = [];
var defaultSet;

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=hr_trialplan_trialplan&action=getMyPlans",
	    data: {"userAccount" : $("#userAccount").val() },
	    async: false,
	    success: function(data){
	    	if(planArr){
		   		planArr = eval( "(" + data + ")" );
				defaultSet = planArr[0].value;
	    	}
		}
	});

	$("#trialplandetailGrid").yxgrid({
		model : 'hr_trialplan_trialplandetail',
		title : 'Ա����ѵ�ƻ���ϸ',
		param : {
			'memberId' : $("#userAccount").val()
		},
//		isAddAction : false,
//		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'taskName',
				display : '��������',
				sortable : true,
				width : 130
			}, {
				name : 'description',
				display : '��������',
				sortable : true,
				width : 150
			}, {
				name : 'managerName',
				display : '��������',
				sortable : true,
				hide : true
			}, {
				name : 'managerId',
				display : '��������id',
				sortable : true,
				hide : true
			}, {
				name : 'taskScore',
				display : '�������',
				sortable : true,
				width : 60
			}, {
				name : 'baseScore',
				display : '���û���',
				sortable : true,
				width : 60
			}, {
				name : 'memberName',
				display : '����ִ����',
				sortable : true,
				hide : true
			}, {
				name : 'memberId',
				display : '����ִ����id',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '״̬',
				sortable : true,
				process : function(v){
					switch(v){
						case '0' : return 'δ����';break;
						case '1' : return 'ִ����';break;
						case '2' : return '�����';break;
						case '3' : return '�����';break;
						case '4' : return '�ѹر�';break;
						default : return v;
					}
				},
				width : 60
			}, {
				name : 'handupDate',
				display : '�ύ����',
				sortable : true,
				width : 80
			}, {
				name : 'score',
				display : '����',
				sortable : true,
				width : 60
			}, {
				name : 'scoreDate',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'scoreDesc',
				display : '����˵��',
				sortable : true,
				width : 130
			}, {
				name : 'beforeId',
				display : 'ǰ������id',
				sortable : true,
				hide : true
			}, {
				name : 'beforeName',
				display : 'ǰ����������',
				sortable : true,
				width : 130
			}],
		toAddConfig : {
			plusUrl : "&planId="+ $("#planId").val() +"&userAccount=" + $("#userAccount").val()+"&userName=" + $("#userName").val(),
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		comboEx : [{
				text : '�ƻ�����',
				key : 'planId',
				value : defaultSet,
				data : planArr
			}
		],

		//��չ�Ҽ�
		menusEx : [{
			text : '�ر�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if(confirm('ȷ��Ҫ�ر����������')){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_trialplan_trialplandetail&action=close",
					    data: {"id" : row.id },
					    async: false,
					    success: function(data){
					   		if(data == "1"){
								alert('�رճɹ�');
								show_page();
					   	    }else{
								alert('�ر�ʧ��');
					   	    }
						}
					});
				}
			}
		}],
		searchitems : [{
			display : "��������",
			name : 'taskNameSearch'
		}],
		sortorder : 'ASC'
	});
});