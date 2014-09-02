<?PHP

class qor_controleur {

	var $pmnl_services = array();
	var $pile = array();
	var $donnees = array();
	var $messages = array();
	var $chemin = "./include/";
	
	//
	function actionsDefinir($pmnl_services) {
		$sortie = false;
		
		if(is_array($pmnl_services)) {
			$this->actions = $pmnl_services;
			$sortie = true;
		}
		
		return $sortie;
	}
	
	//
	function cheminDefinir($chemin) {
		$sortie = false;
		
		if (is_dir($chemin)) {
			$this->chemin = $chemin;
			$sortie = true;
		}
	
		return $sortie;
	}
	
	//
	function messageAjouter($nom, $message) {
		$sortie = false;
		
		$this->messages[$nom][] = $message;
		$sortie = true;
		
		return $sortie;
	}
	
	//
	function messageSuivant($nom) {
		$sortie = false;
		
		if (isset($this->messages[$nom])) {
			if (count($this->messages[$nom]) > 0) {
				$message = array_shift($this->messages[$nom]);
				
				if ($message != null) {
					$sortie = $message;
				}
			}
		}
		
		return $sortie;
	}
	
	//
	function pileAjouter($nom, $pmnl_service) {
		$sortie = false;
		
		if (isset($this->actions[$pmnl_service])) {
			$this->pile[$nom][] = $pmnl_service;
			$sortie = true;
		}
		
		return $sortie;
	}
	
	//
	function pileSuivant($nom) {
		$sortie = false;
		
		$pmnl_service = array_shift($this->pile[$nom]);
		
		if ($pmnl_service != null) {
			$sortie = $this->actions[$pmnl_service];
		}
		
		return $sortie;
	}
		
	//
	function documentLire($document, $chemin = false) {
		$sortie = false;
		
		print($chemin . $document);
		
		if (file_exists($chemin . $document)) {
			include($chemin . $document);
			$sortie = true;
		} else {
			echo "EC1";
			$sortie = false;
		}
		
		return $sortie;
	}
	
	//
	function actionLire($document) {
		$sortie = false;
		
		if ($this->documentLire($document, $this->chemin)) {
			$sortie = true;
		} else {
			echo "eer";
			$sortie = false;
		}
		
		return $sortie;
	}
	
	//
	function actionAjouter($nom) {
		$sortie = false;
		
		if (is_string($nom)) {
			$this->flux[] = $nom;
			$sortie = true;
		}
		
		return $sortie;
	}

	//
	function actionExecuter($pmnl_service) {
		$sortie = false;
		
		if (isset($this->actions[$pmnl_service])) {
			$document = $this->actions[$pmnl_service]['document'];
			if($this->actionLire($document)) {
				$sortie = true;
			}
		}
		
		return $sortie;
	}
	
	//
	function actionsExecuter($pile) {
		$sortie = false;
		
		if (isset($this->flux[$pile])) {
			foreach ($this->flux[$pile] as $pmnl_service) {
				$resultat = $this->actionExecuter($pmnl_service);
				$sortie = $resultat;
				if ($resultat === false) {
					break;
				}
			}
		}
		
		return $sortie;
	}
	
}

?>