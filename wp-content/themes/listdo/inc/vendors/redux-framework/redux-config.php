<?php
/**
 * ReduxFramework Sample Config File
 * For full documentation, please visit: http://docs.reduxframework.com/
 */

if (!class_exists('Listdo_Redux_Framework_Config')) {

    class Listdo_Redux_Framework_Config
    {
        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct()
        {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            add_action('init', array($this, 'initSettings'), 10);
        }

        public function initSettings()
        {
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setSections()
        {
            global $wp_registered_sidebars;
            $sidebars = array();

            if ( !empty($wp_registered_sidebars) ) {
                foreach ($wp_registered_sidebars as $sidebar) {
                    $sidebars[$sidebar['id']] = $sidebar['name'];
                }
            }
            $columns = array( '1' => esc_html__('1 Column', 'listdo'),
                '2' => esc_html__('2 Columns', 'listdo'),
                '3' => esc_html__('3 Columns', 'listdo'),
                '4' => esc_html__('4 Columns', 'listdo'),
                '6' => esc_html__('6 Columns', 'listdo')
            );
            
            $general_fields = array();
            if ( !function_exists( 'wp_site_icon' ) ) {
                $general_fields[] = array(
                    'id' => 'media-favicon',
                    'type' => 'media',
                    'title' => esc_html__('Favicon Upload', 'listdo'),
                    'desc' => esc_html__('', 'listdo'),
                    'subtitle' => esc_html__('Upload a 16px x 16px .png or .gif image that will be your favicon.', 'listdo'),
                );
            }
            $general_fields[] = array(
                'id' => 'preload',
                'type' => 'switch',
                'title' => esc_html__('Preload Website', 'listdo'),
                'default' => true,
            );
            $general_fields[] = array(
                'id' => 'media-preload-icon',
                'type' => 'media',
                'title' => esc_html__('Preload Icon', 'listdo'),
                'subtitle' => esc_html__('Upload a .png or .gif image that will be your preload icon.', 'listdo'),
                'required' => array('preload', '=', true)
            );
            $general_fields[] = array(
                'id' => 'image_lazy_loading',
                'type' => 'switch',
                'title' => esc_html__('Show Image lazy Loading', 'listdo'),
                'default' => true,
            );
            $general_fields[] = array(
                'id' => 'use_recaptcha_register_form',
                'type' => 'switch',
                'title' => esc_html__('Use ReCaptcha register form', 'listdo'),
                'default' => true,
            );
            // General Settings Tab
            $this->sections[] = array(
                'icon' => 'el-icon-cogs',
                'title' => esc_html__('General', 'listdo'),
                'fields' => $general_fields
            );
            // Header
            $allowed_html_array = array( 'a' => array('href' => array(), 'target' => array()) );
            $this->sections[] = array(
                'icon' => 'el el-website',
                'title' => esc_html__('Header', 'listdo'),
                'fields' => array(
                    array(
                        'id' => 'header_settings',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Header Settings', 'listdo').'</h3>',
                    ),
                    array(
                        'id' => 'header_type',
                        'type' => 'select',
                        'title' => esc_html__('Header Layout Type', 'listdo'),
                        'subtitle' => esc_html__('Choose a header for your website.', 'listdo'),
                        'options' => listdo_get_header_layouts(),
                        'desc' => sprintf(wp_kses(__('You can add or edit a header in <a href="%s" target="_blank">Headers Builder</a>', 'listdo'), $allowed_html_array), esc_url( admin_url( 'edit.php?post_type=apus_megamenu') )),
                    ),
                    array(
                        'id' => 'keep_header',
                        'type' => 'switch',
                        'title' => esc_html__('Sticky Header', 'listdo'),
                        'default' => false
                    ),
                    array(
                        'id' => 'header_mobile_settings',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Header Mobile Settings', 'listdo').'</h3>',
                    ),
                    array(
                        'id' => 'separate_header_mobile',
                        'type' => 'switch',
                        'title' => esc_html__('Use Separate Header Mobile', 'listdo'),
                        'default' => true
                    ),
                    array(
                        'id' => 'media-mobile-logo',
                        'type' => 'media',
                        'title' => esc_html__('Mobile Logo Upload', 'listdo'),
                        'subtitle' => esc_html__('Upload a .png or .gif image that will be your logo.', 'listdo'),
                    ),
                    array(
                        'id' => 'show_login_register',
                        'type' => 'switch',
                        'title' => esc_html__('Show Login/Register', 'listdo'),
                        'default' => true
                    ),
                    array(
                        'id' => 'show_mini_cart',
                        'type' => 'switch',
                        'title' => esc_html__('Show Mini Cart', 'listdo'),
                        'default' => true
                    ),
                    array(
                        'id' => 'show_add_listing',
                        'type' => 'select',
                        'title' => esc_html__('Show Add Listing Button', 'listdo'),
                        'options' => array(
                            'no' => esc_html__('No', 'listdo'),
                            'always' => esc_html__('Always', 'listdo'),
                            'show_logedin' => esc_html__('Logedin user', 'listdo'),
                        ),
                        'default' => 'always',
                        'subtitle' => wp_kses(sprintf(__('Go to <a href="%s">Listings Settings</a> > Pages tab to setting Submit Job Page', 'listdo'), admin_url( 'edit.php?post_type=job_listing&page=job-manager-settings')), array('a' => array('href' => array()))),
                    )
                )
            );
            // Footer
            $this->sections[] = array(
                'icon' => 'el el-website',
                'title' => esc_html__('Footer', 'listdo'),
                'fields' => array(
                    array(
                        'id' => 'footer_type',
                        'type' => 'select',
                        'title' => esc_html__('Footer Layout Type', 'listdo'),
                        'subtitle' => esc_html__('Choose a footer for your website.', 'listdo'),
                        'options' => listdo_get_footer_layouts()
                    ),
                    array(
                        'id' => 'back_to_top',
                        'type' => 'switch',
                        'title' => esc_html__('Back To Top Button', 'listdo'),
                        'subtitle' => esc_html__('Toggle whether or not to enable a back to top button on your pages.', 'listdo'),
                        'default' => true,
                    ),
                )
            );

            // Blog settings
            $this->sections[] = array(
                'icon' => 'el el-pencil',
                'title' => esc_html__('Blog', 'listdo'),
                'fields' => array(
                    array(
                        'id' => 'show_blog_breadcrumbs',
                        'type' => 'switch',
                        'title' => esc_html__('Breadcrumbs', 'listdo'),
                        'default' => 1
                    ),
                    array (
                        'title' => esc_html__('Breadcrumbs Background Color', 'listdo'),
                        'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'listdo').'</em>',
                        'id' => 'blog_breadcrumb_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'id' => 'blog_breadcrumb_image',
                        'type' => 'media',
                        'title' => esc_html__('Breadcrumbs Background', 'listdo'),
                        'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'listdo'),
                    ),
                )
            );
            // Archive Blogs settings
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Blog & Post Archives', 'listdo'),
                'fields' => array(
                    array(
                        'id' => 'blog_archive_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Layout', 'listdo'),
                        'subtitle' => esc_html__('Select the variation you want to apply on your store.', 'listdo'),
                        'options' => array(
                            'main' => array(
                                'title' => esc_html__('Main Only', 'listdo'),
                                'alt' => esc_html__('Main Only', 'listdo'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                            ),
                            'left-main' => array(
                                'title' => esc_html__('Left - Main Sidebar', 'listdo'),
                                'alt' => esc_html__('Left - Main Sidebar', 'listdo'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                            ),
                            'main-right' => array(
                                'title' => esc_html__('Main - Right Sidebar', 'listdo'),
                                'alt' => esc_html__('Main - Right Sidebar', 'listdo'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                            ),
                        ),
                        'default' => 'main'
                    ),
                    array(
                        'id' => 'blog_archive_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Is Full Width?', 'listdo'),
                        'default' => false
                    ),
                    array(
                        'id' => 'blog_archive_left_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Archive Left Sidebar', 'listdo'),
                        'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'listdo'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'blog_archive_right_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Archive Right Sidebar', 'listdo'),
                        'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'listdo'),
                        'options' => $sidebars
                        
                    ),
                    array(
                        'id' => 'blog_display_mode',
                        'type' => 'select',
                        'title' => esc_html__('Display Mode', 'listdo'),
                        'options' => array(
                            'grid' => esc_html__('Grid Layout', 'listdo'),
                            'list' => esc_html__('List Layout', 'listdo'),
                        ),
                        'default' => 'list'
                    ),
                    array(
                        'id' => 'blog_columns',
                        'type' => 'select',
                        'title' => esc_html__('Blog Columns', 'listdo'),
                        'options' => $columns,
                        'default' => 1
                    ),
                    array(
                        'id' => 'blog_item_thumbsize',
                        'type' => 'text',
                        'title' => esc_html__('Thumbnail Size', 'listdo'),
                        'subtitle' => esc_html__('This featured for the site is using Visual Composer.', 'listdo'),
                        'desc' => esc_html__('Enter thumbnail size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height) .', 'listdo'),
                    ),
                )
            );
            // Single Blogs settings
            $this->sections[] = array(
                'subsection' => true,
                'title' => esc_html__('Blog', 'listdo'),
                'fields' => array(
                    array(
                        'id' => 'blog_single_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Layout', 'listdo'),
                        'subtitle' => esc_html__('Select the variation you want to apply on your store.', 'listdo'),
                        'options' => array(
                            'main' => array(
                                'title' => esc_html__('Main Only', 'listdo'),
                                'alt' => esc_html__('Main Only', 'listdo'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen1.png'
                            ),
                            'left-main' => array(
                                'title' => esc_html__('Left - Main Sidebar', 'listdo'),
                                'alt' => esc_html__('Left - Main Sidebar', 'listdo'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen2.png'
                            ),
                            'main-right' => array(
                                'title' => esc_html__('Main - Right Sidebar', 'listdo'),
                                'alt' => esc_html__('Main - Right Sidebar', 'listdo'),
                                'img' => get_template_directory_uri() . '/inc/assets/images/screen3.png'
                            ),
                        ),
                        'default' => 'main'
                    ),
                    array(
                        'id' => 'blog_single_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Is Full Width?', 'listdo'),
                        'default' => false
                    ),
                    array(
                        'id' => 'blog_single_left_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Left Sidebar', 'listdo'),
                        'subtitle' => esc_html__('Choose a sidebar for left sidebar.', 'listdo'),
                        'options' => $sidebars
                    ),
                    array(
                        'id' => 'blog_single_right_sidebar',
                        'type' => 'select',
                        'title' => esc_html__('Right Sidebar', 'listdo'),
                        'subtitle' => esc_html__('Choose a sidebar for right sidebar.', 'listdo'),
                        'options' => $sidebars
                        
                    ),
                    array(
                        'id' => 'show_blog_social_share',
                        'type' => 'switch',
                        'title' => esc_html__('Show Social Share', 'listdo'),
                        'default' => 0
                    ),
                    array(
                        'id' => 'show_blog_releated',
                        'type' => 'switch',
                        'title' => esc_html__('Show Releated Posts', 'listdo'),
                        'default' => 0
                    ),
                    array(
                        'id' => 'number_blog_releated',
                        'type' => 'text',
                        'title' => esc_html__('Number of related posts to show', 'listdo'),
                        'required' => array('show_blog_releated', '=', '1'),
                        'default' => 4,
                        'min' => '1',
                        'step' => '1',
                        'max' => '20',
                        'type' => 'slider'
                    ),
                    array(
                        'id' => 'releated_blog_columns',
                        'type' => 'select',
                        'title' => esc_html__('Releated Blogs Columns', 'listdo'),
                        'required' => array('show_blog_releated', '=', '1'),
                        'options' => $columns,
                        'default' => 1
                    ),

                )
            );
            
            $this->sections = apply_filters( 'listdo_redux_framwork_configs', $this->sections, $sidebars, $columns );

            // 404 page
            $this->sections[] = array(
                'title' => esc_html__('404 Page', 'listdo'),
                'fields' => array(
                    array(
                        'id' => '404_breadcrumbs_settings',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Breadcrumbs Settings', 'listdo').'</h3>',
                    ),
                    array(
                        'id' => '404_transparent_header',
                        'type' => 'switch',
                        'title' => esc_html__('Transparent header ?', 'listdo'),
                        'default' => 1,
                    ),
                    array(
                        'id' => 'show_404_breadcrumbs',
                        'type' => 'switch',
                        'title' => esc_html__('Breadcrumbs', 'listdo'),
                        'default' => 1
                    ),
                    array (
                        'title' => esc_html__('Breadcrumbs Background Color', 'listdo'),
                        'subtitle' => '<em>'.esc_html__('The breadcrumbs background color of the site.', 'listdo').'</em>',
                        'id' => '404_breadcrumb_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'id' => '404_breadcrumb_image',
                        'type' => 'media',
                        'title' => esc_html__('Breadcrumbs Background', 'listdo'),
                        'subtitle' => esc_html__('Upload a .jpg or .png image that will be your breadcrumbs.', 'listdo'),
                    ),
                    array(
                        'id' => '404_general_settings',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('General Settings', 'listdo').'</h3>',
                    ),
                    array(
                        'id' => '404-image',
                        'type' => 'media',
                        'title' => esc_html__('404 Image', 'listdo'),
                        'subtitle' => esc_html__('Upload a  .png or .gif image for 404 page.', 'listdo'),
                    ),
                    array(
                        'id' => 'title_description',
                        'type' => 'text',
                        'title' => esc_html__('Title', 'listdo'),
                        'default' => 'Oop, that link is broken.'
                    ),
                )
            );
            
            
            // Style
            $this->sections[] = array(
                'icon' => 'el el-icon-css',
                'title' => esc_html__('Custom Style', 'listdo'),
                'fields' => array(
                    array(
                        'id' => 'custom_color',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Custom Color', 'listdo').'</h3>',
                    ),
                    array(
                        'title' => esc_html__('Main Theme Color', 'listdo'),
                        'subtitle' => '<em>'.esc_html__('The main color of the site.', 'listdo').'</em>',
                        'id' => 'main_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'title' => esc_html__('Button Background Color', 'listdo'),
                        'subtitle' => '<em>'.esc_html__('The main color of the site.', 'listdo').'</em>',
                        'id' => 'button_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),
                    array(
                        'title' => esc_html__('Button Background Hover Color', 'listdo'),
                        'subtitle' => '<em>'.esc_html__('The main color of the site.', 'listdo').'</em>',
                        'id' => 'button_hover_color',
                        'type' => 'color',
                        'transparent' => false,
                    ),

                    // Typography
                    array(
                        'id' => 'main_font_info',
                        'icon' => true,
                        'type' => 'info',
                        'raw' => '<h3> '.esc_html__('Custom Typography', 'listdo').'</h3>',
                    ),
                    array (
                        'title' => esc_html__('Main Font Face', 'listdo'),
                        'subtitle' => '<em>'.esc_html__('Pick the Main Font for your site.', 'listdo').'</em>',
                        'id' => 'main_font',
                        'type' => 'typography',
                        'line-height' => false,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => false,
                        'all_styles'=> true,
                        'font-size' => true,
                        'color' => false,
                        'default' => array (
                            'font-family' => '',
                            'subsets' => '',
                        )
                    ),
                    array(
                        'title' => esc_html__('Heading Font Face', 'listdo'),
                        'subtitle' => '<em>'.esc_html__('Pick the Heading Font for your site.', 'listdo').'</em>',
                        'id' => 'heading_font',
                        'type' => 'typography',
                        'line-height' => false,
                        'text-align' => false,
                        'font-style' => false,
                        'font-weight' => true,
                        'all_styles'=> true,
                        'font-size' => false,
                        'color' => false,
                        'default' => array (
                            'font-family' => '',
                            'subsets' => '',
                        )
                    ),
                )
            );

            // Social Media
            $this->sections[] = array(
                'icon' => 'el el-file',
                'title' => esc_html__('Social Media', 'listdo'),
                'fields' => array(
                    array(
                        'id' => 'facebook_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable Facebook Share', 'listdo'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'twitter_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable twitter Share', 'listdo'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'linkedin_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable linkedin Share', 'listdo'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'google_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable google plus Share', 'listdo'),
                        'default' => 1
                    ),
                    array(
                        'id' => 'pinterest_share',
                        'type' => 'switch',
                        'title' => esc_html__('Enable pinterest Share', 'listdo'),
                        'default' => 1
                    )
                )
            );
            $this->sections[] = array(
                'title' => esc_html__('Import / Export', 'listdo'),
                'desc' => esc_html__('Import and Export your Redux Framework settings from file, text or URL.', 'listdo'),
                'icon' => 'el-icon-refresh',
                'fields' => array(
                    array(
                        'id' => 'opt-import-export',
                        'type' => 'import_export',
                        'title' => 'Import Export',
                        'subtitle' => 'Save and restore your Redux options',
                        'full_width' => false,
                    ),
                ),
            );

            $this->sections[] = array(
                'type' => 'divide',
            );
        }

        /**
         * All the possible arguments for Redux.
         * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
         * */
        public function setArguments() {
            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $preset = listdo_get_demo_preset();
            
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'listdo_theme_options'.$preset,
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'),
                // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'),
                // Version that appears at the top of your panel
                'menu_type' => 'menu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true,
                // Show the sections below the admin menu item or not
                'menu_title' => esc_html__('Listdo Options', 'listdo'),
                'page_title' => esc_html__('Listdo Options', 'listdo'),

                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '',
                // Set it you want google fonts to update weekly. A google_api_key value is required.
                'google_update_weekly' => false,
                // Must be defined to add google fonts to the typography module
                'async_typography' => true,
                // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar' => true,
                // Show the panel pages on the admin bar
                'admin_bar_icon' => 'dashicons-portfolio',
                // Choose an icon for the admin bar menu
                'admin_bar_priority' => 50,
                // Choose an priority for the admin bar menu
                'global_variable' => 'apus_options',
                // Set a different name for your global variable other than the opt_name
                'dev_mode' => false,
                // Show the time the page took to load, etc
                'update_notice' => true,
                // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                'customizer' => true,
                // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority' => null,
                // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php',
                // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options',
                // Permissions needed to access the options panel.
                'menu_icon' => '',
                // Specify a custom URL to an icon
                'last_tab' => '',
                // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes',
                // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options',
                // Page slug used to denote the panel
                'save_defaults' => true,
                // On load save the defaults to DB before user clicks save or not
                'default_show' => false,
                // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '',
                // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,
                // Shows the Import/Export panel when not used as a field.

                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true,
                // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true,
                // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info' => false,
                // REMOVE

                // HINTS
                'hints' => array(
                    'icon' => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color' => 'lightgray',
                    'icon_size' => 'normal',
                    'tip_style' => array(
                        'color' => 'light',
                        'shadow' => true,
                        'rounded' => false,
                        'style' => '',
                    ),
                    'tip_position' => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect' => array(
                        'show' => array(
                            'effect' => 'slide',
                            'duration' => '500',
                            'event' => 'mouseover',
                        ),
                        'hide' => array(
                            'effect' => 'slide',
                            'duration' => '500',
                            'event' => 'click mouseleave',
                        ),
                    ),
                )
            );
            $this->args['intro_text'] = '';

            // Add content after the form.
            $this->args['footer_text'] = '';
            return $this->args;
        }

    }

    global $reduxConfig;
    $reduxConfig = new Listdo_Redux_Framework_Config();
}

if ( function_exists('apus_framework_redux_register_custom_extension_loader') ) {
    $preset = listdo_get_demo_preset();
    $opt_name = 'listdo_theme_options'.$preset;
    add_action("redux/extensions/{$opt_name}/before", 'apus_framework_redux_register_custom_extension_loader', 0);
}

function listdo_redux_remove_notice() {
    return 'bub';
}
$preset = listdo_get_demo_preset();
$opt_name = 'listdo_theme_options'.$preset;
add_action('redux/' . $opt_name . '/aNFM_filter', 'listdo_redux_remove_notice');