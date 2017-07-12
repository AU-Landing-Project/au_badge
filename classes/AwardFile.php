<?php

/**
 * Override the ElggFile
 */
class AwardFile extends ElggFile {
	protected function  initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "award";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}

	public function delete() {

		$thumbnails = array($this->thumbnail, $this->smallthumb, $this->largethumb, $this->openbadge);
		foreach ($thumbnails as $thumbnail) {
			if ($thumbnail) {
				$delbadge = new ElggFile();
				$delbadge->owner_guid = $this->owner_guid;
				$delbadge->setFilename($thumbnail);
				$delbadge->delete();
			}
		}

		return parent::delete();
	}
}
