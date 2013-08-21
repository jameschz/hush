/**
 * @brief  扩展原生字符串 
 * @author hechangmin@gmail.com
 */

var StringEx = 
{ 
    /**
     * @brief 去除字符串前后空格
     */
    trim : function()
    {
        return this.replace(/(^\s*)|(\s*$)/g, "");
    },
    
    /**
     * @brief 去除左边空格
     */
    ltrim : function()
    { 
        return this.replace(/(^\s*)/g, ""); 
    },
    
    /**
     * @brief 去除右边空格
     */
    rtrim : function()
    { 
        return this.replace(/(\s*$)/g, "");      
    },
    
    /**
     * @brief 避免XSS 攻击
     */
    avoidXSS : function()
    {
        var strTemp = this.replace(/&/g, "&amp;");
        strTemp = strTemp.replace(/</g, "&lt;");
        strTemp = strTemp.replace(/>/g, "&gt;");
        strTemp = strTemp.replace(/\"/g, "&quot;");
        return strTemp;    
    } ,
    
    /**
     * @brief 获取字符串的字节长度 汉字默认双字节
     */
    byteLength : function()
    {
          return this.replace(/[^\x00-\xff]/g,"**").length;
    },
    
    /**
     * @brief     除去HTML标签
     * @example    <div id="test1">aaaa</div>  =>  aaaa 
     */
    removeHtml : function()
    {
        return this.replace(/<\/?[^>]+>/gi, '');
    },
    
    /**
     * @brief      格式化字符串
     * @example "<div>{0}</div>{1}".format(txt0,txt1)
     */
    format : function()
    {
        var args = [];
        
        for (var i = 0, il = arguments.length; i < il; i++)
        {
            args.push(arguments[i]);
        }
        
        return this.replace(/\{(\d+)\}/g, function(m, i)
        {
            return args[i];
        });
    },
    
    /**
     * @brief 字符串转数字
     */
    toInt : function() 
    {
        return Math.floor(this);
    },
    
    /**
     * @brief 字符串转数字
     */
    noCacheUrl : function() 
    {
        return this.replace(/\?_t=\d*/i, '?_t=' + (new Date()).getTime()).replace(/&_t=\d*/i, '&_t=' + (new Date()).getTime());
    }
}


for (var it in StringEx) 
{
    String.prototype[it] = StringEx[it];
}