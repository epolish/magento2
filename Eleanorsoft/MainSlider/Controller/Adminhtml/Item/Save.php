<?php

namespace Eleanorsoft\MainSlider\Controller\Adminhtml\Item;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends Index
{
    private $destination;
    private $allowedImages = ['image', 'background_image'];

    const UPLOAD_DIRECTORY = 'main-slider';

    /**
     * @return void
     */
    public function execute()
    {
        $isPost = $this->getRequest()->getPost();

        if ($isPost) {
            $newsModel = $this->_itemFactory->create();
            $newsId = $this->getRequest()->getParam('id');

            $formData = $this->getRequest()->getParam('item');
            $this->destination = $this->fileSystem
                ->getDirectoryWrite(DirectoryList::MEDIA)
                ->getAbsolutePath(self::UPLOAD_DIRECTORY);

            $oldImages = [];
            if (isset($formData['id'])) {
                $newsModel->load($formData['id']);

                foreach ($this->allowedImages as $allowedImage) {
                    $oldImages[$allowedImage] = $newsModel->getData($allowedImage);
                }
            }
            $newsModel->setData($formData);

            foreach ($this->allowedImages as $allowedImage) {
                $oldImage = isset($oldImages[$allowedImage]) ? $oldImages[$allowedImage] : '';

                $this->uploadImage($newsModel, $allowedImage, $oldImage);
            }

            if (!$newsModel->getId()) {
                $newsModel->setCreatedAt(date('Y-m-d H:i:s'));
            }

            try {
                // Save news
                $newsModel->save();

                // Display success message
                $this->messageManager->addSuccess(__('The slide has been saved.'));

                // Check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $newsModel->getId(), '_current' => true]);
                    return;
                }

                // Go to grid page
                $this->_redirect('*/*/');
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }

            $this->_getSession()->setFormData($formData);
            $this->_redirect('*/*/edit', ['id' => $newsId]);
        }
    }

    /**
     * @return $uploader
     */
    private function getUploader($imageName)
    {
        $uploader = $this->uploaderFactory->create(['fileId' => $imageName]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $uploader->setAllowCreateFolders(true);

        return $uploader;
    }

    /**
     * @return void
     */
    private function uploadImage(&$model, $imageName, $oldImage)
    {
        if (isset($_FILES[$imageName]) && $_FILES[$imageName]['size'] > 0) {
            try {
                $uploader = $this->getUploader($imageName);
                $resultImage = (
                $result = $uploader->save($this->destination)
                ) ? self::UPLOAD_DIRECTORY . $result['file'] : $oldImage;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __($e->getMessage())
                );
                $resultImage = $oldImage;
            }
        } else {
            $resultImage = $oldImage;
        }

        $model->setData($imageName, $resultImage);
    }
}
