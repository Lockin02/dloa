$(document).ready(function() {


    /******������������*********/

	//����������
	var projectId = $("#projectId").val();
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
        $("#activityId").val(treeNode.id);
        $("#activityName").val(treeNode.name);
	}
	$("#importTable").yxeditgrid({
		objName : 'esmEditArr',
		url : '?model=engineering_budget_esmbudget&action=toCopylistJson&ids='+ $("#ids").val(),
		// type:'edit',
		isAdd : false,
		param : {
			projectId : $("#projectId").val(),
			ids : $("#ids").val()
		},
		colModel : [{
			display : '���÷���',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '����id',
			name : 'parentId',
			readonly : true,
			type : 'hidden'
		}, {
			display : 'Ԥ������',
			name : 'budgetName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		},{
			display : '��Դ����id',
			name : 'budgetId',
			type : 'hidden',
			readonly : true
		}, {
			display : '��Դ����',
			name : 'resourceNature',
//			datacode : 'GCXMZYXZ'
			type : 'hidden'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '����1',
			name : 'numberOne',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '����2',
			name : 'numberTwo',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '���',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '��Ŀid',
			name : 'projectId',
			readonly : true,
			type : 'hidden',
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '��Ŀ���',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '��ע˵��',
			name : 'remark'
		}]
	})

	/**
	 * ��֤��Ϣ(�õ��ӱ���֤ǰ��������ʹ��validate)
	 */
	validate({

	});
});
