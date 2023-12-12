<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\EditManager;

use EasyCMS\src\Model\Entity\Page;

use EasyCMS\src\Model\Entity\Content;

class EditController extends Controller
{
    private $_manager;
    private $userId;

    public function __construct()
    {
        $this->_manager = new EditManager();
        if( isset( $_SESSION['userId'] ) ){
            $this->userId = $_SESSION['userId'];
        } else {
            $this->userId = null;
        }
        
        parent::__construct();
    }

    public function defaultAction()
    {
        if( $this->userId ){
                $data = [
                    'userId' => $this->userId,
                ]; 
                $this->render('edit', $data);
        } else {
            $this->render('index');
        }
        
    }

    public function editPageAction()
    {
        if( $listPages = $this->_manager->getAllPages() ){
            $data = [
                'userId' => $this->userId,
                'listPages' => $listPages
            ]; 
            $this->render('edit', $data);
        }
    }

    public function updatePageAction()
    {
        
        if( isset($_REQUEST['pageId']) ){
            $id = $_REQUEST['pageId'];
            if( $selectedPage = $this->_manager->getPageById($id) ){
                $listPages = $this->_manager->getAllPages();
                $data = [
                    'userId' => $this->userId,
                    'listPages' => $listPages,
                    'selectedPage' => $selectedPage
                ]; 
                $this->render('edit', $data);
            }
        }
    }

    public function updatePageValidAction()
    {
        $data = [
            'userId'        => $this->userId,
            'message'   => [
                'type'      => 'warning',
                'message'   => 'Erreur lors de la modification' 
            ]
        ];

        if ( isset($_REQUEST['id']) && !empty($_REQUEST['pageName']) && !empty($_REQUEST['stringIsHomePage']) ){
            $page = $this->_manager->getPageById( $_REQUEST['id']);
            $page->setPageName($_REQUEST['pageName']);
            if( $_REQUEST['stringIsHomePage'] === 'Oui' ){
                $page->setIsHomePage(1);
            }else{
                $page->setIsHomePage(0);
            }
            if( isset($_REQUEST['toPublish']) ){
                $page->setIsPublished(1);
            } else {
                $page->setIsPublished(0);
            }

            if($this->_manager->updatePage( $page )) {
                $data['message']['type'] = 'success';
                $data['message']['message'] = 'Modification de la page effectuée !';
            }
            $data['selectedPage'] = $page;
        }
        $data['listPages'] = $this->_manager->getAllPages();
        $this->render('edit', $data);
    }

    public function createPageAction()
    {
        $data = [
            'userId' => $this->userId,
            'listPages' => $this->_manager->getAllPages(),
            'createPage' => true
        ]; 
        $this->render('edit', $data);
    }

    public function createPageValidAction()
    {
        $data = [
            'userId'        => $this->userId,
            'message'   => [
                'type'      => 'warning',
                'message'   => 'Erreur lors de la création' 
            ],
            'createPage' => true
        ];

        if ( !empty($_REQUEST['pageName']) && !empty($_REQUEST['stringIsHomePage']) ){
            $page = new Page([]);
            $page->setPageName($_REQUEST['pageName']);
            if( $_REQUEST['stringIsHomePage'] === 'Oui' ){
                $page->setIsHomePage(1);
            }else{
                $page->setIsHomePage(0);
            }
            if( isset($_REQUEST['toPublish']) ){
                $page->setIsPublished(1);
            } else {
                $page->setIsPublished(0);
            }
            $page->setIdUser($this->userId);

            if($this->_manager->insertPage( $page )) {
                $data['message']['type'] = 'success';
                $data['message']['message'] = 'Création de la page effectuée !';
            }
            
        }
        $data['listPages'] = $this->_manager->getAllPages();
        $this->render('edit', $data);
    }

    public function editContentAction()
    {
        if( $listContents = $this->_manager->getAllContents() ){
            $data = [
                'userId' => $this->userId,
                'listContents' => $listContents
            ]; 
            
        }
        $this->render('edit', $data);
    }

    public function updateContentAction()
    {
        
        if( isset($_REQUEST['contentId']) ){
            $id = $_REQUEST['contentId'];
            if( $selectedContent = $this->_manager->getContentById($id) ){
                $listContents = $this->_manager->getAllContents();
                $listContentTypes = $this->_manager->getAllContentTypes();
                $data = [
                    'userId'            => $this->userId,
                    'listContents'      => $listContents,
                    'listContentTypes'  => $listContentTypes,
                    'selectedContent'   => $selectedContent
                ]; 
                
            }
        }
        $this->render('edit', $data);
    }

    public function updateContentValidAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la modification' 
        ];

        if ( isset($_REQUEST['id']) && !empty($_REQUEST['contentName']) && !empty($_REQUEST['contentDescription']) ){
            $content = $this->_manager->getContentById( $_REQUEST['id']);
            $content->setContentName($_REQUEST['contentName']);
            $content->setContentDescription($_REQUEST['contentDescription']);
            $contentType = $this->_manager->getContentTypeById($_REQUEST['contentType']);
            $content->setContentType($contentType);
            if( isset($_REQUEST['toPublish']) ){
                $content->setIsPublished(1);
            } else {
                $content->setIsPublished(0);
            }            

            if($this->_manager->updateContent( $content )) {
                $message['type'] = 'success';
                $message['message'] = 'Modification du contenu effectuée !';
            }
        }
        $listContents = $this->_manager->getAllContents();
        $listContentTypes = $this->_manager->getAllContentTypes();
        $data = [
            'userId'            => $this->userId,
            'listContents'      => $listContents,
            'listContentTypes'  => $listContentTypes,
            'message'           => $message,
            'selectedContent'   => $content
        ]; 
        $this->render('edit', $data);
    }

    public function createContentAction()
    {
        $data = [
            'userId' => $this->userId,
            'listContents' => $this->_manager->getAllContents(),
            'listContentTypes'  => $this->_manager->getAllContentTypes(),
            'createContent' => true
        ]; 
        $this->render('edit', $data);
    }

    public function createContentValidAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la modification' 
        ];

        if ( !empty($_REQUEST['contentName']) && !empty($_REQUEST['contentDescription']) ){
            $content = new Content([]);
            $content->setContentName($_REQUEST['contentName']);
            $content->setContentDescription($_REQUEST['contentDescription']);
            $contentType = $this->_manager->getContentTypeById($_REQUEST['contentType']);
            $content->setContentType($contentType);
            if( isset($_REQUEST['toPublish']) ){
                $content->setIsPublished(1);
            } else {
                $content->setIsPublished(0);
            }
            $content->setIdUser($this->userId);
            if($this->_manager->insertContent( $content )) {
                $message['type'] = 'success';
                $message['message'] = 'Modification du contenu effectuée !';
            }
        }
        $listContents = $this->_manager->getAllContents();
        $listContentTypes = $this->_manager->getAllContentTypes();
        $data = [
            'userId'            => $this->userId,
            'listContents'      => $listContents,
            'listContentTypes'  => $listContentTypes,
            'message'           => $message,
            'selectedContent'   => $content
        ]; 
        $this->render('edit', $data);
    }
}