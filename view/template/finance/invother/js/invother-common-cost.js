//配置分摊类型
var shareTypeData=[
   {name:'部门费用 ',value:'部门费用'},
   {name:'合同项目费用',value:'合同项目费用'},
   {name:'研发费用',value:'研发费用'},
   {name:'售前费用',value:'售前费用'},
   {name:'售后费用',value:'售后费用'}
];

/**
 * 产出费用类型
 */
var tempObj=null;
var tempObjId=null;
function showFeeType($obj,$objId){
	tempObj=$obj;
	tempObjId=$objId;
	showThickboxWin('?model=finance_payablescost_payablescost&action=expense'
			+"&feeTypeId="+$objId.val()
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000");
}
/**
 * 触发费用类型，填充值
 */
function fillFeeType(id,$obj){
	tempObj.val($obj);
	tempObjId.val(id);
}

//重新设置分摊对象
function changeShareType(shareType,$obj,$objCode,$objId){
	//数据清除
	$obj.val("");
	$objCode.val("");
	$objId.val("");
	//取消渲染
	$obj.yxcombogrid_projectall('remove');
	$obj.yxselect_dept('remove');
	$obj.yxcombogrid_rdprojectfordl('remove');
	$obj.yxcombogrid_rdprojectfordl('remove');
	changeTypeClear(shareType,$obj,$objCode,$objId);
}

//初始化分摊对象方法
/**
 * shareType 编码
 * $obj   分摊对象
 * $objCode 分摊对象code
 * $objId  分摊对象Id
 */
function initShare(shareType,$obj,$objCode,$objId){
	changeTypeClear(shareType,$obj,$objCode,$objId);
}

//不清空选项设置 TODO
function changeTypeClear(thisVal,$obj,$objCode,$objId){
	$obj.show();
	$obj.siblings().remove();
	$obj.attr('readonly','readonly');
	switch(thisVal){
		case '部门费用' :
			initDept($obj,$objId);break;
		case '合同项目费用' :
			//费用部门
			initEsmProject($obj,$objId,$objCode);break;
		case '研发费用' :
			//解放费用部门
			initRdProject($obj,$objId,$objCode);break;
		case '售前费用' :
			//销售部分下拉渲染
			initSale($obj,$objId,$objCode);break;
		case '售后费用' :
			//渲染合同
			initContract($obj,$objId,$objCode);break;
		default : break;
	}
}

//初始化人员
function initPerson($obj,$objCode){
	$obj.yxselect_user({
		event:{
			select : function(e, row) {
				if(typeof(row) != 'undefined'){
					$objCode.val(row.val);
				}

			}
		}
	});
}

//初始化部门
function initDept($obj,$objId){
	$obj.yxselect_dept({
		event:{
			selectReturn : function(e,row){
				$objId.val(row.dept.id);
			}
		}
	});
}
//初始化工程项目
function initEsmProject($obj,$objId,$objCode){
	//工程项目渲染
	$obj.yxcombogrid_projectall({
		isDown : true,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'contractType' : 'GCXMYD-01'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$objId.val(data.id);
					$objCode.val(data.projectCode);
				}
			}
		}
	});
}

//研发项目渲染
function initRdProject($obj,$objId,$objCode){
	//研发项目渲染
	$obj.yxcombogrid_rdprojectfordl({
		isDown : true,
		isShowButton : false,
		height : 250,
		isFocusoutCheck : false,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : { 'is_delete' : 0 , 'project_typeNo' : '4'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$objId.val(data.id);
					$objCode.val(data.projectCode)
				}
			}
		}
	});
}
//售前费用
function initSale($obj,$objId,$objCode){
	var data=$.ajax({
				url:'?model=finance_expense_expense&action=getSaleDept&detailType=4',
				type : 'get',
				async: false
			}).responseText;
	var dataArr=eval("("+data+")");
	var val=$obj.val();
	var name=$obj.attr('name');  //当前name值
	var select=$("<select style='width:200px;' name='"+name+"' class='"+$obj.attr('id')+" select'></select>");
	var optionStr = "<option value=''></option>";
	for(i=0;i<dataArr.length;i++){
		if(val == dataArr[i].text){
			optionStr += "<option selected='selected' value='"+ dataArr[i].text +"'>" + dataArr[i].text +"</option>";
		}else{
			optionStr += "<option value='"+ dataArr[i].text +"'>" + dataArr[i].text +"</option>";
		}
	}
	select.append(optionStr);
	$obj.after(select);
	$obj.val(0);
	$obj.hide();

}
//售后费用
function initContract($obj,$objId,$objCode){
	//渲染一个匹配按钮
	var title = "输入完整的鼎利合同号，系统自动匹配相关信息";
	var $button = $("<span class='search-trigger'  title='"+ title +"'>&nbsp;</span>");
	$button.click(function(){
		var check=getContractInfo($obj.val(),$objId,$objCode);
		if(!check)
			$obj.val('');
	})
	$obj.blur(function(){
		var check=getContractInfo($obj.val(),$objId,$objCode);
		if(!check)
			$obj.val('');
	});
	//添加清空按钮
	var $button2 = $("<span class='clear-trigger' title='点击清空数据'>&nbsp;</span>");
	$button2.click(function(){
		$obj.val('');
	})
	$obj.attr('readonly','').after($button2).after($button).width($obj.width() - $button2.width()*2);
}

