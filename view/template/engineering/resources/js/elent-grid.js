var show_page = function() {
	$("#myelentGrid").yxgrid("reload");
};
$(function() {
	$("#myelentGrid").yxgrid({
		model : 'engineering_resources_elent',
		title : '�豸ת�����',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			statusArr : '1,2,4,5'
		},
		// ����Ϣ
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
	                case '1' : return '<img src="images/icon/cicle_yellow.png" title="��ȷ��"/>';break;
	                case '5' : return '<img src="images/icon/cicle_yellow.png" title="����ȷ��"/>';break;
	                case '2' : return '<img src="images/icon/cicle_blue.png" title="��������ȷ��"/>';break;
	                case '4' : return '<img src="images/icon/cicle_green.png" title="��ȷ��"/>';break;
                }
            }
        }, {
            name : 'formNo',
            display : '���뵥���',
            sortable : true,
            width : 110,
            process : function(v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_elent&action=toView&id="
                    + row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
            }
        }, {
            name : 'applyUser',
            display : '������',
            width : 80,
            sortable : true
        }, {
            name : 'applyUserId',
            display : '������id',
            sortable : true,
            hide : true
        }, {
            name : 'deptId',
            display : '��������id',
            sortable : true,
            hide : true
        }, {
            name : 'deptName',
            display : '������������',
            sortable : true,
            hide : true
        }, {
            name : 'applyDate',
            display : '��������',
            sortable : true,
            width : 70
        }, {
            name : 'deviceDeptName',
            display : '�豸��������',
            sortable : true,
            width : 70
        }, {
            name : 'projectId',
            display : '�����Ŀid',
            sortable : true,
            hide : true
        }, {
            name : 'projectCode',
            display : '�����Ŀ���',
            sortable : true,
            width : 120
        }, {
            name : 'projectName',
            display : '�����Ŀ����',
            sortable : true,
            width : 120
        }, {
            name : 'managerName',
            display : '�����Ŀ����',
            sortable : true,
            hide : true
        }, {
            name : 'managerId',
            display : '�����Ŀ����id',
            sortable : true,
            hide : true
        }, {
            name : 'receiverId',
            display : '������id',
            sortable : true,
            hide : true
        }, {
            name : 'receiverName',
            display : '������',
            sortable : true,
            width : 80
        }, {
            name : 'receiverDept',
            display : '�����˲���',
            sortable : true,
            hide : true
        }, {
            name : 'receiverDeptId',
            display : '�����˲���id',
            sortable : true,
            hide : true
        }, {
            name : 'rcProjectId',
            display : '������Ŀid',
            sortable : true,
            hide : true
        }, {
            name : 'rcProjectCode',
            display : '������Ŀ���',
            sortable : true,
            width : 120
        }, {
            name : 'rcProjectName',
            display : '������Ŀ����',
            sortable : true,
            width : 120
        }, {
            name : 'rcManagerName',
            display : '������Ŀ����',
            sortable : true,
            hide : true
        }, {
            name : 'rcManagerId',
            display : '������Ŀ����id',
            sortable : true,
            hide : true
        }, {
            name : 'reason',
            display : 'ת��ԭ��',
            sortable : true
        }, {
            name : 'remark',
            display : '��ע',
            sortable : true
        }, {
            name : 'createId',
            display : '������Id',
            sortable : true,
            hide : true
        }, {
            name : 'createName',
            display : '����������',
            sortable : true,
            hide : true
        }, {
            name : 'createTime',
            display : '����ʱ��',
            sortable : true,
            hide : true
        }, {
            name : 'updateId',
            display : '�޸���Id',
            sortable : true,
            hide : true
        }, {
            name : 'updateName',
            display : '�޸�������',
            sortable : true,
            hide : true
        }, {
            name : 'updateTime',
            display : '�޸�ʱ��',
            hide : true
        }, {
            name : 'confirmName',
            display : 'ȷ����',
            sortable : true
        }, {
            name : 'confirmId',
            display : 'ȷ����id',
            sortable : true,
            hide : true
        }, {
            name : 'confirmTime',
            display : 'ȷ��ʱ��',
            sortable : true,
            width : 120
        } ],
        toViewConfig : {
            toViewFn : function(p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_elent&action=toView&id="
                    + row[p.keyField],1,700,1100,row.id);
            }
        },
        menusEx : [{
			text : 'ȷ��',
			icon : 'add',
			showMenuFn : function(row) {
                return row.status == "1" || row.status == "5";
			},
			action : function(row, rows, grid) {
                showOpenWin("?model=engineering_resources_elent&action=toConfirm&id="
                        + row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
		}, {
            text : '���',
            icon : 'delete',
            showMenuFn : function(row) {
                return row.status == "1";
            },
            action : function(row, rows, grid) {
                if (confirm('ȷ�ϴ�ص�����')) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_resources_elent&action=confirmStatus",
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
            display : "�����Ŀ���",
            name : 'projectCodeSch'
        },{
            display : "�����Ŀ����",
            name : 'projectNameSch'
        },{
            display : "������",
            name : 'receiverNameSch'
        },{
            display : "������Ŀ���",
            name : 'rcProjectCodeSch'
        },{
            display : "������Ŀ����",
            name : 'rcProjectNameSch'
        }],
		//��������
		comboEx:[{
			text : '״̬',
			key : 'status',
            value : '1,2,5',
			data : [{
                text : '��ȷ��',
                value : '1'
            },{
                text : '����ȷ��',
                value : '5'
            },{
                text : '��������ȷ��',
                value : '2'
            },{
                text : '��ȷ��',
                value : '4'
            },{
                text : '-δ���-',
                value : '1,2,5'
            }]
        }]
	});
});