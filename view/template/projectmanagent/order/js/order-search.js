
//// 显示一隐藏 选择时间
//function disDate(name){
//		var temp = document.getElementById(name);
//		if (temp.style.display == ''){
//		     temp.style.display = "none";
//		}else if(temp.style.display == "none"){
//		     temp.style.display = '';
//		}
//}

//本季度
function quarter(){
       var M = new Date()
       var Mon = M.getMonth()+ 1 ;
       var Year = M.getFullYear();
       if( Mon >=1 && Mon <=3){
           var beginDate = Year + "-" + '01' + "-" + '01';
           var endDate = Year + "-" + '03' + "-" + 31;
           $("#beginDate").val(beginDate);
           $("#endDate").val(endDate);
        }else
        if( Mon >=4 && Mon <=6){
           var beginDate = Year + "-" + '04' + "-" + '01';
           var endDate = Year + "-" + '06' + "-" + 30;
           $("#beginDate").val(beginDate);
           $("#endDate").val(endDate);
        }else
       if( Mon >=7 && Mon <=9){
           var beginDate = Year + "-" + '07' + "-" + '01';
           var endDate = Year + "-" + '09' + "-" + 30;
           $("#beginDate").val(beginDate);
           $("#endDate").val(endDate);
        }else
        if( Mon >=10 && Mon <=12){
           var beginDate = Year + "-" + '10' + "-" + '01';
           var endDate = Year + "-" + '12' + "-" + 31;
           $("#beginDate").val(beginDate);
           $("#endDate").val(endDate);
        }

}

//本月
function month(){
     var D = new Date();
     var year = D.getFullYear();
     var month = D.getMonth() +1 ;
     //获取当月最后一天
     D = new Date(year,month,0);
     var listDay = D.getDate();
      if(parseInt(month) < 10) {
  month = "0" + month;
 }

     var beginDate = year + "-" + month + "-" + '01';
     var endDate = year + "-" + month + "-" + listDay;
     $("#beginDate").val(beginDate);
     $("#endDate").val(endDate);
}
//本周
function week()
{
//按周日为一周的最后一天计算
 var date = new Date();
 var this_day = date.getDay(); //今天是这周的第几天
 var step_s = -this_day+1; //上周日距离今天的天数（负数表示）
if (this_day == 0) {
 step_s = -7; // 如果今天是周日
  }
var step_m = 7 - this_day; // 周日距离今天的天数（负数表示）
var thisTime = date.getTime();
var monday = new Date(thisTime +  step_s * 24 * 3600* 1000);
var sunday = new Date(thisTime +  step_m * 24 * 3600* 1000);
//默认统计一周的时间
var starttime = transferDate(monday); //本周一的日期 （起始日期）
var endtime = transferDate(sunday);  //本周日的日期 （结束日期）
     $("#beginDate").val(starttime);
     $("#endDate").val(endtime);

}
function transferDate(date) {
 var yearTemp = date.getFullYear();
 var monthTemp = date.getMonth()+1;
 var dayTemp = date.getDate();
  if(parseInt(monthTemp) < 10) {
  monthTemp = "0" + monthTemp;
 }

 if(parseInt(dayTemp) < 10) {
  dayTemp = "0" + dayTemp;
 }
 return  yearTemp + "-" + monthTemp + "-" + dayTemp;
}


/************************************************************/
function MorQ(){
    var morq = $("#morq").val();
    if (morq == "月份"){
      document.getElementById("mon1").innerHTML = "月开始";
      document.getElementById("mon2").innerHTML = "月截止";
    }else
    if (morq == "季度") {
      document.getElementById("mon1").innerHTML = "季度开始";
      document.getElementById("mon2").innerHTML = "季度截止";
    }
}
//正则表达式判断正整数
function TestRgexp(s){
    var re = /^[0-9]*[1-9][0-9]*$/ ;
    return re.test(s)
}
//填写年份
function beginY(){
	 var D =new Date();
     var beginyear = $("#beginy").val();
     document.getElementById("beginDate").value = beginyear + "-" + '01' + "-" + '01';
     document.getElementById("endy").value = beginyear;
     document.getElementById("endDate").value = beginyear + "-" + '12' + "-" + '31';
  }
