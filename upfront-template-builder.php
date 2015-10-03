<?php
/*
Plugin Name: Upfront Template Helper
Plugin URI:
Description: generates php for upfront layouts
Author: Mike Saunders
Author URI: mikesaunders.co.uk
Version: 0.1
License: GPL
*/


class Upfront_Template_Builder
{

	public function __construct() {
		add_action( 'admin_menu', array($this, 'register_menu' )  );
		add_action( "admin_enqueue_scripts", array($this,"queue_scripts" ));

	}

	function register_menu() {

		add_menu_page( 'Upfront Template Helper', 'Template Builder', 'manage_options', 'upfront-template-helper', array($this, 'upfront_template_helper_admin_page' ), '', "21.4" );

	}

	function queue_scripts($hook)
	{
		if ($hook == "toplevel_page_upfront-template-helper")
		{
			wp_enqueue_style('highlightjs', '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.8.0/styles/default.min.css');
			wp_enqueue_script('highlightjs', '//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.8.0/highlight.min.js');
		}

	}

	function tidy_array($arr)
	{
		return preg_replace('(\d+\s=>)', "", var_export($arr, true));
	}
	//experimented with making it look nice but didn't bother
	function format_php_export($arrayRep) {
		$arrayRep = preg_replace('/[ ]{2}/', "\t", $arrayRep);
		$arrayRep = preg_replace("/\=\>[ \n\t]+array[ ]+\(/", '=> array(', $arrayRep);
		return $arrayRep = preg_replace("/\n/", "\n\t", $arrayRep);
	}

	function upfront_template_helper_admin_page()
	{
		$specificity = $_POST["template"];

		$layout_ids = array(
			'specificity' => $specificity
		);

		$layout = Upfront_Layout::from_entity_ids($layout_ids);
		$storageKey = $layout->get_storage_key();
		$layout_data = $layout->to_php();


		$regionNames = array();
		$regionForm = "";

		foreach($layout_data["regions"] as $region)
		{
			$regionNames[$region["name"]] = $region["title"];

			$regionForm .= '<div class="region-check"><label for="'.$region["name"].'">'.$region["title"].'</label><input type="checkbox" id="'.$region["name"].'" name="'.$region["name"].'"> </div>
			';
		}

		$exportRegionNames = array();
		foreach($_POST as $key => $value)
		{
			if ($value == 'on') $exportRegionNames[] = $key;
		}
		$exportRegions = array();
		foreach($layout_data["regions"] as $region)
		{
			if(in_array($region["name"],$exportRegionNames))
			{
				$exportRegions[] = $region;
			}
		}
		$codeOutput = "";
		// create php output

		foreach($exportRegions as $region)
		{
			$codeOutput .= '$'.$region["name"].' = upfront_create_region('.var_export($region,true).');

$regions->add($'.$region["name"].');

';
		}


		$codeOutput = highlight_string($codeOutput,true);

		?>
		<script>hljs.initHighlightingOnLoad();</script>
		<h2>Regions in this menu</h2>
		<form method="post" action="admin.php?page=upfront-template-helper">

			<table class="form-table">
				<tr>
					<td><label for="template-specificity">Template:</label></td>
					<td><input type="text" id="template-specificity" name="template" class="regular-text"  value="<?php echo $specificity; ?>"></td>
				</tr>
				<tr>
					<td>
						<p class="submit">
							<input type="submit" id="submit" class="button button-primary" value="Load Data">
						</p>
					</td>
				</tr>
			</table>
			<label for="template-specificity">

			</label>
		</form>
		<table class="widefat">
			<thead>
			<tr>
				<th class="row-title">Attribute</th>
				<th>Value</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td class="row-title"><label for="tablecell">Template: </label></td>
				<td><?php echo $specificity ?> </td>
			</tr>
			<tr>
				<td class="row-title"><label for="tablecell">Storage Key: </label></td>
				<td><?php echo  $storageKey ?> </td>
			</tr>
			<tr>
				<td class="row-title"><label for="tablecell">Regions: </label></td>
				<td><?php echo count($layout_data["regions"]); ?> </td>
			</tr>
			<tr>
				<td class="row-title"><label for="tablecell">Properties: </label></td>
				<td><?php echo count($layout_data["properties"]); ?> </td>
			</tr>
			<tr>
				<td class="row-title"><label for="tablecell">Wrappers: </label></td>
				<td><?php echo count($layout_data["wrappers"]); ?> </td>
			</tr>
			</tbody>
		</table>
		<style>
			.region-check{
				display: inline-block;
				margin: 5px;
				padding: 5px;
				background-color: #fff;
			}
			.upfront-regions > div > input[type="checkbox"]{
				margin-left: 5px;
				vertical-align: middle;
			}
			.upfront-regions > div > label{
				margin: 5px;
				padding:2px;
			}
			.upfront-regions{

			}
		</style>
		<form class="upfront-regions" action="admin.php?page=upfront-template-helper" method="post">
			<input type="hidden" name="template" value="<?php echo $specificity; ?>">
			<?php echo $regionForm; ?>
			<div><input class="button button-secondary" type="submit" id="submit" value="Export"></div>
		</form>
		<div>
			<pre class="php">
			<?php echo $codeOutput; ?>
			</pre>
		</div>
		<?php
	}
}
if(is_admin())
{
	$utb = new Upfront_Template_Builder();
}


