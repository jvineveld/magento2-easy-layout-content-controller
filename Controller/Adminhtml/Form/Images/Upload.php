<?php
namespace Jvi\Elcc\Controller\Adminhtml\Form\Images;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;

class Upload extends Action{
	protected $_fileUploaderFactory;

	public function __construct(
	    \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
	    Action\Context $context
	) {

	    $this->_fileUploaderFactory = $fileUploaderFactory;
	    parent::__construct($context);
	}

	public function execute(){

	    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'elccimage[2][background_image][image]']);

	    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

	    $uploader->setAllowRenameFiles(false);

	    $uploader->setFilesDispersion(false);

	    $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)
	    	->getAbsolutePath('images/');

	    $uploader->save($path);

	}

}
