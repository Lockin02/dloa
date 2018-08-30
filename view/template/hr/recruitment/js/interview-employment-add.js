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
	//部门信息
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

	$("#useJobName").yxcombogrid_jobs({
				hiddenId : 'useJobId',
				width : 280
	});
	// 验证信息


	$("#resumeCode").yxcombogrid_resume({
				hiddenId : 'resumeId',
				nameCol:'resumeCode'
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
		},
		"applyCode" : {
			required : true
		},
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

/*************add chenrf 20130527************************************/
//选择网优类型职位时，加载数据字典内容
$(function(){
function initLevelWY(){
	var dataArr=[];
	var data=$.ajax({
					url:'?model=engineering_baseinfo_eperson&action=pageJson&sort=orderNum&dir=ASC&status=0&status=0',
					type:'post',
					dataType:'json',
					async:false,
				}).responseText;
	data=eval("("+data+")");
	data=data.collection;
	var $positionLevel=$("#positionLevel");
	$positionLevel.empty();
	$("<option  value=''>...请选择...</option>").appendTo($positionLevel);
	for(i=0;i<data.length;i++){
		var option=$("<option></option>");
		option.val(data[i].personLevel);
		option.text(data[i].personLevel);
		option.appendTo($positionLevel);
	}
}
if($("#postType").val()=='YPZW-WY'){
	initLevelWY();
}
$("#postType").change(function(){
	if($(this).val()=='YPZW-WY'){
		initLevelWY();
	}else{
		var option='<option value="">...请选择...</option><option value="1">初级</option><option value="2">中级</option><option value="3">高级</option>';
		$("#positionLevel").html(option);;
	}
});
})