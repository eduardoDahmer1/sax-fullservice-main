/* ===========================================================
 * trumbowyg.allowTagsFromPaste.js v1.0.2
 * It cleans tags from pasted text, whilst allowing several specified tags
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author	: Fathi Anshory (0x00000F5C)
 * Twitter	: @fscchannl
 * Notes:
 *  - removeformatPasted must be set to FALSE since it was applied prior to pasteHandlers, or else it will be useless
 *	- It is most advisable to use along with the cleanpaste plugin, or else you'd end up with dirty markup
 */

(function ($) {
    'use strict';

    var defaultOptions = {
        // When empty, all tags are allowed making this plugin useless
        // If you want to remove all tags, use removeformatPasted core option instead
        allowedTags: [
            'a',
            'abbr',
            'address',
            'b',
            'bdi',
            'bdo',
            'blockquote',
            'br',
            'cite',
            'code',
            'del',
            'dfn',
            'details',
            'em',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'hr',
            'i',
            'ins',
            'kbd',
            'mark',
            'meter',
            'pre',
            'progress',
            'q',
            'rp',
            'rt',
            'ruby',
            's',
            'samp',
            'small',
            'span',
            'strong',
            'sub',
            'summary',
            'sup',
            'time',
            'u',
            'var',
            'wbr',
            'img',
            'map',
            'area',
            'canvas',
            'figcaption',
            'figure',
            'picture',
            'audio',
            'source',
            'track',
            'video',
            'ul',
            'ol',
            'li',
            'dl',
            'dt',
            'dd',
            'table',
            'caption',
            'th',
            'tr',
            'td',
            'thead',
            'tbody',
            'tfoot',
            'col',
            'colgroup',
            'style',
            'div',
            'p',
            'form',
            'input',
            'textarea',
            'button',
            'select',
            'optgroup',
            'option',
            'label',
            'fieldset',
            'legend',
            'datalist',
            'keygen',
            'output',
            'iframe',
            'link',
            'nav',
            'header',
            'hgroup',
            'footer',
            'main',
            'section',
            'article',
            'aside',
            'dialog',
            'script',
            'noscript',
            'embed',
            'object',
            'param'
        ],
        // List of tags which can be allowed
        removableTags: []
    };

    $.extend(true, $.trumbowyg, {
        plugins: {
            allowTagsFromPaste: {
                init: function (trumbowyg) {
                    // Force disable remove format pasted
                    trumbowyg.o.removeformatPasted = false;

                    if (!trumbowyg.o.plugins.allowTagsFromPaste) {
                        return;
                    }

                    var allowedTags = trumbowyg.o.plugins.allowTagsFromPaste.allowedTags || defaultOptions.allowedTags;
                    var removableTags = trumbowyg.o.plugins.allowTagsFromPaste.removableTags || defaultOptions.removableTags;

                    if (allowedTags.length === 0) {
                        return;
                    }

                    // Get list of tags to remove
                    var tagsToRemove = $(removableTags).not(allowedTags).get();

                    trumbowyg.pasteHandlers.push(function () {
                        setTimeout(function () {
                            var processNodes = trumbowyg.$ed.html();
                            $.each(tagsToRemove, function (iterator, tagName) {
                                processNodes = processNodes.replace(new RegExp('<\\/?' + tagName + '(\\s[^>]*)?>', 'gi'), '');
                            });
                            trumbowyg.$ed.html(processNodes);
                        }, 0);
                    });
                }
            }
        }
    });
})(jQuery);

/* ===========================================================
 * trumbowyg.base64.js v1.0
 * Base64 plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Cyril Biencourt (lizardK)
 */

(function ($) {
    'use strict';

    var isSupported = function () {
        return typeof FileReader !== 'undefined';
    };

    var isValidImage = function (type) {
        return /^data:image\/[a-z]?/i.test(type);
    };

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                base64: 'Image as base64',
                file: 'File',
                errFileReaderNotSupported: 'FileReader is not supported by your browser.',
                errInvalidImage: 'Invalid image file.'
            },
            da: {
                base64: 'Billede som base64',
                file: 'Fil',
                errFileReaderNotSupported: 'FileReader er ikke understøttet af din browser.',
                errInvalidImage: 'Ugyldig billedfil.'
            },
            fr: {
                base64: 'Image en base64',
                file: 'Fichier'
            },
            cs: {
                base64: 'Vložit obrázek',
                file: 'Soubor'
            },
            zh_cn: {
                base64: '图片（Base64编码）',
                file: '文件'
            },
            nl: {
                base64: 'Afbeelding inline',
                file: 'Bestand',
                errFileReaderNotSupported: 'Uw browser ondersteunt deze functionaliteit niet.',
                errInvalidImage: 'De gekozen afbeelding is ongeldig.'
            },
            ru: {
                base64: 'Изображение как код в base64',
                file: 'Файл',
                errFileReaderNotSupported: 'FileReader не поддерживается вашим браузером.',
                errInvalidImage: 'Недопустимый файл изображения.'
            },
            ja: {
                base64: '画像 (Base64形式)',
                file: 'ファイル',
                errFileReaderNotSupported: 'あなたのブラウザーはFileReaderをサポートしていません',
                errInvalidImage: '画像形式が正しくありません'
            },
            tr: {
                base64: 'Base64 olarak resim',
                file: 'Dosya',
                errFileReaderNotSupported: 'FileReader tarayıcınız tarafından desteklenmiyor.',
                errInvalidImage: 'Geçersiz resim dosyası.'
            },
            zh_tw: {
                base64: '圖片(base64編碼)',
                file: '檔案',
                errFileReaderNotSupported: '你的瀏覽器不支援FileReader',
                errInvalidImage: '不正確的檔案格式'
            },
            pt_br: {
                base64: 'Imagem em base64',
                file: 'Arquivo',
                errFileReaderNotSupported: 'FileReader não é suportado pelo seu navegador.',
                errInvalidImage: 'Arquivo de imagem inválido.'
            },
            ko: {
                base64: '그림 넣기(base64)',
                file: '파일',
                errFileReaderNotSupported: 'FileReader가 현재 브라우저를 지원하지 않습니다.',
                errInvalidImage: '유효하지 않은 파일'
            },
        },
        // jshint camelcase:true

        plugins: {
            base64: {
                shouldInit: isSupported,
                init: function (trumbowyg) {
                    var btnDef = {
                        isSupported: isSupported,
                        fn: function () {
                            trumbowyg.saveRange();

                            var file;
                            var $modal = trumbowyg.openModalInsert(
                                // Title
                                trumbowyg.lang.base64,

                                // Fields
                                {
                                    file: {
                                        type: 'file',
                                        required: true,
                                        attributes: {
                                            accept: 'image/*'
                                        }
                                    },
                                    alt: {
                                        label: 'description',
                                        value: trumbowyg.getRangeText()
                                    }
                                },

                                // Callback
                                function (values) {
                                    var fReader = new FileReader();

                                    fReader.onloadend = function (e) {
                                        if (isValidImage(e.target.result)) {
                                            trumbowyg.execCmd('insertImage', fReader.result, false, true);
                                            $(['img[src="', fReader.result, '"]:not([alt])'].join(''), trumbowyg.$box).attr('alt', values.alt);
                                            trumbowyg.closeModal();
                                        } else {
                                            trumbowyg.addErrorOnModalField(
                                                $('input[type=file]', $modal),
                                                trumbowyg.lang.errInvalidImage
                                            );
                                        }
                                    };

                                    fReader.readAsDataURL(file);
                                }
                            );

                            $('input[type=file]').on('change', function (e) {
                                file = e.target.files[0];
                            });
                        }
                    };

                    trumbowyg.addBtnDef('base64', btnDef);
                }
            }
        }
    });
})(jQuery);

/* ===========================================================
 * trumbowyg.pasteimage.js v1.0
 * Basic base64 paste plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Alexandre Demode (Alex-D)
 *          Twitter : @AlexandreDemode
 *          Website : alex-d.fr
 */

(function ($) {
    'use strict';

    $.extend(true, $.trumbowyg, {
        plugins: {
            pasteImage: {
                init: function (trumbowyg) {
                    trumbowyg.pasteHandlers.push(function (pasteEvent) {
                        try {
                            var items = (pasteEvent.originalEvent || pasteEvent).clipboardData.items,
                                mustPreventDefault = false,
                                reader;

                            for (var i = items.length - 1; i >= 0; i -= 1) {
                                if (items[i].type.match(/^image\//)) {
                                    reader = new FileReader();
                                    /* jshint -W083 */
                                    reader.onloadend = function (event) {
                                        trumbowyg.execCmd('insertImage', event.target.result, false, true);
                                    };
                                    /* jshint +W083 */
                                    reader.readAsDataURL(items[i].getAsFile());

                                    mustPreventDefault = true;
                                }
                            }

                            if (mustPreventDefault) {
                                pasteEvent.stopPropagation();
                                pasteEvent.preventDefault();
                            }
                        } catch (c) {
                        }
                    });
                }
            }
        }
    });
})(jQuery);

/* ===========================================================
 * trumbowyg.colors.js v1.2
 * Colors picker plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Alexandre Demode (Alex-D)
 *          Twitter : @AlexandreDemode
 *          Website : alex-d.fr
 */

(function ($) {
    'use strict';

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            cs: {
                foreColor: 'Barva textu',
                backColor: 'Barva pozadí'
            },
            en: {
                foreColor: 'Text color',
                backColor: 'Background color',
                foreColorRemove: 'Remove text color',
                backColorRemove: 'Remove background color'
            },
            da: {
                foreColor: 'Tekstfarve',
                backColor: 'Baggrundsfarve'
            },
            fr: {
                foreColor: 'Couleur du texte',
                backColor: 'Couleur de fond',
                foreColorRemove: 'Supprimer la couleur du texte',
                backColorRemove: 'Supprimer la couleur de fond'
            },
            de: {
                foreColor: 'Textfarbe',
                backColor: 'Hintergrundfarbe'
            },
            nl: {
                foreColor: 'Tekstkleur',
                backColor: 'Achtergrondkleur'
            },
            sk: {
                foreColor: 'Farba textu',
                backColor: 'Farba pozadia'
            },
            zh_cn: {
                foreColor: '文字颜色',
                backColor: '背景颜色'
            },
            zh_tw: {
                foreColor: '文字顏色',
                backColor: '背景顏色'
            },
            ru: {
                foreColor: 'Цвет текста',
                backColor: 'Цвет выделения текста'
            },
            ja: {
                foreColor: '文字色',
                backColor: '背景色'
            },
            tr: {
                foreColor: 'Yazı rengi',
                backColor: 'Arkaplan rengi'
            },
            pt_br: {
                foreColor: 'Cor de fonte',
                backColor: 'Cor de fundo'
            },
            ko: {
                foreColor: '글자색',
                backColor: '배경색',
                foreColorRemove: '글자색 지우기',
                backColorRemove: '배경색 지우기'
            },
        }
    });

    // jshint camelcase:true


    function hex(x) {
        return ('0' + parseInt(x).toString(16)).slice(-2);
    }

    function colorToHex(rgb) {
        if (rgb.search('rgb') === -1) {
            return rgb.replace('#', '');
        } else if (rgb === 'rgba(0, 0, 0, 0)') {
            return 'transparent';
        } else {
            rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
            return hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
        }
    }

    function colorTagHandler(element, trumbowyg) {
        var tags = [];

        if (!element.style) {
            return tags;
        }

        // background color
        if (element.style.backgroundColor !== '') {
            var backColor = colorToHex(element.style.backgroundColor);
            if (trumbowyg.o.plugins.colors.colorList.indexOf(backColor) >= 0) {
                tags.push('backColor' + backColor);
            } else {
                tags.push('backColorFree');
            }
        }

        // text color
        var foreColor;
        if (element.style.color !== '') {
            foreColor = colorToHex(element.style.color);
        } else if (element.hasAttribute('color')) {
            foreColor = colorToHex(element.getAttribute('color'));
        }
        if (foreColor) {
            if (trumbowyg.o.plugins.colors.colorList.indexOf(foreColor) >= 0) {
                tags.push('foreColor' + foreColor);
            } else {
                tags.push('foreColorFree');
            }
        }

        return tags;
    }

    var defaultOptions = {
        colorList: [
            'ffffff', '000000', 'eeece1', '1f497d', '4f81bd', 'c0504d', '9bbb59', '8064a2', '4bacc6', 'f79646', 'ffff00',
            'f2f2f2', '7f7f7f', 'ddd9c3', 'c6d9f0', 'dbe5f1', 'f2dcdb', 'ebf1dd', 'e5e0ec', 'dbeef3', 'fdeada', 'fff2ca',
            'd8d8d8', '595959', 'c4bd97', '8db3e2', 'b8cce4', 'e5b9b7', 'd7e3bc', 'ccc1d9', 'b7dde8', 'fbd5b5', 'ffe694',
            'bfbfbf', '3f3f3f', '938953', '548dd4', '95b3d7', 'd99694', 'c3d69b', 'b2a2c7', 'b7dde8', 'fac08f', 'f2c314',
            'a5a5a5', '262626', '494429', '17365d', '366092', '953734', '76923c', '5f497a', '92cddc', 'e36c09', 'c09100',
            '7f7f7f', '0c0c0c', '1d1b10', '0f243e', '244061', '632423', '4f6128', '3f3151', '31859b', '974806', '7f6000'
        ],
        foreColorList: null, // fallbacks on colorList
        backColorList: null, // fallbacks on colorList
        allowCustomForeColor: true,
        allowCustomBackColor: true,
        displayAsList: false,
    };

    // Add all colors in two dropdowns
    $.extend(true, $.trumbowyg, {
        plugins: {
            color: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.colors = trumbowyg.o.plugins.colors || defaultOptions;
                    var dropdownClass = trumbowyg.o.plugins.colors.displayAsList ? trumbowyg.o.prefix + 'dropdown--color-list' : '';

                    var foreColorBtnDef = {
                        dropdown: buildDropdown('foreColor', trumbowyg),
                        dropdownClass: dropdownClass,
                    },
                    backColorBtnDef = {
                        dropdown: buildDropdown('backColor', trumbowyg),
                        dropdownClass: dropdownClass,
                    };

                    trumbowyg.addBtnDef('foreColor', foreColorBtnDef);
                    trumbowyg.addBtnDef('backColor', backColorBtnDef);
                },
                tagHandler: colorTagHandler
            }
        }
    });

    function buildDropdown(fn, trumbowyg) {
        var dropdown = [],
            trumbowygColorOptions = trumbowyg.o.plugins.colors,
            colorList = trumbowygColorOptions[fn + 'List'] || trumbowygColorOptions.colorList;

        $.each(colorList, function (i, color) {
            var btn = fn + color,
                btnDef = {
                    fn: fn,
                    forceCss: true,
                    hasIcon: false,
                    text: trumbowyg.lang['#' + color] || ('#' + color),
                    param: '#' + color,
                    style: 'background-color: #' + color + ';'
                };

            if (trumbowygColorOptions.displayAsList && fn === 'foreColor') {
                btnDef.style = 'color: #' + color + ' !important;';
            }

            trumbowyg.addBtnDef(btn, btnDef);
            dropdown.push(btn);
        });

        // Remove color
        var removeColorButtonName = fn + 'Remove',
            removeColorBtnDef = {
                fn: 'removeFormat',
                hasIcon: false,
                param: fn,
                style: 'background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAG0lEQVQIW2NkQAAfEJMRmwBYhoGBYQtMBYoAADziAp0jtJTgAAAAAElFTkSuQmCC);'
            };

        if (trumbowygColorOptions.displayAsList) {
            removeColorBtnDef.style = '';
        }

        trumbowyg.addBtnDef(removeColorButtonName, removeColorBtnDef);
        dropdown.push(removeColorButtonName);

        // Custom color
        if (trumbowygColorOptions['allowCustom' + fn.charAt(0).toUpperCase() + fn.substr(1)]) {
            // add free color btn
            var freeColorButtonName = fn + 'Free',
                freeColorBtnDef = {
                    fn: function () {
                        trumbowyg.openModalInsert(trumbowyg.lang[fn],
                            {
                                color: {
                                    label: fn,
                                    forceCss: true,
                                    type: 'color',
                                    value: '#FFFFFF'
                                }
                            },
                            // callback
                            function (values) {
                                trumbowyg.execCmd(fn, values.color);
                                return true;
                            }
                        );
                    },
                    hasIcon: false,
                    text: '#',
                    // style adjust for displaying the text
                    style: 'text-indent: 0; line-height: 20px; padding: 0 5px;'
                };

            trumbowyg.addBtnDef(freeColorButtonName, freeColorBtnDef);
            dropdown.push(freeColorButtonName);
        }

        return dropdown;
    }
})(jQuery);

