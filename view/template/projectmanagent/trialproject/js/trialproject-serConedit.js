var exedeptArr = new Array();

// 直接提交审批
function toApp() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_trialproject_trialproject&action=edit&act=app";
}
// 加载下拉列表
$(function() {
	// 客户
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerType").val(data.TypeOne);
					$("#province").val(data.ProvId);// 所属省份Id
					$("#province").trigger("change");
					$("#provinceName").val(data.Prov);// 所属省份
					$("#city").val(data.CityId);// 城市ID
					$("#cityName").val(data.City);// 城市名称
					$("#customerId").val(data.id);
					$("#areaPrincipal").val(data.AreaLeader);// 区域负责人
					$("#areaPrincipalId").val(data.AreaLeaderId);// 区域负责人Id
					$("#areaName").val(data.AreaName);// 合同所属区域
					$("#areaCode").val(data.AreaId);// 合同所属区域
					$("#address").val(data.Address);// 客户地址
				}
			}
		}
	});

	// 产品清单
	var isView = $("#isView").val();
	if(isView==1){
		var yxType = "view";
	}else{
		var yxType = "edit";
	}
	$("#productInfo").yxeditgrid({
		objName : 'trialproject[product]',
		url:'?model=projectmanagent_trialproject_trialprojectEqu&action=listJson',
        param:{
        	'trialprojectId' : $("#trialprojectId").val()
        },
        type : yxType,
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
            display : '产品线',
            name : 'newProLineName',
            tclass : 'readOnlyTxtNormal',
            readonly : true
        }, {
            display : '产品线编码',
            name : 'newProLineCode',
			type : 'hidden'
        }, {
            display : '执行区域',
            name : 'exeDeptName',
            tclass : 'readOnlyTxtNormal',
            readonly : true
        },
			{
			display : '产品名称',
			name : 'conProductName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '产品Id',
			name : 'conProductId',
			type : 'hidden'
		}, {
			display : '产品描述',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '产品线编号',
			name : 'exeDeptCode',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'moneySix',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			type : 'money'
//		}, {
//			display : '保修期',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
		}, {
			display : '加密配置Id',
			name : 'license',
			type : 'hidden'
		}, {

			name : 'licenseButton',
			display : '加密配置',
			type : 'statictext',
			process : function(v, row) {
				if (row.license != "") {
					return "<a href='javascript:void(0)' onclick='showLicense(\""
							+ row.license + "\")'>加密配置</a>";
				}
			},
			type : 'hidden'
		}, {
			display : '产品配置Id',
			name : 'deploy',
			type : 'hidden'
		}, {
			name : 'deployButton',
			display : '产品配置',
			process : function(v, row) {
				if (row.deploy != "") {
					return "<a href='javascript:void(0)' onclick='showGoods(\""
							+ row.deploy
							+ "\",\""
							+ row.conProductName
							+ "\")'>产品配置</a>";
				}
			}
		}],
		isAddOneRow:false,
		event : {
			'clickAddRow' : function(e, rowNum, g) {
				url = "?model=contract_contract_product&action=toProductIframe";
				var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");
				if (returnValue) {
					dataTip = $.ajax({
						type : "POST",
						url : "?model=goods_goods_goodsbaseinfo&action=getExeDeptCodeById",
						data : {
							"pid" : returnValue.goodsId
						},
						async : false,
						success : function(data) {

						}
					}).responseText;
					if($.inArray(dataTip,exedeptArr) == "-1" && exedeptArr.length != '0'){
                        alert("请选择同一执行区域的产品！");
                        g.removeRow(rowNum);
					}else{
						exedeptArr.push(dataTip);
					    g.setRowColValue(rowNum, "conProductId",returnValue.goodsId, true);
						g.setRowColValue(rowNum, "conProductName",returnValue.goodsName, true);
						g.setRowColValue(rowNum, "exeDeptCode",returnValue.exeDeptCode, true);
						g.setRowColValue(rowNum, "exeDeptName",returnValue.exeDeptName, true);
						g.setRowColValue(rowNum, "number",returnValue.number, true);
						g.setRowColValue(rowNum, "price", returnValue.price, true);
						g.setRowColValue(rowNum, "money", returnValue.money, true);
						g.setRowColValue(rowNum, "warrantyPeriod",returnValue.warrantyPeriod, true);
						g.setRowColValue(rowNum, "deploy", returnValue.cacheId,true);
						g.setRowColValue(rowNum, "license", returnValue.licenseId,true);
						var $tr=g.getRowByRowNum(rowNum);
						$tr.data("rowData",returnValue);
						//选择产品后动态渲染下面的配置单
						getCacheInfo(returnValue.cacheId,rowNum);
					}
				} else {
					g.removeRow(rowNum);
				}

				return false;
			},
			'reloadData' : function(e){
				initCacheInfo();
			},
			'removeRow' : function(e, rowNum, rowData){
				if(typeof(rowData) != 'undefined'){
			    	$("#goodsDetail_" + rowData.deploy).remove();
				}
			}
		}
	});

});

//省市
$(function (){
   var proId = $("#provinceId").val();
   var cityId = $("#cityId").val();
    $("#province").val(proId);//所属省份Id
    $("#province").trigger("change");
	$("#city").val(cityId);//城市ID
	$("#city").trigger("change");

});

