<?php

namespace App\Policies;

use App\Models\Timesheet;
use App\Models\User;

class TimesheetPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given timesheet can be updated by the user.
     */
    public function update(User $user, Timesheet $timesheet): bool
    {
        return $timesheet->user_id === $user->id;
    }

    /**
     * Determine if the given timesheet can be deleted by the user.
     */
    public function delete(User $user, Timesheet $timesheet): bool
    {
        return $timesheet->user_id === $user->id;
    }
}