/* ===========================================================
 * trumbowyg.emoji.js v0.1
 * Emoji picker plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Nicolas Pion
 *          Twitter : @nicolas_pion
 */

(function ($) {
    'use strict';

    var defaultOptions = {
        emojiList: [
            '&#x2049',
            '&#x2122',
            '&#x2139',
            '&#x2194',
            '&#x2195',
            '&#x2196',
            '&#x2197',
            '&#x2198',
            '&#x2199',
            '&#x2328',
            '&#x2600',
            '&#x2601',
            '&#x2602',
            '&#x2603',
            '&#x2604',
            '&#x2611',
            '&#x2614',
            '&#x2615',
            '&#x2618',
            '&#x2620',
            '&#x2622',
            '&#x2623',
            '&#x2626',
            '&#x2638',
            '&#x2639',
            '&#x2640',
            '&#x2642',
            '&#x2648',
            '&#x2649',
            '&#x2650',
            '&#x2651',
            '&#x2652',
            '&#x2653',
            '&#x2660',
            '&#x2663',
            '&#x2665',
            '&#x2666',
            '&#x2668',
            '&#x2692',
            '&#x2693',
            '&#x2694',
            '&#x2695',
            '&#x2696',
            '&#x2697',
            '&#x2699',
            '&#x2702',
            '&#x2705',
            '&#x2708',
            '&#x2709',
            '&#x2712',
            '&#x2714',
            '&#x2716',
            '&#x2721',
            '&#x2728',
            '&#x2733',
            '&#x2734',
            '&#x2744',
            '&#x2747',
            '&#x2753',
            '&#x2754',
            '&#x2755',
            '&#x2757',
            '&#x2763',
            '&#x2764',
            '&#x2795',
            '&#x2796',
            '&#x2797',
            '&#x2934',
            '&#x2935',
            '&#x3030',
            '&#x3297',
            '&#x3299',
            '&#x1F9E1',
            '&#x1F49B',
            '&#x1F49A',
            '&#x1F499',
            '&#x1F49C',
            '&#x1F5A4',
            '&#x1F494',
            '&#x1F495',
            '&#x1F49E',
            '&#x1F493',
            '&#x1F497',
            '&#x1F496',
            '&#x1F498',
            '&#x1F49D',
            '&#x1F49F',
            '&#x262E',
            '&#x271D',
            '&#x262A',
            '&#x1F549',
            '&#x1F52F',
            '&#x1F54E',
            '&#x262F',
            '&#x1F6D0',
            '&#x26CE',
            '&#x264A',
            '&#x264B',
            '&#x264C',
            '&#x264D',
            '&#x264E',
            '&#x264F',
            '&#x1F194',
            '&#x269B',
            '&#x267E',
            '&#x1F251',
            '&#x1F4F4',
            '&#x1F4F3',
            '&#x1F236',
            '&#x1F21A',
            '&#x1F238',
            '&#x1F23A',
            '&#x1F237',
            '&#x1F19A',
            '&#x1F4AE',
            '&#x1F250',
            '&#x1F234',
            '&#x1F235',
            '&#x1F239',
            '&#x1F232',
            '&#x1F170',
            '&#x1F171',
            '&#x1F18E',
            '&#x1F191',
            '&#x1F17E',
            '&#x1F198',
            '&#x274C',
            '&#x2B55',
            '&#x1F6D1',
            '&#x26D4',
            '&#x1F4DB',
            '&#x1F6AB',
            '&#x1F4AF',
            '&#x1F4A2',
            '&#x1F6B7',
            '&#x1F6AF',
            '&#x1F6B3',
            '&#x1F6B1',
            '&#x1F51E',
            '&#x1F4F5',
            '&#x1F6AD',
            '&#x203C',
            '&#x1F505',
            '&#x1F506',
            '&#x303D',
            '&#x26A0',
            '&#x1F6B8',
            '&#x1F531',
            '&#x269C',
            '&#x1F530',
            '&#x267B',
            '&#x1F22F',
            '&#x1F4B9',
            '&#x274E',
            '&#x1F310',
            '&#x1F4A0',
            '&#x24C2',
            '&#x1F300',
            '&#x1F4A4',
            '&#x1F3E7',
            '&#x1F6BE',
            '&#x267F',
            '&#x1F17F',
            '&#x1F233',
            '&#x1F202',
            '&#x1F6C2',
            '&#x1F6C3',
            '&#x1F6C4',
            '&#x1F6C5',
            '&#x1F6B9',
            '&#x1F6BA',
            '&#x1F6BC',
            '&#x1F6BB',
            '&#x1F6AE',
            '&#x1F3A6',
            '&#x1F4F6',
            '&#x1F201',
            '&#x1F523',
            '&#x1F524',
            '&#x1F521',
            '&#x1F520',
            '&#x1F196',
            '&#x1F197',
            '&#x1F199',
            '&#x1F192',
            '&#x1F195',
            '&#x1F193',
            '&#x0030',
            '&#x0031',
            '&#x0032',
            '&#x0033',
            '&#x0034',
            '&#x0035',
            '&#x0036',
            '&#x0037',
            '&#x0038',
            '&#x0039',
            '&#x1F51F',
            '&#x1F522',
            '&#x0023',
            '&#x002A',
            '&#x23CF',
            '&#x25B6',
            '&#x23F8',
            '&#x23EF',
            '&#x23F9',
            '&#x23FA',
            '&#x23ED',
            '&#x23EE',
            '&#x23E9',
            '&#x23EA',
            '&#x23EB',
            '&#x23EC',
            '&#x25C0',
            '&#x1F53C',
            '&#x1F53D',
            '&#x27A1',
            '&#x2B05',
            '&#x2B06',
            '&#x2B07',
            '&#x21AA',
            '&#x21A9',
            '&#x1F500',
            '&#x1F501',
            '&#x1F502',
            '&#x1F504',
            '&#x1F503',
            '&#x1F3B5',
            '&#x1F3B6',
            '&#x1F4B2',
            '&#x1F4B1',
            '&#x00A9',
            '&#x00AE',
            '&#x27B0',
            '&#x27BF',
            '&#x1F51A',
            '&#x1F519',
            '&#x1F51B',
            '&#x1F51D',
            '&#x1F51C',
            '&#x1F518',
            '&#x26AA',
            '&#x26AB',
            '&#x1F534',
            '&#x1F535',
            '&#x1F53A',
            '&#x1F53B',
            '&#x1F538',
            '&#x1F539',
            '&#x1F536',
            '&#x1F537',
            '&#x1F533',
            '&#x1F532',
            '&#x25AA',
            '&#x25AB',
            '&#x25FE',
            '&#x25FD',
            '&#x25FC',
            '&#x25FB',
            '&#x2B1B',
            '&#x2B1C',
            '&#x1F508',
            '&#x1F507',
            '&#x1F509',
            '&#x1F50A',
            '&#x1F514',
            '&#x1F515',
            '&#x1F4E3',
            '&#x1F4E2',
            '&#x1F5E8',
            '&#x1F441',
            '&#x1F4AC',
            '&#x1F4AD',
            '&#x1F5EF',
            '&#x1F0CF',
            '&#x1F3B4',
            '&#x1F004',
            '&#x1F550',
            '&#x1F551',
            '&#x1F552',
            '&#x1F553',
            '&#x1F554',
            '&#x1F555',
            '&#x1F556',
            '&#x1F557',
            '&#x1F558',
            '&#x1F559',
            '&#x1F55A',
            '&#x1F55B',
            '&#x1F55C',
            '&#x1F55D',
            '&#x1F55E',
            '&#x1F55F',
            '&#x1F560',
            '&#x1F561',
            '&#x1F562',
            '&#x1F563',
            '&#x1F564',
            '&#x1F565',
            '&#x1F566',
            '&#x1F567',
            '&#x26BD',
            '&#x1F3C0',
            '&#x1F3C8',
            '&#x26BE',
            '&#x1F94E',
            '&#x1F3BE',
            '&#x1F3D0',
            '&#x1F3C9',
            '&#x1F3B1',
            '&#x1F3D3',
            '&#x1F3F8',
            '&#x1F945',
            '&#x1F3D2',
            '&#x1F3D1',
            '&#x1F3CF',
            '&#x1F94D',
            '&#x26F3',
            '&#x1F94F',
            '&#x1F3F9',
            '&#x1F3A3',
            '&#x1F94A',
            '&#x1F94B',
            '&#x1F3BD',
            '&#x1F6F9',
            '&#x26F8',
            '&#x1F94C',
            '&#x1F6F7',
            '&#x1F3BF',
            '&#x26F7',
            '&#x1F3C2',
            '&#x1F3CB',
            '&#x1F93C',
            '&#x1F938',
            '&#x26F9',
            '&#x1F93A',
            '&#x1F93E',
            '&#x1F3CC',
            '&#x1F3C7',
            '&#x1F9D8',
            '&#x1F3C4',
            '&#x1F3CA',
            '&#x1F93D',
            '&#x1F6A3',
            '&#x1F9D7',
            '&#x1F6B5',
            '&#x1F6B4',
            '&#x1F3C6',
            '&#x1F947',
            '&#x1F948',
            '&#x1F949',
            '&#x1F3C5',
            '&#x1F396',
            '&#x1F3F5',
            '&#x1F397',
            '&#x1F3AB',
            '&#x1F39F',
            '&#x1F3AA',
            '&#x1F939',
            '&#x1F3AD',
            '&#x1F3A8',
            '&#x1F3AC',
            '&#x1F3A4',
            '&#x1F3A7',
            '&#x1F3BC',
            '&#x1F3B9',
            '&#x1F941',
            '&#x1F3B7',
            '&#x1F3BA',
            '&#x1F3B8',
            '&#x1F3BB',
            '&#x1F3B2',
            '&#x1F3AF',
            '&#x1F3B3',
            '&#x1F3AE',
            '&#x1F3B0',
            '&#x231A',
            '&#x1F4F1',
            '&#x1F4F2',
            '&#x1F4BB',
            '&#x1F5A5',
            '&#x1F5A8',
            '&#x1F5B1',
            '&#x1F5B2',
            '&#x1F579',
            '&#x265F',
            '&#x1F9E9',
            '&#x1F5DC',
            '&#x1F4BD',
            '&#x1F4BE',
            '&#x1F4BF',
            '&#x1F4C0',
            '&#x1F4FC',
            '&#x1F4F7',
            '&#x1F4F8',
            '&#x1F4F9',
            '&#x1F3A5',
            '&#x1F4FD',
            '&#x1F39E',
            '&#x1F4DE',
            '&#x260E',
            '&#x1F4DF',
            '&#x1F4E0',
            '&#x1F4FA',
            '&#x1F4FB',
            '&#x1F399',
            '&#x1F39A',
            '&#x1F39B',
            '&#x23F1',
            '&#x23F2',
            '&#x23F0',
            '&#x1F570',
            '&#x231B',
            '&#x23F3',
            '&#x1F4E1',
            '&#x1F9ED',
            '&#x1F50B',
            '&#x1F50C',
            '&#x1F9F2',
            '&#x1F4A1',
            '&#x1F526',
            '&#x1F56F',
            '&#x1F9EF',
            '&#x1F5D1',
            '&#x1F6E2',
            '&#x1F4B8',
            '&#x1F4B5',
            '&#x1F4B4',
            '&#x1F4B6',
            '&#x1F4B7',
            '&#x1F4B0',
            '&#x1F4B3',
            '&#x1F48E',
            '&#x1F9FF',
            '&#x1F9F1',
            '&#x1F9F0',
            '&#x1F527',
            '&#x1F528',
            '&#x1F6E0',
            '&#x26CF',
            '&#x1F529',
            '&#x26D3',
            '&#x1F52B',
            '&#x1F4A3',
            '&#x1F52A',
            '&#x1F5E1',
            '&#x1F6E1',
            '&#x1F6AC',
            '&#x26B0',
            '&#x26B1',
            '&#x1F3FA',
            '&#x1F52E',
            '&#x1F4FF',
            '&#x1F488',
            '&#x1F9EA',
            '&#x1F9EB',
            '&#x1F9EC',
            '&#x1F9EE',
            '&#x1F52D',
            '&#x1F52C',
            '&#x1F573',
            '&#x1F48A',
            '&#x1F489',
            '&#x1F321',
            '&#x1F6BD',
            '&#x1F6B0',
            '&#x1F6BF',
            '&#x1F6C1',
            '&#x1F6C0',
            '&#x1F9F9',
            '&#x1F9FA',
            '&#x1F9FB',
            '&#x1F9FC',
            '&#x1F9FD',
            '&#x1F9F4',
            '&#x1F9F5',
            '&#x1F9F6',
            '&#x1F6CE',
            '&#x1F511',
            '&#x1F5DD',
            '&#x1F6AA',
            '&#x1F6CB',
            '&#x1F6CF',
            '&#x1F6CC',
            '&#x1F9F8',
            '&#x1F5BC',
            '&#x1F6CD',
            '&#x1F6D2',
            '&#x1F381',
            '&#x1F388',
            '&#x1F38F',
            '&#x1F380',
            '&#x1F38A',
            '&#x1F389',
            '&#x1F38E',
            '&#x1F3EE',
            '&#x1F390',
            '&#x1F9E7',
            '&#x1F4E9',
            '&#x1F4E8',
            '&#x1F4E7',
            '&#x1F48C',
            '&#x1F4E5',
            '&#x1F4E4',
            '&#x1F4E6',
            '&#x1F3F7',
            '&#x1F4EA',
            '&#x1F4EB',
            '&#x1F4EC',
            '&#x1F4ED',
            '&#x1F4EE',
            '&#x1F4EF',
            '&#x1F4DC',
            '&#x1F4C3',
            '&#x1F4C4',
            '&#x1F9FE',
            '&#x1F4D1',
            '&#x1F4CA',
            '&#x1F4C8',
            '&#x1F4C9',
            '&#x1F5D2',
            '&#x1F5D3',
            '&#x1F4C6',
            '&#x1F4C5',
            '&#x1F4C7',
            '&#x1F5C3',
            '&#x1F5F3',
            '&#x1F5C4',
            '&#x1F4CB',
            '&#x1F4C1',
            '&#x1F4C2',
            '&#x1F5C2',
            '&#x1F5DE',
            '&#x1F4F0',
            '&#x1F4D3',
            '&#x1F4D4',
            '&#x1F4D2',
            '&#x1F4D5',
            '&#x1F4D7',
            '&#x1F4D8',
            '&#x1F4D9',
            '&#x1F4DA',
            '&#x1F4D6',
            '&#x1F516',
            '&#x1F517',
            '&#x1F4CE',
            '&#x1F587',
            '&#x1F4D0',
            '&#x1F4CF',
            '&#x1F9F7',
            '&#x1F4CC',
            '&#x1F4CD',
            '&#x1F58A',
            '&#x1F58B',
            '&#x1F58C',
            '&#x1F58D',
            '&#x1F4DD',
            '&#x270F',
            '&#x1F50D',
            '&#x1F50E',
            '&#x1F50F',
            '&#x1F510',
            '&#x1F436',
            '&#x1F431',
            '&#x1F42D',
            '&#x1F439',
            '&#x1F430',
            '&#x1F98A',
            '&#x1F99D',
            '&#x1F43B',
            '&#x1F43C',
            '&#x1F998',
            '&#x1F9A1',
            '&#x1F428',
            '&#x1F42F',
            '&#x1F981',
            '&#x1F42E',
            '&#x1F437',
            '&#x1F43D',
            '&#x1F438',
            '&#x1F435',
            '&#x1F648',
            '&#x1F649',
            '&#x1F64A',
            '&#x1F412',
            '&#x1F414',
            '&#x1F427',
            '&#x1F426',
            '&#x1F424',
            '&#x1F423',
            '&#x1F425',
            '&#x1F986',
            '&#x1F9A2',
            '&#x1F985',
            '&#x1F989',
            '&#x1F99C',
            '&#x1F99A',
            '&#x1F987',
            '&#x1F43A',
            '&#x1F417',
            '&#x1F434',
            '&#x1F984',
            '&#x1F41D',
            '&#x1F41B',
            '&#x1F98B',
            '&#x1F40C',
            '&#x1F41A',
            '&#x1F41E',
            '&#x1F41C',
            '&#x1F997',
            '&#x1F577',
            '&#x1F578',
            '&#x1F982',
            '&#x1F99F',
            '&#x1F9A0',
            '&#x1F422',
            '&#x1F40D',
            '&#x1F98E',
            '&#x1F996',
            '&#x1F995',
            '&#x1F419',
            '&#x1F991',
            '&#x1F990',
            '&#x1F980',
            '&#x1F99E',
            '&#x1F421',
            '&#x1F420',
            '&#x1F41F',
            '&#x1F42C',
            '&#x1F433',
            '&#x1F40B',
            '&#x1F988',
            '&#x1F40A',
            '&#x1F405',
            '&#x1F406',
            '&#x1F993',
            '&#x1F98D',
            '&#x1F418',
            '&#x1F98F',
            '&#x1F99B',
            '&#x1F42A',
            '&#x1F42B',
            '&#x1F992',
            '&#x1F999',
            '&#x1F403',
            '&#x1F402',
            '&#x1F404',
            '&#x1F40E',
            '&#x1F416',
            '&#x1F40F',
            '&#x1F411',
            '&#x1F410',
            '&#x1F98C',
            '&#x1F415',
            '&#x1F429',
            '&#x1F408',
            '&#x1F413',
            '&#x1F983',
            '&#x1F54A',
            '&#x1F407',
            '&#x1F401',
            '&#x1F400',
            '&#x1F43F',
            '&#x1F994',
            '&#x1F43E',
            '&#x1F409',
            '&#x1F432',
            '&#x1F335',
            '&#x1F384',
            '&#x1F332',
            '&#x1F333',
            '&#x1F334',
            '&#x1F331',
            '&#x1F33F',
            '&#x1F340',
            '&#x1F38D',
            '&#x1F38B',
            '&#x1F343',
            '&#x1F342',
            '&#x1F341',
            '&#x1F344',
            '&#x1F33E',
            '&#x1F490',
            '&#x1F337',
            '&#x1F339',
            '&#x1F940',
            '&#x1F33A',
            '&#x1F338',
            '&#x1F33C',
            '&#x1F33B',
            '&#x1F31E',
            '&#x1F31D',
            '&#x1F31B',
            '&#x1F31C',
            '&#x1F31A',
            '&#x1F315',
            '&#x1F316',
            '&#x1F317',
            '&#x1F318',
            '&#x1F311',
            '&#x1F312',
            '&#x1F313',
            '&#x1F314',
            '&#x1F319',
            '&#x1F30E',
            '&#x1F30D',
            '&#x1F30F',
            '&#x1F4AB',
            '&#x2B50',
            '&#x1F31F',
            '&#x26A1',
            '&#x1F4A5',
            '&#x1F525',
            '&#x1F32A',
            '&#x1F308',
            '&#x1F324',
            '&#x26C5',
            '&#x1F325',
            '&#x1F326',
            '&#x1F327',
            '&#x26C8',
            '&#x1F329',
            '&#x1F328',
            '&#x26C4',
            '&#x1F32C',
            '&#x1F4A8',
            '&#x1F4A7',
            '&#x1F4A6',
            '&#x1F30A',
            '&#x1F32B',
            '&#x1F34F',
            '&#x1F34E',
            '&#x1F350',
            '&#x1F34A',
            '&#x1F34B',
            '&#x1F34C',
            '&#x1F349',
            '&#x1F347',
            '&#x1F353',
            '&#x1F348',
            '&#x1F352',
            '&#x1F351',
            '&#x1F96D',
            '&#x1F34D',
            '&#x1F965',
            '&#x1F95D',
            '&#x1F345',
            '&#x1F346',
            '&#x1F951',
            '&#x1F966',
            '&#x1F96C',
            '&#x1F952',
            '&#x1F336',
            '&#x1F33D',
            '&#x1F955',
            '&#x1F954',
            '&#x1F360',
            '&#x1F950',
            '&#x1F35E',
            '&#x1F956',
            '&#x1F968',
            '&#x1F96F',
            '&#x1F9C0',
            '&#x1F95A',
            '&#x1F373',
            '&#x1F95E',
            '&#x1F953',
            '&#x1F969',
            '&#x1F357',
            '&#x1F356',
            '&#x1F32D',
            '&#x1F354',
            '&#x1F35F',
            '&#x1F355',
            '&#x1F96A',
            '&#x1F959',
            '&#x1F32E',
            '&#x1F32F',
            '&#x1F957',
            '&#x1F958',
            '&#x1F96B',
            '&#x1F35D',
            '&#x1F35C',
            '&#x1F372',
            '&#x1F35B',
            '&#x1F363',
            '&#x1F371',
            '&#x1F364',
            '&#x1F359',
            '&#x1F35A',
            '&#x1F358',
            '&#x1F365',
            '&#x1F960',
            '&#x1F362',
            '&#x1F361',
            '&#x1F367',
            '&#x1F368',
            '&#x1F366',
            '&#x1F967',
            '&#x1F370',
            '&#x1F382',
            '&#x1F96E',
            '&#x1F9C1',
            '&#x1F36E',
            '&#x1F36D',
            '&#x1F36C',
            '&#x1F36B',
            '&#x1F37F',
            '&#x1F9C2',
            '&#x1F369',
            '&#x1F95F',
            '&#x1F36A',
            '&#x1F330',
            '&#x1F95C',
            '&#x1F36F',
            '&#x1F95B',
            '&#x1F37C',
            '&#x1F375',
            '&#x1F964',
            '&#x1F376',
            '&#x1F37A',
            '&#x1F37B',
            '&#x1F942',
            '&#x1F377',
            '&#x1F943',
            '&#x1F378',
            '&#x1F379',
            '&#x1F37E',
            '&#x1F944',
            '&#x1F374',
            '&#x1F37D',
            '&#x1F963',
            '&#x1F961',
            '&#x1F962',
            '&#x1F600',
            '&#x1F603',
            '&#x1F604',
            '&#x1F601',
            '&#x1F606',
            '&#x1F605',
            '&#x1F602',
            '&#x1F923',
            '&#x263A',
            '&#x1F60A',
            '&#x1F607',
            '&#x1F642',
            '&#x1F643',
            '&#x1F609',
            '&#x1F60C',
            '&#x1F60D',
            '&#x1F618',
            '&#x1F970',
            '&#x1F617',
            '&#x1F619',
            '&#x1F61A',
            '&#x1F60B',
            '&#x1F61B',
            '&#x1F61D',
            '&#x1F61C',
            '&#x1F92A',
            '&#x1F928',
            '&#x1F9D0',
            '&#x1F913',
            '&#x1F60E',
            '&#x1F929',
            '&#x1F973',
            '&#x1F60F',
            '&#x1F612',
            '&#x1F61E',
            '&#x1F614',
            '&#x1F61F',
            '&#x1F615',
            '&#x1F641',
            '&#x1F623',
            '&#x1F616',
            '&#x1F62B',
            '&#x1F629',
            '&#x1F622',
            '&#x1F62D',
            '&#x1F624',
            '&#x1F620',
            '&#x1F621',
            '&#x1F92C',
            '&#x1F92F',
            '&#x1F633',
            '&#x1F631',
            '&#x1F628',
            '&#x1F630',
            '&#x1F975',
            '&#x1F976',
            '&#x1F97A',
            '&#x1F625',
            '&#x1F613',
            '&#x1F917',
            '&#x1F914',
            '&#x1F92D',
            '&#x1F92B',
            '&#x1F925',
            '&#x1F636',
            '&#x1F610',
            '&#x1F611',
            '&#x1F62C',
            '&#x1F644',
            '&#x1F62F',
            '&#x1F626',
            '&#x1F627',
            '&#x1F62E',
            '&#x1F632',
            '&#x1F634',
            '&#x1F924',
            '&#x1F62A',
            '&#x1F635',
            '&#x1F910',
            '&#x1F974',
            '&#x1F922',
            '&#x1F92E',
            '&#x1F927',
            '&#x1F637',
            '&#x1F912',
            '&#x1F915',
            '&#x1F911',
            '&#x1F920',
            '&#x1F608',
            '&#x1F47F',
            '&#x1F479',
            '&#x1F47A',
            '&#x1F921',
            '&#x1F4A9',
            '&#x1F47B',
            '&#x1F480',
            '&#x1F47D',
            '&#x1F47E',
            '&#x1F916',
            '&#x1F383',
            '&#x1F63A',
            '&#x1F638',
            '&#x1F639',
            '&#x1F63B',
            '&#x1F63C',
            '&#x1F63D',
            '&#x1F640',
            '&#x1F63F',
            '&#x1F63E',
            '&#x1F932',
            '&#x1F450',
            '&#x1F64C',
            '&#x1F44F',
            '&#x1F91D',
            '&#x1F44D',
            '&#x1F44E',
            '&#x1F44A',
            '&#x270A',
            '&#x1F91B',
            '&#x1F91C',
            '&#x1F91E',
            '&#x270C',
            '&#x1F91F',
            '&#x1F918',
            '&#x1F44C',
            '&#x1F448',
            '&#x1F449',
            '&#x1F446',
            '&#x1F447',
            '&#x261D',
            '&#x270B',
            '&#x1F91A',
            '&#x1F590',
            '&#x1F596',
            '&#x1F44B',
            '&#x1F919',
            '&#x1F4AA',
            '&#x1F9B5',
            '&#x1F9B6',
            '&#x1F595',
            '&#x270D',
            '&#x1F64F',
            '&#x1F48D',
            '&#x1F484',
            '&#x1F48B',
            '&#x1F444',
            '&#x1F445',
            '&#x1F442',
            '&#x1F443',
            '&#x1F463',
            '&#x1F440',
            '&#x1F9E0',
            '&#x1F9B4',
            '&#x1F9B7',
            '&#x1F5E3',
            '&#x1F464',
            '&#x1F465',
            '&#x1F476',
            '&#x1F467',
            '&#x1F9D2',
            '&#x1F466',
            '&#x1F469',
            '&#x1F9D1',
            '&#x1F468',
            '&#x1F471',
            '&#x1F9D4',
            '&#x1F475',
            '&#x1F9D3',
            '&#x1F474',
            '&#x1F472',
            '&#x1F473',
            '&#x1F9D5',
            '&#x1F46E',
            '&#x1F477',
            '&#x1F482',
            '&#x1F575',
            '&#x1F470',
            '&#x1F935',
            '&#x1F478',
            '&#x1F934',
            '&#x1F936',
            '&#x1F385',
            '&#x1F9B8',
            '&#x1F9B9',
            '&#x1F9D9',
            '&#x1F9DD',
            '&#x1F9DB',
            '&#x1F9DF',
            '&#x1F9DE',
            '&#x1F9DC',
            '&#x1F9DA',
            '&#x1F47C',
            '&#x1F930',
            '&#x1F931',
            '&#x1F647',
            '&#x1F481',
            '&#x1F645',
            '&#x1F646',
            '&#x1F64B',
            '&#x1F926',
            '&#x1F937',
            '&#x1F64E',
            '&#x1F64D',
            '&#x1F487',
            '&#x1F486',
            '&#x1F9D6',
            '&#x1F485',
            '&#x1F933',
            '&#x1F483',
            '&#x1F57A',
            '&#x1F46F',
            '&#x1F574',
            '&#x1F6B6',
            '&#x1F3C3',
            '&#x1F46B',
            '&#x1F46D',
            '&#x1F46C',
            '&#x1F491',
            '&#x1F48F',
            '&#x1F46A',
            '&#x1F9E5',
            '&#x1F45A',
            '&#x1F455',
            '&#x1F456',
            '&#x1F454',
            '&#x1F457',
            '&#x1F459',
            '&#x1F458',
            '&#x1F97C',
            '&#x1F460',
            '&#x1F461',
            '&#x1F462',
            '&#x1F45E',
            '&#x1F45F',
            '&#x1F97E',
            '&#x1F97F',
            '&#x1F9E6',
            '&#x1F9E4',
            '&#x1F9E3',
            '&#x1F3A9',
            '&#x1F9E2',
            '&#x1F452',
            '&#x1F393',
            '&#x26D1',
            '&#x1F451',
            '&#x1F45D',
            '&#x1F45B',
            '&#x1F45C',
            '&#x1F4BC',
            '&#x1F392',
            '&#x1F453',
            '&#x1F576',
            '&#x1F97D',
            '&#x1F302',
            '&#x1F9B0',
            '&#x1F9B1',
            '&#x1F9B3',
            '&#x1F9B2',
            '&#x1F1FF',
            '&#x1F1FE',
            '&#x1F1FD',
            '&#x1F1FC',
            '&#x1F1FB',
            '&#x1F1FA',
            '&#x1F1F9',
            '&#x1F1F8',
            '&#x1F1F7',
            '&#x1F1F6',
            '&#x1F1F5',
            '&#x1F1F4',
            '&#x1F1F3',
            '&#x1F1F2',
            '&#x1F1F1',
            '&#x1F1F0',
            '&#x1F1EF',
            '&#x1F1EE',
            '&#x1F1ED',
            '&#x1F1EC',
            '&#x1F1EB',
            '&#x1F1EA',
            '&#x1F1E9',
            '&#x1F1E8',
            '&#x1F1E7',
            '&#x1F1E6',
            '&#x1F697',
            '&#x1F695',
            '&#x1F699',
            '&#x1F68C',
            '&#x1F68E',
            '&#x1F3CE',
            '&#x1F693',
            '&#x1F691',
            '&#x1F692',
            '&#x1F690',
            '&#x1F69A',
            '&#x1F69B',
            '&#x1F69C',
            '&#x1F6F4',
            '&#x1F6B2',
            '&#x1F6F5',
            '&#x1F3CD',
            '&#x1F6A8',
            '&#x1F694',
            '&#x1F68D',
            '&#x1F698',
            '&#x1F696',
            '&#x1F6A1',
            '&#x1F6A0',
            '&#x1F69F',
            '&#x1F683',
            '&#x1F68B',
            '&#x1F69E',
            '&#x1F69D',
            '&#x1F684',
            '&#x1F685',
            '&#x1F688',
            '&#x1F682',
            '&#x1F686',
            '&#x1F687',
            '&#x1F68A',
            '&#x1F689',
            '&#x1F6EB',
            '&#x1F6EC',
            '&#x1F6E9',
            '&#x1F4BA',
            '&#x1F9F3',
            '&#x1F6F0',
            '&#x1F680',
            '&#x1F6F8',
            '&#x1F681',
            '&#x1F6F6',
            '&#x26F5',
            '&#x1F6A4',
            '&#x1F6E5',
            '&#x1F6F3',
            '&#x26F4',
            '&#x1F6A2',
            '&#x26FD',
            '&#x1F6A7',
            '&#x1F6A6',
            '&#x1F6A5',
            '&#x1F68F',
            '&#x1F5FA',
            '&#x1F5FF',
            '&#x1F5FD',
            '&#x1F5FC',
            '&#x1F3F0',
            '&#x1F3EF',
            '&#x1F3DF',
            '&#x1F3A1',
            '&#x1F3A2',
            '&#x1F3A0',
            '&#x26F2',
            '&#x26F1',
            '&#x1F3D6',
            '&#x1F3DD',
            '&#x1F3DC',
            '&#x1F30B',
            '&#x26F0',
            '&#x1F3D4',
            '&#x1F5FB',
            '&#x1F3D5',
            '&#x26FA',
            '&#x1F3E0',
            '&#x1F3E1',
            '&#x1F3D8',
            '&#x1F3DA',
            '&#x1F3D7',
            '&#x1F3ED',
            '&#x1F3E2',
            '&#x1F3EC',
            '&#x1F3E3',
            '&#x1F3E4',
            '&#x1F3E5',
            '&#x1F3E6',
            '&#x1F3E8',
            '&#x1F3EA',
            '&#x1F3EB',
            '&#x1F3E9',
            '&#x1F492',
            '&#x1F3DB',
            '&#x26EA',
            '&#x1F54C',
            '&#x1F54D',
            '&#x1F54B',
            '&#x26E9',
            '&#x1F6E4',
            '&#x1F6E3',
            '&#x1F5FE',
            '&#x1F391',
            '&#x1F3DE',
            '&#x1F305',
            '&#x1F304',
            '&#x1F320',
            '&#x1F387',
            '&#x1F386',
            '&#x1F9E8',
            '&#x1F307',
            '&#x1F306',
            '&#x1F3D9',
            '&#x1F303',
            '&#x1F30C',
            '&#x1F309',
            '&#x1F512',
            '&#x1F513',
            '&#x1F301',
            '&#x1F3F3',
            '&#x1F3F4',
            '&#x1F3C1',
            '&#x1F6A9',
            '&#x1F38C',
            '&#x1F3FB',
            '&#x1F3FC',
            '&#x1F3FD',
            '&#x1F3FE',
            '&#x1F3FF'
        ]
    };

    // Add all emoji in a dropdown
    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                emoji: 'Add an emoji'
            },
            pt_br: {
                emoji: 'Inserir emoji'
            },
            es: {
                emoji: 'Agrega un emoji'
            },
            da: {
                emoji: 'Tilføj et humørikon'
            },
            de: {
                emoji: 'Emoticon einfügen'
            },
            fr: {
                emoji: 'Ajouter un emoji'
            },
            zh_cn: {
                emoji: '添加表情'
            },
            ru: {
                emoji: 'Вставить emoji'
            },
            ja: {
                emoji: '絵文字の挿入'
            },
            tr: {
                emoji: 'Emoji ekle'
            },
            ko: {
                emoji: '이모지 넣기'
            },
        },
        // jshint camelcase:true
        plugins: {
            emoji: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.emoji = trumbowyg.o.plugins.emoji || defaultOptions;
                    var emojiBtnDef = {
                        dropdown: buildDropdown(trumbowyg)
                    };
                    trumbowyg.addBtnDef('emoji', emojiBtnDef);
                }
            }
        }
    });

    function buildDropdown(trumbowyg) {
        var dropdown = [];

        $.each(trumbowyg.o.plugins.emoji.emojiList, function (i, emoji) {
            if ($.isArray(emoji)) { // Custom emoji behaviour
                var emojiCode = emoji[0],
                    emojiUrl = emoji[1],
                    emojiHtml = '<img src="' + emojiUrl + '" alt="' + emojiCode + '">',
                    customEmojiBtnName = 'emoji-' + emojiCode.replace(/:/g, ''),
                    customEmojiBtnDef = {
                        hasIcon: false,
                        text: emojiHtml,
                        fn: function () {
                            trumbowyg.execCmd('insertImage', emojiUrl, false, true);
                            return true;
                        }
                    };

                trumbowyg.addBtnDef(customEmojiBtnName, customEmojiBtnDef);
                dropdown.push(customEmojiBtnName);
            } else { // Default behaviour
                var btn = emoji.replace(/:/g, ''),
                    defaultEmojiBtnName = 'emoji-' + btn,
                    defaultEmojiBtnDef = {
                        text: emoji,
                        fn: function () {
                            var encodedEmoji = String.fromCodePoint(emoji.replace('&#', '0'));
                            trumbowyg.execCmd('insertText', encodedEmoji);
                            return true;
                        }
                    };

                trumbowyg.addBtnDef(defaultEmojiBtnName, defaultEmojiBtnDef);
                dropdown.push(defaultEmojiBtnName);
            }
        });

        return dropdown;
    }
})(jQuery);

