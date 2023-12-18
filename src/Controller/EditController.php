<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\EditManager;

use EasyCMS\src\Model\Entity\Page;

use EasyCMS\src\Model\Entity\Content;
use EasyCMS\src\Model\Entity\Navigation;
use EasyCMS\src\Model\Entity\Position;

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

    public function deletePageAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la suppression' 
        ];

        if ( isset($_REQUEST['id']) ) {
            $id = $_REQUEST['id'];
            if( $this->_manager->deletePageById( $id ) ){
                $message['type'] = 'success';
                $message['message'] = 'Suppression de la page effectuée !';
                $selectedPage = null;
            } else {
                $selectedPage = $this->_manager->getPageById($id);
            }
        } 
        $listPages = $this->_manager->getAllPages();
        $data = [
            'userId'            => $this->userId,
            'listPages'         => $listPages,
            'message'           => $message,
            'selectedPage'      => $selectedPage
        ];

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
        $data = [];
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
                $listPositions = $this->_manager->getAllPositions();
                $data = [
                    'userId'            => $this->userId,
                    'listContents'      => $listContents,
                    'listContentTypes'  => $listContentTypes,
                    'listPositions'     => $listPositions,
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

        $content = null;
        if ( isset($_REQUEST['id']) && !empty($_REQUEST['contentName']) && !empty($_REQUEST['contentDescription']) ){
            $content = $this->_manager->getContentById( $_REQUEST['id']);
            $content->setContentName($_REQUEST['contentName']);
            $contentType = $this->_manager->getContentTypeById($_REQUEST['contentType']);
            $content->setContentType($contentType);
            $content->setContentDescription($_REQUEST['contentDescription']);
            if( isset($_FILES['img']) ){
                
                if( $_FILES['img']['error'] === UPLOAD_ERR_OK ){
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];
                    $uploadDir = 'assets/images/';
                    $uploadFile = $uploadDir . basename($_FILES['img']['name']);
                    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
                    $maxFileSize = 128 * 1024; // 128 Ko

                    // Vérifier la taille du fichier
                    if ($_FILES['img']['size'] <= $maxFileSize) {
                        // Vérifier l'extension du fichier
                        if (in_array($imageFileType, $allowedExtensions)) {
                            // Déplacer le fichier téléchargé vers le répertoire cible
                            if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadFile)) {
                                // Le fichier a été téléchargé avec succès

                                // Enregistrez le nom du fichier dans la description du contenu
                                $imageName = $_FILES['img']['name'];
                                $content->setContentDescription($imageName);

                            } else {
                                // Une erreur s'est produite lors du téléchargement du fichier
                                $message['type'] = 'warning';
                                $message['message'] = 'Erreur lors du téléchargement du fichier.';
                            }
                        } else {
                            // Extension de fichier non autorisée
                            $message['type'] = 'warning';
                            $message['message'] = 'Seuls les fichiers JPG, JPEG et PNG sont autorisés.';
                        }
                    } else {
                        // Taille de fichier excessive
                        $message['type'] = 'warning';
                        $message['message'] = 'La taille du fichier ne doit pas dépasser 128 Ko.';
                    }
                } else {
                    // Aucun fichier téléchargé ou une erreur s'est produite
                    $message['type'] = 'warning';
                    $message['message'] = 'La taille du fichier ne doit pas dépasser 128 Ko.';
                }

            }
                
            
            if( isset($_REQUEST['toPublish']) ){
                $content->setIsPublished(1);
            } else {
                $content->setIsPublished(0);
            }
            $idPosition = $_REQUEST['position'];
            $idPosition = ($idPosition === "") ? null : $idPosition;
            if ( $idPosition === NULL ) {
                $position = NULL;
            } else {
                $position = $this->_manager->getPositionById($_REQUEST['position']);
            }       
            $content->setPosition($position);
            $content->setIdUser($this->userId);
            if($this->_manager->updateContent( $content )) {
                $message['type'] = 'success';
                $message['message'] = 'Modification du contenu effectuée !';
            }
        }
        $listContents = $this->_manager->getAllContents();
        $listContentTypes = $this->_manager->getAllContentTypes();
        $listPositions = $this->_manager->getAllPositions();
        $data = [
            'userId'            => $this->userId,
            'listContents'      => $listContents,
            'listContentTypes'  => $listContentTypes,
            'listPositions'     => $listPositions,
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
            'listPositions' => $this->_manager->getAllPositions(),
            'createContent' => true
        ]; 
        $this->render('edit', $data);
    }

    public function deleteContentAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la suppression' 
        ];

        if ( isset($_REQUEST['id']) ) {
            $id = $_REQUEST['id'];
            if( $this->_manager->deleteContentById( $id ) ){
                $message['type'] = 'success';
                $message['message'] = 'Suppression du contenu effectuée !';
                $selectedContent = $this->_manager->getContentById($id);
            }
        }
        $listContents = $this->_manager->getAllContents();
        $listContentTypes = $this->_manager->getAllContentTypes();
        $listPositions = $this->_manager->getAllPositions();
        $data = [
            'userId'            => $this->userId,
            'listContents'      => $listContents,
            'listContentTypes'  => $listContentTypes,
            'listPositions'     => $listPositions,
            'message'           => $message,
            'selectedContent'   => $selectedContent
        ];

        $this->render('edit', $data);
    }

    public function createContentValidAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la création' 
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
            $position = new Position([]);
            if( isset($_REQUEST['position']) ){
                $idPosition = $_REQUEST['position'];
                $idPosition = ($idPosition === "") ? 0 : $idPosition;
                if ( $idPosition != 0 ) {
                    $position = $this->_manager->getPositionById($_REQUEST['position']);
                }                   
            }
            $content->setPosition($position);
            var_dump($_SESSION['userId']);

            $content->setIdUser($this->userId);
            if($this->_manager->insertContent( $content )) {
                $message['type'] = 'success';
                $message['message'] = 'Création du contenu effectuée !';
            }
        }
        $listContents = $this->_manager->getAllContents();
        $listContentTypes = $this->_manager->getAllContentTypes();
        $listPositions = $this->_manager->getAllPositions();
        $data = [
            'userId'            => $this->userId,
            'listContents'      => $listContents,
            'listContentTypes'  => $listContentTypes,
            'listPositions'     => $listPositions,
            'message'           => $message,
            'createContent' => true
        ]; 
        $this->render('edit', $data);
    }

    public function editNavAction()
    {
        if( $listNavigations = $this->_manager->getAllNavigations() ){
            $data = [
                'userId' => $this->userId,
                'listNavigations' => $listNavigations
            ]; 
            $this->render('edit', $data);
        }
    }

    public function updateNavAction()
    {
        
        if( isset($_REQUEST['navId']) ){
            $id = $_REQUEST['navId'];
            if( $selectedNav = $this->_manager->getNavigationById($id) ){
                $listNavigations = $this->_manager->getAllNavigations();
                $listNavPages = $this->_manager->getAllPages();
                $data = [
                    'userId'            => $this->userId,
                    'listNavigations'   => $listNavigations,
                    'listNavPages'      => $listNavPages,
                    'selectedNav'       => $selectedNav
                ]; 
                
            }
        }
        $this->render('edit', $data);
    }

    public function updateNavValidAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la modification' 
        ];

        if ( isset($_REQUEST['id']) && !empty($_REQUEST['navName']) ){
            $nav = $this->_manager->getNavigationById( $_REQUEST['id']);
            $nav->setNavName($_REQUEST['navName']);
            $navPage = $this->_manager->getPageById($_REQUEST['navPage']);
            $nav->setPage($navPage);
            if( isset($_REQUEST['toPublish']) ){
                $nav->setIsPublished(1);
            } else {
                $nav->setIsPublished(0);
            }            

            if($this->_manager->updateNavigation( $nav )) {
                $message['type'] = 'success';
                $message['message'] = 'Modification de la navigation effectuée !';
            }
        }
        
        $listNavigations = $this->_manager->getAllNavigations();
        $listNavPages = $this->_manager->getAllPages();
        $data = [
            'userId'            => $this->userId,
            'listNavigations'   => $listNavigations,
            'listNavPages'      => $listNavPages,
            'message'           => $message,
            'selectedNav'       => $nav
        ];

        $this->render('edit', $data);
    }

    public function deleteNavAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la suppression' 
        ];

        if ( isset($_REQUEST['id']) ) {
            $id = $_REQUEST['id'];
            if( $this->_manager->deleteNavById( $id ) ){
                $message['type'] = 'success';
                $message['message'] = 'Suppression de la navigation effectuée !';
                $selectedNav = null;
            } else {
                $selectedNav = $this->_manager->getPageById($id);
            }
        } 
        $listNavigations = $this->_manager->getAllNavigations();
        $listNavPages = $this->_manager->getAllPages();
        $data = [
            'userId'            => $this->userId,
            'listNavigations'   => $listNavigations,
            'listNavPages'      => $listNavPages,
            'message'           => $message,
            'selectedNav'       => $selectedNav
        ];

        $this->render('edit', $data);
    }

    public function createNavAction()
    {    
        $data = [
            'userId'            => $this->userId,
            'listNavigations'   => $this->_manager->getAllNavigations(),
            'listNavPages'      => $this->_manager->getAllPages(),
            'createNav'         => true
        ]; 
        $this->render('edit', $data);
    }

    public function createNavValidAction()
    {
        $message = [
            'type'      => 'warning',
            'message'   => 'Erreur lors de la création' 
        ];

        if ( !empty($_REQUEST['navName']) ){
            $nav = new Navigation([]);
            $nav->setNavName($_REQUEST['navName']);
            $navPage = $this->_manager->getPageById($_REQUEST['navPage']);
            $nav->setPage($navPage);
            if( isset($_REQUEST['toPublish']) ){
                $nav->setIsPublished(1);
            } else {
                $nav->setIsPublished(0);
            }            
            $nav->setIdUser($this->userId);
            if($this->_manager->insertNavigation( $nav )) {
                $message['type'] = 'success';
                $message['message'] = 'Création de la navigation effectuée !';
            }
        }

        $listNavigations = $this->_manager->getAllNavigations();
        $listNavPages = $this->_manager->getAllPages();
        $data = [
            'userId'            => $this->userId,
            'listNavigations'   => $listNavigations,
            'listNavPages'      => $listNavPages,
            'message'           => $message,
            'createNav'         => true
        ];
        $this->render('edit', $data);
    }

    

    
}