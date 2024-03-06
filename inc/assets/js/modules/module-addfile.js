((api, createElement, themifyBuilder, g) => {
    'use strict';
    api.ModuleAddfile = class extends api.Module {

        constructor(fields) {
            super(fields);
        }

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
        static builderSave(settings) {
            super.builderSave(settings);
        }
    };
})(tb_app, tb_createElement, themifyBuilder, themifyBuilder.i18n.label);

/*
public function get_options() {

    return array(
        array(
            'id' => 'content_button',
            'type' => 'builder',
            'options' => array(
                array(
                    'id' => 'label',
                    'type' => 'text',
                    'label' => 'text',
                    'control' => array(
                        'selector' => '.module-buttons-item span'
                    )
                ),
                array(
                    'id' => 'link',
                    'type' => 'file',
                    'label' => 'l',
                    'binding' => array(
                        'empty' => array(
                            'hide' => array('link_options', 'button_color_bg', 'title')
                        ),
                        'not_empty' => array(
                            'show' => array('link_options', 'button_color_bg', 'title')
                        )
                    )
                ),
                array(
                    'id' => 'link_options',
                    'type' => 'radio',
                    'label' => 'o_l',
                    'link_type' => true,
                    'option_js' => true
                ),
                array(
                    'type' => 'multi',
                    'label' => __('Lightbox Dimension', 'themify'),
                    'options' => array(
                        array(
                            'id' => 'lightbox_width',
                            'type' => 'range',
                            'label' => 'w',
                            'control' => false,
                            'units' => array(
                                'px' => array(
                                    'max' => 3000
                                ),
                                '%' => ''
                            )
                        ),
                        array(
                            'id' => 'lightbox_height',
                            'label' => 'ht',
                            'control' => false,
                            'type' => 'range',
                            'units' => array(
                                'px' => array(
                                    'max' => 3000
                                ),
                                '%' => ''
                            )
                        )
                    ),
                    'wrap_class' => 'tb_group_element_lightbox lightbox_size'
                ),
                array(
                    'id' => 'button_color_bg',
                    'type' => 'layout',
                    'label' => 'c',
                    'class' => 'tb_colors',
                    'mode' => 'sprite',
                    'color' => true,
                    'transparent' => true
                ),
                array(
                    'type' => 'radio',
                    'id' => 't',
                    'label' => 'icon',
                    'options' => array(
                        array('value' => 'i', 'name' => 'icon'),
                        array('value' => 'l', 'name' => 'lt')
                    ),
                    'option_js' => true
                ),
                array(
                    'id' => 'icon',
                    'type' => 'icon',
                    'label' => 'icon',
                    'wrap_class' => 'tb_group_element_i',
                    'class' => 'fullwidth',
                    'binding' => array(
                        'empty' => array(
                            'hide' => 'icon_alignment'
                        ),
                        'not_empty' => array(
                            'show' => 'icon_alignment'
                        )
                    )
                ),
                array(
                    'type' => 'lottie',
                    'wrap_class' => 'tb_group_element_l',
                    'binding' => array(
                        'empty' => array(
                            'hide' => 'icon_alignment'
                        ),
                        'not_empty' => array(
                            'show' => 'icon_alignment'
                        ),
                    )
                ),
                array(
                    'id' => 'icon_alignment',
                    'type' => 'select',
                    'label' => 'ialign',
                    'options' => array(
                        'left' => 'left',
                        'right' => 'right'
                    )
                ),
                array(
                    'id' => 'title',
                    'type' => 'text',
                    'label' => 'tat'
                ),
                array(
                    'id' => 'id',
                    'type' => 'text',
                    'label' => 'idat'
                ),
            )
        ),
        array(
            'type' => 'group',
            'label' => __('Button Appearance', 'themify'),
            'display' => 'accordion',
            'options' => array(
                array(
                    'id' => 'buttons_size',
                    'label' => __('Size', 'themify'),
                    'type' => 'layout',
                    'mode' => 'sprite',
                    'options' => array(
                        array('img' => 'normall_button', 'value' => 'normal', 'label' => 'def'),
                        array('img' => 'small_button', 'value' => 'small', 'label' => 'sml'),
                        array('img' => 'large_button', 'value' => 'large', 'label' => 'lrg'),
                        array('img' => 'xlarge_button', 'value' => 'xlarge', 'label' => 'xlrg'),
                    ),
                    'control' => array(
                        'classSelector' => ''
                    )
                ),
                array(
                    'id' => 'buttons_shape',
                    'type' => 'layout',
                    'mode' => 'sprite',
                    'label' => __('Shape', 'themify'),
                    'options' => array(
                        array('img' => 'normall_button', 'value' => 'normal', 'label' => 'def'),
                        array('img' => 'squared_button', 'value' => 'squared', 'label' => 'squared'),
                        array('img' => 'circle_button', 'value' => 'circle', 'label' => 'circle'),
                        array('img' => 'rounded_button', 'value' => 'rounded', 'label' => 'rounded'),
                    ),
                    'control' => array(
                        'classSelector' => ''
                    )
                ),
                array(
                    'id' => 'buttons_style',
                    'type' => 'layout',
                    'mode' => 'sprite',
                    'label' => 'bg',
                    'options' => array(
                        array('img' => 'solid_button', 'value' => 'solid', 'label' => 'solid'),
                        array('img' => 'outline_button', 'value' => 'outline', 'label' => 'o'),
                        array('img' => 'transparent_button', 'value' => 'transparent', 'label' => 'transparent'),
                    ),
                    'control' => array(
                        'classSelector' => ''
                    )
                ),
                array(
                    'id' => 'display',
                    'type' => 'layout',
                    'mode' => 'sprite',
                    'label' => 'disp',
                    'options' => array(
                        array('img' => 'horizontal_button', 'value' => 'buttons-horizontal', 'label' => 'hrztal'),
                        array('img' => 'vertical_button', 'value' => 'buttons-vertical', 'label' => 'vertical'),
                    ),
                    'control' => array(
                        'classSelector' => ''
                    )
                ),
                array(
                    'id' => 'fullwidth_button',
                    'type' => 'toggle_switch',
                    'label' => 'fw',
                    'options' => array(
                        'on' => array('name' => 'buttons-fullwidth')
                    ),
                    'binding' => array(
                        'checked' => array(
                            'hide' => 'display'
                        ),
                        'not_checked' => array(
                            'show' => 'display'
                        )
                    )
                ),
                array(
                    'id' => 'nofollow_link',
                    'type' => 'toggle_switch',
                    'label' => __('Nofollow', 'themify'),
                    'options' => array(
                        'on' => array('name' => 'yes')
                    ),
                    'help' => __("If nofollow is enabled, search engines won't crawl this link.", 'themify'),
                    'control' => false
                ),
                array(
                    'id' => 'download_link',
                    'type' => 'toggle_switch',
                    'label' => 'dwnable',
                    'options' => array(
                        'on' => array('name' => 'yes')
                    ),
                    'help' => 'dwnablef',
                    'control' => false
                ),
            ),
        ),
        array('type' => 'custom_css_id', 'custom_css' => 'css_button'),
    );
}
*/