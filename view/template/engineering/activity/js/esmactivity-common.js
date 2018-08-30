

//Ԥ�ƿ�ʼ������Ԥ�ƽ������ڲ���֤
function timeCheck($t){
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate);
//	if(s < 0) {
//		alert("Ԥ�ƿ�ʼ���ڲ��ܱ�Ԥ�ƽ���������");
//		$t.value = "";
//		return false;
//	}
	var actDays = s + 1;
	$("#days").val(actDays);
	//����������
	if($("#workloadUnit").val() == 'GCGZLDW-00'){
		$("#workload").val(actDays);
	};

	//�ӱ�����������
	if($("#id").length > 0){
		var thisGrid = $("#activityPersons");
	}else{
		var thisGrid = $("#activityMembers");
	}

	if($t.id == 'planBeginDate'){
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "planBeginDate");
		cmps.each(function(i,n) {
			this.value = startDate;
			detailTimeCheck(i);
		});
	}else{
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "planEndDate");
		cmps.each(function(i,n) {
			this.value = endDate;
			detailTimeCheck(i);
		});
	}
}

//ʵ�����ڼ���
function actTimeCheck($t){
	var startDate = $('#actBeginDate').val();
	var endDate = $('#actEndDate').val();
	if(startDate == "" || endDate == ""){
		return false;
	}
	var s = DateDiff(startDate,endDate);
	if(s < 0) {
		alert("ʵ�ʿ�ʼ���ڲ��ܱ�ʵ�ʽ���������");
		$t.value = "";
		return false;
	}
	var actDays = s + 1;
	$("#actDays").val(actDays);
	$("#workedDays").val(actDays);

	var days = $("#days").val()*1;
	var needDays = 0;
	if(days!="" && days != 0){
		needDays = days - actDays;
	}
	$("#needDays").val(needDays);
}

/**
 * �ӱ����
 * @param {} rowNum
 */
function detailTimeCheck(rowNum){
	if($("#id").length > 0){
		//�ӱ�ǰ���ַ���
		var beforeStr = "activityPersons_cmp";
	}else{
		//�ӱ�ǰ���ַ���
		var beforeStr = "activityMembers_cmp";
	}
	//��ȡ��ʼ����
	var planBeginDate = $("#" + beforeStr +  "_planBeginDate" + rowNum).val();
	//��ȡ��������
	var planEndDate = $("#" + beforeStr +  "_planEndDate" + rowNum ).val();

	if(planBeginDate != "" && planEndDate != ""){
		var days = DateDiff(planBeginDate,planEndDate) + 1 ;
		$("#" + beforeStr +  "_days" + rowNum).val(days);
		calPersonBatch(rowNum);
	}
}

//��������Ԥ�� - ����������ʹ��
function calPersonBatch(rowNum){
	if($("#id").length > 0){
		//�ӱ�ǰ���ַ���
		var beforeStr = "activityPersons_cmp";
		var thisGrid = $("#activityPersons");
	}else{
		//�ӱ�ǰ���ַ���
		var beforeStr = "activityMembers_cmp";
		var thisGrid = $("#activityMembers");
	}
	//��ȡ��ǰ����
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_personLevel"  + rowNum ).val() != "" && number != ""){
		//��ȡ����ϵ��
		var coefficient = $("#" + beforeStr +  "_coefficient" + rowNum).val();
		//��ȡ����
		var price = $("#" + beforeStr +  "_price" + rowNum).val();
		//��ȡ����
		var days = $("#" + beforeStr +  "_days" + rowNum ).val();
		//�����˹�����
		var personDays = accMul(number,days,2);
		$("#" + beforeStr +  "_personDays" + rowNum).val(personDays);

		//�����˹�����
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#" + beforeStr +  "_personCostDays" +  rowNum).val(personCostDays);

		//�����˹�����
		var personCost = accMul(price,personDays,2);
		setMoney(beforeStr +  "_personCost" +  rowNum,personCost,2);
	}

	//����Ĭ���������
	thisGrid.yxeditgrid('setConfigValue','planBeginDate',$("#planBeginDate").val());
	thisGrid.yxeditgrid('setConfigValue','planEndDate',$("#planEndDate").val());

	//��ȡ��ǰ����������λ
	var workloadUnit = $("#workloadUnit").val();
	//������죬���������Ԥ���������Ĺ�������
	if(workloadUnit == 'GCGZLDW-00'){
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "personDays");
		var allDays = 0;
		cmps.each(function(i,n) {
			allDays = accAdd(allDays,this.value);
		});

		$("#workload").val(allDays);
	}
}


//��������Ԥ�� - ����������ʹ��
function calPersonBatch2(rowNum){
	//�ӱ�ǰ���ַ���
	var beforeStr = "activityPersons_cmp";
	//��ȡ��ǰ����
	var number= $("#"+ beforeStr + "_number" + rowNum ).val();

	if($("#" + beforeStr + "_personLevel"  + rowNum ).val() != "" && number != ""){
		//��ȡ����ϵ��
		var coefficient = $("#" + beforeStr +  "_coefficient" + rowNum).val();
		//��ȡ����
		var price = $("#" + beforeStr +  "_price" + rowNum).val();
		//��ȡ����
		var days = $("#" + beforeStr +  "_days" + rowNum ).val();
		//�����˹�����
		var personDays = accMul(number,days,2);
		$("#" + beforeStr +  "_personDays" + rowNum).val(personDays);

		//�����˹�����
		var personCostDays = accMul(coefficient,accMul(number,days,2),2);
		$("#" + beforeStr +  "_personCostDays" +  rowNum).val(personCostDays);

		//�����˹�����
		var personCost = accMul(price,personDays,2);
		setMoney(beforeStr +  "_personCost" +  rowNum,personCost,2);
	}

	//�ı�Ĭ��ֵ
	var thisGrid = $("#activityPersons");

	//��ȡ��ǰ����������λ
	var workloadUnit = $("#workloadUnit").val();

	//������죬���������Ԥ���������Ĺ�������
	if(workloadUnit == 'GCGZLDW-00'){
		var cmps = thisGrid.yxeditgrid("getCmpByCol", "personDays");
		var allDays = 0;
		cmps.each(function(i,n) {
			allDays = accAdd(allDays,this.value);
		});

		$("#workload").val(allDays);
	}
}

//�����Ա��ֵ
function setMember(){
	var beforeStr = "activityMembers_cmp";
	var memberIdArr = [];
	var memberNameArr = [];

	$("input[id^='"+ beforeStr +"_memberName']").each(function(i,n){
		if(this.value){
			memberNameArr.push(this.value);
			memberIdArr.push($("#" + beforeStr +"_memberId" + i).val());
		}
	});
	//��Ա
	$("#memberName").val(memberNameArr.toString());
	$("#memberId").val(memberIdArr.toString());
}

//����ˢ��tab
function reloadTab(thisVal){
	var tt = window.parent.$("#tt");
	var tb=tt.tabs('getTab',thisVal);
	tb.panel('options').headerCls = tb.panel('options').thisUrl;
}

//�����б������ʾ
function formatProgress(value,row){
	if($("#isACatWithFallOutsourcing").val() == "1" && row.planProcess != undefined){
		value = row.planProcess;
	}
    if (value){
        var s = '<div style="width:100%;height:auto;border:1px solid #ccc">' +
                '<div style="width:' + value + '%;background:#22DD92;">' + value + '%' + '</div>'
                '</div>';
        return s;
    } else {
        return '';
    }
}

function checkform(){
	if($("#workRate").val()<0){
		alert("����ռ�Ȳ���С��0");
		return false;
	}else{
		return true;
	}
}