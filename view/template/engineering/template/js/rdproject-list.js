
//���ģ��
function addTemplate(){
	//var projectType = $("#projectType").val();
	var url = "?model=engineering_template_rdprjtemplate&action=toAddTemplate"
			//+"&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=740"
			+"TB_iframe=true&modal=false&height=200&width=740"
	showThickboxWin(url);
}


//�����̱���
function addMilestone(parentId){
	//alert(temp);
	var url = "?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone&parentId="
			+parentId
    	    + "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=740"
	//alert(url);
	showThickboxWin(url);
}

//����ģ��-���͹���
function setTemplateType(){
	var url = "?model=engineering_template_rdprjtemplate&action=toSetTemplate"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=740"
	showThickboxWin(url);
}

//�������ͣ���ת����������ҳ�棬����Ŀ���ͽ��ж��弰�޸�
function setProjectType(){
	this.location = "?model=engineering_projecttype_projecttype&action=showProjectType"
}

//����ģ��
function releaseTemplate(){
	this.location = "?model=engineering_template_rdprjtemplate&action=releaseTemplate";
}

/*
 * ҳ���Զ�ˢ��
 */
function show_page(page){
//	var pageId =
	this.location="?model=engineering_template_rdprjtemplate&action=toviewtemplate";
}


/*
 * ѡ����Ŀ���ʹ����¼�
 */
 function selectType(v){
 	var param = {
 		'projectType' : v
 	};
 	myTree._searchGrid(param);
 }
