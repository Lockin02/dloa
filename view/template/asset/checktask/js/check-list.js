// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#checkGrid").yxgrid('reload');
};

$(function() {
	$("#checkGrid").yxgrid({
		model : 'asset_checktask_check',
	    title : '�̵���������Ϣ',
	    showcheckbox : false,
		// ����Ϣ
		 colModel : [
	            {
	                display : 'id',
	                name : 'id',
	                sortable : true,
	                hide : true
	            }, {
	                display : '����id',
	                name : 'taskId',
	                sortable : true,
	                hide : true
	            },
	            {
	                display : '������',
	                name : 'taskNo',
	                sortable : true
	            },
	            {
	                display : '��ʼʱ��',
	                name : 'beginDate',
	                sortable : true
	            },
	            {
	                display : '����ʱ��',
	                name : 'endDate',
	                sortable : true
	            },
	            {
	                display : '�̵㲿��id',
	                name : 'deptId',
	                sortable : true,
	                hide:true
	            },
	            {
	                display : '�̵㲿��',
	                name : 'dept',
	                sortable : true
	            },{
				   display:'�̵���id',
				   name : 'manId',
	               sortable : true,
	                hide:true
	
				},{
				   display:'�̵���',
				   name : 'man',
	               sortable : true
	
				},
	            {
	                display : '��ע',
	                name : 'remark',
	                sortable : true
	            }],
	
				isViewAction : true,
	            isEditAction : false,
	            isDelAction:  false,
	            toAddConfig : {
				  formWidth : 800,
				  formHeight : 350
			},
			    toEditConfig : {
					formWidth : 800,
					formHeight : 350
			},
			    toViewConfig : {
					formWidth : 800,
					formHeight : 300
			},
			menusEx : [
			  {
				text : 'ɾ��',
				icon : 'delete',
				action : function(row) {
					if (window.confirm(("ȷ��ɾ����"))) {
	
						$.ajax({
							type : "GET",
							url : "?model=asset_checktask_check&action=deletes&id="
									+ row.id,
							success : function(msg) {
								$("#checkGrid").yxgrid("reload");
							}
						});
					}
				}
			}],
			// ��������
			searchitems : [{
				display : '������',
				name : 'taskNo'
			}, {
				display : '��ʼʱ��',
				name : 'beginDate'
			}, {
				display : '�̵㲿��',
				name : 'dept'
			}],
			sortname : "id",
			sortorder : "ASC"
			});
		});