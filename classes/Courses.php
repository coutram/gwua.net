<?php
class Courses {

	public static $christain_studies = array(
		'name' => 'College of Spiritual Studies',
		'divisions' => array(
			'division_biblical_studies' => array(
				'name' => 'Division of Biblical Studies and Theology',
				'degrees' => array(
					'ma_biblical_studies' => array('name' => 'Master of Arts in Biblical Studies'),
					'ma_ot_studies' => array('name' => 'Master of Arts in Old Testament Studies'),
					'ma_nt_studies' => array('name' => 'Master of Arts in New Testament Studies'),
					'ma_biblical_languages' => array('name' => 'Master of Arts in Biblical Languages'),
					'ma_theology' => array('name' => 'Master of Arts in Theology'),
					'ma_systematic_theology' => array('name' => 'Master of Arts in Systematic Theology'),
					'ma_historical_theology' => array('name' => 'Master of Arts in Historical Theology'),
					'ma_contemporary_theology' => array('name' => 'Master of Arts in Contemporary Theology'),	
				)
			),
			'division_christian_ministry' => array(
				'name' => 'Division of Christian Ministry',
				'degrees' => array(
					'ma_christian_ministry' => array('name' => 'Master of Arts in Christain Ministry'),
					'ma_christian_education' => array('name' => 'Master of Arts in Christain Education'),
				),
			),
			'division_christian_counseling' => array(
				'name' => 'Division of Christian Counseling and Psychology',
				'degrees' => array(
					'ma_christain_counseling' => array('name' => 'Master of Arts in Christian Counseling')		
				)
			),
		),
	);
	
	public static $guidance_physc = array(
		'name' => 'College Of Guidance and Counseling Psychology',
		'degrees' => array(
			'ms_guidance_counseling' => array('name' => 'Masters of Science in School Guidance and Counseling Psychology'),
			'ms_counseling' => array('name' => 'Masters of Science in General Counseling Program'),
			'ma_christian_counseling' => array('name' => 'Masters of Science in Christian Counseling and Psychology'),
		),
	);
	
	public static $center_individual_excellence = array (
		'name' => 'Center for Individual Excellence',
		'degrees' => array(
			'cmmp' => array('name' => 'Medical Office Manager Training & Certification'),
		)		
	);
}