(function ($) {
    'use strict';

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                fontFamily: 'Font'
            },
            es: {
                fontFamily: 'Fuente'
            },
            da: {
                fontFamily: 'Skrifttype'
            },
            fr: {
                fontFamily: 'Police'
            },
            de: {
                fontFamily: 'Schriftart'
            },
            nl: {
                fontFamily: 'Lettertype'
            },
            tr: {
                fontFamily: 'Yazı Tipi'
            },
            zh_tw: {
                fontFamily: '字體',
            },
            pt_br: {
                fontFamily: 'Fonte',
            },
            ko: {
                fontFamily: '글꼴'
            },
        }
    });
    // jshint camelcase:true

    var defaultOptions = {
        fontList: [
            {name: 'Arial', family: 'Arial, Helvetica, sans-serif'},
            {name: 'Arial Black', family: 'Arial Black, Gadget, sans-serif'},
            {name: 'Comic Sans', family: 'Comic Sans MS, Textile, cursive, sans-serif'},
            {name: 'Courier New', family: 'Courier New, Courier, monospace'},
            {name: 'Georgia', family: 'Georgia, serif'},
            {name: 'Impact', family: 'Impact, Charcoal, sans-serif'},
            {name: 'Lucida Console', family: 'Lucida Console, Monaco, monospace'},
            {name: 'Lucida Sans', family: 'Lucida Sans Uncide, Lucida Grande, sans-serif'},
            {name: 'Palatino', family: 'Palatino Linotype, Book Antiqua, Palatino, serif'},
            {name: 'Tahoma', family: 'Tahoma, Geneva, sans-serif'},
            {name: 'Times New Roman', family: 'Times New Roman, Times, serif'},
            {name: 'Trebuchet', family: 'Trebuchet MS, Helvetica, sans-serif'},
            {name: 'Verdana', family: 'Verdana, Geneva, sans-serif'}
        ]
    };

    // Add dropdown with web safe fonts
    $.extend(true, $.trumbowyg, {
        plugins: {
            fontfamily: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.fontfamily = $.extend({},
                      defaultOptions,
                      trumbowyg.o.plugins.fontfamily || {}
                    );

                    trumbowyg.addBtnDef('fontfamily', {
                        dropdown: buildDropdown(trumbowyg),
                        hasIcon: false,
                        text: trumbowyg.lang.fontFamily
                    });
                }
            }
        }
    });

    function buildDropdown(trumbowyg) {
        var dropdown = [];

        $.each(trumbowyg.o.plugins.fontfamily.fontList, function (index, font) {
            trumbowyg.addBtnDef('fontfamily_' + index, {
                title: '<span style="font-family: ' + font.family + ';">' + font.name + '</span>',
                hasIcon: false,
                fn: function () {
                    trumbowyg.execCmd('fontName', font.family, true);
                }
            });
            dropdown.push('fontfamily_' + index);
        });

        return dropdown;
    }
})(jQuery);

