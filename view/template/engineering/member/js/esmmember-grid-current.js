//初始化
$(function() {
	init(0);
});
//改变统计周期
function changeRange(){
	init(0);
}
//获取当前
function returnCurrent(){
	init(1);
}
//获取所有
function returnAll(){
	init(2);
}
function init(condition){
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var projectId = $("#projectId").val();
	
	var s = DateDiff(beginDate,endDate);
	if(s < 0) { 
		alert("查询起始日期不能比查询结束日期晚！");
		return false;
	}
//人员资源现状表
	var responseText = $.ajax({
	    type: "POST",
	    url: "?model=engineering_member_esmmember&action=ajaxManageCurrent",
	    data: {"beginDate" : beginDate , 'endDate' : endDate , "projectId" : projectId , "condition" :　condition},
	    async: false
	}).responseText;
	var colData = eval("(" + responseText + ")");
	var obj = [];
    $.each(colData[0] ,function(i){
        obj.push({'display':i,'name':i});
    });
    var colName = obj;
	$("#esmmemberGrid").yxeditgrid('remove').yxeditgrid({
		data : colData,
		type : 'view',
		colModel : colName
	});
	//人员资源现状图表 
	var chartData = $.ajax({
	    type: "POST",
	    url: "?model=engineering_member_esmmember&action=getChart",
	    data: {"beginDate" : beginDate , 'endDate' : endDate , "projectId" : projectId , "condition" :　condition},
	    async: false
	}).responseText;
	$("#chart").html("").append(chartData);
	
	//成员列表
	$("#esmmemberListGrid").yxeditgrid('remove').yxeditgrid({
		url: "?model=engineering_member_esmmember&action=memberListJson",
		title : '成员列表',
		param : {
			'projectId' : projectId,
			'beginDate' : beginDate,
			'endDate' : endDate,
			'condition' : condition
			
		},
		type : 'view',
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        },{
            display : '项目编号',
            name : 'projectCode',
            type : 'hidden'
        },{
        	display : '员工编号',
            name : 'userNo'
        },{
			display : '工作日志创建人Id',
			name : 'createId',
			type : 'hidden'
		},{
			display : '员工Id',
			name : 'memberId',
			type : 'hidden'
		},{
        	display : '姓名',
            name : 'memberName',
            process : function(v,row){
				if(row.createId){
					return "<a href='javascript:void(0)' title='查看工作日志列表' onclick='searchDetail(\""+ row.createId+"\",\""+ row.memberName+"\");'>" + v +"</a>";
				}else{
					return v;
				}
			}
        },{
        	display : '归属',
            name : 'belongDeptName'
        },{
        	display : '级别',
            name : 'personLevel'
        },{
        	display : '分组',
            name : 'roleName'
        },{
        	display : '加入项目',
            name : 'beginDate'
        },{
        	display : '离开项目',
            name : 'endDate'
        },{
        	display : '人工投入',
            name : 'inWorkRate'
        },{
        	display : '日志缺报',
            name : 'logMissNum',
            process : function(v,row){
				if(row){
					return '<span class="red">' + v + '</span>';
				}
			}
        },{
        	display : '工作系数',
            name : 'workCoefficient'
        },{
        	display : '进展系数',
            name : 'processCoefficient'
        },{
        	display : '项目完成量',
            name : 'thisProjectProcess',
            process : function(v){
				if(v){
					return v + " %";
				}
			}
        },{
        	display : '借款',
            name : 'loan',
            align: 'right',
			process : function(v,row) {
				if(row.memberId){
					return "<a href='javascript:void(0)' title='查看借款记录表' onclick='payView(\""+ row.memberId+"\",\""+ row.projectCode+"\");'>" + moneyFormat2(v) +"</a>";
				}else{
					return moneyFormat2(v);
				}
			}
        },{
        	display : '消费',
            name : 'feeFieldCount',
            align: 'right',
			process : function(v,row) {
				if(row.memberId){
					return "<a href='javascript:void(0)' title='查看报销记录表' onclick='expense(\""+ row.memberId+"\",\""+ row.projectCode+"\");'>" + moneyFormat2(v) +"</a>";
				}else{
					return moneyFormat2(v);
				}
			}
        }]
        });
    //导出Excel按钮
	$("#esmmemberListGrid td.form_header").append('<input type="button" value="导出Excel" onclick="exportExcel('+condition+');"/>');
}

//查看工作日志列表
function searchDetail(createId,createName){
	var url = "?model=engineering_worklog_esmworklog&action=toSearchDetailList&createId="
		+ createId
		+ "&createName=" + createName
		+ "&beginDate=" + $("#beginDate").val()
		+ "&endDate=" + $("#endDate").val()
		+ "&projectId=" + $("#projectId").val()
	;
	showOpenWin(url, 1 ,600 , 1100 ,createName );
}

//导出Excel
function exportExcel(condition){
	var url = "?model=engineering_member_esmmember&action=memberListExport"
		+ "&beginDate=" + $("#beginDate").val()
		+ "&endDate=" + $("#endDate").val()
		+ "&projectId=" + $("#projectId").val()
		+ "&condition=" + condition
		;
	showOpenWin(url, 1 ,200 , 200);
}

//查看借款记录表
function payView(memberId,projectCode){
	var url = "?model=hr_payView_payView&action=toEsmmemberList&userAccount="
		+ memberId+"&projectCode="+projectCode
	;
	showOpenWin(url, 1 ,600 , 1200);
}

//查看报销记录表
function expense(memberId,projectCode){
	var url = "?model=finance_expense_expense&action=listForEsmmember&userAccount="
		+ memberId+"&projectCode="+projectCode
	;
	showOpenWin(url, 1 ,600 , 1200);
}