function endY(){
	 var D =new Date();
	 var beginyear = $("#beginy").val();
     var endyear = $("#endy").val();
     document.getElementById("endDate").value = endyear + "-" + '12' + "-" + '31';
     if(endyear < beginyear){
        alert ("截止年份不得小于开始年份");
        document.getElementById("endy").value = "";
        document.getElementById("endDate").value = "";
     }
  }

//填写月份/季度
function beginM(){
	 var morq = $("#morq").val();//获取选择的是月份还是季度
     var D = new Date();
     var year = $("#beginy").val();
     if(year == ''){
     	year = D.getFullYear();
     }
     var beginm = $("#beginm").val();
      var h= TestRgexp(beginm);//判断填写数据是否为正整数
     switch (morq){
        case "月份" :
        if (h == false || beginm < 1 || beginm >12){
         alert ("请输入正确月份！")
         document.getElementById("beginm").value = "";
     }else {
         document.getElementById("beginDate").value = year + "-" + beginm + "-" + '01';
         document.getElementById("endm").value = beginm;
         da = new Date(year,beginm,0);
     	 var listDay = da.getDate();
     	 var beginmT=Math.floor(beginm);
     	 if(parseInt(beginmT) < 10) {
  			beginmT = "0" + beginmT;
 		}
 		if(parseInt(listDay) < 10) {
  			listDay = "0" + listDay;
 		}
 		 document.getElementById("beginm").value = beginmT;
 		 document.getElementById("endm").value = beginmT;
 		 document.getElementById("beginDate").value = year + "-" + beginmT + "-" + '01';
         document.getElementById("endDate").value = year + "-" + beginmT + "-" + listDay;

     };
        break;
        case "季度" :
        if(h == false || beginm <1 || beginm > 4){
          alert("请输入正确季度！")
          document.getElementById("beginm").value= "";
        }else{
           document.getElementById("endm").value = beginm;
           switch(beginm){
              case "1" :
              document.getElementById("beginDate").value = year + "-" + '01' + "-" + '01';
              document.getElementById("endDate").value = year + "-" + '03' + "-" + '31';break;
              case "2" :
              document.getElementById("beginDate").value = year + "-" + '04' + "-" + '01';
              document.getElementById("endDate").value = year + "-" + '06' + "-" + '30';break;
              case "3" :
              document.getElementById("beginDate").value = year + "-" + '07' + "-" + '01';
              document.getElementById("endDate").value = year + "-" + '09' + "-" + '30';break;
              case "4" :
              document.getElementById("beginDate").value = year + "-" + '10' + "-" + '01';
              document.getElementById("endDate").value = year + "-" + '12' + "-" + '31';break;
           }

        }
        break;
     }
}

function endM(){
     var morq = $("#morq").val();//获取选择的是月份还是季度
     var D = new Date();
     var year = $("#endy").val();
     if(year == ''){
     	year = D.getFullYear();
     }
     var endm = $("#endm").val();
      var h= TestRgexp(endm);//判断填写数据是否为正整数
     switch (morq){
        case "月份" :
        if (h == false || endm < 1 || endm >12){
         alert ("请输入正确月份！")
         document.getElementById("endm").value = "";
     }else {
     	 D = new Date(year,endm,0);
     	 var listDay = D.getDate();
     	 if(parseInt(endm) < 10) {
  			endm = "0" + endm;
 		}
 		if(parseInt(listDay) < 10) {
  			listDay = "0" + listDay;
 		}
         document.getElementById("endDate").value = year + "-" + endm + "-" + listDay;
     };
        break;
        case "季度" :
        if(h == false || endm <1 || endm > 4){
          alert("请输入正确季度！")
          document.getElementById("endm").value= "";
        }else{
           switch(endm){
              case "1" :
              //获取月分最后一天
              D = new Date(year,3,0);
              var listDay = D.getDate();
              document.getElementById("endDate").value = year + "-" + '03' + "-" + listDay; break;
              case "2" :
              D = new Date(year,6,0);
              var listDay = D.getDate();
              document.getElementById("endDate").value = year + "-" + '06' + "-" + listDay; break;
              case "3" :
              D = new Date(year,9,0);
              var listDay = D.getDate();
              document.getElementById("endDate").value = year + "-" + '09' + "-" + listDay; break;
              case "4" :
              D = new Date(year,12,0);
              var listDay = D.getDate();
              document.getElementById("endDate").value = year + "-" + '12' + "-" + listDay; break;
           }

        }
        break;
     }
}

