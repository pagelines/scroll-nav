<?php
/*
    Section: Scroll Nav
    Author: bestrag
  	Author URI: http://bestrag.net
    Class Name: ScrollNav
    Demo: http://bestrag.net/scrollnav/
    Workswith: templates
    Description: Scroll Nav ~easy to use one page navigation with icon menu builder~ It allows users to build custom one-page navigation menu. Besides common scroll-to controls it additionally offers default blueprint set that is easy to customize or place on various portions of your page.
    Version: 1.0
    Cloning: false
*/

class ScrollNav extends PageLinesSection {

    var $default_template = 'left';

    /* section_styles */
    function section_styles(){
        wp_enqueue_script( 'scrollnav', $this->base_url.'/scrollnav.js', array( 'jquery' ) );
    }

    /* section_head */
    function section_head() {
        //Site Options metatab colors
        $sectionbg         = (ploption('section_bg', $this->oset)) ? (ploption('section_bg', $this->oset)) : '';
        $contentbg         = (ploption('content_bg', $this->oset)) ? (ploption('content_bg', $this->oset)) : '';
        $scrollnavbg       = (ploption('scrollnav_bg', $this->oset)) ? (ploption('scrollnav_bg', $this->oset)) : '';
        $navpilbg          = (ploption('navpil_bg', $this->oset)) ? (ploption('navpil_bg', $this->oset)) : '';
        $fontcolor         = (ploption('ffontcolor', $this->oset)) ? (ploption('ffontcolor', $this->oset)) : '';
        $iconcolor         = (ploption('iconcolor', $this->oset)) ? (ploption('iconcolor', $this->oset)) : '';
        $subtxtcolor       = (ploption('subtxtcolor', $this->oset)) ? (ploption('subtxtcolor', $this->oset)) : '';
        $active_background = (ploption('active_background', $this->oset)) ? (ploption('active_background', $this->oset)) : '';
        $active_text       = (ploption('active_text', $this->oset)) ? (ploption('active_text', $this->oset)) : '';
        //Site Options metatab icon size
        $icon_size         = (ploption('iconszs', $this->oset)) ? (ploption('iconszs', $this->oset)) : '';
        //Site Options metatab to-top menu item
        $enable_to_top     = (ploption('enable_to_top', $this->oset)) ? (ploption('enable_to_top', $this->oset)) : '';
        $top_text          = (ploption('top_text', $this->oset)) ? (ploption('top_text', $this->oset)) : '';
        $top_subtext       = (ploption('top_subtext', $this->oset)) ? (ploption('top_subtext', $this->oset)) : '';
        $top_icon          = (ploption('top_icon', $this->oset)) ? (ploption('top_icon', $this->oset)) : '';
        //Site Options metatab item layout
        $menu_offset       = (ploption('menu_offset', $this->oset)) ? (ploption('menu_offset', $this->oset)) : '0';
        $margin_li       = (ploption('margin_li', $this->oset)) ? (ploption('margin_li', $this->oset)) : '';
        $height_a       = (ploption('height_a', $this->oset)) ? (ploption('height_a', $this->oset)) : '';
        $width_a       = (ploption('width_a', $this->oset)) ? (ploption('width_a', $this->oset)) : '';
        $remove_title      = (ploption('remove_title', $this->oset)) ? (ploption('remove_title', $this->oset)) : '';
        //Page metapanel
        $template          = (ploption('template', $this->oset)) ? (ploption('template', $this->oset)) : $this->default_template;
        $scroll_speed      = (ploption('scroll_speed', $this->oset)) ? (ploption('scroll_speed', $this->oset)) : '800';
        $target_offset     = (ploption('target_offset', $this->oset)) ? (ploption('target_offset', $this->oset)) : '0';
        //create arrays of ploptions ->
        $item_num      = (ploption('item_num', $this->oset)) ? ploption('item_num', $this->oset) : 6;
        $icon_array    = array();
        $subtext_array = array();
        for($i = 1; $i <= $item_num; $i++){
            $icon_array[$i]    = (ploption('icon'.$i, $this->oset)) ? (ploption('icon'.$i, $this->oset)) : '';
            $subtext_array[$i] = (ploption('item_text'.$i, $this->oset)) ? (ploption('item_text'.$i, $this->oset)) : '';
        }
        // add div before #header
        add_action('pagelines_before_header', array(&$this, 'customHead' ));
        ?>
        <script type="text/javascript">
            jQuery(window).ready(function() {

                // -> and translate them
                var iconArray    = <?php echo json_encode($icon_array); ?>;
                var subtextArray = <?php echo json_encode($subtext_array); ?>;
                //some vars
                var temp         = "<?php print $template;?>";
                var el           = jQuery('#scroll-nav');
                var ul           = jQuery('#scroll-nav div.scrollnav ul');
   				//colors
   				var sectionbg = "<?php print $sectionbg;?>";

                //scrollnav init
                jQuery('.scrollnav')
                    .scrollNav({
                                scrollSpeed:    <?php echo $scroll_speed; ?> ,
                                scrollOffset:   <?php echo $target_offset; ?>,
                });

                //template-specific control of entire section in LESS
                if (temp) {el.addClass(temp)};

                //add icon to each menu item
                jQuery("li a i", ul).each(function( i ){jQuery(this).addClass(iconArray[++i]);});
                //add background image and subtitle text to each menu item
                jQuery("li a", ul).each(function( i ){
                    jQuery(this).append( '<div class="subtitle">' + subtextArray[++i] + '</div>' );
                });

                //add "scroll to top" menu item and add icon, image and text to it
                if ("<?php print $enable_to_top;?>" != '') {
                    ul.prepend('<li class="to-top"><a class="scroll-nav-anchor to_top" href="#"><i></i><div class="sn-title">' + '<?php print $top_text;?>' + '</div><div class="subtitle">' + '<?php print $top_subtext;?>' + '</div></a></li>');
                    jQuery("li:first-child a i", ul).addClass("<?php print $top_icon;?>");
                }//menu created

                 //some more selectors
                var li   = jQuery('#scroll-nav div.scrollnav ul li');
                var a   = jQuery('#scroll-nav div.scrollnav ul li a');
                var i   = jQuery('#scroll-nav div.scrollnav ul li a i');
                var div = jQuery('#scroll-nav div.scrollnav ul li a div');

                //menu offset - to the pixel
                var cpad = (parseFloat(jQuery('#page div.page-canvas').css('paddingTop')));
                var fixednav = 0; if (jQuery('#site').children('#navbar')[0]) {fixednav = (cpad - jQuery('#navbar').outerHeight());}
                var hh = jQuery('#header').offset().top - fixednav + <?php print $menu_offset;?>;
                el.css('top', hh);

                //push page content bellow menu. Add margin between menu items
                var arr = ['center', 'center-left'];
                if ( jQuery.inArray( temp, arr, 0 ) > -1 ) {var h = el.outerHeight(); jQuery('#sn-head').css("height",h);
                li.not(':last-child').css("marginRight", "<?php print $margin_li;?>px");}
                else{li.not(':last-child').css("marginBottom", "<?php print $margin_li;?>px");}
                //menu item height and width
                if ("<?php print $width_a;?>")      {a.css('width', '<?php print $width_a;?>');}
                if ("<?php print $height_a;?>")     {a.css('height', '<?php print $height_a;?>');}
                //change colors
                if (sectionbg)    {jQuery('#scroll-nav .texture').css('background-color',sectionbg);}
                if ("<?php print $contentbg;?>")    {jQuery('#scroll-nav .content').css('background-color','<?php print $contentbg;?>');}
                if ("<?php print $scrollnavbg;?>")  {ul.css('background-color','<?php print $scrollnavbg;?>');}
                if ("<?php print $navpilbg;?>")     {a.css('background-color','<?php print $navpilbg;?>');}
                if ("<?php print $fontcolor;?>")    {div.css('color','<?php print $fontcolor;?>');}
                if ("<?php print $icon_size;?>")    {i.addClass('<?php print $icon_size;?>');}
                if ("<?php print $iconcolor;?>")    {i.css('color','<?php print $iconcolor;?>');}
                //hide title text from menu
                if ("<?php print $remove_title;?>") {a.children('div.sn-title').css('display','none');}

                // Manage :hover and .current color
                if ("<?php print $active_background;?>" || "<?php print $active_text;?>") {
                    a.hover(
                        function(){jQuery(this).css('background-color', "<?php print $active_background;?>").children().css('color', "<?php print $active_text;?>");},
                        function(){if(!(jQuery(this).hasClass('current'))) {jQuery(this).css('background-color', "<?php print $navpilbg;?>").children('i').css('color', "<?php print $iconcolor;?>").siblings('div').css('color', "<?php print $fontcolor;?>");}}
                    );
                    a.click(function(e){
                        e.preventDefault();
                        // click tweak
                        if(jQuery(this).hasClass('to_top')) {
                            jQuery("html, body").animate({ scrollTop: 0 }, 600);
                            a.removeClass('current').not('.to_top').css('background-color', "<?php print $navpilbg;?>").children('i').css('color', "<?php print $iconcolor;?>").siblings('div').css('color', "<?php print $fontcolor;?>");
                        }else{
                            if (!jQuery.browser.msie) {a.not('.current').css('background-color', "<?php print $navpilbg;?>").children('i').css('color', "<?php print $iconcolor;?>").siblings('div').css('color', "<?php print $fontcolor;?>"); console.log(!jQuery.browser.msie);}
                        }
                    });
                } else {
                	    a.click(function(e){
                        e.preventDefault();
                        // click tweak
                        if(jQuery(this).hasClass('to_top')) {
                            jQuery("html, body").animate({ scrollTop: 0 }, 600);
                            a.removeClass('current');
                        }
                    });
                }
            });
        </script>
        <?php

        /* menu font */
        if ( ploption( 'text_font', $this->oset ) ) {
                       echo load_custom_font( ploption( 'text_font', $this->oset ), "#scroll-nav .scrollnav" );
        }
    }

