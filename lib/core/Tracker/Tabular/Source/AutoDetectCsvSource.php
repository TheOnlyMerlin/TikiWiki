<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Tabular\Source;

class AutoDetectCsvSource implements SourceInterface
{
	private $source;

	function __construct(\Tracker_Definition $definition, $fileName)
	{
		$file = new \SplFileObject($fileName, 'r');
		$headers = $file->fgetcsv();

		$schema = new \Tracker\Tabular\Schema($definition);
		
		foreach ($headers as $header) {
			if (preg_match('/\[(\*?)(\w+):([^\]]+)\]$/', $header, $parts)) {
				list($full, $pk, $field, $mode) = $parts;
				$schema->addColumn($field, $mode);

				if ($pk === '*') {
					$schema->setPrimaryKey($field);
				}
			} else {
				// Column without definition, add fake entry to skip column
				$schema->addNew('ignore', 'ignore');
			}
		}

		$this->source = new CsvSource($schema, $fileName);
	}

	function getEntries()
	{
		return $this->source->getEntries();
	}

	function getSchema()
	{
		return $this->source->getSchema();
	}
}

