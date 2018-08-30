(function($){
	if(!$) return;

	var idIndex = 0;
	$.woo = $.woo || {version:"@VERSION"};

/**
 * @class global
 * WooUI��ȫ�����ö�������趨���������ڿ���ͨ��ȫ�ֶ�����Լ��WooUI������ʹ�÷�ʽ������UI�����Ⱦ���ơ�ȫ���¼�������������������������񡢲���ϰ�ߵȡ����⻹���һЩȫ�ֵ����ݡ�
 * <p>ʹ�÷�����$.woo.global.�����������ƣ������ж��Ƿ��Զ�����DOM�ṹ��</p>
 * <p><pre><code>
if($.woo.global.autoParse){
  //�������Զ�����DOM�ṹ
};</code></pre></p>
 * ���WooUI��ȫ�����ö��󣬿����������������������������£�
 * <p><pre><code>
$.extend($.woo.global,{
  '������':'����ֵ'
});
 * </code></pre></p>
 * @singleton
 */
	$.woo.global = {
		/**
		 * �Զ�����html,���{@link woo#parse}
		 * @type Boolean
		 */
		autoParse : true,

		/**
		 * WooUI�������ÿ�������ʼ��ʱ�����������ѹ���������
		 * @type Array
		 */
		components : []

	};

/**
 * @class woo
 * WooUI�Ĺ����࣬�������ʵ���˺ܶ�ʵ�õĹ��ߣ��������������жϡ�����봦��url����������������ݸ�ʽ���ȹ��ߡ�
 * <p>ʹ�÷�����$.woo.���߷�����������������һ������UUID�ķ���{@link #createUUID}��</p>
 * <p><pre><code>
$.woo.createUUID();//����������һ�� 'version 4' RFC-4122 UUID �ַ���
</code></pre></p>
	 * @singleton
	 */


	$.extend($.woo,{
		/**
		 * ��ָ���������н���html�ṹ���������ĳ��DOM�ڵ���css��ʽ�� 'wooui-'+����� ����û������autoParse='false'��
		 * �����������Ӧ�����ȥ��Ⱦ
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
		 * �÷�����������;��һ���棬��������DOMԪ��û��id������һ��id�����û�д���DOMԪ�أ��򷵻�һ�������ɵ�id��
		 * ������û�д���DOMԪ�أ����᷵��һ��ҳ����Ψһ��idֵ��
		 * @param {Mixed} el DOMԪ��
		 * @param {String} prefix idֵ��ǰ׺
		 * @return String ���ظ���idֵ
		 */
		uid : function(el,prefix){
			prefix = prefix || 'wooui-';
			el = $(el).get(0);
			var id = prefix + (++idIndex);
			return el ? (el.id ? el.id : (el.id = id)) : id;
		},

		/**
		 *  ������Ĳ�����һ��javascript���飬�򷵻�true�����򷵻�false
		 *
		 * @param {Object}
		 * @return {Boolean}
		 */
		isArray : function(v) {
			return v && typeof v.length == 'number'
					&& typeof v.splice == 'function';
		},

		/**
		 *  ������Ĳ�����һ��javascript���ڶ����򷵻�true�����򷵻�false
		 *
		 * @param {Date}
		 * @return {Boolean}
		 */
		isDate : function(v){
			return v && typeof v.getFullYear == 'function';
		},

		/**
         *  ������Ĳ�����һ��javascript�����򷵻�true�����򷵻�false
         * @param {Object} object
         * @return {Boolean}
         */
        isObject : function(v){
            return v && typeof v == 'object';
        },

        /**
         *  ������Ĳ�����һ��javascript�����������ͣ�����String/Number/Boolean�����򷵻�true�����򷵻�false
         * @param {Mixed} value
         * @return {Boolean}
         */
        isPrimitive : function(v){
            return $.woo.isString(v) || $.woo.isNumber(v) || $.woo.isBoolean(v);
        },

		/**
		 *  ������Ĳ�����һ��javascript�������򷵻�true�����򷵻�false
		 * @param {Object} fn
		 * @return {Boolean}
		 */
		isFunction : function(fn) {
			return !!fn && typeof fn != 'string' && !fn.nodeName
					&& fn.constructor != Array
					&& /^[\s[]?function/.test(fn + '');
		},

		/**
         *  ������Ĳ�����һ��javascript���֣��򷵻�true�����򷵻�false
         * @param {Object} v
         * @return {Boolean}
         */
        isNumber: function(v){
            return typeof v === 'number' && isFinite(v);
        },

        /**
         *  ������Ĳ�����һ��javascript�ַ������򷵻�true�����򷵻�false
         * @param {Object} v
         * @return {Boolean}
         */
        isString: function(v){
            return typeof v === 'string';
        },

        /**
         *  ������Ĳ�����һ��javascript ����ֵ���򷵻�true�����򷵻�false
         * @param {Object} v The object to test
         * @return {Boolean}
         */
        isBoolean: function(v){
            return typeof v === 'boolean';
        },

        /**
         *  ������Ĳ�������һ��javascript undefined���򷵻�true�����򷵻�false
         * @param {Object} v The object to test
         * @return {Boolean}
         */
        isDefined: function(v){
            return typeof v !== 'undefined';
        },

        /**
         *  ������Ĳ�������һ��javascript undefined,null���߿յ��ַ������򷵻�true�����򷵻�false
         * @param {Mixed} value
         * @param {Boolean} allowBlank (��ѡ��) �Ƿ�ѿ��ַ���������
         * @return {Boolean}
         */
        isEmpty : function(v, allowBlank){
            return v === null || v === undefined || (!allowBlank ? v === '' : false);
        },

		/**
		 *  ���ش������������ ���Ͱ���:
		 * <ul>
		 * <li><b>string</b>: ������һ���ַ���</li>
		 * <li><b>number</b>: ������һ���ַ���</li>
		 * <li><b>boolean</b>: ������һ������ֵ</li>
		 * <li><b>function</b>: ������һ����������</li>
		 * <li><b>object</b>: ������һ������</li>
		 * <li><b>array</b>: ������һ������</li>
		 * <li><b>regexp</b>: ������һ��������ʽ����</li>
		 * <li><b>undefined</b>: ������undefined</li>
		 * <li><b>null</b>: ������һ��nullֵ</li>
		 * <li><b>element</b>: ������һ��DOMԪ��</li>
		 * <li><b>nodelist</b>: ������һ��DOMԪ���б�</li>
		 * <li><b>textnode</b>: ����һ���ǿ��ı��ڵ�</li>
		 * <li><b>whitespace</b>: ������һ���հ��ı��ڵ�</li>
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
         *  ��һ������ת��Ϊһ��url�Ĳ����ַ�����
         * @param {Object} o
         * @param {String} pre ǰ׺
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
         *  ��һ��url�Ĳ����ַ���ת��Ϊһ������
         * @param {String} string
         * @param {Boolean} overwrite (��ѡ��) ͬ��������һ��������档Ĭ��Ϊfalse��
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
         *  ��һ��url����׷��һ����ѯ�ַ���
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
         *  �������鲢��ÿһ��������ִ��һ������
         * @param {Array} array Ҫ����������
         * @param {Function} fn �����ص��������ص�����ͨ��������������ã�
         * <ul>
         * <li>item {Mixed} : �����еĵ�index��item</li>
         * <li>index{Number} : ������item������</li>
         * <li>array {Array} : ������������</li>
         * </ul>
         * @param {Object} scope �ص�����ִ�е�������
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
         * ��һ���ɵ�����ֵ��������������length���ԣ�ת��Ϊһ�����������顣
         * <p>ע�⣬��Ҫ�����ַ�������ΪIE��֧��"abc"[0]������д�������Ҫ���ַ���ת��Ϊ���飬����ʹ��"abc".match(/./g) ===> [a,b,c]</p>
         * @param {Iterable} Ҫת��Ϊ����Ŀɵ�������
         * @return (Array) ����
         */
        toArray : function(){
        	//TODO !$.support.htmlSerialize�����ж���ie�����������жϲ������Ժ�Ҫ��
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
         *  ����һ����������һ����������ԣ���ִ��һ��������
         * <b>Note</b>: ���ȷ��Ҫ����һ�����飬���ʹ��each������
         * @param {Object/Array} object
         * @param {Function} fn
         * �����������false�����������ֹͣ��
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
         *  ��֤һ��ֵ�Ƿ����֣�������ǣ��򷵻�һ��Ĭ��ֵ��
         * @param {Mixed} value Ӧ����һ�����֣������κ����Ͷ����Դ���
         * @param {Number} defaultValue ԭʼֵ����������֣��򷵻����Ĭ��ֵ
         * @return {Number} ���� Value �� defaultValue
         */
        num : function(v, defaultValue){
            v = Number(v === null || typeof v == 'boolean'? NaN : v);
            return isNaN(v)? defaultValue : v;
        },

        /**
         * ����ֵ�������ֵʱ�����Ϊ��ֵ���򷵻�Ĭ��ֵ��
         */
        value : function(v, defaultValue, allowBlank){
            return $.woo.isEmpty(v, allowBlank) ? defaultValue : v;
        },

        /**
         * ����domԪ�ص�͸����
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
         *  ��һ��������ʽ�е��Ѷ������ת��
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
         * ��ȡָ���ַ���unicode���룬����ո��unicode����Ϊ &#160;
         * @param {String} c
         * @return {String} unicode����
         */
        getUnicode : function(c){
        	return '&#' + c.charCodeAt() + ';';
        },

        /**
         * �����ַ����е������ַ�[<>&"]תΪunicode����
         * @param {String} str
         * @param {String}
         */
        tran2unicode : function(str){
        	return String(str).replace(/[<>&"]/g,$.woo.getUnicode);
        },
        /**
         *  ����һ�����鱸�ݣ����ҽ����еĿ�ֵ������������µ����鷵�ء��÷�������ı�ԭʼ���顣
         * @param {Array/NodeList} arr ��Ҫ���������
         * @return {Array} �µģ�ȥ����ֵ������
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
         *  ����һ������ı��ݣ����ҽ��ظ���ֵ���˵����÷�������ı�ԭʼ���顣
         * @param {Array} arr ��Ҫ���˵�ֵ
         * @return {Array} ���˺�Ĳ������ظ�ֵ������
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
         *  ��һ����ά����ת��Ϊһ��һά���飬�����ء��÷�������ı�ԭʼ���顣
         * @param {Array} arr ��ά����
         * @return {Array} �µ�
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
         *  ���������е���Сֵ
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
         *  ���������е����ֵ
         * @param {Array|NodeList} arr
         * @param {Function} comp (��ѡ��) �Զ���ıȽ�����Ĭ��Ϊ ��>���Ƚϡ�
         *
         * @return {Object} �����е����ֵ
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
         *  ���������е�ƽ��ֵ
         * @param {Array} arr
         * @return {Number}
         */
        mean : function(arr){
           return $.woo.sum(arr) / arr.length;
        },

        /**
         *  ������������������ܺ�
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
         * ��һ�����ϰ���true/false��ֵ���ͽ������֣��õ���������
         * <pre><code>
// ���� 1:
TOONE.partition([true, false, true, true, false]); // [[true, true, true], [false, false]]

// ���� 2:
TOONE.partition(
    TOONE.query('p'),
    function(val){
        return val.className == 'class1'
    }
);
// ���pԪ�ص�css��ʽ��Ϊ 'class1'��Ϊtrue��
// ���pԪ��û������Ϊ'class1'��css��ʽ�࣬��Ϊfalse
         * </code></pre>
         * @param {Array|NodeList} arr ��Ҫ���ֵļ��ϡ�
         * @param {Function} truth ����ѡ�ģ� һ������ȷ�� true or false �ĺ��������ʡ�Ըú������򼯺��е�ֵ���������Խ���true/false�жϡ�
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
         * �������ÿһ���ϵ���ͬһ��������
         * <pre><code>
// ���ӣ�
TOONE.invoke(TOONE.query('p'), 'getAttribute', 'id');
// [el1.getAttribute('id'), el2.getAttribute('id'), ..., elN.getAttribute('id')]
         * </code></pre>
         * @param {Array|NodeList} arr ��Ҫִ�з���������
         * @param {String} methodName  ִ�еķ�����
         * @param {Anything} ... ����������ʱ���ݵĲ���
         * @return {Array} ���յķ������ú�õ��Ľ����
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
         * ��ȡÿһ���������ĳ������ֵ������µ����飬�����ء�
         * <pre><code>
// ���ӣ�
TOONE.pluck(TOONE.query('p'), 'className'); // [el1.className, el2.className, ..., elN.className]
         * </code></pre>
         * @param {Array|NodeList} arr ��Ҫ��ȡֵ�Ķ�����ɵ�����
         * @param {String} prop ������
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
         *  ����������һ�� 'version 4' RFC-4122 UUID �ַ���
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
         *  �����ְ��ո����ĸ�ʽ���и�ʽ������ʽ��ģ��ƥ��/^(#*,)?(#*(#|0))\.(#*)$/���������¸�ʽ��
         *
         * <li>##.##</li> //�����ֵΪ�㣬����ʾ������һ���ո��unicode���� ��&#160;��
		 * <li>##.#####</li>
		 * <li>##</li>ֻ��ʾ�������������
         * <li>#0.##</li> //���ģ�����˼�ǣ������ֵΪ�㣬��ǿ����ʾ��������תΪ��
         * <li>###,###.##</li>
         * <li>###,###</li>�ֶε�����
         * <li>###,##0.##</li>
         *
         * <pre><code>
			// ���ӣ�
			$.woo.numberFormat(2134.5685,'##.##'); // 2134.57
			$.woo.numberFormat(2134.5685,'##.###'); // 2134.569
			$.woo.numberFormat(0.00000,'##.##'); // &#160;
			$.woo.numberFormat('000.00000','##.##'); // &#160;
			$.woo.numberFormat(0.00000,'#0.##'); // 0.00
			$.woo.numberFormat(2342134.5685,'###,###.###'); // 2,342,134.57
         * </code></pre>
         *
         * @param {Number/String} value ��Ҫ��ʽ����ֵ
         * @param {String} pattern ��ʽ��ģ��
         * @return {Number} ��ʽ�����ֵ
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
		 * ��һ�����ָ�ʽ��Ϊ����Ҹ�ʽ
		 *
		 * @param {Number/String}
		 *            v ��Ҫ��ʽ��������
		 * @param {Boolean}
		 *            cap �Ƿ��ʽ��Ϊ��д
		 * @return {String} ���Ҹ�ʽ���ַ���
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
			return "&#0165;" + v;//����ҷ��ŵ�unicode����Ϊ&#0165;
		},

		/**
		 * ��ȡһ�����ַ����ĵ�N-3���ַ��Ժ�����ݴ���Ϊָ�������ݻ���'...'�����磺
		 * &.woo.ellipsis('ͬ���Ƽ�ǰ�˿�������һȺ���������������',10)����'ͬ���Ƽ�ǰ�˿�������...'
		 *
		 * @param {String}
		 *            value ��Ҫ��ȡ���ַ���
		 * @param {Number}
		 *            len ָ����ʣ���ַ�������
		 * @param {String}
		 *            str ָ�������ݣ��ò�����ѡ��Ĭ��Ϊ'...'
		 * @return {String} ��ȡ�滻����Ժ���ַ���
		 */
		ellipsis : function(value, len, str) {
			if (value && value.length > len) {
				return value.substr(0, len - 3) + (str ? str : "...");
			}
			return value;
		},
		/**
		 * ����һ��ָ����ֵ�����ַ�����������һ������
		 *
		 * @param {String}
		 *            value ԭʼ������
		 * @param {Number}
		 *            start �Ӵ���ʼ��λ��
		 * @param {Number}
		 *            length �Ӵ��ĳ���
		 * @return {String} ���ַ���
		 */
		slice : function(value, start, length) {
			return String(value).slice(start, length);
		},

		/**
		 * ����һ��ָ����ֵ�������ַ�ת��ΪСд�ַ���������һ������
		 *
		 * @param {String}
		 *            value ��Ҫת������ֵ
		 * @return {String} ת������ַ���
		 */
		lowercase : function(value) {
			return String(value).toLowerCase();
		},

		/**
		 * ����һ��ָ����ֵ�������ַ�ת��Ϊ��д�ַ���������һ������
		 *
		 * @param {String}
		 *            value ��Ҫת������ֵ
		 * @return {String} ת������ַ���
		 */
		uppercase : function(value) {
			return String(value).toUpperCase();
		},

		/**
		 * ��һ���ַ���������ĸ��Ϊ��д,�����ַ���ȫ����ΪСд
		 *
		 * @param {String}
		 *            value ��Ҫת����ַ���
		 * @return {String} ת�����ַ���
		 */
		cap : function(value) {
			return !value ? value : value.charAt(0).toUpperCase()
					+ value.substr(1).toLowerCase();
		},
		/**
		 * ����һ��ָ���ַ��������ַ�����ʹ�÷�����
		 * <p><ul>
		 * <li>��I love bicycle�н�ȡlove��&.woo.substr('I love bicycle',3,4);</li>
		 * <li>������1234567����Ҫ��ȡ234��&.woo.substr(1234567,2,3);</li>
		 * </ul></p>
		 * @param {mixed}
		 *            value ԭʼ������
		 * @param {Number}
		 *            start �Ӵ���ʼ��λ��
		 * @param {Number}
		 *            length �Ӵ��ĳ���
		 * @return {String} ���ַ���
		 */
		substr : function(value, start, length) {
			return String(value).substr(start, length);
		},

		/**
		 * ��ʽ���ļ��ߴ� (xxx bytes, xxx KB, xxx MB)
		 *
		 * @param {Number/String}
		 *            �ļ�����
		 * @return {String} ��ʽ������ַ���
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
		 * @description ��¡һ��������������{Object/Array}��ֵ�������Ҫ��ȿ�¡����ڶ�����������true
		 * @param {Object/Array} value ��Ҫ��¡��ԭʼֵ
		 * @param {Boolean} deep �Ƿ���ȿ�¡��Ĭ��Ϊfalse
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

				// Ԥ������ѭ��
				if ( ret === copy )
					continue;

				// �������ȿ�������ݹ鸴��
				if ( deep && copy && ($.woo.isArray(copy) || $.woo.isObject(copy)) )
					ret[ name ] = $.woo.clone(copy, deep);

				// �ų��� undefined ֵ
				else if ( copy !== undefined )
					ret[ name ] = copy;
			}

			return ret;
		},

		/**
		 * ��ȡָ��Ŀ���ĳ����ʽ��ֵ�����ֵ��Ϊ���֣�����auto֮��ģ��򷵻�0
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
	 * �жϽڵ�a�Ƿ�����ڵ�b ������compareDocumentPosition��contains �÷��� http://www.cnblogs.com/siceblue/archive/2010/02/02/1661833.html��
	 *
	 * @param {HTMLElement} a DOM�ڵ�
	 * @param {HTMLElement} b DOM�ڵ�
	 * @return {Boolean}
	 */
	contains: function(a, b) {
		return document.compareDocumentPosition
			? a.compareDocumentPosition(b) & 16//��16��λ�ڵ�ԭ���ǣ�������� ֵΪ20 ������true; ���������ֵΪ 0 ������false
			: a !== b && a.contains(b);
	},

	//�ж�Ԫ�ص�sroll ��top����left�������Ƿ��й���
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

    //ȷ��x�����Ƿ���Ԫ���ڲ�
    //x��Ҫȷ�ϵ����ꣻreference���ο����ꣻsize��Ԫ�ؿ��
	isOverAxis: function(x, reference, size) {
		//Determines when x coordinate is over "b" element axis
		return (x > reference) && (x < (reference + size));
	},
	//ȷ��x��y�����Ƿ�ͬ����Ԫ���ڲ�
  	//x��y�����ꣻtop��left��Ԫ�����ꣻheight��width��Ԫ�ؿ��
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
	 * ��̬������ʽ�ļ�
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
	 * ��̬����js
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
	 * ͨ��ֵ��ȡ�����ֵ�����
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
 * json֧�֡��ַ���תjavascript����javascript����ת�ַ���
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


	//jQuery ���
	$.fn.extend({
		_focus: $.fn.focus,
		//����Ԫ�ؽ��㣨delay���ӳ�ʱ�䣩�ɱ༭�����ֶ�ջ�������ʱȥ��
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
		//����Ԫ��֧�ֱ�ѡ��
		enableSelection: function() {
			return this
				.attr('unselectable', 'off')
				.css('MozUserSelect', '')
				.unbind('selectstart.ui');
		},
		 //����Ԫ�ز�֧�ֱ�ѡ��
		disableSelection: function() {
			return this
				.attr('unselectable', 'on')
				.css('MozUserSelect', 'none')
				.bind('selectstart.ui', function() { return false; });
		},

		//��ȡ���ù������Ե� ��Ԫ��
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
	 	//���û��ȡԪ�صĴ�ֱ����
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

                // ����
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
                // ����һ�� Mask �㣬׷�ӵ�������
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
                    // ���뵭��Ч��
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
 * ��չѡ��������Ĺ���
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
	 * match:һ��ƥ������
	 * match[0]:data(key)
	 * match[1]:data
	 * match[2]:δ֪
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
 * @class String ��ԭ����JavaScript�ַ���ԭ�ͽ��й�����չ����չ�Ĺ��ܿ���������һ���ַ���
 */
$.extend(String.prototype, {
	/**
			 * �������ַ����е� ' �� \ �������ַ�����ת��
			 *
			 * @param {String}
			 *            string Ҫת����ַ���
			 * @return {String} ����ת����ַ���
			 * @static
			 */
			escape : function(string) {
				return string.replace(/('|\\)/g, "\\$1");
			},

			/**
			 * ���ַ�������߲���ָ�����ַ� ʹ�����ӣ�
			 *
			 * <pre><code>
			 * var s = String.leftPad('123', 5, '0');// '00123'
			 * </code></pre>
			 *
			 * @param {String}
			 *            string ԭʼ�ַ���
			 * @param {Number}
			 *            size �Զ��ַ��ظ��Ĵ���
			 * @param {String}
			 *            char ����ѡ�ģ�ָ�����ַ���Ĭ��Ϊ���ַ����� ��
			 * @return {String} ����ָ���ַ�������ַ���
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
			 * �ַ�����ʽ�������ַ����еĴ��� {0}, {1}...�滻Ϊ��Ӧ�����б�λ���е�ֵ ʹ�����ӣ�
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
			 *            value1 �����������{0}��ֵ
			 * @param {String}
			 *            value2 ������
			 * @return {String} ��ʽ�������ַ���
			 * @static
			 */
			format : function(format) {
				var args = Array.prototype.slice.call(arguments, 1);
				return format.replace(/\{(\d+)\}/g, function(m, i) {
							return args[i];
						});
			},
			/**
			 * ���ַ���������ֵ֮���л�������ַ����ʹ���ĵ�һ��ֵ��ȣ��򷵻���һ��ֵ�����ȣ��򷵻ص�һ��ֵ����ǰ�ַ�����û�б��ı�
			 *
			 * <pre><code>
			 * // �����л�������
			 * sort = sort.toggle('ASC', 'DESC');
			 *
			 * // ����������������߼�
			 * sort = (sort == 'ASC' ? 'DESC' : 'ASC');
			 * </code></pre>
			 *
			 * @param {String}
			 *            value �����͵�ǰ�ַ����Ƚϵ�ֵ
			 * @param {String}
			 *            other ��һ��ֵ
			 * @return {String} �����߼���ֵ
			 */
			toggle : function(value, other) {
				return this == value ? other : value;
			},

			/**
			 * �ص���ǰ�ַ�����ͷ�Ŀհס� ʹ������:
			 *
			 * <pre><code>
			 * var s = '  foo bar  ';
			 * alert('-' + s + '-'); //alerts &quot;- foo bar -&quot;
			 * alert('-' + s.trim() + '-'); //alerts &quot;-foo bar-&quot;
			 * </code></pre>
			 *
			 * @return {String} ��ȡ�����ַ���
			 */
			trim : function() {
				var re = /^\s+|\s+$/g;
				return this.replace(re, "");
			}
});

/**
 * @class Number ��ԭ����JavaScript����ԭ�ͽ��й�����չ����չ�Ĺ��ܿ���������һ������
 */
$.extend(Array.prototype, {
			/**
			 * ����ֵ�����ڸ�����[min,max]֮�䣬����ڸ��������䣬�򷵻��������С��min���򷵻�min���������max���򷵻�max
			 *
			 * @param {Number}
			 *            min �������Сֵ
			 * @param {Number}
			 *            max ��������ֵ
			 * @return {Number} �����ڵ�ֵ
			 */
			constrain : function(min, max) {
				return Math.min(Math.max(this, min), max);
			}
		});

/**
 * @class Array ��ԭ����JavaScript����ԭ�ͽ��й�����չ����չ�Ĺ��ܿ���������һ������
 */
$.extend(Array.prototype, {
            /**
			 * ����ĳ��Ԫ����������ƥ��ĵ�һ��λ�ã�����Ҳ������򷵻�-1����Ԫ����һ�������������ͣ�������Ǿ���һ�£�===�������ҵ���
			 *
			 * @param {Mixed}
			 *            elt Ҫ���ҵ�Ԫ��
			 * @param {Number}
			 *            from ��ʼ����λ�ã�Ĭ��Ϊ0
			 * @return {Number} Ԫ���������е�λ�ã�����Ҳ������򷵻�-1
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
			 * ����ĳ��Ԫ����������ƥ������һ��λ�ã�����Ҳ������򷵻�-1����Ԫ����һ�������������ͣ�������Ǿ���һ�£�===�������ҵ���
			 *
			 * @param {Mixed}
			 *            elt Ҫ���ҵ�Ԫ��
			 * @param {Number}
			 *            from ��ʼ����λ�ã�Ĭ��Ϊ0
			 * @return {Number} Ԫ���������е�λ�ã�����Ҳ������򷵻�-1
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
			 * ��֤������������飬��ÿһ���������<b>�ȽϺ���</b>ִ�У������һ����������ʹ�ñȽϺ�������true����ú�������true��ע���{@link #every}����������
			 *
			 * @param {Function}
			 *            fn �ȽϺ���������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope �ȽϺ���ִ�е�������
			 * @return {Boolean} �з��ϱȽϺ����ıȽϹ�����������򷵻�true�����򷵻�false
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
			 * ӳ��ת��������������飬��ÿһ��������ͨ��<b>ӳ�亯��</b>����ӳ��ת������������鷵�ء�ԭ���鲻��ı䡣
			 *
			 * @param {Function}
			 *            fn ӳ�亯��������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope ӳ�亯��ִ�е�������
			 * @return {Array} ����ӳ�亯��ת����������
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
			 * �������飬������ͨ��<b>���˺���</b>���й��ˣ���������������������µ����鷵�ء�ԭ���鲻��ı䡣
			 *
			 * @param {Function}
			 *            fn ���˺��������Ϲ��������򷵻�true������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope ���˺���ִ�е�������
			 * @return {Array} �������˵õ���������
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
			 * ��֤������������飬���ÿһ�����������<b>�ȽϺ���</b>��Ҫ�󣨼��ȽϺ�������true������ú�������true��ע���{@link #some}����������
			 *
			 * @param {Function}
			 *            fn �ȽϺ���������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope �ȽϺ���ִ�е�������
			 * @return {Boolean} �����е�ÿһ����������ϱȽϺ����ıȽϹ����򷵻�true�����򷵻�false
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
			 * �������飬��ÿһ��������������������λ����Ϊ��������<b>�ص�����</b>ִ�С�
			 * @param {Function}
			 *            fn �ص�����������Ϊitem���������itemIndex�������������λ�ã�
			 * @param {Object}
			 *            scope �ص�����ִ�е�������
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
			 * ��������ɾ��ָ����������
			 * @param {Object}
			 *            o Ҫɾ����������
			 * @return {Array} ���鱾��
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

//�Զ����ʾ����ʾ������
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