$(document).ready(function() {

		//员工姓名
		$("#staffName").yxselect_user({
			hiddenId: "userNo"
		});

		//所属部门
		$("#belongDeptName").yxselect_dept({
			hiddenId: "belongDeptId"
		});

		//归属区域
		$("#officeName").yxcombogrid_office({
			hiddenId : 'officeId',
			gridOptions : {
				showcheckbox : false
			}
		});

		//职位
		$("#jobName").yxcombogrid_position({
			hiddenId: "jobId"
		});

		//技术等级
		$("#personLevel").yxcombogrid_eperson({
			hiddenId : 'personLevelId',
			width : '400px',
			gridOptions : {
				showcheckbox : false
			}
		});


		//公司选择绑定事件
		$("#companyTypeCode").bind('change', function() {
			var companyType=$(this).val();
			if($(this).val()!==""){
				//根据公司类型获取公司数据：1集团，0子公司
				$.ajax({
				    type: "POST",
				    url: "?model=deptuser_branch_branch&action=getBranchStr",
				    data: {"type" :companyType},
				    async: true,
				    success: function(data){
				    	if(data != ""){
				    		$("#companyName").html(data);
				        }else{
				        	$("#companyName").html("");
				        }
					}
				});
			}
		});



})