<?php

/**
 * @group unit
 * 
 */

//include_once "lib/core/lib/Multilingual/Aligner/SentenceAlignments.php";
//include_once "lib/core/lib/Multilingual/Aligner/UpdateSentences.php";
//include_once "lib/test/TikiTestCase.php";
class  Multilingual_Aligner_UpdatePagesTest extends TikiTestCase
{
	private $orig_source_sentences =
				array(
					"Firefox supports international characters for languages such as Hindi.",
					"You can test your Firefoxs support of Hindi scripts at BBC Hindi.",
					"Most sites that require additional fonts will have a page describing where you can get the font.",
				);
	private $orig_target_sentences =
			array(
				"Firefox supporte les caractères internationaux pour des langues tel que lindien.",
				"Vous pouvez tester le support Firefox des scripts indiens sur BBC indien.",
				"La plupart des sites qui ont besoin de polices supplémentaires vont avoir une page qui décrit ou vous pouvez obtenir la police."
			 	); 
	private $source_alignment="Firefox supports international characters for languages such as Hindi.<br/>You can test your Firefoxs support of Hindi scripts at BBC Hindi.<br/>Most sites that require additional fonts will have a page describing where you can get the font.";
	private $target_alignment="Firefox supporte les caractères internationaux pour des langues tel que lindien.<br/>Vous pouvez tester le support Firefox des scripts indiens sur BBC indien.<br/>La plupart des sites qui ont besoin de polices supplémentaires vont avoir une page qui décrit ou vous pouvez obtenir la police.";	

	private $extra_source_sentence = "This is a test statement.";
	private $extra_target_sentence = "C'est une déclaration d'essai.";
	
	
	protected function setUp()  {
		$this->updater = new Multilingual_Aligner_UpdatePages();
		$this->updater->alignments = new Multilingual_Aligner_SentenceAlignments();
		$this->updater->translator=new Multilingual_Aligner_MockMTWrapper();
	} 


	public function ___test_reminder()  {
		$this->fail("remember to reactivate all tests in UpdateSentences");
	}

	////////////////////////////////////////////////////////////////
	// Documentation tests
	//    These tests illustrate how to use this class.
	////////////////////////////////////////////////////////////////
    
	public function test_this_is_how_you_create_a_UpdatePages() {
		$test = new Multilingual_Aligner_UpdatePages();
	}
	 	 
	////////////////////////////////////////////////////////////////
	// Note: In the rest of these tests, you can assume that 
	//       $this->updater is an instance of UpdatePages
	//       created as above.
	////////////////////////////////////////////////////////////////
	 
	public function test_sentence_added_on_source_side_only() {
		$source_lng = "en";
		$target_lng = "fr";
		
		$source_original_array = $this->orig_source_sentences;
		$source_modified_array = $this->orig_source_sentences;
		$source_modified_array = $this->insertSentenceAtIndex(1, $this->extra_source_sentence, $source_modified_array);

		$target_original_array = $this->orig_target_sentences;
		$target_modified_array = $target_original_array;

		$expected_content = $this->insertSentenceAtIndex(1, "Added_Source ".$this->extra_target_sentence, $target_modified_array);

		$this->do_test_basic_updating(
					$source_lng,$target_lng,
					$source_original_array,$source_modified_array,
					$target_original_array, $target_modified_array, 
					$expected_content, "");

	}


	public function test_sentence_inserted_on_target_side_only() {
		$source_lng = "en";
		$target_lng = "fr";
		
		$source_original_array = $this->orig_source_sentences;
		$source_modified_array = $this->orig_source_sentences;

		$target_original_array = $this->orig_target_sentences;
		$target_modified_array = $target_original_array;
		$target_modified_array = $this->insertSentenceAtIndex(1, $this->extra_target_sentence, $target_modified_array);

		$expected_content = $target_modified_array;

		$this->do_test_basic_updating(
					$source_lng,$target_lng,
					$source_original_array,$source_modified_array,
					$target_original_array, $target_modified_array, 
					$expected_content, "");		
	}
	
	
	public function test_sentence_deleted_on_source_side_only() {
		$source_lng = "en";
		$target_lng = "fr";
		
		$source_original_array = $this->orig_source_sentences;
		$source_modified_array = $this->orig_source_sentences;
		$source_modified_array = $this->removeSentenceAtIndex(1, $source_modified_array);

		$target_original_array = $this->orig_target_sentences;
		$target_modified_array = $target_original_array;

		$expected_content = $target_modified_array;
		$expected_content [1] = "Deleted_Source ".$expected_content [1]; 

		$this->removeSentenceAtIndex(1, $expected_content);

		$this->do_test_basic_updating(
					$source_lng,$target_lng,
					$source_original_array,$source_modified_array,
					$target_original_array, $target_modified_array, 
					$expected_content, "");

	}
	
