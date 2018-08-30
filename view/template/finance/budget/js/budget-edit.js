$(function () {
$("#year").val($("#year").attr('val'));
	$("#budgetDetail").yxeditgrid({
				objName: 'budget[budgetDetail]',
		        tableClass: 'form_in_table',
		        isAddAndDel : false,
		        url:'?model=finance_budget_budgetDetail&action=listJson&mainId='+$("#id").val()+'&sort=areaId',
		        event : {
					removeRow : function(t, rowNum, rowData) {
						countYearBudget();
					},
					reloadData : function(){
						countYearBudget();
					}
				},
		        colModel: [{
		            display: 'id',
		            name: 'id',
					type : 'hidden'
		        },{
		            display: '����ID',
		            name: 'areaId',
					type : 'hidden'
		        }, {
		            display: '����',
		            name: 'area',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: '��˾',
		            name: 'company',
		            width:'8%',
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
		            display: '������Ԥ��',
		            name: 'totalBudget',
		            type: 'money',
		            width:'8%',
		            tclass : 'readOnlyTxtNormal',
		            readonly : true
		        }, {
		            display: 'ʡ�����Ƿ�ɼ�',
		            name: 'isProvinceVisible',
		            type:'checkbox',
					checkVal:'1',
					event : {
						click : function(e) {
							var rowNum = $(this).data("rowNum");
							var itemTableObj = $("#budgetDetail");
							var isProvinceVisible = itemTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "isProvinceVisible").val();
							if(isProvinceVisible==1){
								$("#budgetDetail_cmp_isProvinceVisible"+rowNum).val(0);
							}else{
								$("#budgetDetail_cmp_isProvinceVisible"+rowNum).val(1);
							}
						}
					}
		        }]
			})

	//���㵥���������Ԥ��
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
		$("#budgetDetail_cmp_totalBudget"+rowNum+"_v").val(areaBudget); //����type��'money' ��Ҫ�������ֵ���ܿ���Ч��
		countYearBudget();
	}


	//���������Ԥ��
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