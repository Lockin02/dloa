$(document).ready(function() {


	//Tab��ͷ
	topTabShow(arrayTop,"rdMilestone");

	//�����꾭������ɫ�ı�
	rowsColorChange();
/*
	$("#addButton").bind("click",function(){
		var altStr = $(this).attr("alt");
		altStr += "&projectType2=" + $("#projectType").val()+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700";
		showThickboxWin( altStr );
	});
*/
});

/*
 * �Զ�ˢ��ҳ��
 */
 	function show_page(page){
		this.location="?model=engineering_baseinfo_rdmilestoneinfo&action=milestonelist"
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

 /*
  *	�����̱�����
  */
  function addMilestone(){
  	var projectType = $("#projectType").val();
  	if(projectType != "typeoption"){
	  	var url = '?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone&projectType='
	  			+ projectType
	  			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=700'
	//	alert(url);
	  	showThickboxWin(url);
  	}else{
  		alert("����ѡ����Ŀ���͡�����������̱�");
  	}
  }

// 	function addMilestone(){
// 		var url = '?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone'
// 				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=700'
// 		showThickboxWin(url);
// 	}


  //������Excel
  function export2Excel(){
	this.location = "?model=engineering_baseinfo_rdmilestoneinfo&action=export2Excel";
  }


//	$('#projectType').val('{projectType}');//ѡ��ֵ����
	//���ݲ�ͬ�е�ѡ���������ݣ���ת����ͬ���б�ҳ��
  	function selectType(v){
		if(v != 'typeoption'){
			this.location="?model=engineering_baseinfo_rdmilestoneinfo&action=milestonefilterlist&projectType="+v;
		}else{
			this.location="?model=engineering_baseinfo_rdmilestoneinfo&action=milestonelist";
		}
	}


	//�����沢�½�����ť�Ĺ��ܣ������ˢ�µ�ǰҳ�棬ʹ����Լ�������µ����ݡ�
	function saveAndNew(){
		this.show_page();
	}



/*
	function showprojectTypeCode(){
		var projectType=$('#projectType').val();
		showThickboxWin('?model=engineering_baseinfo_rdmilestoneinfo&action=toaddmilestone&projectType='+projectType+'&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700')
	}
*/
