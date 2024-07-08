function QrcPoiders() {
    var clipboardDemos = new ClipboardJS("[data-clipboard-demo]");
    clipboardDemos.on("success", function(e) {
        e.clearSelection();
        showTooltip(e.trigger, "Copied!");
    });
    clipboardDemos.on("error", function(e) {
        showTooltip(e.trigger, fallbackMessage(e.action));
    });
}
QrcPoiders();
var qrc_clibord_btn = document.querySelectorAll(".qrcclipbtns");
for (var i = 0; i < qrc_clibord_btn.length; i++) {
    qrc_clibord_btn[i].addEventListener("mouseleave", clearTooltip);
    qrc_clibord_btn[i].addEventListener("blur", clearTooltip);
}

function clearTooltip(e) {
    e.currentTarget.setAttribute("class", "qrcclipbtns");
    e.currentTarget.removeAttribute("aria-label");
}

function showTooltip(elem, msg) {
    elem.setAttribute("class", "qrcclipbtns tooltipped tooltipped-s");
    elem.setAttribute("aria-label", msg);
}

function fallbackMessage(action) {
    var actionMsg = "";
    var actionKey = action === "cut" ? "X" : "C";
    if (/iPhone|iPad/i.test(navigator.userAgent)) {
        actionMsg = "No support :(";
    } else if (/Mac/i.test(navigator.userAgent)) {
        actionMsg = "Press ⌘-" + actionKey + " to " + action;
    } else {
        actionMsg = "Press Ctrl-" + actionKey + " to " + action;
    }
    return actionMsg;
}