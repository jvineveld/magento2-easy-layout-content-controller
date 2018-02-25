<?php

namespace Jvi\Elcc\Controller\Adminhtml\Index;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save{
	private $dirlist;

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 */
	public function __construct(DirectoryList $dirlist) {
		$this->_save_image();
		$this->dirlist = $dirlist;

	}

	private function _save_image(){
		$profileImage = $this->getRequest()->getFiles('profile');
		$fileName = ($profileImage && array_key_exists('name', $profileImage)) ? $profileImage['name'] : null;

		if ($profileImage && $fileName) {
		    try {
		        /** @var \Magento\Framework\ObjectManagerInterface $uploader */
		        $uploader = $this->_objectManager->create(
		            'Magento\MediaStorage\Model\File\Uploader',
		            ['fileId' => 'profile']
		        );
		        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
		        /** @var \Magento\Framework\Image\Adapter\AdapterInterface $imageAdapterFactory */
		        $imageAdapterFactory = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')
		            ->create();
		        $uploader->setAllowRenameFiles(true);
		        $uploader->setFilesDispersion(true);
		        $uploader->setAllowCreateFolders(true);
		        /** @var \Magento\Framework\Filesystem\Directory\Read $mediaDirectory */
		        $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
		            ->getDirectoryRead(DirectoryList::MEDIA);

		        $result = $uploader->save(
		            $mediaDirectory
		                ->getAbsolutePath('Modulename/Profile')
		        );
		        //$data['profile'] = 'Modulename/Profile/'. $result['file'];
		        $model->setProfile('Modulename/Profile'.$result['file']); //Database field name
		    } catch (\Exception $e) {
		        if ($e->getCode() == 0) {
		            $this->messageManager->addError($e->getMessage());
		        }
		    }
		}
	}

	/**
	 * @return \Magento\Framework\Controller\Result\Json
	 */
	public function execute() {
		/** @var \Magento\Framework\Controller\Result\Json $result */
		$result = $this->resultJsonFactory->create();
		return $result->setData(['templates' => $this->templates]);
	}
}
