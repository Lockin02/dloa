$(document).ready(function() {

	var addType=$("#addType").val();
	if(addType=="addEdit"){
		$("#closeButn").hide();
	}

	var name = $("#afterUnitId").find("option:selected").html();
	$("#afterUnitName").val(name);
	if($("#preUnitName").val() != name) {
		$("#preUnitNameTip").remove();
			var obj = $("<span>",{
				id: 'preUnitNameTip',
				text: '公司变动',
				style: 'color:red'
			});
			$("#afterUnitName").after(obj);
		$("#isCompanyChange").val(1);
	} else {
		$("#isCompanyChange").val(0);
	}

	//调动后公司绑定事件
	$("#afterUnitId").bind('change', function() {
		var name = $("#afterUnitId").find("option:selected").html();
		$("#afterUnitName").val(name);
		if($("#preUnitName").val() != name) {
			$("#preUnitNameTip").remove();
			var obj = $("<span>",{
				id: 'preUnitNameTip',
				text: '公司变动',
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
				text: '区域变动',
				style: 'color:red'
			});
			$("#afterUseAreaId").after(obj);
			$("#isAreaChange").val(1);
	} else {
		$("#isAreaChange").val(0);
	}

	//调动后归属区域绑定事件
	$("#afterUseAreaId").bind('change', function() {
		var name = $("#afterUseAreaId").find("option:selected").html();
		$("#afterUseAreaName").val(name);
		if(name != $("#preArea").val()) {
			$("#preAreaTip").remove();
			var obj = $("<span>",{
				id: 'preAreaTip',
				text: '区域变动',
				style: 'color:red'
			});
			$("#afterUseAreaId").after(obj);
			$("#isAreaChange").val(1);
		}else{
			$("#preAreaTip").remove();
			$("#isAreaChange").val(0);
		}
	});

	//调动人员分类绑定事件
	$("#afterPersonClassCode").bind('change' ,function() {
		var name = $("#afterPersonClassCode").find("option:selected").html();
		$("#afterPersonClass").val(name);
		if(name != $("#prePersonClass").val()) {
			$("#personClassTip").remove();
			var obj = $("<span>",{
				id: 'personClassTip',
				text: '人员分类变动',
				style: 'color:red'
			});
			$("#afterPersonClassCode").after(obj);
			$("#isClassChange").val(1);
		} else {
			$("#personClassTip").remove();
			$("#isClassChange").val(0);
		}
	}).trigger('change');

	//验证必填项
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
		"afterPersonClassCode" : {
			require : true
		},
		"remark" : {
			require : true
		}
	});

	if($("#afterJobName").val() != $("#preJobName").val()) {
		$("#jobNameTip").remove();
		var obj = $("<span>",{
			id: 'jobNameTip',
			text: '职位变动',
			style: 'color:red'
		});
		$("#afterJobName").next().after(obj);
		$("#isJobChange").val(1);
	} else {
		$("#jobNameTip").remove();
		$("#isJobChange").val(0);
	}

	//调动后岗位
	$("#afterJobName").yxcombogrid_position({
		hiddenId: 'afterJobId',
		isShowButton : false,
		width:350,
		gridOptions:{
			param : {
				deptId : $("#afterBelongDeptId").val()
			},
			event : {
				'row_dblclick' : function(e,row,data){
					if(data.name != $("#preJobName").val()) {
						$("#jobNameTip").remove();
						var obj = $("<span>",{
							id: 'jobNameTip',
							text: '职位变动',
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


	if($("#preBelongDeptName").val() != $("#afterBelongDeptName").val()){
		$("#deptTip").remove();
			var obj=$("<span>",{
			id: 'deptTip',
			text: '部门变动',
			style: 'color:red'
		});
		$("#afterBelongDeptName").next().next().after(obj);
		$("#isDeptChange").val(1);
	}else{
		$("#deptTip").remove();
		$("#isDeptChange").val(0);
	}


	$("#afterBelongDeptName").yxselect_dept({
		hiddenId : 'afterBelongDeptId',
		event : {
			selectReturn : function(e,row){
				if($("#preBelongDeptName").val()!=$("#afterBelongDeptName").val()){
					$("#deptTip").remove();
					var obj=$("<span>",{
						id: 'deptTip',
						text: '部门变动',
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
					data: {"deptId" : row.dept.id,"levelflag":row.dept.levelflag},
					success: function(data){
						var obj = eval("(" + data + ")");
						//直属部门
						$("#afterDeptId").val(obj.deptId);
						$("#afterDeptName").val(obj.deptName);
						$("#afterDeptCode").val(obj.deptCode);
						//二级部门
						$("#afterDeptIdS").val(obj.deptIdS);
						$("#afterDeptNameS").val(obj.deptNameS);
						$("#afterDeptCodeS").val(obj.deptCodeS);
						//三级部门
						$("#afterDeptIdT").val(obj.deptIdT);
						$("#afterDeptNameT").val(obj.deptNameT);
						$("#afterDeptCodeT").val(obj.deptCodeT);
					}
				});
				$("#afterJobName").val("");
				$("#afterJobId").val('');
				$("#afterJobName").yxcombogrid_position("remove");
				//职位选择
				$("#afterJobName").yxcombogrid_position({
					hiddenId : 'afterJobId',
					isShowButton : false,
					width:350,
					gridOptions : {
						param:{deptId:row.dept.id},
						event : {
							'row_dblclick' : function(e,row,data){
								if(data.name!=$("#preJobName").val()){
									$("#jobNameTip").remove();
									var obj=$("<span>",{
										id: 'jobNameTip',
										text: '职位变动',
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

	$.ajax({
		url : 'index1.php?model=hr_personnel_personnel&action=listJson',
		ansyc : false,
		success : function(data) {
			data = eval("("+data+")");
			var userName = $("#managerName").val();
			for(var i = 0 ;i < data.length ;i++) {
				if(data[i].userName == userName){
					$("#entryDate").val(data[i].entryDate);
					$("#preUnitName").val(data[i].companyName);
				}
			}
		}
	});

});

//直接提交审批
function toSubmit() {
	$("#status").val("7");
	document.getElementById('form1').action = "?model=hr_transfer_transfer&action=edit&actType=audit";
}