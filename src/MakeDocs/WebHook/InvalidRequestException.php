<?php
/**
 * This file is part of MakeDocs. An application created by Pixel Polishers.
 *
 * @copyright Copyright (c) 2012-2013 Pixel Polishers. All rights reserved.
 * @license https://github.com/pixelpolishers/makedocs
 */

namespace MakeDocs\WebHook;

use RuntimeException;

/**
 * The InvalidRequestException exception is thrown once an invalid request is done to a webhook.
 */
class InvalidRequestException extends RuntimeException
{
}
