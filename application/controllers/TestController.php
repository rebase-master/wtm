<?php

class TestController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
//	    $this->_helper->layout->disableLayout();

//	    $front = Zend_Controller_Front::getInstance();
//	    $routes = $front->getRouter()->getRoutes();
//	    foreach ($routes as $routeName => $route) {
//		    echo $routeName . ': ' . get_class($route) . "<br />\n";
////		    var_dump($route);
//	    }
////	    var_dump($front->getRouter()->getRoutes());
    }
    public function sitemapAction()
    {
//	    $this->_helper->layout->disableLayout();

//	    $front = Zend_Controller_Front::getInstance();
//	    $routes = $front->getRouter()->getRoutes();
//	    foreach ($routes as $routeName => $route) {
//		    echo $routeName . ': ' . get_class($route) . "<br />\n";
////		    var_dump($route);
//	    }
////	    var_dump($front->getRouter()->getRoutes());


//	    $this
//		    ->navigation()
//		    ->sitemap()
//		    ->setFormatOutput(true)
//		    ->setMaxDepth(2)
//	    ;
//	    echo $this->navigation()->sitemap();
//
//
// XML-related routine - <urlset>
	    $xml = new DOMDocument('1.0', 'utf-8');
	    $masterRoot = $xml->createElement('urlset');
	    $xml->appendChild($masterRoot);

	    $data = array();
        $front = Zend_Controller_Front::getInstance();
	    $routes = $front->getRouter()->getRoutes();
	    foreach ($routes as $routeName => $route) {
//		    echo $routeName . ': ' . get_class($route) . "<br />\n";
		    array_push($data,
			        array('lastmod'=> date("Y-m-d"),'loc' => BASE_URL.$routeName ,'changefreq' =>'daily','priority' =>'1.00')
			    );
	    }

	    $this->_url($xml,$masterRoot,$data);
	    $output = $xml->saveXML();

	    $xml->save("/sitemap.xml" );

	    // Both layout and view renderer should be disabled
	    Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
	    Zend_Layout::getMvcInstance()->disableLayout();

	    // Setting up headers and body
	    $this->_response->setHeader('Content-Type', 'text/xml; charset=utf-8')->setBody($output);
    }


	protected function _url($xml,$masterRoot,$allData)
	{
		foreach($allData as $data)
		{
			// <url>
			$root = $xml->createElement('url');
			$masterRoot->appendChild($root);
			//<loc>http://www.example.com/</loc>
			$elem = $xml->createElement('loc');
			$root->appendChild($elem);
			$elemtext = $xml->createTextNode($data['loc']);
			$elem->appendChild($elemtext);
			//<lastmod>2005-01-01</lastmod>
			$elem = $xml->createElement('lastmod');
			$root->appendChild($elem);
			$elemtext = $xml->createTextNode($data['lastmod']);
			$elem->appendChild($elemtext);
			//<changefreq>monthly</changefreq>
			$elem = $xml->createElement('changefreq');
			$root->appendChild($elem);
			$elemtext = $xml->createTextNode($data['changefreq']);
			$elem->appendChild($elemtext);
			//<priority>0.8</priority>
			$elem = $xml->createElement('priority');
			$root->appendChild($elem);
			$elemtext = $xml->createTextNode($data['priority']);
			$elem->appendChild($elemtext);

		}

	}
}



