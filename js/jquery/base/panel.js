/**
 * @class panel
 * 面板容器
 * 
 * @extends component
 */

(function($) {
	$.woo.component.subclass('woo.panel', {
		options : {
			/**
			 * 面板标题,支持使用html语句.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		title:'业务信息查询'
			 * });
			 * </code></pre>
			 * 
			 * @type	{String}
			 * 
			 */
			title : null,
			/**
			 * 面板头部图标,设置值参照icon样式定义.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		iconCls:'icon-save'
			 * });
			 * </code></pre>
			 * 
			 * @type		{String}
			 */
			iconCls : null,
			/**
			 * 面板宽度,允许设置数字或特定字符串'auto'.<br/>
			 * 默认值:'auto'<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		width:200
			 * });
			 * </code></pre>
			 * 
			 * @type		{Number}
			 */
			width : 'auto',
			/**
			 * 面板高度,允许设置数字或特定字符串'auto'.<br/>
			 * 默认值:'auto'<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		height:200
			 * });
			 * </code></pre>
			 * 
			 * @type		{Number}
			 */
			height : 'auto',
			/**
			 * 面板左侧偏移量.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		left:100
			 * });
			 * </code></pre>
			 * 
			 * @type		{Number}
			 */
			left : null,
			/**
			 * 面板顶部偏移量.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		top:100
			 * });
			 * </code></pre>
			 * 
			 * @type		{Number}
			 */
			top : null,
			/**
			 * 面板整体样式.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		cls:'divPanelCls'
			 * });
			 * </code></pre>
			 * 
			 * @type		{String}
			 */
			cls : null,
			/**
			 * 面板头部样式.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		headerCls:'divPanelHCls'
			 * });
			 * </code></pre>
			 * 
			 * @type		{String}
			 */
			headerCls : null,
/**
 * 面板内容区背景颜色.<br/>
 * 默认值:'#fff'<br/>
 * 
 * <pre><code>
$('#divPanel').panel({
	bgcolor:'#f00'
});
 * </code></pre>
 * 
 * @type		{String}
 */
			bgcolor : '#fff',
			/**
			 * 面板内容区样式.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		bodyCls:'divPanelBCls'
			 * });
			 * </code></pre>
			 * 
			 * @type		{String}
			 */
			bodyCls : null,
			/**
			 * 面板单独添加的样式.<br/>
			 * 默认值:null<br/>
			 * @type		{String}
			 */
			style : null,
			/**
			 * 面板内容的地址.<br/>
			 * 默认值:null<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		href:'http://www.toone.com.cn'
			 * });
			 * </code></pre>
			 * 
			 * @type	{String}
			 */
			href : null,
			/**
			 * 内容的放置模式.<br/>
			 * 设置值:'iframe':以iframe方式加载href,忽略content属性.<br/>
			 * 设置值:'content':以innerHTML方式加载,content优先,href其次.<br/>
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		href:'http://www.toone.com.cn',
			 * 		content:'面板内容',
			 * 		contentMode:'content'//加载content,href设置无效
			 * 		或者
			 * 		contentMode:'iframe'//加载href,content设置无效
			 * });
			 * </code></pre>
			 * 
			 * @type {String}
			 */
			contentMode : '',
			/**
			 * 加载内容时，显示的信息.<br/>
			 * 默认值:'正在加载...'
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		loadingMessage:'loading...'
			 * });
			 * </code></pre>
			 * 
			 * @type {String}
			 * 
			 */
			loadingMessage : '正在加载...',
			/**
			 * 指定面板内容<br/>
			 * 默认值:null
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		content:'面板内容'
			 * });
			 * </code></pre>
			 * 
			 * @type {String}
			 */
			content : null,
			/**
			 * 是否缓存内容<br/>
			 * 默认值:true
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		cache:true
			 * });
			 * </code></pre>
			 * 
			 * @type {Boolean}
			 * 
			 */
			cache : true,
			/**
			 * 是否自适应父级DOM尺寸<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		fit:true
			 * });
			 * </code></pre>
			 * 
			 * @type {Boolean}
			 * 
			 */
			fit : false,
			/**
			 * 指定面板自适应的对象，确定最大化时的尺寸及位置<br/>
			 * 默认值:null
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		container:'#divPanelMain'//#divPanelMain为对象选择器
			 * });
			 * </code></pre>
			 * 
			 * @type {String}
			 * 
			 */
			container : null,
			/**
			 * 是否初始化边框<br/>
			 * 默认值:true
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		border:true
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			border : true,
			/**
			 * 是否允许改变panel尺寸<br/>
			 * 默认值:true
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		doSize:false
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			doSize : true, // true to set size and do layout
			/**
			 * 是否隐藏标题部分<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		noheader:false
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			noheader : false,
			/**
			 * 是否初始化panel的收缩功能<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		collapsible:true
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			collapsible : false,
			/**
			 * 是否初始化panel的最小化功能<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		minimizable:true
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			minimizable : false,
			/**
			 * 是否初始化panel的最大化功能<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		maximizable:true
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			maximizable : false,
			/**
			 * 是否初始化panel的关闭功能<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		closable:true
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			closable : false,
			/**
			 * 是否初始化后立即收缩组件<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		collapsed:true
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			collapsed : false,
			/**
			 * 是否初始化后立即最小化组件<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		minimized:false
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			minimized : false,
			/**
			 * 是否初始化后立即最大化组件<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		maximized:true
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			maximized : false,
			/**
			 * 是否初始化后立即关闭组件<br/>
			 * 默认值:false
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		closed:false
			 * });
			 * </code></pre>
			 * 
			 * @type		{Boolean}
			 */
			closed : false,

			/**
			 * 自定义工具图标 { iconCls:'',//图标的css类名 // * handler:function(){}//点击触发事件 }<br/>
			 * 默认值:null
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		tools: [{
			 *				iconCls:'icon-add',
			 *				handler:function(panel,e){}
			 *			  },{
			 *				iconCls:'icon-save',
			 *				handler:function(panel,e){alert('save')}
			 *			  }]
			 * });
			 * </code></pre>
			 * 
			 * @type {Object}
			 * 
			 */
			tools : null,
			/**
			 * 内容区域装载时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onLoad:panelLoad
			 * });
			 * 
			 * function panelLoad(){
			 * alert('内容区加载时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onLoad : $.noop,
			/**
			 * 组件打开前触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onBeforeOpen:panelBfOpen
			 * });
			 * 
			 * function panelBfOpen(){
			 * alert('组件打开前触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onBeforeOpen : $.noop,

			/**
			 * 组件打开时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onOpen:panelOpen
			 * });
			 * 
			 * function panelOpen(){
			 * alert('组件打开时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type {Function}
			*/
			onOpen : $.noop,
			/**
			 * 组件关闭前触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onBeforeClose:panelBfClose
			 * });
			 * 
			 * function panelBfClose(){
			 * alert('组件关闭前触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onBeforeClose : $.noop,
			/**
			 * 组件关闭时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onClose:panelClose
			 * });
			 * 
			 * function panelClose(){
			 * alert('组件关闭时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onClose : $.noop,
			/**
			 * 组件销毁前触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onBeforeDestroy:panelBfDes
			 * });
			 * 
			 * function panelBfDes(){
			 * alert('组件销毁前触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onBeforeDestroy : $.noop,
			/**
			 * 组件销毁时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onDestroy:panelDes
			 * });
			 * 
			 * function panelDes(){
			 * alert('组件销毁时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onDestroy : $.noop,
			/**
			 * 当尺寸改变时触发事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onResize:panelResize
			 * });
			 * 
			 * function panelResize(){
			 * alert('当尺寸改变时触发事件');
			 * }
			 * </code></pre>
			 * 
			 * @type {Function}
			 */
			onResize : $.noop,
			/**
			 * 移动时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onMove:panelMove
			 * });
			 * 
			 * function panelMove(){
			 * alert('移动时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type {Function}
			 */
			onMove : $.noop,
			/**
			 * 组件最大化时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onMaximize:panelMax
			 * });
			 * 
			 * function panelMax(){
			 * alert('组件最大化时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onMaximize : $.noop,
			/**
			 * 组件还原时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onRestore:panelRestore
			 * });
			 * 
			 * function panelRestore(){
			 * alert('组件还原时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onRestore : $.noop,
			/**
			 * 组件最小化时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onMinimize:panelMin
			 * });
			 * 
			 * function panelMin(){
			 * alert('组件最小化时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onMinimize : $.noop,
			/**
			 * 组件收缩前触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onBeforeCollapse:panelBfColl
			 * });
			 * 
			 * function panelBfColl(){
			 * alert('组件收缩前触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onBeforeCollapse : $.noop,
			/**
			 * 组件展开前触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onBeforeExpand:panelBfExp
			 * });
			 * 
			 * function panelBfExp(){
			 * alert('组件展开前触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onBeforeExpand : $.noop,
			/**
			 * 组件收缩时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onCollapse:panelColl
			 * });
			 * 
			 * function panelColl(){
			 * alert('组件收缩时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onCollapse : $.noop,
			/**
			 * 组件展开时触发的事件<br/>
			 * 默认值:$.noop
			 * 
			 * <pre><code>
			 * $('#divPanel').panel({
			 * 		onExpand:panelExp
			 * });
			 * 
			 * function panelExp(){
			 * alert('组件展开时触发的事件');
			 * }
			 * </code></pre>
			 * 
			 * @type		{Function}
			 */
			onExpand : $.noop
		},
		
		/**
		 * 组件创建函数
		 * @private
		 * @type {Function}
		 */
		_create : function() {
			this._wrapPanel();
			this._addHeader();
			this._setBorder();
			if (this.options.doSize == true) {
				this.el.css('display', 'block');
				this.resize();
			}
			if (this.options.closed == true) {
				this.el.hide();
			} else {
				this.open();
			}
			// wangkun 2011/1/12
			// 为panel绑定窗体改变的事件，当浏览器大小改变时，panel随之改变
			this._bindWindowResize();
		},
		
		/**
		 * 当浏览器大小改变时，panel随之改变（前提是options中的fit为true）
		 * @private
		 * @type {Function}
		 */
		_bindWindowResize : function() {
			var self = this, opts = this.options, target = this.el;
			if (opts.fit === true) {
				$(window).resize(function() {
							self.resize();
						});
			}
		},
		
		/**
		 * 删除节点
		 * @param {Object} node 节点对象
		 * @private
		 * @type {Function}
		 */
		_removeNode : function(node) {
			node.each(function() {
						$(this).remove();
						if ($.browser.msie) {
							this.outerHTML = '';
						}
					});
		},
		
		/**
		 * 增加头部
		 * @private
		 * @type {Function}
		 */
		_addHeader : function() {
			var opts = this.options, self = this;

			this._removeNode(this.el.find('>div.panel-header'));
			if (opts.title && !opts.noheader) {
				/*
				 * var header = ['<div class="panel-header"><div
				 * class="panel-title', opts.iconCls?' panel-with-icon':'',
				 * '">', opts.title?opts.title:'', '</div>', opts.iconCls?'<div
				 * class="panel-icon '+opts.iconCls+'"></div>':'', '<div
				 * class="panel-tool">', '</div>', '</div>' ];
				 */
				var header = $('<div class="panel-header"><div class="panel-title">'
						+ opts.title + '</div></div>').prependTo(this.el);
				if (opts.iconCls) {
					header.find('>div.panel-title').addClass('panel-with-icon');
					$('<div class="panel-icon"></div>').addClass(opts.iconCls)
							.appendTo(header);
				}

				var tool = $('<div class="panel-tool"></div>').appendTo(header);

				opts.tools = opts.tools ? opts.tools : [];
				//
				if (opts.collapsible) {
					opts.tools.push({
								iconCls : 'panel-tool-collapse',
								handler : function(el, e) {
									if ($(e.target)
											.hasClass('panel-tool-expand')) {
										el.panel('expand', true);
									} else {
										el.panel('collapse', true);
									}
									return false;
								}
							});
				}

				if (opts.minimizable) {
					opts.tools.push({
								iconCls : 'panel-tool-min',
								handler : function(el, e) {
									el.panel('minimize');
									return false;
								}
							});
				}

				if (opts.maximizable) {
					opts.tools.push({
								iconCls : 'panel-tool-max',
								handler : function(el, e) {
									if ($(e.target)
											.hasClass('panel-tool-restore')) {
										el.panel('restore');
									} else {
										el.panel('maximize');
									}
									return false;
								}
							});
				}

				if (opts.closable) {
					opts.tools.push({
								iconCls : 'panel-tool-close',
								handler : function(el, e) {
									el.panel('close');
									return false;
								}
							});
				}
				this.addTools(opts.tools);
				delete opts.tools;
				this.el.find('>div.panel-body')
						.removeClass('panel-body-noheader');
			} else {
				this.el.find('>div.panel-body').addClass('panel-body-noheader');
			}
		},


		/**
		 * 增加工具栏按钮
		 * 
		 * <pre><code>
		 * var tools="[{iconCls:'icon-add',handler:function(panel,e){}},{iconCls:'icon-save',handler:function(panel,e){alert('save')}}]";
		 * $('#divPanel').panel("addTools",tools);
		 * </code></pre>
		 * 
		 * @param {Object} tools 配置项工具栏对象
		 * @type {Function}
		 */
		addTools : function(tools) {
			var self = this;
			var tool = this.el.find('>div.panel-header>div.panel-tool');
			if (tools) {
				for (var i = tools.length - 1; i >= 0; i--) {
					var t = $('<div></div>').addClass(tools[i].iconCls)
							.appendTo(tool);
					t.data('handler', tools[i].handler).bind('click',
							function(e) {
								var handler = $(this).data('handler');
								if ($.isFunction(handler)) {
									handler.call(self, self.el, e);
								}
							}).hover(function() {
								$(this).addClass('panel-tool-over');
							}, function() {
								$(this).removeClass('panel-tool-over');
							});
				}
			}
		},

		/**
		 * 删除工具栏按钮
		 * 
		 * <pre><code>
		 * $('#divPanel').panel("removeTools",['icon-cut','icon-remove','panel-tool-min']);
		 * </code></pre>
		 * 
		 * @param {Object} tools 配置项工具栏对象
		 * @type {Function}
		 */
		removeTools : function(tools) {
			var tool = this.el.find('>div.panel-header>div.panel-tool');
			if (tools) {
				for (var i = tools.length - 1; i >= 0; i--) {
					tool.find('>div.' + tools[i]).removeData('handler')
							.remove();
				}
			}
		},

		/**
		 * 设置边框
		 * 
		 * @private
		 * @type {Function}
		 */
		_setBorder : function() {
			var el = this.el;
			if (this.options.border == true) {
				el.find('>div.panel-header')
						.removeClass('panel-header-noborder');
				el.find('>div.panel-body').removeClass('panel-body-noborder');
			} else {
				el.find('>div.panel-header').addClass('panel-header-noborder');
				el.find('>div.panel-body').addClass('panel-body-noborder');
			}
		},

		/**
		 * 包裹面板
		 * 
		 * @private
		 * @type {Function}
		 */
		_wrapPanel : function() {
			var self = this;
			this.el.addClass('panel')
					.wrapInner('<div class="panel-body"></div>');
			this.el.find(">div.panel-body").css("backgroundColor",
					this.options.bgcolor);
			this.el.bind('_resize', function() {
						if (self.options.fit == true) {
							self.resize();
						}
						return false;
					});
			this.loaded = false;
		},

		/**
		 * 重新设置面板尺寸
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('resize',{width:100,height:200,top:100,left:100});
		 * </code></pre>
		 * @param {Object} param 可选参数 { width:300, height:200, top:10, left:10 }
		 * @type {Function}
		 */
		resize : function(param) {
			var opts = this.options, el = this.el, pheader = el
					.find('>div.panel-header'), pbody = el
					.find('>div.panel-body');
			if (param) {
				if (param.width)
					opts.width = param.width;
				if (param.height)
					opts.height = param.height;
				if (param.left != null)
					opts.left = param.left;
				if (param.top != null)
					opts.top = param.top;
			}
			if (opts.fit == true) {
				// var p = opts.container?$(opts.container):el.parent();
				var p = null;
				if (opts.container) {
					p = $(opts.container);
					opts.width = p.width();
					opts.height = p.height();
				} else {
					if (el.parent()[0] == $('body')[0]) {
						p = $(window);
						opts.width = p.outerWidth();
						opts.height = p.outerHeight();
					} else {
						p = el.parent();
						opts.width = p.width();
						opts.height = p.height();
					}
				}
				// opts.width = p.width();
				// opts.height = p.height();
			}
			el.css({
						// position:'absolute',
						left : opts.left,
						top : opts.top
					});
			if (opts.style)
				el.css(opts.style);
			el.addClass(opts.cls);
			pheader.addClass(opts.headerCls);
			pbody.addClass(opts.bodyCls);

			if (!isNaN(opts.width)) {
				if ($.boxModel == true) {
					el.width(opts.width - (el.outerWidth() - el.width()));
					pheader.width(el.width()
							- (pheader.outerWidth() - pheader.width()));
					pbody.width(el.width()
							- (pbody.outerWidth() - pbody.width()));
				} else {
					el.width(opts.width);
					pheader.width(el.width());
					pbody.width(el.width());
				}
			} else {
				el.width('auto');
				pbody.width('auto');
			}
			if (!isNaN(opts.height)) {
				// var height = opts.height -
				// (panel.outerHeight()-panel.height()) - pheader.outerHeight();
				// if ($.boxModel == true){
				// height -= pbody.outerHeight() - pbody.height();
				// }
				// pbody.height(height);

				if ($.boxModel == true) {
					el.css('height', (opts.height - (el.outerHeight() - el.height())));
					pbody.height(el.height() - pheader.outerHeight()
							- (pbody.outerHeight() - pbody.height()));
				} else {
					el.css('height', (opts.height));
					pbody.css('height', (el.height() - pheader.outerHeight()));
				}
			} else {
				// wangkun 2011/1/12
				// pbody.height('auto');
				if (el.parent()[0] == $('body')[0]) {
					pbody
							.css('height', ($(window).height() - pheader.outerHeight()
									- 2));
				} else {
					pbody.css({
								height : el.parent().height()
							});
				}
			}
//			el.css('height', null);

			opts.onResize.apply(el, [opts.width, opts.height]);

			el.find('>div.panel-body>div').triggerHandler('_resize');

		},

		/**
		 * 加载指定内容
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('load',"<div>这是内容</div>");
		 * </code></pre>
		 * @param {Object} content 内容对象
		 * @type {Function}
		 */
		load : function(content) {
			if (content) {
				this.options.contentMode = 'content';
				this.options.content = content;
			}
			var opts = this.options;
			var el = this.el;
			if (!this.loaded || !opts.cache) {
				var pbody = el.find('>div.panel-body'), tempHtml = "";
				if (pbody.children().length > 0 || $.trim(pbody.html()) != "") {
					tempHtml = pbody.html();
				}

				var s = 'paneltemp=' + new Date().getTime();
				switch (opts.contentMode) {
					case 'iframe' :
						pbody.html($('<div class="panel-loading"></div>')
								.html(opts.loadingMessage));
						pbody
								.html('<iframe width="100%" height="100%" frameborder="0" src="'
										+ $.woo.urlAppend(opts.href, s)
										+ '"></iframe>');
						break;
					case 'content' :
						pbody.html($('<div class="panel-loading"></div>')
								.html(opts.loadingMessage));
						if (opts.content || tempHtml != "") {
							if (opts.content) {
								pbody.html(opts.content);
								if ($.woo.autoParse) {
									$.woo.parse(pbody);
								}
							} else {
								pbody.html(tempHtml);
							}
						} else {
							pbody.load(opts.href, null, function() {
										if ($.woo.autoParse) {
											$.woo.parse(pbody);
										}
										opts.onLoad.apply(this.el, arguments);
									});
						}
						break;
					case 'component' :
						break;
					default :
						if ($.parser) {
							$.parser.parse(pbody);
						}
						break;
				}
				this.loaded = true;
			}
		},

		/**
		 * 返回自己作为对象
		 * 
		 * <pre><code>
		 * var panel = $('#divPanel').panel('panel');
		 * </code></pre>
		 * @type {Function}
		 * @return {Object} el
		 */
		panel : function() {
			return this.el;
		},
		
		/**
		 * 获取面板的头部
		 * 
		 * <pre><code>
		 * var panelHeader = $('#divPanel').panel('getHeader');
		 * </code></pre>
		 * @type {Function}
		 * @return {Object} panel-header
		 */
		getHeader : function() {
			return this.el.find('>div.panel-header');
		},
		/**
		 * 获取面板的主题
		 * 
		 * <pre><code>
		 * var panelBody = $('#divPanel').panel('getBody');
		 * </code></pre>
		 * @type {Function}
		 * @return {Object} panel-body
		 */
		getBody : function() {
			return this.el.find('>div.panel-body');
		},
		/**
		 * 设置面板标题
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('setTitle',"<h4>更改后的标题</h4>");
		 * </code></pre>
		 * 
		 * @param {String} title 标题字符串,支持html
		 * @type {Function}
		 */
		setTitle : function(title) {
			this.getHeader().find('div.panel-title').html(title);
		},
		
		/**
		 * 打开面板
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('open',true);
		 * </code></pre>
		 * 
		 * @param {Boolean} forceOpen 判断是否越过绑定的事件
		 * @type {Function}
		 */
		open : function(forceOpen) {
			var opts = this.options;
			if (forceOpen != true) {
				if (opts.onBeforeOpen.call(this.el) == false)
					return;
			}
			this.el.show();
			opts.closed = false;
			opts.onOpen.call(this.el);

			if (opts.maximized == true)
				this.maximize();
			if (opts.minimized == true)
				this.minimize();
			if (opts.collapsed == true)
				this.collapse();

			if (!opts.collapsed) {
				this.load();
			}
		},
		
		/**
		 * 关闭面板
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('close',true);
		 * </code></pre>
		 * 
		 * @param {Boolean} forceClose 判断是否越过绑定的事件
		 * @type {Function}
		 */
		close : function(forceClose) {
			var opts = this.options;

			if (forceClose != true) {
				if (opts.onBeforeClose.call(this.el) == false)
					return;
			}
			this.el.hide();
			opts.closed = true;
			opts.onClose.call(this.el);
		},
		
		/**
		 * 刷新面板
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('refresh');
		 * </code></pre>
		 * 
		 * @type {Function}
		 */
		refresh : function() {
			var cache = this.options.cache;
			this.options.cache = false;
			this.load();
			this.options.cache = cache;
		},
				
		/**
		 * 移动面板
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('move',{left:200,top:300});
		 * </code></pre>
		 * 
		 * @param {Object} param {left:Number,top:Number}
		 * @type {Function}
		 */
		move : function(param) {
			var opts = this.options;
			if (param) {
				if (param.left != null)
					opts.left = param.left;
				if (param.top != null)
					opts.top = param.top;
			}
			this.el.css({
						left : opts.left,
						top : opts.top
					});
			opts.onMove.apply(this.el, [opts.left, opts.top]);
		},
						
		/**
		 * 最大化面板尺寸
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('maximize');
		 * </code></pre>
		 * 
		 * @type {Function}
		 */
		maximize : function() {
			var opts = this.options;
			var tool = this.el.find('>div.panel-header .panel-tool-max');

			if (tool.hasClass('panel-tool-restore'))
				return;

			tool.addClass('panel-tool-restore');
			this.original = {
				width : opts.width,
				height : opts.height,
				left : opts.left,
				top : opts.top,
				fit : opts.fit
			};
			opts.left = 0;
			opts.top = 0;
			opts.fit = true;
			this.resize();
			opts.minimized = false;
			opts.maximized = true;
			this.el.css("position", "absolute");
			opts.onMaximize.call(this.el);
		},
								
		/**
		 * 最小化面板尺寸
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('minimize');
		 * </code></pre>
		 * 
		 * @type {Function}
		 */
		minimize : function() {
			var opts = this.options;
			var el = this.el;
			this.original = {
				width : opts.width,
				height : opts.height,
				left : opts.left,
				top : opts.top,
				fit : opts.fit
			};
			el.hide();
			opts.minimized = true;
			opts.maximized = false;
			this.el.css("position", "static");
			opts.onMinimize.call(this.el);
		},
										
		/**
		 * 还原面板尺寸
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('restore');
		 * </code></pre>
		 * 
		 * @type {Function}
		 */
		restore : function() {
			var opts = this.options;
			var el = this.el;
			var tool = el.find('>div.panel-header .panel-tool-max');
			// wangkun 2011/1/5
			// 修改原因：当未初始化最大化，最小化按钮时，则无法触发容器大小还原的事件
			// if (!tool.hasClass('panel-tool-restore')&&!opts.minimized)
			// return;

			el.show();
			tool.removeClass('panel-tool-restore');
			var original = this.original;
			opts.width = original.width;
			opts.height = original.height;
			opts.left = original.left;
			opts.top = original.top;
			opts.fit = original.fit;
			this.resize();
			opts.minimized = false;
			opts.maximized = false;
			this.el.css("position", "static");
			opts.onRestore.call(this.el);
		},
		
		/**
		 * 收缩面板
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('collapse',false);
		 * </code></pre>
		 * 
		 * @param {Boolean} animate 是否执行动画效果
		 * @type {Function}
		 */
		collapse : function(animate) {
			var opts = this.options;
			var el = this.el;
			var body = el.find('>div.panel-body');
			var tool = el.find('>div.panel-header .panel-tool-collapse');

			if (tool.hasClass('panel-tool-expand'))
				return;

			body.stop(true, true); // stop animation
			if (opts.onBeforeCollapse.call(this.el) == false)
				return;

			tool.addClass('panel-tool-expand');
			if (animate == true) {
				body.slideUp('normal', function() {
							opts.collapsed = true;
							opts.onCollapse.call(this.el);
						});
			} else {
				body.hide();
				opts.collapsed = true;
				opts.onCollapse.call(this.el);
			}
		},
				
		/**
		 * 展开面板
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('expand',false);
		 * </code></pre>
		 * 
		 * @param {Boolean} animate 是否动画执行
		 * @type {Function}
		 */
		expand : function(animate) {
			var self = this;
			var opts = this.options;
			var el = this.el;
			var body = el.find('>div.panel-body');
			var tool = el.find('>div.panel-header .panel-tool-collapse');

			if (!tool.hasClass('panel-tool-expand'))
				return;

			body.stop(true, true); // stop animation
			if (opts.onBeforeExpand.call(this.el) == false)
				return;

			tool.removeClass('panel-tool-expand');
			if (animate == true) {
				body.slideDown('normal', function() {
							opts.collapsed = false;
							opts.onExpand.call(this.el);
							self.load();
						});
			} else {
				body.show();
				opts.collapsed = false;
				opts.onExpand.call(this.el);
				self.load();
			}
		},
				
		/**
		 * 摧毁面板
		 * 
		 * <pre><code>
		 * $('#divPanel').panel('destroy');
		 * </code></pre>
		 * 
		 * @type {Function}
		 * 
		 */
		destroy : function() {
		}
	});
})(jQuery);
