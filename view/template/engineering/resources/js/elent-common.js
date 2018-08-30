//提交或保存时改变隐藏值
function setConfirm(thisType){
	$("#status").val(thisType);
}

//保存,提交时进行相关验证
function checkSubmit(type) {
	var objGrid = $("#importTable");
	//获取设备的行数
	var curRowNum = objGrid.yxeditgrid("getCurShowRowNum");
	//日期验证
	for(var i = 0; i < curRowNum ; i++){
		var beginDate = objGrid.yxeditgrid("getCmpByRowAndCol",i,"beginDate").val();
		var endDateObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"endDate");
		var days = DateDiff(beginDate,endDateObj.val());

		if(days < 0){
			alert("预计归还日期不能早于预计领用日期");
			endDateObj.focus();
			return false;
		}
		if(days > 30){
			if($("#rcContractType").val() != ""){
				if($("#rcContractType").val() == "GCXMYD-04"){//接收项目为试用项目
					alert("接收项目为试用项目，借用时间不能超过1个月");
					endDateObj.focus();
					return false;
				}
			}else{
				if($("#rcProjectId").val() != "" && $("#rcProjectId").val() != "0"){
					//验证项目为是否试用项目
					var isPK = false;
					$.ajax({
		                type: "POST",
		                url: "?model=engineering_project_esmproject&action=isPK",
		                data: {'projectId': $("#rcProjectId").val()},
		                async: false,
		                success: function (data) {
		                    if (data == 1) {
		                        isPK = true;
		                    }
		                }
		            });
					if(isPK){
						alert("接收项目为试用项目，借用时间不能超过1个月");
						endDateObj.focus();
						return false;
					}
				}else{//接收项目为空，则转借到个人名下
					alert("转借到个人名下时，借用时间不能超过1个月");
					endDateObj.focus();
					return false;
				}
			}
		}
		//新增页面，验证数量
		if(type == 'add'){
			var maxNum = objGrid.yxeditgrid("getCmpByRowAndCol",i,"maxNum").val();
			var numObj = objGrid.yxeditgrid("getCmpByRowAndCol",i,"number");
			var number = numObj.val();
			
	        if (!isNum(number)) {
	            alert("转借数量" + "<" + number + ">" + "填写有误!");
	            numObj.focus();
	            return false;
	        }
	        if (number*1 > maxNum*1) {
	            alert("转借数量不能大于在借数量");
	            numObj.focus();
	            return false;
	        }
		}
	}
}