(function ($) {
    'use strict';

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                fontsize: 'Font size',
                fontsizes: {
                    'x-small': 'Extra small',
                    'small': 'Small',
                    'medium': 'Regular',
                    'large': 'Large',
                    'x-large': 'Extra large',
                    'custom': 'Custom'
                },
                fontCustomSize: {
                    title: 'Custom Font Size',
                    label: 'Font Size',
                    value: '48px'
                }
            },
            es: {
                fontsize: 'Tamaño de Fuente',
                fontsizes: {
                    'x-small': 'Extra pequeña',
                    'small': 'Pegueña',
                    'medium': 'Regular',
                    'large': 'Grande',
                    'x-large': 'Extra Grande',
                    'custom': 'Customizada'
                },
                fontCustomSize: {
                    title: 'Tamaño de Fuente Customizada',
                    label: 'Tamaño de Fuente',
                    value: '48px'
                }
            },
            da: {
                fontsize: 'Skriftstørrelse',
                fontsizes: {
                    'x-small': 'Ekstra lille',
                    'small': 'Lille',
                    'medium': 'Normal',
                    'large': 'Stor',
                    'x-large': 'Ekstra stor',
                    'custom': 'Brugerdefineret'
                }
            },
            fr: {
                fontsize: 'Taille de la police',
                fontsizes: {
                    'x-small': 'Très petit',
                    'small': 'Petit',
                    'medium': 'Normal',
                    'large': 'Grand',
                    'x-large': 'Très grand',
                    'custom': 'Taille personnalisée'
                },
                fontCustomSize: {
                    title: 'Taille de police personnalisée',
                    label: 'Taille de la police',
                    value: '48px'
                }
            },
            de: {
                fontsize: 'Schriftgröße',
                fontsizes: {
                    'x-small': 'Sehr klein',
                    'small': 'Klein',
                    'medium': 'Normal',
                    'large': 'Groß',
                    'x-large': 'Sehr groß',
                    'custom': 'Benutzerdefiniert'
                },
                fontCustomSize: {
                    title: 'Benutzerdefinierte Schriftgröße',
                    label: 'Schriftgröße',
                    value: '48px'
                }
            },
            nl: {
                fontsize: 'Lettergrootte',
                fontsizes: {
                    'x-small': 'Extra klein',
                    'small': 'Klein',
                    'medium': 'Normaal',
                    'large': 'Groot',
                    'x-large': 'Extra groot',
                    'custom': 'Tilpasset'
                }
            },
            tr: {
                fontsize: 'Yazı Boyutu',
                fontsizes: {
                    'x-small': 'Çok Küçük',
                    'small': 'Küçük',
                    'medium': 'Normal',
                    'large': 'Büyük',
                    'x-large': 'Çok Büyük',
                    'custom': 'Görenek'
                }
            },
            zh_tw: {
                fontsize: '字體大小',
                fontsizes: {
                    'x-small': '最小',
                    'small': '小',
                    'medium': '中',
                    'large': '大',
                    'x-large': '最大',
                    'custom': '自訂大小',
                },
                fontCustomSize: {
                    title: '自訂義字體大小',
                    label: '字體大小',
                    value: '48px'
                }
            },
            pt_br: {
                fontsize: 'Tamanho da fonte',
                fontsizes: {
                    'x-small': 'Extra pequeno',
                    'small': 'Pequeno',
                    'regular': 'Médio',
                    'large': 'Grande',
                    'x-large': 'Extra grande',
                    'custom': 'Personalizado'
                },
                fontCustomSize: {
                    title: 'Tamanho de Fonte Personalizado',
                    label: 'Tamanho de Fonte',
                    value: '48px'
                }
            },
            it: {
                fontsize: 'Dimensioni del testo',
                fontsizes: {
                    'x-small': 'Molto piccolo',
                    'small': 'piccolo',
                    'regular': 'normale',
                    'large': 'grande',
                    'x-large': 'Molto grande',
                    'custom': 'Personalizzato'
                },
                fontCustomSize: {
                    title: 'Dimensioni del testo personalizzato',
                    label: 'Dimensioni del testo',
                    value: '48px'
                }
            },
            ko: {
                fontsize: '글꼴 크기',
                fontsizes: {
                    'x-small': '아주 작게',
                    'small': '작게',
                    'medium': '보통',
                    'large': '크게',
                    'x-large': '아주 크게',
                    'custom': '사용자 지정'
                },
                fontCustomSize: {
                    title: '사용자 지정 글꼴 크기',
                    label: '글꼴 크기',
                    value: '48px'
                }
            },
        }
    });
    // jshint camelcase:true

    var defaultOptions = {
        sizeList: [
            'x-small',
            'small',
            'medium',
            'large',
            'x-large'
        ],
        allowCustomSize: true
    };

    // Add dropdown with font sizes
    $.extend(true, $.trumbowyg, {
        plugins: {
            fontsize: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.fontsize = $.extend({},
                      defaultOptions,
                      trumbowyg.o.plugins.fontsize || {}
                    );

                    trumbowyg.addBtnDef('fontsize', {
                        dropdown: buildDropdown(trumbowyg)
                    });
                }
            }
        }
    });

    function setFontSize(trumbowyg, size) {
        trumbowyg.$ed.focus();
        trumbowyg.saveRange();
        var text = trumbowyg.range.startContainer.parentElement;
        var selectedText = trumbowyg.getRangeText();
        if ($(text).html() === selectedText) {
            $(text).css('font-size', size);
        } else {
            trumbowyg.range.deleteContents();
            var html = '<span style="font-size: ' + size + ';">' + selectedText + '</span>';
            var node = $(html)[0];
            trumbowyg.range.insertNode(node);
        }
        trumbowyg.restoreRange();
    }

    function buildDropdown(trumbowyg) {
        var dropdown = [];

        $.each(trumbowyg.o.plugins.fontsize.sizeList, function (index, size) {
            trumbowyg.addBtnDef('fontsize_' + size, {
                text: '<span style="font-size: ' + size + ';">' + (trumbowyg.lang.fontsizes[size] || size) + '</span>',
                hasIcon: false,
                fn: function () {
                    setFontSize(trumbowyg, size);
                }
            });
            dropdown.push('fontsize_' + size);
        });

        if (trumbowyg.o.plugins.fontsize.allowCustomSize) {
            var customSizeButtonName = 'fontsize_custom';
            var customSizeBtnDef = {
                fn: function () {
                    trumbowyg.openModalInsert(trumbowyg.lang.fontCustomSize.title,
                        {
                            size: {
                                label: trumbowyg.lang.fontCustomSize.label,
                                value: trumbowyg.lang.fontCustomSize.value
                            }
                        },
                        function (form) {
                            setFontSize(trumbowyg, form.size);
                            return true;
                        }
                    );
                },
                text: '<span style="font-size: medium;">' + trumbowyg.lang.fontsizes.custom + '</span>',
                hasIcon: false
            };
            trumbowyg.addBtnDef(customSizeButtonName, customSizeBtnDef);
            dropdown.push(customSizeButtonName);
        }

        return dropdown;
    }
})(jQuery);