    //inserted div before #header
    function customHead(){
        print "<div id='sn-head'></div>";
    }

    /* section_template */
    function section_template() {
        $template = (ploption('template', $this->oset)) ? (ploption('template', $this->oset)) : $this ->default_template;
        printf('<div class="scrollnav %s"><ul class="nav nav-pills"></ul></div>',$template);
    }

    /* section_optionator */
    function section_optionator($settings){
        $settings = wp_parse_args($settings, $this->optionator_default);
        $opts = array();

        $opts['main_settings'] = array(
                'type'      =>   'multi_option',
                'title'         => __( 'Main Options', 'pagelines' ),
                'shortexp'      => __( 'Parameters', 'pagelines' ),
                'exp'          => __( 'Use <strong>Blueprint Template</strong> drop down box to show your favorite menu layout.
                                      <br>Use <strong>Scroll Speed</strong> to make scrolling faster or slower. Enter the number in milliseconds to adjust scroll duration. Greater the number - Smaller the speed.
                                      <br>Use <strong>Target Offset</strong> to adjust the stop point. Use positive or negative numerals.', 'pagelines' ),
                'selectvalues'  => array(
                    'template'=> array(
                        'type'         => 'select',
                        'inputlabel'   => __( 'Choose Blueprint Template', 'pagelines' ),
                        'selectvalues' => array(
                            'left'      => array( 'name' => __( 'Left blueprint', 'pagelines' ) ),
                            'right'       => array( 'name' => __( 'Right blueprint', 'pagelines' ) ),
                            'center'       => array( 'name' => __( 'Top Center blueprint', 'pagelines' ) ),
                            'center-left'     => array( 'name' => __( 'Top Left inline blueprint', 'pagelines' ) ),
                        )
                    ),
                    'scroll_speed' => array(
                        'type'          => 'text_small',
                        'inputlabel'    => __( 'Scroll Speed in milliseconds (default 800)', 'pagelines' ),
                    ),
                    'target_offset'     => array(
                        'type'          => 'text_small',
                        'inputlabel'    => __( 'Offset from target section in pixels (default 0)', 'pagelines' ),
                    ),
                )
        );

        $opts['item_sum'] = array(
                'type'      =>   'multi_option',
                'title'         => __( 'Menu Button Options', 'pagelines' ),
                'shortexp'      => __( 'Parameters', 'pagelines' ),
                'exp'          => __( 'Select <strong>Number of Menu Items</strong> you wish to customize. It represents the number of items in the Scroll Nav menu.
                                      <br><strong>HEADS UP:</strong> make sure to <strong>Save Settings</strong> after using this option.', 'pagelines' ),
                'selectvalues'  => array(
                    'item_num'=> array(
                        'type'          => 'count_select',
                        'count_start'   => '1',
                        'count_number'  => '15',
                        'inputlabel'    => __( 'Number of Items in the Menu (default 6)', 'pagelines' )
                    ),
                )
        );

        global $post_ID;
        $oset = array('post_id' => $post_ID);
        $item_num = (ploption('item_num', $oset)) ? ploption('item_num', $oset) : 6;

        for($i = 1; $i <= $item_num; $i++){
            $opts['item_'.$i] = array(
                'type'         =>    'multi_option',
                'title'        => __( 'Menu Item #'.$i.' Options', 'pagelines' ),
                'shortexp'     => __( 'select an icon or add subtitle text for menu item #'.$i, 'pagelines'),
                'selectvalues' => array(
                    'icon'.$i  => array(
                        'type'         => 'select',
                        'inputlabel'   => __( 'Choose Icon', 'pagelines' ),
                        'selectvalues' => $this->get_icon_array(),
                    ),
                    'item_text'.$i => array(
                        'type'       => 'text',
                        'inputlabel' => __( 'Subtitle text on menu item', 'pagelines' ),
                    )
                )
            );
        }

        $tab_settings = array(
            'id'        => $this->id,
            'name'      => $this->name,
            'icon'      => $this->icon,
            'active'    => $settings['active']
        );
        register_metatab($tab_settings, $opts);
    }

    /* section_persistent */
    function section_persistent() {
        add_filter( 'pagelines_options_array', array( &$this, 'get_meta_array' ) );
    }

    /* site options metapanel */
    function get_meta_array( $options ){
        if( defined( 'THEMENAME'  ) && 'PageLines' == THEMENAME  ) {
            $options_array['Scroll_Nav'] = array(//entire space
                //Type menu
                'icon'      =>  $this->icon,
                'bkg-colors' => array(
                    'type'      => 'color_multi',
                    'title'     => __( 'Adjust Colors', 'pagelines' ),
                    'shortexp'  => __( 'take full control over menu colors', 'pagelines' ),
                    'exp'       => __( '<strong>Color customization</strong> for selected blueprint. Use color options to blend Scroll Nav into your Theme.
                    					<br><strong>HEADS UP:</strong> left and right blueprints do not use Section and Content Background. Use Scroll Nav Background instead.', 'pagelines' ),
                    'layout'    => 'full',
                    'selectvalues'  => array(
                        'section_bg'    => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'background-color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'texture',
                            'inputlabel'    => __( 'Section Background', 'pagelines' ),
                        ),
                        'content_bg'    => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'background-color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'testimonials-lud',
                            'inputlabel'    => __( 'Content Background', 'pagelines' ),
                        ),
                        'scrollnav_bg'  => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'background-color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'texture',
                            'inputlabel'    => __( 'Scroll Nav Background', 'pagelines' ),
                        ),
                        'navpil_bg' => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'background-color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'texture',
                            'inputlabel'    => __( 'Menu Item Background', 'pagelines' ),
                        ),
                         'iconcolor' => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'texture',
                            'inputlabel'    => __( 'Icon Color', 'pagelines' ),
                        ),
                        'ffontcolor'    => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'texture',
                            'inputlabel'    => __( 'Text Color', 'pagelines' ),
                        ),
                        'active_background' => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'texture',
                            'inputlabel'    => __( 'Active/Hover Color', 'pagelines' ),
                        ),
                        'active_text'   => array(
                            'default'       => '#FFFFFF',
                            'css_prop'      => 'color',
                            'flag'          => 'set_default',
                            'cssgroup'      => 'texture',
                            'inputlabel'    => __( 'Active/Hover Text Color', 'pagelines' ),
                        ),

                    )
                ),
                'iconszs' => array(
                    'default'       => 'pixels',
                    'type'          => 'graphic_selector',
                    'inputlabel'    => __( 'Select Icon Size', 'pagelines' ),
                    'showname'      => true,
                    'sprite'        => $this->base_url.'/images/sprite-icon-sizes.png',
                    'width'         => '40px',
                    'height'        => '40px',
                    'layout'        => 'interface',
                    'selectvalues'  => array(
                        ''        => array( 'name' => __( '14px', 'pagelines' ), 'offset' => '0px 0px' ),
                        'icon-2x'       => array( 'name' => __( '28px', 'pagelines' ), 'offset' => '0px -50px',  ),
                        'icon-3x'   => array( 'name' => __( '42px', 'pagelines' ), 'offset' => '0px -100px' ),
                        'icon-4x'       => array( 'name' => __( '56px', 'pagelines' ), 'offset' => '0px -150px'),
                        'icon-5x'   => array( 'name' => __( '70px', 'pagelines' ), 'offset' => '0px -200px' ),
                        'icon-6x'   => array( 'name' => __( '84px', 'pagelines' ), 'offset' => '0px -250px' ),
                        'icon-7x'   => array( 'name' => __( '98px', 'pagelines' ), 'offset' => '0px -300px' ),
                        'icon-8x'   => array( 'name' => __( '112px', 'pagelines' ), 'offset' => '0px -350px' ),

                    ),
                    'title'     => __( 'Icons Handling', 'pagelines' ),
                    'shortexp'  => __( 'adjust the size of your icons', 'pagelines' ),
                ),
                'px_opts' => array(
                    'type'         =>    'multi_option',
                    'title'        => __( 'Menu Adjustments', 'pagelines' ),
                    'shortexp'     => __( 'additional menu control and customization', 'pagelines' ),
                    'exp'          => __( '<br>Use <strong>Hide Title</strong> if you want to hide Titles from your menu. Use it if you wish to have <strong>icon-only menu</strong> or <strong>icons with Subtitle Text</strong>.
                    						<br>Use <strong>Font</strong> to change fonts for your menu items.
                    						<br>Use <strong>Menu top position</strong> to adjust menu distance from the top of the screen. Use positive or negative numerals.
                    						<br>Use <strong>Distance between Menu Items</strong> to add additional space between single menu items (default 2).
                    						<br>Use <strong>Height and Width</strong> to adjust proportions of a single menu item. Requires proper css syntax.
                    						', 'pagelines' ),
                    'selectvalues' => array(
                    	'remove_title' => array(
                            'type'          => 'check',
                            'inputlabel'    => __( 'Hide Title on menu', 'pagelines' ),
                        ),
                        'text_font' => array(
                                'type'       => 'fonts',
                                'inputlabel' => __( 'Choose Menu text font', 'pagelines' ),
                        ),
                        'menu_offset'   => array(
                                'type'       => 'text_small',
                                'inputlabel' => __( 'Adjust menu top position in pixels (e.g. 30)', 'pagelines' ),
                        ),
                        'margin_li'   => array(
                                'type'       => 'text_small',
                                'inputlabel' => __( 'Distance between Menu Items in pixels (e.g. 8)', 'pagelines' ),
                        ),
                        'height_a'   => array(
                                'type'       => 'text_small',
                                'inputlabel' => __( 'Height of the single Menu Item (e.g. 60px, 4em, auto)', 'pagelines' ),
                        ),
                        'width_a'   => array(
                                'type'       => 'text_small',
                                'inputlabel' => __( 'Width of the single Menu Item (e.g. 60px, 4em, auto)', 'pagelines' ),
                        ),
                    )
                ),
                'scroll_to_top' => array(
                    'type'         =>    'multi_option',
                    'title'        => __( '"Scroll to Top" Options', 'pagelines' ),
                    'shortexp'     => __( 'add first menu item that scrolls to the top of the page', 'pagelines' ),
                    'exp'          => __( '<br>Add <strong>"scroll to top"</strong> menu item at the first menu position.
                    					   <br>Add <strong>Title text</strong> to label "scroll to top" menu item.
                    					   <br>Select an <strong>Icon</strong> or add <strong>Subtitle Text</strong> to represent "scroll to top" menu item.', 'pagelines' ),
                    'selectvalues' => array(
                        'enable_to_top' => array(
                            'type'          => 'check',
                            'inputlabel'    => __( 'Enable "scroll to top"', 'pagelines' ),
                        ),
                        'top_text' => array(
                            'type'       => 'text',
                            'inputlabel' => __( 'Title text on menu item', 'pagelines' ),
                        ),
                        'top_subtext' => array(
                            'type'       => 'text',
                            'inputlabel' => __( 'Subtitle text on menu item', 'pagelines' ),
                        ),
                        'top_icon'  => array(
                            'type'         => 'select',
                            'inputlabel'   => __( 'Choose Icon', 'pagelines' ),
                            'selectvalues' => $this->get_icon_array(),
                        ),
                    )
                )
            );
            return array_merge($options, $options_array);
        }
        else {
            return $options;
        }
    }

    /* Get fontawsome icons as array */
    function get_icon_array(){
        $icon_list = array();
        $icons = array("icon-adjust", "icon-asterisk", "icon-ban-circle", "icon-bar-chart", "icon-barcode", "icon-beaker", "icon-beer", "icon-bell", "icon-bell-alt", "icon-bolt", "icon-book", "icon-bookmark", "icon-bookmark-empty", "icon-briefcase", "icon-bullhorn", "icon-calendar", "icon-camera", "icon-camera-retro", "icon-certificate", "icon-check", "icon-check-empty", "icon-circle", "icon-circle-blank", "icon-cloud", "icon-cloud-download", "icon-cloud-upload", "icon-coffee", "icon-cog", "icon-cogs", "icon-comment", "icon-comment-alt", "icon-comments", "icon-comments-alt", "icon-credit-card", "icon-dashboard", "icon-desktop", "icon-download", "icon-download-alt", "icon-edit", "icon-envelope", "icon-envelope-alt", "icon-exchange", "icon-exclamation-sign", "icon-external-link", "icon-eye-close", "icon-eye-open", "icon-facetime-video", "icon-fighter-jet", "icon-film", "icon-filter", "icon-fire", "icon-flag", "icon-folder-close", "icon-folder-open", "icon-folder-close-alt", "icon-folder-open-alt", "icon-food", "icon-gift", "icon-glass", "icon-globe", "icon-group", "icon-hdd", "icon-headphones", "icon-heart", "icon-heart-empty", "icon-home", "icon-inbox", "icon-info-sign", "icon-key", "icon-leaf", "icon-laptop", "icon-legal", "icon-lemon", "icon-lightbulb", "icon-lock", "icon-unlock", "icon-magic", "icon-magnet", "icon-map-marker", "icon-minus", "icon-minus-sign", "icon-mobile-phone", "icon-money", "icon-move", "icon-music", "icon-off", "icon-ok", "icon-ok-circle", "icon-ok-sign", "icon-pencil", "icon-picture", "icon-plane", "icon-plus", "icon-plus-sign", "icon-print", "icon-pushpin", "icon-qrcode", "icon-question-sign", "icon-quote-left", "icon-quote-right", "icon-random", "icon-refresh", "icon-remove", "icon-remove-circle", "icon-remove-sign", "icon-reorder", "icon-reply", "icon-resize-horizontal", "icon-resize-vertical", "icon-retweet", "icon-road", "icon-rss", "icon-screenshot", "icon-search", "icon-share", "icon-share-alt", "icon-shopping-cart", "icon-signal", "icon-signin", "icon-signout", "icon-sitemap", "icon-sort", "icon-sort-down", "icon-sort-up", "icon-spinner", "icon-star", "icon-star-empty", "icon-star-half", "icon-tablet", "icon-tag", "icon-tags", "icon-tasks", "icon-thumbs-down", "icon-thumbs-up", "icon-time", "icon-tint", "icon-trash", "icon-trophy", "icon-truck", "icon-umbrella", "icon-upload", "icon-upload-alt", "icon-user", "icon-user-md", "icon-volume-off", "icon-volume-down", "icon-volume-up", "icon-warning-sign", "icon-wrench", "icon-zoom-in", "icon-zoom-out", "icon-file", "icon-file-alt", "icon-cut", "icon-copy", "icon-paste", "icon-save", "icon-undo", "icon-repeat", "icon-text-height", "icon-text-width", "icon-align-left", "icon-align-center", "icon-align-right", "icon-align-justify", "icon-indent-left", "icon-indent-right", "icon-font", "icon-bold", "icon-italic", "icon-strikethrough", "icon-underline", "icon-link", "icon-paper-clip", "icon-columns", "icon-table", "icon-th-large", "icon-th", "icon-th-list", "icon-list", "icon-list-ol", "icon-list-ul", "icon-list-alt", "icon-angle-left", "icon-angle-right", "icon-angle-up", "icon-angle-down", "icon-arrow-down", "icon-arrow-left", "icon-arrow-right", "icon-arrow-up", "icon-caret-down", "icon-caret-left", "icon-caret-right", "icon-caret-up", "icon-chevron-down", "icon-chevron-left", "icon-chevron-right", "icon-chevron-up", "icon-circle-arrow-down", "icon-circle-arrow-left", "icon-circle-arrow-right", "icon-circle-arrow-up", "icon-double-angle-left", "icon-double-angle-right", "icon-double-angle-up", "icon-double-angle-down", "icon-hand-down", "icon-hand-left", "icon-hand-right", "icon-hand-up", "icon-circle", "icon-circle-blank", "icon-play-circle", "icon-play", "icon-pause", "icon-stop", "icon-step-backward", "icon-fast-backward", "icon-backward", "icon-forward", "icon-fast-forward", "icon-step-forward", "icon-eject", "icon-fullscreen", "icon-resize-full", "icon-resize-small", "icon-phone", "icon-phone-sign", "icon-facebook", "icon-facebook-sign", "icon-twitter", "icon-twitter-sign", "icon-github", "icon-github-alt", "icon-github-sign", "icon-linkedin", "icon-linkedin-sign", "icon-pinterest", "icon-pinterest-sign", "icon-google-plus", "icon-google-plus-sign", "icon-sign-blank", "icon-ambulance", "icon-beaker", "icon-h-sign", "icon-hospital", "icon-medkit", "icon-plus-sign-alt", "icon-stethoscope", "icon-user-md");
        foreach ($icons as $value) {
            $icon_list[$value] = array( 'name' => __( $value, 'pagelines' ) );
        }
        return $icon_list;
    }

}//EON
