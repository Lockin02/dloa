var show_page = function(page) {
	$("#MyBeforeListGrid").yxgrid("reload");
};
$(function() {
	$("#MyBeforeListGrid").yxgrid({
		model : 'techsupport_tstask_tstask',
	    action : 'MyBeforeListPageJson' ,
		title : '�ҵ���ǰ֧��',
		isDelAction : false,
		isEditAction : false,
        //����Ϣ
		colModel : [{
			display : 'id',
   			name : 'id',
   			sortable : true,
   			hide : true
		},{
			name : 'formNo',
			display : '���ݱ��',
			sortable : true,
			width : 120
        },{
			name : 'objName',
			display : '������Ŀ����',
			sortable : true
        },{
			name : 'salesman',
			display : '���۸�����',
			sortable : true
        },{
			name : 'trainDate',
			display : '����ʱ��',
			sortable : true
        },{
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
        },{
			name : 'cusLinkman',
			display : '�ͻ���ϵ��',
			sortable : true
        },{
			name : 'cusLinkPhone',
			display : '�ͻ���ϵ�绰',
			sortable : true
        },{
			name : 'technicians',
			display : '������Ա',
			sortable : true
        },{
			name : 'status',
			display : '��ǰ״̬',
			sortable : true,
			datacode : 'XMZT'
        },{
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 120
        }],
        toAddConfig :{
        	formWidth : 900,
        	formHeight : 500,
        	action : 'toSelect'
        },
        toEditConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
        toViewConfig :{
        	formWidth : 900,
        	formHeight : 500
        },
        menusEx : [
	        {
				text : '�༭',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == 'XMZT-01') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						showThickboxWin('?model=techsupport_tstask_tstask&action=init&id='
							+ row.id + '&skey=' + row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}
				}
	        },
	        {
				text : '�ύ',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == 'XMZT-01') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (window.confirm(("ȷ��Ҫ�ύ?"))) {
						$.ajax({
							type : "POST",
							url : "?model=techsupport_tstask_tstask&action=pushUp",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�ύ�ɹ���');
									show_page(1);
								}else{
									alert('�ύʧ��');
								}
							}
						});
					}
				}
	        },
	        {
				text : '����',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == 'XMZT-03') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (window.confirm(("ȷ��Ҫ����?"))) {
						$.ajax({
							type : "POST",
							url : "?model=techsupport_tstask_tstask&action=pushDown",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�����ɹ���');
									show_page(1);
								}else{
									alert('����ʧ��');
								}
							}
						});
					}
				}
	        },
	        {
				text : '��д�����¼',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.status == 'XMZT-03') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						showThickboxWin('?model=techsupport_tstask_tstask&action=handup&id='
							+ row.id + '&skey=' + row.skey_
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
					}
				}
	        },
	        {
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.status == 'XMZT-01') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (row) {
						if (window.confirm(("ȷ��Ҫɾ��?"))) {
							$.ajax({
								type : "POST",
								url : "?model=techsupport_tstask_tstask&action=ajaxdeletes",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('ɾ���ɹ���');
										show_page(1);
									}else{
										alert('ɾ��ʧ��');
										show_page(1);
									}
								}
							});
						}
					}
				}
	        }
        ],
		searchitems:[
	        {
	            display:'���ݱ��',
	            name:'formNoSearch'
	        }
        ],
        // ��������
		comboEx : [{
			text : '״̬',
			key : 'status',
			datacode : 'XMZT'
		}]
	});
});