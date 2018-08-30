var invoiceTypeArr = [];

$(function() {
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	$("#customerTypeView").val($("#customerType").val());

	//开票类型
	invoiceTypeArr = getData('CPFWLX');

	changeInvType('invoiceType');

	//开票客户
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
        isFocusoutCheck : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#customerType").val(data.TypeOne);
					$("#customerTypeView").val(data.TypeOne);
					$("#customerProvince").val(data.Prov);
					$("#areaName").val(data.AreaName);
					$("#areaId").val(data.AreaId);
				}
			}
		}
	});

	//初始化开票详细
	thisCount = $("#invnumber").val() ;
	if( thisCount != 0 ){
		for(i = 1;i<= thisCount ; i++ ){
			addDataToSelect(invoiceTypeArr, 'psTyle'+i);
			$("#invoiceEquName"+ i).yxcombogrid_datadict({
				hiddenId :  'invoiceEquId'+ i,
				height : 250,
				gridOptions : {
					param : {"parentCode":"KPXM"},
					showcheckbox : false,
					isTitle : true,
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

	//实例化邮寄公司
	initExpressCompany();
});

/**
 * 开票历史
 * @param {} tablelist
 * @param {} countNumP
 */
function toHistory(){
	url = '?model=finance_invoice_invoice&action=invoiceHistory'
			+ '&obj[objId]=' + $("#objId").val()
			+ '&obj[objCode]=' + $("#objCode").val()
			+ '&obj[objType]=' + $("#objType").val()
			+ '&skey=' + $("#skey").val()
	;
	showOpenWin(url,1,500,1000,$("#objCode").val());
}

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

//发票类型对应字段
function changeInvType(thisVal){
	innerInvType = $("#" + thisVal).find("option:selected").attr("e2");
	switch(innerInvType){
		case 'ZZSFP':
			$("#taxpayerIdNeed").html("[*]");
			$("#bankNeed").html("[*]");
			$("#bankCountNeed").html("[*]");
			break;
		default :
			$("#taxpayerIdNeed").html("");
			$("#bankNeed").html("");
			$("#bankCountNeed").html("");
			break;
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