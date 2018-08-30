$(function(){
	var option = {
		width: 150,
		items: [{
			text: "�鿴",
			icon: oa_cMenuImgArr['read'],
			alias: "1-1",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.title);
			}
		},{
			text: "�޸�",
			icon: oa_cMenuImgArr['edit'],
			alias: "1-2",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=initEditInPurCon&id=' + row.title);
			}
		},{
			text: "ɾ��",
			icon: oa_cMenuImgArr['del'],
			alias: "1-3",
			action: function(row) {
				if(confirm('ȷ��Ҫɾ��?')){
					$.ajax({
						type : "POST",
						url : "?model=finance_invpurchase_invpurchase&action=ajaxdeletes",
						data : {
							"id" : row.title
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page(1);
							}else{
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		},
		{
			type: "splitLine"
		},
		{
			text: "��ӡ",
			icon: oa_cMenuImgArr['print'],
			alias: "3-1",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=toPrint&id=' + row.title );
			}
		}],
		onShow: applyrule
	};

	$(".tr_even,.tr_odd").contextmenu(option);

	//�����Ҽ�ĳЩ����
	function applyrule(menu) {
		var thisStatus = $('#status_'+this.title).val();
		switch(thisStatus){
			case 'CGFPZT-WSH' :
				menu.applyrule({
					name : "WSH",
					disable : true,
					items : []
				});
				break;
			case 'CGFPZT-WGJ' :
				menu.applyrule({
					name : "WGJ",
					disable : true,
					items : ['1-2','1-3']
				});
				break;
			case 'CGFPZT-BFGJ' :
				menu.applyrule({
					name : "BFGJ",
					disable : true,
					items : ['1-2','1-3']
				});
				break;
			case 'CGFPZT-YGJ' :
				menu.applyrule({
					name : "YGJ",
					disable : true,
					items : ['1-2','1-3']
				});
				break;
			case 'CGFPZT-YHS' :
				menu.applyrule({
					name : "YGJ",
					disable : true,
					items : ['1-2','1-3']
				});
				break;
			default:
				menu.applyrule({
					name : "YGJ",
					disable : true,
					items : ['1-1','1-2','1-3','3-1']
				});
				break;
		}
	}


	/**
	 * ��ʼ��ʱʱ�������
	 */
	$.each($("table[id^='table']"),function(){
		$(this).hide();
	})

	/**
	 * �󶨵���������ͼƬ
	 */
	var thistitle;
	$("img[id^='changeTab']").bind("click",function(){
		var thistitle = $(this).attr("title");
		if($(this).attr("src") == "images/collapsed.gif"){
			$("#table" + thistitle).show();
			$("#inputDiv" + thistitle).hide();
			$(this).attr("src","images/expanded.gif");
		}else{
			$("#table" + thistitle).hide();
			$("#inputDiv" + thistitle).show();
			$(this).attr("src","images/collapsed.gif");
		}
	})

	/**
	 * ������������ͼƬ
	 */
	var thissrc ;
	$("#changeImage").bind("click",function(){
		thissrc = $(this).attr("src");
		if($(this).attr("src")=="images/collapsed.gif"){
			$(this).attr("src","images/expanded.gif");
		}else{
			$(this).attr("src","images/collapsed.gif");
		}
		$.each($("img[id^='changeTab']"),function(i,n){
			if($(this).attr("src")==thissrc)
				$(this).trigger("click");
		})
	})

	/**
	 * ��DIV
	 */
	var imgId ;
	$("div[id^='inputDiv']").bind("click",function(){
		imgId = $(this).attr("title");
		$("#changeTab" + imgId).trigger("click");
		$(this).hide();
	})
});

function show_page(v){
	self.location.reload();
}

function selectAudit(v){
	if(v!=''){
		this.location="?model=finance_invpurchase_invpurchase&action=showContractList&status="+v;
	}else{
		this.location="?model=finance_invpurchase_invpurchase&action=showContractList";

	}
}
