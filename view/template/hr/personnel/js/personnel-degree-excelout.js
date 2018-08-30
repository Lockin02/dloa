$(document).ready(function() {

		//Ա������
		$("#staffName").yxselect_user({
			hiddenId: "userNo"
		});

		//��������
		$("#belongDeptName").yxselect_dept({
			hiddenId: "belongDeptId"
		});

		//��������
		$("#officeName").yxcombogrid_office({
			hiddenId : 'officeId',
			gridOptions : {
				showcheckbox : false
			}
		});

		//ְλ
		$("#jobName").yxcombogrid_position({
			hiddenId: "jobId"
		});

		//�����ȼ�
		$("#personLevel").yxcombogrid_eperson({
			hiddenId : 'personLevelId',
			width : '400px',
			gridOptions : {
				showcheckbox : false
			}
		});


		//��˾ѡ����¼�
		$("#companyTypeCode").bind('change', function() {
			var companyType=$(this).val();
			if($(this).val()!==""){
				//���ݹ�˾���ͻ�ȡ��˾���ݣ�1���ţ�0�ӹ�˾
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