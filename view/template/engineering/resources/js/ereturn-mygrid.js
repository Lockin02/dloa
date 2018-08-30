var show_page = function() {
	$("#myereturnlistGrid").yxgrid("reload");
};

$(function() {
	$("#myereturnlistGrid").yxgrid({
		model : 'engineering_resources_ereturn',
		action : 'mylistJson',
		title : '�ҵ��豸�黹',
		isDelAction : false,
		isOpButton : false,
		isAddAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'status',
			display : '״̬',
			sortable : true,
			width : 30,
			align : 'center',
			process : function(v) {
				switch(v){
                    case '0' : return '<img src="images/icon/cicle_grey.png" title="δ�ύ"/>';break;
                    case '1' : return '<img src="images/icon/cicle_yellow.png" title="��ȷ��"/>';break;
                    case '4' : return '<img src="images/icon/cicle_blue.png" title="����ȷ��"/>';break;
                    case '2' : return '<img src="images/icon/cicle_green.png" title="��ȷ��"/>';break;
                    case '3' : return '<img src="images/icon/cicle_red.png" title="�Ѵ��"/>';break;
				}
			}
		}, {
			display : '���뵥���',
			name : 'formNo',
			width : 140,
			sortable : true,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_ereturn&action=toView&id="
						+ row.id + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '������',
			sortable : true,
			width : 90,
			hide : true
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 90
		}, {
			name : 'areaName',
			display : '�黹����',
			sortable : true,
			width : 80
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			width : 150
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 150
		}, {
			name : 'managerName',
			display : '��Ŀ����',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'expressName',
			display : '��ݹ�˾',
			sortable : true,
			width : 120
		},{
			name : 'expressNo',
			display : '��ݵ���',
			sortable : true,
			width : 120
		},{
			name : 'mailDate',
			display : '�ʼ�����',
			sortable : true,
			width : 90
		},{
			name : 'remark',
			display : '��ע��Ϣ',
			sortable : true,
			width : 130
		}],
		toEditConfig : {
			showMenuFn : function(row) {
				return row.status == "0" || row.status == "3";
			},
			toEditFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_ereturn&action=toEdit&id="
					+ row.id,1,700,1100,row.id);
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_ereturn&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		menusEx : [{
			text : '�ύȷ��',
			icon : 'add',
			showMenuFn : function(row) {
                return row.status == "0" || row.status == "3";
			},
			action : function(row, rows, grid) {
				if (confirm('ȷ���������ύȷ����')) {
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_resources_ereturn&action=confirmStatus",
					    data: {
					    	'id' : row.id,
					    	'status' : '1'
					    },
					    async: false,
					    success: function(data){
					   		if(data == '1'){
								alert('�ύ�ɹ�');
								show_page();
							}else{
								alert('�ύʧ��');
							}
						}
					});
				}
			}
		}, {
            text : '��������',
            icon : 'delete',
            showMenuFn : function(row) {
                return row.status == "1";
            },
            action : function(row, rows, grid) {
                if (confirm('ȷ������������')) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_resources_ereturn&action=confirmStatus",
                        data: {
                            'id' : row.id,
                            'status' : 0
                        },
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
            }
        }, {
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
                return row.status == "0" || row.status == "3";
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_ereturn&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],
		searchitems : [{
			display : "���뵥���",
			name : 'formNoSch'
		},{
			display : "�黹����",
			name : 'areaNameSch'
		},{
			display : "��Ŀ���",
			name : 'projectCodeSch'
		},{
			display : "��Ŀ����",
			name : 'projectNameSch'
		}],
		//��������
		comboEx:[{
			text : '״̬',
			key : 'status',
			data : [{
                text : '���ύ',
                value : '0'
            }, {
                text : '��ȷ��',
                value : '1'
            },{
                text : '����ȷ��',
                value : '4'
            },{
                text : '��ȷ��',
                value : '2'
            },{
                text : '�Ѵ��',
                value : '3'
            }]
        }]
	});
});