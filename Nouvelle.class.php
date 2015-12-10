<?php
class nouvelle {
		private $titre;			//	Titre de la nouvelle
		private $description;	//	Description de la nouvelle
		private $link;			//	URL de la nouvelle
		private $date;			// 	Date de publication
		private $image;			//	URL (ou pas ?) de l'image


        // Contructeur
        // AH FAIRE APRES


        // Getters
		function titre() {
			return $this->titre;
		}

		function description() {
			return $this->description;
		}

		function link() {
			return $this->link;
		}

		function date() {
			return $this->date;
		}

		function image() {
			return $this->image;
		}

		function update(DOMElement $item) {

			$nodeList = $item->getElementsByTagName('title');
			$this->titre    = $nodeList->item(0)->textContent;

			$nodeList = $item->getElementsByTagName('pubDate');
			$this->date  = $nodeList->item(0)->textContent;

			$nodeList = $item->getElementsByTagName('description');
			$this->description  = $nodeList->item(0)->textContent;

			$nodeList = $item->getElementsByTagName('link');
			$this->link  = $nodeList->item(0)->textContent;


		}

		function downloadImage(DOMElement $item, $imageId) {
			$nodeList = $item->getElementsByTagName('enclosure');
			if($nodeList->length != 0) {
				$node = $nodeList->item(0);

				$node = $node->attributes->getNamedItem('url');
				if ($node != NULL) {
					$url = $node->nodeValue;
					$this->image = './images/'.$imageId.'.jpg';
					file_put_contents($this->image, file_get_contents($url));
				} else {
					$this->image = "";
				}
			}
		}

	}

	?>