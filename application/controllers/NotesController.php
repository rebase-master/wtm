<?php

class NotesController extends Zend_Controller_Action
{

    public function init()
    {
	    require_once APPLICATION_PATH.'/../library/MobileDetect/Mobile_Detect.php';
        $this->_helper->_layout->setLayout('_tuts');

	    $mdlCategory = new Model_Notes();
	    $detect = new Mobile_Detect;

	    $this->view->results  = $this->organizeCategories($mdlCategory->getSubjectCategories());
	    $this->view->isMobile = $detect->isMobile();
    }

    public function indexAction()
    {
    }

	//URL: subject/:param
	public function subjectAction(){
		$slug = urldecode($this->getRequest()->getParam('param'));
		$filter = new Zend_Filter_Int();

		if(empty($slug))
			return $this->_redirect('notes/computer-science');
		else {
			$mdlNotesContent = new Model_NotesContent();
			$this->view->subject = $subject = ucwords(str_replace("-", " ", $slug));
			$notes = $mdlNotesContent->findAllByCategory($subject);
			if(count($notes) == 0){
				$notes = $mdlNotesContent->findAllByNotesSlug($slug);
			}

			if (count($notes) > 0){
				$currentPage = 1;
				$i = $this->_request->getQuery('page');
				$i = $filter->filter($i);
				if (!empty($i)) {
					$currentPage = $i;
				}
				$this->view->limit = $limit = 10;
				$paginator = Zend_Paginator::factory($notes);
				$paginator->setItemCountPerPage($limit);
				$paginator->setPageRange(ceil(count($notes) / $limit));
				$paginator->setCurrentPageNumber($currentPage);
				$this->view->paginator = $paginator;
				$this->view->pageRange = count($notes);
				$this->view->currentPage = $currentPage;
			}
		}

	}




	// THIS ROUTE IS CURRENTLY NOT IN USE
	//URL: notes/:param
	public function topicAction(){
		$slug = urldecode($this->getRequest()->getParam('param'));

		echo $slug;
		die;
		if(empty($slug))
			return $this->_forward('index');
		else{
			$mdlNotesContent = new Model_NotesContent();
//			$notes = $mdlNotesContent->findAllBySlug($slug);
			$notes = $mdlNotesContent->findAllByCategory($slug);
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




	//URL: notes/:category/:slug
	public function subjectNotesAction(){
		$category = urldecode($this->getRequest()->getParam('category'));
		$slug = urldecode($this->getRequest()->getParam('slug'));

		if(empty($slug) && empty($category))
			return $this->_redirect('notes/computer-science');
		else if(empty($slug)){
			return $this->_forward('subject', 'notes','default', array('param' => $category));
		}
		else{
			$mdlNotesContent = new Model_NotesContent();
			$notes = $mdlNotesContent->findAllByCategorySlug($category, $slug);

			if(!empty($notes) && count($notes) > 0){

				$notesId = $notes['notes_id'];
				$excludeId = $notes['id'];
				$this->view->related = $r = $mdlNotesContent->findRelatedNotes($notesId, $excludeId, 10);
				$this->view->title = $notes['heading'];
				$this->view->notes = $notes;

				$this->view->meta =
					array(
						'name'          =>  $notes['sub_category']." - ".$notes['heading'],
						'description'   =>  strip_tags($notes['extract']),
						'image'         =>  BASE_URL.'images/content/uploads/'.$notes['cover_image'],
						'title'         =>  $notes['sub_category']." - ".$notes['heading'],
						'url'           =>  BASE_URL.'notes/'.$category.'/'.$slug
					);

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







