var provinceArr; //缓存的省份数组

$(document).ready(function() {
	//省份
	provinceArr = getProvince();
	validate({
//				"beginDate" : {
//					required : true
//				},
				"endDate" : {
					required : true
				}
			});
	$("#wholeListInfo").yxeditgrid({
		objName : 'suppVerify[wholeList]',
		dir : 'ASC',
		isAddOneRow : false,
		colModel : [ {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_office({
							hiddenId: 'wholeListInfo_cmp_officeId'+rowNum
						});
					}
				},  {
					display : '区域ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				}, {
					display : '省份',
					name : 'provinceId',
					validation : {
						required : true
					},
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : '省份ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '类型',
					name : 'outsourcing',
					type : 'select',
					datacode:'HTWBFS',
					width : 100
				},{
					display : '项目名称',
					name : 'projectName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_esmproject({
							nameCol : 'projectName',
							hiddenId : 'wholeListInfo_cmp_projectId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);
										//区域
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeId").val(data.officeId);
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeName").val(data.officeName);

										//省份和城市
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
										$("#wholeListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
									}
								}
							}
						});
					}
				},  {
					display : '项目ID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '项目编号',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '外包合同号',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
//					validation : {
//						required : true
//					}
				},  {
					display : '外包合同ID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '负责人',
					name : 'principal',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '总进度',
					name : 'scheduleTotal',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '本期进度',
					name : 'presentSchedule',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				}]
	});

	$("#rentListInfo").yxeditgrid({
		objName : 'suppVerify[rentList]',
		dir : 'ASC',
		colModel : [ {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_office({
							hiddenId: 'rentListInfo_cmp_officeId'+rowNum
						});
					}
				},  {
					display : '区域ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				}, {
					display : '省份',
					name : 'provinceId',
					validation : {
						required : true
					},
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : '省份ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '项目名称',
					name : 'projectName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_esmproject({
							nameCol : 'projectName',
							hiddenId : 'rentListInfo_cmp_projectId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);
										//区域
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeId").val(data.officeId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeName").val(data.officeName);

										//省份和城市
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
									}
								}
							}
						});
					}
				},  {
					display : '项目ID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '项目编号',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '外包合同号',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80,
					validation : {
						required : true
					}
				},  {
					display : '外包合同ID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '负责人',
					name : 'principal',
					type : 'txt',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '姓名',
					name : 'userName',
					type : 'txt',
					width :80,
					readonly:true,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxselect_user({
							hiddenId: 'rentListInfo_cmp_userId'+rowNum
						});
					}
				}, {
					display : '姓名ID',
					name : 'userId',
					type : 'txt',
					type:'hidden'
				},{
					display : '本期开始',
					name : 'beginDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '本期结束',
					name : 'endDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '计价天数',
					name : 'feeDay',
					type : 'txt',
					width : 60,
					validation : {
						required : true
					}
				}]
	});

 });

 		function initTemplate(beginDate,endDate){
			$("#rentListInfo").yxeditgrid({
					objName : 'suppVerify[rentList]',
					url : '?model=outsourcing_workverify_suppVerify&action=worklogListJson',
					param : {
						beginDate :beginDate,
						endDate :endDate,
						dir : 'ASC'
					},
					dir : 'ASC',
					realDel : false,
				colModel : [ {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_office({
							hiddenId: 'rentListInfo_cmp_officeId'+rowNum
						});
					}
				},  {
					display : '区域ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				},  {
					display : '日志ID',
					name : 'ids',
//					type : 'txt'
					type:'hidden'
				}, {
					display : '省份',
					name : 'provinceId',
					validation : {
						required : true
					},
					type : 'select',
					width : 70,
					options : provinceArr
				},  {
					display : '省份ID',
					name : 'province',
					type : 'txt',
					type:'hidden'
				}, {
					display : '项目名称',
					name : 'projectName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_esmproject({
							nameCol : 'projectName',
							hiddenId : 'rentListInfo_cmp_projectId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								action : 'myProjectListPageJson',
								param : {'selectstatus' : 'GCXMZT02','isLog' : 1},
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"projectCode").val(data.projectCode);
										//区域
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeId").val(data.officeId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"officeName").val(data.officeName);

										//省份和城市
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"provinceId").val(data.provinceId);
										$("#rentListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"province").val(data.province);
									}
								}
							}
						});
					}
				},  {
					display : '项目ID',
					name : 'projectId',
					type : 'txt',
					type:'hidden'
				},{
					display : '项目编号',
					name : 'projectCode',
					type : 'txt',
					width : 100
				},{
					display : '外包合同号',
					name : 'outsourceContractCode',
					type : 'txt',
					width : 80
//					validation : {
//						required : true
//					}
				},  {
					display : '外包合同ID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '负责人',
					name : 'principal',
					type : 'txt',
					width : 70
				},{
					display : '姓名',
					name : 'userName',
					type : 'txt',
					width :80,
					readonly:true,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxselect_user({
							hiddenId: 'rentListInfo_cmp_userId'+rowNum
						});
					}
				}, {
					display : '姓名ID',
					name : 'userId',
					type : 'txt',
					type:'hidden'
				},{
					display : '本期开始',
					name : 'beginDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '本期结束',
					name : 'endDate',
					type : 'date',
					width : 70,
					validation : {
						required : true
					}
				},{
					display : '计价天数',
					name : 'feeDay',
					type : 'txt',
					width : 60,
					validation : {
						required : true
					}
				}]
					});
	}

 	//根据开始日期和结束日期，获取工作量信息
		function getworkInfo(){
			var beginDate=$("#beginDate").val();
			var endDate=$("#endDate").val();
			if(endDate!=''){
				var s = plusDateInfo('beginDate','endDate');
				if(s < 0) {
					alert("结束日期不能小于开始日期！");
				}else{
					$("#rentListInfo").yxeditgrid('remove');
					initTemplate(beginDate,endDate);
				}
			}
		}

		         //直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_workverify_suppVerify&action=add&addType=submit";
}