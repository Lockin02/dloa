$(function(){
	// ӦƸְλ
	YPZWArr = getData('YPZW');
	addDataToSelect(YPZWArr, 'post');
	    // ������Դ
	JLLYArr = getData('JLLY');
	addDataToSelect(JLLYArr, 'sourceA');
	 // ����ˮƽ
	WYSPArr = getData('WYSP');
	addDataToSelect(WYSPArr, 'languageGrade');
	 // �����ˮƷ
	JSJSPArr = getData('JSJSP');
	addDataToSelect(JSJSPArr, 'computerGrade');
	//ѧ��
	HRJYXLArr = getData('HRJYXL');
	addDataToSelect(HRJYXLArr, 'education');
})

function searchConfirm(){
	var gridName = $("#gridName").val();
	var listGrid= parent.$("#"+gridName).data('yxgrid');

	var sex=$("#sex").val();//�Ա�
	var marital=$("#marital").val();//����״��
	var education = $("#education").val();//ѧ��
	var post = $("#post").val();//ӦƸְλ
	var computerGrade = $("#computerGrade").val();//�����ˮƽ
	var languageGrade = $("#languageGrade").val();//����ˮƽ
	var sourceA = $("#sourceA").val();//������Դ
	var reserveA = $("#reserveA").val();//������Դ

	var keyword = $("#keyword").val();//�ؼ����ֶ�
	var keywords = $("#keywords").val();//�ؼ�������

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
       var keyword = $("#keyword").val();//�ؼ����ֶ�
	   listGrid.options.extParam[keyword] = "";

	listGrid.reload();

	self.parent.tb_remove();

}

