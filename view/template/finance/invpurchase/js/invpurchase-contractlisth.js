$(function(){
	var option = {
		width: 150,
		items: [{
			text: "查看",
			icon: oa_cMenuImgArr['read'],
			alias: "1-1",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.title);
			}
		},{
			text: "修改",
			icon: oa_cMenuImgArr['edit'],
			alias: "1-2",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=initEditInPurCon&id=' + row.title);
			}
		},{
			text: "删除",
			icon: oa_cMenuImgArr['del'],
			alias: "1-3",
			action: function(row) {
				if(confirm('确定要删除?')){
					$.ajax({
						type : "POST",
						url : "?model=finance_invpurchase_invpurchase&action=ajaxdeletes",
						data : {
							"id" : row.title
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page(1);
							}else{
								alert('删除失败!');
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
			text: "打印",
			icon: oa_cMenuImgArr['print'],
			alias: "3-1",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=toPrint&id=' + row.title );
			}
		}],
		onShow: applyrule
	};

	$(".tr_even,.tr_odd").contextmenu(option);

	//隐藏右键某些操作
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
	 * 初始化时时表格隐藏
	 */
	$.each($("table[id^='table']"),function(){
		$(this).hide();
	})

	/**
	 * 绑定单体伸缩的图片
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
	 * 绑定批量伸缩的图片
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
	 * 绑定DIV
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
