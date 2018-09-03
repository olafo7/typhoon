var jxcGoodsScroll;  
var jxcPullDownEl, jxcPullDownL;  
var jxcPullUpEl, jxcPullUpL;  
var jxcLoadingStep = 0; //加载状态0默认，1显示加载状态，2执行加载数据，只有当为0时才能再次加载，这是防止过快拉动刷新  

function pullDownActionJxc() { //下拉刷新事件  
    setTimeout(function() {  
        if(isExitsFunction('myFns.pullDownRefreshJxc')){
            myFns.pullDownRefreshJxc();//调用全局函数刷新
            jxcPullDownEl.removeClass('loading');  
            jxcPullDownL.html('下拉显示更多...');  
            jxcPullDownEl['class'] = jxcPullDownEl.attr('class');  
            jxcPullDownEl.attr('class','').hide();  
            jxcGoodsScroll.refresh();  
            jxcLoadingStep = 0;  
        }else{
            return false;
        }
    }, 500); //模拟网络堵塞1秒  
}  

function pullUpActionJxc() { //上拉加载更多事件  
    setTimeout(function() {  
        //加载下一页内容和刷新
        if(isExitsFunction('myFns.pullUpActionJxc')){ //先判断此函数是否存在
            myFns.pullUpActionJxc();
            jxcPullUpEl.removeClass('loading');  
            jxcPullUpL.html('上拉显示更多...');  
            jxcPullUpEl['class'] = jxcPullUpEl.attr('class');  
            jxcPullUpEl.attr('class','').hide();  
            jxcGoodsScroll.refresh();  
            jxcLoadingStep = 0;  
        }else{
            return false;
        }
    }, 500); //模拟网络堵塞一秒
}  

function jxcGoodsLoaded() {  
    jxcPullDownEl = $('#jxcPullDown');  
    jxcPullDownL = jxcPullDownEl.find('.jxcPullDownLabel');  
    jxcPullDownEl['class'] = jxcPullDownEl.attr('class');  
    jxcPullDownEl.attr('class','').hide();  
      
    jxcPullUpEl = $('#jxcPullUp');  
    jxcPullUpL = jxcPullUpEl.find('.jxcPullUpLabel');  
    jxcPullUpEl['class'] = jxcPullUpEl.attr('class');  
    jxcPullUpEl.attr('class','').hide();  
      
    jxcGoodsScroll = new IScroll('#jxcGoods-wrapper', {  
        probeType: 2, //probeType：1对性能没有影响。在滚动事件被触发时，滚动轴是不是忙着做它的东西。probeType：2总执行滚动，除了势头，反弹过程中的事件。这类似于原生的onscroll事件。probeType：3发出的滚动事件与到的像素精度。注意，滚动被迫requestAnimationFrame（即：useTransition：假）。  
        useTransition: false,
        scrollbars: false, //禁止滚动条  
        scrollX: true, //允许X轴滚动
        scrollY: true, //允许Y轴滚动
        click: false , //允许点击事件  
        preventDefault: false, //阻止默认事件
        preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/ } //这个后面加|A,因为iscroll阻止了A的默认事件
    });  
    //滚动时  
    jxcGoodsScroll.on('scroll', function(){  
        if(jxcLoadingStep == 0 && !jxcPullDownEl.attr('class').match('flip|loading') && !jxcPullUpEl.attr('class').match('flip|loading')){  
            if (this.y > 80) {  
                //下拉刷新效果  
                jxcPullDownEl.attr('class',jxcPullUpEl['class'])  
                jxcPullDownEl.show();  
                jxcGoodsScroll.refresh();  
                jxcPullDownEl.addClass('flip');  
                jxcPullDownL.html('松手开始刷新...');  
                jxcLoadingStep = 1;  
            }else if (this.y < (this.maxScrollY - 80)) {  
                //上拉刷新效果  
                jxcPullUpEl.attr('class',jxcPullUpEl['class'])  
                jxcPullUpEl.show();  
                jxcGoodsScroll.refresh();  
                jxcPullUpEl.addClass('flip');  
                jxcPullUpL.html('松手开始更新...');  
                jxcLoadingStep = 1;  
            }  
        }  
    });  
    //滚动完毕  
    jxcGoodsScroll.on('scrollEnd',function(){  
        if(jxcLoadingStep == 1){  
            if (jxcPullUpEl.attr('class').match('flip|loading')) {  
                    jxcPullUpEl.removeClass('flip').addClass('loading');  
                jxcPullUpL.html('Loading...');  
                jxcLoadingStep = 2;  
                pullUpActionJxc();  
            }else if(jxcPullDownEl.attr('class').match('flip|loading')){  
                jxcPullDownEl.removeClass('flip').addClass('loading');  
                jxcPullDownL.html('Loading...');  
                jxcLoadingStep = 2;  
                pullDownActionJxc();  
            }  
        }  
    });  
}  
document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);  
