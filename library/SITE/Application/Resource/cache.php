<?php
class SITE_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract{
  public function init(){
	
	$options=$this->getOptions();
	//Get a Zend_Cache_Core object
	$cache=Zend_Cache::factory(
	  $options['frontEnd'],
	  $options['backEnd'],
	  $options['frontEndOptions'],
	  $options['backEndOptions']
	);
	Zend_Registry::set('cache',$cache);
	return $cache;
  resources.cache.frontEnd=core
resources.cache.backEnd=file
resources.cache.fronEndOptions.lifetime=1200
resources.cache.frontEndOptions.automatic_serialization=true
resources.cache.backEndOptions.lifetime=3600
resources.cache.backEndOptions.cache_dir=APPLICATION_PATH "/../cache"

  }
}