function calculate(S, SS, SSS) {
	var state = false;
	var s = $("#" + S).val()
	var ss = $("#" + SS).val()
	if ($("#" + SS).val() > 0) {
		$("#" + SS).val(ss);
		state = true;
	} else if($("#" + SS).val()!='') {
		$("#" + SS).val("");
	}
	if (state) {
		var sss=Subtr(ss, s);
		if(sss>0){
			$("#costing").html("<span style='color: green;'>增长<span>");
			$("#" + SSS).val(sss);
		}else{
			$("#costing").html("<span style='color: gray;'>减少<span>");
			$("#" + SSS).val(Subtr(ss, s));
		}
	} else {
		$("#" + SSS).val("");
	}
}

//计算毛利率(整包)
function grossMargin(){
	var contractBudget1=$("#contractBudget1").val();
	var nbys=$("#nbys").val();
	var jhzbje=$("#jhzbje").val();
	if(contractBudget1>0&&nbys>0){
		var grossMargin=accDiv(contractBudget1-nbys,contractBudget1,4);
		$("#yjmll").val(accMul(grossMargin,100,2) + '%');
	}
	if(contractBudget1>0&&jhzbje>0){
		var grossMargin2=accDiv(contractBudget1-jhzbje,contractBudget1,4);
		$("#yjmll2").val(accMul(grossMargin2,100,2) + '%');
	}
	if($("#yjmll").val()!=''&&$("#yjmll2").val!=''){
		percentageCalculate('yjmll', 'yjmll2', 'mlycy2');
	}
}
//计算分包合同额( 分包)
function contractFb(){
	var contractBudget1=$("#contractBudget2").val();
	var fbbl=$("#fbbl").val().split('%')[0];
	if(contractBudget1>0&&fbbl>0){
		var grossMargin=accDiv(fbbl,100,4);
		$("#fbhte").val(accMul(contractBudget1,grossMargin,2) );
	}
}

//计算毛利率( 分包)
function grossMarginFb(){
	var fbhte=$("#fbhte").val();
	var fblbys=$("#fblbys").val();
	var fbys=$("#fbys").val();
	if(fbhte>0&&fblbys>0){
		var grossMargin=accDiv(fbhte-fblbys,fbhte,4);
		$("#fbyjmly1").val(accMul(grossMargin,100,2) + '%');
	}
	if(fbhte>0&&fbys>0){
		var grossMargin2=accDiv(fbhte-fbys,fbhte,4);
		$("#fbyjmly2").val(accMul(grossMargin2,100,2) + '%');
	}
	if($("#fbyjmly1").val()!=''&&$("#fbyjmly2").val!=''){
		percentageCalculate('fbyjmly1', 'fbyjmly2', 'fbmlycy');
	}
}
function ob(s) {// 不是百分号
	var zhi = $("#" + s).val();
	if ($("#" + s).val() > 0) {
		$("#" + s).val(zhi);
	} else if ($("#" + s).val() !=''){
		$("#" + s).val("");
		alert("请输入正确的值");
	}
}
function Subtr(arg1, arg2) {
	var r1, r2, m, n;
	try {
		r1 = arg1.toString().split(".")[1].length
	} catch (e) {
		r1 = 0
	}
	try {
		r2 = arg2.toString().split(".")[1].length
	} catch (e) {
		r2 = 0
	}
	m = Math.pow(10, Math.max(r1, r2));
	// last modify by deeka
	// 动态控制精度长度
	n = (r1 >= r2) ? r1 : r2;
	return ((arg1 * m - arg2 * m) / m).toFixed(n);
}
function percentageCalculate(S, SS, SSS) {// 百分号
	var state = false;
	var s = $("#" + S).val()
	var ss = $("#" + SS).val()
	if ($("#" + SS).val() > 0||$("#" + SS).val() == 0) {
		$("#" + SS).val(ss + '%');
		state = true;
	} else {
		var zhi = $("#" + SS).val().split('%')[0];
		if (zhi > 0||zhi == 0||zhi< 0) {
			$("#" + SS).val(zhi + '%');
			state = true;
		} else {
			$("#" + SS).val("");
//			alert("请正确输入");
		}
	}
	if (state) {
		$("#" + SSS).val(Subtr1(s, ss));
	} else {
		$("#" + SSS).val("");
	}
}
function Subtr1(arg1, arg2) {// 百分号
	arg1 = arg1.split('%')[0];
	arg2 = arg2.split('%')[0];
	var r1, r2, m, n;
	try {
		r1 = arg1.toString().split(".")[1].length
	} catch (e) {
		r1 = 0
	}
	try {
		r2 = arg2.toString().split(".")[1].length
	} catch (e) {
		r2 = 0
	}
	m = Math.pow(10, Math.max(r1, r2));
	// last modify by deeka
	// 动态控制精度长度
	n = (r1 >= r2) ? r1 : r2;
	return ((arg2 * m - arg1 * m) / m).toFixed(n) + '%';
}
function obr(s) {// 百分号
	var zhi = $("#" + s).val();
	if ($("#" + s).val() > 0) {
		$("#" + s).val(zhi + '%');
	} else {
		var zhi = $("#" + s).val().split('%')[0];
		if (zhi > 0) {
			$("#" + s).val(zhi + '%');
		} else {
			$("#" + s).val("");
			alert("请正确输入");
		}
	}
}

function ob2(s) {// 不是百分号
	var zhi = s.val();
	if (s.val() > 0) {
		s.val(zhi);
	} else {
		s.val("");
		alert("请正确输入");
	}
}
//$(function (){
//	$("#texar").click(function(){
//		$(this).html("Hello <b>world!</b>");
//	});
//});