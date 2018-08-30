//初始化一些字段
var objName = 'require';
var initId = 'feeTbl_c';
var actionType = 'view';
var myUrl = '?model=flights_require_require&action=ajaxGet';

$(document).ready(function() {
	if($("#actType").val() == 'audit'){
		$("#buttonTable").hide();
	}

	//如果期望时间无值，隐藏相关信息
	if(strTrim($("#flyStartTimeShow").html()) == '' && strTrim($("#flyEndTimeShow").html()) == ''){
		$("#flyTimeKeyShow").hide();
	}

	//界面显示
    changeType($("#ticketTypeHidden").val());
	
	//随行人员
	var itemTableObj = $("#itemTable");

	itemTableObj.yxeditgrid({
		url : "?model=flights_require_requiresuite&action=listJson",
		param : {
			"mainId" : $("#id").val(),
			"cardNoLimit" : $("#cardNoLimit").val()
		},
		tclass : 'form_in_table',
		type : 'view',
//		event : {
//			'reloadData' : function(e,g,data){
//				if(!data.length){
//					$("#itemTable tbody").append("<tr class='tr_odd'><td colspan='10'>--- 没有随行人员信息 ---</td>");
//				}
//			}
//		},
		colModel : [{
			name : 'employeeTypeName',
			display : '员工类型',
			readonly : true
		},{
			name : 'airName',
			display : '姓名',
			readonly : true
		},{
			name : 'sex',
			display : '乘机人性别',
			readonly : true,
			width : 60
		}, {
			name : 'airPhone',
			display : '移动号码',
			width : 80,
			readonly : true
		}, {
			name : 'cardTypeName',
			display : '证件类型'
		}, {
			name : 'cardNo',
			display : '证件号码'
		}, {
			name : 'validDate',
			display : '证件有效期'
		}, {
			name : 'birthDate',
			display : '出生日期'
		}, {
			name : 'nation',
			display : '国籍',
			width : 80
		}, {
			name : 'tourAgencyName',
			display : '常旅客机构'
		}, {
			name : 'tourCardNo',
			display : '常旅客卡号'
		}]
	});
	//乘机人信息第一行,默认为登录人信息
	$("#itemTable_cmp_airName0").append("W3School");
	$("#itemTable_cmp_airId0").val($("#requireId").val());
	$("#itemTable_cmp_airPhone0").val($("#requirePhone").val());
	$("#itemTable_cmp_cardNo0").val($("#cardNo").val());
});
