var show_page = function() {
	$("#esmactivityGrid").yxgrid("reload");

	var nodes = treeObj.getSelectedNodes();
	treeObj.reAsyncChildNodes(nodes[0],'refresh');

	//���Ԥ���·������������ˢ��
	self.parent.$("#iframe5").attr('src','');
};

var treeObj;

$(function() {
	var projectId = $("#projectId").val();

    //�����������������Ϣ
	$.ajax({
		type : "POST",
		url : "?model=engineering_activity_esmactivity&action=updateTriActivity",
		data : {"projectId":projectId}
	});

	/******������������*********/

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

	//��Ŀ��Χ
	$("#esmactivityGrid").yxgrid({
		model : 'engineering_activity_esmactivity',
		title : '��Ŀ����',
		param : {
			"projectId" : $("#projectId").val()
		},
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
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
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_activity_esmactivity&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				},
				width : 130
			}, {
				name : 'workRate',
				display : '����ռ��',
				sortable : true,
				process : function(v){
					return v + "%";
				},
				width : 60
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
				width : 60
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
				width : 60
			}, {
				name : 'process',
				display : '��ɽ���',
				sortable : true,
				process : function(v){
					return v + "%";
				},
				width : 60
			}, {
				name : 'workContent',
				display : '��������',
				sortable : true,
				width : 300,
				hide : true
			}],
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					//��֤ʱ���Ѿ�������
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_esmresources&action=checkHasResourceInAct",
						data : {
							activityId : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('�û�ѱ���Դ�ƻ�����������ɾ��');
								return false;
							} else {
								if (window.confirm("ɾ���ڵ������ӽڵ�һͬɾ��.ȷ��Ҫɾ��?")) {
									$.ajax({
										type : "POST",
										url : "?model=engineering_activity_esmactivity&action=ajaxdeletes",
										data : {
											id : row.id,
											key : row['skey_']
										},
										success : function(msg) {
											if (msg == 1) {
												alert('ɾ���ɹ�!');
												show_page();
											} else {
												alert('ɾ��ʧ��!');
											}
										}
									});
								}
							}
						}
					});
				}
			}
		}],
		//����
		toAddConfig : {
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			formWidth : 1000,
			formHeight : 500,
			plusUrl : "&projectId=" + projectId,
			toAddFn : function(p, treeNode, treeId) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
					+ p.model
					+ "&action="
					+ c.action
					+ c.plusUrl
					+ "&parentId="
					+ $("#parentId").val(treeNode.id)
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
					+ h + "&width=" + w);
			}
		},
		toEditConfig : {
			formWidth : 1000,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : 'toEdit'
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 500,
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : 'toView'
		},
		sortname : 'c.planBeginDate',
		sortorder : 'ASC'
	});
});