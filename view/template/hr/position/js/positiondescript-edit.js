$(function() {
	$("#abilityneedTable").yxeditgrid({
		objName : 'positiondescript[ability]',
		isAddOneRow : true,
		defaultClass : 'txtlong',
		url : '?model=hr_position_ability&action=listJson',
		param : {
			parentId : $("#id").val()
		},
		colModel : [{
			display : '������',
			name : 'featureItem',
			validation : {
				required : true
			}
		}, {
			display : '��������',
			name : 'contents',
			validation : {
				required : true
			}
		}, {
			type : 'hidden',
			name : 'id',
			display : 'id'
		}]
	});
	
	$("#positionName").change(function(){
		if($("#deptName").val()==''){
			alert('����ѡ���ţ�');
			$(this).val('');
		}else{
			var url = "?model=hr_position_positiondescript&action=checkRepeat&deptIdEq="+$("#deptId").val();
			$("#positionName").ajaxCheck({
						url : url,
						alertText : "* ��ְλ�Ѵ���",
						alertTextOk : "* ��ְλ���ƿ���"
			});
		}
	})
	var arr=['superiorPosition','promotionPosition','suborPosition','parallelPosition','rotationPosition','downPosition'];
	$("#deptName").dblclick(function(){
		for(var i=0;i<arr.length;i++){
			$("#"+arr[i]).val("");
			$("#"+arr[i]).yxcombogrid_jobs("remove");	
		}
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		event:{
			selectReturn : function(e,row){				
				var id=row.dept.id;
				for(var index=0;index<arr.length;index++){
					$("#"+arr[index]).yxcombogrid_jobs("remove");
					addComGrid(arr[index],id);
				}
				//$("#positionName").val('');
				var url = "?model=hr_position_positiondescript&action=checkRepeat&deptIdEq="+$("#deptId").val();
				$.ajax({
					url : url,
					data : {validateValue : $("#positionName").val(),validateId : 'positionName',validateError:'positionNameAjaxName' },
					type : 'post',
					dataType : 'json',
					success : function(msg){
					
						if(msg.jsonValidateReturn[2]=='false'){
							alert(row.dept.DEPT_NAME+' �Ѵ��� ��'+$("#positionName").val());
							$("#positionName").val('');
						}
					}
				});
			}
		}
	});
	//����������
	function addComGrid(name,id){
		if($("#deptName").val()==''){
			alert('��ѡ����!');
			return false;
		}
		
		$("#"+name).yxcombogrid_jobs({
			hiddenId : name+'Id',
			width : 350,
			gridOptions : {
				param:{deptId:id}
			}
		});
	}
	for(var i=0;i<arr.length;i++){
		$("#"+arr[i]).attr('readonly',true)
		$("#"+arr[i]).click(function(){
			if($("#deptName").val()==''){				
				alert('��ѡ�����ڲ���!');
				$(this).val('');
			}
		});
	}
	$("#workinfoTable").yxeditgrid({
		objName : 'positiondescript[work]',
		defaultClass : 'txtlong',
		url : '?model=hr_position_work&action=listJson',
		param : {
			parentId : $("#id").val()
		},
		isAddOneRow : true,
		colModel : [{
			display : '����ְ��',
			name : 'jobContents',
			validation : {
				required : true
			}
		}, {
			display : '��������',
			name : 'specificContents',
			validation : {
				required : true
			}
		}, {
			display : 'Ҫ����������ﵽ��Ŀ��',
			name : 'jobTarget',
			validation : {
				required : true
			}
		}, {
			type : 'hidden',
			name : 'id',
			display : 'id'
		}]
	});
	/*$("#deptName").yxselect_dept({
		hiddenId : 'deptCode'
	});*/

	validate({
		"deptName" : {
			required : true
		},
		"rewardGrade" : {
			required : true
		},
		"positionRemark" : {
			required : true
		},
		"professionalKnow" : {
			required : true
		},
		"companyKnow" : {
			required : true
		},
		"workProcess" : {
			required : true
		},
		"positionName" : {
			required : true
		}
	});
	$("#superiorPosition").yxcombogrid_jobs({
		hiddenId : 'superiorPositionId',
		width : 300
	});
	$("#suborPosition").yxcombogrid_jobs({
		hiddenId : 'suborPositionId',
		width : 300
	});
	$("#parallelPosition").yxcombogrid_jobs({
		hiddenId : 'parallelPositionId',
		width : 300
	});
	$("#promotionPosition").yxcombogrid_jobs({
		hiddenId : 'promotionPositionId',
		width : 300
	});
	$("#rotationPosition").yxcombogrid_jobs({
		hiddenId : 'rotationPositionId',
		width : 300
	});
	$("#downPosition").yxcombogrid_jobs({
		hiddenId : 'downPositionId',
		width : 300
	});
});
