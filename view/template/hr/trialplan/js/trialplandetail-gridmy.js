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
		action : 'myJson',
		title : '�ҵ�������ѵ',
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
				name : 'managerName',
				display : '��������',
				sortable : true,
				width : 90
			}, {
				name : 'managerId',
				display : '��������id',
				sortable : true,
				hide : true
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
				width : 130,
				hide : true
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

		menusEx : [{
			name : 'edit',
			text : "��������",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.beforeName != ""){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_trialplan_trialplandetail&action=isComplate",
					    data: {"taskName" : row.beforeName,"planId" : row.planId},
					    async: false,
					    success: function(data){
					   		if(data == '1'){
								if(confirm('ȷ��Ҫ������������')){
									$.ajax({
									    type: "POST",
									    url: "?model=hr_trialplan_trialplandetail&action=begin",
									    data: {"id" : row.id },
									    async: false,
									    success: function(data){
									   		if(data == '1'){
												alert('�����ɹ�');
												show_page();
									   	    }else{
												alert('����ʧ��');
									   	    }
										}
									});
								}
					   	    }else{
								alert('ǰ������δ��ɣ���������������');
					   	    }
						}
					});
				}else{
					if(confirm('ȷ��Ҫ�������������')){
						$.ajax({
						    type: "POST",
						    url: "?model=hr_trialplan_trialplandetail&action=begin",
						    data: {"id" : row.id },
						    async: false,
						    success: function(data){
						   		if(data != ""){
									alert('����ɹ�');
									show_page();
						   	    }else{
									alert('����ʧ��');
						   	    }
							}
						});
					}
				}
			}
		},{
			name : 'edit',
			text : "�ύ����",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1' && row.closeType == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=hr_trialplan_trialplandetail&action=toHandUp&id=" + row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			name : 'edit',
			text : "�������",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '1' && row.closeType == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('ȷ��Ҫ������������')){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_trialplan_trialplandetail&action=complate",
					    data: {"id" : row.id },
					    async: false,
					    success: function(data){
					   		if(data != ""){
								alert('�����ɹ�');
								show_page();
					   	    }else{
								alert('����ʧ��');
					   	    }
						}
					});
				}
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		comboEx : [{
				text : '�ƻ�����',
				key : 'planId',
				value : defaultSet,
				data : planArr
			}
		],
		searchitems : [{
			display : "��������",
			name : 'taskNameSearch'
		}],
		sortorder : 'ASC'
	});
});