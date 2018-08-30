var show_page = function(page) {
	$("#orderGrid").yxgrid("reload");
};
$(function() {
	$("#orderGrid").yxgrid({				      
		model : 'outsourcing_workorder_order',
       	title : '�������',
       	isAddAction:false,
       	isEditAction:false,
       	isViewAction:false,
       	isDelAction:false,
		//����Ϣ
		colModel : [{
						��display : 'id',
							��name : 'id',
						��sortable : true,
						��hide : true
		        ��	����},{
        					name : 'approvalCode',
      					display : '���������',
      					sortable : true
                  },{
        					name : 'suppName',
      					display : '�����Ӧ������',
      					sortable : true
                  },{
        					name : 'suppCode',
      					display : '�����Ӧ�̱��',
      					sortable : true
                  },{
        					name : 'projectName',
      					display : '��Ŀ����',
      					sortable : true
                  },{
        					name : 'projectCode',
      					display : '��Ŀ���',
      					sortable : true
                  },{
        					name : 'province',
      					display : '��Ŀʡ��',
      					sortable : true
                  },{
        					name : 'suppType',
      					display : '�������',
      					sortable : true,
						��	hide : true
                  },{
        					name : 'suppTypeName',
      					display : '�����������',
      					sortable : true
                  },{
        					name : 'natureCode',
      					display : '��Ŀ����',
      					sortable : true,
						��	hide : true
                  },{
        					name : 'natureName',
      					display : '��Ŀ��������',
      					sortable : true
                  },{
        					name : 'projectManager',
      					display : '��Ŀ����',
      					sortable : true
                  },{
        					name : 'projectManagerId',
      					display : '��Ŀ����ID',
      					sortable : true,
						��	hide : true
                  },{
        					name : 'createId',
      					display : '������ID',
      					sortable : true,
						��	hide : true
                  },{
        					name : 'createName',
      					display : '�����',
      					sortable : true
                  },{
        					name : 'createTime',
      					display : '���ʱ��',
      					sortable : true
                  },{
        					name : 'ExaStatus',
      					display : '���״̬',
      					sortable : true
                  },{
        					name : 'ExaDT',
      					display : '���ʱ��',
      					sortable : true
                  }],
	//��ͷ��ť
	buttonsEx : [{
			name : 'view',
			text : "����",
			icon : 'add',
			action : function() {
				 showModalWin("?model=outsourcing_workorder_order&action=toAdd");
				}
			},{
			name : 'exportOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				window.open("?model=outsourcing_workorder_order&action=excelOutAll"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=40&width=60")
				}
			},{
			name : 'searchExport',
			text : "��ѯ����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_workorder_order&action=toSearchExport"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
    menusEx : [{
    		text : '�༭',
    		icon : 'edit',
    		action : function(row){
    			showModalWin("?model=outsourcing_workorder_order&action=toEdit&id="+row.id);
    		}
    		
			},{
    		text : '�鿴',
    		icon : 'view',
    		action : function(row){
    			showModalWin("?model=outsourcing_workorder_order&action=toView&id="+row.id);
    				}
    		},{
	    		text : 'ɾ��',
					icon : 'delete',
					action : function(row, rows, grid) {
						if (window.confirm(("ȷ��Ҫɾ��?"))) {
							$.ajax({
								type : "POST",
								url : "?model=outsourcing_workorder_order&action=ajaxdeletes",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('ɾ���ɹ���');
										$("#orderGrid").yxgrid("reload");
									}
								}
							});
						}
				}
    		}],
		// ���ӱ������
//		subGridOptions : {
//			url : '?model=outsourcing_workorder_NULL&action=pageItemJson',
//			param : [{
//						paramId : 'mainId',
//						colId : 'id'
//					}],
//			colModel : [{
//						name : 'XXX',
//						display : '�ӱ��ֶ�'
//					}]
//		},
        toAddConfig : {
			action : 'toAdd',
			formWidth : 1000,
			formHeight : 400
		},
		toEditConfig : {
			action : 'toEdit',
			formWidth : 800,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},
		searchitems : [{
					display : "���������",
					name : 'approvalCode'
					},{
						display : "�����Ӧ������",
						name : 'suppName'
					},{
						display : "�����Ӧ�̱��",
						name : 'suppCode'
					},{
						display : "��Ŀ����",
						name : 'projectName'
					},{
						display : "��Ŀ���",
						name : 'projectCode'
					},{
						display : "�������",
						name : 'suppTypeName'
					},{
						display : "��Ŀ����",
						name : 'natureName'
					},{
						display : "��Ŀ����",
						name : 'projectManager'
					}]
 		});
 });