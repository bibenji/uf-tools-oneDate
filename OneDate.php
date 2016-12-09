<?php

/*
getDatetime() -> DateTime
getStringtime(1) -> Lundi 1er Janvier à 14h00
getStringtime(2) -> 01/01/2016 à 14:00
getStringdate(1) -> Lundi 1er Janvier
getStringdate(2) -> 01/01/2016
getStringhour(1) -> 14h00
getStringhour(2) -> 14:00

isPast() -> yes or no (true | false)

getPasttime(1) -> Temps écoulé ou temps restant (plus grande unité)
getPasttime(2) -> Temps écoulé ou temps restant (exact)

getDiffWith($otherDate) -> diff entre deux dates
*/

class OneDate
{	
	private $days = array(
		"Sun" => "Dimanche",
		"Mon" => "Lundi",
		"Tue" => "Mardi",
		"Wed" => "Mercredi",
		"Thu" => "Jeudi",
		"Fri" => "Vendredi",
		"Sat" => "Samedi"		
	);
	
	private $months = array(
		"01" => "Janvier",
		"02" => "Février",
		"03" => "Mars",
		"04" => "Avril",
		"05" => "Mai",
		"06" => "Juin",
		"07" => "Juillet",
		"08" => "Aout",
		"09" => "Septembre",
		"10" => "Octobre",
		"11" => "Novembre",
		"12" => "Décembre",
	);
	
	protected $date;
	
	// crée une date à partir d'un DateTime ou d'un string
	public function __construct($date) {
		if ($date instanceof DateTime) {
			$this->date = $date;
		}
		else {
			$this->date = date_create_from_format('Y-m-d H:i:s', $date);
		}		
	}
	
	public function getDatetime() {
		return $this->date;
	}
	
	public function getStringtime($mode) {		
		return $this->getStringdate($mode).' à '.$this->getStringhour($mode);
	
		/*
		$day = $this->days[$this->date->format('D')];
		$month = $this->months[$this->date->format('m')];
		
		return $day.' '.$this->date->format('d').' '.$month.' à '.$this->date->format('H\hi');		
		*/
	}
	
	public function getStringdate($mode) {
		if ($mode === 1) {
			$month = $this->months[$this->date->format('m')];
			$day = $this->days[$this->date->format('D')];
			
			if ($this->date->format('d') == 1) {
				$nbDay = '1er';			
			}
			else $nbDay = $this->date->format('d');
			
			return $day.' '.$nbDay.' '.$month;
		}
		else {
			return $this->date->format('d').'/'.$this->date->format('m').'/'.$this->date->format('Y');
		}
		
	}
	
	public function getStringhour($mode) {
		if ($mode === 1) {
			return date_format($this->date, 'H\hi');
		}
		else {
			return date_format($this->date, 'H:i');
		}		
	}
	
	public function getEnglishdate() {
		return date_format($this->date, 'l d F');
	}
	
	/* OTHERS */
	
	public function isPast() {
		$now = new \DateTime();
		
		return $now > $this->date;
	}
	
	public function getPasttime($mode) {
				
		$now = new \DateTime();
		$interval = $this->date->diff($now);		
		
		return $this->calcDiff($interval, $mode);
		
	}
	
	public function getDiffWith($otherDate, $mode) {
		
		if (is_string($otherDate)) {
			$otherDate = date_create_from_format('Y-m-d H:i:s', $otherDate);
		}
		
		$interval = $this->date->diff($otherDate);
		
		return $this->calcDiff($interval, $mode);		
	}
	
	private function calcDiff($interval, $mode) {
		
		$intervalTexte = '';
		
		if($mode === 1) {
			if ($interval->y >= 1) $intervalTexte = "Plus d'un an";
			else {
				if ($interval->m >= 1) $intervalTexte = $interval->m." mois";
				else {
					if ($interval->d >= 1) $intervalTexte = $interval->d." jour(s)";
					else {
						if ($interval->h >= 1) $intervalTexte = $interval->h." heure(s)";
						else {
							if ($interval->i >= 1) $intervalTexte = $interval->i." minute(s)";
							else {
								if ($interval->s >= 1) $intervalTexte = "Maintenant";
								else $intervalTexte = "Erreur de calcul";
							}			
						}
					}
				}
			}
		}
		else {
			if ($interval->y >= 1) $intervalTexte .= $interval->y." an(s) ";
			if ($interval->m >= 1) $intervalTexte .= $interval->m." mois ";
			if ($interval->d >= 1) $intervalTexte .= $interval->d." jour(s) ";
			if ($interval->h >= 1) $intervalTexte .= $interval->h." heure(s) ";
			if ($interval->i >= 1) $intervalTexte .= $interval->i." minute(s) ";
			if ($interval->s >= 1) $intervalTexte .= $interval->s." seconde(s)";
		}
	
		return $intervalTexte;	
	}
	
	public function getTime($format = null) {
		
		switch ($format) {	
			case 'H1':				
				return $this->getStringhour(1);
				break;			
			case 'H2':
				return $this->getStringhour(2);
				break;
			case 'D1':
				return $this->getStringdate(1);
				break;
			case 'D2':
				return $this->getStringdate(2);
				break;
			case 'T1':
				return $this->getStringtime(1);
				break;
			case 'T2':
				return $this->getStringtime(2);
				break;
			default:
				return $this->getDatetime();
			
		}
	}
}