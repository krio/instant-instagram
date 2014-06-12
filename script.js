(function($) {
    $('a.insta_rio_link').click(function(e) {
        e.preventDefault();
        var win = window.open(this.href, 'targetWindow',
            'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height='+screen.height);
        win.focus();
        return false;
    });
})(jQuery);