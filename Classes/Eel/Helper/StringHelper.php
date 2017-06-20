<?php
namespace Ttree\ContentRepositoryUtility\Eel\Helper;

/*
 * This file is part of the Ttree.ContentRepositoryUtility package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Eel\Helper;
use Cocur\Slugify\Slugify;

/**
 * String helpers for Eel contexts
 *
 * @Flow\Proxy(false)
 */
class StringHelper extends Helper\StringHelper
{

    /**
     * @param string $value
     * @return string mixed
     */
    public function slugify($value)
    {
        $slugify = new Slugify();
        return $slugify->slugify($value);
    }

}
