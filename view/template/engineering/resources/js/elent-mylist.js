var show_page = function() {
	$("#myelentGrid").yxgrid("reload");
};
$(function() {
	$("#myelentGrid").yxgrid({
		model : 'engineering_resources_elent',
		title : '�ҵ��豸ת��',
		action : 'mylistJson',
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
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
                    case '0' : return '<img src="images/icon/cicle_grey.png" title="���ύ"/>';break;
                    case '1' : return '<img src="images/icon/cicle_blue.png" title="������Աȷ��"/>';break;
                    case '2' : return '<img src="images/icon/cicle_yellow.png" title="��������ȷ��"/>';break;
                    case '3' : return '<img src="images/icon/cicle_red.png" title="�Ѵ��"/>';break;
                    case '4' : return '<img src="images/icon/cicle_green.png" title="��ȷ��"/>';break;
                }
            }
        }, {
            name : 'formNo',
            display : '���뵥���',
            sortable : true,
            width : 120,
            process : function(v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_elent&action=toView&id="
                    + row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
            }
        }, {
            name : 'applyUser',
            display : '������',
            sortable : true,
            hide : true
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
        toEditConfig : {
            showMenuFn : function(row) {
                return row.status == "0" || row.status == "3";
            },
            toEditFn : function(p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_elent&action=toEdit&id="
                    + row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
            }
        },
        toViewConfig : {
            toViewFn : function(p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_elent&action=toView&id="
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
                if (confirm('ȷ���������ύ��')) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_resources_elent&action=confirmStatus",
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
                        url: "?model=engineering_resources_elent&action=confirmStatus",
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
                        url : "?model=engineering_resources_elent&action=ajaxdeletes",
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
        }, {
            text : 'ȷ��ת��',
            icon : 'add',
            showMenuFn : function(row) {
                return row.status == "2" && row.receiverId == $("#receiverId").val();
            },
            action : function(row) {
                showOpenWin("?model=engineering_resources_elent&action=toFinalConfirm&id="
                        + row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
        }],
        searchitems : [{
            display : "���뵥���",
            name : 'formNoSch'
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
            text : '����״̬',
            key : 'status',
            data : [{
                text : '���ύ',
                value : '0'
            }, {
                text : '������Աȷ��',
                value : '1'
            }, {
                text : '��������ȷ��',
                value : '2'
            }, {
                text : '�Ѵ��',
                value : '3'
            }, {
                text : '��ȷ��',
                value : '4'
            }]
        }]
	});
});