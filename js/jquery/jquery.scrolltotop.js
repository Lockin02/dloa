/**
 * escrolltotop jquery回到顶部插件，平滑返回顶部、
 *
 * 参数设置
 *   startline : 出现返回顶部按钮离顶部的距离
 *   scrollto : 滚动到距离顶部的距离，或者某个id元素的位置
 *   scrollduration : 平滑滚动时间
 *   fadeduration : 淡入淡出时间 eg:[ 500, 100 ] [0]淡入、[1]淡出
 *   controlHTML : html代码
 *   className ：样式名称
 *   titleName : 回到顶部的title属性
 *   offsetx : 回到顶部 right 偏移位置
 *   offsety : 回到顶部 bottom 偏移位置
 *   anchorkeyword : 猫点链接
 * eg:
 *   $.scrolltotop({
 *   	scrollduration: 1000
 *   });
 */
(function($){
	$.scrolltotop = function(options){
		options = jQuery.extend({
			startline : 100,				//出现返回顶部按钮离顶部的距离
			scrollto : 0,					//滚动到距离顶部的距离，或者某个id元素的位置
			scrollduration : 500,			//平滑滚动时间
			fadeduration : [ 500, 100 ],	//淡入淡出时间 ，[0]淡入、[1]淡出
			controlHTML : '<a href="javascript:;"><b>回到顶部↑</b></a>',		//html代码
			className: '',					//样式名称
			titleName: '回到顶部',				//回到顶部的title属性
			offsetx : 80,					//回到顶部 right 偏移位置
			offsety : 15,					//回到顶部 bottom 偏移位置
			anchorkeyword : '#top',  		//锚点链接
			customGrid : false,				//自定义表格
			customCode : false,				//自定义编码
			notOpacity : 0,				    //是否不透明
            customJson : '',                //自定义表格中文
            click : false
		}, options);

		var state = {
			isvisible : false,
			shouldvisible : false
		};

		var current = this;

		var $body,$control,$cssfixedsupport;

		var init = function(){
			var iebrws = document.all;
			$cssfixedsupport = !iebrws || iebrws
					&& document.compatMode == "CSS1Compat"
					&& window.XMLHttpRequest
			$body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
			$control = $('<div class="'+options.className+'" id="topcontrol">' + options.controlHTML + '</div>').css({
				position : $cssfixedsupport ? 'fixed': 'absolute',
				bottom : options.offsety,
				right : options.offsetx,
				opacity : options.notOpacity,
				cursor : 'pointer'
			}).attr({
				title : options.titleName
			}).appendTo('body');

			//判断绑定事件 TODO 先写死
			if(options.customGrid == true){//如果是自定义表格
                $control.click(function() {
                    if(options.customCode == false){
                        alert('自定表格没有配置完成');
                    }else{
                        var colTextArr = [];
                        var colNameArr = [];
                        var colShowArr = [];
                        var colWidthArr = [];
                        var customJson = options.customJson;
                        for(i in customJson){
                            colTextArr.push(customJson[i].colText);
                            colNameArr.push(customJson[i].colName);
                            colShowArr.push(customJson[i].colShow);
                            colWidthArr.push(customJson[i].colWidth);
                        }
                        //这里加入自定义表格的事件
                        showOpenWin("?model=system_gridcustom_gridcustom&action=customList&customCode=" + options.customCode
                            + "&colText="+colTextArr.toString()+ "&colName="+colNameArr.toString()+ "&colShow="+colShowArr.toString()
                            + "&colWidth="+colWidthArr.toString(),
                            1,600,800,'customGrid');
                    }
                    return false;
                });
			}else{
                if(options.click == false){
                    $control.click(function() {
                        scrollup();
                        return false;
                    });
                }else{
                    $control.bind('click',options.click);
                }
			}

			if (document.all && !window.XMLHttpRequest && $control.text() != ''){
				$control.css({
					width : $control.width()
				});
			}
			togglecontrol();
			$('a[href="' + options.anchorkeyword + '"]').click(function() {
				scrollup();
				return false;
			});
			$(window).bind('scroll resize', function(e) {
				togglecontrol();
			})

			return current;
		};

		var scrollup = function() {
			if (!$cssfixedsupport){
				$control.css( {
					opacity : options.notOpacity
				});
			}
			var dest = isNaN(options.scrollto) ? parseInt(options.scrollto): options.scrollto;
			if(typeof dest == "string"){
				dest = jQuery('#' + dest).length >= 1 ? jQuery('#' + dest).offset().top : 0;
			}
			$body.animate( {
				scrollTop : dest
			}, options.scrollduration);
		};

		var keepfixed = function() {
			var $window = jQuery(window);
			var controlx = $window.scrollLeft() + $window.width()
					- $control.width() - options.offsetx;
			var controly = $window.scrollTop() + $window.height()
					- $control.height() - options.offsety;
			$control.css( {
				left : controlx + 'px',
				top : controly + 'px'
			});
		};

		var togglecontrol = function() {
			var scrolltop = jQuery(window).scrollTop();
			if (!$cssfixedsupport){
//				this.keepfixed()
				//bug fixed
				keepfixed();
			}
			state.shouldvisible = (scrolltop >= options.startline) ? true : false;
			if (state.shouldvisible && !state.isvisible) {
				$control.stop().animate( {
					opacity : 1
				}, options.fadeduration[0]);
				state.isvisible = true;
			} else if (state.shouldvisible == false && state.isvisible) {
				$control.stop().animate( {
					opacity : options.notOpacity
				}, options.fadeduration[1]);
				state.isvisible = false;
			}
		};

		return init();
	};
})(jQuery);