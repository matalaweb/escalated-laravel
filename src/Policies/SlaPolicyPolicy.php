<?php

namespace Escalated\Laravel\Policies;

use Escalated\Laravel\Models\SlaPolicy;
use Illuminate\Support\Facades\Gate;

class SlaPolicyPolicy
{
    public function viewAny($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function view($user, SlaPolicy $slaPolicy): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function create($user): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function update($user, SlaPolicy $slaPolicy): bool
    {
        return Gate::allows('escalated-admin', $user);
    }

    public function delete($user, SlaPolicy $slaPolicy): bool
    {
        return Gate::allows('escalated-admin', $user);
    }
}
