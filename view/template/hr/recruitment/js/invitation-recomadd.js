$(document).ready(function() {
		//����
	$("#useAreaName").val($("#useAreaId").find("option:selected").text());
	$("#useAreaId").change(function(){
		$("#useAreaName").val($(this).find("option:selected").text());
		//alert($("#useAreaName").val());
	});
	$( 'textarea.editor' ).ckeditor();
	$("#interviewerName").yxselect_user({
			hiddenId : 'interviewerId',
			formCode : 'interviewerName',
			mode : 'check'
	});
	$("#hrInterviewer").yxselect_user({
			hiddenId : 'hrInterviewerId',
			formCode : 'hrInterviewer',
			mode : 'check'
	});
	/*$("#positionsName").yxcombogrid_position({
		hiddenId : 'positionsId',
		width:300
	});*/
	$("#positionsName").click(function(){
		if($("#deptName").val()==""){
			alert('��ѡ�����˲���!');
		}
	});
	//ӦƸְλ
	function positionsGrid(id){
		$("#positionsName").yxcombogrid_jobs({
					hiddenId : 'positionsId',
					width : 280,
					gridOptions : {
						param:{deptId:id}
					}
		});
	};


	$("#deptName").yxselect_dept({
			hiddenId : 'deptId',
			formCode : 'deptName',
			event:{
				selectReturn : function(e,row){
					$("#positionsName").val("");
					$("#positionsId").val("");
					$("#positionsName").yxcombogrid_jobs("remove");
					positionsGrid(row.dept.id);

				}
			}
	});
	//������Աѡ��
	$("#toccmail").yxselect_user({
		hiddenId : 'toccmailId',
		mode : 'check',
		formCode : 'intUseManager'

	})
    //������Աѡ��
	$("#tobccmail").yxselect_user({
		hiddenId : 'tobccmailId',
		mode : 'check',
		formCode : 'intUseManager'
	})
	validate({
//		"interviewPlace" : {
//			required : true
//		},
//		"interviewDate" : {
//			required : true
//		},
		"deptName" : {
			required : true
		},
		"interviewerName" : {
			required : true
		},
		"positionsName" : {
			required : true
		},
		"workPlace" : {
			required : true
		},
		"developPositionName" : {
			required : true
		},
		"hrInterviewer" : {
			required : true
		}
	})
})
function addmail(name){
	if(name == 'ccmail'){
	  var nameCol = "����";
	}else{
	  var nameCol = "����";
	}
   var temp = document.getElementById(name);
	if (temp.style.display == ''){
	   temp.style.display = "none";
	   $("#to"+name).val("");

	   $("#btn"+name).val("���"+nameCol);
	}else if (temp.style.display == "none"){
		temp.style.display = '';
	    $("#btn"+name).val("ɾ��"+nameCol);
	}
}
/*****************add chenrf 20150527************************/
//ѡ����������ְλʱ�����������ֵ�����
function initLevelWY(){
	var dataArr=[];
	var data=$.ajax({
					url:'?model=hr_basicinfo_level&action=listJson&sort=personLevel&dir=ASC&status=0',
					type:'post',
					dataType:'json',
					async:false
				}).responseText;
	data=eval("("+data+")");
//	data=data.collection;
	var $positionLevel=$("#positionLevel");
	$positionLevel.empty();
	$("<option  value=''>...��ѡ��...</option>").appendTo($positionLevel);
	for(i=0;i<data.length;i++){
		var option=$("<option></option>");
		option.val(data[i].personLevel);
		option.text(data[i].personLevel);
		option.appendTo($positionLevel);
	}
}
$(function(){
	if($("#postType").val()=='YPZW-WY'){
		initLevelWY();
	}
	$("#postType").change(function(){
		if($(this).val()=='YPZW-WY'){
			initLevelWY();
		}else{
			var option='<option value="">...��ѡ��...</option><option value="1">����</option><option value="2">�м�</option><option value="3">�߼�</option>';
			$("#positionLevel").html(option);;
		}
	});
});
