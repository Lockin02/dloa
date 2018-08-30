$(document).ready(function() {


    /******新树部分设置*********/

	//树基本设置
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

	//加载树
	treeObj = $.fn.zTree.init($("#tree"), setting);

	//第一次加载的时候刷新根节点
	var firstAsy = true;
	// 加载成功后执行
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

	//树的双击事件
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
			display : '费用分类',
			name : 'parentName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '分类id',
			name : 'parentId',
			readonly : true,
			type : 'hidden'
		}, {
			display : '预算名称',
			name : 'budgetName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		},{
			display : '资源类型id',
			name : 'budgetId',
			type : 'hidden',
			readonly : true
		}, {
			display : '资源性质',
			name : 'resourceNature',
//			datacode : 'GCXMZYXZ'
			type : 'hidden'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '数量1',
			name : 'numberOne',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '数量2',
			name : 'numberTwo',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '金额',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '项目id',
			name : 'projectId',
			readonly : true,
			type : 'hidden',
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '项目编号',
			name : 'projectCode',
			type : 'hidden'
		}, {
			display : '项目名称',
			name : 'projectName',
			type : 'hidden'
		}, {
			display : '备注说明',
			name : 'remark'
		}]
	})

	/**
	 * 验证信息(用到从表验证前，必须先使用validate)
	 */
	validate({

	});
});
