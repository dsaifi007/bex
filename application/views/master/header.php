<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$siteURL = base_url();
$this->minify->css($this->config->item('global_imp_css'), 'global');
$this->minify->css($this->config->item('global_th_css'), 'globalth');
$this->minify->css($this->config->item('global_final_css'), 'final');

$siteURL .= ($this->config->item('index_page')) ? $this->config->item('index_page') . '/' : '';

$css_groups = ['global', 'pageplg', 'globalth', 'page'];
if ($is_admin) : $css_groups[] = 'globaladmnpnlth';
    $this->minify->css($this->config->item('global_admin_th_css'), 'globaladmnpnlth');
endif;
$css_groups[] = 'final';

echo doctype('html5');
?>
<!-- Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6 -->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <title><?php echo $this->lang->line('page_title') . ' | ' . $this->lang->line('site_name'); ?></title>
        <!-- start: META -->
        <?php echo meta('Content-type', 'text/html; charset=' . $this->config->item('charset'), 'equiv'); ?>
        <!--[if IE]><?php echo meta('X-UA-Compatible', 'IE=edge,IE=9,IE=8,chrome=1', 'equiv'); ?><![endif]-->
        <?php
        $meta = array(
            array(
                'name' => 'viewport',
                'content' => 'width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0'
            ),
            array(
                'name' => 'apple-mobile-web-app-capable',
                'content' => 'yes'
            ),
            array(
                'name' => 'apple-mobile-web-app-status-bar-style',
                'content' => 'black'
            ),
            array(
                'name' => 'keywords',
                'content' => implode(', ', array($this->lang->line('page_keywords'), $this->lang->line('site_keywords')))
            ),
            array(
                'name' => 'description',
                'content' => implode(' ', array($this->lang->line('page_description'), $this->lang->line('site_description')))
            ),
            array(
                'name' => 'author',
                'content' => $this->lang->line('site_author')
            )
        );

        echo meta($meta);
        echo link_tag(media_url() . 'favicon.ico', 'shortcut icon', 'image/ico');
        ?>
		<base href="<?php echo $siteURL; ?>">
        <!-- start: MAIN CSS -->
        <?php
        echo link_tag('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all');
        //echo $this->minify->deploy_css(TRUE, $css_file, 'global'); 

        $th_url = media_url();
        foreach ($css_groups as $grp) :
            if (isset($this->minify->css_array[$grp])) :
                foreach ($this->minify->css_array[$grp] as $v) :
                    echo link_tag($th_url . $v);
                endforeach;
            endif;
        endforeach;
        
        /*$css_prefix = encode($this->current_app) . '_' . encode($this->router->fetch_class() . '_' . $this->router->fetch_method());
        $file_name = $css_prefix . '.css';
        foreach ($css_groups as $grp) :
            if (!isset($this->minify->css_array[$grp])) : continue;
            endif;
            echo $this->minify->deploy_css(TRUE, $file_name, $grp);
        endforeach;*/
        ?>
        <!-- end: MAIN CSS -->
        <script type="text/javascript" language="javascript">
            var siteurl = '<?php echo $siteURL; ?>';
            var numberRegex = new RegExp(/^[0-9]+$/);
            var list_limit = <?php echo $this->config->item('global_list_limit'); ?>;
            var list_limit_display = <?php echo $this->config->item('global_list_limit_box'); ?>;
            var current_app = '<?php echo $this->current_app; ?>';
            var master_app = '<?php echo $this->master_app; ?>';
        </script>
    </head>
    <!-- end: HEAD -->
    <!-- start: BODY -->