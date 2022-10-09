<?php

class ProgrammingInJavaController extends Zend_Controller_Action
{

    public function init()
    {
    }

	public function forLoopAction()
	{
		return $this->_redirect('tutorials/for-loop-in-java');
	}

	public function seriesAction()
	{
		return $this->_redirect('category/series');
	}

	public function patternsAction()
	{
		return $this->_redirect('category/patterns');

	}

	public function typesOfNumbersAction()
	{
		return $this->_redirect('category/types-of-numbers');
	}

	public function whileLoopAction()
	{
		return $this->_redirect('tutorials/while-loop-in-java');
	}

	public function doWhileLoopAction()
	{
		return $this->_redirect('category/do-while-loop');
	}

	public function array1DAction()
	{
		return $this->_redirect('category/arrays-1d');
	}

	public function generalAction()
	{
		return $this->_redirect('category/general');
	}

	public function searchAction()
	{
		return $this->_redirect('category/searching');
	}

	public function sortAction()
	{
		return $this->_redirect('category/sorting');
	}

	public function stringsBasicAction()
	{
		return $this->_redirect('category/strings-basic');
	}


	public function stringsAdvancedAction()
	{
		return $this->_redirect('category/strings-advanced');
	}

	public function array2DAction()
	{
		return $this->_redirect('tutorials/double-dimension-array-in-java');
	}

	public function recursiveFunctionAction()
	{
		return $this->_redirect('tutorials/recursive-function-in-java');
	}

	public function functionsAction()
	{
		return $this->_redirect('tutorials/functions-in-java');
	}

	public function stringsAction()
	{
		return $this->_redirect('tutorials/immutable-strings-in-java');
	}

	public function array2dQuestionsAction()
	{
		return $this->_redirect('category/arrays-2d');
	}

	public function functionOverloadingAction()
	{
		return $this->_redirect('tutorials/function-overloading-in-java');
	}


}