//异步匹配合同信息
function getContractInfo(contractCode,$objId,$objCode){
	if(contractCode == ""){
		return false;
	}
	var data=$.ajax({
	    type: "POST",
	    url: "?model=contract_contract_contract&action=ajaxGetContract",
	    data: {"contractCode" : contractCode },
	    async: false
	}).responseText;
	if(data){
		var dataArr = eval("(" + data + ")");
		if(dataArr.thisLength*1 > 1){
			alert('错误，系统中存在【' + dataArr.thisLength + '】条名称为【' + contractName + '】的合同，请通过合同编号匹配合同信息！');
			return false;
		}else if(dataArr.thisLength*1 == 1){
			$objId.val(dataArr.id);
			$objCode.val(dataArr.contractCode);
			alert('该合同号可用！');
			return true;
		}
	    }else{
	    	alert('错误，没有查询到相关合同信息');
	    	return false;
	    }
}

//计算分摊明细总金额
function countDetailMoney(g,rowNum){
	var shareMoney=0;
	var shareMoneyDel=0;
	var shareMoneyArr = g.getCmpByCol('shareMoney');
	for(i=0;i<shareMoneyArr.length;i++){
		shareMoney+=($(shareMoneyArr[i]).val().replaceAll(',',''))*1;
	}

	return shareMoney;
}
//判断新增一行分摊金额的可分摊金额数
function applyMoney(g,rowNum){
	var applyMoney=$("#applyMoney").val();
	if(applyMoney==''||typeof(applyMoney)=='undefined'||isNaN(applyMoney)){
		applyMoney=0;
	}
	var money=Number(applyMoney);//分摊总金额
	return money-countDetailMoney(g,rowNum);
}
//分摊明细总计
function changeMoney(money){
	$("#payDetailMoney").val(Convert(money));  //分摊明细总计
	$("#payDetailMoneyHidden").val(money);  //分摊明细总计
}

//金额转换
function Convert(money) {
	if(money=='')money=0;
    var s = money; //获取小数型数据
    s += "";
    if (s.indexOf(".") == -1) s += ".0"; //如果没有小数点，在后面补个小数点和0
    if (/\.\d$/.test(s)) s += "0";   //正则判断
    while (/\d{4}(\.|,)/.test(s))  //符合条件则进行替换
        s = s.replace(/(\d)(\d{3}(\.|,))/, "$1,$2"); //每隔3位添加一个
    return s;
}

//导入excel弹出窗体
function payImportExcel(){
	showThickboxWin('?model=contract_other_other&action=toPayImportExcel'
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=450");
}

//初始化合计栏
function initInvcostCount(){
	$("#invcostTable tbody").after("<tr class='tr_count'><td colspan='3'>合计</td>"
		+ "<td colspan='2'></td>"
		+ "<td>"
		+"<input type='text' class='readOnlyTxtMiddleCount' style='width:200px;' id='payDetailMoney' readonly='readonly'/>"
		+"</td>"
		+ "</tr>");
}

//初始化相关按钮
function initButton(){
	//导入按钮
	$title=$("#invcostTable tr:first td");
	$title.html($title.html()+"<input type='button' value='引入付款分摊内容' class='txt_btn_a' style='margin-left:10px;' onclick='importPayCost();'/>");
}

//分摊合计计算
function countCost(){
	var invcostObj = $("#invcostTable");
	var trObj = invcostObj.yxeditgrid("getCmpByCol", "shareMoney");
	var countCost = 0;
	trObj.each(function(i,n){
		countCost = accAdd(countCost,this.value,2);
	});
	$("#payDetailMoney").val(moneyFormat2(countCost));
}