<?php

namespace EasyCMS\src\Controller;

use EasyCMS\src\Model\WebsiteManager;

class WebsiteController extends Controller
{
    private $_manager;
    private $userId;

    public function __construct()
    {
        $this->_manager = new WebsiteManager();
        if( isset( $_SESSION['userId'] ) ){
            $this->userId = $_SESSION['userId'];
        } else {
            $this->userId = null;
        }
        
        parent::__construct();
    }

    public function defaultAction()
    {
        $data=[];
        if( $homePage = $this->_manager->getPublishedHomePage()){
            if( $listContentsOfHomePage = $this->_manager->getPublishedContentsByIdPage( $homePage->getId() ) ){
                // Trier la liste par positionNumber
                usort($listContentsOfHomePage, function($a, $b) {
                    return $a->getPosition()->getPositionNumber() - $b->getPosition()->getPositionNumber();
                });
                if( $listNav = $this->_manager->getAllPublishedNav() ){
                    $data = [
                        'userId'                    => $this->userId,
                        'homePage'                  => $homePage,
                        'listContentsOfHomePage'    => $listContentsOfHomePage,
                        'listNav'                   => $listNav
                    ];

                }                
            }
        }
        $this->render('website', $data);
    }

    public function selectPageAction(){
        
        if( isset( $_REQUEST['pageId'] ) ){
            
            if($page = $this->_manager->getPageById($_REQUEST['pageId'])){
                if($listContents = $this->_manager->getPublishedContentsByIdPage( $_REQUEST['pageId'] )){
                    
                    usort($listContents, function($a, $b) {
                        return $a->getPosition()->getPositionNumber() - $b->getPosition()->getPositionNumber();
                    });
                    
                    
                }
            }
            
        }
        $data = [
            'userId'                    => $this->userId,
            'listNav'                   => $this->_manager->getAllPublishedNav(),
            'page'                      => $page,
            'listContents'              => $listContents
        ];
        $this->render('website', $data);
    }
}