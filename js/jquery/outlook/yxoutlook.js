(function($) {

	$.woo.component.subclass('woo.yxoutlook', {
		options : {
			data : [],
			// �˵�չ�������¼�
			revealtype : "click",
			// �������¼�Ϊ onmouseoverʱ����������ƶ����ӳ�ʱ��
			mouseoverdelay : 50,
			// �Ƿ�ֻ��һ��չ���Ϊtrueʱdefaultexpanded����ֻ������Ψһֵ
			collapseprev : false,
			// Ĭ��չ���Ĳ˵���
			defaultexpanded : [0],
			// ȫ���������Ƿ�Ԥ�����һ��Ϊչ��
			onemustopen : false,
			// Should contents open by default be animated into view?
			animatedefault : false,
			// ���ü�¼��ǰչ����״̬
			persiststate : false,
			// ���ò˵�չ��ʱ�ٶ�
			animatespeed : 'fast'
		},
		// ��ʼ�����
		_create : function() {
			p = this.options;

			// var html = "";
			var html = $("<div></div>");
			for (var i = 0; i < p.data.menu.length; i++) {
				var fmenu = p.data.menu[i];
				var $f = $("<h3 class='menuheader expandable'>"
						+ "<img src='js/jquery/images/outlook/titlebullet.png' "
						+ "width='15' height = '15' style='vertical-align:bottom;'/>&nbsp;"
						+ fmenu.name + "</h3>");
				if (fmenu.url) {
					$f.data("title", fmenu.name);
					$f.data("url", fmenu.url);
					$f.click(function() {
								$(this).parent().find("h3")
										.removeClass("menuheaderclick");
								$(this).parent().find("h3")
										.addClass("menuheader");
								$(this).removeClass("menuheader");
								$(this).addClass("menuheaderclick");
								var title = $(this).data("title");
								var url = $(this).data("url");
								openTab(url, title);
							});
				}
				html.append($f);

				if (p.data.menu[i].item.length) {
					var $ul = $("<ul class='categoryitems'></ul>");
					html.append($ul);
					for (var j = 0; j < p.data.menu[i].item.length; j++) {
						var name = p.data.menu[i].item[j].name;
						var $li = $("<li><a href='javascript:void(0)'>" + name
								+ "</a></li>");
						$li.data("url", p.data.menu[i].item[j].url);
						$li.data("title", name);
						$li.click(function() {
									$(this).parent().parent().find("a").attr(
											"style", "background-color:ffffff");
									$(this).children().attr("style",
											"background-color:#CAD0E4");
									var url = $(this).data("url");
									var title = $(this).data("title");
									openTab(url, title);
								});
						$ul.append($li);
					}
				}

			}
			$(this.el).addClass("arrowlistmenu").append(html);

			ddaccordion.init({
						headerclass : "expandable",
						contentclass : "categoryitems",
						revealtype : p.revealtype,
						mouseoverdelay : p.mouseoverdelay,
						collapseprev : p.collapseprev,
						defaultexpanded : p.defaultexpanded,
						onemustopen : p.onemustopen,
						animatedefault : p.animatedefault,
						persiststate : p.persiststate,
						toggleclass : ["", "openheader"],
						togglehtml : ["prefix", "", ""],
						animatespeed : p.animatespeed,
						oninit : function(headers, expandedindices) {

						},
						onopenclose : function(header, index, state,
								isuseractivated) {
						}
					});

		}
	})
})(jQuery);
