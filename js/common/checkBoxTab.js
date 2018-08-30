/**
 * 在HTML中提前加入的变量
 */
		var imgCollapsed = "images/collapsed.gif" //加号图片路径

		var imgExpanded = "images/expanded.gif" //减号图路径

		var allShrinkImage = "p.allImg IMG"; //收缩all图片jquery选择定位

		var childShrinkImage = "p.childImg IMG"; //收某行缩图片jquery选择定位

		var shrinkTable = ".shrinkTable"; //收缩某行table jquery定位

		var readThisTable = "div.readThisTable"; //收缩某行查看div jquery定位

		var tdChange = "td.tdChange"; //某td单击收缩 Jquery定位

		var checkListIdStr = "#idsArry"; //隐藏checkbox选择值字符串 jquery定位

		var childCheckbox = "input.checkChild"; //最小checkbox jquery定

		var childAllCheckbox = "p.checkChildAll input"; //某行所有checkbox jquery定位

		var allCheckbox = "p.checkAll input"; //所有checkbox jquery定位

$(document).ready(function(){

			//添加收缩单击弹出div
			$(readThisTable).click(function(){
				$(this).parent().parent().
					find(childShrinkImage).click();
			});

			//加减图片收缩
			$(childShrinkImage).click(function(){
				$parentTr = $(this).parent().parent().parent();
				if( $(this).attr('src')== imgCollapsed ){
					$(this).attr('src',imgExpanded);
					$parentTr.find(readThisTable).hide();
					$parentTr.find(shrinkTable).show();
				}else{
					$(this).attr('src',imgCollapsed);
					$parentTr.find(readThisTable).show();
					$parentTr.find(shrinkTable).hide();
				}
			});

			//all图片收缩
			$(allShrinkImage).click(function(){
				if( $(this).attr('src') == imgCollapsed ){
					$(this).attr('src',imgExpanded);
					$.each( $(childShrinkImage),function(val){
						if( $(this).attr('src') == imgCollapsed ){
							$(this).click();
						}
					});
				}else{
					$(this).attr('src',imgCollapsed);
					$.each( $(childShrinkImage),function(val){
						if( $(this).attr('src') == imgExpanded){
							$(this).click();
						}
					});
				}
			});

			//改变idList的URL
			function idsChange(){
				$tagA = $(".pageShow#pageShow").find("a");
				$tagOption = $(".pageShow#pageShow").find("option");
				var idList = checkListIdStr.slice(1);
				$.each($tagA , function(){
					var begin = $(this).attr("href").indexOf('&'+idList+'=');
					var end = $(this).attr("href").length;
					if(begin=="-1"){
						$(this).attr("href" , $(this).attr("href") + "&"+idList+"=" + $(checkListIdStr).val());
					}else{
						var strMidle = $(this).attr("href").substring(begin+1,end);
						var middle = $(this).attr("href").substring(begin+1,end).indexOf('&');
						if(middle=="-1"){
							$(this).attr("href" , $(this).attr("href").substring(0,begin) + "&"+idList+"=" + $(checkListIdStr).val() );
						}else{
							$(this).attr("href" , $(this).attr("href").substring(0,begin) + "&"+idList+"=" + $(checkListIdStr).val()+ strMidle.substring(middle,end) );
						}
					}
				});

				$.each($tagOption , function(){
					var begin = $(this).attr("value").indexOf('&'+idList+'=');
					var end = $(this).attr("value").length;
					if(begin=="-1"){
						$(this).attr("value" , $(this).attr("value") + "&"+idList+"=" + $(checkListIdStr).val());
					}else{
						var strMidle = $(this).attr("value").substring(begin+1,end);
						var middle = $(this).attr("value").substring(begin+1,end).indexOf('&');
						if(middle=="-1"){
							$(this).attr("value" , $(this).attr("value").substring(0,begin) + "&"+idList+"=" + $(checkListIdStr).val() );
						}else{
							$(this).attr("value" , $(this).attr("value").substring(0,begin) + "&"+idList+"=" + $(checkListIdStr).val()+ strMidle.substring(middle,end) );
						}
					}
				});
			}

			//checkbox单击改变变量与分页url
			$(childCheckbox).live('click',function(e,isTrigger){
				//字符串变数组
				var str = $(checkListIdStr).val();
				var strArray = str.split(",");
				var $val = $(this).next("input");
				var isTrue = jQuery.inArray( $val.val() , strArray);
				var isCheckEd = $(this).attr("checked");
				if(isCheckEd ==!isTrigger  && isTrue=="-1" ){
					strArray.push( $val.val() );
					$(checkListIdStr).attr('value',strArray.join(","));

					//TODO:扩展内容
					$(this).parent().parent().css({ color: "#ff0011" });
				}else if(isCheckEd == (isTrigger?true:false) && isTrue != "-1" ){
					strArray.splice(isTrue,1)
					$(checkListIdStr).attr('value',strArray.join(","));

					//TODO:扩展内容
					$(this).parent().parent().css({ color: "#000000" });

				}
				idsChange();
			});

			//checkbox多选
			$(childAllCheckbox).live('click',function() {
				var $chdChick = $(this).parent().parent().parent().find(childCheckbox);
				if($(this).attr("checked") == true) {
					$.each($chdChick,function(val){
						if( $(this).attr("checked") == false ){
							$(this).trigger('click',[true]);
						}
					});
				}else{
					$.each($chdChick,function(val){
						if( $(this).attr("checked") == true ){
							$(this).trigger('click',[true]);
						}
					});
				}
			});

			//all checkbox选择框选择
			$(allCheckbox).live('click',function() {
				if($(this).attr("checked") == true) {
					$(childAllCheckbox).attr("checked",true);
					$.each( $(childCheckbox),function(val){
						if( $(this).attr("checked") == false ){
							$(this).trigger('click',[true]);
						}
					});
				}else{
					$(childAllCheckbox).attr("checked",false);
					$.each( $(childCheckbox),function(val){
						if( $(this).attr("checked") == true ){
							$(this).trigger('click',[true]);
						}
					});
				}
			});

			//默认开始运行
			function beginRead(){
				//加载通过URL选择checkbox
				var strList = $(checkListIdStr).val()+",";
				$.each( $(childCheckbox),function(val){
					var thisVal = $(this).next().val();
					if( strList.indexOf(","+thisVal+",")!="-1" ){
						$(this).attr("checked",true);
						$(this).parent().parent().css({ color: "#ff0011" });
					}
				});

				//默认关闭所有选项卡
				$(allShrinkImage).click();
			}
			//加载运行
			beginRead();

		});