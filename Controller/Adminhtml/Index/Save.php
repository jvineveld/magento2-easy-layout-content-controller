<?php
namespace Jvi\Elcc\Controller\Adminhtml\Index;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Backend\App\Action;
use Jvi\Elcc\File\ElccUploader;

class Save extends Action{
	protected $_fileUploaderFactory;
	protected $resultJsonFactory;
	protected $urlInterface;
	protected $filesystem;
	protected $image_folder;
	protected $_objectManager;

	public function __construct(
		JsonFactory $resultJsonFactory,
	    Action\Context $context,
		UrlInterface $urlInterface,
		Filesystem $filesystem,
		RequestInterface $request,
		ElccUploader $uploader,
		\Magento\Framework\ObjectManagerInterface $objectManager
	) {
		$this->resultJsonFactory = $resultJsonFactory;
		$this->urlInterface = $urlInterface;
		$this->filesystem = $filesystem;
		$this->request = $request;
		$this->_objectManager = $objectManager;
		$this->_uploader = $uploader;

		$this->image_folder = 'images/';

	    parent::__construct($context);
	}

	private function fix_post_data(){
		$file = ['name' => 'elcc_image'];
		foreach ($_FILES['elccimage'] as $type => $block) {
			foreach ($block as $block_name => $sub_block) {
				foreach ($sub_block as $sub_block_name => $value) {
					$file[$type] = $value;
				}
			}
		}
		return $file;
	}

	public function execute(){
		$fileId = $this->fix_post_data();

		$uploader = $this->_uploader->create(['fileId' => $fileId]);

	    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
	    $uploader->setAllowRenameFiles(false);
	    $uploader->setFilesDispersion(false);

		$result = $this->resultJsonFactory->create();

		try {
			$path = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
		    	->getAbsolutePath($this->image_folder);
			$image = $uploader->save($path);
			$media_base = $this->urlInterface->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]);

			$returnObject = array_merge($image, [
				'url' => $media_base.$this->image_folder.$image['name']
			]);
			return $result->setData($returnObject);
		} catch (Exception $e) {
			return $result->setData(['error' => 1, 'message' => $e->getMessage()]);
		}
	}
}
