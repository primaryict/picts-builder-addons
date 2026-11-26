((api, g) => {
    'use strict';
    api.ModuleAddfile = class extends api.Module {

        static getOptions() {
            return [
                {
                    id: "content_addfile",
                    type: "builder",
                    options: [
                        { id: "label", type: "text", label: "text", control: { selector: '[data-name="label"]' } },
                        { id: "link", type: "file", label: "l", binding: { empty: { hide: ["link_options", "button_color_bg", "title"] }, not_empty: { show: ["link_options", "button_color_bg", "title"] } } },
                        { id: "link_options", type: "radio", label: "o_l", link_type: !0, option_js: !0 },
                        {
                            type: "multi",
                            label: "lbdim",
                            options: [
                                { id: "lightbox_width", type: "range", label: "w", control: !1, units: { px: { max: 3e3 }, "%": "" } },
                                { id: "lightbox_height", label: "ht", control: !1, type: "range", units: { px: { max: 3e3 }, "%": "" } },
                            ],
                            wrap_class: "tb_group_element_lightbox lightbox_size",
                        },
                        { id: "button_color_bg", type: "layout", label: "c", class: "tb_colors", mode: "sprite", color: !0, transparent: !0 },
                        {
                            type: "radio",
                            id: "t",
                            label: "icon",
                            options: [
                                { value: "i", name: "icon" },
                                { value: "l", name: "lt" },
                            ],
                            option_js: !0,
                        },
                        { id: "icon", type: "icon", label: "icon", wrap_class: "tb_group_element_i", class: "fullwidth", binding: { empty: { hide: "icon_alignment" }, not_empty: { show: "icon_alignment" } } },
                        { type: "lottie", wrap_class: "tb_group_element_l", binding: { empty: { hide: "icon_alignment" }, not_empty: { show: "icon_alignment" } } },
                        { id: "icon_alignment", type: "select", label: "ialign", options: { left: "left", right: "right" } },
                        { id: "title", type: "text", label: "tat" },
                        { id: "id", type: "text", label: "idat" },
                    ],
                },
                {
                    type: "group",
                    label: "btnapp",
                    display: "accordion",
                    options: [
                        {
                            id: "buttons_size",
                            label: "size",
                            type: "layout",
                            mode: "sprite",
                            options: [
                                { img: "normall_button", value: "normal", label: "def" },
                                { img: "small_button", value: "small", label: "sml" },
                                { img: "large_button", value: "large", label: "lrg" },
                                { img: "xlarge_button", value: "xlarge", label: "xlrg" },
                            ],
                            control: { classSelector: "" },
                        },
                        {
                            id: "buttons_shape",
                            type: "layout",
                            mode: "sprite",
                            label: "shape",
                            options: [
                                { img: "normall_button", value: "normal", label: "def" },
                                { img: "squared_button", value: "squared", label: "squared" },
                                { img: "circle_button", value: "circle", label: "circle" },
                                { img: "rounded_button", value: "rounded", label: "rounded" },
                            ],
                            control: { classSelector: "" },
                        },
                        {
                            id: "buttons_style",
                            type: "layout",
                            mode: "sprite",
                            label: "bg",
                            options: [
                                { img: "solid_button", value: "solid", label: "solid" },
                                { img: "outline_button", value: "outline", label: "o" },
                                { img: "transparent_button", value: "transparent", label: "transparent" },
                            ],
                            control: { classSelector: "" },
                        },
                        {
                            id: "display",
                            type: "layout",
                            mode: "sprite",
                            label: "disp",
                            options: [
                                { img: "horizontal_button", value: "buttons-horizontal", label: "hrztal" },
                                { img: "vertical_button", value: "buttons-vertical", label: "vertical" },
                            ],
                            control: { classSelector: "" },
                        },
                        { id: "fullwidth_button", type: "toggle_switch", label: "fw", options: { on: { name: "buttons-fullwidth" } }, binding: { checked: { hide: "display" }, not_checked: { show: "display" } } },
                        { id: "nofollow_link", type: "toggle_switch", label: "nfollow", options: { on: { name: "yes" } }, help: "nfollowh", control: !1 },
                        { id: "download_link", type: "toggle_switch", label: "dwnable", options: { on: { name: "yes" } }, help: "dwnablef", control: !1 },
                    ],
                },
                { type: "custom_css_id", custom_css: "css_button" },
            ];
        }
        static default() {
            return { content_addfile: [{ label: g.btntext, link: "#" }] };
        }
    };
})(tb_app, themifyBuilder.i18n.label);
