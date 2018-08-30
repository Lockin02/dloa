
$(document).ready(function() {

	$("#wholeListInfo").yxeditgrid({
		objName : 'workVerify[wholeList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val(),
			outsourcing :''
		},
		dir : 'ASC',
		colModel : [ {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50
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
					width : 120
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
				},  {
					display : '外包合同ID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '外包公司',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
					display : '外包公司ID',
					name : 'outsourceSuppId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '负责人',
					name : 'principal',
					type : 'txt',
					width : 70
				},{
					display : '总进度',
					name : 'scheduleTotal',
					type : 'txt',
					width : 70
				},{
					display : '本期进度',
					name : 'presentSchedule',
					type : 'txt',
					width : 70
				}]
	});

	$("#rentListInfo").yxeditgrid({
		objName : 'workVerify[rentList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val()
		},
		dir : 'ASC',
		colModel : [ {
					display : '区域',
					name : 'officeName',
					type : 'txt',
					width :50
				},  {
					display : '区域ID',
					name : 'officeId',
//					type : 'txt'
					type:'hidden'
				}, {
					display : '省份',
					name : 'provinceId',
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
					width : 120
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
				},  {
					display : '外包合同ID',
					name : 'outsourceContractId',
					type : 'txt',
					type:'hidden'
				},{
					display : '外包公司',
					name : 'outsourceSupp',
					type : 'txt',
					width : 80
				},  {
					display : '外包公司ID',
					name : 'outsourceSuppId',
					type : 'txt',
					type:'hidden'
				}, {
					display : '负责人',
					name : 'principal',
					type : 'txt',
					width : 70
				},{
					display : '姓名',
					name : 'userName',
					type : 'txt',
					width :80,
					readonly:true
				}, {
					display : '姓名ID',
					name : 'userId',
					type : 'txt',
					type:'hidden'
				},{
					display : '本期开始',
					name : 'beginDate',
					type : 'date',
					width : 70
				},{
					display : '本期结束',
					name : 'endDate',
					type : 'date',
					width : 70
				},{
					display : '计价天数',
					name : 'feeDay',
					type : 'txt',
					width : 60
				}]
	});

 });

