/**
 * 渲染前后台部分
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;

var rowStr = [];
var dataStr = {};
var tempStr = {};

//初始化license选单
$(function(){
	$("#objType").val($("#licenseType").val());
	toselect($("#licenseType").val());
});

function initInput(objectArr){
	for(var t in objectArr){
		$("#"+ t).val(objectArr[t]);
		$("#"+ t + "_v").html(objectArr[t]);
	}
}

//行渲染方法
function initRow(rowVal,extVal){
    if(rowVal != ""){
        var rowArr = rowVal.split(',');
        var extArr = eval("(" + extVal + ")");
        for(var i = 0; i < rowArr.length ; i++){
            var key = rowArr[i];
            var str="<td class='clickBtn'><img id='" + i + "' onclick='deleLine("+ key +");' src='images/removeline.png' /></td>"; // 功能列
            $(".tempLine").each(function(){
                var id = 'GMS' + $(this).val() + '-' + key;
                var idV = id + '_v';
                var val = extArr[id] ? extArr[id] : '';
                str += "<td ondblclick=\"disAndfocus('" + id +"')\"><span id='"+ idV + "'>"+val+"</span>"
                    +"<input type=\"text\" class=\"txtmiddle\" id='"+ id +"' onblur=\"changeInput('"+ id +"')\" style=\"display:none\" value='"+val+"'/></td>";
            });
            $("#tableHead").append("<tr class=\"tr_even\" id=row_" + key + " value="+ key +">"+ str +"</tr>");
        }
    }
    initBtnClears();
}

//查看界面清除button
function initBtnClears(){
	$("#licenseDiv img").each(function(){
			$(this).remove();	
	});
	
	$("#licenseDiv td").each(function(){
		if($(this).attr('class') == 'clickBtn'){
			$(this).remove();
		};		
	});
}

//选择 渲染license列表
function toselect(licenseType){
	$("#licenseDiv").html("");
	$("#licenseType").val(licenseType);
	switch(licenseType){
		case 'PIO' : initLicense(licenseType); break;
		case 'NAV' : initLicense(licenseType); break;
		case 'FL' : initLicense(licenseType); break;
		case 'FL2' : initLicense(licenseType); break;
		case 'SL' : initLicense(licenseType); break;
		case 'RCU' : initLicense(licenseType); break;
		case 'WT' : initLicense(licenseType); break;
		case 'WISER' : initLicense(licenseType); break;
		case 'Walktour Pack-Ipad' : initLicense(licenseType); break;
		default : initLicense(licenseType); break;
	}
}

//渲染license 通用
function initLicense(licenseType){
	$.post("?model=yxlicense_license_tempKey&action=returnHtml",{ 'licenseType' : licenseType},function(data){
		$("#licenseDiv").append(data);

		//初始化选择值
		var thisVal = $("#thisVal").val();
		var extVal = $("#extVal").val();
		var rowVal = $("#rowVal").val();
		if(thisVal != "" || extVal != "" ){
			//行渲染
			if(rowVal != ""){
                initRow(rowVal,extVal);
			}
			//选择渲染
			if(thisVal != ""){
				idArr = thisVal.split(",");
				for(var i = 0;i<idArr.length ;i++){
					disInit(idArr[i] );
				}
			}
			//文本输入渲染
			if(extVal != ""){
				dataStr = eval('('+ extVal +')');
				initInput(dataStr)
			}
		}
	});
}

//显示/隐藏对象
function disInit(name){
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

function dis(){};
function disAndfocus() {};
function changeInput(){};
function deleLine(){};
function addNew(){};