	public function ___test_This_is_how_you_DeleteSentencefromTargetside() {
		$source_lng="en";
		$target_lng="fr";
		$source_outofdate="Firefox supports international characters for languages such as Hindi. You can test your Firefoxs support of Hindi scripts at BBC Hindi.Most sites that require additional fonts will have a page describing where you can get the font.";
		$source_modified="Firefox supports international characters for languages such as Hindi. You can test your Firefoxs support of Hindi scripts at BBC Hindi.Most sites that require additional fonts will have a page describing where you can get the font.";
		$target_outofdate="Firefox supporte les caract�res internationaux pour des langues tel que lindien. Vous pouvez tester le support Firefox des scripts indiens sur BBC indien.La plupart des sites qui ont besoin de polices suppl�mentaires vont avoir une page qui d�crit o� vous pouvez obtenir la police.";
		$target_modified="Vous pouvez tester le support Firefox des scripts indiens sur BBC indien.La plupart des sites qui ont besoin de polices suppl�mentaires vont avoir une page qui d�crit o� vous pouvez obtenir la police.";
		$source_alignment="Firefox supports international characters for languages such as Hindi.<br/>You can test your Firefoxs support of Hindi scripts at BBC Hindi.<br/>Most sites that require additional fonts will have a page describing where you can get the font.";
		$target_alignment="Firefox supporte les caract�res internationaux pour des langues tel que lindien.<br/>Vous pouvez tester le support Firefox des scripts indiens sur BBC indien.<br/>La plupart des sites qui ont besoin de polices suppl�mentaires vont avoir une page qui d�crit o� vous pouvez obtenir la police.";
		
		$source_Mtranslation="This is a test statement.";
		$target_Mtranslation="C'est une déclaration d'essai.";

		$expected_content = "Deleted_Target Firefox supporte les caract�res internationaux pour des langues tel que lindien. Vous pouvez tester le support Firefox des scripts indiens sur BBC indien. La plupart des sites qui ont besoin de polices suppl�mentaires vont avoir une page qui d�crit o� vous pouvez obtenir la police.";

		$final_updated=$this->do_test_basic_updating($source_alignment,$target_alignment,$source_Mtranslation,$target_Mtranslation,$source_lng,$target_lng,$source_outofdate,$source_modified,$target_outofdate,$target_modified, $expected_content, "");
		
	}

	public function do_test_basic_updating(
						$source_lng,$target_lng,
						$orig_source_array,$modified_source_array,
						$orig_target_array,$modified_target_array,
						$expected_updated_target_array, $message)
	{
		
//		echo "<pre>-- UpdatePagesTest.do_test_basic_updating: \$orig_source_array="; var_dump($orig_source_array); echo "</pre>\n";
//		echo "<pre>-- UpdatePagesTest.do_test_basic_updating: \$modified_source_array="; var_dump($modified_source_array); echo "</pre>\n";
//		echo "<pre>-- UpdatePagesTest.do_test_basic_updating: \$orig_target_array="; var_dump($orig_target_array); echo "</pre>\n";
//		echo "<pre>-- UpdatePagesTest.do_test_basic_updating: \$modified_target_array="; var_dump($modified_target_array); echo "</pre>\n";
//		echo "<pre>-- UpdatePagesTest.do_test_basic_updating: \$expected_updated_target_array="; var_dump($expected_updated_target_array); echo "</pre>\n";
		
		$orig_source = join(' ', $orig_source_array);
		$modified_source = join(' ', $modified_source_array);			
		$orig_target = join(' ', $orig_target_array);
		$modified_target = join(' ', $modified_target_array);


		$this->updater->SetAlignment($this->source_alignment,$this->target_alignment,$source_lng,$target_lng);
		$this->updater->SetMT($this->extra_source_sentence, $this->extra_target_sentence, $source_lng, $target_lng);
		$final=$this->updater->UpdatingTargetPage($orig_source,$modified_source,$orig_target,$modified_target,$source_lng,$target_lng);

//		echo "<pre>-- UpdatePagesTest.do_test_basic_updating: \$final="; var_dump($final); echo "</pre>\n";


		$this->assertEquals($expected_updated_target_array, $final, $message);

	}


	////////////////////////////////////////////////////////////////
	// Helper functions.
	////////////////////////////////////////////////////////////////

	function insertSentenceAtIndex($index, $sentenceToAdd, $sentenceList) {
		$modifiedSentenceList = array();
		$ii;
		for ($ii=0; $ii < count($sentenceList); $ii++) {
			if ($ii == $index) {
				$modifiedSentenceList[] = $sentenceToAdd;
			}
			$modifiedSentenceList[] = $sentenceList[$ii];
		}
		return $modifiedSentenceList;
	}
	
	function removeSentenceAtIndex($index, $sentenceList) {
		array_splice($sentenceList, $index, 1);
		return $sentenceList;
	}

}

