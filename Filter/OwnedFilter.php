<?php


namespace SuperAdmin\Bundle\Filter;


use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use InvalidArgumentException;
use SuperAdmin\Bundle\Security\AccountOwned;
use SuperAdmin\Bundle\Security\User;

final class OwnedFilter extends SQLFilter
{

    /**
     * @inheritDoc
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        if(! is_subclass_of($targetEntity->name, AccountOwned::class)) {
            return '';
        }

        try {
            // Don't worry, getParameter automatically escapes parameters
            $encodedUser = $this->getParameter('user');
            if (empty($encodedUser)) {
                return '';
            }
            // Decoding user
            $user = json_decode(base64_decode($encodedUser));
        } catch (InvalidArgumentException $e) {
            // No user id has been defined
            return '';
        }

        $sqlUserFilter = sprintf("%s.user_id = '%s'", $targetTableAlias, $user->id);

        $sqlParts = [];
        foreach ($user->permissions as $permission) {
            if (in_array(User::ACCOUNT_MANAGER, $permission->account->grants)) {
                $sqlParts [] = sprintf("%s.account_id = '%s'", $targetTableAlias, $permission->account->id);
            }
        }
        $sqlAccountFilter = implode(" OR ", $sqlParts);

        return $sqlUserFilter . ' OR ' . $sqlAccountFilter;
    }

}