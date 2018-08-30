$(function() {
	
	$("#abilityneedTable").yxeditgrid({
		objName : 'positiondescript[ability]',
		defaultClass : 'txtlong',
		isFristRowDenyDel : true,
		isAddOneRow : true,
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
		}]
	});

	$("#workinfoTable").yxeditgrid({
		objName : 'positiondescript[work]',
		defaultClass : 'txtlong',
		isFristRowDenyDel : true,
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
		}]
	});
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
							alert(row.dept.DEPT_NAME+'�Ѵ��ڣ�'+$("#positionName").val());
							$("#positionName").val('');
						}
					}
				});	
			}
		}
	});
	rewardGrade = getData('HRGZJB');
	addDataToSelect(rewardGrade, 'rewardGradeCode');
	education = getData('HRJYXL');
	addDataToSelect(education, 'education');
	
	
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
		}
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
	
	

});
//�������ʱˢ�¸�������������Ҫ����������Ƹ�ƻ�
function refreshContent(){
	if(typeof(window.opener.refreshContent)!='undefined'){
		window.opener.refreshContent();
	}
}