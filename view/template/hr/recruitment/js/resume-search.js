$(function(){
	// 应聘职位
	YPZWArr = getData('YPZW');
	addDataToSelect(YPZWArr, 'post');
	    // 简历来源
	JLLYArr = getData('JLLY');
	addDataToSelect(JLLYArr, 'sourceA');
	 // 外语水平
	WYSPArr = getData('WYSP');
	addDataToSelect(WYSPArr, 'languageGrade');
	 // 计算机水品
	JSJSPArr = getData('JSJSP');
	addDataToSelect(JSJSPArr, 'computerGrade');
	//学历
	HRJYXLArr = getData('HRJYXL');
	addDataToSelect(HRJYXLArr, 'education');
})

function searchConfirm(){
	var gridName = $("#gridName").val();
	var listGrid= parent.$("#"+gridName).data('yxgrid');

	var sex=$("#sex").val();//性别
	var marital=$("#marital").val();//婚姻状况
	var education = $("#education").val();//学历
	var post = $("#post").val();//应聘职位
	var computerGrade = $("#computerGrade").val();//计算机水平
	var languageGrade = $("#languageGrade").val();//外语水平
	var sourceA = $("#sourceA").val();//简历来源
	var reserveA = $("#reserveA").val();//简历来源

	var keyword = $("#keyword").val();//关键字字段
	var keywords = $("#keywords").val();//关键字内容

	   listGrid.options.extParam['sex'] = sex;
	   listGrid.options.extParam['marital'] = marital;
	   listGrid.options.extParam['education'] = education;
	   listGrid.options.extParam['post_d'] = post;
	   listGrid.options.extParam['computerGrade_d'] = computerGrade;
	   listGrid.options.extParam['languageGrade_d'] = languageGrade;
	   listGrid.options.extParam['sourceA_d'] = sourceA;
	   listGrid.options.extParam['reserveA'] = reserveA;

	   listGrid.options.extParam[keyword] = keywords;
	   listGrid.reload();

	   self.parent.tb_remove();

}

function refresh(){
    var gridName = $("#gridName").val();
	var listGrid= parent.$("#"+gridName).data('yxgrid');

	   listGrid.options.extParam['sex'] = "";
	   listGrid.options.extParam['marital'] = "";
	   listGrid.options.extParam['education'] = "";
	   listGrid.options.extParam['post'] = "";
	   listGrid.options.extParam['computerGrade'] = "";
	   listGrid.options.extParam['languageGrade'] = "";
	   listGrid.options.extParam['sourceA'] = "";
	   listGrid.options.extParam['reserveA'] = "";
       var keyword = $("#keyword").val();//关键字字段
	   listGrid.options.extParam[keyword] = "";

	listGrid.reload();

	self.parent.tb_remove();

}

