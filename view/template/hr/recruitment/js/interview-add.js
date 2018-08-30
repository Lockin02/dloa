//应聘职位
function positionsGrid(id){
	$("#positionsName").yxcombogrid_jobs({
				hiddenId : 'positionsId',
				width : 280,
				gridOptions : {
					param:{deptId:id}
				}
	});
}

$(document).ready(function() {
	/*//部门信息
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});*/

	/*$("#positionsName").yxcombogrid_jobs({
				hiddenId : 'positionsId',
				width : 280
	});*/
	//部门信息
	$("#deptName").dblclick(function(){
		$("#positionsName").val("");
		$("#positionsId").val("");
		$("#positionsName").yxcombogrid_jobs("remove");
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		event:{
			selectReturn : function(e,row){
				$("#positionsName").val("");
				$("#positionsId").val("");
				$("#positionsName").yxcombogrid_jobs("remove");
				positionsGrid(row.dept.id);
			}
		}
	});
	$("#positionsName").attr("readonly",true);
	$("#positionsName").click(function(){
		if($("#deptId").val()==""){
			alert("请选择用人部门");
			$(this).val("");
		}
	});
	$("#resumeCode").yxcombogrid_resume({
			hiddenId : 'resumeId',
			nameCol:'applicantName',
			isFocusoutCheck:false,
			gridOptions : {
				event:{
					'row_dblclick' : function(e, row, data) {
						$("#phone").val(data.phone);
						$("#email").val(data.email);
						$("#userName").val(data.applicantName);
						$("#resumeCode").val(data.resumeCode);
						$("#sexy").val(data.sex);
					}
				}
			}
	});

	$("#useJobName").yxcombogrid_jobs({
				hiddenId : 'useJobId',
				width : 280
	});
	$("#socialPlace").mouseover(function(){
		$.validationEngine.buildPrompt(this,"尽量购买在珠海或者上海,北京世源信通编制买在北京、广州贝软编制买在广州",null);
	});
	$("#socialPlace").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});
	$("#socialPlace").yxcombogrid_socialplace({
		hiddenId : 'socialPlaceId',
		width : 350
	});
	// 验证信息

	$("#applyCode").yxcombogrid_interview({
			hiddenId : 'applyId',
			width : 500,
			nameCol:'employmentCode',
			isFocusoutCheck:false,
			gridOptions : {
				event:{
					'row_dblclick' : function(e, row, data) {
						$("#applyCode").val(data.employmentCode);
					}
				},
				showcheckbox : false
			}
		});

	validate({
		"userName" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"sexy" : {
			required : true
		},
		"positionsName" : {
			required : true
		},/*
		"applyCode" : {
			required : true
		},*/
		"phone" : {
			required : true
		},
		"email" : {
			required : true
		},
		"positionLevel" : {
			required : true
		}
	});
})
function getRadio(){
	if($("#hrRequire5").length>0){
		//alert($("#computerConfiguration").length);
		$("#hrRequire5").remove();
		return;
	}
	var input = document.createElement("input");
    input.type = "text";
    input.id = "hrRequire5";
    input.name = "interview[computerConfiguration]";
    document.getElementById("setAttr").appendChild(input);
}
function closeRadio(){
	if($("#hrRequire5").length>0){
		//alert($("#computerConfiguration").length);
		$("#hrRequire5").remove();
		return;
	}
}

//选择网优类型职位时，加载数据字典内容
function initLevelWY(){
	var data=$.ajax({
					url:'?model=hr_basicinfo_level&action=listJson&sort=personLevel&dir=ASC&status=0',
					type:'post',
					dataType:'json',
					async:false
				}).responseText;
	data=eval("("+data+")");
//	data=data.collection;
	var positionLevel=$("#positionLevel");
	positionLevel.empty();
	for(i=0;i<data.length;i++){
		var options=$("<option></option>");
		options.text(data[i].personLevel);
		options.val(data[i].id);
		options.appendTo(positionLevel);
	}
}
$(function(){
	$("#postType").change(function(){
		if($(this).val()=='YPZW-WY'){//如果等于网优
			initLevelWY();
		}else{
			var options='<option value="">...请选择...</option> <option value="1">初级</option><option value="2">中级</option><option value="3">高级</option>';
			var positionLevel=$("#positionLevel");
			positionLevel.empty();
			positionLevel.html(options);
		}
	});
})