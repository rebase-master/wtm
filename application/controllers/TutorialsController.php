<?php

class TutorialsController extends Zend_Controller_Action
{

    public function init()
    {
	    return $this->_redirect('./notes');
    }

    public function indexAction()
    {
//	    $mdlCategory = new Model_Notes();
//	    $this->view->results = $this->organizeCategories($mdlCategory->getSubjectCategories());
    }

	//URL: tutorials/:param
	public function topicAction(){
		$slug = urldecode($this->getRequest()->getParam('param'));

		if(empty($slug))
			return $this->_forward('index');
		else{
			$mdlNotesContent = new Model_NotesContent();
			$notes = $mdlNotesContent->findAllBySlug($slug);
			$filter = new Zend_Filter_Int();

			if(!empty($notes) && count($notes) > 0){
//				var_dump($notes);
//				die;
				$this->view->title = $notes[0]['heading'];
				$currentPage=1;
				$i = $this->_request->getQuery('page');
				$i = $filter->filter($i);
				if(!empty($i)){
					$currentPage = $i;
				}
				$this->view->limit = $limit = 1;
				$paginator=Zend_Paginator::factory($notes);
				$paginator->setItemCountPerPage($limit);
				$paginator->setPageRange(ceil(count($notes)/$limit));
				$paginator->setCurrentPageNumber($currentPage);
				$this->view->paginator = $paginator;
				$this->view->pageRange=count($notes);
				$this->view->currentPage = $currentPage;
			}else{
				$this->view->title = "Content not updated. Check back later.";
			}

		}

	}

	public function peopleAction()
	{
		$this->_helper->_layout->setLayout('_2col');

		$mdlNotes=new Model_Notes();

		if($this->_request->getParam('poi') && $this->_request->getParam('pid') )
		{
			$filter = new Zend_Filter_Int();
			$id = $this->_request->getParam('pid');
			$this->view->person = $mdlNotes->findById($filter->filter($id));
		}else{
			$this->view->people = $mdlNotes->find('people');
		}
	}


    public function installingAntAction(){
    }

	private function organizeCategories($categories){
		$results = array();
		foreach($categories as $category){
			if(array_key_exists($category['category'], $results)){
				array_push($results[$category['category']], $category);
			}else{
				$results[$category['category']] = array();
				array_push($results[$category['category']], $category);
			}
		}
		return $results;
	}

}







