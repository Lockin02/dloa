var show_page = function(page) {
	$(".protasklist").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;
	var pjId = $('#projectId').val();
	$(".protasklist").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ



		model : 'engineering_task_protask',
            /**
			 * �Ƿ���ʾ�鿴��ť/�˵�
			 */
			isViewAction : false,
			/**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 */
			isEditAction : false,
			/**
			 * �Ƿ���ʾɾ����ť/�˵�
			 */
            isDelAction : false,

		// ��չ�Ҽ��˵�

		toAddConfig : {
				text : '����',
				/**
				 * Ĭ�ϵ��������ť�����¼�
				 */
				toAddFn : function(p) {
					var c = p.toAddConfig;
					var w = c.formWidth ? c.formWidth : p.formWidth;
					var h = c.formHeight ? c.formHeight : p.formHeight;
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ '&pjId='
							+ pjId
							+ c.plusUrl
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				},
				/**
				 * ���������õĺ�̨����
				 */
				action : 'toAdd',
				/**
				 * ׷�ӵ�url
				 */
				plusUrl : '',
				/**
				 * ������Ĭ�Ͽ��
				 */
				formWidth : 0,
				/**
				 * ������Ĭ�ϸ߶�
				 */
				formHeight : 0
			},
		menusEx : [

		{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row){
				if(	row.status == 'WFB'){
					return true;
				}
				return false;
			},
			action: function(row){
                showThickboxWin('?model=engineering_task_protask&action=deletes&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}



		},
		 {
			name : 'publishTask',
			text : '����',
			icon : 'add',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return true;
			   }
			       return false;
			},
			action : function(row) {
				$.get('index1.php', {
					model : 'engineering_task_protask',
					action : 'publishTask',
					id : row.id

				}, function(data) {
					alert(data);
					show_page();
					return;
				})
			}
		}, {

			text : '�༭',
			icon : 'edit',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return true;
			   }
			       return false;
			},
			action : function(row) {

				showThickboxWin('?model=engineering_task_protask&action=edit&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');

			}
		},{

			text : '���',
			icon : 'edit',
			 showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return false;
			   }else if(row.status=='QZZZ'){
			       return false;
			   }else if(row.status=='ZT'){
			       return false;
			   }
			       return true;
			},
			action : function(row) {

				showThickboxWin('?model=engineering_task_protask&action=editTab&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');

			}
		}, {
			name : 'pause',
			text : '��ͣ',
			icon : 'delete',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return false;
			   }else if(row.status=='QZZZ'){
			       return false;
			   }else if(row.status=='ZT') {
			       return false;
			   }
			       return true;
			},
			action : function(row) {
				showThickboxWin('?model=engineering_task_stophistory&action=toStopTask&id='
						+ row.id
						+ '&status='
						+ row.status
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
				// alert("��δ����")
			}
		}, {
			name : 'renew',
			text : '�ָ�',
			icon : 'edit',
			showMenuFn : function (row){
			   if(row.status=='ZT'){
			       return true;
			   }
			       return false;
			},
			action : function(row) {
				showThickboxWin('?model=engineering_task_stophistory&action=toReBackTask&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
			}
		}, {
			name : '',
			text : 'ǿ����ֹ',
			icon : 'delete',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return false;
			   }else if(row.status=='QZZZ'){
			       return false;
			   }
			       return true;
			},
			action : function(row) {

				showThickboxWin('?model=engineering_task_tkover&action=toAdd&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
			}
		}, {
			name : '',
			text : '�鿴',
			icon : 'view',
			action : function(row) {

				showThickboxWin('?model=engineering_task_protask&action=taskTab&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
			}
		}],



		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '��������',
			name : 'name',
			sortable : true,
			width : 80
		},

				// {
				// display : '������Ŀ',
				// name : 'projectName',
				// sortable : true,
				// width : 80
				// },

				{
					display : '���ȼ�',
					name : 'priority',
					sortable : true,
					width : 100
				}, {
					display : '״̬',
					name : 'status',
					sortable : true,
					datacode : 'XMRWZT',
					width : 100
				}, {
					display : '�����',
					name : 'effortRate',
					sortable : true,
					width : 100
				}, {
					display : 'ƫ����',
					name : 'warpRate',
					sortable : true,
					width : 100
				}, {
					display : '������',
					name : 'chargeName',
					sortable : true,
					width : 100
				}, {
					display : '������',
					name : 'publishName',
					sortable : true,
					width : 100
				}, {
					display : '�ƻ���ʼʱ��',
					name : 'planBeginDate',
					sortable : true,
					width : 100
				}, {
					display : '�ƻ����ʱ��',
					name : 'planEndDate',
					sortable : true,
					width : 100
				}, {
					display : '��������',
					name : 'taskType',
					width : 100,
					sortable : true
				}],
		//����pjId ����ҳ����Ϣ
		param : {
			"projectId" : $("#projectId").val()
		        },

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��������',
			name : 'name'
		}],
		sortorder : "ASC",
		title : '��Ŀ����'
	});
});