<?php


namespace SuperAdmin\Bundle\Filter;


use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use InvalidArgumentException;
use SuperAdmin\Bundle\Security\AccountOwned;
use SuperAdmin\Bundle\Security\User;
use SuperAdmin\Bundle\Security\UserOwned;

final class OwnedFilter extends SQLFilter
{

    /**
     * @inheritDoc
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        $checkAccountFilter = is_subclass_of($targetEntity->name, AccountOwned::class);
        $checkUserFilter = is_subclass_of($targetEntity->name, UserOwned::class);

        if(!$checkAccountFilter && !$checkUserFilter) {
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

        $sqlUserFilter = false;
        if ($checkUserFilter) {
            $sqlUserFilter = sprintf("%s.user_id = '%s'", $targetTableAlias, $user->id);
        }

        $sqlAccountFilter = false;
        if ($checkAccountFilter) {
            $sqlParts = [];
            foreach ($user->permissions as $permission) {
                if (in_array(User::ACCOUNT_MANAGER, $permission->grants)) {
                    $sqlParts [] = sprintf("%s.account_id = '%s'", $targetTableAlias, $permission->account->id);
                }
            }
            $sqlAccountFilter = implode(" OR ", $sqlParts);
        }

        if ($sqlAccountFilter && $sqlUserFilter) return $sqlUserFilter . ' OR ' . $sqlAccountFilter;
        if($sqlUserFilter) return $sqlUserFilter;
        return $sqlAccountFilter;
    }

}