/*/* ===========================================================
 * trumbowyg.history.js v1.0
 * history plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Sven Dunemann [dunemann@forelabs.eu]
 */

(function ($) {
    'use strict';
    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            de: {
                history: {
                    redo: 'Wiederholen',
                    undo: 'Rückgängig'
                }
            },
            en: {
                history: {
                    redo: 'Redo',
                    undo: 'Undo'
                }
            },
            da: {
                history: {
                    redo: 'Annuller fortryd',
                    undo: 'Fortryd'
                }
            },
            fr: {
                history: {
                    redo: 'Annuler',
                    undo: 'Rétablir'
                }
            },
            zh_tw: {
               history: {
                   redo: '重做',
                   undo: '復原'
               }
            },
            pt_br: {
                history: {
                    redo: 'Refazer',
                    undo: 'Desfazer'
                }
            },
            ko: {
                history: {
                    redo: '다시 실행',
                    undo: '되돌리기'
                }
            },
            // jshint camelcase:true
        },
        plugins: {
            history: {
                init: function (t) {
                    t.o.plugins.history = $.extend(true, {
                        _stack: [],
                        _index: -1,
                        _focusEl: undefined
                    }, t.o.plugins.history || {});

                    var btnBuildDefRedo = {
                        title: t.lang.history.redo,
                        ico: 'redo',
                        key: 'Y',
                        fn: function () {
                            if (t.o.plugins.history._index < t.o.plugins.history._stack.length - 1) {
                                t.o.plugins.history._index += 1;
                                var index = t.o.plugins.history._index;
                                var newState = t.o.plugins.history._stack[index];

                                t.execCmd('html', newState);
                                // because of some semantic optimisations we have to save the state back
                                // to history
                                t.o.plugins.history._stack[index] = t.$ed.html();

                                carretToEnd();
                                toggleButtonStates();
                            }
                        }
                    };

                    var btnBuildDefUndo = {
                        title: t.lang.history.undo,
                        ico: 'undo',
                        key: 'Z',
                        fn: function () {
                            if (t.o.plugins.history._index > 0) {
                                t.o.plugins.history._index -= 1;
                                var index = t.o.plugins.history._index,
                                    newState = t.o.plugins.history._stack[index];

                                t.execCmd('html', newState);
                                // because of some semantic optimisations we have to save the state back
                                // to history
                                t.o.plugins.history._stack[index] = t.$ed.html();

                                carretToEnd();
                                toggleButtonStates();
                            }
                        }
                    };

                    var pushToHistory = function () {
                        var index = t.o.plugins.history._index,
                            stack = t.o.plugins.history._stack,
                            latestState = stack.slice(-1)[0] || '<p></p>',
                            prevState = stack[index],
                            newState = t.$ed.html(),
                            focusEl = t.doc.getSelection().focusNode,
                            focusElText = '',
                            latestStateTagsList,
                            newStateTagsList,
                            prevFocusEl = t.o.plugins.history._focusEl;

                        latestStateTagsList = $('<div>' + latestState + '</div>').find('*').map(function () {
                            return this.localName;
                        });
                        newStateTagsList = $('<div>' + newState + '</div>').find('*').map(function () {
                            return this.localName;
                        });
                        if (focusEl) {
                            t.o.plugins.history._focusEl = focusEl;
                            focusElText = focusEl.outerHTML || focusEl.textContent;
                        }

                        if (newState !== prevState) {
                            // a new stack entry is defined when current insert ends on a whitespace character
                            // or count of node elements has been changed
                            // or focused element differs from previous one
                            if (focusElText.slice(-1).match(/\s/) ||
                                !arraysAreIdentical(latestStateTagsList, newStateTagsList) ||
                                t.o.plugins.history._index <= 0 || focusEl !== prevFocusEl)
                            {
                                t.o.plugins.history._index += 1;
                                // remove newer entries in history when something new was added
                                // because timeline was changes with interaction
                                t.o.plugins.history._stack = stack.slice(
                                    0, t.o.plugins.history._index
                                );
                                // now add new state to modified history
                                t.o.plugins.history._stack.push(newState);
                            } else {
                                // modify last stack entry
                                t.o.plugins.history._stack[index] = newState;
                            }

                            toggleButtonStates();
                        }
                    };

                    var toggleButtonStates = function () {
                        var index = t.o.plugins.history._index,
                            stackSize = t.o.plugins.history._stack.length,
                            undoState = (index > 0),
                            redoState = (stackSize !== 0 && index !== stackSize - 1);

                        toggleButtonState('historyUndo', undoState);
                        toggleButtonState('historyRedo', redoState);
                    };

                    var toggleButtonState = function (btn, enable) {
                        var button = t.$box.find('.trumbowyg-' + btn + '-button');

                        if (enable) {
                            button.removeClass('trumbowyg-disable');
                        } else if (!button.hasClass('trumbowyg-disable')) {
                            button.addClass('trumbowyg-disable');
                        }
                    };

                    var arraysAreIdentical = function (a, b) {
                        if (a === b) {
                            return true;
                        }
                        if (a == null || b == null) {
                            return false;
                        }
                        if (a.length !== b.length) {
                            return false;
                        }

                        for (var i = 0; i < a.length; i += 1) {
                            if (a[i] !== b[i]) {
                                return false;
                            }
                        }
                        return true;
                    };

                    var carretToEnd = function () {
                        var node = t.doc.getSelection().focusNode,
                            range = t.doc.createRange();

                        if (node.childNodes.length > 0) {
                            range.setStartAfter(node.childNodes[node.childNodes.length - 1]);
                            range.setEndAfter(node.childNodes[node.childNodes.length - 1]);
                            t.doc.getSelection().removeAllRanges();
                            t.doc.getSelection().addRange(range);
                        }
                    };

                    t.$c.on('tbwinit tbwchange', pushToHistory);

                    t.addBtnDef('historyRedo', btnBuildDefRedo);
                    t.addBtnDef('historyUndo', btnBuildDefUndo);
                }
            }
        }
    });
})(jQuery);

