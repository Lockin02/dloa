$(document).ready(function() {
	//������˾���¼�
	$("#afterUnitId").bind('change', function() {
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
		})
	});

	//���������������¼�
	$("#afterUseAreaId").bind('change', function() {
		var name = $("#afterUseAreaId").find("option:selected").html();
		$("#afterUseAreaName").val(name);
		if(name != $("#preArea").val()) {
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

	//������Ա������¼�
	$("#afterPersonClassCode").bind('change' ,function() {
		var name = $("#afterPersonClassCode").find("option:selected").html();
		$("#afterPersonClass").val(name);
		if(name != $("#prePersonClass").val()) {
			$("#personClassTip").remove();
			var obj = $("<span>",{
				id: 'personClassTip',
				text: '��Ա����䶯',
				style: 'color:red'
			});
			$("#afterPersonClassCode").after(obj);
			$("#isClassChange").val(1);
		} else {
			$("#personClassTip").remove();
			$("#isClassChange").val(0);
		}
	});

	//��֤������
	validate({
		"userName" : {
			required : true
		},
		"afterBelongDeptName" : {
			required : true
		},
		"afterUnitId" : {
			required : true
		},
		"afterJobName" : {
			require : true
		},
		"afterUseAreaId" : {
			require : true
		},
		"afterPersonClassCode" : {
			require : true
		},
		"remark" : {
			require : true
		}
	});

	//������Ա�����䶯������ǰ��ϢҲ���ű䶯
	$("#userName").yxselect_user({
		hiddenId: 'userAccount',
		event : {
			select : function(e,row) {
				$.ajax({
					type: "POST",
					url: "?model=hr_personnel_personnel&action=getPersonnelInfo",
					data: {
						"userAccount" : row.val
					},
					async: false,
					success: function(data) {
						if(data != "") {
							var dataObj = eval("(" + data +")");
							var myDate = new Date();
							var curYear = myDate.getFullYear();
							$("#preBelongDeptName").val(dataObj.belongDeptName);
							$("#preBelongDeptId").val(dataObj.belongDeptId);
							$("#preBelongDeptCode").val(dataObj.belongDeptCode);
							$("#entryDate").val(dataObj.entryDate);
							$("#preUnitName").val(dataObj.companyName);
							$("#preUnitId").val(dataObj.companyId);
							$("#preDeptNameS").val(dataObj.deptNameS);
							$("#preDeptCodeS").val(dataObj.deptCodeS);
							$("#preDeptNameT").val(dataObj.deptNameT);
							$("#preDeptCodeT").val(dataObj.deptCodeT);
							$("#preDeptIdT").val(dataObj.deptIdT);
							$("#preDeptNameF").val(dataObj.deptNameF);
							$("#preDeptCodeF").val(dataObj.deptCodeF);
							$("#preDeptIdF").val(dataObj.deptIdF);
							$("#preArea").val(dataObj.regionName);
							$("#userNo").val(dataObj.userNo);
							$("#userName").val(dataObj.staffName);
							$("#preJobName").val(dataObj.jobName);
							$("#preJobId").val(dataObj.jobId);
							$("#preDeptIdS").val(dataObj.deptIdS);
							$("#preUseAreaId").val(dataObj.regionId);
							$("#prePersonClass").val(dataObj.personnelClassName);
							$("#prePersonClassCode").val(dataObj.personnelClass);
							var entryYear = $("#entryDate").val().substr(0 ,4);
							if(entryYear != "" && entryYear != null) {
								if(entryYear > curYear) {
									$("#seniority").val("0");
								} else {
									$("#seniority").val(curYear-entryYear);
								}
							} else {
								$("#seniority").val("0");
							}
							//���ݵ���ǰ��˾���ƣ���õ���ǰ��˾ID  ��ΪgetPersonnelInfo����û�е���ǰ��˾ID��  ��TM���ڶ��ң�
							$.ajax({
								url: "?model=common_otherdatas&action=getCompanyAndAreaInfo",
								success: function(data) {
									var dataObj = eval("(" + data +")");
									var companyName = $("#preUnitName").val();
									for(var i = 0 ;i < dataObj.length ;i++) {
										if(dataObj[i].NameCN == companyName) {
											$("#preUnitId").val(dataObj[i].bid);
										}
									}
								}
							});
						}
						if($("#preUnitName").val() != $("#afterUnitName")) {
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
						if($("#afterJobName").val().length != 0) {
							if($("#afterJobName").val()!=$("#preJobName").val()) {
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
						if($("#afterUseAreaName").val() != $("#preArea").val()) {
							$("#preAreaTip").remove();
							var obj = $("<span>",{
								id: 'preAreaTip',
								text: '����䶯',
								style: 'color:red'
							});
							$("#afterUseAreaId").after(obj);
							$("#isAreaChange").val(1);
						}else{
							$("#preAreaTip").remove();
							$("#isAreaChange").val(0);
						}
						if($("#afterBelongDeptName").val().length != 0) {
							if($("#preBelongDeptName").val() != $("#afterBelongDeptName").val()) {
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
						}
						$("#afterPersonClassCode").trigger('change'); //��Ա����
					}
				});
			}
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
			selectReturn : function(e,row){
				if($("#preBelongDeptName").val()!=$("#afterBelongDeptName").val()){
					$("#deptTip").remove();
					var obj=$("<span>",{
						id: 'deptTip',
						text: '���ű䶯',
						style: 'color:red'
					});
					$("#afterBelongDeptName").next().next().after(obj);
					$("#isDeptChange").val(1);
				}else{
					$("#deptTip").remove();
					$("#isDeptChange").val(0);
				}
				$("#afterBelongDeptCode").val(row.dept.Depart_x);
				$.ajax({
					type: "POST",
					url: "?model=deptuser_dept_dept&action=getDeptInfo",
					data: {
						"deptId" : row.dept.id,
						"levelflag" : row.dept.levelflag
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
					width:350,
					gridOptions : {
						param : {
							deptId:row.dept.id
						},
						event : {
							'row_dblclick' : function(e,row,data){
								if(data.name!=$("#preJobName").val()){
									$("#jobNameTip").remove();
									var obj = $("<span>",{
										id: 'jobNameTip',
										text: 'ְλ�䶯',
										style: 'color:red'
									});
									$("#afterJobName").next().after(obj);
									$("#isJobChange").val(1);
								}else{
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

//ֱ���ύ����
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_transfer_transfer&action=add&actType=audit&addType=manager";
}