/**
 * ��HTML����ǰ����ı���
 */
		var imgCollapsed = "images/collapsed.gif" //�Ӻ�ͼƬ·��

		var imgExpanded = "images/expanded.gif" //����ͼ·��

		var allShrinkImage = "p.allImg IMG"; //����allͼƬjqueryѡ��λ

		var childShrinkImage = "p.childImg IMG"; //��ĳ����ͼƬjqueryѡ��λ

		var shrinkTable = ".shrinkTable"; //����ĳ��table jquery��λ

		var readThisTable = "div.readThisTable"; //����ĳ�в鿴div jquery��λ

		var tdChange = "td.tdChange"; //ĳtd�������� Jquery��λ

		var checkListIdStr = "#idsArry"; //����checkboxѡ��ֵ�ַ��� jquery��λ

		var childCheckbox = "input.checkChild"; //��Сcheckbox jquery��

		var childAllCheckbox = "p.checkChildAll input"; //ĳ������checkbox jquery��λ

		var allCheckbox = "p.checkAll input"; //����checkbox jquery��λ

$(document).ready(function(){

			//���������������div
			$(readThisTable).click(function(){
				$(this).parent().parent().
					find(childShrinkImage).click();
			});

			//�Ӽ�ͼƬ����
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

			//allͼƬ����
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

			//�ı�idList��URL
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

			//checkbox�����ı�������ҳurl
			$(childCheckbox).live('click',function(e,isTrigger){
				//�ַ���������
				var str = $(checkListIdStr).val();
				var strArray = str.split(",");
				var $val = $(this).next("input");
				var isTrue = jQuery.inArray( $val.val() , strArray);
				var isCheckEd = $(this).attr("checked");
				if(isCheckEd ==!isTrigger  && isTrue=="-1" ){
					strArray.push( $val.val() );
					$(checkListIdStr).attr('value',strArray.join(","));

					//TODO:��չ����
					$(this).parent().parent().css({ color: "#ff0011" });
				}else if(isCheckEd == (isTrigger?true:false) && isTrue != "-1" ){
					strArray.splice(isTrue,1)
					$(checkListIdStr).attr('value',strArray.join(","));

					//TODO:��չ����
					$(this).parent().parent().css({ color: "#000000" });

				}
				idsChange();
			});

			//checkbox��ѡ
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

			//all checkboxѡ���ѡ��
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

			//Ĭ�Ͽ�ʼ����
			function beginRead(){
				//����ͨ��URLѡ��checkbox
				var strList = $(checkListIdStr).val()+",";
				$.each( $(childCheckbox),function(val){
					var thisVal = $(this).next().val();
					if( strList.indexOf(","+thisVal+",")!="-1" ){
						$(this).attr("checked",true);
						$(this).parent().parent().css({ color: "#ff0011" });
					}
				});

				//Ĭ�Ϲر�����ѡ�
				$(allShrinkImage).click();
			}
			//��������
			beginRead();

		});