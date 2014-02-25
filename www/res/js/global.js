/**
 +----------------------------------------------------------
 * 下拉菜单
 +----------------------------------------------------------
 */
$(document).ready(function() {
    $('#mainNav li').hover(function() {
        $(this).children('dl').show()
    },
    function() {
        $(this).children('dl').hide()
    })
});

/**
 +----------------------------------------------------------
 * 收藏本站
 +----------------------------------------------------------
 */
function AddFavorite(sURL, sTitle) {
    try {
        window.external.addFavorite(sURL, sTitle)
    } catch(e) {
        try {
            window.sidebar.addPanel(sTitle, sURL, "")
        } catch(e) {
            alert("加入收藏失败，请使用Ctrl+D进行添加")
        }
    }
}

/**
 +----------------------------------------------------------
 * 在线客服
 +----------------------------------------------------------
 */
$(document).ready(function(e) {
    $("#onlineService").css("right", "0px");
    var button_toggle = true;
    $(".service").live("mouseover",
    function() {
        button_toggle = false;
        $("#pop").show();
    }).live("mouseout",
    function() {
        button_toggle = true;
        hideRightTip()
    });
    $("#pop").live("mouseover",
    function() {
        button_toggle = false;
        $(this).show()
    }).live("mouseout",
    function() {
        button_toggle = true;
        hideRightTip()
    });
    function hideRightTip() {
        setTimeout(function() {
            if (button_toggle) $("#pop").hide()
        },
        500)
    }
    $(".goTop").live("click",
    function() {
        var _this = $(this);
        $('html,body').animate({
            scrollTop: 0
        },
        500,
        function() {
            _this.hide()
        })
    });
    $(window).scroll(function() {
        var htmlTop = $(document).scrollTop();
        if (htmlTop > 0) {
            $(".goTop").show()
        } else {
            $(".goTop").hide()
        }
    })
});