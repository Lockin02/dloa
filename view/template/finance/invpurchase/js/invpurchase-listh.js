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
				showOpenWin('?model=finance_invpurchase_invpurchase&action=init&id=' + row.title);
			}
		},{
			text: "删除",
			icon: oa_cMenuImgArr['del'],
			alias: "1-3",
			action: function(row) {
				if($('#payStatus_' + row.title).val() != '未申请'){
					alert('已申请付款或已付款，记录不能删除');
					return false;
				}
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
			text: "审核",
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
							alert('审核成功！');
							show_page(1);
						}else{
							alert('审核失败!');
						}
					}
				});
			}
		},
		{
			text: "反审",
			icon: oa_cMenuImgArr['unaudit'],
			alias: "2-2",
			action: function(row) {
				if($('#belongId_' + row.title).val() !="" ){
					alert('拆分发票不能反审');
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
								alert('反审成功！');
								show_page(1);
							}else{
								alert('反审失败!');
							}
						}
					});
				}else{
					alert('被拆分的发票不能反审');
				}
			}
		},
		{
			type: "splitLine"
		},{
			text: "单据拆分",
			icon: oa_cMenuImgArr['break'],
			alias: "1-4",
			action: function(row) {
				if($('#belongId_' + row.title).val() == ""){
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=break&id=' + row.title);
				}else{
					alert('已经是拆分发票,不可再拆分');
					return false;
				}
			}
		},{
			text: "单据合并",
			icon: oa_cMenuImgArr['merge'],
			alias: "1-5",
			action: function(row) {
				var belongId = $('#belongId_' + row.title).val();
				if( belongId != ""){
					if(confirm('确定要合并?')){
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=merge",
							data : {
								"id" : row.title,
								"belongId" : belongId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('合并成功！');
									show_page(1);
								}else{
									alert('合并失败!');
								}
							}
						});
					}
				}else{
					alert('不是拆分单据,不能合并');
					return false;
				}
			}
		},
		{
			type: "splitLine"
		},
		{
			text: "钩稽",
			icon: oa_cMenuImgArr['focus'],
			alias: "1-6",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=toHook&id=' + row.title );
			}
		},
		{
			text: "反钩稽",
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
			text: "暂估冲回",
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
			text: "打印",
			icon: oa_cMenuImgArr['print'],
			alias: "3-1",
			action: function(row) {
				showOpenWin('?model=finance_invpurchase_invpurchase&action=toPrint&id=' + row.title );
			}
		}],
		onShow: applyrule
	};

	$("tbody .tr_even,.tr_odd").contextmenu(option);

	//隐藏右键某些操作
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