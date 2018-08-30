$(function () {
	$.ajax({
		url:"?model=system_region_region&action=listJson",
		data:{isStart:0,isBudgetArea:1},
		success:function(data){
			data=JSON.parse(data);
			var i=0;
			var len = data.length;
			var areaArr = new Array();
			for(var i=0;i<len;i++){
				areaArr[i] = new Array();
				areaArr[i]['area']=data[i]['areaName'];
				areaArr[i]['areaId']=data[i]['id'];
				areaArr[i]['company']=data[i]['businessBelongName'];
			}
			//初始化广州贝讯对象
			var bxObj = {
				area:'广州贝讯',
				areaId:'999',
				company:'广州贝讯'
			}
			areaArr.push(bxObj);
			$("#budgetDetail").yxeditgrid({
				objName: 'budget[budgetDetail]',
		        tableClass: 'form_in_table',
		        data:areaArr,
		        isAddAndDel : false,
		        event : {
					removeRow : function(t, rowNum, rowData) {
						countYearBudget();
					},
					reloadData : function(){
						countYearBudget();
					}
				},
		        colModel: [{
		            display: '区域ID',
		            name: 'areaId',
					type : 'hidden'
		        }, {
		            display: '区域',
		            name: 'area',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: '公司',
		            name: 'company',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: 'Q1',
		            name: 'firstBudget',
		            type: 'money',
		            event : {
						blur : function(e) {
							var rowNum = $(this).data("rowNum");
							countAreaBudget(rowNum);
						}
					}
		        }, {
		            display: 'Q2',
		            name: 'secondBudget',
		            type: 'money',
		            event : {
						blur : function(e) {
							var rowNum = $(this).data("rowNum");
							countAreaBudget(rowNum);
						}
					}
		        }, {
		            display: 'Q3',
		            name: 'thirdBudget',
		            type: 'money',
		            event : {
						blur : function(e) {
							var rowNum = $(this).data("rowNum");
							countAreaBudget(rowNum);
						}
					}
		        }, {
		            display: 'Q4',
		            name: 'fourthBudget',
		            type: 'money',
		            event : {
						blur : function(e) {
							var rowNum = $(this).data("rowNum");
							countAreaBudget(rowNum);
						}
					}
		        }, {
		            display: '区域总预算',
		            name: 'totalBudget',
		            type: 'money',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }]
			})
		}
	})

	//计算单个区域的总预算
	function countAreaBudget(rowNum){
		var areaBudget = 0;
		var itemTableObj = $("#budgetDetail");
		var firstBudget = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "firstBudget").val();
		var secondBudget = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "secondBudget").val();
		var thirdBudget = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "thirdBudget").val();
		var fourthBudget = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "fourthBudget").val();
		var budget = new Array(firstBudget,secondBudget,thirdBudget,fourthBudget);
		areaBudget = accAddMore(budget,2);
		$("#budgetDetail_cmp_totalBudget"+rowNum).val(areaBudget);
		$("#budgetDetail_cmp_totalBudget"+rowNum+"_v").val(areaBudget); //加了type：'money' 需要对这个赋值才能看到效果
		countYearBudget();
	}


	//计算年度总预算
	function countYearBudget(){
		var len = $(".tr_even").length;
		var areaBudget = new Array();
		var i =0;
		for(;i<len;i++){
			areaBudget[i]=$("#budgetDetail_cmp_totalBudget"+i).val();
		}
		var yearBudget = accAddMore(areaBudget,2);
		$("#totalBudget").val(yearBudget);
	}

});