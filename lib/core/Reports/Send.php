<?php
// (c) Copyright 2002-2012 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Send e-mail reports to users with changes in Tiki
 * in a given period of time.
 * 
 * @package Tiki
 * @subpackage Reports
 */
class Reports_Send
{
	protected $dt;
	
	protected $mail;
	
	/**
	 * @param DateTime $dt
	 * @param TikiMail $mail
	 * @return null
	 */
	public function __construct(DateTime $dt, TikiMail $mail)
	{
		$this->dt = $dt;
		$this->mail = $mail;
	}
	
	public function sendEmail($userData, $reportPreferences, $reportCache) 
	{
		$mailData = $this->buildEmailBody($userData, $reportPreferences, $reportCache);
				
		$this->mail->setUser($userData['login']);

		$this->setSubject($reportCache);
		
		if ($reportPreferences['type'] == 'plain') {
			$this->mail->setText($mailData);
		} else {
			$this->mail->setHtml(nl2br($mailData));
		}
		
		$this->mail->buildMessage();
		$this->mail->send(array($userData['email']));
	}
		
	protected function setSubject($reportCache)
	{
		if (is_array($reportCache)) {
			if (count($reportCache) == 1) {
				$subject = tr(
								'Report from %0 (1 change)',
								TikiLib::date_format($this->tikiPrefs['short_date_format'], $this->dt->getTimestamp())
				);
			} else {
				$subject = tr(
								'Report from %0 (%1 changes)',
								TikiLib::date_format($this->tikiPrefs['short_date_format'], $this->dt->getTimestamp()), count($reportCache)
				);
			}
		} else {
			$subject = tr(
							'Report from %0 (no changes)',
							TikiLib::date_format($this->tikiPrefs['short_date_format'], $this->dt->getTimestamp())
			);
		}

		$this->mail->setSubject($subject);
	}	
}
