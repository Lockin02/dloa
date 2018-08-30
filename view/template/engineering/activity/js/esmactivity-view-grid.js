$(function() {
	var projectId = $("#projectId").val();

    //�����������������Ϣ
    $.ajax({
        type : "POST",
        url : "?model=engineering_activity_esmactivity&action=updateTriActivity",
        data : {"projectId":projectId}
    });

	/******������������*********/
	//��ֵ����ĸ߶�
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#tree").height(thisHeight);

	//����������
	var setting = {
		async : {
			enable : true,
			url : "?model=engineering_activity_esmactivity&action=getChildren&projectId=" + projectId,
			autoParam : ["id", "name=n"],
			otherParam : { 'rtParentType' : 1 }
		},
		callback : {
			onClick : clickFun,
			onAsyncSuccess : zTreeOnAsyncSuccess
		},
		view : {
			selectedMulti : false
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
		}
		firstAsy = false;
	}

	//����˫���¼�
	function clickFun(event, treeId, treeNode){

		var budgetGrid = $("#esmactivityGrid").data('yxgrid');
		budgetGrid.options.param['parentId']=treeNode.id;

		budgetGrid.reload();
		$("#parentId").val(treeNode.id);
	}

	$("#esmactivityGrid").yxgrid({
		model : 'engineering_activity_esmactivity',
		title : '��Ŀ����',
		param : {
			"projectId" : $("#projectId").val()
		},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'activityName',
				display : '��������',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_activity_esmactivity&action=toViewPEdit&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
				},
				width : 130
			}, {
				name : 'workRate',
				display : '����ռ��',
				sortable : true,
				process : function(v){
					return v + "%";
				},
				width : 70
			}, {
				name : 'parentId',
				display : '�ϼ�Id',
				sortable : true,
				hide : true
			}, {
				name : 'parentCode',
				display : '�ϼ�����',
				sortable : true,
				hide : true
			}, {
				name : 'parentName',
				display : '�ϼ�����',
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
				name : 'planBeginDate',
				display : 'Ԥ�ƿ�ʼ',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'planEndDate',
				display : 'Ԥ�ƽ���',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'days',
				display : 'Ԥ�ƹ���',
				sortable : true,
				width : 70
			}, {
				name : 'actBeginDate',
				display : 'ʵ�ʿ�ʼ',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'actEndDate',
				display : 'ʵ�ʽ���',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'actDays',
				display : 'ʵ�ʹ���',
				sortable : true,
				width : 70
			}, {
				name : 'workedDays',
				display : '��ʵʩ����',
				sortable : true,
				width : 70
			}, {
				name : 'needDays',
				display : 'Ԥ�ƻ���',
				sortable : true,
				width : 70
			}, {
				name : 'process',
				display : '��ɽ���',
				sortable : true,
				width : 70,
				process : function(v){
					return v + "%";
				}
			}, {
				name : 'workContent',
				display : '��������',
				sortable : true,
				width : 300,
				hide : true
			}],
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.id != "noId") {
					return true;
				}
				return false;
			},
			action : 'toView'
		},
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});