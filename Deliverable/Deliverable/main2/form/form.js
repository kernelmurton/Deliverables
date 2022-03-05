var change_scale = {
    "container": 1920,
    "percent": 1,
    "function": function() {
        "use strict";
        if (change_scale.percent === window.devicePixelRatio) {
            var scale = document.documentElement.clientWidth;
            scale = scale / change_scale.container;
            scale = "scale(" + scale + ")";
            document.body.style.transform = scale;
        } else {
            change_scale.percent = window.devicePixelRatio;
        }
    }
};
(function() {
    "use strict";
    if (!navigator.userAgent.match(/(iPhone|iPad|iPod|Android)/i)) {
        if (window.devicePixelRatio !== 1) {
            alert("申し訳ございません。スタイルが崩れている可能性があります。恐れ入りますが、倍率を100%に変更後、リロードしてください。")
        }
    }
    change_scale.function();
}());
window.addEventListener("resize", function(event) {
    "use strict";
    change_scale.function();
});