/*************************************************************************************************************/
function confirm(){
	var gridName = $("#gridName").val();
	var listGrid= parent.$("#"+gridName).data('yxgrid');

	var beginDate=$("#beginDate").val();//开始时间结
	var endDate=$("#endDate").val();//截止时间
	var ExaDT = $("#ExaDT").val();//建立时间
	var areaName = $("#areaName").val();//归属区域
	var orderCodeOrTempSearch = $("#orderCodeOrTempSearch").val();//合同编号
	var prinvipalName = $("#prinvipalName").val();//合同负责人
	var customerName = $("#customerName").val();//客户名称
	var orderProvince = $("#orderProvince").val();//所属省份
	var customerType = $("#customerType").val();//客户类型
	var orderNature = $("#orderNature").val();//合同属性
	var isShip = $("input[name='isShip']:checked").val();//是否有发货记录
//	var orderType = $("#orderType").val();//合同类型
//	var orderState = $("#orderState").val();//合同状态
	if (endDate < beginDate){
        alert ("截止日期不得小于开始日期！");
	}else {
	   listGrid.options.extParam['beginDate'] = beginDate;
	   listGrid.options.extParam['endDate'] = endDate;
	   listGrid.options.extParam['createTime'] = ExaDT;
	   listGrid.options.extParam['areaNameArr'] = areaName;
	   listGrid.options.extParam['prinvipalName'] = prinvipalName;
	   listGrid.options.extParam['orderCodeOrTempSearch'] = orderCodeOrTempSearch;
	   listGrid.options.extParam['customerName'] = customerName;
	   listGrid.options.extParam['orderProvince'] = orderProvince;
	   listGrid.options.extParam['customerType'] = customerType;
	   listGrid.options.extParam['orderNatureArr'] = orderNature;
	   listGrid.options.extParam['DeliveryStatusArr'] = isShip;
//       listGrid.options.param['orderType'] =  orderType;
//       listGrid.options.param['orderState'] = orderState;
	   listGrid.reload();

	   self.parent.tb_remove();
	}



}



function refresh(){
    var gridName = $("#gridName").val();
	var listGrid= parent.$("#"+gridName).data('yxgrid');

	   listGrid.options.extParam['beginDate'] = "";
	   listGrid.options.extParam['endDate'] = "";
	   listGrid.options.extParam['ExaDT'] = "";
	   listGrid.options.extParam['areaName'] = "";
	   listGrid.options.extParam['customerName'] = "";
	   listGrid.options.extParam['prinvipalName'] = "";
	   listGrid.options.extParam['orderCodeOrTempSearch'] = "";
	   listGrid.options.extParam['orderProvince'] = "";
	   listGrid.options.extParam['customerType'] = "";
	   listGrid.options.extParam['orderNatureArr'] = "";
       listGrid.options.extParam['DeliveryStatusArr'] = "";

	listGrid.reload();

	self.parent.tb_remove();



}
//区域下拉
$(function() {
	$("#areaName").yxcombogrid_area({
		hiddenId : 'areaPrincipalId',
		height : 200,
		width : 550,
		gridOptions : {
			showcheckbox : true
		}
	});
});
//组织机构
// 组织机构选择
$(function() {
	$("#prinvipalName").yxselect_user({
		hiddenId : 'prinvipalId'
	});
});
// 选择省份
$(function() {

	$("#orderProvince").yxcombogrid_province({
		hiddenId : 'orderProvinceId',
		height : 200,
		width : 300,
		gridOptions : {
			showcheckbox : false
		}
	});
});

$(function() {
	// 客户类型
	customerTypeArr = getData('KHLX');
	addDataToSelect(customerTypeArr, 'customerType');
});
//选择客户

$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false
		}
	});	});

//xuanze合同属性
$(function() {
	$("#orderNatureName").yxcombogrid_orderNature({
		hiddenId : 'orderNature',
		nameCol : 'dataName',
		height : 250,
		width : 450,
		gridOptions : {
			showcheckbox : true
		}

	});
});