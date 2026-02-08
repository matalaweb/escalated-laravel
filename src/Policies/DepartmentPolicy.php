<?php

namespace Escalated\Laravel\Policies;

use Escalated\Laravel\Models\Department;
use Illuminate\Support\Facades\Gate;

class DepartmentPolicy
{
    public function viewAny($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function view($user, Department $department): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function create($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function update($user, Department $department): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function delete($user, Department $department): bool
    {
        return Gate::allows('escalated-admin', $user);
    }
}
