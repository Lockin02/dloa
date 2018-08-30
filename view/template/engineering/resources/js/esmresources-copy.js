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
            //��ע
            var remark = $("#importTable").yxeditgrid("getCmpByRowAndCol",i,"remark");
        	remark.val(treeNode.remark);

        	//��������
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
			display : '�豸����',
			name : 'resourceName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '�豸id',
			name : 'resourceId',
			readonly : true,
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceCode',
			readonly : true,
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'resourceTypeName',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		},{
			display : '�豸����id',
			name : 'resourceTypeId',
			type : 'hidden',
			readonly : true
		}, {
			display : '�豸����',
			name : 'resourceNature',
//			datacode : 'GCXMZYXZ'
			type : 'hidden'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					var rowNum = $(this).data("rowNum");
        			calResourceBatch(rowNum);
				}
			}
		}, {
			display : '��λ',
			name : 'unit',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '��������',
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
			display : '�黹����',
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
			display : 'ʹ������',
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
			display : '���豸�۾�',
			name : 'price',
			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�豸�ɱ�',
			name : 'amount',
			type : 'money',
			tclass : 'readOnlyTxtShort',
			readonly : true
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
			display : '��������',
			name : 'workContent'
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
