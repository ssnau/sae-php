<?php
class HelpAction extends ActionClass {
	private static function setPageInfo(){
		App::setVar('page_nav',array('入门' => "/help/intro", 
							'FAQ' => "/help/faq",
							'关于' => '/help/about'));
	}
	public static function actDefault() {
		App::redirect('/help/intro');
	}
	public static function actIntro() {
		self::setPageInfo();
		self::display();
	}
	public static function actAbout() {
		self::setPageInfo();
		self::display();
	}
	public static function actFaq() {
		self::setPageInfo();
		self::display();
	}
}
