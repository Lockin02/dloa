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
            var days = $("#importTable").yxeditgrid("getCmpByRowAndCol",i,"useDays");
            if(treeNode.days == '0000-00-00'){
            	days.val("");
            }else{
            	days.val(treeNode.days);
            }
            //备注
            var remark = $("#importTable").yxeditgrid("getCmpByRowAndCol",i,"remark");
        	remark.val(treeNode.remark);

        	//工作内容
            var workContent = $("#importTable").yxeditgrid("getCmpByRowAndCol",i,"workContent");
        	workContent.val(treeNode.workContent);

        	calResourceBatch(i);
		}

	}
	$("#importTable").yxeditgrid({
		objName : 'esmEditArr',
		url : '?model=engineering_resources_esmresources&action=toCopylistJson&ids='+ $("#ids").val(),
		// type:'edit',
		isAdd : false,
		param : {
			projectId : $("#projectId").val(),
			ids : $("#ids").val()
		},
		colModel : [{
			display : '设备名称',
			name : 'resourceName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '设备id',
			name : 'resourceId',
			readonly : true,
			type : 'hidden'
		}, {
			display : '设备编码',
			name : 'resourceCode',
			readonly : true,
			type : 'hidden'
		}, {
			display : '设备类型',
			name : 'resourceTypeName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		},{
			display : '设备类型id',
			name : 'resourceTypeId',
			type : 'hidden',
			readonly : true
		}, {
			display : '设备性质',
			name : 'resourceNature',
//			datacode : 'GCXMZYXZ'
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
        			calResourceBatch(rowNum);
				}
			}
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '领用日期',
			name : 'planBeginDate',
			type : 'date',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planBeginDate = $(this).val();
					var planEndDate = g.getCmpByRowAndCol(rowNum,'planEndDate').val();
					var useDays = g.getCmpByRowAndCol(rowNum,'useDays');
                    var days = DateDiff(planBeginDate,planEndDate) + 1;
                    useDays.val(days);
        			calResourceBatch(rowNum);
				}
			}
		}, {
			display : '归还日期',
			name : 'planEndDate',
			type : 'date',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
					var g = $(this).data("grid");
					var planEndDate = $(this).val();
					var planBeginDate = g.getCmpByRowAndCol(rowNum,'planBeginDate').val();;
					var useDays = g.getCmpByRowAndCol(rowNum,'useDays');
                    var days = DateDiff(planBeginDate,planEndDate) + 1;
                    useDays.val(days);
        			calResourceBatch(rowNum);
				}
			}
		}, {
			display : '使用天数',
			name : 'useDays',
			validation : {
				required : true
			},
			tclass : 'txtshort',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
        			calResourceBatch(rowNum);
				}
			}
		}, {
			display : '单设备折旧',
			name : 'price',
			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '设备成本',
			name : 'amount',
			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true
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
			display : '工作内容',
			name : 'workContent'
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
