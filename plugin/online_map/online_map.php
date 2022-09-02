<?php
/**
 * @package    online_map
 *
 * @author     a.popucheyev <vlasteg@mail.ru>
 * @copyright  A copyright
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Online_map plugin.
 *
 * @package   online_map
 * @since     1.0.0
 */
class plgSystemOnline_map extends CMSPlugin
{
	/**
	 * Application object
	 *
	 * @var    CMSApplication
	 * @since  1.0.0
	 */
	protected $app;
	
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  1.0.0
	 */
	protected $db;
	
	/**
	 * Affects constructor behavior. If true, language files will be loaded automatically.
	 *
	 * @var    boolean
	 * @since  1.0.0
	 */
	protected $autoloadLanguage = true;
	
	/**
	 * onAfterInitialise.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterInitialise()
	{
	
	}
	
	/**
	 * onAfterRoute.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterRoute()
	{
	
	}
	
	/**
	 * onAfterDispatch.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterDispatch()
	{
	
	}
	
	/**
	 * onAfterRender.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterRender()
	{
	
	}
	
	/**
	 * onAfterCompileHead.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterCompileHead()
	{
	
	}
	
	/**
	 * OnAfterCompress.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterCompress()
	{
	
	}
	
	/**
	 * onAfterRespond.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterRespond()
	{
	
	}
	
	/**
	 * Prepare form.
	 *
	 * @param JForm $form The form to be altered.
	 * @param mixed $data The associated data for the form.
	 *
	 * @return  boolean
	 *
	 * @since    1.0
	 */
	public function onContentPrepareForm(JForm $form, $data): bool {
		// Check we are manipulating the online_map module.
		if ($form->getName() !== 'com_modules.module' || !$form->getField('online_map_module', 'params')) {
			return true;
		}
		
		$script = <<<JS
		jQuery(function ($){
            $('form').attr('enctype', 'multipart/form-data');
		});
JS;
		JFactory::getDocument()->addScriptDeclaration($script);
		
		return true;
	}
	
	/**
	 * @param $context
	 * @param $table
	 *
	 * @return bool|mixed
	 *
	 * @since 1.0
	 */
	public function onExtensionBeforeSave($context, $table) {
		if ($context != 'com_modules.module' || $table->module != 'mod_online_map') return true;
		
		// Retrieve file details from uploaded file, sent from upload form
		$file = JFactory::getApplication()->input->files->get('jform');
		
		$file = $file['params']['upload_data_file'];
		// Import filesystem libraries. Perhaps not necessary, but does not hurt.
		jimport('joomla.filesystem.file');
		
		// Clean up filename to get rid of strange characters like spaces etc.
		$filename = JFile::makeSafe($file['name']);
		
		if(!$filename) return true;
		
		// Set up the source and destination of the file
		$src = $file['tmp_name'];
		$dest = JPATH_ROOT . "/modules/mod_online_map/uploads/$filename";
		
		// First verify that the file has the right extension. We need jpg only.
		if (strtolower(JFile::getExt($filename)) == 'json')
		{
			// TODO: Add security checks.
			
			if (JFile::upload($src, $dest))
			{
				$this->app->enqueueMessage(JText::sprintf('PLG_SYSTEM_ONLINE_MAP_FILE_UPLOAD_SUCCESS', $filename));
				return true;
			}
			else
			{
				$this->app->enqueueMessage(JText::sprintf('PLG_SYSTEM_ONLINE_MAP_FILE_UPLOAD_ERROR', $filename), 'error');
				return false;
			}
		}
		else
		{
			$this->app->enqueueMessage(JText::sprintf('PLG_SYSTEM_ONLINE_MAP_FILE_UPLOAD_FILE_TYPE_ERROR', $filename), 'error');
			
			return false;
		}
	}
}
