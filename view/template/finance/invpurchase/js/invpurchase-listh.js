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
				showOpenWin('?model=finance_invpurchase_invpurchase&action=init&id=' + row.title);
			}
		},{
			text: "ɾ��",
			icon: oa_cMenuImgArr['del'],
			alias: "1-3",
			action: function(row) {
				if($('#payStatus_' + row.title).val() != 'δ����'){
					alert('�����븶����Ѹ����¼����ɾ��');
					return false;
				}
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
			text: "���",
			icon: oa_cMenuImgArr['audit'],
			alias: "2-1",
			action: function(row) {
				$.ajax({
					type : "POST",
					url : "?model=finance_invpurchase_invpurchase&action=audit",
					data : {
						"id" : row.title
					},
					success : function(msg) {
						if (msg == 1) {
							alert('��˳ɹ���');
							show_page(1);
						}else{
							alert('���ʧ��!');
						}
					}
				});
			}
		},
		{
			text: "����",
			icon: oa_cMenuImgArr['unaudit'],
			alias: "2-2",
			action: function(row) {
				if($('#belongId_' + row.title).val() !="" ){
					alert('��ַ�Ʊ���ܷ���');
					return false;
				}
				var markId = 1;
				$.each($(':input[id^="belongId_"]'),function(){
					if($(this).val() == row.title){
						markId = 0;
					}
				});
				if(markId == 1){
					$.ajax({
						type : "POST",
						url : "?model=finance_invpurchase_invpurchase&action=unaudit",
						data : {
							id : row.title
						},
						success : function(msg) {
							if (msg == 1) {
								alert('����ɹ���');
								show_page(1);
							}else{
								alert('����ʧ��!');
							}
						}
					});
				}else{
					alert('����ֵķ�Ʊ���ܷ���');
				}
			}
		},
		{
			type: "splitLine"
		},{
			text: "���ݲ��",
			icon: oa_cMenuImgArr['break'],
			alias: "1-4",
			action: function(row) {
				if($('#belongId_' + row.title).val() == ""){
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=break&id=' + row.title);
				}else{
					alert('�Ѿ��ǲ�ַ�Ʊ,�����ٲ��');
					return false;
				}
			}
		},{
			text: "���ݺϲ�",
			icon: oa_cMenuImgArr['merge'],
			alias: "1-5",
			action: function(row) {
				var belongId = $('#belongId_' + row.title).val();
				if( belongId != ""){
					if(confirm('ȷ��Ҫ�ϲ�?')){
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=merge",
							data : {
								"id" : row.title,
								"belongId" : belongId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�ϲ��ɹ���');
									show_page(1);
								}else{
									alert('�ϲ�ʧ��!');
								}
							}
						});
					}
				}else{
					alert('���ǲ�ֵ���,���ܺϲ�');
					return false;
				}
			}
		},
		{
			type: "splitLine"
		},
		{
			text: "����",
			icon: oa_cMenuImgArr['focus'],
			alias: "1-6",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=toHook&id=' + row.title );
			}
		},
		{
			text: "������",
			icon: oa_cMenuImgArr['unfocus'],
			alias: "1-7",
			action: function(row) {
				showOpenWin('?model=finance_related_baseinfo&action=toUnhook&invPurId=' + row.title );
			}
		},
		{
			type: "splitLine"
		},
		{
			text: "�ݹ����",
			icon: oa_cMenuImgArr['focus'],
			alias: "4-1",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=toRelease&id=' + row.title );
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

	$("tbody .tr_even,.tr_odd").contextmenu(option);

	//�����Ҽ�ĳЩ����
	function applyrule(menu) {
		var thisStatus = $('#status_'+this.title).val();
		switch(thisStatus){
			case 'CGFPZT-WSH' :
				menu.applyrule({
					name : "WSH",
					disable : true,
					items : ['1-4','1-5','1-6','1-7','2-2','4-1']
				});
				break;
			case 'CGFPZT-WGJ' :
				menu.applyrule({
					name : "WGJ",
					disable : true,
					items : ['1-2','1-3','2-1','1-7']
				});
				break;
			case 'CGFPZT-BFGJ' :
				menu.applyrule({
					name : "BFGJ",
					disable : true,
					items : ['1-2','1-3','1-4','1-5','2-1','2-2']
				});
				break;
			case 'CGFPZT-YGJ' :
				menu.applyrule({
					name : "YGJ",
					disable : true,
					items : ['1-2','1-3','1-4','1-5','1-6','2-1','2-2','4-1']
				});
				break;
			case 'CGFPZT-YHS' :
				menu.applyrule({
					name : "YGJ",
					disable : true,
					items : ['1-2','1-3','1-4','1-5','1-6','1-7','2-1','2-2','4-1']
				});
				break;
			case 'CGFPZT-BFHS' :
				menu.applyrule({
					name : "YGJ",
					disable : true,
					items : ['1-2','1-3','1-4','1-5','1-6','1-7','2-1','2-2','4-1']
				});
				break;
			default:
				menu.applyrule({
					name : "YGJ",
					disable : true,
					items : ['1-1','1-2','1-3','1-4','1-5','1-6','1-7','2-1','2-2','3-1','4-1']
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

function search(){
	var searchvalue=$('#searchvalue').val();
	var searchfield=$('#searchfield').val();
	this.location = "?model=finance_invpurchase_invpurchase&action=pageh&"+searchfield+"="+searchvalue;
}



function selectAudit(v){
	if(v!=''){
		this.location="?model=finance_invpurchase_invpurchase&action=pageh&status="+v;
	}else{
		this.location="?model=finance_invpurchase_invpurchase&action=pageh";

	}
}