/* ===========================================================
 * trumbowyg.noembed.js v1.0
 * noEmbed plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Jake Johns (jakejohns)
 */

(function ($) {
    'use strict';

    var defaultOptions = {
        proxy: 'https://noembed.com/embed?nowrap=on',
        urlFiled: 'url',
        data: [],
        success: undefined,
        error: undefined
    };

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                noembed: 'Noembed',
                noembedError: 'Error'
            },
            da: {
                noembedError: 'Fejl'
            },
            sk: {
                noembedError: 'Chyba'
            },
            fr: {
                noembedError: 'Erreur'
            },
            cs: {
                noembedError: 'Chyba'
            },
            ru: {
                noembedError: 'Ошибка'
            },
            ja: {
                noembedError: 'エラー'
            },
            tr: {
                noembedError: 'Hata'
            },
            zh_tw: {
                noembed: '插入影片',
                noembedError: '錯誤'
            },
            pt_br: {
                noembed: 'Incorporar',
                noembedError: 'Erro'
            },
            ko: {
                noembed: 'oEmbed 넣기',
                noembedError: '에러'
            },
            // jshint camelcase:true
        },

        plugins: {
            noembed: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.noembed = $.extend(true, {}, defaultOptions, trumbowyg.o.plugins.noembed || {});

                    var btnDef = {
                        fn: function () {
                            var $modal = trumbowyg.openModalInsert(
                                // Title
                                trumbowyg.lang.noembed,

                                // Fields
                                {
                                    url: {
                                        label: 'URL',
                                        required: true
                                    }
                                },

                                // Callback
                                function (data) {
                                    $.ajax({
                                        url: trumbowyg.o.plugins.noembed.proxy,
                                        type: 'GET',
                                        data: data,
                                        cache: false,
                                        dataType: 'json',

                                        success: trumbowyg.o.plugins.noembed.success || function (data) {
                                            if (data.html) {
                                                trumbowyg.execCmd('insertHTML', data.html);
                                                setTimeout(function () {
                                                    trumbowyg.closeModal();
                                                }, 250);
                                            } else {
                                                trumbowyg.addErrorOnModalField(
                                                    $('input[type=text]', $modal),
                                                    data.error
                                                );
                                            }
                                        },
                                        error: trumbowyg.o.plugins.noembed.error || function () {
                                            trumbowyg.addErrorOnModalField(
                                                $('input[type=text]', $modal),
                                                trumbowyg.lang.noembedError
                                            );
                                        }
                                    });
                                }
                            );
                        }
                    };

                    trumbowyg.addBtnDef('noembed', btnDef);
                }
            }
        }
    });
})(jQuery);

/* ===========================================================
 * trumbowyg.table.custom.js v2.0
 * Table plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Sven Dunemann [dunemann@forelabs.eu]
 */

