var show_page = function() {
	$("#warningGrid").yxgrid("reload");
};
$(function() {
	$("#warningGrid").yxgrid({
		model : 'system_warning_warning',
		title : 'ͨ��Ԥ������',
		//����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'objName',
			display : 'ҵ������',
			sortable : true,
			width : 120
		}, {
			name : 'description',
			display : '������Ϣ',
			sortable : true,
			width : 120,
            hide : true
		}, {
			name : 'executeSql',
			display : '��ѯ�ű�',
			sortable : true,
			width : 500
		}, {
			name : 'isUsing',
			display : '�Ƿ�����',
			sortable : true,
			width : 70,
			process : function(v) {
				if (v == "1") {
					return '��';
				} else {
					return '��';
				}
			}
		}, {
            name : 'executeClass',
            display : 'ִ����',
            sortable : true,
            width : 120
        }, {
            name : 'executeFun',
            display : 'ִ�з���',
            sortable : true,
            width : 120
        }, {
			name : 'mailCode',
			display : 'ִ���ʼ�֪ͨ����',
			sortable : true,
			width : 120
		}, {
			name : 'inKeys',
			display : '�����ʼ��ֶ�',
			sortable : true,
			width : 120
		}, {
			name : 'receiverIdKey',
			display : '�ʼ�������id�ֶ�',
			sortable : true,
			width : 120
		}, {
			name : 'receiverNameKey',
			display : '�ʼ������������ֶ�',
			sortable : true,
			width : 120
		}, {
			name : 'isMailManager',
			display : '�Ƿ�֪ͨ�ϼ�',
			sortable : true,
			width : 70,
			process : function(v) {
				if (v == "1") {
					return '��';
				} else {
					return '��';
				}
			}
		} , {
			name : 'lastTime',
			display : '���ִ��ʱ��',
			sortable : true,
			width : 120
		} , {
			name : 'intervalDay',
			display : '���ʱ��(��)',
			sortable : true,
			width : 70
		} , {
			name : 'regularPlan',
			display : '���ڼƻ�',
			sortable : true,
			width : 70
		} ],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		menusEx : [{
			text : '���Խű�',
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=system_warning_warning&action=testSql&id="
					+ row.id + "&skey=" + row.skey_ + "&height="+400 + "&width=" + 800
                    + "&placeValuesBefore&TB_iframe=true&modal=false");
			}
		},{
			text : '�ֶ�ִ��',
			icon : 'edit',
			action : function(row) {
				if (row) {
					if (window.confirm(("ȷ��Ҫ�ֶ�ִ�С�" + row.objName + "��?"))) {
						$.ajax({
						    type: "POST",
						    url: "?model=system_warning_warning&action=dealWarningByMan",
						    data: {
						    	"id" : row.id
						    },
						    async: false,
						    success: function(data){
						   		if(data == 1){
									alert('ִ�гɹ�!');
									show_page();
						   	    }else{
						   	    	alert('ִ��ʧ��!');
						   	    }
							}
						});
					}
				}
			}
		}],
		searchitems : [{
			display : "����ҵ��",
			name : 'objName'
		}]
	});
});