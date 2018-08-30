var show_page = function(page) {
	$("#esmresourcesGrid").yxgrid("reload");
};

$(function() {
	var projectId = $("#projectId").val();

	/******������������*********/
	//����������
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_resources_esmresources&action=resourcesTree&projectId=" + projectId + "&resourceNature=GCXMZYXZ-02" ,
			autoParam : ["id"],
			otherParam : { 'rtParentType' : 1 }
		},
		callback : {
			onClick : clickFun,
			onAsyncSuccess : zTreeOnAsyncSuccess
		}
	};

	//������
	treeObj = $.fn.zTree.init($("#tree"), setting);

	//��һ�μ��ص�ʱ��ˢ�¸��ڵ�
	var firstAsy = true;
	// ���سɹ���ִ��
	function zTreeOnAsyncSuccess() {
		if (firstAsy) {
			var treeObj = $.fn.zTree.getZTreeObj("tree");
			var nodes = treeObj.getNodes();
			if (nodes.length > 0) {
				treeObj.reAsyncChildNodes(nodes[0], "refresh");
			}
			firstAsy = false;
		}
	}

	//����˫���¼�
	function clickFun(event, treeId, treeNode){

		var budgetGrid = $("#esmresourcesGrid").data('yxgrid');
		if(treeNode.code == 'root'){
			budgetGrid.options.param['resourceCode']= '' ;
		}else{
			budgetGrid.options.param['resourceCode']=treeNode.code;
		}


		budgetGrid.reload();
	}

	//ʵ�����б�
	$("#esmresourcesGrid").yxgrid({
		model : 'engineering_resources_esmresources',
		action : 'pageJsonOrg',
		title : '��Ŀ��Դ�ƻ���ϸ����',
		param : {
			"projectId" : projectId,
			"resourceNature" : 'GCXMZYXZ-02'
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		customCode : 'esmresourcesGridDeal',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceId',
				display : '��Դid',
				sortable : true,
				hide : true
			}, {
				name : 'resourceCode',
				display : '��Դ����',
				sortable : true,
				hide : true
			}, {
				name : 'resourceName',
				display : '��Դ����',
				sortable : true
			}, {
				name : 'resourceTypeId',
				display : '��Դ����id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeName',
				display : '��Դ����',
				sortable : true,
				hide : true
			},{
			    name : 'resourceNature',
			    display : '��Դ����',
			    sortable : true,
			    datacode : 'GCXMZYXZ',
			    width : 70
			}, {
				name : 'activityId',
				display : '�id',
				sortable : true,
				hide : true
			}, {
				name : 'activityName',
				display : '�����',
				sortable : true
			}, {
				name : 'number',
				display : '����',
				sortable : true,
				width : 50
			}, {
				name : 'unit',
				display : '��λ',
				sortable : true,
				width : 50
			}, {
				name : 'planBeginDate',
				display : '����ʼ',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 70
			}, {
				name : 'planEndDate',
				display : '�������',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 70
			}, {
				name : 'beignTime',
				display : '��ʼʹ��',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'endTime',
				display : '����ʹ��',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'useDays',
				display : '����',
				sortable : true,
				width : 50
			}, {
				name : 'projectId',
				display : '��Ŀid',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				hide : true
			}, {
				name : 'workContent',
				display : '��������',
				sortable : true,
            	width : 150
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				hide : true
			}, {
				name : 'dealStatus',
				display : '����״̬',
				width : 70,
				datacode : 'GCZYCLZT'
			}, {
				name : 'dealManName',
				display : '������',
				width : 90
			}, {
				name : 'dealDate',
				display : '��������',
				width : 70
			}, {
				name : 'dealResult',
				display : '������',
				width : 150
			}
		],
		buttonsEx : [{
			name : 'deal',
			text : "����",
			icon : 'edit',
			action : function(row, rows, idArr) {
				if (row) {
					idStr = idArr.toString();
					showThickboxWin("?model=engineering_resources_esmresources"
						+ "&action=toDeal&ids="
						+ idStr
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
						);
				} else {
					alert('����ѡ���¼');
				}
			}
		},{
			name : 'Add',
			text : "����",
			icon : 'edit',
			action : function(row, rows, idArr) {
				location.href = "?model=engineering_project_esmproject&action=waitDealEquProject";
			}
		}],
		// ����״̬���ݹ���
		comboEx : [{
				text : '����״̬',
				key : 'dealStatus',
				datacode : 'GCZYCLZT'
			}
		],
		searchitems : [{
				display : "��Դ����",
				name : 'resourceNameSearch'
			}
		],
		sortname : 'c.resourceCode asc,c.planBeginDate',
		sortorder : 'ASC'
	});
});