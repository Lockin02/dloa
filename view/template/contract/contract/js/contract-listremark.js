$(function(){
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});

   getRemarkInfo();
});
//提交
function sub(){
   $.ajax({
		    type : 'POST',
		    url : "?model=contract_contract_contract&action=listremarkAdd",
		    data:{
		        contractId : $("#contractId").val(),
		        content : $("#content").val()
		    },
		    async: false,
		    success : function(data){
			}
		}).responseText;
		getRemarkInfo();
		$("#content").val("");
};

//获取数据
function getRemarkInfo(){
    var infoT = $.ajax({
		    type : 'POST',
		    url : "?model=contract_contract_contract&action=getRemarkInfo",
		    data:{
		        contractId : $("#contractId").val()
		    },
		    async: false,
		    success : function(data){
			}
		}).responseText;
//		info = eval("(" + infoT + ")");

	 $("#info").html(infoT);
};

function subM(){
   var content = strTrim($("#content").val());
   if(content == ''){
   	  alert("请输入内容")
      return false;
   }else{
      return true;
   }
}