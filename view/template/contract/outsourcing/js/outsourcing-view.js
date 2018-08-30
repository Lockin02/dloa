var pageAttr = 'view';//配置页面操作，用于渲染整包/人员租赁信息
$(function() {
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
	}
	//outsourType();
	//判断是否存在关闭原因，不存在则隐藏
	if($("#closeReason").val() == ''){
		$("#closeReason").parents("tr:first").hide();
	}
});

function itemDetail(){
	$("#itemTable").yxeditgrid( {
		objName : 'outsourcing[items]',
		url : '?model=contract_personrental_personrental&action=listJson',
		type : 'view',
		param : {
			mainId : $("#pid").val()
		},
		colModel : [{
			name : 'personLevel',
			display : '人员级别',
			type : "hidden"
		}, {
			name : 'personLevelName',
			display : '人员级别名称'
		}, {
			name : 'pesonName',
			display : '姓名'
		}, {
			name : 'beginDate',
			display : '租赁开始日期'
		}, {
			name : 'endDate',
			display : '租赁结束日期'
		}, {
			name : 'selfPrice',
			display : '服务线人力成本',
			process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            }
		}, {
			name : 'rentalPrice',
			display : '外包价格',
			process : function(v){
            	return '<span style="padding:0px 10px 0px 0px;">'+moneyFormat2(v)+'</span>';
            }
		}, {
			name : 'skillsRequired',
			display : '工作技能要求'
		}, {
			name : 'interviewResults',
			display : '技术面试结果'
		}, {
			name : 'interviewName',
			display : '面试人员'
		}, {
			name : 'interviewId',
			display : '面试人员id',
			type : "hidden"
		}, {
			name : 'remark',
			display : '备注'
		}]
	});
	tableHead();
}
