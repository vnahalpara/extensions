<?php
ini_set('memory_limit', '500M');
ini_set('max_execution_time', 0);
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
require_once 'app/Mage.php';
umask(0);
Mage::app();
$attributeCode = $_GET['code'];
if(!$attributeCode)
{
	die('add attribute code in url using query string. i.e. example.com?code=size');
}
$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeCode);
$id = $attribute->getId();
function saveOption($arr,$id){
	$attr_model = Mage::getModel('catalog/resource_eav_attribute');
	$attr_model->load($id);
	$data = array();
	$i=1;
	foreach ($arr as $key => $label) {

		$values = array($key => array(0 => $label['orglab']));
		$order = array($key => $i);
		$data['option']['value'] = $values;
		$data['option']['order'] = $order;
		$attr_model->addData($data);


		try {
			$attr_model->save();
			echo "option-- ".$label['orglab']." --saved----<br>";
		} catch (Exception $e) {
		 	echo $e->getMessage();
		}
		$i++;
	}
	echo "total ".($i-1)." items update";
}
$model = Mage::getResourceModel('catalog/product')
        ->getAttribute($attributeCode)
        ->getSource()
        ->getAllOptions();
echo "<pre>";
$arr = array();
foreach ($model as $mod) {
	if($mod['value'])
	{
		$res = str_replace('/', '-', $mod['label']);
		$res = preg_replace("/[^0-9.Xx-]/", "", $res);
		$matches = array();
		preg_match("/^[0-9]+/", $res, $matches);
		$res = $matches[0];
		$arr[$mod['value']] = array('intlab'=>$res,'orglab'=>$mod['label']); 
	}
}
asort($arr);
saveOption($arr,$id);
//print_r($arr);
die;
?>