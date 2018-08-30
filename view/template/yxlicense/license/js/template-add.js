/**
 * 渲染前后台部分
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;


var dataStr = {};
var tempStr = {};
var rowStr = [];

//选择 渲染license列表
function toselect(licenseType){
	$("#licenseDiv").html("");
	$("#licenseType").val(licenseType);
	$("#licenseId").val($("select option:selected").attr("id"));
	switch(licenseType){
		case 'PN' : initPN(); break;
		case 'PIO' : initLicense(licenseType); break;
		case 'NAV' : initLicense(licenseType); break;
		case 'FL' : initLicense(licenseType); break;
		case 'FL2' : initLicense(licenseType); break;
		case 'SL' : initLicense(licenseType); break;
		case 'RCU' : initLicense(licenseType); break;
		case 'WT' : initLicense(licenseType); break;
		case 'WISER' : initLicense(licenseType); break;
		case 'Walktour Pack-Ipad' : initLicense(licenseType); break;
		case 'Pioneer-Navigator' : initLicense(licenseType); break;
		case licenseType : initLicense(licenseType); break;
		default : break;
	}
}

//渲染license 通用
function initLicense(licenseType){
	idArr = new Array();
	dataStr = {};
	$("#thisVal").val("");
	if(licenseType!=""){
		$.post("?model=yxlicense_license_tempKey&action=returnHtml",{ 'licenseType' : licenseType},function(data){
			$("#licenseDiv").append(data);
		});
	}
}

//显示/隐藏对象
function dis(name){
	var obj = document.getElementById(name);
    var a = obj.getElementsByTagName("div");
    if(a.length>0){
    	//判断当前节点id是否在数组里面
		index = idArr.indexOf(name);
		if(index != -1 ){
			//如果有,则删除数组中的该节点id
			idArr.splice(index, 1);
		}
		//将数组转换成字符转
		var idStr = idArr.toString();
		$("#thisVal").val(idStr);
  	  	$("#div"+name).remove();
    }else{
    	//判断当前节点id是否在数组里面
		index = idArr.indexOf(name);
		if(index == -1 ){
			idArr.push(name);
		}
		//将数组转换成字符转
		var idStr = idArr.toString();
		$("#thisVal").val(idStr);
		$("#"+name).append("<div id=div" + name + ">√</div>");
	}
}

var thisFocus = "";
//显示隐藏某对象 - 用于flee
function disAndfocus(name) {
	if( document.activeElement.id == ""){
		var temp = document.getElementById(name);
		temp.value = $("#" + name + "_v").html();
		if (temp.style.display == '')
			temp.style.display = "none";
		else if (temp.style.display == "none")
			temp.style.display = '';
			temp.focus();
	}
}
//input赋值
function changeInput(focusId){
	tempVal = $("#"+ focusId).val();
	$("#"+ focusId).val(tempVal);
	$("#"+ focusId).hide();
	$("#"+ focusId + "_v").html(tempVal);
	//var tempStr={};
	if(tempVal != ""){
		dataStr[focusId]=tempVal;
	}
	$("#extVal").val($.obj2json(dataStr));
	//dataStr=tempStr;
}

//点击增行方法(编辑)
function addNew(){
    var rowValObj = $("#rowVal"); // 行数据对象
    var rowVal = ''; // 行数 + 1 为现有实际行数
    var rowArr = []; // 行id值缓存
    var colArr = $(".tempLine"); // 列信息
    var rowObj = $("tr[id^='row_']");
    rowObj.each(function(){
        rowVal = $(this).attr('value')*1;
        rowArr.push(rowVal);
    });
    var rowVal = rowVal + 1; // 行数 + 1 为现有实际行数
    rowArr.push(rowVal);
    var str="<td class='clickBtn'><img id='" + rowVal + "' onclick='deleLine("+ rowVal +");' src='images/removeline.png' /></td>"; // 功能列
    colArr.each(function(){
        str += "<td ondblclick=\"disAndfocus('GMS" + $(this).val() + '-' + rowVal +"')\"><span id='GMS"+ $(this).val() + '-' + rowVal + "_v'></span>"
            +"<input type=\"text\" class=\"txtmiddle\" id='GMS"+ $(this).val() + '-' + rowVal +"' onblur=\"changeInput('GMS"+$(this).val() + '-' + rowVal +"')\" style=\"display:none\"/></td>";
    });
    $("#tableHead").append("<tr class=\"tr_even\" id=row_" + rowVal + " value="+ rowVal +">"+ str +"</tr>");
    rowValObj.val(rowArr.toString());
}

//删除当前行
function deleLine(rowVal){
    var rowObj = $("tr[id^='row_']");
    var rowArr = []; // 行id值缓存
    $("#row_" + rowVal).remove();
    rowObj.each(function(){
        if(rowVal*1 != $(this).attr('value')*1) rowArr.push($(this).attr('value')*1);
    });
    $("#rowVal").val(rowArr.toString());
}