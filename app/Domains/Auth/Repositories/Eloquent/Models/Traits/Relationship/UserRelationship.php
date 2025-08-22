<?php

namespace App\Domains\Auth\Repositories\Eloquent\Models\Traits\Relationship;

use App\Domains\Auth\Models\SocialAccount;
use App\Domains\Auth\Repositories\Eloquent\Models\PasswordHistory;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function passwordHistories()
    {
        return $this->morphMany(PasswordHistory::class, 'model');
    }

}
