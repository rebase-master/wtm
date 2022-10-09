  <?php
class Form_ProfileForm extends Zend_Form{
    public function init(){
    	$this->setMethod('POST');
	$fileUploadElement=new Zend_Form_Element_File('profile-pic');
	$fileUploadElement->setAttrib('enctype', 'multipart/form-data');
	$fileUploadElement->setLabel('Change Picture:');
	$fileUploadElement->setDestination('../public/users');
	$fileUploadElement->addValidator('Count',false,1);
	$fileUploadElement->addValidator('Extension',false,'jpg,png,gif')
//					  ->addValidator(new Zend_Validate_File_ImageSize(array(
//                                        'minheight' => 100, 'minwidth' => 150,
//                                        'maxheight' => 1920, 'maxwidth' => 1200)))
            // File must be below 1.5 Mb
            ->addValidator(new Zend_Validate_File_FilesSize(array('max' => 1572864)));
            //->addValidator(new Zend_Validate_File_IsImage());;

	$this->addElement($fileUploadElement);
	$this->addElement('submit','submit',array('label'=>'Upload Picture'));

	}
	public function photoUpload(){
		
//		$this->setAttrib('enctype', 'multipart/form-data');
//$this->addElement('file', 'files', array(
//    'label'         => 'Pictures',
//    'validators'    => array(
//        array('Count', false, array('min'=>1, 'max'=>3)),
//        array('Size', false, 102400),
//        array('Extension', false, 'jpg,png,gif')
//    ),
//    'multiFile'=>3,
//    'destination'=>APPLICATION_PATH . '/tmp'
//));

	
	}
}