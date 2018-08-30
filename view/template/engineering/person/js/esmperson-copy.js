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
        var rowNumAll = $("#importTable").yxeditgrid("getCurRowNum");
		for(i=0; i < rowNumAll; i++){
			var planBeginDate = $("#importTable").yxeditgrid("getCmpByRowAndCol",i,"planBeginDate");
            if(treeNode.planBeginDate == '0000-00-00'){
            	planBeginDate.val("");
            }else{
            	planBeginDate.val(treeNode.planBeginDate);
            }
            var planEndDate = $("#importTable").yxeditgrid("getCmpByRowAndCol",i,"planEndDate");
            if(treeNode.planEndDate == '0000-00-00'){
            	planEndDate.val("");
            }else{
            	planEndDate.val(treeNode.planEndDate);
            }
            var days = $("#importTable").yxeditgrid("getCmpByRowAndCol",i,"days");
            if(treeNode.days == '0000-00-00'){
            	days.val("");
            }else{
            	days.val(treeNode.days);
            }
            calPersonBatch(i);
		}

	}
	$("#importTable").yxeditgrid({
		objName : 'esmEditArr',
		url : '?model=engineering_person_esmperson&action=toCopylistJson&ids='+ $("#ids").val(),
		// type:'edit',
		isAdd : false,
		param : {
			projectId : $("#projectId").val(),
			ids : $("#ids").val()
		},
		colModel : [{
			display : '人员等级',
			name : 'personLevel',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '人员级别id',
			name : 'personLevelId',
			readonly : true,
			type : 'hidden'
		}, {
			display : '需求开始日期',
			name : 'planBeginDate',
			type : 'date',
			tclass : 'txtmiddle Wdate',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planEndDate = $(this).val();
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch(rowNum);
					}
				}
			}
		}, {
			display : '需求结束日期',
			name : 'planEndDate',
			type : 'date',
			tclass : 'txtmiddle Wdate',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planEndDate = $(this).val();
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();
					if(planBeginDate != "" && planEndDate != ""){
						var days = DateDiff(planBeginDate,planEndDate) + 1 ;
						g.getCmpByRowAndCol(rowNum,'days').val(days);
						calPersonBatch(rowNum);
					}
				}
			}
		}, {
			display : '天数',
			name : 'days',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					calPersonBatch($(this).data("rowNum"));
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '计量系数',
			name : 'coefficient',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '人工天数',
			name : 'personDays',
			tclass : 'txtshort'
		}, {
			display : '人力成本',
			name : 'personCostDays',
			tclass : 'txtshort'
		}, {
			display : '人力成本金额',
			name : 'personCost',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '项目id',
			name : 'projectId',
			readonly : true,
			type : 'hidden'
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
