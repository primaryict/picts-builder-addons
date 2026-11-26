(function ($) {
    const toggle = '.module-submenu .picts_sub_page_list button.dropDown';

    $(document).on('click', toggle, function () {
        const $children = $(this).next('.children, .sub-menu');
        if (!$children.length) {
            return;
        }

        const expanded = this.getAttribute('aria-expanded') !== 'true';
        this.setAttribute('aria-expanded', String(expanded));
        const duration = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 'fast';
        $children.stop(true, true)[expanded ? 'slideDown' : 'slideUp'](duration);
    });

    document.documentElement.classList.add('picts-submenus-enabled');
})(jQuery);
