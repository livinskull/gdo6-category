<?php
namespace GDO\Category\Method;

use GDO\Category\Category;
use GDO\Category\GDT_Category;
use GDO\Core\Website;
use GDO\DB\Cache;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;

final class Add extends MethodForm
{
	public function createForm(GDT_Form $form)
	{
		$table = Category::table();
		$form->addField($table->gdoColumn('cat_name'));
		$form->addField(GDT_Category::make('cat_parent')->emptyLabel('select_parent_category'));
		$form->addField(GDT_AntiCSRF::make());
		$form->addField(GDT_Submit::make());
	}
	
	public function formValidated(GDT_Form $form)
	{
		Category::blank($form->getFormData())->insert();
		Category::table()->rebuildFullTree();
		Cache::unset('gwf_category');
		return $this->message('msg_category_added')->add(Website::redirectMessage(href('Category', 'Overview')));
	}
}