//判断试用时间间隔
function timeInterval(){
     //开始时间
	 var beginDate = $("#beginDate").val();
	 //结束时间
	 var closeDate = $("#closeDate").val();
	 if(beginDate!='' && closeDate!=''){
	 	if(closeDate>=beginDate){
		   var days = daysBetween(beginDate,closeDate);
		   if(days > 31){
		       alert("试用项目时间不得超过一个月（31天）！");
		       $("#closeDate").val("");
		    }
	 	}else{
			alert("结束日期不能小于开始日期！");
			$("#closeDate").val("");
	 	}
	 }
}

//判断工期
function timeIntervals(){
	var projectDays = $("#projectDays");
	if(projectDays.val()!=""){
		if(!(/^(\+|-)?\d+$/.test( projectDays.val() ))){
			alert("请输入正整数");
			projectDays.val("");
		}else{
			if(projectDays.val() < 0  || projectDays.val()>31){
				alert("试用项目时间不得超过一个月（31天）！");
				projectDays.val("");
			}
		}
	}
}

//提交验证
function toSub(){
	$("form").bind("submit", function() {
	     //开始时间
		 var beginDate = $("#beginDate").val();
		 //结束时间
		 var closeDate = $("#closeDate").val();
		 //工期
		 var projectDays = $("#projectDays").val();
		if(projectDays == "" && closeDate == "" && beginDate==""){
			alert("必须填写项目周期或者工期!");
			return false;
		}
		var rowNum = $("#productInfo").yxeditgrid('getCurShowRowNum');
        if(rowNum == '0'){
            alert("产品清单不能为空!");
            return false;
        }else{
        	return true;
        }
	});
}

$(function(){
	// 提交验证
	$("#form1").validationEngine({
	inlineValidation: false,
	success :  function(){
		   toSub();
		   $("#form1").submit();//加上验证后再提交表单，解决需要连续点击两次按钮才能提交表单的bug

	},
	failure :false
	})
    /**
	 * 验证信息
	 */
	validate({
		"projectName" : {
			required : true
		},
		"customerId" : {
			required : true
		},
		"projectDescribe" : {
			required : true
		},
		"executive" : {
			required : true
		},
		"budgetMoney" : {
			required : true
		}
	});
})


//回调插入产品信息 － 单条
function getCacheInfo(cacheId,rowNum){
	$.ajax({
	    type: "POST",
	    url: "?model=goods_goods_goodscache&action=getCacheConfig",
	    data: {"id" : cacheId },
	    async: false,
	    success: function(data){
	    	if(data != ""){
				$("#productInfo table tr[rowNum="+ rowNum + "]").after(data);
	    	}

		}
	});
//	$.ajax({
//	    type: "POST",
//	    url: "?model=goods_goods_goodscache&action=getCacheInRow",
//	    data: {"id" : cacheId },
//	    async: false,
//	    success: function(data){
//	    	if(data != ""){
//				$("#productInfo_cmp_showDeploy"+ rowNum + "").html(data);
//	    	}
//		}
//	});
}


//回调插入产品信息 - 单边/带变更
function getCacheInfoChange(cacheId,beforeCacheId,rowNum){
	$.ajax({
	    type: "POST",
	    url: "?model=goods_goods_goodscache&action=getCacheChange",
	    data: {"id" : cacheId , "beforeId" : beforeCacheId },
	    async: false,
	    success: function(data){
	    	if(data != ""){
				$("#productInfo table tr[rowNum="+ rowNum + "]").after(data);
	    	}
		}
	});
}

//加载页面时渲染产品配置信息
function initCacheInfo(){
	//缓存表格对象
	var thisGrid = $("#productInfo");

	var colObj = thisGrid.yxeditgrid("getCmpByCol", "deploy");
	colObj.each(function(i,n) {
		//判断是否有变更前值
		var beforeDeployObj = $("#productInfo_cmp_beforeDeploy" + i);
		if(beforeDeployObj.length == 1){
			if(beforeDeployObj.val()){
				getCacheInfoChange(this.value,beforeDeployObj.val(),i);
			}else{
				getCacheInfo(this.value,i);
			}
		}else{
			getCacheInfo(this.value,i);
		}
	});
}


// 计算方法
function countAll(rowNum) {
	var beforeStr = "productInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
			|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// 获取当前数
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// 获取当前单价
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// 计算本行金额 - 不含税
		thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}


//产品查看方法
function showGoods(thisVal,goodsName){

	url = "?model=goods_goods_properties&action=toChooseView"
		+ "&cacheId=" + thisVal
		+ "&goodsName=" + goodsName
	;

	var sheight = screen.height-300;
	var swidth = screen.width-200;
	var winoption ="dialogHeight:"+sheight+"px;dialogWidth:"+ swidth +"px;status:yes;scroll:yes;resizable:yes;center:yes";

//	showModalDialog(url, '',winoption);
	window.open(url,"", "width=900,height=500,top=200,left=200");

//	showThickboxWin("?model=goods_goods_properties&action=toChooseView"
//		+ "&cacheId=" + thisVal
//		+ "&goodsName=" + goodsName
//		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
}


//license查看方法
function showLicense(thisVal){
	if( thisVal == 0 || thisVal=='' || thisVal=='undefined' ){
		alert('该物料无加密信息！');
		return false;
	}
	url = "?model=yxlicense_license_tempKey&action=toViewRecord"
		+ "&id=" + thisVal
	;

	var sheight = screen.height-200;
	var swidth = screen.width-70;
	var winoption ="dialogHeight:"+sheight+"px;dialogWidth:"+ swidth +"px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '',winoption);
}
