$(function() {
	
	$("#abilityneedTable").yxeditgrid({
		objName : 'positiondescript[ability]',
		defaultClass : 'txtlong',
		isFristRowDenyDel : true,
		isAddOneRow : true,
		colModel : [{
			display : '特征项',
			name : 'featureItem',
			validation : {
				required : true
			}
		}, {
			display : '具体描述',
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
			display : '工作职责',
			name : 'jobContents',
			validation : {
				required : true
			}
		}, {
			display : '具体任务',
			name : 'specificContents',
			validation : {
				required : true
			}
		}, {
			display : '要求输出结果或达到的目标',
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
							alert(row.dept.DEPT_NAME+'已存在：'+$("#positionName").val());
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
			alert('请先选择部门！');
			$(this).val('');
		}else{
			var url = "?model=hr_position_positiondescript&action=checkRepeat&deptIdEq="+$("#deptId").val();
			$("#positionName").ajaxCheck({
						url : url,
						alertText : "* 该职位已存在",
						alertTextOk : "* 该职位名称可用"
			});
		}
	})
	
	
	
	
	//添加下拉表格
	function addComGrid(name,id){
		if($("#deptName").val()==''){
			alert('请选择部门!');
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
				alert('请选择所在部门!');
				$(this).val('');
			}
		});
	}
	
	

});
//点击保存时刷新父窗口下拉框，主要用于新增招聘计划
function refreshContent(){
	if(typeof(window.opener.refreshContent)!='undefined'){
		window.opener.refreshContent();
	}
}