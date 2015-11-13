<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tiki\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FilesBatchuploadCommand extends Command
{
	protected function configure()
	{
		$this
			->setName('files:batchupload')
			->setDescription('Batch upload files into the file galleries')
			->addArgument(
				'galleryId',
				InputArgument::OPTIONAL,
				'Destination gallery for uploads'
			)
			->addOption(
				'subToDesc',
				null,
				InputOption::VALUE_NONE,
				'Use last sub-directory name as description'
			)
			->addOption(
				'subdirToSubgal',
				null,
				InputOption::VALUE_NONE,
				'Move the file into a gallery matching the subdirectory name'
			)
			->addOption(
				'createSubgals',
				null,
				InputOption::VALUE_NONE,
				'Create missing sub galleries'
			)
			->addOption(
				'confirm',
				null,
				InputOption::VALUE_NONE,
				'Perform the batch upload'
			)
		;
	}	

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		global $prefs;

		if ($prefs['feature_file_galleries'] != 'y' || $prefs['feature_file_galleries_batch'] != 'y' || ! is_dir($prefs['fgal_batch_dir'])) {
			throw new \Exception("Feature Batch Uploading not set up, please refer to https://doc.tiki.org/Batch+Upload for instructions");
		}

		$filegallib = \TikiLib::lib('filegal');
		$filegalbatchlib = \TikiLib::lib('filegalbatch');

		$galleryId = (int) $input->getArgument('galleryId');
		if (empty($galleryId)) {
			$galleryId = $prefs['fgal_root_id'];
		}

		$gal_info = $filegallib->get_file_gallery($galleryId);
		if (! $gal_info) {
			throw new \Exception("Files Batch Upload: Gallery #$galleryId not found");
		}

		$confirm = $input->getOption('confirm');

		$files = $filegalbatchlib->batchUploadFileList();
		if (! $files) {
			$output->writeln('<comment>No files to upload</comment>');
			return;
		}

		$subdirToSubgal = $input->getOption('subdirToSubgal');
		if ($subdirToSubgal) {
			$createSubgals = $input->getOption('createSubgals');
		} else {
			$createSubgals = false;
		}

		if ($confirm) {
			$output->writeln('<comment>Files Batch Upload starting...</comment>');

			foreach ($files as & $file) {
				$file = $file['file'];
			}

			$feedback = $filegalbatchlib->processBatchUpload($files, $galleryId, [
					'subToDesc' => $input->getOption('subToDesc'),
					'subdirToSubgal' => $subdirToSubgal,
					'createSubgals' => $createSubgals,
			]);

			foreach ($feedback as $message) {
				if (strpos($message, '<span class="text-danger">') !== false) {
					$error = true;
				} else {
					$error = false;
				}
				$message = strip_tags(str_replace('<br>', ' : ', $message));
				if ($error) {
					$message = "<error>$message</error>";
				} else {
					$message = "<info>$message</info>";
				}
				$output->writeln($message);
			}

			$output->writeln('<comment>Files Batch Upload complete</comment>');
		} else {
			$output->writeln("<comment>Files to upload from {$prefs['fgal_batch_dir']} to gallery #$galleryId</comment>");

			foreach($files as $file) {
				$fname = substr($file['file'], strlen($prefs['fgal_batch_dir']) + 1);
				if (! $file['writable']) {
					$fname = "<error>$fname</error>";
				}
				$output->writeln("  {$fname} ({$file['size']} bytes)");
			}
			$output->writeln("<info>Use the --confirm option to proceed with the upload operation.</info>");
		}

	}
}
