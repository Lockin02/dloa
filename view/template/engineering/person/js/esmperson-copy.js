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
			display : '��Ա�ȼ�',
			name : 'personLevel',
			readonly : true,
			tclass : 'readOnlyTxtMiddle'
		}, {
			display : '��Ա����id',
			name : 'personLevelId',
			readonly : true,
			type : 'hidden'
		}, {
			display : '����ʼ����',
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
			display : '�����������',
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
			display : '����',
			name : 'days',
			readonly : true,
			tclass : 'readOnlyTxtMiddle',
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					calPersonBatch($(this).data("rowNum"));
				}
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '����ϵ��',
			name : 'coefficient',
			tclass : 'readOnlyTxtShort',
			readonly : true
		}, {
			display : '�˹�����',
			name : 'personDays',
			tclass : 'txtshort'
		}, {
			display : '�����ɱ�',
			name : 'personCostDays',
			tclass : 'txtshort'
		}, {
			display : '�����ɱ����',
			name : 'personCost',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '��Ŀid',
			name : 'projectId',
			readonly : true,
			type : 'hidden'
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
