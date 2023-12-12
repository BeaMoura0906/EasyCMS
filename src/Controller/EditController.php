<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\EditManager;

use EasyCMS\src\Model\Entity\Page;

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
            ]
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

            if($this->_manager->createPage( $page )) {
                $data['message']['type'] = 'success';
                $data['message']['message'] = 'Création de la page effectuée !';
            }
            
        }
        $data['listPages'] = $this->_manager->getAllPages();
        $this->render('edit', $data);
    }
}