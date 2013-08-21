<?php

require_once '../../../etc/frontend.config.php';

require_once 'Hush/Util.php';

require_once 'Hush/Html/Element.php';
$ele = new Hush_Html_Element('a', false);
$ele->setBody('testLink')->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
	'href'	=> '#',
));
echo $ele->render();
echo "<br/><br/>\n";

require_once 'Hush/Html/Form.php';
$form = new Hush_Html_Form();
$form->setName('testForm')->setAction('testAction')->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
	'method'=> 'post',
));
//echo $form->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Button.php';
$input = new Hush_Html_Form_Button();
$input->setName('testButton')->setValue('testButton')->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
));
$input_str = $input->saveJson();
$input = $input->loadJson($input_str);
$form->addElement($input);
//echo $input->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Submit.php';
$input = new Hush_Html_Form_Submit();
$input->setName('testSubmit')->setValue('testSubmit')->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
));
$input_str = $input->saveJson();
$input = $input->loadJson($input_str);
$form->addElement($input);
//echo $input->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Text.php';
$input = new Hush_Html_Form_Text();
$input->setName('testText')->setValue('testText')->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
));
$input_str = $input->saveJson();
$input = $input->loadJson($input_str);
$form->addElement($input);
//echo $input->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/File.php';
$input = new Hush_Html_Form_File();
$input->setName('testFile')->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
));
$input_str = $input->saveJson();
$input = $input->loadJson($input_str);
$form->addElement($input);
//echo $input->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Textarea.php';
$input = new Hush_Html_Form_Textarea();
$input->setName('testTextarea')->setValue('testTextarea')->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
));
$input_str = $input->saveJson();
$input = $input->loadJson($input_str);
$form->addElement($input);
//echo $input->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Checkbox.php';
$input = new Hush_Html_Form_Checkbox();
$input->setName('testCheckbox')->setValue('2')->setOptions(array(
	'1'		=> 'Option-1',
	'2'		=> 'Option-2',
	'3'		=> 'Option-3',
));
$input_str = $input->saveJson();
$input = $input->loadJson($input_str);
$form->addElement($input);
//echo $input->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Radio.php';
$input = new Hush_Html_Form_Radio();
$input->setName('testRadio')->setValue(2)->setOptions(array(
	'1'		=> 'Option-1',
	'2'		=> 'Option-2',
	'3'		=> 'Option-3',
));
$input_str = $input->saveJson();
$input = $input->loadJson($input_str);
$form->addElement($input);
//echo $input->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Select.php';
$select = new Hush_Html_Form_Select();
$select->setName('testButton')->setValue(2)->setOptions(array(
	'1'		=> 'Option-1',
	'2'		=> 'Option-2',
	'3'		=> 'Option-3',
))->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
));
$select_str = $select->saveJson();
$select = $select->loadJson($select_str);
$form->addElement($select);
//echo $select->render();
//echo "<br/><br/>\n";

require_once 'Hush/Html/Form/Select.php';
$select = new Hush_Html_Form_Select();
$select->setName('testButton')->setValues(array(1,2,3))->setMultiple(5)->setOptions(array(
	'1'		=> 'Option-1',
	'2'		=> 'Option-2',
	'3'		=> 'Option-3',
))->setAttrs(array(
	'id'	=> 'test',
	'class'	=> 'test',
));
$select_str = $select->saveJson();
$select = $select->loadJson($select_str);
$form->addElement($select);
//echo $select->render();
//echo "<br/><br/>\n";

$form->buildForm();
$form_str = $form->saveJson();
$form = $form->loadJson($form_str);
//Hush_Util::dump($form);
echo $form->render();
echo "<br/><br/>\n";