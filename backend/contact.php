<?php
/**
 * Copyright (c) 2014 Georg Ehrke <oc.list@georgehrke.com>
 * Copyright (c) 2014 Thomas Tanghus <thomas@tanghus.net>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
namespace OCA\Calendar\Backend;

use \OCA\Calendar\Db\Calendar;
use \OCA\Calendar\Db\CalendarCollection;

use \OCA\Calendar\Db\Object;
use \OCA\Calendar\Db\ObjectCollection;

use \OCA\Calendar\Db\TimeZone;
use \OCA\Calendar\Db\TimeZoneCollection;

use \OCA\Calendar\Db\ObjectType;
use \OCA\Calendar\Db\Permissions;

class Contact extends Backend {

	public $calendarURIs;

	public function __construct($api, $parameters){
		parent::__construct($api, 'Contact');

		$this->calendarURIs = array(
			'anniversary',
			'birthday',
		);
	}

	/**
	 * @brief returns whether or not a backend can be enabled
	 * @returns boolean
	 * 
	 * This method returns a boolean.
	 * This method is mandatory!
	 */
	public function canBeEnabled() {
		return true;//return \OCP\App::isEnabled('contacts');
	}

	/**
	 * @brief returns whether or not calendar objects should be cached
	 * @param string $calendarURI
	 * @param string $userId
	 * @returns boolean
	 * 
	 * This method returns a boolen.
	 * This method is mandatory!
	 */
	public function cacheObjects($uri, $userId) {
		return false;
	}

	/**
	 * @brief returns information about calendar $calendarURI of the user $userId
	 * @param string $calendarURI
	 * @param string $userId
	 * @returns array with \OCA\Calendar\Db\Calendar object
	 * @throws DoesNotExistException if uri does not exist
	 * 
	 * This method returns an \OCA\Calendar\Db\Calendar object.
	 * This method is mandatory!
	 */
	public function findCalendar($calendarURI, $userId) {
		if(!$this->doesCalendarExist($calendarURI, $userId)) {
			$msg  = 'Backend\Contact::findCalendar(): ';
			$msg .= '"' . $calendarURI . '" doesn\'t exist';
			throw new DoesNotExistException($msg);
		}

		$calendar = new Calendar();
		$calendar->setUserId($userId);
		$calendar->setOwnerId($userId);
		$calendar->setBackend($this->backend);
		$calendar->setUri($calendarURI);
		$calendar->setComponents(ObjectType::EVENT);
		$calendar->setCtag(1); //sum of all addressbook ctags
		$calendar->setTimezone(new TimeZone('UTC'));
		$calendar->setCruds(Permissions::READ + Permissions::SHARE);
		$calendar->setColor('#ffffff');
		$calendar->setOrder(0);
		$calendar->setEnabled(true);

		if($calendarURI === 'anniversary') {
			$calendar->setDisplayname('Anniversary'); //TODO - use translation
		} elseif($calendarURI === 'birthday') {
			$calendar->setDisplayname('Birthday'); //TODO - use translation
		}

		return $calendar;
	}

	/**
	 * @brief returns all calendars of the user $userId
	 * @param string $userId
	 * @returns \OCA\Calendar\Db\CalendarCollection
	 * @throws DoesNotExistException if uri does not exist
	 * 
	 * This method returns an \OCA\Calendar\Db\CalendarCollection object.
	 * This method is mandatory!
	 */
	public function findCalendars($userId, $limit=null, $offset=null) {
		$calendars = new CalendarCollection();

		foreach($this->calendarURIs as $uri) {
			$calendars->add($this->findCalendar($uri, $userId));
		}

		return $calendars;
	}

	/**
	 * @brief returns number of calendar
	 * @param string $userid
	 * @returns integer
	 * 
	 * This method returns an integer
	 * This method is mandatory!
	 */
	public function countCalendars($userId) {
		return 1;
	}

	/**
	 * @brief returns whether or not a calendar exists
	 * @param string $calendarURI
	 * @param string $userid
	 * @returns boolean
	 * 
	 * This method returns a boolean
	 * This method is mandatory!
	 */
	public function doesCalendarExist($calendarURI, $userId) {
		if(in_array($calendarURI, $this->calendarURIs)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @brief returns information about the object (event/journal/todo) with the uid $objectURI in the calendar $calendarURI of the user $userId 
	 * @param string $calendarURI
	 * @param string $objectURI
	 * @param string $userid
	 * @returns \OCA\Calendar\Db\Object object
	 * @throws DoesNotExistException if calendar does not exist
	 * @throws DoesNotExistException if object does not exist
	 *
	 * This method returns an \OCA\Calendar\Db\Object object.
	 * This method is mandatory!
	 */
	public function findObject(Calendar &$calendar, $objectURI) {
		//TODO implement
		throw new DoesNotExistException();
	}

	/**
	 * @brief returns all objects in the calendar $calendarURI of the user $userId
	 * @param string $calendarURI
	 * @param string $userId
	 * @returns \OCA\Calendar\Db\ObjectCollection
	 * @throws DoesNotExistException if calendar does not exist
	 * 
	 * This method returns an \OCA\Calendar\Db\ObjectCollection object.
	 * This method is mandatory!
	 */
	public function findObjects(Calendar &$calendar, $limit, $offset) {
		//TODO implement
		return new ObjectCollection();
	}
}