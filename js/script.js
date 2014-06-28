function instant_instagram_anchor_click(e) {
    var win = window.open(e.href, 'targetWindow',
        'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height='+screen.height);
    win.focus();
    return false;
}