(function ($) {
    'use strict';

    var defaultOptions = {
        rows: 8,
        columns: 8,
        styler: 'table'
    };

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                table: 'Insert table',
                tableAddRow: 'Add row',
                tableAddRowAbove: 'Add row above',
                tableAddColumnLeft: 'Add column to the left',
                tableAddColumn: 'Add column to the right',
                tableDeleteRow: 'Delete row',
                tableDeleteColumn: 'Delete column',
                tableDestroy: 'Delete table',
                error: 'Error'
            },
            da: {
                table: 'Indsæt tabel',
                tableAddRow: 'Tilføj række',
                tableAddRowAbove: 'Tilføj række',
                tableAddColumnLeft: 'Tilføj kolonne',
                tableAddColumn: 'Tilføj kolonne',
                tableDeleteRow: 'Slet række',
                tableDeleteColumn: 'Slet kolonne',
                tableDestroy: 'Slet tabel',
                error: 'Fejl'
            },
            de: {
                table: 'Tabelle einfügen',
                tableAddRow: 'Zeile hinzufügen',
                tableAddRowAbove: 'Zeile hinzufügen',
                tableAddColumnLeft: 'Spalte hinzufügen',
                tableAddColumn: 'Spalte hinzufügen',
                tableDeleteRow: 'Zeile löschen',
                tableDeleteColumn: 'Spalte löschen',
                tableDestroy: 'Tabelle löschen',
                error: 'Error'
            },
            sk: {
                table: 'Vytvoriť tabuľky',
                tableAddRow: 'Pridať riadok',
                tableAddRowAbove: 'Pridať riadok',
                tableAddColumnLeft: 'Pridať stĺpec',
                tableAddColumn: 'Pridať stĺpec',
                error: 'Chyba'
            },
            fr: {
                table: 'Insérer un tableau',
                tableAddRow: 'Ajouter des lignes',
                tableAddRowAbove: 'Ajouter des lignes',
                tableAddColumnLeft: 'Ajouter des colonnes',
                tableAddColumn: 'Ajouter des colonnes',
                tableDeleteRow: 'Effacer la ligne',
                tableDeleteColumn: 'Effacer la colonne',
                tableDestroy: 'Effacer le tableau',
                error: 'Erreur'
            },
            cs: {
                table: 'Vytvořit příkaz Table',
                tableAddRow: 'Přidat řádek',
                tableAddRowAbove: 'Přidat řádek',
                tableAddColumnLeft: 'Přidat sloupec',
                tableAddColumn: 'Přidat sloupec',
                error: 'Chyba'
            },
            ru: {
                table: 'Вставить таблицу',
                tableAddRow: 'Добавить строку',
                tableAddRowAbove: 'Добавить строку',
                tableAddColumnLeft: 'Добавить столбец',
                tableAddColumn: 'Добавить столбец',
                tableDeleteRow: 'Удалить строку',
                tableDeleteColumn: 'Удалить столбец',
                tableDestroy: 'Удалить таблицу',
                error: 'Ошибка'
            },
            ja: {
                table: '表の挿入',
                tableAddRow: '行の追加',
                tableAddRowAbove: '行の追加',
                tableAddColumnLeft: '列の追加',
                tableAddColumn: '列の追加',
                error: 'エラー'
            },
            tr: {
                table: 'Tablo ekle',
                tableAddRow: 'Satır ekle',
                tableAddRowAbove: 'Satır ekle',
                tableAddColumnLeft: 'Kolon ekle',
                tableAddColumn: 'Kolon ekle',
                error: 'Hata'
            },
            zh_tw: {
                table: '插入表格',
                tableAddRow: '加入行',
                tableAddRowAbove: '加入行',
                tableAddColumnLeft: '加入列',
                tableAddColumn: '加入列',
                tableDeleteRow: '刪除行',
                tableDeleteColumn: '刪除列',
                tableDestroy: '刪除表格',
                error: '錯誤'
            },
            id: {
                table: 'Sisipkan tabel',
                tableAddRow: 'Sisipkan baris',
                tableAddRowAbove: 'Sisipkan baris',
                tableAddColumnLeft: 'Sisipkan kolom',
                tableAddColumn: 'Sisipkan kolom',
                tableDeleteRow: 'Hapus baris',
                tableDeleteColumn: 'Hapus kolom',
                tableDestroy: 'Hapus tabel',
                error: 'Galat'
            },
            pt_br: {
                table: 'Inserir tabela',
                tableAddRow: 'Adicionar linha',
                tableAddRowAbove: 'Adicionar linha',
                tableAddColumnLeft: 'Adicionar coluna',
                tableAddColumn: 'Adicionar coluna',
                tableDeleteRow: 'Deletar linha',
                tableDeleteColumn: 'Deletar coluna',
                tableDestroy: 'Deletar tabela',
                error: 'Erro'
            },
            ko: {
                table: '표 넣기',
                tableAddRow: '줄 추가',
                tableAddRowAbove: '줄 추가',
                tableAddColumnLeft: '칸 추가',
                tableAddColumn: '칸 추가',
                tableDeleteRow: '줄 삭제',
                tableDeleteColumn: '칸 삭제',
                tableDestroy: '표 지우기',
                error: '에러'
            },
            // jshint camelcase:true
        },

        plugins: {
            table: {
                init: function (t) {
                    t.o.plugins.table = $.extend(true, {}, defaultOptions, t.o.plugins.table || {});

                    var buildButtonDef = {
                        fn: function () {
                            t.saveRange();

                            var btnName = 'table';

                            var dropdownPrefix = t.o.prefix + 'dropdown',
                                dropdownOptions = { // the dropdown
                                    class: dropdownPrefix + '-' + btnName + ' ' + dropdownPrefix + ' ' + t.o.prefix + 'fixed-top'
                                };
                            dropdownOptions['data-' + dropdownPrefix] = btnName;
                            var $dropdown = $('<div/>', dropdownOptions);

                            if (t.$box.find('.' + dropdownPrefix + '-' + btnName).length === 0) {
                                t.$box.append($dropdown.hide());
                            } else {
                                $dropdown = t.$box.find('.' + dropdownPrefix + '-' + btnName);
                            }

                            // clear dropdown
                            $dropdown.html('');

                            // when active table show AddRow / AddColumn
                            if (t.$box.find('.' + t.o.prefix + 'table-button').hasClass(t.o.prefix + 'active-button')) {
                                $dropdown.append(t.buildSubBtn('tableAddRowAbove'));
                                $dropdown.append(t.buildSubBtn('tableAddRow'));
                                $dropdown.append(t.buildSubBtn('tableAddColumnLeft'));
                                $dropdown.append(t.buildSubBtn('tableAddColumn'));
                                $dropdown.append(t.buildSubBtn('tableDeleteRow'));
                                $dropdown.append(t.buildSubBtn('tableDeleteColumn'));
                                $dropdown.append(t.buildSubBtn('tableDestroy'));
                            } else {
                                var tableSelect = $('<table/>');
                                $('<tbody/>').appendTo(tableSelect);
                                for (var i = 0; i < t.o.plugins.table.rows; i += 1) {
                                    var row = $('<tr/>').appendTo(tableSelect);
                                    for (var j = 0; j < t.o.plugins.table.columns; j += 1) {
                                        $('<td/>').appendTo(row);
                                    }
                                }
                                tableSelect.find('td').on('mouseover', tableAnimate);
                                tableSelect.find('td').on('mousedown', tableBuild);

                                $dropdown.append(tableSelect);
                                $dropdown.append($('<div class="trumbowyg-table-size">1x1</div>'));
                            }

                            t.dropdown(btnName);
                        }
                    };

                    var tableAnimate = function(columnEvent) {
                        var column = $(columnEvent.target),
                            table = column.closest('table'),
                            colIndex = this.cellIndex,
                            rowIndex = this.parentNode.rowIndex;

                        // reset all columns
                        table.find('td').removeClass('active');

                        for (var i = 0; i <= rowIndex; i += 1) {
                            for (var j = 0; j <= colIndex; j += 1) {
                                table.find('tr:nth-of-type('+(i+1)+')').find('td:nth-of-type('+(j+1)+')').addClass('active');
                            }
                        }

                        // set label
                        table.next('.trumbowyg-table-size').html((colIndex+1) + 'x' + (rowIndex+1));
                    };

                    var tableBuild = function() {
                        t.saveRange();

                        var tabler = $('<table/>');
                        $('<tbody/>').appendTo(tabler);
                        if (t.o.plugins.table.styler) {
                            tabler.attr('class', t.o.plugins.table.styler);
                        }

                        var colIndex = this.cellIndex,
                            rowIndex = this.parentNode.rowIndex;

                        for (var i = 0; i <= rowIndex; i += 1) {
                            var row = $('<tr></tr>').appendTo(tabler);
                            for (var j = 0; j <= colIndex; j += 1) {
                                $('<td/>').appendTo(row);
                            }
                        }

                        t.range.deleteContents();
                        t.range.insertNode(tabler[0]);
                        t.$c.trigger('tbwchange');
                    };

                    var addRow = {
                        title: t.lang.tableAddRow,
                        text: t.lang.tableAddRow,
                        ico: 'row-below',

                        fn: function () {
                            t.saveRange();

                            var node = t.doc.getSelection().focusNode;
                            var focusedRow = $(node).closest('tr');
                            var table = $(node).closest('table');

                            if(table.length > 0) {
                                var row = $('<tr/>');
                                // add columns according to current columns count
                                for (var i = 0; i < table.find('tr')[0].childElementCount; i += 1) {
                                    $('<td/>').appendTo(row);
                                }
                                // add row to table
                                focusedRow.after(row);
                            }

                            t.syncCode();
                        }
                    };

                    var addRowAbove = {
                        title: t.lang.tableAddRowAbove,
                        text: t.lang.tableAddRowAbove,
                        ico: 'row-above',

                        fn: function () {
                            t.saveRange();

                            var node = t.doc.getSelection().focusNode;
                            var focusedRow = $(node).closest('tr');
                            var table = $(node).closest('table');

                            if(table.length > 0) {
                                var row = $('<tr/>');
                                // add columns according to current columns count
                                for (var i = 0; i < table.find('tr')[0].childElementCount; i += 1) {
                                    $('<td/>').appendTo(row);
                                }
                                // add row to table
                                focusedRow.before(row);
                            }

                            t.syncCode();
                        }
                    };

                    var addColumn = {
                        title: t.lang.tableAddColumn,
                        text: t.lang.tableAddColumn,
                        ico: 'col-right',

                        fn: function () {
                            t.saveRange();

                            var node = t.doc.getSelection().focusNode;
                            var focusedCol = $(node).closest('td');
                            var table = $(node).closest('table');
                            var focusedColIdx = focusedCol.index();

                            if(table.length > 0) {
                                $(table).find('tr').each(function() {
                                    $($(this).children()[focusedColIdx]).after('<td></td>');
                                });
                            }

                            t.syncCode();
                        }
                    };

                    var addColumnLeft = {
                        title: t.lang.tableAddColumnLeft,
                        text: t.lang.tableAddColumnLeft,
                        ico: 'col-left',

                        fn: function () {
                            t.saveRange();

                            var node = t.doc.getSelection().focusNode;
                            var focusedCol = $(node).closest('td');
                            var table = $(node).closest('table');
                            var focusedColIdx = focusedCol.index();

                            if(table.length > 0) {
                                $(table).find('tr').each(function() {
                                    $($(this).children()[focusedColIdx]).before('<td></td>');
                                });
                            }

                            t.syncCode();
                        }
                    };

                    var destroy = {
                        title: t.lang.tableDestroy,
                        text: t.lang.tableDestroy,
                        ico: 'table-delete',

                        fn: function () {
                            t.saveRange();

                            var node = t.doc.getSelection().focusNode,
                                table = $(node).closest('table');

                            table.remove();

                            t.syncCode();
                        }
                    };

                    var deleteRow = {
                        title: t.lang.tableDeleteRow,
                        text: t.lang.tableDeleteRow,
                        ico: 'row-delete',

                        fn: function () {
                            t.saveRange();

                            var node = t.doc.getSelection().focusNode,
                                row = $(node).closest('tr');

                            row.remove();

                            t.syncCode();
                        }
                    };

                    var deleteColumn = {
                        title: t.lang.tableDeleteColumn,
                        text: t.lang.tableDeleteColumn,
                        ico: 'col-delete',

                        fn: function () {
                            t.saveRange();

                            var node = t.doc.getSelection().focusNode,
                                table = $(node).closest('table'),
                                td = $(node).closest('td'),
                                cellIndex = td.index();

                            $(table).find('tr').each(function() {
                                $(this).find('td:eq(' + cellIndex + ')').remove();
                            });

                            t.syncCode();
                        }
                    };

                    t.addBtnDef('table', buildButtonDef);
                    t.addBtnDef('tableAddRowAbove', addRowAbove);
                    t.addBtnDef('tableAddRow', addRow);
                    t.addBtnDef('tableAddColumnLeft', addColumnLeft);
                    t.addBtnDef('tableAddColumn', addColumn);
                    t.addBtnDef('tableDeleteRow', deleteRow);
                    t.addBtnDef('tableDeleteColumn', deleteColumn);
                    t.addBtnDef('tableDestroy', destroy);
                }
            }
        }
    });
})(jQuery);
/* ===========================================================
 * trumbowyg.upload.js v1.2
 * Upload plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Alexandre Demode (Alex-D)
 *          Twitter : @AlexandreDemode
 *          Website : alex-d.fr
 * Mod by : Aleksandr-ru
 *          Twitter : @Aleksandr_ru
 *          Website : aleksandr.ru
 */

(function ($) {
    'use strict';

    var defaultOptions = {
        serverPath: '',
        fileFieldName: 'fileToUpload',
        data: [],                       // Additional data for ajax [{name: 'key', value: 'value'}]
        headers: {},                    // Additional headers
        xhrFields: {},                  // Additional fields
        urlPropertyName: 'file',        // How to get url from the json response (for instance 'url' for {url: ....})
        statusPropertyName: 'success',  // How to get status from the json response 
        success: undefined,             // Success callback: function (data, trumbowyg, $modal, values) {}
        error: undefined,               // Error callback: function () {}
        imageWidthModalEdit: false      // Add ability to edit image width
    };

    function getDeep(object, propertyParts) {
        var mainProperty = propertyParts.shift(),
            otherProperties = propertyParts;

        if (object !== null) {
            if (otherProperties.length === 0) {
                return object[mainProperty];
            }

            if (typeof object === 'object') {
                return getDeep(object[mainProperty], otherProperties);
            }
        }
        return object;
    }

    addXhrProgressEvent();

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                upload: 'Upload',
                file: 'File',
                uploadError: 'Error'
            },
            da: {
                upload: 'Upload',
                file: 'Fil',
                uploadError: 'Fejl'
            },
            de: {
                upload: 'Hochladen',
                file: 'Datei',
                uploadError: 'Fehler'
            },
            sk: {
                upload: 'Nahrať',
                file: 'Súbor',
                uploadError: 'Chyba'
            },
            fr: {
                upload: 'Envoi',
                file: 'Fichier',
                uploadError: 'Erreur'
            },
            cs: {
                upload: 'Nahrát obrázek',
                file: 'Soubor',
                uploadError: 'Chyba'
            },
            zh_cn: {
                upload: '上传',
                file: '文件',
                uploadError: '错误'
            },
            zh_tw: {
                upload: '上傳',
                file: '文件',
                uploadError: '錯誤'
            },
            ru: {
                upload: 'Загрузка',
                file: 'Файл',
                uploadError: 'Ошибка'
            },
            ja: {
                upload: 'アップロード',
                file: 'ファイル',
                uploadError: 'エラー'
            },
            pt_br: {
                upload: 'Enviar do local',
                file: 'Arquivo',
                uploadError: 'Erro'
            },
            tr: {
                upload: 'Yükle',
                file: 'Dosya',
                uploadError: 'Hata'
            },
            ko: {
                upload: '그림 올리기',
                file: '파일',
                uploadError: '에러'
            },
        },
        // jshint camelcase:true

        plugins: {
            upload: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.upload = $.extend(true, {}, defaultOptions, trumbowyg.o.plugins.upload || {});
                    var btnDef = {
                        fn: function () {
                            trumbowyg.saveRange();

                            var file,
                                prefix = trumbowyg.o.prefix;

                            var fields = {
                                file: {
                                    type: 'file',
                                    required: true,
                                    attributes: {
                                        accept: 'image/*'
                                    }
                                },
                                alt: {
                                    label: 'description',
                                    value: trumbowyg.getRangeText()
                                }
                            };

                            if (trumbowyg.o.plugins.upload.imageWidthModalEdit) {
                                fields.width = {
                                    value: ''
                                };
                            }

                            var $modal = trumbowyg.openModalInsert(
                                // Title
                                trumbowyg.lang.upload,

                                // Fields
                                fields,

                                // Callback
                                function (values) {
                                    var data = new FormData();
                                    data.append(trumbowyg.o.plugins.upload.fileFieldName, file);

                                    trumbowyg.o.plugins.upload.data.map(function (cur) {
                                        data.append(cur.name, cur.value);
                                    });

                                    $.map(values, function (curr, key) {
                                        if (key !== 'file') {
                                            data.append(key, curr);
                                        }
                                    });

                                    if ($('.' + prefix + 'progress', $modal).length === 0) {
                                        $('.' + prefix + 'modal-title', $modal)
                                            .after(
                                                $('<div/>', {
                                                    'class': prefix + 'progress'
                                                }).append(
                                                    $('<div/>', {
                                                        'class': prefix + 'progress-bar'
                                                    })
                                                )
                                            );
                                    }

                                    $.ajax({
                                        url: trumbowyg.o.plugins.upload.serverPath,
                                        headers: trumbowyg.o.plugins.upload.headers,
                                        xhrFields: trumbowyg.o.plugins.upload.xhrFields,
                                        type: 'POST',
                                        data: data,
                                        cache: false,
                                        dataType: 'json',
                                        processData: false,
                                        contentType: false,

                                        progressUpload: function (e) {
                                            $('.' + prefix + 'progress-bar').css('width', Math.round(e.loaded * 100 / e.total) + '%');
                                        },

                                        success: function (data) {
                                            if (trumbowyg.o.plugins.upload.success) {
                                                trumbowyg.o.plugins.upload.success(data, trumbowyg, $modal, values);
                                            } else {
                                                if (!!getDeep(data, trumbowyg.o.plugins.upload.statusPropertyName.split('.'))) {
                                                    var url = getDeep(data, trumbowyg.o.plugins.upload.urlPropertyName.split('.'));
                                                    trumbowyg.execCmd('insertImage', url, false, true);
                                                    var $img = $('img[src="' + url + '"]:not([alt])', trumbowyg.$box);
                                                    $img.attr('alt', values.alt);
                                                    if (trumbowyg.o.imageWidthModalEdit && parseInt(values.width) > 0) {
                                                        $img.attr({
                                                            width: values.width
                                                        });
                                                    }
                                                    setTimeout(function () {
                                                        trumbowyg.closeModal();
                                                    }, 250);
                                                    trumbowyg.$c.trigger('tbwuploadsuccess', [trumbowyg, data, url]);
                                                } else {
                                                    trumbowyg.addErrorOnModalField(
                                                        $('input[type=file]', $modal),
                                                        trumbowyg.lang[data.message]
                                                    );
                                                    trumbowyg.$c.trigger('tbwuploaderror', [trumbowyg, data]);
                                                }
                                            }
                                        },

                                        error: trumbowyg.o.plugins.upload.error || function () {
                                            trumbowyg.addErrorOnModalField(
                                                $('input[type=file]', $modal),
                                                trumbowyg.lang.uploadError
                                            );
                                            trumbowyg.$c.trigger('tbwuploaderror', [trumbowyg]);
                                        }
                                    });
                                }
                            );

                            $('input[type=file]').on('change', function (e) {
                                try {
                                    // If multiple files allowed, we just get the first.
                                    file = e.target.files[0];
                                } catch (err) {
                                    // In IE8, multiple files not allowed
                                    file = e.target.value;
                                }
                            });
                        }
                    };

                    trumbowyg.addBtnDef('upload', btnDef);
                }
            }
        }
    });

    function addXhrProgressEvent() {
        if (!$.trumbowyg.addedXhrProgressEvent) {   // Avoid adding progress event multiple times
            var originalXhr = $.ajaxSettings.xhr;
            $.ajaxSetup({
                xhr: function () {
                    var that = this,
                        req = originalXhr();

                    if (req && typeof req.upload === 'object' && that.progressUpload !== undefined) {
                        req.upload.addEventListener('progress', function (e) {
                            that.progressUpload(e);
                        }, false);
                    }

                    return req;
                }
            });
            $.trumbowyg.addedXhrProgressEvent = true;
        }
    }
})(jQuery);
/* ===========================================================
 * trumbowyg.pasteembed.js v1.0
 * Url paste to iframe with noembed. Plugin for Trumbowyg
 * http://alex-d.github.com/Trumbowyg
 * ===========================================================
 * Author : Max Seelig
 *          Facebook : https://facebook.com/maxse
 *          Website : https://www.maxmade.nl/
 */

