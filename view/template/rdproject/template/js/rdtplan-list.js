var arrayTop = [{
		"name" : "���ȼƻ�ģ��",
		"url"  : "?model=rdproject_template_rdtplan&&action=showPlanTemplates",
		"choose" : "planTemplate"
	}
	/*
	,{
		"name" : "��Ŀģ��",
		"url" : "?model=rdproject_template_rdprjtemplate&action=toTemplateList",
		"choose" : "rdTemplate"
	}
	*/
];


function addFun(){
	var url = '?model=rdproject_template_rdtplan&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750';
	showThickboxWin(url);
}