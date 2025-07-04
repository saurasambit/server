<?php

/**
 * SPDX-FileCopyrightText: 2019-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Files_Trashbin\Command;

use OC\Command\FileAccess;
use OCA\Files_Trashbin\Trashbin;
use OCP\Command\ICommand;
use OCP\IUserManager;
use OCP\Server;

class Expire implements ICommand {
	use FileAccess;

	/**
	 * @param string $user
	 */
	public function __construct(
		private $user,
	) {
	}

	public function handle() {
		$userManager = Server::get(IUserManager::class);
		if (!$userManager->userExists($this->user)) {
			// User has been deleted already
			return;
		}

		\OC_Util::tearDownFS();
		\OC_Util::setupFS($this->user);
		Trashbin::expire($this->user);
		\OC_Util::tearDownFS();
	}
}
