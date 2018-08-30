var provinceArr; //缓存的省份数组

$(document).ready(function() {
	//省份
	provinceArr = getProvince();
	$("#wholeListInfo").yxeditgrid({
		objName : 'workVerify[wholeList]',
		url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
		param : {
			parentId :$("#id").val(),
			outsourcingArr :'HTWBFS-01,HTWBFS-03',
			purchAuditState:'0'
		},
		dir : 'ASC',
		isAddAndDel : false,
		colModel : [  {
					type : 'hidden',
					name : 'id',
					display : 'id'
				},{
					display : '区域',
					name : 'officeName',
					type : 'statictext',
					width :50
				},  {
					display : '省份',
					name : 'province',
					type : 'txt',
					width :50,
					type:'statictext'
				}, {
					display : '类型',
					name : 'outsourcingName',
					type : 'statictext',
					width :50
				},{
					display : '项目名称',
					name : 'projectName',
					type : 'statictext',
					width : 120
				},  {
					display : '项目编号',
					name : 'projectCode',
					type : 'statictext',
					width : 100
				},{
					display : '外包合同号',
					name : 'outsourceContractCode',
					type : 'statictext',
					width : 80
				},  {
					display : '外包公司',
					name : 'outsourceSupp',
					type : 'statictext',
					width : 80
				},  {
					display : '负责人',
					name : 'principal',
					type : 'statictext',
					width : 70
				},{
					display : '总进度',
					name : 'scheduleTotal',
					type : 'statictext',
					width : 70
				},{
					display : '本期进度',
					name : 'presentSchedule',
					type : 'statictext',
					width : 70
				}, {
					display : '总金额',
					name : 'feeTotal',
					width : 100
				}]
	});

	$("#rentListInfo").yxeditgrid({
				objName : 'workVerify[rentList]',
				url : '?model=outsourcing_workverify_verifyDetail&action=listJson',
				param : {
					parentId :$("#id").val(),
					outsourcingArr :'HTWBFS-02',
					purchAuditState:'0'
				},
				dir : 'ASC',
				isAddAndDel : false,
				realDel : true,
				colModel : [ {
					type : 'hidden',
					name : 'id',
					display : 'id'
				},{
					display : '区域',
					name : 'officeName',
					type : 'statictext',
					width :50
				},  {
					display : '省份',
					name : 'province',
					type : 'statictext',
					width :50
				}, {
					display : '项目名称',
					name : 'projectName',
					type : 'statictext',
					width : 120
				}, {
					display : '项目编号',
					name : 'projectCode',
					type : 'statictext',
					width : 100
				},{
					display : '外包合同号',
					name : 'outsourceContractCode',
					type : 'statictext',
					width : 80
				},  {
					display : '外包公司',
					name : 'outsourceSupp',
					type : 'statictext',
					width : 80
				}, {
					display : '负责人',
					name : 'principal',
					type : 'statictext',
					width : 70
				},{
					display : '姓名',
					name : 'userName',
					type : 'statictext',
					width :80,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=engineering_worklog_esmworklog&action=getDetailList&projectId=" + row.projectId +"&createId="+row.userId+"&beginDate="+row.beginDate+"&endDate="+row.endDate+"\",\"1\",\"人员日志\")'>" + v + "</a>";
					},
					readonly:true
				}, {
					display : '本期开始',
					name : 'beginDate',
					type : 'statictext',
					width : 70
				},{
					display : '本期结束',
					name : 'endDate',
					type : 'statictext',
					width : 70
				},{
					display : '计价天数',
					name : '',
					type : 'statictext',
					width : 50,
					process : function(v,row){
							return row.feeDay;
					}
				},{
					display : '计价天数',
					name : 'feeDay',
					type : 'hidden',
					width : 50
				}, {
					display : '工价',
					name : 'confirmFee',
					width : 50,
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //判断是否为数字
								if (isNaN(this.value)) {
									this.value = "";
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// 第几行
							var colnum = $(this).data('colNum');// 第几列
							var grid = $(this).data('grid');// 表格组件
							var feeDay=grid.getCmpByRowAndCol(rownum, 'feeDay').val();
							grid.getCmpByRowAndCol(rownum, 'feeTotal').val(feeDay*this.value);
						}
					}
				}, {
					display : '总金额',
					name : 'feeTotal',
					width : 80
				}]
					});

 });

		         //直接提交
function toSubmit(){
	document.getElementById('form1').action="?model=outsourcing_workverify_workVerify&action=edit&addType=submit";
}