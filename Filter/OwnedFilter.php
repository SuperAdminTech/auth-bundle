<?php


namespace SuperAdmin\Bundle\Filter;


use SuperAdmin\Bundle\Entity\Compose\Owned;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use InvalidArgumentException;

final class OwnedFilter extends SQLFilter
{

    /**
     * @inheritDoc
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        if(! is_subclass_of($targetEntity->name, Owned::class)) {
            return '';
        }

        $fieldName = 'owner_id';
        try {
            // Don't worry, getParameter automatically escapes parameters
            $encodedPermissions = $this->getParameter('permissions');
            if (empty($encodedPermissions)) {
                return '';
            }
            // Decoding parameter
            $permissions = json_decode(base64_decode($encodedPermissions));
        } catch (InvalidArgumentException $e) {
            // No user id has been defined
            return '';
        }

        $sqlParts = [];
        foreach ($permissions as $permission) {
            $sqlParts []= sprintf("%s.%s = '%s'", $targetTableAlias, $fieldName, $permission->account->id);
        }
        return implode(" OR ", $sqlParts);
    }

}