<?php
/***************************************************************
 * Copyright notice
*
* (c) 2012 Niels Pardon (mail@niels-pardon.de)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * This class represents a view helper for rendering a countdown.
 *
 * @package TYPO3
 * @subpackage tx_seminars
 *
 * @author Niels Pardon <mail@niels-pardon.de>
 */
class tx_seminars_ViewHelper_Countdown {
	/**
	 * @var tx_oelib_Translator
	 */
	protected $translator = NULL;

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->translator = tx_oelib_TranslatorRegistry::getInstance()->get('seminars');
	}

	/**
	 * Frees as much memory that has been used by this object as possible.
	 */
	public function __destruct() {
		unset($this->translator);
	}

	/**
	 * Returns a localized string representing an amount of seconds in words.
	 * For example:
	 * 150000 seconds -> "1 day"
	 * 200000 seconds -> "2 days"
	 * 50000 seconds -> "13 hours"
	 * The function uses localized strings and also looks for proper usage of singular/plural.
	 *
	 * @param integer $targettime the target UNIX timestamp to count up to, must be >= 0
	 *
	 * @return string a localized string representing the time left until the event starts
	 */
	public function render($targettime) {
		$seconds = $targettime - $GLOBALS['SIM_ACCESS_TIME'];

		if ($seconds >= tx_oelib_Time::SECONDS_PER_DAY) {
			$result = $this->getAsDays($seconds);
		} elseif ($seconds >= tx_oelib_Time::SECONDS_PER_HOUR) {
			$result = $this->getAsHours($seconds);
		} elseif ($seconds >= tx_oelib_Time::SECONDS_PER_MINUTE) {
			$result = $this->getAsMinutes($seconds);
		} else {
			$result = $this->getAsSeconds($seconds);
		}

		return $result;
	}

	/**
	 * Returns the given duration in days.
	 *
	 * @param integer $seconds the duration in seconds, must be >= 0
	 *
	 * @return string the duration in days
	 */
	protected function getAsDays($seconds) {
		$countdownValue = intval(round($seconds / tx_oelib_Time::SECONDS_PER_DAY));
		if ($countdownValue > 1 || $countdownValue === 0) {
			$countdownText = $this->translator->translate('countdown_days_plural');
		} else {
			$countdownText = $this->translator->translate('countdown_days_singular');
		}

		return $this->getFormattedMessage($countdownValue, $countdownText);
	}

	/**
	 * Returns the given duration in hours.
	 *
	 * @param integer $seconds the duration in seconds, must be >= 0
	 *
	 * @return string the duration in hours
	 */
	protected function getAsHours($seconds) {
		$countdownValue = intval(round($seconds / tx_oelib_Time::SECONDS_PER_HOUR));
		if ($countdownValue > 1 || $countdownValue === 0) {
			$countdownText = $this->translator->translate('countdown_hours_plural');
		} else {
			$countdownText = $this->translator->translate('countdown_hours_singular');
		}

		return $this->getFormattedMessage($countdownValue, $countdownText);
	}

	/**
	 * Returns the given duration in minutes.
	 *
	 * @param integer $seconds the duration in seconds, must be >= 0
	 *
	 * @return string the duration in minutes
	 */
	protected function getAsMinutes($seconds) {
		$countdownValue = intval(round($seconds / tx_oelib_Time::SECONDS_PER_MINUTE));
		if ($countdownValue > 1 || $countdownValue === 0) {
			$countdownText = $this->translator->translate('countdown_minutes_plural');
		} else {
			$countdownText = $this->translator->translate('countdown_minutes_singular');
		}

		return $this->getFormattedMessage($countdownValue, $countdownText);
	}

	/**
	 * Returns the given duration in seconds.
	 *
	 * @param integer $seconds the duration in seconds, must be >= 0
	 *
	 * @return string the duration in seconds
	 */
	protected function getAsSeconds($seconds) {
		$countdownValue = $seconds;
		$countdownText = $this->translator->translate('countdown_seconds_plural');

		return $this->getFormattedMessage($countdownValue, $countdownText);
	}

	/**
	 * Returns the formatted countdown message using $countdownValue and $countdownText.
	 *
	 * @param integer $countdownValue
	 * @param string $countdownText
	 *
	 * @return string the formatted countdown message
	 */
	protected function getFormattedMessage($countdownValue, $countdownText) {
		return sprintf($this->translator->translate('message_countdown'), $countdownValue, $countdownText);
	}
}

if (defined('TYPO3_MODE') && $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/seminars/ViewHelper/Countdown.php']) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/seminars/ViewHelper/Countdown.php']);
}