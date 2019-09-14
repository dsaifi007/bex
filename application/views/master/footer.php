<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$th_url = media_url();
$th_path = media_path();

$this->minify->js($this->config->item('global_plg_js'), 'global'); 
$this->minify->js($this->config->item('global_th_js'), 'globalth');
$this->minify->add_js($this->config->item('global_utility_js'), 'utility');

$js_file = 'js.min.js'; $js_groups = ['global', 'pageplg', 'globalth'];

if($is_admin) : 
$this->minify->js($this->config->item('global_th_admin_lyt_js'), 'globalth_admin_lyt');
$js_groups[] = 'globalth_admin_lyt';
?>
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner"> <?php echo $this->lang->line('site_copyright'); ?></div>
	<div class="scroll-to-top"><i class="icon-arrow-up"></i></div>
</div>
<!-- END FOOTER -->
<?php else : ?>
	
<?php endif; 

$js_groups = array_merge($js_groups, ['utility', 'page']);
?>

<!--[if lt IE 9]>
<?php if(count($this->config->item('IE9_LT')) > 0) : 
foreach($this->config->item('IE9_LT') as $v) : ?>
	<script type="text/javascript" src="<?php echo $th_url.$v; ?>"></script>
<?php endforeach; 
endif;

if(count($this->config->item('IE9_LT_outside')) > 0) :   
    foreach($this->config->item('IE9_LT_outside') as $v) : ?>
	<script type="text/javascript" src="<?php echo $v; ?>"></script>
<?php endforeach; 
endif;
?> 
<![endif]-->

<!-- BEGIN CORE PLUGINS -->
<?php 
$js_prefix = encode($this->current_app).'_'.encode($this->router->fetch_class().'_'.$this->router->fetch_method());
$file_name = $js_prefix.'.js';
foreach($js_groups as $grp) :
	/*if(isset($this->minify->js_array[$grp])) :
		foreach($this->minify->js_array[$grp] as $v) : ?>
			<script type="text/javascript" src="<?php echo $th_url.$v; ?>"></script>
		<?php endforeach;
	endif;*/
	
	if(!isset($this->minify->js_array[$grp])) : continue; 
        elseif(file_exists($th_path.$grp.'_'.$file_name)) : ?>
            <script type="text/javascript" src="<?php echo $th_url.$grp.'_'.$file_name; ?>"></script>
        <?php     
        else : 
            echo $this->minify->deploy_js(FALSE, $file_name, $grp);
        endif;
endforeach;
 
if(isset($inline_script)) : 
	if($inline_script !='') : 
?>
<script type="text/javascript" language="javascript">
	<?php echo $inline_script; ?>
</script>
<?php endif; endif; ?>
</body>
</html>