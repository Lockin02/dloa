var show_page = function(page) {
	$("#esmpersonGrid").yxgrid("reload");
};
$(function() {
	var projectId = $("#projectId").val();

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
		var budgetGrid = $("#esmpersonGrid").data('yxgrid');
		budgetGrid.options.param['activityId']=treeNode.id;
		budgetGrid.options.param['lft']=treeNode.lft;
		budgetGrid.options.param['rgt']=treeNode.rgt;

		budgetGrid.reload();
		$("#activityId").val(treeNode.id);
		$("#activityName").val(treeNode.name);
		$("#lft").val(treeNode.lft);
		$("#rgt").val(treeNode.rgt);
	}

	$("#esmpersonGrid").yxgrid({
		model : 'engineering_person_esmperson',
		title : '��Ŀ����Ԥ��',
		param : {
			"projectId" : projectId
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '������Ŀ',
				sortable : true,
				hide : true
			}, {
				name : 'personLevel',
				display : '��Ա�ȼ�',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_person_esmperson&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'number',
				display : '����',
				sortable : true
			}, {
				name : 'planBeginDate',
				display : '������Ŀ',
				sortable : true
			}, {
				name : 'planEndDate',
				display : '�뿪��Ŀ',
				sortable : true
			}, {
				name : 'days',
				display : '����',
				sortable : true
			}, {
				name : 'personDays',
				display : '�˹�����(��)',
				sortable : true
			}, {
				name : 'personCostDays',
				display : '�����ɱ�(��)',
				sortable : true
			}, {
				name : 'activityName',
				display : '��������',
				sortable : true
			}, {
				name : 'personCost',
				display : '�����ɱ����(Ԫ)',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}],
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : 'toView'
		},
		sortname : 'planBeginDate',
		sortorder : 'ASC',
		searchitems : {
			display : "��Ա����",
			name : 'personLevel'
		}
	});
});