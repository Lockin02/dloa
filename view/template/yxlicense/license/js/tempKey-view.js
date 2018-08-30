//初始化一个模板缓存数组
var templateStr = "";

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

//初始化license选单
$(function(){
	var licenseVal  = $('#licenseId').val();
	if(licenseVal != ""){
		$.post("?model=yxlicense_license_tempKey&action=viewRecord",
			{ "id" : licenseVal },
			function(data){
				if(data != 0){
					data = eval("(" + data +")");
					$("#objType").val(data.licenseType );
					$("#licenseType").val(data.licenseType );
					if(data.licenseType == "PN"){
						$("#licenseDiv").html("");
						$("#thisVal").val(data.thisVal );
						initPN();
						$("#outputTable").show();
					}else{
						if( data.licenseStr == undefined ){
							if(data.thisVal != "" || data.extVal != "" ){
								if(data.templateId != ""){
									$.ajax({
									    type: "POST",
									    url: "?model=yxlicense_license_template&action=getTemplate",
									    data: { 'id' :  data.templateId },
									    async: false,
									    success: function(innerDate){
									    	if(data != 0){
												innerDate = eval("(" + innerDate +")");
												templateStr = innerDate.thisVal;
									    	}
										}
									});
								}

								$("#licenseDiv").append(data.modalStr );
                                if(data.rowVal != ""){
                                    //行初始化
                                    initRow(data.rowVal,data.extVal);
                                }
								
								//选择渲染
								if(data.thisVal != ""){
									idArr = data.thisVal.split(",");
									for(var i = 0;i<idArr.length ;i++){
										setCheck(idArr[i] );
									}
								}
								//文本输入渲染
								if(data.extVal != ""){
									initInput( eval('('+ data.extVal +')') )
								}
								initBtnClears();
							}
						}else{
							$("#licenseDiv").append(data.licenseStr );
						}
					}
				}else{
					alert('初始化失败');
				}
			}
		)
	}
});

function initInput(objectArr){
	for(var t in objectArr){
		$("#"+ t).val(objectArr[t]);
		$("#"+ t + "_v").html(objectArr[t]);
	}
}

/*****************Navigator + Pioneer
 */
/**
 * 渲染前后台部分
 */
var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;


function initPN() {
	$("#licenseDiv").append("<ul id='baseinfoGrid'/>");
    $("#baseinfoGrid").yxtree({
		checkable : true,
		expandSpeed : "",
		checkedObjId : "id",
		appendData : $("#thisVal").val(),
		url : '?model=yxlicense_license_baseinfo&action=getContent',
        nameCol : "describe",
        event : {
			"node_change" : function(event, treeId, treeNode) {
				//判断当前节点id是否在数组里面
				index = idArr.indexOf(treeNode.id);
				if(index != -1 ){
					//如果有,则删除数组中的该节点id
					idArr.splice(index, 1);
				}else{
					//如果没有,把id放入数组中
					if(treeNode.checked){
						idArr.push(treeNode.id);
					}
				}
				//如果当前节点有子节点,进行递归
				if(treeNode.nodes != undefined ){
					for(i = 0;i< treeNode.nodes.length ; i++){
						changeFn(treeNode.nodes[i]);
					}
				}
				//将数组转换成字符转
				var treeNodeStr = idArr.toString();
				$("#thisVal").val(treeNodeStr);
			}
		}
    });
}

//递归调用树节点函数
function changeFn(object){

	index = idArr.indexOf(object.id);
	if(index != -1){
		idArr.splice(index, 1);
	}else{
		if(object.checked){
			idArr.push(object.id);
		}
	}
	if(object.nodes != undefined ){
		for(var i = 0;i< object.nodes.length ; i++){
			changeFn(object.nodes[i]);
		}
	}
}

//显示/隐藏对象
function setCheck(name){
	//设置类型
	if(templateStr != undefined){
		//判断当前节点id是否在数组里面
		templateIndex = templateStr.indexOf(name);
		if( templateIndex == -1 ){
			$("#"+name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[√]</div>");
		}else{
			$("#"+name).append("<div id=div" + name + ">√</div>");
		}
	}else{
		$("#"+name).append("<div style='color:green;font-weight:bold' id=div" + name + ">[√]</div>");
	}
}

//空函数,避免JS报错
function dis(){}
function disAndfocus() {}
function changeInput(){}
