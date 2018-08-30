$(document).ready(function() {

	//�����˺Ż���û�����ǰ����Ϣ
	$.ajax({
		type : "POST",
		url : "?model=hr_personnel_personnel&action=getPersonnelSimpleInfo_d",
		async : false,
		data : {
			userAccount : $("#userAccount").val()
		},
		success: function(data){
			var dataObj = eval("(" + data + ")");
			var myDate = new Date();
			var curYear = myDate.getFullYear();
			$("#preUnitTypeName").val(dataObj.companyType);
			$("#preBelongDeptName").val(dataObj.belongDeptName);
			$("#preBelongDeptCode").val(dataObj.belongDeptCode);
			$("#preBelongDeptId").val(dataObj.belongDeptId);
			$("#userNo").val(dataObj.userNo);
			$("#preDeptCode").val(dataObj.deptCode);
			$("#preDeptName").val(dataObj.deptName);
			$("#preDeptId").val(dataObj.deptId);
			$("#preDeptNameS").val(dataObj.deptNameS);
			$("#preDeptCodeS").val(dataObj.deptCodeS);
			$("#preDeptNameT").val(dataObj.deptNameT);
			$("#preDeptCodeT").val(dataObj.deptCodeT);
			$("#preDeptIdT").val(dataObj.deptIdT);
			$("#preDeptNameF").val(dataObj.deptNameF);
			$("#preDeptCodeF").val(dataObj.deptCodeF);
			$("#preDeptIdF").val(dataObj.deptIdF);
			$("#preJobName").val(dataObj.jobName);
			$("#preDeptIdS").val(dataObj.deptIdS);
			$("#preUnitId").val(dataObj.companyId);
			$("#preUnitName").val(dataObj.companyName);
			$("#entryDate").val(dataObj.entryDate);
			$("#preJobId").val(dataObj.jobId);
			$("#preUseAreaId").val(dataObj.regionId);
			$("#preArea").val(dataObj.regionName);
			var entryYear = $("#entryDate").val().substr(0,4);
			if (entryYear != "" && entryYear != null) {
				if (entryYear > curYear) {
					$("#seniority").val("0");
				} else {
					$("#seniority").val(curYear - entryYear);
				}
			} else {
				$("#seniority").val("0");
			}
			//���ݵ���ǰ��˾���ƣ���õ���ǰ��˾ID  ��ΪgetPersonnelInfo����û�е���ǰ��˾ID��  ��TM���ڶ��ң�
			$.ajax({
				url : "?model=common_otherdatas&action=getCompanyAndAreaInfo",
				success : function(data){
					var dataObj = eval("(" + data + ")");
					var companyName = $("#preUnitName").val();
					for(var i = 0 ;i < dataObj.length ;i++) {
						if(dataObj[i].NameCN == companyName) {
							$("#preUnitId").val(dataObj[i].bid);
						}
					}
				}
			});
		}
	});

	var name = $("#afterUnitId").find("option:selected").html();
	$("#afterUnitName").val(name);
	if($("#preUnitName").val() != name) {
		$("#preUnitNameTip").remove();
			var obj = $("<span>",{
				id: 'preUnitNameTip',
				text: '��˾�䶯',
				style: 'color:red'
			});
			$("#afterUnitName").after(obj);
		$("#isCompanyChange").val(1);
	} else {
		$("#isCompanyChange").val(0);
	}

	//������˾���¼�
	$("#afterUnitId").bind('change', function() {
		var name = $("#afterUnitId").find("option:selected").html();
		$("#afterUnitName").val(name);
		if($("#preUnitName").val() != name){
			$("#preUnitNameTip").remove();
			var obj=$("<span>",{
				id: 'preUnitNameTip',
				text: '��˾�䶯',
				style: 'color:red'
			});
			$("#afterUnitName").after(obj);
			$("#isCompanyChange").val(1);
		}else{
			$("#preUnitNameTip").remove();
			$("#isCompanyChange").val(0);
		}

		$.ajax({
			url: "?model=hr_transfer_transfer&action=companyType",
			data : {
				"id" : $("#afterUnitId").find("option:selected").val()
			},
			type : "post",
			success : function (data){
				$("#afterUnitTypeName").val(data);
			}
		});
	});

	var name = $("#afterUseAreaId").find("option:selected").html();
	$("#afterUseAreaName").val(name);
	if(name != $("#preArea").val()){
		var obj = $("<span>",{
				id: 'preAreaTip',
				text: '����䶯',
				style: 'color:red'
			});
			$("#afterUseAreaId").after(obj);
			$("#isAreaChange").val(1);
	} else {
		$("#isAreaChange").val(0);
	}


	//���������������¼�
	$("#afterUseAreaId").bind('change', function() {
		var name = $("#afterUseAreaId").find("option:selected").html();
		$("#afterUseAreaName").val(name);
		if(name != $("#preArea").val()){
			$("#preAreaTip").remove();
			var obj = $("<span>",{
				id: 'preAreaTip',
				text: '����䶯',
				style: 'color:red'
			});
			$("#afterUseAreaId").after(obj);
			$("#isAreaChange").val(1);
		} else {
			$("#preAreaTip").remove();
			$("#isAreaChange").val(0);
		}
	});

	//��֤������
	validate({
		"userName" : {
			required : true
		},
		"transferType" : {
			required : true
		},
		"afterBelongDeptName" : {
			required : true
		},
		"afterUnitName" : {
			required : true
		},
		"afterJobName" : {
			require : true
		},
		"remark" : {
			require : true
		}
	});

	$("#afterJobName").bind('click', function() {
		if($("#afterBelongDeptName").val()==''){
			alert("����ѡ���������������");
			$(this).val("");
		}
	});

	//��������������
	$("#afterBelongDeptName").yxselect_dept({
		hiddenId : 'afterBelongDeptId',
		event : {
			selectReturn : function(e ,row) {
				if($("#preBelongDeptName").val() != $("#afterBelongDeptName").val()){
					$("#deptTip").remove();
					var obj = $("<span>",{
						id: 'deptTip',
						text: '���ű䶯',
						style: 'color:red'
					});
					$("#afterBelongDeptName").next().next().after(obj);
					$("#isDeptChange").val(1);
				} else {
					$("#deptTip").remove();
					$("#isDeptChange").val(0);
				}

				$("#afterBelongDeptCode").val(row.dept.Depart_x);
				$.ajax({
					type : "POST",
					url : "?model=deptuser_dept_dept&action=getDeptInfo",
					data : {
						deptId : row.dept.id,
						levelflag : row.dept.levelflag
					},
					success: function(data){
						var obj = eval("(" + data + ")");
						//ֱ������
						$("#afterDeptId").val(obj.deptId);
						$("#afterDeptName").val(obj.deptName);
						$("#afterDeptCode").val(obj.deptCode);
						//��������
						$("#afterDeptIdS").val(obj.deptIdS);
						$("#afterDeptNameS").val(obj.deptNameS);
						$("#afterDeptCodeS").val(obj.deptCodeS);
						//��������
						$("#afterDeptIdT").val(obj.deptIdT);
						$("#afterDeptNameT").val(obj.deptNameT);
						$("#afterDeptCodeT").val(obj.deptCodeT);
						//�ļ�����
						$("#afterDeptIdF").val(obj.deptIdF);
						$("#afterDeptNameF").val(obj.deptNameF);
						$("#afterDeptCodeF").val(obj.deptCodeF);
					}
				});

				$("#afterJobName").val("");
				$("#afterJobId").val('');
				$("#afterJobName").yxcombogrid_position("remove");
				//ְλѡ��
				$("#afterJobName").yxcombogrid_position({
					hiddenId : 'afterJobId',
					isShowButton : false,
					width : 350,
					gridOptions : {
						param : {
							deptId : row.dept.id
						},
						event : {
							row_dblclick : function(e,row,data){
								if(data.name != $("#preJobName").val()){
									$("#jobNameTip").remove();
									var obj = $("<span>",{
										id: 'jobNameTip',
										text: 'ְλ�䶯',
										style: 'color:red'
									});
									$("#afterJobName").next().after(obj);
									$("#isJobChange").val(1);
								} else {
									$("#jobNameTip").remove();
									$("#isJobChange").val(0);
								}
							}
						}
					}
				});
				$("#afterJobName").yxcombogrid_position("show");
			}
		}
	});
});

//ֱ���ύ
function toSubmit(){
	$("#status").val("1");
	document.getElementById('form1').action = "?model=hr_transfer_transfer&action=add";
}