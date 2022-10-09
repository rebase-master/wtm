<?php
class Model_Quotes extends Zend_Db_Table_Abstract{
    
    protected $_name='quotes';
	protected $_cols = array('id','quote','slug','author','visible','created');

    public function addQuote($data){

        $row = $this->createRow();
        $row->quote     = strip_tags(htmlspecialchars($data['quote']));
        $row->author    = !empty($data['author'])? strip_tags(htmlspecialchars($data['author'])): 'anonymous';
        $row->slug      = $this->slugify(substr($data['quote'], 0, 70));
        $row->visible   = intval($data['visible'])? 1 : 0;
        $row->created   = date('Y-m-d H:i:s', time()+5.5*60*60);

        return $row->save();
    }

	private function slugify($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, '-');

		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		$existingSlugCount = count($this->findBySlug($text.'%'));

		if($existingSlugCount  > 0 ){
			$text.= "-".($existingSlugCount+1);
		}

		return $text;
	}

	public function findBySlug($slug){
		$select=$this->select()
			->where('slug LIKE ?',$slug);
		return $this->fetchAll($select);
	}

	public function findOneBySlug($slug){
		$select=$this->select()
			->where('slug = ?',$slug);
		return $this->fetchRow($select);
	}

	public function exists($quote, $author='Anonymous'){
        $select=$this->select()
                ->where('quote = ?',$quote);
        //return $select;
        $result=$this->fetchAll($select);
        if(count($result)>0){
            return true;
        }else{
            $this->addQuote($quote, $author);
            return false;
        }
    }
	
    public function updateQuote($id, $data){
	    try{

	        $row = $this->find($id)->current();

		    $row->quote     =   strip_tags(htmlspecialchars($data['quote']));
		    $row->author    =   !empty($data['author'])? strip_tags(htmlspecialchars($data['author'])): 'anonymous';
		    $row->slug      =   $this->slugify(substr($data['quote'], 0, 70));
		    $row->visible   =   $data['visible']? 1 : 0;

            return $row->save();

        }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());

            return false;
        }
        
    }
	
	//This function was created to add slug to existing quotes
	//This only needs to be executed after adding the column 'slug'
	//if the db is not empty
    public function addSlugToQuote(){
	    try{

	        $rows = $this->listQuotes();

		    foreach ($rows as $row) {
			    if(empty($row->slug)){
				    $row->slug      =   $this->slugify(substr($row->quote, 0, 70));
				    $row->save();
			    }
		    }

        }catch (Exception $e){
		    $log = new Zend_Log(
			    new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/log')
		    );
		    $log->debug($e->getMessage() . "\n" .
			    $e->getTraceAsString());

            return false;
        }

    }

    public function findById($id){
        return $this->find($id)->current();
    }

    public function countQuotes(){
        $select=$this->select()
                ->from($this->_name, 'COUNT(*) AS num');
        $result=$this->fetchRow($select);
        return $result->num;
    }

    public function listQuotes(){
        $select=$this->select()
            ->order('created DESC');
        return $this->fetchAll($select);
    }


    public function getQuotesByAuthor($author){
        $select=$this->select()
                ->where('author = ?',$author);
        return $this->fetchAll($select);
    }

    public function getAuthorByQuote($quote){
        $select=$this->select()
                ->where('quote = ?',$quote);
        return $this->fetchAll($select);
    }

    public function deleteQuote($id)
    {
	    $row = $this->find($id)->current();
	    if ($row) {
		    $row->delete();
		    return true;
	    } else {
		    throw new Zend_Exception("Delete function failed; could not find page!");
	    }
    }

    public function randomQuote()
    {
	    $length = $this->countQuotes();
	    $select = $this->select()
		    ->where('id = ?', mt_rand(1, $length));
	    return $this->fetchRow($select);
    }

    public function randomQuotes($limit = 10)
    {
	    $select = $this->select()
		            ->order('RAND()')
	                ->limit($limit);
	    return $this->fetchAll($select);
    }

}