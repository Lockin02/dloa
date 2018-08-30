var show_page = function() {
	$("#ereturnlistGrid").yxgrid("reload");
};

$(function() {
	$("#ereturnlistGrid").yxgrid({
		model : 'engineering_resources_ereturn',
		action : 'pageJson',
		title : '�豸�黹��',
		isDelAction : false,
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		param : {
			statusArr : '1,2,4'
		},
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},  {
			name : 'status',
			display : '״̬',
			sortable : true,
			width : 30,
			align : 'center',
			process : function(v) {
				switch(v){
                    case '1' : return '<img src="images/icon/cicle_yellow.png" title="��ȷ��"/>';break;
                    case '4' : return '<img src="images/icon/cicle_blue.png" title="����ȷ��"/>';break;
                    case '2' : return '<img src="images/icon/cicle_green.png" title="��ȷ��"/>';break;
				}
			}
		}, {
			display : '���뵥���',
			name : 'formNo',
			width : 110,
			sortable : true,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_ereturn&action=toView&id="
                    + row.id + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '������',
			sortable : true,
			width : 80
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 80
		}, {
            name : 'areaName',
            display : '�黹����',
            sortable : true,
            width : 50
        }, {
			name : 'deviceDeptName',
			display : '�黹����',
			sortable : true,
			width : 70
		}, {
			name : 'projectCode',
			display : '��Ŀ���',
			sortable : true,
			width : 120
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
			width : 150
		}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_ereturn&action=toView&id="
                    + row[p.keyField],1,700,1100,row.id);
			}
		},
		menusEx : [{
			text : 'ȷ��',
			icon : 'add',
			showMenuFn : function(row) {
                return row.status == "1" || row.status == "4";
			},
			action : function(row, rows, grid) {
                showOpenWin("?model=engineering_resources_ereturn&action=toConfirm&id="
                        + row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
		},{
            text : '���',
            icon : 'delete',
            showMenuFn : function(row) {
                return row.status == "1";
            },
            action : function(row, rows, grid) {
                if (confirm('ȷ�ϴ�ص�����')) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_resources_ereturn&action=confirmStatus",
                        data: {
                            'id' : row.id ,
                            'status' : '3'
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
        }],
		searchitems : [{
			display : "���뵥���",
			name : 'formNoSch'
		},{
			display : "������",
			name : 'applyUserSch'
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
            value : '1,4',
			data : [{
                text : '��ȷ��',
                value : '1'
            },{
                text : '����ȷ��',
                value : '4'
            },{
                text : '��ȷ��',
                value : '2'
            },{
                text : '-δ���-',
                value : '1,4'
            }]
        }]
	});
});