((api, themifyBuilder, g) => {
    'use strict';
    api.ModuleSubmenu = class extends api.Module {

        static getOptions() {
            return [
                {
                    id: 'menu_type',
                    type: 'radio',
                    label: 'Sub Menu Type',
                    options: [
                        {
                            value: 'block',
                            name: 'Block'
                        },
                        {
                            value: 'list',
                            name: 'List'
                        }
                    ],
                    option_js: !0,
                },
                {
                    id: 'block_menu_icon',
                    type: 'image',
                    label: 'Block Menu Icon'
                },
                { id: "custom_menu", type: "select", dataset: "menu", description: g.addmore + ' <a href="' + themifyBuilder.admin_url + '/nav-menus.php" target="_blank">' + g.lmenu + "</a>", label: "menuc", options: [] },
                { id: "allow_menu_fallback", label: "", type: "checkbox", options: [{ name: "allow_fallback", value: "listallfail" }] },
                { type: "custom_css_id", custom_css: "css_menu" },
            ];
        }

        static default() {
            return {};
        }
    };
})(tb_app, themifyBuilder, themifyBuilder.i18n.label);
