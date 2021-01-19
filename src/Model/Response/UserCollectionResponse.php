<?php

namespace LevelCredit\LevelCreditApi\Model\Response;

use Doctrine\Common\Collections\ArrayCollection;
use LevelCredit\LevelCreditApi\Model\Response\Resource\User;

/**
 * @method ArrayCollection|User[] getElements()
 */
class UserCollectionResponse extends CollectionResponse
{
}