(function($) {
    'use strict';

    var defaultOptions = {
        enabled: true,
        endpoints: [
            'https://noembed.com/embed?nowrap=on',
            'https://api.maxmade.nl/url2iframe/embed'
        ]
    };

    $.extend(true, $.trumbowyg, {
        plugins: {
            pasteEmbed: {
                init: function(trumbowyg) {
                    trumbowyg.o.plugins.pasteEmbed = $.extend(true, {}, defaultOptions, trumbowyg.o.plugins.pasteEmbed || {});

                    if (!trumbowyg.o.plugins.pasteEmbed.enabled) {
                        return;
                    }

                    trumbowyg.pasteHandlers.push(function(pasteEvent) {
                        try {
                            var clipboardData = (pasteEvent.originalEvent || pasteEvent).clipboardData,
                                pastedData = clipboardData.getData('Text'),
                                endpoints = trumbowyg.o.plugins.pasteEmbed.endpoints,
                                request = null;

                            if (pastedData.startsWith('http')) {
                                pasteEvent.stopPropagation();
                                pasteEvent.preventDefault();

                                var query = {
                                    url: pastedData.trim()
                                };
                                var content = '';
                                var index = 0;

                                if (request && request.transport) {
                                    request.transport.abort();
                                }

                                request = $.ajax({
                                    crossOrigin: true,
                                    url: endpoints[index],
                                    type: 'GET',
                                    data: query,
                                    cache: false,
                                    dataType: 'jsonp',
                                    success: function(res) {
                                        if (res.html) {
                                            index = 0;
                                            content = res.html;
                                        } else {
                                            index += 1;
                                        }
                                    },
                                    error: function() {
                                        index += 1;
                                    },
                                    complete: function() {
                                        if (content.length === 0 && index < endpoints.length - 1) {
                                            this.url = endpoints[index];
                                            this.data = query;
                                            $.ajax(this);
                                        }
                                        if (index === endpoints.length - 1) {
                                            content = $('<a>', {
                                                href: pastedData,
                                                text: pastedData
                                            }).prop('outerHTML');
                                        }
                                        if (content.length > 0) {
                                            index = 0;
                                            trumbowyg.execCmd('insertHTML', content);
                                        }
                                    }
                                });
                            }
                        } catch (c) {}
                    });
                }
            }
        }
    });
})(jQuery);
(function ($) {
    'use strict';

    var defaultOptions = {
        minSize: 32,
        step: 4
    };

    function preventDefault(e) {
        e.stopPropagation();
        e.preventDefault();
    }

    var ResizeWithCanvas = function () {
        // variable to create canvas and save img in resize mode
        this.resizeCanvas = document.createElement('canvas');
        // to allow canvas to get focus
        this.resizeCanvas.setAttribute('tabindex', '0');
        this.resizeCanvas.id = 'trumbowyg-resizimg-' + (+new Date());
        this.ctx = null;
        this.resizeImg = null;

        this.pressEscape = function (obj) {
            obj.reset();
        };
        this.pressBackspaceOrDelete = function (obj) {
            $(obj.resizeCanvas).replaceWith('');
            obj.resizeImg = null;
        };

        // PRIVATE FUNCTION
        var focusedNow = false;
        var isCursorSeResize = false;

        // calculate offset to change mouse over square in the canvas
        var offsetX, offsetY;
        var reOffset = function (canvas) {
            var BB = canvas.getBoundingClientRect();
            offsetX = BB.left;
            offsetY = BB.top;
        };

        var drawRect = function (shapeData, ctx) {
            // Inner
            ctx.beginPath();
            ctx.fillStyle = 'rgb(255, 255, 255)';
            ctx.rect(shapeData.points.x, shapeData.points.y, shapeData.points.width, shapeData.points.height);
            ctx.fill();
            ctx.stroke();
        };

        var updateCanvas = function (canvas, ctx, img, canvasWidth, canvasHeight) {
            ctx.translate(0.5, 0.5);
            ctx.lineWidth = 1;

            // image
            ctx.drawImage(img, 5, 5, canvasWidth - 10, canvasHeight - 10);

            // border
            ctx.beginPath();
            ctx.rect(5, 5, canvasWidth - 10, canvasHeight - 10);
            ctx.stroke();

            // square in the angle
            ctx.beginPath();
            ctx.fillStyle = 'rgb(255, 255, 255)';
            ctx.rect(canvasWidth - 10, canvasHeight - 10, 9, 9);
            ctx.fill();
            ctx.stroke();

            // get the offset to change the mouse cursor
            reOffset(canvas);

            return ctx;
        };

        // PUBLIC FUNCTION
        // necessary to correctly print cursor over square. Called once for instance. Useless with trumbowyg.
        this.init = function () {
            var _this = this;
            $(window).on('scroll resize', function () {
                _this.reCalcOffset();
            });
        };

        this.reCalcOffset = function () {
            reOffset(this.resizeCanvas);
        };

        this.canvasId = function () {
            return this.resizeCanvas.id;
        };

        this.isActive = function () {
            return this.resizeImg !== null;
        };

        this.isFocusedNow = function () {
            return focusedNow;
        };

        this.blurNow = function () {
            focusedNow = false;
        };

        // restore image in the HTML of the editor
        this.reset = function () {
            if (this.resizeImg === null) {
                return;
            }

            this.resizeImg.width = this.resizeCanvas.clientWidth - 10;
            this.resizeImg.height = this.resizeCanvas.clientHeight - 10;
            // clear style of image to avoid issue on resize because this attribute have priority over width and height attribute
            this.resizeImg.removeAttribute('style');

            $(this.resizeCanvas).replaceWith($(this.resizeImg));

            // reset canvas style
            this.resizeCanvas.removeAttribute('style');
            this.resizeImg = null;
        };

        // setup canvas with points and border to allow the resizing operation
        this.setup = function (img, resizableOptions) {
            this.resizeImg = img;

            if (!this.resizeCanvas.getContext) {
                return false;
            }

            focusedNow = true;

            // draw canvas
            this.resizeCanvas.width = $(this.resizeImg).width() + 10;
            this.resizeCanvas.height = $(this.resizeImg).height() + 10;
            this.resizeCanvas.style.margin = '-5px';
            this.ctx = this.resizeCanvas.getContext('2d');

            // replace image with canvas
            $(this.resizeImg).replaceWith($(this.resizeCanvas));

            updateCanvas(this.resizeCanvas, this.ctx, this.resizeImg, this.resizeCanvas.width, this.resizeCanvas.height);

            // enable resize
            $(this.resizeCanvas).resizable(resizableOptions)
                .on('mousedown', preventDefault);

            var _this = this;
            $(this.resizeCanvas)
                .on('mousemove', function (e) {
                    var mouseX = Math.round(e.clientX - offsetX);
                    var mouseY = Math.round(e.clientY - offsetY);

                    var wasCursorSeResize = isCursorSeResize;

                    _this.ctx.rect(_this.resizeCanvas.width - 10, _this.resizeCanvas.height - 10, 9, 9);
                    isCursorSeResize = _this.ctx.isPointInPath(mouseX, mouseY);
                    if (wasCursorSeResize !== isCursorSeResize) {
                        this.style.cursor = isCursorSeResize ? 'se-resize' : 'default';
                    }
                })
                .on('keydown', function (e) {
                    if (!_this.isActive()) {
                        return;
                    }

                    var x = e.keyCode;
                    if (x === 27) { // ESC
                        _this.pressEscape(_this);
                    } else if (x === 8 || x === 46) { // BACKSPACE or DELETE
                        _this.pressBackspaceOrDelete(_this);
                    }
                })
                .on('focus', preventDefault);

            this.resizeCanvas.focus();

            return true;
        };

        // update the canvas after the resizing
        this.refresh = function () {
            if (!this.resizeCanvas.getContext) {
                return;
            }

            this.resizeCanvas.width = this.resizeCanvas.clientWidth;
            this.resizeCanvas.height = this.resizeCanvas.clientHeight;
            updateCanvas(this.resizeCanvas, this.ctx, this.resizeImg, this.resizeCanvas.width, this.resizeCanvas.height);
        };
    };

    // object to interact with canvas
    var resizeWithCanvas = new ResizeWithCanvas();

    function destroyResizable(trumbowyg) {
        // clean html code
        trumbowyg.$ed.find('canvas.resizable')
            .resizable('destroy')
            .off('mousedown', preventDefault)
            .removeClass('resizable');

        resizeWithCanvas.reset();

        trumbowyg.syncCode();
    }

    $.extend(true, $.trumbowyg, {
        plugins: {
            resizimg: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.resizimg = $.extend(true, {},
                        defaultOptions,
                        trumbowyg.o.plugins.resizimg || {},
                        {
                            resizable: {
                                resizeWidth: false,
                                onDragStart: function (ev, $el) {
                                    var opt = trumbowyg.o.plugins.resizimg;
                                    var x = ev.pageX - $el.offset().left;
                                    var y = ev.pageY - $el.offset().top;
                                    if (x < $el.width() - opt.minSize || y < $el.height() - opt.minSize) {
                                        return false;
                                    }
                                },
                                onDrag: function (ev, $el, newWidth, newHeight) {
                                    var opt = trumbowyg.o.plugins.resizimg;
                                    if (newHeight < opt.minSize) {
                                        newHeight = opt.minSize;
                                    }
                                    newHeight -= newHeight % opt.step;
                                    $el.height(newHeight);
                                    return false;
                                },
                                onDragEnd: function () {
                                    // resize update canvas information
                                    resizeWithCanvas.refresh();
                                    trumbowyg.syncCode();
                                }
                            }
                        }
                    );

                    function initResizable() {
                        trumbowyg.$ed.find('img')
                            .off('click')
                            .on('click', function (e) {
                                // if I'm already do a resize, reset it
                                if (resizeWithCanvas.isActive()) {
                                    resizeWithCanvas.reset();
                                }
                                // initialize resize of image
                                resizeWithCanvas.setup(this, trumbowyg.o.plugins.resizimg.resizable);

                                preventDefault(e);
                            });
                    }

                    trumbowyg.$c.on('tbwinit', function () {
                        initResizable();

                        // disable resize when click on other items
                        trumbowyg.$ed.on('click', function (e) {
                            // check if I've clicked out of canvas or image to reset it
                            if ($(e.target).is('img') || e.target.id === resizeWithCanvas.canvasId()) {
                                return;
                            }

                            preventDefault(e);
                            resizeWithCanvas.reset();

                            // save changes
                            trumbowyg.$c.trigger('tbwchange');
                        });

                        trumbowyg.$ed.on('scroll', function () {
                            resizeWithCanvas.reCalcOffset();
                        });
                    });

                    trumbowyg.$c.on('tbwfocus tbwchange', initResizable);
                    trumbowyg.$c.on('tbwresize', function () {
                        resizeWithCanvas.reCalcOffset();
                    });

                    // Destroy
                    trumbowyg.$c.on('tbwblur', function () {
                        // if I have already focused the canvas avoid destroy
                        if (resizeWithCanvas.isFocusedNow()) {
                            resizeWithCanvas.blurNow();
                        } else {
                            destroyResizable(trumbowyg);
                        }
                    });
                },
                destroy: function (trumbowyg) {
                    destroyResizable(trumbowyg);
                }
            }
        }
    });
})(jQuery);