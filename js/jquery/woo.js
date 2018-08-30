(function($){
	if(!$) return;

	var idIndex = 0;
	$.woo = $.woo || {version:"@VERSION"};

/**
 * @class global
 * WooUI的全局配置对象。这个设定的意义在于可以通过全局对象来约定WooUI的整体使用方式，包括UI组件渲染机制、全局事件监听、组件交互方法、界面风格、操作习惯等。另外还存放一些全局的数据。
 * <p>使用方法：$.woo.global.配置属性名称，例如判断是否自动解析DOM结构，</p>
 * <p><pre><code>
if($.woo.global.autoParse){
  //当允许自动解析DOM结构
};</code></pre></p>
 * 这个WooUI的全局配置对象，可以在其他类中添加配置项，方法如下：
 * <p><pre><code>
$.extend($.woo.global,{
  '配置名':'配置值'
});
 * </code></pre></p>
 * @singleton
 */
	$.woo.global = {
		/**
		 * 自动解析html,详见{@link woo#parse}
		 * @type Boolean
		 */
		autoParse : true,

		/**
		 * WooUI的组件，每个组件初始化时将自身的名称压入这个数组
		 * @type Array
		 */
		components : []

	};

/**
 * @class woo
 * WooUI的工具类，这个类中实现了很多实用的工具，包括数据类型判断、编解码处理、url处理、对象遍历、数据格式化等工具。
 * <p>使用方法：$.woo.工具方法名，例如下面是一个生成UUID的方法{@link #createUUID}：</p>
 * <p><pre><code>
$.woo.createUUID();//创建并返回一个 'version 4' RFC-4122 UUID 字符串
</code></pre></p>
	 * @singleton
	 */


	$.extend($.woo,{
		/**
		 * 在指定上下文中解析html结构，如果发现某个DOM节点有css样式类 'wooui-'+组件名 ，且没有属性autoParse='false'，
		 * 则用组件名对应的组件去渲染
		 *
		 */
		parse : function(context){
			if ($.woo.global.autoParse){
				for(var i=0; i<$.woo.components.length; i++){
					(function(){
						var comp = $.woo.components[i];
						var dom = $('.wooui-' + comp, context);
						if (dom.length && !(dom.attr('autoParse') != 'false')){
							if (dom[comp]){
								dom[comp]();
							}
						}
					})();
				}
			}
		},

		/**
		 * 该方法有两个用途，一方面，如果传入的DOM元素没有id，则赋予一个id。如果没有传入DOM元素，则返回一个新生成的id。
		 * 无论有没有传入DOM元素，都会返回一个页面中唯一的id值。
		 * @param {Mixed} el DOM元素
		 * @param {String} prefix id值的前缀
		 * @return String 不重复的id值
		 */
		uid : function(el,prefix){
			prefix = prefix || 'wooui-';
			el = $(el).get(0);
			var id = prefix + (++idIndex);
			return el ? (el.id ? el.id : (el.id = id)) : id;
		},

		/**
		 *  当传入的参数是一个javascript数组，则返回true，否则返回false
		 *
		 * @param {Object}
		 * @return {Boolean}
		 */
		isArray : function(v) {
			return v && typeof v.length == 'number'
					&& typeof v.splice == 'function';
		},

		/**
		 *  当传入的参数是一个javascript日期对象，则返回true，否则返回false
		 *
		 * @param {Date}
		 * @return {Boolean}
		 */
		isDate : function(v){
			return v && typeof v.getFullYear == 'function';
		},

		/**
         *  当传入的参数是一个javascript对象，则返回true，否则返回false
         * @param {Object} object
         * @return {Boolean}
         */
        isObject : function(v){
            return v && typeof v == 'object';
        },

        /**
         *  当传入的参数是一个javascript基本数据类型（包括String/Number/Boolean），则返回true，否则返回false
         * @param {Mixed} value
         * @return {Boolean}
         */
        isPrimitive : function(v){
            return $.woo.isString(v) || $.woo.isNumber(v) || $.woo.isBoolean(v);
        },

		/**
		 *  当传入的参数是一个javascript函数，则返回true，否则返回false
		 * @param {Object} fn
		 * @return {Boolean}
		 */
		isFunction : function(fn) {
			return !!fn && typeof fn != 'string' && !fn.nodeName
					&& fn.constructor != Array
					&& /^[\s[]?function/.test(fn + '');
		},

		/**
         *  当传入的参数是一个javascript数字，则返回true，否则返回false
         * @param {Object} v
         * @return {Boolean}
         */
        isNumber: function(v){
            return typeof v === 'number' && isFinite(v);
        },

        /**
         *  当传入的参数是一个javascript字符串，则返回true，否则返回false
         * @param {Object} v
         * @return {Boolean}
         */
        isString: function(v){
            return typeof v === 'string';
        },

        /**
         *  当传入的参数是一个javascript 布尔值，则返回true，否则返回false
         * @param {Object} v The object to test
         * @return {Boolean}
         */
        isBoolean: function(v){
            return typeof v === 'boolean';
        },

        /**
         *  当传入的参数不是一个javascript undefined，则返回true，否则返回false
         * @param {Object} v The object to test
         * @return {Boolean}
         */
        isDefined: function(v){
            return typeof v !== 'undefined';
        },

        /**
         *  当传入的参数不是一个javascript undefined,null或者空的字符串，则返回true，否则返回false
         * @param {Mixed} value
         * @param {Boolean} allowBlank (可选的) 是否把空字符串当做空
         * @return {Boolean}
         */
        isEmpty : function(v, allowBlank){
            return v === null || v === undefined || (!allowBlank ? v === '' : false);
        },

		/**
		 *  返回传入参数的类型 类型包括:
		 * <ul>
		 * <li><b>string</b>: 参数是一个字符串</li>
		 * <li><b>number</b>: 参数是一个字符串</li>
		 * <li><b>boolean</b>: 参数是一个布尔值</li>
		 * <li><b>function</b>: 参数是一个函数引用</li>
		 * <li><b>object</b>: 参数是一个对象</li>
		 * <li><b>array</b>: 参数是一个数组</li>
		 * <li><b>regexp</b>: 参数是一个正则表达式对象</li>
		 * <li><b>undefined</b>: 参数是undefined</li>
		 * <li><b>null</b>: 参数是一个null值</li>
		 * <li><b>element</b>: 参数是一个DOM元素</li>
		 * <li><b>nodelist</b>: 参数是一个DOM元素列表</li>
		 * <li><b>textnode</b>: 参数一个非空文本节点</li>
		 * <li><b>whitespace</b>: 参数是一个空白文本节点</li>
		 * </ul>
		 * @param {Mixed}
		 *            object
		 * @return {String}
		 */
		type : function(o) {
			if (o === undefined) {
				return 'undefined';
			}
			if (o === null) {
				return 'null';
			}
			if (o.htmlElement) {
				return 'element';
			}
			var t = typeof o;
			if (t == 'object' && o.nodeName) {
				switch (o.nodeType) {
					case 1 :
						return 'element';
					case 3 :
						return (/\S/).test(o.nodeValue)
								? 'textnode'
								: 'whitespace';
				}
			}
			if (t == 'object' || t == 'function') {
				switch (o.constructor) {
					case Array :
						return 'array';
					case RegExp :
						return 'regexp';
				}
				if (typeof o.length == 'number' && typeof o.item == 'function') {
					return 'nodelist';
				}
			}
			return t;
		},
		/**
         *  将一个对象转变为一个url的参数字符串。
         * @param {Object} o
         * @param {String} pre 前缀
         * @return {String}
         */
        urlEncode: function(o, pre){
            var undef, buf = [], key, e = encodeURIComponent;

            for(key in o){
                undef = !$.woo.isDefined(o[key]);
                $.woo.each(undef ? key : o[key], function(val, i){
                    buf.push('&', e(key), '=', (val != key || !undef) ? e(val) : '');
                });
            }
            if(!pre){
                buf.shift();
                pre = '';
            }
            return pre + buf.join('');
        },

        /**
         *  将一个url的参数字符串转变为一个对象。
         * @param {String} string
         * @param {Boolean} overwrite (可选的) 同名参数用一个数组代替。默认为false。
         * @return {Object}
         */
        urlDecode : function(string, overwrite){
            if(!string || !string.length){
                return {};
            }
            var obj = {};
            var pairs = string.split('&');
            var pair, name, value;
            for(var i = 0, len = pairs.length; i < len; i++){
            	var pos = pairs[i].indexOf('=');
            	if(pos <= 0){
            		continue;
            	}
            	name = decodeURIComponent(pairs[i].slice(0,pos));
            	value = decodeURIComponent(pairs[i].slice(pos+1));
                /*pair = pairs[i].split('=');
                name = decodeURIComponent(pair[0]);
                value = decodeURIComponent(pair[1]);*/
                if(overwrite !== true){
                    if(typeof obj[name] == 'undefined'){
                        obj[name] = value;
                    }else if(typeof obj[name] == 'string'){
                        obj[name] = [obj[name]];
                        obj[name].push(value);
                    }else{
                        obj[name].push(value);
                    }
                }else{
                    obj[name] = value;
                }
            }
            return obj;
        },

        /**
         *  在一个url后面追加一个查询字符串
         * @param {String} url
         * @param {String} s
         * @return (String)
         */
        urlAppend : function(url, s){
            if(!$.woo.isEmpty(s)){
                return url + (url.indexOf('?') === -1 ? '?' : '&') + s;
            }
            return url;
        },

        /**
         *  遍历数组并对每一个数组项执行一个函数
         * @param {Array} array 要遍历的数组
         * @param {Function} fn 遍历回调函数，回调函数通过下面参数来调用：
         * <ul>
         * <li>item {Mixed} : 数组中的第index个item</li>
         * <li>index{Number} : 数组中item的索引</li>
         * <li>array {Array} : 被遍历的数组</li>
         * </ul>
         * @param {Object} scope 回调函数执行的作用域
         *
         */
		each : function(array, fn, scope){
            if(typeof array.length == 'undefined' || typeof array == 'string'){
                array = [array];
            }
            for(var i = 0, len = array.length; i < len; i++){
                if(fn.call(scope || array[i], array[i], i, array) === false){ return i; };
            }
        },

        /**
         * 将一个可迭代的值（有数字索引和length属性）转化为一个真正的数组。
         * <p>注意，不要用于字符串，因为IE不支持"abc"[0]这样的写法。如果要将字符串转化为数组，可以使用"abc".match(/./g) ===> [a,b,c]</p>
         * @param {Iterable} 要转化为数组的可迭代对象
         * @return (Array) 数组
         */
        toArray : function(){
        	//TODO !$.support.htmlSerialize用于判断是ie浏览器，这个判断不合理，以后要改
            return !$.support.htmlSerialize ?
                function(a, i, j, res){
                    res = [];
                    $.woo.each(a, function(v) {
                        res.push(v);
                    });
                    return res.slice(i || 0, j || res.length);
                } :
                function(a, i, j){
                    return Array.prototype.slice.call(a, i || 0, j || a.length);
                }
        }(),
        /**
         *  遍历一个数组或迭代一个对象的属性，并执行一个函数。
         * <b>Note</b>: 如果确定要遍历一个数组，最好使用each方法。
         * @param {Object/Array} object
         * @param {Function} fn
         * 如果函数返回false，则迭代将会停止。
         */
        iterate : function(obj, fn, scope){
            if(isIterable(obj)){
                $.woo.each(obj, fn, scope);
                return;
            }else if($.woo.isObject(obj)){
                for(var prop in obj){
                    if(obj.hasOwnProperty(prop)){
                        if(fn.call(scope || obj, prop, obj[prop]) === false){
                            return;
                        };
                    }
                }
            }
        },
        /**
         *  验证一个值是否数字，如果不是，则返回一个默认值。
         * @param {Mixed} value 应该是一个数字，但是任何类型都可以传入
         * @param {Number} defaultValue 原始值如果不是数字，则返回这个默认值
         * @return {Number} 返回 Value 或 defaultValue
         */
        num : function(v, defaultValue){
            v = Number(v === null || typeof v == 'boolean'? NaN : v);
            return isNaN(v)? defaultValue : v;
        },

        /**
         * 设置值，允许空值时，如果为空值，则返回默认值。
         */
        value : function(v, defaultValue, allowBlank){
            return $.woo.isEmpty(v, allowBlank) ? defaultValue : v;
        },

        /**
         * 设置dom元素的透明度
         * @param {DOM}
         * @param {val}
         * @return {DOM}
         */
        setOpacity : function(dom,val){
        	if (!dom.currentStyle || !dom.currentStyle.hasLayout) dom.style.zoom = 1;
            if (window.ActiveXObject) dom.style.filter = ($ == 1) ? "": "alpha(opacity=" + $ * 100 + ")";
            dom.style.opacity = $;
            return dom;
        },

        /**
         *  将一个正则表达式中的已定义符号转义
         * @param {String} str
         * @return {String}
         */
        escapeRe : function(s) {
            return s.replace(/([.*+?^${}()|[\]\/\\])/g, '\\$1');
        },

        ascii : function(str){
        	return String(str).replace(/[^\u0000-\u00FF]/g,function($0){
	        			return escape($0).replace(/(%u)(\w{4})/gi,"\\u$2")
	        		});
        },

        unascii : function(){
        	return unescape(str.replace(/\\u/g,"%u"));
        },
        /**
         * 获取指定字符的unicode编码，比如空格的unicode编码为 &#160;
         * @param {String} c
         * @return {String} unicode编码
         */
        getUnicode : function(c){
        	return '&#' + c.charCodeAt() + ';';
        },

        /**
         * 将将字符串中的特殊字符[<>&"]转为unicode编码
         * @param {String} str
         * @param {String}
         */
        tran2unicode : function(str){
        	return String(str).replace(/[<>&"]/g,$.woo.getUnicode);
        },
        /**
         *  创建一个数组备份，并且将其中的空值清除掉，并将新的数组返回。该方法不会改变原始数组。
         * @param {Array/NodeList} arr 需要清除的数组
         * @return {Array} 新的，去掉空值的数组
         */
        clean : function(arr){
            var ret = [];
            $.woo.each(arr, function(v){
                if(!!v){
                    ret.push(v);
                }
            });
            return ret;
        },

        /**
         *  创建一个数组的备份，并且将重复的值过滤掉。该方法不会改变原始数组。
         * @param {Array} arr 需要过滤的值
         * @return {Array} 过滤后的不包含重复值的数组
         */
        unique : function(arr){
            var ret = [],
                collect = {};

            $.woo.each(arr, function(v) {
                if(!collect[v]){
                    ret.push(v);
                }
                collect[v] = true;
            });
            return ret;
        },

        /**
         *  将一个多维数组转变为一个一维数组，并返回。该方法不会改变原始数组。
         * @param {Array} arr 多维数组
         * @return {Array} 新的
         */
        flatten : function(arr){
            var worker = [];
            function rFlatten(a) {
                $.woo.each(a, function(v) {
                    if($.woo.isArray(v)){
                        rFlatten(v);
                    }else{
                        worker.push(v);
                    }
                });
                return worker;
            }
            return rFlatten(arr);
        },

        /**
         *  返回数组中的最小值
         * @param {Array|NodeList} arr The Array from which to select the minimum value.
         * @param {Function} comp (optional) a function to perform the comparision which determines minimization.
         *                   If omitted the '<' operator will be used. Note: gt = 1; eq = 0; lt = -1
         * @return {Object} The minimum value in the Array.
         */
        min : function(arr, comp){
            var ret = arr[0];
            comp = comp || function(a,b){ return a < b ? -1 : 1; };
            $.woo.each(arr, function(v) {
                ret = comp(ret, v) == -1 ? ret : v;
            });
            return ret;
        },

        /**
         *  返回数组中的最大值
         * @param {Array|NodeList} arr
         * @param {Function} comp (可选的) 自定义的比较器。默认为 “>”比较。
         *
         * @return {Object} 数组中的最大值
         */
        max : function(arr, comp){
            var ret = arr[0];
            comp = comp || function(a,b){ return a > b ? 1 : -1; };
            $.woo.each(arr, function(v) {
                ret = comp(ret, v) == 1 ? ret : v;
            });
            return ret;
        },

        /**
         *  计算数组中的平均值
         * @param {Array} arr
         * @return {Number}
         */
        mean : function(arr){
           return $.woo.sum(arr) / arr.length;
        },

        /**
         *  计算数组中所有项的总和
         * @param {Array} arr
         * @return {Number}
         */
        sum : function(arr){
           var ret = 0;
           $.woo.each(arr, function(v) {
               ret += v;
           });
           return ret;
        },

        /**
         * 将一个集合按照true/false的值类型进行区分，得到两个集合
         * <pre><code>
// 例子 1:
TOONE.partition([true, false, true, true, false]); // [[true, true, true], [false, false]]

// 例子 2:
TOONE.partition(
    TOONE.query('p'),
    function(val){
        return val.className == 'class1'
    }
);
// 如果p元素的css样式类为 'class1'则为true，
// 如果p元素没有名字为'class1'的css样式类，则为false
         * </code></pre>
         * @param {Array|NodeList} arr 需要区分的集合。
         * @param {Function} truth （可选的） 一个用来确定 true or false 的函数。如果省略该函数，则集合中的值本身必须可以进行true/false判断。
         * @return {Array} [true<Array>,false<Array>]
         */
        partition : function(arr, truth){
            var ret = [[],[]];
            $.woo.each(arr, function(v, i, a) {
                ret[ (truth && truth(v, i, a)) || (!truth && v) ? 0 : 1].push(v);
            });
            return ret;
        },

        /**
         * 在数组的每一项上调用同一个方法。
         * <pre><code>
// 例子：
TOONE.invoke(TOONE.query('p'), 'getAttribute', 'id');
// [el1.getAttribute('id'), el2.getAttribute('id'), ..., elN.getAttribute('id')]
         * </code></pre>
         * @param {Array|NodeList} arr 需要执行方法的数组
         * @param {String} methodName  执行的方法名
         * @param {Anything} ... 方法被调用时传递的参数
         * @return {Array} 最终的方法调用后得到的结果。
         */
        invoke : function(arr, methodName){
            var ret = [],
                args = Array.prototype.slice.call(arguments, 2);
            $.woo.each(arr, function(v,i) {
                if (v && typeof v[methodName] == 'function') {
                    ret.push(v[methodName].apply(v, args));
                } else {
                    ret.push(undefined);
                }
            });
            return ret;
        },

        /**
         * 获取每一个数组项的某个属性值，组成新的数组，并返回。
         * <pre><code>
// 例子：
TOONE.pluck(TOONE.query('p'), 'className'); // [el1.className, el2.className, ..., elN.className]
         * </code></pre>
         * @param {Array|NodeList} arr 需要获取值的对象组成的数组
         * @param {String} prop 属性名
         * @return {Array}
         */
        pluck : function(arr, prop){
            var ret = [];
            $.woo.each(arr, function(v) {
                ret.push( v[prop] );
            });
            return ret;
        },

        /**
         *  创建并返回一个 'version 4' RFC-4122 UUID 字符串
         * @param {Array} arr
         * @return {Number}
         */
        createUUID: function(){
			var s = [], itoh = ['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F'];
			for (var i = 0; i < 36; i++) s[i] = Math.floor(Math.random()*0x10);
			s[14] = 4;
			s[19] = (s[19] & 0x3) | 0x8;
			for (var i = 0; i < 36; i++) s[i] = itoh[s[i]];
			s[8] = s[13] = s[18] = s[23] = '-';
			return s.join('');
        },

        /**
         *  将数字按照给定的格式进行格式化。格式化模板匹配/^(#*,)?(#*(#|0))\.(#*)$/，例如以下格式：
         *
         * <li>##.##</li> //如果该值为零，则不显示，返回一个空格的unicode编码 “&#160;”
		 * <li>##.#####</li>
		 * <li>##</li>只显示四舍五入的整数
         * <li>#0.##</li> //这个模板的意思是：如果该值为零，则强制显示，而不是转为空
         * <li>###,###.##</li>
         * <li>###,###</li>分段的整数
         * <li>###,##0.##</li>
         *
         * <pre><code>
			// 例子：
			$.woo.numberFormat(2134.5685,'##.##'); // 2134.57
			$.woo.numberFormat(2134.5685,'##.###'); // 2134.569
			$.woo.numberFormat(0.00000,'##.##'); // &#160;
			$.woo.numberFormat('000.00000','##.##'); // &#160;
			$.woo.numberFormat(0.00000,'#0.##'); // 0.00
			$.woo.numberFormat(2342134.5685,'###,###.###'); // 2,342,134.57
         * </code></pre>
         *
         * @param {Number/String} value 需要格式化的值
         * @param {String} pattern 格式化模板
         * @return {Number} 格式化后的值
         */
        numberFormat : function(value, pattern) {
			var re = /^(#*,)?(#*(#|0))\.(#*)$/;
			var re2 = /^\s+|\s+$/g;

			var t = pattern.replace(re2,'');

			var result = re.exec(t);

			var len = result[4].length;

			var tail = '.' + String(Math.pow(10,len)).slice(1);

			var ps,whole,sub,suf;

			if(result[1] == ''){
				if(Number(value) == 0){
					result[3]== '0' ? value = '0' + tail : value = '&#160;';
				}else{
					value = Math.round(value*Math.pow(10,len))/Math.pow(10,len);
					value = String(value);
					ps = value.split('.');
					suf = String(Math.pow(10,len - (ps[1] ? ps[1].length : len))).slice(1);
					whole = ps[0];
					sub = ps[1] ? '.'+ ps[1] + suf : tail;
					value = whole + sub;
				}
			}else{
				if(Number(value) == 0){
					result[3]== '0' ? value = '0' + tail : value = '&#160;';
				}else{

					value = Math.round(value*Math.pow(10,len))/Math.pow(10,len);
					if((result[1].length - 1) ==  result[2].length){
						value = String(value);
						ps = value.split('.');
						suf = String(Math.pow(10,len - (ps[1] ? ps[1].length : len))).slice(1);
						whole = ps[0];
						sub = ps[1] ? '.'+ ps[1] +suf : tail;
						var r = new RegExp('(\\d+)(\\d{'+result[2].length+'})');
						while (r.test(whole)) {
							whole = whole.replace(r, '$1' + ',' + '$2');
						}
						value = whole + sub;
					}
				}
			}
			return value;
		},

		/**
		 * 将一个数字格式化为人民币格式
		 *
		 * @param {Number/String}
		 *            v 需要格式化的数字
		 * @param {Boolean}
		 *            cap 是否格式化为大写
		 * @return {String} 货币格式的字符串
		 */
		RMB : function(v, cap) {
			v = (Math.round((v - 0) * 100)) / 100;
			v = (v == Math.floor(v)) ? v + ".00" : ((v * 10 == Math.floor(v
					* 10)) ? v + "0" : v);
			v = String(v);
			var ps = v.split('.');
			var whole = ps[0];
			var sub = ps[1] ? '.' + ps[1] : '.00';
			var r = /(\d+)(\d{3})/;
			while (r.test(whole)) {
				whole = whole.replace(r, '$1' + ',' + '$2');
			}
			v = whole + sub;
			if (v.charAt(0) == '-') {
				return '-' + v.substr(1);
			}
			return "&#0165;" + v;//人民币符号的unicode编码为&#0165;
		},

		/**
		 * 截取一个长字符串的第N-3个字符以后的内容代替为指定的内容或者'...'，例如：
		 * &.woo.ellipsis('同望科技前端开发组有一群热情洋溢的年轻人',10)返回'同望科技前端开发组有...'
		 *
		 * @param {String}
		 *            value 需要截取的字符串
		 * @param {Number}
		 *            len 指定的剩余字符串长度
		 * @param {String}
		 *            str 指定的内容，该参数可选，默认为'...'
		 * @return {String} 截取替换完成以后的字符串
		 */
		ellipsis : function(value, len, str) {
			if (value && value.length > len) {
				return value.substr(0, len - 3) + (str ? str : "...");
			}
			return value;
		},
		/**
		 * 返回一个指定数值的子字符串，可以是一个数字
		 *
		 * @param {String}
		 *            value 原始的数据
		 * @param {Number}
		 *            start 子串开始的位置
		 * @param {Number}
		 *            length 子串的长度
		 * @return {String} 子字符串
		 */
		slice : function(value, start, length) {
			return String(value).slice(start, length);
		},

		/**
		 * 返回一个指定数值的所有字符转换为小写字符，可以是一个数字
		 *
		 * @param {String}
		 *            value 需要转换的数值
		 * @return {String} 转换后的字符串
		 */
		lowercase : function(value) {
			return String(value).toLowerCase();
		},

		/**
		 * 返回一个指定数值的所有字符转换为大写字符，可以是一个数字
		 *
		 * @param {String}
		 *            value 需要转换的数值
		 * @return {String} 转换后的字符串
		 */
		uppercase : function(value) {
			return String(value).toUpperCase();
		},

		/**
		 * 将一个字符串的首字母变为大写,其他字符则全部变为小写
		 *
		 * @param {String}
		 *            value 需要转变的字符串
		 * @return {String} 转变后的字符串
		 */
		cap : function(value) {
			return !value ? value : value.charAt(0).toUpperCase()
					+ value.substr(1).toLowerCase();
		},
		/**
		 * 返回一个指定字符串的子字符串，使用方法：
		 * <p><ul>
		 * <li>从I love bicycle中截取love，&.woo.substr('I love bicycle',3,4);</li>
		 * <li>从数字1234567中需要获取234，&.woo.substr(1234567,2,3);</li>
		 * </ul></p>
		 * @param {mixed}
		 *            value 原始的数据
		 * @param {Number}
		 *            start 子串开始的位置
		 * @param {Number}
		 *            length 子串的长度
		 * @return {String} 子字符串
		 */
		substr : function(value, start, length) {
			return String(value).substr(start, length);
		},

		/**
		 * 格式化文件尺寸 (xxx bytes, xxx KB, xxx MB)
		 *
		 * @param {Number/String}
		 *            文件长度
		 * @return {String} 格式化后的字符串
		 */
		fileSize : function(size) {
			if (size < 1024) {
				return size + " bytes";
			} else if (size < 1048576) {
				return (Math.round(((size * 10) / 1024)) / 10) + " KB";
			} else {
				return (Math.round(((size * 10) / 1048576)) / 10) + " MB";
			}
		},

		/**
		 * @description 克隆一个引用数据类型{Object/Array}的值，如果需要深度克隆，则第二个参数传入true
		 * @param {Object/Array} value 需要克隆的原始值
		 * @param {Boolean} deep 是否深度克隆，默认为false
		 * @return {Object/Array}
		 */
		clone: function(value, deep){
			deep = deep != 'undefined' ? deep : false;

			if(this.isArray(value)){
				var aa = Array.prototype.slice.call(value,0);
				if(deep){
					for(var i = 0, l = aa.length;i < l; i++){
						var copy = aa[i];
						if(copy && ($.woo.isArray(copy) || $.woo.isObject(copy))){
							aa[i] = $.woo.clone(copy,deep);
						}
					}
				}
				return aa;
			}

			var ret = {};
			for ( var name in value ) {
				var src = ret[ name ], copy = value[ name ];

				// 预防无限循环
				if ( ret === copy )
					continue;

				// 如果是深度拷贝，则递归复制
				if ( deep && copy && ($.woo.isArray(copy) || $.woo.isObject(copy)) )
					ret[ name ] = $.woo.clone(copy, deep);

				// 排除掉 undefined 值
				else if ( copy !== undefined )
					ret[ name ] = copy;
			}

			return ret;
		},

		/**
		 * 获取指定目标的某个样式的值，如果值不为数字，例如auto之类的，则返回0
		 */
		getStyleValue : function(target,css){
			var val = parseInt($(target).css(css));
				if (isNaN(val)) {
					return 0;
				} else {
					return val;
				}
		},
		//private
	plugin: {
		add: function(module, option, set) {
			var proto = $.woo[module].prototype;
			for(var i in set) {
				proto.plugins[i] = proto.plugins[i] || [];
				proto.plugins[i].push([option, set[i]]);
			}
		},
		call: function(instance, name, args) {
			var set = instance.plugins[name];
			if(!set || !instance.element[0].parentNode) { return; }

			for (var i = 0; i < set.length; i++) {
				if (instance.options[set[i][0]]) {
					set[i][1].apply(instance.element, args);
				}
			}
		}
	},


	/**
	 * 判断节点a是否包含节点b （关于compareDocumentPosition和contains 用法见 http://www.cnblogs.com/siceblue/archive/2010/02/02/1661833.html）
	 *
	 * @param {HTMLElement} a DOM节点
	 * @param {HTMLElement} b DOM节点
	 * @return {Boolean}
	 */
	contains: function(a, b) {
		return document.compareDocumentPosition
			? a.compareDocumentPosition(b) & 16//与16按位于的原因是，如果包含 值为20 即返回true; 如果不包含值为 0 即返回false
			: a !== b && a.contains(b);
	},

	//判断元素的sroll 在top或者left方向是是否有滚动
	/**
	 *
	 */
	hasScroll: function(el, a) {

		//If overflow is hidden, the element might have extra content, but the user wants to hide it
		if ($(el).css('overflow') == 'hidden') { return false; }

		var scroll = (a && a == 'left') ? 'scrollLeft' : 'scrollTop',
			has = false;

		if (el[scroll] > 0) { return true; }

		// TODO: determine which cases actually cause this to happen
		// if the element doesn't have the scroll set, see if it's possible to
		// set the scroll
		el[scroll] = 1;
		has = (el[scroll] > 0);
		el[scroll] = 0;
		return has;
	},

    //确定x坐标是否在元素内部
    //x：要确认的坐标；reference：参考坐标；size：元素宽度
	isOverAxis: function(x, reference, size) {
		//Determines when x coordinate is over "b" element axis
		return (x > reference) && (x < (reference + size));
	},
	//确定x、y坐标是否同事在元素内部
  	//x、y：坐标；top、left：元素坐标；height、width：元素宽高
	isOver: function(y, x, top, left, height, width) {
		//Determines when x, y coordinates is over "b" element
		return $.ui.isOverAxis(y, top, height) && $.ui.isOverAxis(x, left, width);
	},

	keyCode: {
		BACKSPACE: 8,
		CAPS_LOCK: 20,
		COMMA: 188,
		CONTROL: 17,
		DELETE: 46,
		DOWN: 40,
		END: 35,
		ENTER: 13,
		ESCAPE: 27,
		HOME: 36,
		INSERT: 45,
		LEFT: 37,
		NUMPAD_ADD: 107,
		NUMPAD_DECIMAL: 110,
		NUMPAD_DIVIDE: 111,
		NUMPAD_ENTER: 108,
		NUMPAD_MULTIPLY: 106,
		NUMPAD_SUBTRACT: 109,
		PAGE_DOWN: 34,
		PAGE_UP: 33,
		PERIOD: 190,
		RIGHT: 39,
		SHIFT: 16,
		SPACE: 32,
		TAB: 9,
		UP: 38
	},
	/**
	 * 动态加入样式文件
	 *
	 * @param {}
	 *            href
	 * @return {}
	 */
	addCssStyle : function(href) {
		return $('<link href="' + href
				+ '" rel="stylesheet" type="text/css" />').appendTo('head');
	},
	importAllJs:function(jsArr){
		$.woo.importJs(jsArr,0);
	},
	/**
	 * 动态加入js
	 */
	importJs : function(jsArguments,jsIndex) {
		var jsLength=jsArguments.length;
		var _script=document.createElement('script');
        _script.setAttribute('charset','gbk');
        _script.setAttribute('type','text/javascript');
        _script.setAttribute('src',jsArguments[jsIndex]);
        document.getElementsByTagName('head')[0].appendChild(_script);
        if($.woo.Browser.ie){
            _script.onreadystatechange=function(){
                if(this.readyState=='loaded'||this.readyState=='complete'){
                	var nextIndex=jsIndex+1;
                	if(nextIndex<jsLength){
                		$.woo.importJs(jsArguments,nextIndex);
                	}
                }
            };
        }else if($.woo.Browser.moz){
            _script.onload=function(){

            };
        }else{
        }

		//fCallback=function(){};
		// document.write('<script type=\"text/javascript\" src=\"' + href + '\"></script>');
//		return $('<script type="text/javascript" src="' + href
//				+ '"></script>').appendTo('head');
//		if(!jsHis[href]){
//			jsHis[href]=false;
//		}

	},
	/**
	 * 通过值获取数据字典名称
	 */
	getDataName:function(v,data){
		for (var i = 0; i < data.length; i++) {
			if (data[i].dataCode == v) {
				return data[i].text;
				break;
			}
		}
	}
});

/*
 * json支持。字符串转javascript对象、javascript对象转字符串
 */
$.woo.JSON = new (function() {
	var useHasOwn = {}.hasOwnProperty ? true : false;

	var pad = function(n) {
		return n < 10 ? "0" + n : n;
	};

	var m = {
		"\b" : '\\b',
		"\t" : '\\t',
		"\n" : '\\n',
		"\f" : '\\f',
		"\r" : '\\r',
		'"' : '\\"',
		"\\" : '\\\\'
	};

	var encodeString = function(s) {
		if (/["\\\x00-\x1f]/.test(s)) {
			return '"' + s.replace(/([\x00-\x1f\\"])/g, function(a, b) {
						var c = m[b];
						if (c) {
							return c;
						}
						c = b.charCodeAt();
						return "\\u00" + Math.floor(c / 16).toString(16)
								+ (c % 16).toString(16);
					}) + '"';
		}
		return '"' + s + '"';
	};

	var encodeArray = function(o) {
		var a = ["["], b, i, l = o.length, v;
		for (i = 0; i < l; i += 1) {
			v = o[i];
			switch (typeof v) {
				case "undefined" :
				case "function" :
				case "unknown" :
					break;
				default :
					if (b) {
						a.push(',');
					}
					a.push(v === null ? "null" : $.obj2json(v));
					b = true;
			}
		}
		a.push("]");
		return a.join("");
	};

	var encodeDate = function(o) {
		return '"' + o.getFullYear() + "-" + pad(o.getMonth() + 1) + "-"
				+ pad(o.getDate()) + "T" + pad(o.getHours()) + ":"
				+ pad(o.getMinutes()) + ":" + pad(o.getSeconds()) + '"';
	};


	this.encode = function(o) {
		if (typeof o == "undefined" || o === null) {
			return "null";
		} else if (o instanceof Array) {
			return encodeArray(o);
		} else if (o instanceof Date) {
			return encodeDate(o);
		} else if (typeof o == "string") {
			return encodeString(o);
		} else if (typeof o == "number") {
			return isFinite(o) ? String(o) : "null";
		} else if (typeof o == "boolean") {
			return String(o);
		} else {
			var a = ["{"], b, i, v;
			for (i in o) {
				if (!useHasOwn || o.hasOwnProperty(i)) {
					v = o[i];
					switch (typeof v) {
						case "undefined" :
						case "function" :
						case "unknown" :
							break;
						default :
							if (b) {
								a.push(',');
							}
							a.push($.woo.JSON.encode(i), ":", v === null
											? "null"
											: $.woo.JSON.encode(v));
							b = true;
					}
				}
			}
			a.push("}");
			return a.join("");
		}
	};


	this.decode = function(json) {
		 return (new Function("return " + json.trim() + ";"))();

	};
})();
$.extend({
	obj2json : $.woo.JSON.encode,
	json2obj : $.woo.JSON.decode
});


	//jQuery 插件
	$.fn.extend({
		_focus: $.fn.focus,
		//设置元素焦点（delay：延迟时间）可编辑表格出现堆栈溢出，暂时去掉
//		focus: function(delay, fn) {
//			return typeof delay === 'number'
//				? this.each(function() {
//					var elem = this;
//					setTimeout(function() {
//						$(elem).focus();
//						(fn && fn.call(elem));
//					}, delay);
//				})
//				: this._focus.apply(this, arguments);
//		},
		//设置元素支持被选择
		enableSelection: function() {
			return this
				.attr('unselectable', 'off')
				.css('MozUserSelect', '')
				.unbind('selectstart.ui');
		},
		 //设置元素不支持被选择
		disableSelection: function() {
			return this
				.attr('unselectable', 'on')
				.css('MozUserSelect', 'none')
				.bind('selectstart.ui', function() { return false; });
		},

		//获取设置滚动属性的 父元素
		scrollParent: function() {
			var scrollParent;
			if(($.browser.msie && (/(static|relative)/).test(this.css('position'))) || (/absolute/).test(this.css('position'))) {
				scrollParent = this.parents().filter(function() {
					return (/(relative|absolute|fixed)/).test($.curCSS(this,'position',1)) && (/(auto|scroll)/).test($.curCSS(this,'overflow',1)+$.curCSS(this,'overflow-y',1)+$.curCSS(this,'overflow-x',1));
				}).eq(0);
			} else {
				scrollParent = this.parents().filter(function() {
					return (/(auto|scroll)/).test($.curCSS(this,'overflow',1)+$.curCSS(this,'overflow-y',1)+$.curCSS(this,'overflow-x',1));
				}).eq(0);
			}

			return (/fixed/).test(this.css('position')) || !scrollParent.length ? $(document) : scrollParent;
		},
	 	//设置或获取元素的垂直坐标
		zIndex: function(zIndex) {
			if (zIndex !== undefined) {
				return this.css('zIndex', zIndex);
			}

			if (this.length) {
				var elem = $(this[0]), position, value;
				while (elem.length && elem[0] !== document) {
					// Ignore z-index if position is set to a value where z-index is ignored by the browser
					// This makes behavior of this function consistent across browsers
					// WebKit always returns auto if the element is positioned
					position = elem.css('position');
					if (position == 'absolute' || position == 'relative' || position == 'fixed')
					{
						// IE returns 0 when zIndex is not specified
						// other browsers return a string
						// we ignore the case of nested elements with an explicit value of 0
						// <div style="z-index: -10;"><div style="z-index: 0;"></div></div>
						value = parseInt(elem.css('zIndex'));
						if (!isNaN(value) && value != 0) {
							return value;
						}
					}
					elem = elem.parent();
				}
			}

			return 0;
		},
		 mask: function(options){
                this.unmask();

                // 参数
                var op = $.extend({
                    opacity: 0.5,
                    msg: '',
                    z: 10000,
                    maskDivClass:'',
                    bgcolor: '#ccc'
                },options);

                var original=$(document.body);
                var position={top:0,left:0};
                            if(this[0] && this[0]!==window.document){
                                original=this;
                                position=original.position();
                            }
                // 创建一个 Mask 层，追加到对象中
                var maskDiv=$('<div class="maskdivgen">&nbsp;</div>');
                maskDiv.appendTo(original);
                var maskWidth=original.outerWidth();
                if(!maskWidth){
                    maskWidth=original.width();
                }
                var maskHeight=original.outerHeight();
                if(!maskHeight){
                    maskHeight=original.height();
                }
                maskDiv.css({
                    position: 'absolute',
                    top: position.top,
                    left: position.left,
                    'z-index': op.z,
                  	width: maskWidth,
                    height:maskHeight,
                    'background-color': op.bgcolor,
                    opacity: 0
                });
                if(op['maskDivClass']){
                    maskDiv.addClass(op['maskDivClass']);
                }
                if(op['msg']){
                    var msgDiv=$('<div style="position:absolute;border:#6593cf 1px solid; padding:2px;background:#ccca"><div style="line-height:24px;border:#a3bad9 1px solid;background:white;padding:2px 10px 2px 10px">'+op['msg']+'</div></div>');
                    msgDiv.appendTo(maskDiv);
                    var widthspace=(maskDiv.width()-msgDiv.width());
                    var heightspace=(maskDiv.height()-msgDiv.height());
                    msgDiv.css({
                                cursor:'wait',
                                top:(heightspace/2-2),
                                left:(widthspace/2-2)
                      });
                  }
                  maskDiv.fadeIn('fast', function(){
                    // 淡入淡出效果
                    $(this).fadeTo('slow', op.opacity);
                })
                return maskDiv;
            },
         	unmask: function(){
                 var original=$(document.body);
                     if(this[0] && this[0]!==window.document){
                        original=$(this[0]);
                  }
                  original.find("> div.maskdivgen").fadeOut('slow',0,function(){
                      $(this).remove();
                  });
            }
	});

/*
 * 扩展选择器引擎的功能
 * $.extend($.expr[':'],{
	    inline: function(a) {
	        return $(a).css('display') === 'inline';
	    }
	});
	$(':inline'); // Selects ALL inline elements
	$('a:inline'); // Selects ALL inline anchors
 */
$.extend($.expr[':'], {
	/**
	 * match:一个匹配数组
	 * match[0]:data(key)
	 * match[1]:data
	 * match[2]:未知
	 * match[3]:key
	 */
	data: function(elem, i, match) {
		return !!$.data(elem, match[3]);
	},

	focusable: function(element) {
		var nodeName = element.nodeName.toLowerCase(),
			tabIndex = $.attr(element, 'tabindex');
		return (/input|select|textarea|button|object/.test(nodeName)
			? !element.disabled
			: 'a' == nodeName || 'area' == nodeName
				? element.href || !isNaN(tabIndex)
				: !isNaN(tabIndex))
			// the element and all of its ancestors must be visible
			// the browser may report that the area is hidden
			&& !$(element)['area' == nodeName ? 'parents' : 'closest'](':hidden').length;
	},

	tabbable: function(element) {
		var tabIndex = $.attr(element, 'tabindex');
		return (isNaN(tabIndex) || tabIndex >= 0) && $(element).is(':focusable');
	}
});


/**
 * @class String 对原生的JavaScript字符串原型进行功能扩展，扩展的功能可用于任意一个字符串
 */
$.extend(String.prototype, {
	/**
			 * 将传入字符串中的 ' 和 \ 这两个字符进行转义
			 *
			 * @param {String}
			 *            string 要转义的字符串
			 * @return {String} 经过转义的字符串
			 * @static
			 */
			escape : function(string) {
				return string.replace(/('|\\)/g, "\\$1");
			},

			/**
			 * 在字符串的左边补上指定的字符 使用例子：
			 *
			 * <pre><code>
			 * var s = String.leftPad('123', 5, '0');// '00123'
			 * </code></pre>
			 *
			 * @param {String}
			 *            string 原始字符串
			 * @param {Number}
			 *            size 自定字符重复的次数
			 * @param {String}
			 *            char （可选的）指定的字符。默认为空字符串“ ”
			 * @return {String} 补上指定字符后的新字符串
			 * @static
			 */
			leftPad : function(val, size, ch) {
				var result = new String(val);
				if (ch === null || ch === undefined || ch === '') {
					ch = " ";
				}
				while (result.length < size) {
					result = ch + result;
				}
				return result;
			},

			/**
			 * 字符串格式化，将字符串中的代号 {0}, {1}...替换为相应参数列表位置中的值 使用例子：
			 *
			 * <pre><code>
			 * var cls = 'my-class', text = 'Some text';
			 * var s = String.format('&lt;div class=&quot;{0}&quot;&gt;{1}&lt;/div&gt;', cls, text);
			 * // '&lt;div class=&quot;my-class&quot;&gt;Some text&lt;/div&gt;'
			 * </code></pre>
			 *
			 * @param {String}
			 *            string
			 * @param {String}
			 *            value1 用来代替代号{0}的值
			 * @param {String}
			 *            value2 。。。
			 * @return {String} 格式化过的字符串
			 * @static
			 */
			format : function(format) {
				var args = Array.prototype.slice.call(arguments, 1);
				return format.replace(/\{(\d+)\}/g, function(m, i) {
							return args[i];
						});
			},
			/**
			 * 将字符串在两个值之间切换。如果字符串和传入的第一个值相等，则返回另一个值。不等，则返回第一个值。当前字符串并没有被改变
			 *
			 * <pre><code>
			 * // 交替切换排序方向
			 * sort = sort.toggle('ASC', 'DESC');
			 *
			 * // 用来代替下面这个逻辑
			 * sort = (sort == 'ASC' ? 'DESC' : 'ASC');
			 * </code></pre>
			 *
			 * @param {String}
			 *            value 用来和当前字符串比较的值
			 * @param {String}
			 *            other 另一个值
			 * @return {String} 符合逻辑的值
			 */
			toggle : function(value, other) {
				return this == value ? other : value;
			},

			/**
			 * 截掉当前字符串两头的空白。 使用例子:
			 *
			 * <pre><code>
			 * var s = '  foo bar  ';
			 * alert('-' + s + '-'); //alerts &quot;- foo bar -&quot;
			 * alert('-' + s.trim() + '-'); //alerts &quot;-foo bar-&quot;
			 * </code></pre>
			 *
			 * @return {String} 截取过的字符串
			 */
			trim : function() {
				var re = /^\s+|\s+$/g;
				return this.replace(re, "");
			}
});

/**
 * @class Number 对原生的JavaScript数字原型进行功能扩展，扩展的功能可用于任意一个数字
 */
$.extend(Array.prototype, {
			/**
			 * 将数值限制在给定的[min,max]之间，如果在给定的区间，则返回自身，如果小于min，则返回min，如果大于max，则返回max
			 *
			 * @param {Number}
			 *            min 区间的最小值
			 * @param {Number}
			 *            max 区间的最大值
			 * @return {Number} 区间内的值
			 */
			constrain : function(min, max) {
				return Math.min(Math.max(this, min), max);
			}
		});

/**
 * @class Array 对原生的JavaScript数组原型进行功能扩展，扩展的功能可用于任意一个数组
 */
$.extend(Array.prototype, {
            /**
			 * 查找某个元素在数组中匹配的第一个位置，如果找不到，则返回-1。当元素是一个引用数据类型，则必须是绝对一致（===）才能找到。
			 *
			 * @param {Mixed}
			 *            elt 要查找的元素
			 * @param {Number}
			 *            from 开始查找位置，默认为0
			 * @return {Number} 元素在数组中的位置，如果找不到，则返回-1
			 */
			indexOf : function(elt, from) {
				var len = this.length;
				var from = Number(arguments[1]) || 0;
				from = (from < 0) ? Math.ceil(from) : Math.floor(from);
				if (from < 0)
					from += len;
				for (; from < len; from++) {
					if (from in this && this[from] === elt)
						return from;
				}
				return -1;
			},

			/**
			 * 查找某个元素在数组中匹配的最后一个位置，如果找不到，则返回-1。当元素是一个引用数据类型，则必须是绝对一致（===）才能找到。
			 *
			 * @param {Mixed}
			 *            elt 要查找的元素
			 * @param {Number}
			 *            from 开始查找位置，默认为0
			 * @return {Number} 元素在数组中的位置，如果找不到，则返回-1
			 */
			lastIndexOf : function(elt, from) {
				var len = this.length;
				var from = Number(arguments[1]);
				if (isNaN(from)) {
					from = len - 1;
				} else {
					from = (from < 0) ? Math.ceil(from) : Math.floor(from);
					if (from < 0)
						from += len;
					else if (from >= len)
						from = len - 1;
				}
				for (; from > -1; from--) {
					if (from in this && this[from] === elt)
						return from;
				}
				return -1;
			},

			/**
			 * 验证数组项。遍历数组，将每一个数组项传入<b>比较函数</b>执行，如果有一个数组项能使得比较函数返回true，则该函数返回true。注意和{@link #every}方法的区别
			 *
			 * @param {Function}
			 *            fn 比较函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 比较函数执行的作用域
			 * @return {Boolean} 有符合比较函数的比较规则的数组项则返回true，否则返回false
			 */
			some : function(fn, scope) {
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				for (var i = 0; i < len; i++) {
					if (i in this && fn.call(scope || window, this[i], i, this))
						return true;
				}
				return false;
			},

			/**
			 * 映射转换数组项。遍历数组，将每一个数组项通过<b>映射函数</b>进行映射转换，将结果数组返回。原数组不会改变。
			 *
			 * @param {Function}
			 *            fn 映射函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 映射函数执行的作用域
			 * @return {Array} 经过映射函数转换的新数组
			 */
			map : function(fn, scope) {
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				var res = new Array(len);
				for (var i = 0; i < len; i++) {
					if (i in this)
						res[i] = fn.call(scope || window, this[i], i, this);
				}
				return res;
			},

			/**
			 * 过滤数组，将数组通过<b>过滤函数</b>进行过滤，符合条件的数组项组成新的数组返回。原数组不会改变。
			 *
			 * @param {Function}
			 *            fn 过滤函数，符合过滤条件则返回true，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 过滤函数执行的作用域
			 * @return {Array} 经过过滤得到的新数组
			 */
			filter : function(fn, scope){
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				var res = new Array();
				for (var i = 0; i < len; i++){
					if (i in this){
						var val = this[i]; // in case fun mutates this
						if (fn.call(scope || window, val, i, this))
							res.push(val);
					}
				}
				return res;
			},

			/**
			 * 验证数组项。遍历数组，如果每一个数组项都符合<b>比较函数</b>的要求（即比较函数返回true），则该函数返回true。注意和{@link #some}方法的区别
			 *
			 * @param {Function}
			 *            fn 比较函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 比较函数执行的作用域
			 * @return {Boolean} 数组中的每一个数组项都符合比较函数的比较规则，则返回true，否则返回false
			 */
			every : function(fn, scope){
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				var thisp = arguments[1];
				for (var i = 0; i < len; i++){
					if (i in this && !fn.call(thisp, this[i], i, this))
						return false;
				}
				return true;
			},

			/**
			 * 遍历数组，以每一个数组项和数组项的索引位置作为参数传入<b>回调函数</b>执行。
			 * @param {Function}
			 *            fn 回调函数，参数为item（数组项）和itemIndex（数组项的索引位置）
			 * @param {Object}
			 *            scope 回调函数执行的作用域
			 * @return {}
			 */
			forEach : function(fn, scope){
				var len = this.length;
				if (typeof fn != "function")
					throw new TypeError();
				for (var i = 0; i < len; i++){
					if (i in this)
						fn.call(scope || window, this[i], i, this);
				}
			},

			/**
			 * 从数组中删除指定的数组项
			 * @param {Object}
			 *            o 要删除的数组项
			 * @return {Array} 数组本身
			 */
			remove : function(o) {
				var index = this.indexOf(o);
				if (index != -1) {
					this.splice(index, 1);
				}
				return this;
			}
    });

$(function(){
	if ($.woo.autoParse){
		$.woo.parse();
	}
});

//以定点表示法表示的数字
if (typeof(Number.prototype.toFixed) != "function") {

		Number.prototype.toFixed = function(d) {

			var s = this + "";
			if (!d)
				d = 0;
			if (s.indexOf(".") == -1)
				s += ".";
			s += new Array(d + 1).join("0");
			if (new RegExp("^(-|\\+)?(\\d+(\\.\\d{0," + (d + 1) + "})?)\\d*$")
					.test(s)) {

				var s = "0" + RegExp.$2, pm = RegExp.$1, a = RegExp.$3.length, b = true;
				if (a == d + 2) {
					a = s.match(/\d/g);
					if (parseInt(a[a.length - 1]) > 4) {

						for (var i = a.length - 2; i >= 0; i--) {
							a[i] = parseInt(a[i]) + 1;
							if (a[i] == 10) {
								a[i] = 0;
								b = i != 1;

							} else
								break;

						}

					}
					s = a.join("").replace(
							new RegExp("(\\d+)(\\d{" + d + "})\\d$"), "$1.$2");

				}
				if (b)
					s = s.substr(1);
				return (pm + s).replace(/\.$/, "");

			}
			return this + "";

		};

	}
})(jQuery);