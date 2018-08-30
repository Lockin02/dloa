var invoiceTypeArr = [];

$(function() {
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'invoiceapply'
	});

	//是否需要邮寄
	if($("#isMail").val() == 1){
		$("#isMailYes").attr('checked',true);
		changeIsMail(1);
	}else{
		$("#isMailNo").attr('checked',true);
		changeIsMail(0);
	}
	
	//是否盖章
	if($("#isNeedStamp").val() == 1){
		$("#isNeedStampYes").attr('checked',true);
		changeIsStamp(1);
	}else{
		$("#isNeedStampNo").attr('checked',true);
		changeIsStamp(0);
	}
	
	//开票类型
	invoiceTypeArr = getData('CPFWLX');

	changeInvType("invoiceType");

	thisCount = $("#invnumber").val() ;
	if( thisCount != 0 ){
		for(i = 1;i<= thisCount ; i++ ){
			$("#invoiceEquName"+ i).yxcombogrid_datadict({
				height : 250,
				hiddenId :  'invoiceEquId'+ i,
				gridOptions : {
					isTitle : true,
					param : {"parentCode":"KPXM"},
					showcheckbox : false,
					event : {
						'row_dblclick' : function(i){
							return function(e, row, data) {
								var thisObj = $("#invoiceEquName"+ i);
								$("#invoiceEquId" + i).val(data.dataCode);
								$("#invoiceEquName" + i).val(data.dataName);
								thisObj.attr('readonly',"readonly");
								if(data.dataCode == "QT"){
									thisObj.attr('readonly',"");
									thisObj.val("");
									thisObj.focus();
								}
							};
						}(i)
					},
					// 快速搜索
					searchitems : [{
						display : '名称',
						name : 'dataName'
					}]
				}
			});

		}
	}

	//是否需要邮寄
	var isOffSite = $("#isOffSite").val();
	if(isOffSite == '1'){
		$("#isOffSiteYes").attr('checked',true);
	}else{
		$("#isOffSiteNo").attr('checked',false);
	}
	//是否异地开票初始化
	changeMailInfo(isOffSite);
});

//判断是其他状态,是的话将输入的值附到productId中
function isEmpty(obj,thisKey){
	underObj = $("#invoiceEquId" + thisKey);
	if(obj.value != "" && underObj.val() == "QT"){
		underObj.val(obj.value);
	}
}

/**********************删除动态表单*************************/
function mydel(obj,mytable)
{
	if(confirm('确定要删除该行？')){
		var rowNo = obj.parentNode.parentNode.rowIndex*1 - 1;
		var mytabletemp = document.getElementById(mytable);
   		mytabletemp.deleteRow(rowNo);
   		var myrows=mytabletemp.rows.length;
   		for(i=1;i<myrows;i++){
			mytabletemp.rows[i].childNodes[0].innerHTML=i;
		}
		countFun(this);
	}
}

function audit(){
	document.getElementById('form1').action="?model=finance_invoiceapply_invoiceapply&action=add&act=audit";
}

function auditEdit(){
	document.getElementById('form1').action="?model=finance_invoiceapply_invoiceapply&action=edit&act=audit";
}

/********************************邮件控制段***********************/
function checkEmailTA(obj){
    var addressdiv=document.getElementById("maildiv");
	if(obj.value=="y"){
	 	addressdiv.style.display="";
	}else{
		 addressdiv.style.display="none";
	}
}

//是否开票邮寄
function checkMail(t){
	if(t.checked){
		$("#mailForm").show();
	}else{
		$("#mailForm").hide();
	}
}
/*****************************邮件控制端***************************/