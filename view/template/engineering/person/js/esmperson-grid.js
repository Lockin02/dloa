var show_page = function(page) {
	$("#esmpersonGrid").yxgrid("reload");
};
$(function() {
	//��ֵ����ĸ߶�
	var thisHeight = document.documentElement.clientHeight - 40;
	$("#tree").height(thisHeight);

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
		title : '����Ԥ��',
		param : {
			"projectId" : projectId
		},
		noCheckIdValue : 'noId',
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

		toAddConfig : {
			formWidth : 950,
			formHeight : 400,
			plusUrl : "&projectId=" + projectId,
			toAddFn : function(p, treeNode, treeId) {
				var activityId = $("#activityId").val();
				if(activityId == -1){
					alert('����ѡ��һ����Ŀ����');
					return false;
				}
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ c.plusUrl
						+ "&activityId="
						+ activityId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			}
		},
		toEditConfig : {
			formWidth : 950,
			formHeight : 400,
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
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			action : 'toView'
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			}
		},
		buttonsEx : [{
				text : "��������",
				icon : 'add',
				name : 'batchAdd',
				action : function() {
					showThickboxWin("?model=engineering_person_esmperson&action=toAddBatch&projectId="
						+ projectId
						+ "&activityId="
						+ $("#activityId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000");
				}
			},{
				name : 'Add',
				text : "����",
				icon : 'add',
				action : function(row, rows, idArr) {
					if (row) {
						idStr = idArr.toString();
						showThickboxWin("?model=engineering_person_esmperson"
							+ "&action=toCopy&ids="
							+ idStr
							+ "&projectId=" + projectId
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
							);
					} else {
						alert('����ѡ���¼');
					}
				}
			}],
		sortname : 'planBeginDate',
		sortorder : 'ASC',
		searchitems : {
			display : "��Ա����",
			name : 'personLevel'
		}
	});
});